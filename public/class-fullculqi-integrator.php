<?php
#[\AllowDynamicProperties]
class FullCulqi_Integrator {

	static function create_payment( $payment ) {

		if( !isset($payment->id) )
			return;

		$args = apply_filters( 'fullculqi/integrator/payment_args', [
			'post_title'	=> $payment->id,
			'post_type'		=> 'culqi_payments',
			'post_status'	=> 'publish'
		], $payment );

		$post_id = wp_insert_post($args);
		

		$totime = (int)($payment->creation_date/1000);
		$amount = number_format($payment->amount/100, 2);
		$refund = number_format($payment->amount_refunded/100, 2);

		update_post_meta($post_id, 'culqi_id', $payment->id);
		update_post_meta($post_id, 'culqi_capture', $payment->capture);
		update_post_meta($post_id, 'culqi_capture_date', $payment->capture_date);
		update_post_meta($post_id, 'culqi_data', $payment);

		$status = $payment->capture ? 'captured' : 'authorized';
		update_post_meta( $post_id, 'culqi_status', $status );
		
		// IP Client
		if( isset($payment->source->client->ip) )
			update_post_meta($post_id, 'culqi_ip', $payment->source->client->ip);
		else
			update_post_meta($post_id, 'culqi_ip', $payment->source->source->client->ip);

		// Order ID Woocommerce
		if( isset($payment->metadata->order_id) )
			update_post_meta($post_id, 'culqi_order_id', $payment->metadata->order_id);
		else
			update_post_meta($post_id, 'culqi_order_id', '');

		//Culqi Card Brand
		if( isset($payment->source->iin->card_brand) )
			$culqi_card_brand = $payment->source->iin->card_brand;
		else
			$culqi_card_brand = $payment->source->source->iin->card_brand;

		//Culqi Card Type
		if( isset($payment->source->iin->card_type) )
			$culqi_card_type = $payment->source->iin->card_type;
		else
			$culqi_card_type = $payment->source->source->iin->card_type;

		//Culqi Card Number
		if( isset($payment->source->card_number) )
			$culqi_card_number = $payment->source->card_number;
		else
			$culqi_card_number = $payment->source->source->card_number;


		$basic = [
			'culqi_creation'		=> date('Y-m-d H:i:s', $totime),
			'culqi_amount'			=> $amount,
			'culqi_amount_refunded'	=> $refund,
			'culqi_currency'		=> $payment->currency_code,
			'culqi_card_brand'		=> $culqi_card_brand,
			'culqi_card_type'		=> $culqi_card_type,
			'culqi_card_number'		=> $culqi_card_number,
		];

		update_post_meta($post_id, 'culqi_basic', $basic);


		//Culqi First Name
		if( isset($payment->antifraud_details->first_name) )
			$culqi_first_name = $payment->antifraud_details->first_name;
		else
			$culqi_first_name = $payment->source->antifraud_details->first_name;

		//Culqi Last Name
		if( isset($payment->antifraud_details->last_name) )
			$culqi_last_name = $payment->antifraud_details->last_name;
		else
			$culqi_last_name = $payment->source->antifraud_details->last_name;

		//Culqi Last Name
		if( isset($payment->antifraud_details->last_name) )
			$culqi_last_name = $payment->antifraud_details->last_name;
		else
			$culqi_last_name = $payment->source->antifraud_details->last_name;

		//Culqi Address City
		if( isset($payment->antifraud_details->address_city) )
			$culqi_address_city = $payment->antifraud_details->address_city;
		else
			$culqi_address_city = $payment->source->antifraud_details->address_city;

		//Culqi Country Code
		if( isset($payment->antifraud_details->country_code) )
			$culqi_country_code = $payment->antifraud_details->country_code;
		else
			$culqi_country_code = $payment->source->antifraud_details->country_code;

		//Culqi Phone
		if( isset($payment->antifraud_details->phone) )
			$culqi_phone = $payment->antifraud_details->phone;
		else
			$culqi_phone = $payment->source->antifraud_details->phone;


		$customer = [
			'culqi_email'		=> $payment->email,
			'culqi_first_name'	=> $culqi_first_name,
			'culqi_last_name'	=> $culqi_last_name,
			'culqi_city'		=> $culqi_address_city,
			'culqi_country'		=> $culqi_country_code,
			'culqi_phone'		=> $culqi_phone,
		];

		update_post_meta($post_id, 'culqi_customer', $customer);

		do_action( 'fullculqi/integrator/payment', $post_id, $payment );

		return $post_id;
	}
}
?>