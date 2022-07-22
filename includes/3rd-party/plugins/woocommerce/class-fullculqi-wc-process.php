<?php
/**
 * WooCommerce Process Class
 * @since  1.0.0
 * @package Includes / 3rd-party / plugins / WooCommerce / Process
 */
class FullCulqi_WC_Process {

	public static $log;

	/**
	 * Create Order
	 * @param  array  $post_data
	 * @return mixed
	 */
	public static function order( $post_data = [] ) {
		if( ! isset( $post_data['id'] ) ||
			! isset( $post_data['cip_code'] ) ||
			! isset( $post_data['order_id'] ) ) {
			return false;
		}

		// Settings Gateway
		$method = get_option( 'woocommerce_fullculqi_settings', [] );

		if( empty( $method ) )
			return false;

		// Variables
		$order = wc_get_order( absint( $post_data['order_id'] ) );
		//echo var_dump($order);

		if( ! $order  )
			return false;

		// Log

        if (version_compare(WC_VERSION, "2.7", "<")) {
            self::$log = new FullCulqi_Logs( $post_data['order_id'] );
            $post_customer_id = 0;
            if( self::customer( $order ) ) {
                $culqi_customer_id = get_post_meta( $post_data['order_id'], '_culqi_customer_id', true );
                $post_customer_id = get_post_meta( $post_data['order_id'], '_post_customer_id', true );
            }
        }else{
            self::$log = new FullCulqi_Logs( $order->get_id() );
            $post_customer_id = 0;
            if( self::customer( $order ) ) {
                $culqi_customer_id = get_post_meta( $order->get_id(), '_culqi_customer_id', true );
                $post_customer_id = get_post_meta( $order->get_id(), '_post_customer_id', true );
            }
        }



		// Culqi Customer ID



		$notice = sprintf(
			esc_html__( 'Culqi Multipayment CIP: %s', 'fullculqi' ), $post_data['cip_code']
		);

		self::$log->set_notice( $notice );
		$order->add_order_note( $notice );

		// Status Orders
		$order->update_status( $method['multi_status'],
			sprintf(
				esc_html__( 'Status changed by FullCulqi (to %s)', 'fullculqi' ),
				$method['multi_status']
			)
		);

		// Update CIP CODE in WC Order
        if (version_compare(WC_VERSION, "2.7", "<")) {
            update_post_meta(  $post_data['order_id'], '_culqi_cip', $post_data['cip_code'] );
        }else{
            update_post_meta( $order->get_id(), '_culqi_cip', $post_data['cip_code'] );
        }

		// From Culqi
		$culqi_order = FullCulqi_Orders::after_confirm( $post_data, $post_customer_id );
        //echo var_dump($culqi_order);
		if( $culqi_order['status'] != 'ok' ) {
            //echo 'hola';
			$error = sprintf(
				esc_html__( 'Culqi Multipayment Error: %s', 'fullculqi' ), $culqi_order['data']
			);
			self::$log->set_notice( $error );

			return false;
		}

		$culqi_order_id = $culqi_order['data']['culqi_order_id'];
		$post_order_id = $culqi_order['data']['post_order_id'];

		// Log
		$notice = sprintf(
			esc_html__( 'Post Multipayment Created: %s', 'fullculqi' ), $post_order_id
		);
		self::$log->set_notice( $notice );

		// Update meta post in wc order
        if (version_compare(WC_VERSION, "2.7", "<")) {
            update_post_meta(  $post_data['order_id'], '_post_order_id', $post_order_id );
            update_post_meta(  $post_data['order_id'], '_culqi_order_id', $post_data['id'] );
            update_post_meta( $post_order_id, 'culqi_wc_order_id', $post_data['order_id'] );
        }else{
            update_post_meta( $order->get_id(), '_post_order_id', $post_order_id );
            update_post_meta( $order->get_id(), '_culqi_order_id', $post_data['id'] );
            update_post_meta( $post_order_id, 'culqi_wc_order_id', $order->get_id() );
        }


		// Update WC Order IN in Culqi Orders


		return true;
	}

	/**
	 * Create Charge
	 * @param  array  $post_data
	 * @return bool
	 */
	public static function charge( $post_data = [] ) {

		// Settings WC
		$method = get_option( 'woocommerce_fullculqi_settings' );

		if( empty( $method ) )
			return false;

		// Get WC Order
		$order = wc_get_order( absint( $post_data['order_id'] ) );
		$installments = sanitize_text_field( $post_data['installments'] );
		if(!$installments>0){
            $installments=1;
        }
		$country_code = sanitize_text_field( $post_data['country_code'] );

		if( isset( $post_data['token_id'] ) )
			$token = sanitize_text_field( $post_data['token_id'] );

		if( ! $order )
			return false;

		// Instance Logs
		if (version_compare(WC_VERSION, "2.7", "<")) {
		    self::$log = new FullCulqi_Logs( $post_data['order_id'] );
		}else{
		    self::$log = new FullCulqi_Logs( $order->get_id() );
		}


		// If the user is logged
		/*if( self::customer( $order ) ) {

			$culqi_customer_id = get_post_meta( $order->get_id(), '_culqi_customer_id', true );
			$post_customer_id = get_post_meta( $order->get_id(), '_post_customer_id', true );

			// Create Card
			if( ! empty( $culqi_customer_id ) ) {

				if( isset( $post_data['card_id'] ) ) {

					$token = sanitize_text_field( $post_data['card_id'] );

					$culqi_card = FullCulqi_Cards::get( $token );

					do_action( 'fullculqi/wc/charge/card', $culqi_card, $order );

				} else {

					$args_card = [
						'customer_id'	=> $culqi_customer_id,
						'token_id'		=> $token,
						'metadata'		=> [
							'customer_id'	=> $post_customer_id,
						]
					];

					$culqi_card = FullCulqi_Cards::create( $args_card );

					do_action( 'fullculqi/wc/charge/card', $culqi_card, $order );

					if( $culqi_card['status'] == 'ok' ) {
						$token = $culqi_card['data']['culqi_card_id'];
					} else {
						$error = sprintf(
							esc_html__( 'Culqi Card Error: %s', 'fullculqi' ),
							$culqi_card['data']
						);
						self::$log->set_error( $error );
					}
				}
			}
		}*/

		if( ! isset( $token ) )
			return false;

		if( apply_filters( 'fullculqi/wc/charge/is_new', false, $order ) ) {

			return apply_filters( 'fullculqi/wc/charge/create', false, $order );

		} else {

			// Charges
			$pnames = [];

			foreach( $order->get_items() as $item ) {
			    if (version_compare(WC_VERSION, "2.7", "<")) {
        		    $pnames[] = $item['name'];
        		}else{
        		    $product = $item->get_product();
				    if( $product && method_exists( $product, 'get_name' ) )
					    $pnames[] = $product->get_name();
        		}

			}

			$desc = count( $pnames ) == 0 ? 'Product' : implode(', ', $pnames);

			// Antifraud Customer Data
			$antifraud_charges = [ 'device_finger_print_id' => $post_data['device'] ];
/*
			$billing_first_name 	= $order->get_billing_first_name();
			$billing_last_name 		= $order->get_billing_last_name();
			$billing_address_1 		= $order->get_billing_address_1();
			$billing_phone 			= $order->get_billing_phone();
			$billing_city 			= $order->get_billing_city();
			$billing_country 		= $order->get_billing_country();

			if( ! empty( $billing_first_name ) )
				$antifraud_charges['first_name'] = $billing_first_name;

			if( ! empty( $billing_last_name ) )
				$antifraud_charges['last_name'] = $billing_last_name;

			if( ! empty( $billing_address_1 ) )
				$antifraud_charges['address'] = $billing_address_1;

			if( ! empty( $billing_city ) )
				$antifraud_charges['address_city'] = $billing_city;

			if( ! empty( $billing_country ) )
				$antifraud_charges['country_code'] = $billing_country;
			elseif( ! empty($country_code) )
				$antifraud_charges['country_code'] = $country_code;

			if( ! empty( $billing_phone ) )
				$antifraud_charges['phone_number'] = $billing_phone;
*/
			// Metadata Order
			if (version_compare(WC_VERSION, "2.7", "<")) {
			    $metadata_charges = [
    				//'order_id'			=> $post_data['order_id'],
    				'order_number'		=> $order->get_order_number(),
    				'order_key'			=> $order->order_key,
    				'post_customer'		=> isset( $post_customer_id ) ? $post_customer_id : false,
    			];
    			if(isset($post_data['parameters3DS']) and $post_data['parameters3DS']!==FALSE and !is_null($post_data['parameters3DS']) and is_array($post_data['parameters3DS'])){
                    $args_charges = apply_filters( 'fullculqi/process/charge_args', [
                        'amount'			=> fullculqi_format_total( $order->get_total() ),
                        'currency_code'		=> $order->order_currency,
                        'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                        'capture'			=> true,
                        'email'				=> $order->billing_email,
                        'installments'		=> $installments,
                        'source_id'			=> $token,
                        'metadata'			=> $metadata_charges,
                        'antifraud_details'	=> $antifraud_charges,
                        'enviroment' => $post_data['enviroment'],
                        'authentication_3DS' => $post_data['parameters3DS']
                    ], $order );
                }else{
                    $args_charges = apply_filters( 'fullculqi/process/charge_args', [
                        'amount'			=> fullculqi_format_total( $order->get_total() ),
                        'currency_code'		=> $order->order_currency,
                        'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                        'capture'			=> true,
                        'email'				=> $order->billing_email,
                        'installments'		=> $installments,
                        'source_id'			=> $token,
                        'metadata'			=> $metadata_charges,
                        'antifraud_details'	=> $antifraud_charges,
                        'enviroment' => $post_data['enviroment']
                    ], $order );
                }
			}else{
			    $metadata_charges = [
    				//'order_id'			=> $order->get_id(),
    				'order_number'		=> $order->get_order_number(),
    				'order_key'			=> $order->get_order_key(),
    				'post_customer'		=> isset( $post_customer_id ) ? $post_customer_id : false,
    			];
    			if(isset($post_data['parameters3DS']) and $post_data['parameters3DS']!==FALSE and !is_null($post_data['parameters3DS']) and is_array($post_data['parameters3DS'])){
                    $args_charges = apply_filters( 'fullculqi/process/charge_args', [
                        'amount'			=> fullculqi_format_total( $order->get_total() ),
                        'currency_code'		=> $order->get_currency(),
                        'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                        'capture'			=> true,
                        'email'				=> $order->get_billing_email(),
                        'installments'		=> $installments,
                        'source_id'			=> $token,
                        'metadata'			=> $metadata_charges,
                        'antifraud_details'	=> $antifraud_charges,
                        'enviroment' => $post_data['enviroment'],
                        'authentication_3DS' => $post_data['parameters3DS']
                    ], $order );
                }else{
                    $args_charges = apply_filters( 'fullculqi/process/charge_args', [
                        'amount'			=> fullculqi_format_total( $order->get_total() ),
                        'currency_code'		=> $order->get_currency(),
                        'description'		=> substr( str_pad( $desc, 5, '_' ), 0, 80 ),
                        'capture'			=> true,
                        'email'				=> $order->get_billing_email(),
                        'installments'		=> $installments,
                        'source_id'			=> $token,
                        'metadata'			=> $metadata_charges,
                        'antifraud_details'	=> $antifraud_charges,
                        'enviroment' => $post_data['enviroment']
                    ], $order );
                }
			}



			//echo var_dump($args_charges);
			$culqi_charge = FullCulqi_Charges::create( $args_charges );
			//echo var_dump($installments);
            //echo var_dump($culqi_charge);
			if( $culqi_charge['status'] != 'ok' ) {
				$error = sprintf(
					esc_html__( 'Culqi Charge Error: %s', 'fullculqi' ),
					$culqi_charge['data']
				);
				self::$log->set_notice( $error );
                if(isset($culqi_charge['action_code']) and $culqi_charge['action_code']=='REVIEW'){
                    return $culqi_charge['action_code'];
                }else{
                    $json = json_decode($culqi_charge['data'], true);
                    return $json['user_message'];
                }

			}
			$culqi_charge_id = $culqi_charge['data']['culqi_charge_id'];
			$post_charge_id = $culqi_charge['data']['post_charge_id'];

			// Meta value
			update_post_meta( $post_data['order_id'], '_culqi_charge_id', $culqi_charge_id );

			// Log
			$notice = sprintf(
				esc_html__( 'Culqi Charge Created: %s', 'fullculqi' ),
				$culqi_charge_id
			);

			$order->add_order_note( $notice );
			self::$log->set_notice( $notice );


			// Log
			$notice = sprintf(
				esc_html__( 'Post Charge Created: %s', 'fullculqi' ), $post_charge_id
			);
			self::$log->set_notice( $notice );

			// Update PostID in WC-Order
			update_post_meta( $post_data['order_id'], '_post_charge_id', $post_charge_id );

			// Update OrderID in CulqiCharges
			update_post_meta( $post_charge_id, 'culqi_wc_order_id', $post_data['order_id'] );


			$status = apply_filters( 'fullculqi/process/change_status', [
				'name'	=> $method['status_success'],
				'note'	=> sprintf(
					esc_html__( 'Status changed by FullCulqi (to %s)', 'fullculqi' ),
					$method['status_success']
				),
			], $order );

			// Change Status
			$order->update_status( $status['name'], $status['note'] );
		}

		do_action( 'fullculqi/process/charge_success', $order );

		return true;
	}


	/**
	 * Create Customer
	 * @param  WP_OBJECT $order
	 * @return mixed
	 */
	public static function customer( $order ) {

		if( is_user_logged_in() )
			$culqi_customer = FullCulqi_Customers::get( get_current_user_id() );
		else
			$culqi_customer = FullCulqi_Customers::getByEmail( $order->get_billing_email() );


		if( ! empty( $culqi_customer ) ) {

			// Log Notice
			$notice = sprintf(
				esc_html__( 'Culqi Customer: %s', 'fullculqi' ), $culqi_customer['culqi_id']
			);
			self::$log->set_notice( $notice );

			// Update meta culqi id in wc order
			update_post_meta( $order->get_id(), '_culqi_customer_id', $culqi_customer['culqi_id'] );

			// Log
			$notice = sprintf(
				esc_html__( 'Post Customer: %s', 'fullculqi' ), $culqi_customer['post_id']
			);
			self::$log->set_notice( $notice );

			// Update meta post in wc order
			update_post_meta( $order->get_id(), '_post_customer_id', $culqi_customer['post_id'] );

			return true;
		}


		if( ! is_user_logged_in() )
			return false;


        if (version_compare(WC_VERSION, "2.7", "<")) {
            $args_customer = [
                'email'		=> $order->billing_email,
                'metadata'	=> [ 'user_id' => get_current_user_id() ],
            ];
            $billing_first_name 	= $order->billing_first_name;
            $billing_last_name 		= $order->billing_last_name;
            $billing_phone 			= $order->billing_phone;
            $billing_address_1 		= $order->billing_city;
            $billing_city 			= $order->billing_city;
            $billing_country 		= $order->billing_country;
        }else{
            $args_customer = [
                'email'		=> $order->get_billing_email(),
                'metadata'	=> [ 'user_id' => get_current_user_id() ],
            ];
            $billing_first_name 	= $order->get_billing_first_name();
            $billing_last_name 		= $order->get_billing_last_name();
            $billing_phone 			= $order->get_billing_phone();
            $billing_address_1 		= $order->get_billing_address_1();
            $billing_city 			= $order->get_billing_city();
            $billing_country 		= $order->get_billing_country();
        }


		if( ! empty( $billing_first_name ) )
			$args_customer['first_name'] = $billing_first_name;

		if( ! empty( $billing_last_name ) )
			$args_customer['last_name'] = $billing_last_name;

		if( ! empty( $billing_phone ) )
			$args_customer['phone_number'] = $billing_phone;

		if( ! empty( $billing_address_1 ) )
			$args_customer['address'] = $billing_address_1;

		if( ! empty( $billing_city ) )
			$args_customer['address_city'] = $billing_city;

		if( ! empty( $billing_country ) )
			$args_customer['country_code'] = $billing_country;


		$culqi_customer = FullCulqi_Customers::create(
			get_current_user_id(), $args_customer
		);

		// Error
		if( $culqi_customer['status'] == 'error' ) {

			$error = sprintf(
				esc_html__( 'Culqi Customer Error: %s', 'fullculqi' ),
				$culqi_customer['data']
			);
			self::$log->set_error( $error );

			return false;
		}


		$culqi_customer_id = $culqi_customer['data']['culqi_customer_id'];
		$post_customer_id = $culqi_customer['data']['post_customer_id'];

		// Log Notice
		$notice = sprintf(
			esc_html__( 'Culqi Customer Created: %s', 'fullculqi' ), $culqi_customer_id
		);
		self::$log->set_notice( $notice );
        if (version_compare(WC_VERSION, "2.7", "<")) {
            update_post_meta( $order->id, '_culqi_customer_id', $culqi_customer_id );
            $notice = sprintf(
                esc_html__( 'Post Customer Created: %s', 'fullculqi' ), $post_customer_id
            );
            self::$log->set_notice( $notice );
            update_post_meta( $order->id, '_post_customer_id', $post_customer_id );
        }else{
            update_post_meta( $order->get_id(), '_culqi_customer_id', $culqi_customer_id );
            $notice = sprintf(
                esc_html__( 'Post Customer Created: %s', 'fullculqi' ), $post_customer_id
            );
            self::$log->set_notice( $notice );
            update_post_meta( $order->get_id(), '_post_customer_id', $post_customer_id );
        }
		return true;
	}

}
