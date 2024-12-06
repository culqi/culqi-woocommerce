<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Gateway_Culqi extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'culqi';
        $this->icon = PLUGIN_CULQI_URL . 'assets/images/cards.svg';
        $this->has_fields = true;
        $this->method_title = 'Culqi Payment';
        $this->method_description = 'Acepta pagos con tarjetas de débito y crédito, Yape, Cuotéalo BCP y PagoEfectivo (billeteras móviles, agentes y bodegas).';
        $this->init_form_fields();
        $this->init_settings();
        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
    }

    public function init_form_fields()
    {
        $this->form_fields = array(
            'enabled' => array(
                'title' => 'Enable/Disable',
                'label' => 'Enable Culqi Payment Gateway',
                'type' => 'checkbox',
                'default' => 'no',
            ),
            'title' => array(
                'title' => 'Title',
                'type' => 'text',
                'default' => 'Culqi Payment',
            ),
            'description' => array(
                'title' => 'Description',
                'type' => 'textarea',
                'default' => 'Pay securely using your Culqi account.',
            ),
        );
    }

    public function process_payment($order_id)
    {
        $token = generate_token();
        $order = wc_get_order($order_id);
        $order_key = $order->get_order_key();
        $shop_domain = get_site_url();
        $api_url = CULQI_API_URL . 'shopify/public/save-order';
        $platform = "woocommerce";

        $body = array(
            "id" => $order_id,
            "platform" => $platform,
            "gid" => "gid://woocommerce/PaymentSession/" . $order_id,
            "amount" => number_format($order->get_total(), 2, '.', ''),
            "currency" => $order->get_currency(),
            "proposed_at" => date('Y-m-d\TH:i:s'),
            "kind" => "sale",
            "test" => true,
            "payment_method" => array(
                "type" => "offsite",
                "data" => array(
                    "cancel_url" => wc_get_checkout_url()
                )
            ),
            "customer" => array(
                "billing_address" => array(
                    "given_name" => $order->get_billing_first_name(),
                    "family_name" => $order->get_billing_last_name(),
                    "line1" => $order->get_billing_address_1(),
                    "line2" => $order->get_billing_address_2(),
                    "city" => $order->get_billing_city(),
                    "postal_code" => $order->get_billing_postcode(),
                    "province" => $order->get_billing_state(),
                    "country_code" => $order->get_billing_country()
                ),
                "shipping_address" => array(
                    "given_name" => $order->get_shipping_first_name(),
                    "family_name" => $order->get_shipping_last_name(),
                    "line1" => $order->get_shipping_address_1(),
                    "line2" => $order->get_shipping_address_2(),
                    "city" => $order->get_shipping_city(),
                    "postal_code" => $order->get_shipping_postcode(),
                    "province" => $order->get_shipping_state(),
                    "country_code" => $order->get_shipping_country()
                ),
                "email" => $order->get_billing_email(),
                "locale" => "en-PE"
            ),
            "cancel_url" => wc_get_checkout_url(),
            "merchant_locale" => "en-PE",
            "shop_domain" => $shop_domain,
            "order_key" => $order_key,
        );

        $response = wp_remote_post($api_url, array(
            'method'    => 'POST',
            'body'      => json_encode($body),
            'timeout'   => 45,
            'headers'   => array(
                'Content-Type' => 'application/json',
                'shopify-shop-domain' => $shop_domain,
                'authorization' => 'Bearer '. $token,
            ),
        ));

        if (is_wp_error($response)) {
            wc_add_notice(__('Payment error: Could not connect to the payment gateway.', 'culqi-payment'), 'error');
            return;
        }

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);

        if (isset($result['redirect_url'])) {
            $gateway_url = $result['redirect_url'];
        } else {
            wc_add_notice(__('Payment error: Invalid response from payment gateway.', 'culqi-payment'), 'error');
            return;
        }

        $order->update_status('on-hold', __('Awaiting payment', 'culqi-payment'));
        // $order->update_meta_data('culqi_redirect_url', $gateway_url);
        $order->save();
        //WC()->cart->empty_cart();

        return array(
            'result'     => 'success',
            'show_modal' => true,
            'redirect'   => $gateway_url
        );
    }
}
