<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class WC_Gateway_FullCulqi extends WC_Payment_Gateway {

	public function __construct() {

		$this->id 					= 'fullculqi';
		$this->method_title			= esc_html__('Culqi Full Integration','letsgo');
		$this->method_description 	= esc_html__( 'Allows payments by Card Credit. This payment method will decide if it is a simple payment or subscription or other', 'letsgo' );
		$this->icon 				= FULLCULQI_PLUGIN_URL . 'public/assets/images/cards.png';

		// Define user set variables
		$this->has_fields		= apply_filters('fullculqi/method/has_fields', false);
		$this->title			= $this->get_option( 'title' );
		$this->installments 	= $this->get_option( 'installments', 'no' );
		$this->multipayment 	= $this->get_option( 'multipayment', 'no' );
		$this->multi_duration	= $this->get_option( 'multi_duration', 24 );
		$this->multi_status		= $this->get_option( 'multi_status', 'wc-pending' );
		$this->description		= $this->get_option( 'description' );
		$this->instructions		= $this->get_option( 'instructions', $this->description );
		$this->msg_fail			= $this->get_option( 'msg_fail' );
		$this->time_modal		= $this->get_option( 'time_modal', 0 );

		$this->supports = apply_filters('fullculqi/method/supports',
								[ 'products', 'refunds', 'pre-orders' ]
							);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Actions
		add_action('woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
		add_action('woocommerce_receipt_' . $this->id, [ $this, 'receipt_page' ] );
		add_action('woocommerce_thankyou_' . $this->id, [ $this, 'thankyou_page' ] );

		// JS and CSS
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}


	function enqueue_scripts() {
		if( is_checkout_pay_page() ) {

			global $wp;

			if( !isset($wp->query_vars['order-pay']) ) return;

			$pnames = [];
			$order_id = $wp->query_vars['order-pay'];
			$order = new WC_Order( $order_id );

			$settings = fullculqi_get_settings();

			foreach ($order->get_items() as $item ) {
				$product = $item->get_product();

				if( $product && method_exists($product, 'get_name' ) )
					$pnames[] = $product->get_name();
			}

			// If empty
			if( count($pnames) == 0 )
				$pnames[0] = 'Product';


			// Disabled from thirds
			$this->multipayment = apply_filters('fullculqi/method/disabled_multipayments', false, $order, 'order') ? 'no' : $this->multipayment;

			$this->installments = apply_filters('fullculqi/method/disabled_installments', false, $order, 'order') ? 'no' : $this->installments;


			if( $this->multipayment == 'yes' ) {

				$multi_order = get_post_meta($order_id, 'culqi_order', true);

				if( !$multi_order ) {

					// Init Log
					$log = new FullCulqi_Logs();
					$log->set_settings_payment( $order->get_id() );

					$multi_order = FullCulqi_Checkout::create_order($order, $this->multi_duration, $pnames, $log);

					update_post_meta($order_id, 'culqi_order', $multi_order);
				}
			}


			$js_checkout	= 'https://checkout.culqi.com/js/v3';
			$js_fullculqi	= FULLCULQI_PLUGIN_URL . 'public/assets/js/fullculqi.js';
			$js_waitme		= FULLCULQI_PLUGIN_URL . 'public/assets/js/waitMe.min.js';
			$css_waitme		= FULLCULQI_PLUGIN_URL . 'public/assets/css/waitMe.min.css';

			wp_enqueue_script('fullcheckout-js', $js_checkout, [ 'jquery' ], false, true);
			wp_enqueue_script('fullculqi-js', $js_fullculqi, [ 'jquery', 'fullcheckout-js' ], false, true);
			wp_enqueue_script('waitme-js', $js_waitme, [ 'jquery' ], false, true);
			wp_enqueue_style('waitme-css', $css_waitme );
            $logo_url = (isset($settings['logo_url']) and $settings['logo_url']!='' and !is_null($settings['logo_url'])) ? $settings['logo_url'] :  FULLCULQI_URL.'resources/assets/images/brand.svg';
			wp_localize_script( 'fullculqi-js', 'fullculqi',
				apply_filters('fullculqi/method/localize',
				[
					'url_payment'	=> site_url('wc-api/fullculqi_create_payment/'),
					'url_order'		=> site_url('wc-api/fullculqi_create_order/'),
					'url_success'	=> $order->get_checkout_order_received_url(),
					'public_key'	=> sanitize_text_field($settings['public_key']),
					'installments'	=> sanitize_title($this->installments),
					'multipayment'	=> sanitize_title($this->multipayment),
					'multi_order'	=> $this->multipayment == 'yes' ? $multi_order : '',
					'lang'			=> fullculqi_get_language(),
					'time_modal'	=> absint($this->time_modal*1000),
					'order_id'		=> absint($order_id),
					'commerce'		=> sanitize_text_field($settings['commerce']),
					'url_logo'		=> esc_url($logo_url),
					'currency'		=> get_woocommerce_currency(),
					'description'	=> substr(str_pad(implode(', ', $pnames), 5, '_'), 0, 80),
					'loading_text'	=> esc_html__('Loading. Please wait.','letsgo'),
					'total'			=> fullculqi_format_total($order->get_total()),
					'msg_fail'		=> sanitize_text_field($this->msg_fail),
					'msg_error'		=> esc_html__('There was some problem in the purchase process. Try again please','letsgo'),
					'wpnonce'		=> wp_create_nonce('fullculqi'),
				], $this)
			);
		}

		do_action('fullculqi/method/enqueue_scripts' );
	}


	function init_form_fields() {

		$this->form_fields = apply_filters('fullculqi/method/form_fields', [
				'basic_section' => [
					'title' => esc_html__('BASIC SETTING','letsgo'),
					'type'  => 'title'
				],

				'enabled' => [
					'title'		=> esc_html__( 'Enable/Disable', 'letsgo' ),
					'type'		=> 'checkbox',
					'label'		=> esc_html__( 'Enable Culqi', 'letsgo' ),
					'default'	=> 'yes',
				],
				'installments' => [
					'title'			=> esc_html__('Installments', 'letsgo'),
					'description'	=> esc_html__('If checked, a selection field will appear in the modal with the available installments.','letsgo'),
					'class'			=> '',
					'type'			=> 'checkbox',
					'label'			=> esc_html__('Enable Installments', 'letsgo'),
					'default'		=> 'no',
					'desc_tip'		=> true,
				],
				'title' => [
					'title'			=> esc_html__( 'Title', 'letsgo' ),
					'type'			=> 'text',
					'description'	=> esc_html__( 'This controls the title which the user sees during checkout.', 'letsgo' ),
					'desc_tip'		=> true,
				],
				'description' => [
					'title'			=> esc_html__('Description', 'letsgo'),
					'description'	=> esc_html__('Brief description of the payment gateway. This message will be seen by the buyer','letsgo'),
					'class'			=> '',
					'default'		=> 'Culqi acepta pago con tarjetas, pagoefectivo, billeteras móviles y cuotéalo',
					'type'			=> 'textarea',
					'desc_tip'		=> true,
				],

				'multi_section' => [
					'title'			=> esc_html__('MULTIPAYMENT SETTING','letsgo'),
					'type'			=> 'title',
					'description'	=> apply_filters('fullculqi/method/multi_html',''),
				],

				'multipayment' => [
					'title'			=> esc_html__('Enable', 'letsgo'),
					'description'	=> esc_html__('If checked several tabs will appear in the modal with other payments','letsgo'),
					'class'			=> '',
					'type'			=> 'checkbox',
					'label'			=> esc_html__('Enable Multipayment', 'letsgo'),
					'default'		=> 'no',
					'desc_tip'		=> true,
				],
				'multi_duration' => [
					'title'			=> esc_html__('Duration', 'letsgo'),
					'description'	=> esc_html__('If enable Multipayment option, you must choose the order duration. This is the time you give the customer to make the payment.','letsgo'),
					'class'			=> '',
					'type'			=> 'select',
					'options'		=> [
						'1'		=> esc_html__('1 Hour','letsgo'),
						'2'		=> esc_html__('2 Hours','letsgo'),
						'4'		=> esc_html__('4 Hours','letsgo'),
						'8'		=> esc_html__('8 Hours','letsgo'),
						'12'	=> esc_html__('12 Hours','letsgo'),
						'24'	=> esc_html__('1 Day','letsgo'),
						'48'	=> esc_html__('2 Days','letsgo'),
						'96'	=> esc_html__('4 Days','letsgo'),
						'168'	=> esc_html__('7 Days','letsgo'),
						'360'	=> esc_html__('15 Days','letsgo'),
					],
					'default'		=> '24',
					'desc_tip'		=> true,
				],
				'multi_status' => [
					'title'			=> esc_html__('Status', 'letsgo'),
					'description'	=> esc_html__('If the sale is made via multipayments, you must specify the status.','letsgo'),
					'type'			=> 'select',
					'class'			=> 'wc-enhanced-select',
					'options'  => wc_get_order_statuses(),
					'default'		=> 'wc-pending',
					'desc_tip'		=> true,
				],
				'multi_url' => [
					'title' => esc_html__('Webhook URL','letsgo'),
					'type' => 'multiurl',
					'description' => esc_html__('If you have enabled the multipayment, so you need configure the webhooks usign this URL','letsgo'),
					'desc_tip' => true,
					'default' => 'yes',
				],

				'additional_section' => [
					'title' => esc_html__('ADDITIONAL SETTING','letsgo'),
					'type'  => 'title'
				],

				'status_success' => [
					'title' => esc_html__('Success Status','letsgo'),
					'type' => 'select',
					'class'       => 'wc-enhanced-select',
					'description' => esc_html__('If the purchase is success, apply this status to the order','letsgo'),
					'default' => 'wc-processing',
					'desc_tip' => true,
					'options'  => wc_get_order_statuses(),
				],
				'msg_fail' => [
					'title'			=> esc_html__('Failed Message', 'letsgo'),
					'description'	=> esc_html__('This is the message will be shown to the customer if there is a error in the payment','letsgo'),
					'class'			=> '',
					'type'			=> 'textarea',
					'desc_tip'		=> false,
					'default'		=> esc_html__('Im sorry! an error occurred making the payment. A email was sent to shop manager with your information.','letsgo'),
				],
				'time_modal' => [
					'title'			=> esc_html__('Popup/Modal Time','letsgo'),
					'type'			=> 'text',
					'description'	=> esc_html__('If you want the modal window to appear after a while without clicking "buy", put the seconds here. (Warning: may it not work in Safari). If you do not want to, leave it at zero.','letsgo'),
					'default'		=> '0',
					'placeholder'	=> '0',
					'desc_tip'		=> false,
				],
			]
		);
	}


	function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) ); // @codingStandardsIgnoreLine.
		}

		do_action('fullculqi/method/payment_fields', $this);
	}


	function thankyou_page( $order_id ) {

		$order = new WC_Order( $order_id );
	}

	function receipt_page( $order_id ) {

		$order = new WC_Order( $order_id );

		$args = [
			'src_image'		=> $this->icon,
			'url_cancel'	=> esc_url( $order->get_cancel_order_url() ),
			'order_id'		=> $order_id,
		];

		do_action('fullculqi/form-receipt/before', $order);

		wc_get_template('public/layouts/form-receipt.php', $args, false, FULLCULQI_PLUGIN_DIR );

		do_action('fullculqi/form-receipt/after', $order);
	}


	function process_payment( $order_id ) {
		$order = new WC_Order( $order_id );

		// Mark as on-hold (we're awaiting the cheque)
		//$order->update_status( 'pending', esc_html__('Order pending confirmation','letsgo'));

		return apply_filters('fullculqi/method/redirect', [
					'result'   => 'success',
					'redirect' => $order->get_checkout_payment_url(true),
				], $order, $this);
	}


	/**
	 * Can the order be refunded via Culqi?
	 *
	 * @param  WC_Order $order Order object.
	 * @return bool
	 */
	public function can_refund_order( $order ) {

		$settings = fullculqi_get_settings();

		$has_api_creds = ! empty( $settings['public_key'] ) && ! empty( $settings['secret_key'] );

		return $order && $has_api_creds;
	}

	/**
	 * Process a refund if supported.
	 *
	 * @param  int    $order_id Order ID.
	 * @param  float  $amount Refund amount.
	 * @param  string $reason Refund reason.
	 * @return bool|WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $this->can_refund_order( $order ) ) {
			return new WP_Error( 'error', esc_html__( 'Refund failed.', 'letsgo' ) );
		}

		// Init Log
		$log = new FullCulqi_Logs();
		$log->set_settings_payment( $order->get_id() );

		$provider_refund = FullCulqi_Checkout::create_refund( $order, $amount, $reason, $log );

		if( count( $provider_refund ) == 0 ) {
			$message = esc_html__('Culqi Provider Payment error : There was not set any refund','letsgo');
			$log->set_msg_payment( 'error', $message );

			return new WP_Error( 'error', $message );
		}

		$data = $provider_refund['data'];

		$culqi_post_id	= get_post_meta( $order_id, 'culqi_post_id', true );
		update_post_meta( $culqi_post_id, 'culqi_data', $data );
		update_post_meta( $culqi_post_id, 'culqi_status', 'refunded' );

		// Save Refund
		$basic = get_post_meta( $culqi_post_id, 'culqi_basic', true );
		$refunds = (array)get_post_meta( $culqi_post_id, 'culqi_ids_refunded', true );

		$refunds[ $data->id ] = number_format( $data->amount / 100, 2, '.', '' );

		$basic['culqi_amount_refunded'] = array_sum( $refunds );

		update_post_meta( $culqi_post_id, 'culqi_basic', $basic );
		update_post_meta( $culqi_post_id, 'culqi_ids_refunded', $refunds );

		return true;
	}


	function validate_fields() {
		return apply_filters('fullculqi/method/validate', true, $this);
	}


	function generate_radio_html( $key, $data ) {

		$field_key = $this->get_field_key( $key );
		$defaults  = [
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'radio',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => [],
			'options'           => [],
		];
		$data = wp_parse_args( $data, $defaults );
		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>

					<?php foreach ( (array) $data['options'] as $option_key => $option_value ) : ?>
						<label for="<?php echo esc_attr( $option_key ); ?>">
							<input type="radio" value="<?php echo esc_attr( $option_key ); ?>" name="<?php echo esc_attr( $field_key ); ?>" id="<?php echo esc_attr( $option_key ); ?>" <?php checked( $this->get_option( $key ), $option_key ); ?> /><?php echo esc_attr( $option_value ); ?>
						</label>
						<br />
					<?php endforeach; ?>

					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	function generate_multiurl_html($key, $data) {

		$field_key = $this->get_field_key( $key );

		ob_start();
		?>

		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?></label>
				<?php echo $this->get_tooltip_html( $data ); ?>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<b><?php echo site_url('wc-api/fullculqi_update_order'); ?></b>
					<?php echo $this->get_description_html( $data ); ?>
				</fieldset>
			</td>
		</tr>

		<?php
		return ob_get_clean();
	}
}

?>
