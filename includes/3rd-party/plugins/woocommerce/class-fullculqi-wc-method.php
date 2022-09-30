<?php
/**
 * Method Payment Class
 * @since  1.0.0
 * @packkage Includes / Method Payment
 */
class WC_Gateway_FullCulqi extends WC_Payment_Gateway {

	/**
	 * Construct
	 */
	public function __construct() {

		$this->id 					= 'fullculqi';
		$this->method_title			= esc_html__( 'Culqi', 'fullculqi' );
		$this->method_description 	= esc_html__( 'Conéctate a nuestra pasarela de pagos para aumentar tus ventas.', 'fullculqi' );
		$this->icon 				= MPCULQI_WC_URL . 'assets/images/cards.png';

		// Define user set variables
		$this->has_fields		= apply_filters( 'fullculqi/method/has_fields', false );
		$this->title			= esc_html__( 'Culqi', 'fullculqi' );
		$this->installments 	= $this->get_option( 'installments', 'no' );
		$this->multipayment 	= $this->get_option( 'multipayment', 'no' );
		$this->multi_duration	= $this->get_option( 'multi_duration', 24 );
		$this->multi_status		= $this->get_option( 'multi_status', 'wc-pending' );
		$this->description		= esc_html__( 'Culqi acepta pagos con tarjeta de crédito/débito y más.', 'fullculqi' );
		$this->instructions		= $this->get_option( 'instructions', $this->description );
		$this->msg_fail			= $this->get_option( 'msg_fail' );
		$this->time_modal		= $this->get_option( 'time_modal', 0 );

		$this->supports = apply_filters('fullculqi/method/supports',
			[ 'products', 'refunds', 'pre-orders' ]
		);

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();
		//var_dump($this); exit(1);
		// Actions
		//add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'process_admin_options' ] );
        //echo 'woocommerce_update_options_payment_gateways_' . $this->id;
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, [ $this, 'insert_fields_form' ] );
		add_action( 'woocommerce_receipt_' . $this->id, [ $this, 'receipt_page' ] );
		add_action( 'woocommerce_thankyou_' . $this->id, [ $this, 'thankyou_page' ] );

		// Script JS && CSS
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

    public function insert_fields_form() {
        WC_Settings_API::process_admin_options($this);
        $fullculqi_options = fullculqi_get_settings();
        $fullculqi_options['enabled'] = $this->settings['enabled'];
        update_option('fullculqi_options', $fullculqi_options );
    }

	public function enqueue_scripts() {

		// Check if it is /checkout/pay page
		if( is_checkout_pay_page() ) {

			global $wp;
			//var_dump($wp->query_vars); exit(1);
			if( ! isset( $wp->query_vars['order-pay'] ) )
				return;

			$pnames = [];
			$order_id = $wp->query_vars['order-pay'];
			$order = new WC_Order( $order_id );
			if (version_compare(WC_VERSION, "2.7", "<")) {
                $log = new FullCulqi_Logs( $order_id );

    			$settings = fullculqi_get_settings();
    			//var_dump($settings); exit(1);
    			// Disabled from thirds
    			//$this->multipayment = apply_filters( 'fullculqi/method/disabled_multipayments', false, $order, 'order') ? 'no' : $this->multipayment;
                $this->multipayment='yes';
    			$this->installments = apply_filters( 'fullculqi/method/disabled_installments', false, $order, 'order') ? 'no' : $this->installments;

    			// Description
    			$pnames = [];

    			foreach( $order->get_items() as $item ) {
    					$pnames[] = $item['name'];
    			}

    			$desc = count( $pnames ) == 0 ? 'Product' : implode(', ', $pnames);
    			// Check if there is multipayment
    			//if( $this->multipayment == 'yes' ) {
    			if((isset($settings['methods']['cuetealo']) and $settings['methods']['cuetealo']!='0')
            or (isset($settings['methods']['billetera']) and $settings['methods']['billetera']!='0')
            or (isset($settings['methods']['agente']) and $settings['methods']['agente']!='0')
            or (isset($settings['methods']['bancaMovil']) and $settings['methods']['bancaMovil']!='0')){

    				$culqi_order_id = get_post_meta( $order_id, '_culqi_order_id', true );

    				if( empty( $culqi_order_id ) ) {

    					$client_details = [ 'email' => $order->billing_email ];

    					$billing_first_name 	= $order->billing_first_name;
    					$billing_last_name 		= $order->billing_last_name;
    					$billing_phone 			= $order->billing_phone;

    					if( ! empty( $billing_first_name ) )
    						$client_details['first_name'] = $billing_first_name;

    					if( ! empty( $billing_last_name ) )
    						$client_details['last_name'] = $billing_last_name;

    					if( ! empty( $billing_phone ) )
    						$client_details['phone_number'] = $billing_phone;
                        $enviroment = explode('|',$settings['enviroment']);

                        $time_expiration = time() + ( TIME_EXPIRATION_DEFAULT * 60*60 );
                        if($settings['time_expiration']>0){
                            $time_expiration = time() + ( $settings['time_expiration'] * 60*60  );
                        }
                        //echo var_dump($time_expiration);

    					$args_order = apply_filters( 'fullculqi/orders/create/args', [
    						'amount'			=> fullculqi_format_total( $order->get_total() ),
    						'currency_code'		=> $order->order_currency,
                            //'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                            'description'		=> 'Venta desde Plugin WooCommerce',
    						'order_number'		=> 'woo-'.time(),
    						'client_details'	=> $client_details,
    						'confirm'			=> false,
    						'expiration_date'	=> $time_expiration,
                            //'expiration_date'	=> $settings['time_expiration'],
    						'metadata'			=> [
    							'order_id'			=> $order_id,
    							'order_number'		=> $order->get_order_number(),
    							'order_key'			=> $order->order_key,
    							'customer_email'	=> $order->billing_email,
    							'customer_first'	=> $order->billing_first_name,
    							'customer_last'		=> $order->billing_last_name,
    							'customer_city'		=> $order->billing_city,
    							'customer_country'	=> $order->billing_country,
    							'customer_phone'	=> $order->billing_phone,
                                'sponsor'           => 'woocommerce'
    						],
                            'enviroment' 	=> $enviroment[0]
    					], $order);
    					$culqi_order = FullCulqi_Orders::create( $args_order );
    					//echo var_dump($culqi_order);
    					if( $culqi_order['status'] == 'ok' ) {
    						$culqi_order_id = $culqi_order['data']['culqi_order_id'];

    						// Save meta order
    						update_post_meta( $order_id, '_culqi_order_id', $culqi_order_id );

    					} else {
    						$error = sprintf(
    							esc_html__( 'Culqi Multipayment Error: %s', 'fullculqi' ),
    							$culqi_order['data']
    						);
    						$log->set_notice( $error );
    					}
    				}
    			}
            }else{
                $log = new FullCulqi_Logs( $order->get_id() );

    			$settings = fullculqi_get_settings();
    			//var_dump($settings); exit(1);
    			// Disabled from thirds
    			//$this->multipayment = apply_filters( 'fullculqi/method/disabled_multipayments', false, $order, 'order') ? 'no' : $this->multipayment;
                $this->multipayment='yes';
    			$this->installments = apply_filters( 'fullculqi/method/disabled_installments', false, $order, 'order') ? 'no' : $this->installments;


    			// Description
    			$pnames = [];

    			foreach( $order->get_items() as $item ) {
    				$product = $item->get_product();

    				if( $product && method_exists( $product, 'get_name' ) )
    					$pnames[] = $product->get_name();
    			}

    			$desc = count( $pnames ) == 0 ? 'Product' : implode(', ', $pnames);

    			// Check if there is multipayment
                if((isset($settings['methods']['cuetealo']) and $settings['methods']['cuetealo']!='0')
                    or (isset($settings['methods']['billetera']) and $settings['methods']['billetera']!='0')
                    or (isset($settings['methods']['agente']) and $settings['methods']['agente']!='0')
                    or (isset($settings['methods']['bancaMovil']) and $settings['methods']['bancaMovil']!='0')){

    				$culqi_order_id = get_post_meta( $order_id, '_culqi_order_id', true );
    				//echo var_dump($culqi_order_id);
    				if( empty( $culqi_order_id ) ) {
    					// Antifraud Customer Data
    					$client_details = [ 'email' => $order->get_billing_email() ];

    					$billing_first_name 	= $order->get_billing_first_name();
    					$billing_last_name 		= $order->get_billing_last_name();
    					$billing_phone 			= $order->get_billing_phone();

    					if( ! empty( $billing_first_name ) )
    						$client_details['first_name'] = $billing_first_name;

    					if( ! empty( $billing_last_name ) )
    						$client_details['last_name'] = $billing_last_name;

    					if( ! empty( $billing_phone ) )
    						$client_details['phone_number'] = $billing_phone;
                        $enviroment = explode('|',$settings['enviroment']);
                        $time_expiration = time() + ( TIME_EXPIRATION_DEFAULT * 60*60 );
                        if($settings['time_expiration']>0){
                            $time_expiration = time() + ( $settings['time_expiration'] * 60*60  );
                        }

    					$args_order = apply_filters( 'fullculqi/orders/create/args', [
    						'amount'			=> fullculqi_format_total( $order->get_total() ),
    						'currency_code'		=> $order->get_currency(),
                            //'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                            'description'		=> 'Venta desde Plugin WooCommerce',
                            'order_number'		=> 'woo-'.time(),
    						'client_details'	=> $client_details,
    						'confirm'			=> false,
    						'expiration_date'	=> $time_expiration,
    						'metadata'			=> [
    							'order_id'			=> $order->get_id(),
    							'order_number'		=> $order->get_order_number(),
    							'order_key'			=> $order->get_order_key(),
    							'customer_email'	=> $order->get_billing_email(),
    							'customer_first'	=> $order->get_billing_first_name(),
    							'customer_last'		=> $order->get_billing_last_name(),
    							'customer_city'		=> $order->get_billing_city(),
    							'customer_country'	=> $order->get_billing_country(),
    							'customer_phone'	=> $order->get_billing_phone(),
                                'sponsor'           => 'woocommerce'
    						],
                            'enviroment' 	=> $enviroment[0]
    					], $order);
    					$culqi_order = FullCulqi_Orders::create( $args_order );
    					//echo var_dump($culqi_order);
    					if( $culqi_order['status'] == 'ok' ) {
    						$culqi_order_id = $culqi_order['data']['culqi_order_id'];

    						// Save meta order
    						update_post_meta( $order->get_id(), '_culqi_order_id', $culqi_order_id );

    					} else {
    						$error = sprintf(
    							esc_html__( 'Culqi Multipayment Error: %s', 'fullculqi' ),
    							$culqi_order['data']
    						);
    						$log->set_notice( $error );
    					}
    				}
    			}
            }
			if( ! $order )
				return;

			// Log


			//var_dump(MPCULQI_WC_URL); exit(1);
			//OLANDA SCRIPT JS
			//$js_library		= 'https://checkout.culqi.com/js/v3';
			//var_dump(explode('|', $settings['enviroment'])); exit(1);

			$libraries = explode('|', $settings['enviroment']);
			$js_library		= $libraries[1];
            $js_3ds	= $libraries[2];
			$js_checkout	= MPCULQI_WC_URL . 'assets/js/wc-checkout.js';
			$js_waitme		= MPCULQI_WC_URL . 'assets/js/waitMe.min.js';
			$css_waitme		= MPCULQI_WC_URL . 'assets/css/waitMe.min.css';
            add_filter('script_loader_tag', 'add_type_attribute' , 10, 3);
			wp_enqueue_script( 'culqi-library-js', $js_library, [ 'jquery' ], false, true );
            wp_enqueue_script(
                '3ds-js', $js_3ds, [ 'jquery' ], false, true
            );
			wp_enqueue_script(
				'fullculqi-js', $js_checkout, [ 'jquery', 'culqi-library-js' ], false, true
			);
			//var_dump(isset($settings['methods']['tarjeta'])); exit(1);
			// Waitme
			wp_enqueue_script( 'waitme-js', $js_waitme, [ 'jquery' ], false, true );
			wp_enqueue_style( 'waitme-css', $css_waitme );
            $returnUrl3DS = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $logo_url = (isset($settings['logo_url']) and $settings['logo_url']!='' and !is_null($settings['logo_url'])) ? $settings['logo_url'] :  MPCULQI_URL.'resources/assets/images/brand.svg';
            wp_localize_script( 'fullculqi-js', 'fullculqi_vars',
				apply_filters('fullculqi/method/localize', [
                    'url_actions'	=> site_url( 'fullculqi-api/wc-actions/' ),
					'url_success'	=> $order->get_checkout_order_received_url(),
					'public_key'	=> sanitize_text_field( $settings['public_key'] ),
					'installments'	=> sanitize_title( $this->installments ),
					'multipayment'	=> sanitize_title( $this->multipayment ),
					'multi_order'	=> ((isset($settings['methods']['cuetealo']) and $settings['methods']['cuetealo']!='0') or (isset($settings['methods']['billetera']) and $settings['methods']['billetera']!='0') or (isset($settings['methods']['agente']) and $settings['methods']['agente']!='0') or (isset($settings['methods']['bancaMovil']) and $settings['methods']['bancaMovil']!='0')) ? $culqi_order_id : '',
					'lang'			=> fullculqi_language(),
					'time_modal'	=> absint( $this->time_modal*1000 ),
					'order_id'		=> absint( $order_id ),
					//'commerce'		=> sanitize_text_field( $settings['commerce'] ),
                    'commerce'		=> sanitize_text_field( get_bloginfo( 'name' ) ),
					'url_logo'		=> esc_url( $logo_url ),
                    'color_palette'	=> $settings['color_palette'],
					'currency'		=> get_woocommerce_currency(),
                    'version_wc'		=> WC_VERSION,
					'description'	=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
					'loading_text'	=> esc_html__( 'Loading. Please wait.', 'fullculqi' ),
					'total'			=> fullculqi_format_total( $order->get_total() ),
					'methods'		=> [
                            'tarjeta'		=>	(isset($settings['methods']['tarjeta']) and $settings['methods']['tarjeta']!='0') ? true : false,
							'yape'			=>	(isset($settings['methods']['yape']) and $settings['methods']['yape']!='0') ? true : false,
							'billetera'		=>	(isset($settings['methods']['billetera']) and $settings['methods']['billetera']!='0') ? true : false,
                            'bancaMovil'	=>	(isset($settings['methods']['bancaMovil']) and $settings['methods']['bancaMovil']!='0') ? true : false,
                            'agente'		=>	(isset($settings['methods']['agente']) and $settings['methods']['agente']!='0') ? true : false,
                            'cuetealo'		=>	(isset($settings['methods']['cuetealo']) and $settings['methods']['cuetealo']!='0') ? true : false,
					],
					'msg_fail'		=> sanitize_text_field( $this->msg_fail ),
					'msg_error'		=> esc_html__( 'There was some problem in the purchase process. Try again please', 'fullculqi' ),
					'wpnonce'		=> wp_create_nonce( 'fullculqi' ),
					'enviroment' 	=> $settings['enviroment'],
                    'url' 	=> $returnUrl3DS,
				], $order )
			);
			do_action( 'fullculqi/method/enqueue_scripts/pay_page', $order );
		}

		do_action( 'fullculqi/method/enqueue_scripts/after', $this );
	}

	//OLANDA - FORM WOOCOMMERCE SETTINGS JS
	/**
	 * Fields Form
	 * @return mixed
	 */
	public function init_form_fields() {
        if (isset($_GET['section']) and $_GET['section']=='fullculqi') {
            wp_safe_redirect(
                add_query_arg(
                    [ 'page' => 'fullculqi_settings' ],
                    admin_url( 'admin.php' )
                )
            );
        }
		$this->form_fields = apply_filters( 'fullculqi/method/form_fields', [
			'basic_section' => [
				'title' => esc_html__( 'BASIC SETTING', 'fullculqi' ),
				'type'  => 'title'
			],
			'enabled' => [
				'title'		=> esc_html__( 'Enable/Disable', 'fullculqi' ),
				'type'		=> 'checkbox',
				'label'		=> esc_html__( 'Enable Culqi', 'fullculqi' ),
				'default'	=> 'no',
			],
			'installments' => [
				'title'			=> esc_html__( 'Installments', 'fullculqi' ),
				'description'	=> esc_html__( 'If checked, a selection field will appear in the modal with the available installments.', 'fullculqi' ),
				'class'			=> '',
				'type'			=> 'checkbox',
				'label'			=> esc_html__( 'Enable Installments', 'fullculqi' ),
				'default'		=> 'no',
				'desc_tip'		=> true,
			],
			'title' => [
				'title'			=> esc_html__( 'Title', 'fullculqi' ),
				'type'			=> 'text',
				'description'	=> esc_html__( 'This controls the title which the user sees during checkout.', 'fullculqi' ),
				'desc_tip'		=> true,
				'default'		=> 'Culqi',
			],
			'description' => [
				'title'			=> esc_html__( 'Description', 'fullculqi' ),
				'description'	=> esc_html__( 'Brief description of the payment gateway. This message will be seen by the buyer', 'fullculqi' ),
				'class'			=> '',
				'default'		=> 'Culqi acepta pagos con tarjeta de crédito/débito y más',
				'type'			=> 'textarea',
				'desc_tip'		=> true,
			],
			'multi_section' => [
				'title'			=> esc_html__( 'MULTIPAYMENT SETTING', 'fullculqi' ),
				'type'			=> 'title',
				'description'	=> apply_filters( 'fullculqi/method/multi_html', '' ),
			],

			'multipayment' => [
				'title'			=> esc_html__('Enable', 'fullculqi'),
				'description'	=> esc_html__('If checked several tabs will appear in the modal with other payments','fullculqi'),
				'class'			=> '',
				'type'			=> 'checkbox',
				'label'			=> esc_html__('Enable Multipayment', 'fullculqi'),
				'default'		=> 'no',
				'desc_tip'		=> true,
			],
			'multi_duration' => [
				'title'			=> esc_html__( 'Duration', 'fullculqi' ),
				'description'	=> esc_html__( 'If enable Multipayment option, you must choose the order duration. This is the time you give the customer to make the payment.', 'fullculqi' ),
				'class'			=> '',
				'type'			=> 'select',
				'options'		=> [
					'1'		=> esc_html__( '1 Hour', 'fullculqi' ),
					'2'		=> esc_html__( '2 Hours', 'fullculqi' ),
					'4'		=> esc_html__( '4 Hours', 'fullculqi' ),
					'8'		=> esc_html__( '8 Hours', 'fullculqi' ),
					'12'	=> esc_html__( '12 Hours', 'fullculqi' ),
					'24'	=> esc_html__( '1 Day', 'fullculqi' ),
					'48'	=> esc_html__( '2 Days', 'fullculqi' ),
					'96'	=> esc_html__( '4 Days', 'fullculqi' ),
					'168'	=> esc_html__( '7 Days', 'fullculqi' ),
					'360'	=> esc_html__( '15 Days', 'fullculqi' ),
				],
				'default'		=> '24',
				'desc_tip'		=> true,
			],
			'multi_status' => [
				'title'			=> esc_html__( 'Status', 'fullculqi' ),
				'description'	=> esc_html__( 'If the sale is made via multipayments, you must specify the status.', 'fullculqi' ),
				'type'			=> 'select',
				'class'			=> 'wc-enhanced-select',
				'options'		=> wc_get_order_statuses(),
				'default'		=> 'wc-pending',
				'desc_tip'		=> true,
			],

			'additional_section' => [
				'title' => esc_html__( 'ADDITIONAL SETTING', 'fullculqi' ),
				'type'  => 'title'
			],

			'status_success' => [
				'title'			=> esc_html__( 'Success Status', 'fullculqi' ),
				'type'			=> 'select',
				'class'			=> 'wc-enhanced-select',
				'description'	=> esc_html__( 'If the purchase is success, apply this status to the order', 'fullculqi' ),
				'default'		=> 'wc-processing',
				'desc_tip'		=> true,
				'options'		=> wc_get_order_statuses(),
			],
			'msg_fail' => [
				'title'			=> esc_html__( 'Failed Message', 'fullculqi' ),
				'description'	=> esc_html__( 'This is the message will be shown to the customer if there is a error in the payment', 'fullculqi' ),
				'class'			=> '',
				'type'			=> 'textarea',
				'desc_tip'		=> false,
				'default'		=> esc_html__( 'Im sorry! an error occurred making the payment. A email was sent to shop manager with your information.', 'fullculqi' ),
			],
			'time_modal' => [
				'title'			=> esc_html__( 'Popup/Modal Time', 'fullculqi' ),
				'type'			=> 'text',
				'description'	=> esc_html__( 'If you want the modal window to appear after a while without clicking "buy", put the seconds here. (Warning: may it not work in Safari). If you do not want to, leave it at zero.', 'fullculqi' ),
				'default'		=> '0',
				'placeholder'	=> '0',
				'desc_tip'		=> false,
			],
		] );
        //header("Location: /wp-admin/admin.php?page=fullculqi_settings");
	}

	/**
	 * Payment fields ( credit card form )
	 * @return mixed
	 */
	public function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) ); // @codingStandardsIgnoreLine.
		}

		do_action( 'fullculqi/method/payment_fields', $this );
	}

	/**
	 * Thanks You Page
	 * @param  integer $order_id
	 * @return mixed
	 */
	public function thankyou_page( $order_id = 0 ) {

		$order = new WC_Order( $order_id );
	}

	/**
	 * Payment Receipt Page
	 * @param  integer $order_id
	 * @return mixed
	 */
	public function receipt_page( $order_id = 0 ) {
		//var_dump('receipt page'); exit(1);
		$order = new WC_Order( $order_id );

		$args = apply_filters( 'fullculqi/receipt_page/args', [
			'src_image'		=> $this->icon,
			'url_cancel'	=> esc_url( $order->get_cancel_order_url() ),
			'order_id'		=> $order_id,
			'class_button'	=> [ 'button', 'alt' ],
		], $order );

		do_action('fullculqi/form-receipt/before', $order);

		wc_get_template(
			'layouts/checkout-receipt.php', $args, false, MPCULQI_WC_DIR
		);

		do_action('fullculqi/form-receipt/after', $order);
	}


	/**
	 * Process Payment
	 *
	 * @param  integer $order_id
	 * @return mixed
	 */
	public function process_payment( $order_id = 0 ) {
		$order = new WC_Order( $order_id );

		$output = [
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true ),
		];

		return apply_filters( 'fullculqi/method/process_payment', $output, $order, $this );
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
	public function process_refund( $order_id = 0, $amount = null, $reason = '' ) {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
        $settings = fullculqi_get_settings();
        $enviroment = explode('|',$settings['enviroment']);
		$order = wc_get_order( $order_id );

		if ( ! $this->can_refund_order( $order ) ) {
			$message = esc_html__( 'The refund cannot be made from FullCulqi', 'fullculqi' );
			return new WP_Error( 'error', $message );
		}
		// Logs
        if (version_compare(WC_VERSION, "2.7", "<")) {
            $log = new FullCulqi_Logs($order_id);
            $culqi_charges_id = get_post_meta( $order_id, '_culqi_charge_id', true );
            $post_charge_id = get_post_meta( $order_id, '_post_charge_id', true );
            $args = [
                'amount'	=> round( $amount*100, 0 ),
                'charge_id'	=> $culqi_charges_id,
                'reason'	=> 'solicitud_comprador',
                'metadata'	=> [
                    //'post_id'	=> $post_charge_id,
                    'order_id'	=> $order_id,
                ],
                'enviroment'=>$enviroment[0]
            ];
        }else{
            $log = new FullCulqi_Logs($order->get_id());
            $culqi_charges_id = get_post_meta( $order->get_id(), '_culqi_charge_id', true );
            $post_charge_id = get_post_meta( $order->get_id(), '_post_charge_id', true );
            $args = [
                'amount'	=> round( $amount*100, 0 ),
                'charge_id'	=> $culqi_charges_id,
                'reason'	=> 'solicitud_comprador',
                'metadata'	=> [
                    //'post_id'	=> $post_charge_id,
                    'order_id'	=> $order->get_id(),
                ],
                'enviroment'=>$enviroment[0]
            ];
        }
        if($culqi_charges_id!=''){
            $refund = FullCulqi_Refunds::create( $args, $post_charge_id );
            if( $refund['status'] == 'error' ) {
                $error = sprintf(
                    esc_html__( 'Culqi Refund Error : %s','fullculqi' ), $refund['data']
                );
                $log->set_error( $error );
                return new WP_Error( 'error', $error );
            }

            $notice = sprintf(
                esc_html__( 'Culqi Refund created: %s', 'fullculqi' ),
                $refund['data']['culqi_refund_id']
            );
            $order->add_order_note( $notice );
            $log->set_notice( $notice );
        }else{
            $error = sprintf(
                'Solo se puede hacer devoluciones a pedidos pagados con tarjeta de crédito o débito.', 'Error'
            );
            $log->set_error( $error );
            return new WP_Error( 'error', $error );
        }



		return true;
	}

	/**
	 * Validate Fields
	 * @return bool
	 */
	public function validate_fields() {
		return apply_filters( 'fullculqi/method/validate', true, $this );
	}


	/**
	 * Create new field to settings
	 * @param  string $key
	 * @param  array  $data
	 * @return mixed
	 */
	public function generate_radio_html( $key = '', $data = [] ) {

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

	/**
	 * MultiUrl Field
	 * @param  string $key
	 * @param  array  $data
	 * @return mixed
	 */
	public function generate_multiurl_html( $key = '', $data = [] ) {

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

function add_type_attribute($tag, $handle, $src) {
    // if not your script, do nothing and return original $tag
    if ( 'fullculqi-js' !== $handle ) {
        return $tag;
    }
    // change the script tag by adding type="module" and return it.
    $tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
    return $tag;
}

?>
