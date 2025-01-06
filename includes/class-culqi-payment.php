<?php

if (!defined('ABSPATH')) {
    exit;
}

class WC_Gateway_Culqi extends WC_Payment_Gateway
{
    protected $culqi_logo, $payment_methods;
    public function __construct()
    {
        $this->id = 'culqi';
        $this->icon = PLUGIN_CULQI_URL . 'assets/images/cards.svg';
		$this->culqi_logo = PLUGIN_CULQI_URL . 'assets/images/culqi-logo.svg';
        $this->has_fields = true;
        $this->method_title = 'Culqi Payment';
        $this->payment_methods = 'Medios de pago';
        $this->method_description = 'Acepta pagos con tarjetas de débito y crédito, Yape, Cuotéalo BCP y PagoEfectivo (billeteras móviles, agentes y bodegas).';
        $this->init_form_fields();
        $this->init_settings();
        $this->title = $this->get_option('title');
        $this->description = $this->get_description();
        add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        // Add block support

        add_filter('woocommerce_blocks_payment_gateway_support', array($this, 'add_blocks_support'));
    }

    public function add_blocks_support($gateways) {
        $gateways[] = $this->id;
        return $gateways;
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
            "proposed_at" => gmdate('Y-m-d\TH:i:s'),
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
            'body'      => wp_json_encode($body),
            'timeout'   => 45,
            'headers'   => array(
                'Content-Type' => 'application/json',
                'shopify-shop-domain' => $shop_domain,
                'authorization' => 'Bearer '. $token,
            ),
        ));

        if (is_wp_error($response)) {
            wc_add_notice(__('Payment error: Could not connect to the payment gateway.', 'culqi'), 'error');
            return;
        }

        $response_body = wp_remote_retrieve_body($response);
        $result = json_decode($response_body, true);

        if (isset($result['redirect_url'])) {
            $gateway_url = $result['redirect_url'];
        } else {
            wc_add_notice(__('Payment error: Invalid response from payment gateway.', 'culqi'), 'error');
            return;
        }

        $order->update_status('pending', __('Payment pending, redirecting to gateway.', 'culqi'));
        $order->save();
        
        return array(
            'result'     => 'success',
            'show_modal' => true,
            'redirect'   => $gateway_url
        );
    }

    public function get_description() {
		$config = culqi_get_config();
        $payment_methods = $config->payment_methods ?? [];
        $txt = '';
        if($payment_methods) {
            $payment_methods = explode(',', $payment_methods);
            $tarjeta = in_array('tarjeta', $payment_methods);
            $yape =	in_array('yape', $payment_methods);
            $billetera = in_array('billetera', $payment_methods);
            $bancaMovil = in_array('bancaMovil', $payment_methods);
            $agente = in_array('agente', $payment_methods);
            $cuotealo =	in_array('cuotealo', $payment_methods);
            $txt_general = 'Acepta pagos con ';
            $txtPE = '';
            if($tarjeta) {
                $txt .= 'tarjetas de débito y crédito';
            }
            if($yape) {
                if($tarjeta) {
                    $txt .= ', ';
                }
                $txt .= 'Yape';
            }
            if($billetera || $bancaMovil || $agente || $cuotealo) {
                if($tarjeta || $yape) {
                    $txt .= ', ';
                }
                $txt .= 'Cuotéalo BCP y PagoEfectivo';
                $txtPE = ' (billeteras móviles, agentes y bodegas)';
            }
            $txt = '<strong>'.$txt.'</strong>';
            $txt = $txt_general. $txt. $txtPE;
            $txt .= '.';
        }
		$allowed_html = array(
			'strong' => array(),
		);
		
		return wp_kses(__($txt, 'culqi'), $allowed_html);
	}

    public function get_icon() {
		?>
			<script>
				jQuery('label[for="payment_method_culqi"]').contents().filter(function() {
					return this.nodeType === 3;
				}).first().remove();
			</script>
			<style>
				span.custom-checkbox{
					width: 100%;
				}
				.wc-culqi-container {
					width: 100%;
					align-items: center;
					justify-content: space-between;
					
					display: inline-grid !important;
					grid-template-columns: auto auto;
    				grid-template-rows: auto;
				}
				.wc-culqi-icon-container {
					grid-row: 1;
					grid-column: 2;
					display: flex;
					justify-content: right;
				}
				.wc-culqi-icon {
    				margin-left: 8px !important;
					height: 1.3em !important;
				}
				.wc-culqi-title {
					float: none !important;
					display: inline-block;
					margin-left: 0 !important;

					grid-row: 1;
    				grid-column: 1;
				}
				div.payment_method_culqi {
					width: 100%;
				}
				div.payment_method_culqi p {
					font-size: 12px;
				}
				li.payment_method_culqi {
					flex-wrap: wrap;
					margin-top: 10px;
				}
				label[for=payment_method_culqi] ,li.payment_method_culqi {
					vertical-align: initial !important;
					width: 100%;
					display: flex !important;
					flex: 1;
				}
				@media only screen and (max-width: 480px) {
					label[for=payment_method_culqi] {
						width: 100%;
					}
				}
			</style>
		<?php
		$config = culqi_get_config();
        $payment_methods = $config->payment_methods ?? [];
        if($payment_methods) {
            $payment_methods = explode(',', $payment_methods);
        }
		$tarjeta = in_array('tarjeta', $payment_methods);
		$yape =	in_array('yape', $payment_methods);
		$billetera = in_array('billetera', $payment_methods);
		$bancaMovil = in_array('bancaMovil', $payment_methods);
		$agente = in_array('agente', $payment_methods);
		$cuotealo =	in_array('cuotealo', $payment_methods);
		$cards_img = PLUGIN_CULQI_URL . 'assets/images/cards.svg';
		$yape_img = PLUGIN_CULQI_URL . 'assets/images/yape.svg';
		$pagoefectivo_img = PLUGIN_CULQI_URL . 'assets/images/pagoefectivo.svg';

		?>

		<div class="wc-culqi-container">
			<img class="wc-culqi-title" src="<?php echo esc_url( $this->culqi_logo ); ?>" alt="<?php echo esc_attr( $this->title ); ?>" />
			<div class="wc-culqi-icon-container">
				<?php if( $tarjeta ) : ?>
					<img class="wc-culqi-icon" src="<?php echo esc_url( $cards_img ); ?>" alt="<?php echo esc_attr( $this->payment_methods ); ?>" />
				<?php endif; ?>
				<?php if( $yape ) : ?>
					<img class="wc-culqi-icon" src="<?php echo esc_url( $yape_img ); ?>" alt="<?php echo esc_attr( $this->payment_methods ); ?>" />
				<?php endif; ?>
				<?php if( $billetera || $bancaMovil || $agente || $cuotealo ) : ?>
					<img class="wc-culqi-icon" src="<?php echo esc_url( $pagoefectivo_img ); ?>" alt="<?php echo esc_attr( $this->payment_methods ); ?>" />
				<?php endif; ?>
			</div>
		</div>

		<?php
    }
}
