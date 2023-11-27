<?php

#[\AllowDynamicProperties]
class FullCulqi_Checkout {

	static public function simple($order, $culqi_token, $log) {

		$pnames = $provider_payment = [];
		$method_array = fullculqi_get_woo_settings();

		extract($culqi_token);

		$log->set_msg_payment( 'notice', esc_html__( 'This order is a simple payment', 'culqi' ) );

		foreach ($order->get_items() as $item ) {
			$_product = $item->get_product();

			if( $_product && method_exists($_product, 'get_name' ) )
				$pnames[] = $_product->get_name();
		}

		// If empty
		if( count($pnames) == 0 )
			$pnames[0] = 'Product';


		// Antifraud Customer Data
		$antifraud = [ 'email' => $order->get_billing_email() ];

		$billing_first_name 	= $order->get_billing_first_name();
		$billing_last_name 		= $order->get_billing_last_name();
		$billing_address_1 		= $order->get_billing_address_1();
		$billing_phone 			= $order->get_billing_phone();
		$billing_city 			= $order->get_billing_city();
		$billing_country 		= $order->get_billing_country();

		if( !empty( $billing_first_name ) )
			$antifraud['first_name'] = $billing_first_name;

		if( !empty( $billing_last_name ) )
			$antifraud['last_name'] = $billing_last_name;

		if( !empty( $billing_address_1 ) )
			$antifraud['address'] = $billing_address_1;

		if( !empty( $billing_city ) )
			$antifraud['address_city'] = $billing_city;

		if( !empty( $billing_country ) )
			$antifraud['country_code'] = $billing_country;
		elseif( !empty($country_code) )
			$antifraud['country_code'] = $country_code;

		if( !empty( $billing_phone ) )
			$antifraud['phone_number'] = str_replace(' ', '', $billing_phone);


		// Metadata Order
		$metadata = [
			'order_id'		=> $order->get_id(),
			'order_number'	=> $order->get_order_number(),
			'order_key'		=> $order->get_order_key(),
		];

		$args_payment = apply_filters('fullculqi/checkout/simple_args', [
			'amount'			=> fullculqi_format_total($order->get_total()),
			'currency_code'		=> $order->get_currency(),
			'description'		=> 'Venta desde plugin WooCommece',//substr(str_pad(implode(', ', $pnames), 5, '_'), 0, 80),
			'capture'			=> true,
			'email'				=> $order->get_billing_email(),
			'installments'		=> $installments,
			'source_id'			=> $token_id,
			'metadata'			=> $metadata,
			'antifraud_details'	=> $antifraud,
		], $order );

		$provider_payment = FullCulqi_Provider::create_payment($args_payment);

		if( $provider_payment['status'] == 'ok' ) {

			$note = sprintf(
				esc_html__('Culqi Payment created: %s','culqi'),
				$provider_payment['data']->id
			);

			$order->add_order_note($note);

			update_post_meta( $order->get_id(), 'culqi_charge_id', wc_clean( $provider_payment['data']->id ) );

			$log->set_msg_payment('notice', sprintf(
				esc_html__('Culqi Payment created: %s','culqi'),
				$provider_payment['data']->id
			));

			$post_id = FullCulqi_Integrator::create_payment( $provider_payment['data'] );

			update_post_meta( $order->get_id(), 'culqi_post_id', wc_clean( $post_id ) );

			$log->set_msg_payment('notice', sprintf(
				esc_html__( 'Post Payment created : %s', 'culqi' ),
				$post_id
			));


			if( apply_filters( 'fullculqi/checkout/change_status', true, $log, $order ) ) {

				if( $method_array['status_success'] == 'wc-completed')
					$order->payment_complete();
				else
					$order->update_status($method_array['status_success']);
			}

			do_action('fullculqi/checkout/simple_success', $order, $log, $provider_payment );

		} else {

			$log->set_msg_payment('error', sprintf(
				esc_html__('Culqi Payment error : %s','culqi'),
				$provider_payment['msg']
			));

			do_action('fullculqi/checkout/simple_error', $order, $log, $provider_payment );
		}

		return $provider_payment;
	}


	static public function create_order($order, $duration, $product_names, $log) {

		// Antifraud Customer Data
		$client_details = [ 'email' => $order->get_billing_email() ];

		$billing_first_name 	= $order->get_billing_first_name();
		$billing_last_name 		= $order->get_billing_last_name();
		$billing_phone 			= $order->get_billing_phone();


		if( !empty( $billing_first_name ) )
			$client_details['first_name'] = $billing_first_name;

		if( !empty( $billing_last_name ) )
			$client_details['last_name'] = $billing_last_name;

		if( !empty( $billing_phone ) )
			$client_details['phone_number'] = str_replace(' ', '', $billing_phone);

		$args_order = apply_filters('fullculqi/checkout/order_args', [
			'amount'			=> fullculqi_format_total($order->get_total()),
			'currency_code'		=> $order->get_currency(),
			'description'		=> substr(str_pad(implode(', ', $product_names), 5, '_'), 0, 80),
			'order_number'		=> $order->get_order_number(),
			'client_details'	=> $client_details,
			'confirm'			=> false,
			'expiration_date'	=> time() + ( $duration * HOUR_IN_SECONDS ),
			'metadata'			=> [
									'order_id'		=> $order->get_id(),
									'order_number'	=> $order->get_order_number(),
									'order_key'		=> $order->get_order_key(),
								],
		], $order);

		$provider_order = FullCulqi_Provider::create_order($args_order);

		if( $provider_order['status'] == 'ok' ) {

			$log->set_msg_payment('notice', sprintf(esc_html__('Culqi Multipayment created: %s','culqi'), $provider_order['data']->id) );

			$provider_order = apply_filters('fullculqi/checkout/order_success', $provider_order, $log, $order);

		} else {

			$log->set_msg_payment('error', sprintf(esc_html__('Culqi Multipayment error : %s','culqi'), $provider_order['msg']) );

			$provider_order = apply_filters('fullculqi/checkout/order_error', $provider_order, $log, $order);
		}

		return $provider_order;
	}


	static function process_order($order, $cip_code, $log ) {

		$log->set_msg_payment('notice', esc_html__('This order is a Multipayment', 'culqi') );
		$log->set_msg_payment('notice', sprintf(esc_html__('Culqi Multipayment CIP: %s','culqi'), $cip_code) );

		$note = esc_html__('Culqi Method: Multipayment','culqi');
		$order->add_order_note($note);

		if( apply_filters( 'fullculqi/checkout/order_change_status', true, $log, $order ) ) {

			$method_array = fullculqi_get_woo_settings();

			if( $method_array['multi_status'] == 'wc-completed')
				$order->payment_complete();
			else
				$order->update_status($method_array['multi_status']);
		}


		$note = sprintf(esc_html__('Culqi Multipayment CIP: %s','culqi'), $cip_code);
		$order->add_order_note($note);

		update_post_meta($order->get_id(), 'culqi_cip', $cip_code);

		$provider_order = [ 'status' => 'ok' ];

		$provider_order = apply_filters('fullculqi/checkout/order_process', $provider_order, $log, $order);

		return $provider_order;
	}


	static function create_refund( $order, $amount = 0.00, $reason = '', $log ) {

		$culqi_charge_id	= get_post_meta( $order->get_id(), 'culqi_charge_id', true );
		$culqi_post_id		= get_post_meta( $order->get_id(), 'culqi_post_id', true );

		$args = apply_filters( 'fullculqi/checkout/refund_args', [
			'amount'	=> round( $amount*100, 0 ),
			'charge_id'	=> $culqi_charge_id,
			'reason'	=> 'solicitud_comprador',
		], $order );


		$provider_refund = FullCulqi_Provider::refund_payment( $args );

		if( $provider_refund['status'] == 'ok' ) {

			$log->set_msg_payment( 'notice', sprintf(
				esc_html__( 'Culqi Refund created: %s', 'culqi' ),
				$provider_refund['data']->id)
			);

			$provider_refund = apply_filters( 'fullculqi/checkout/refund_success',
				$provider_refund, $log, $order
			);

		} else {

			$log->set_msg_payment( 'error', sprintf(
				esc_html__( 'Culqi Refund error : %s', 'culqi' ), $provider_refund['msg']
			));

			$provider_refund = apply_filters('fullculqi/checkout/refund_error',
				$provider_refund, $log, $order
			);
		}

		return $provider_refund;
	}
}
