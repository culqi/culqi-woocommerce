<?php
/**
 * Orders Class
 * @since  1.0.0
 * @package Includes / Sync / Orders
 */
class FullCulqi_Orders {

	/**
	 * Sync from Culqi
	 * @param  integer $records
	 * @return mixed
	 */
	public static function sync( $records = 100, $after_id = '' ) {
		global $culqi;

		$params = [ 'limit' => $records ];

		if( ! empty( $after_id ) )
			$params[ 'after' ] = $after_id;

		// Connect to the API Culqi
		try {
			$culqi_orders = $culqi->Orders->all( $params );
		} catch(Exception $e) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}

		if( isset( $culqi_orders->object ) && $culqi_orders->object == 'error' )
			return [ 'status' => 'error', 'data' => $culqi_orders->merchant_message ];

		// Empty data
		if( isset( $culqi_orders->data ) && empty( $culqi_orders->data ) ) {
			return [
				'status'	=> 'ok',
				'data'		=> [
					'after_id'	=> null,
				]
			];
		}

		global $wpdb;

		$query = 'SELECT
					p.ID AS post_id,
					m.meta_value AS culqi_id
				FROM
					'.$wpdb->posts.' AS p
				INNER JOIN
					'.$wpdb->postmeta.' AS m
				ON
					p.ID = m.post_id
				WHERE
					p.post_type = "culqi_orders" AND
					m.meta_key = "culqi_id" AND
					m.meta_value <> ""';

		// @codingStandardsIgnoreStart
		$results = $wpdb->get_results( $query );
		// @codingStandardsIgnoreEnd

		$keys = [];

		// Keys Post Type
		foreach( $results as $result )
			$keys[ $result->culqi_id ] = $result->post_id;

		// Culqi orders
		foreach( $culqi_orders->data as $culqi_order ) {

			$post_id = 0;

			// Check if is update
			if( isset( $keys[ $culqi_order->id ] ) )
				$post_id = $keys[ $culqi_order->id ];

			$post_id = self::create_wppost( $culqi_order, $post_id );

			do_action( 'fullculqi/orders/sync/loop', $culqi_order, $post_id );
		}

		do_action( 'fullculqi/orders/sync/after', $culqi_orders );

		return [
			'status'	=> 'ok',
			'data'		=> [
				'after_id'	=> $culqi_orders->paging->cursors->after,
			]
		];
	}

	/**
	 * Create Order
	 * @param  array $args_order
	 * @return array
	 */
	public static function create( $args_order = [] ) {

		global $culqi;

		try {
			$culqi_order = $culqi->Orders->create( $args_order );

		} catch( Exception $e ) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}

		if( ! isset( $culqi_order->object ) || $culqi_order->object == 'error' ) {
			return [ 'status' => 'error', 'data' => $culqi_order->merchant_message ];
		}

		do_action( 'fullculqi/orders/create', $culqi_order );

		return apply_filters( 'fullculqi/orders/create/success', [
			'status'	=> 'ok',
			'data'		=> [ 'culqi_order_id' => $culqi_order->id ]
		] );
	}

	/**
	 * Create the CIP Code
	 * @param  array  $post_data
	 * @param  int  $post_customer_id
	 * @return mixed
	 */
	public static function after_confirm( $post_data = [], $post_customer_id = 0 ) {
		if( empty( $post_data ) ) {
			return [
				'status' => 'error',
				'data' => esc_html__( 'Culqi Order Data empty', 'fullculqi' )
			];
		}
        $culqi_data['enviroment'] = $post_data['enviroment'];
		$culqi_data['metadata'] = [ 'cip_code' => $post_data['cip_code'] ];

		if( ! empty( $post_customer_id ) )
			$culqi_data['metadata']['post_customer_id'] = $post_customer_id;


		global $culqi;
		try {
			$culqi_order = $culqi->Orders->update( $post_data['id'], $culqi_data );
		} catch( Exception $e ) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}


		if( ! isset( $culqi_order->object ) || $culqi_order->object == 'error' ) {
			return [ 'status' => 'error', 'data' => $culqi_order->merchant_message ];
		}

		// Update post
		$post_id = self::create_wppost( $culqi_order );

		do_action( 'fullculqi/orders/confirm', $culqi_order );

		return apply_filters( 'fullculqi/orders/confirm/success', [
			'status'	=> 'ok',
			'data'		=> [ 'culqi_order_id' => $culqi_order->id, 'post_order_id' => $post_id ]
		] );
	}

	/**
	 * Update Order from webhook
	 * @param  object $culqi_order
	 * @return mixed
	 */
	public static function update( $culqi_order, $wh = 0) {

		if( ! isset( $culqi_order->id ) )
			return;

		//$cip_code = trim( $culqi_order->payment_code );
		$post_id = fullculqi_post_from_meta( 'culqi_id', $culqi_order->id );

		if($wh>0){
            if( ! empty( $post_id ) )
                $post_id = self::create_wppost_wh( $culqi_order, $post_id );
        }else{
            if( ! empty( $post_id ) )
                $post_id = self::create_wppost( $culqi_order, $post_id );
        }


		do_action( 'fullculqi/orders/update', $culqi_order );
	}

	/**
	 * Create Order Post
	 * @param  integer $post_id
	 * @param  objt $culqi_order
	 * @param  integer $post_customer_id
	 * @return integer
	 */
	public static function create_wppost( $culqi_order, $post_id = 0 ) {

		if( empty( $post_id ) ) {

			// Create Post
			$args = [
				'post_title'	=> $culqi_order->id,
				'post_type'		=> 'culqi_orders',
				'post_status'	=> 'publish',
			];

			$post_id = wp_insert_post( $args );
		}

		$amount = round( $culqi_order->amount/100, 2 );

		update_post_meta( $post_id, 'culqi_id', $culqi_order->id );
		update_post_meta( $post_id, 'culqi_data', $culqi_order );
		update_post_meta( $post_id, 'culqi_status', $culqi_order->state );
		update_post_meta( $post_id, 'culqi_status_date', date('Y-m-d H:i:s') );

		// CIP CODE
		$culqi_cip = '';
		if( ! empty( $culqi_order->payment_code ) )
			$culqi_cip = $culqi_order->payment_code;
		elseif( isset( $culqi_order->metadata->cip_code ) )
			$culqi_cip = $culqi_order->metadata->cip_code;

		update_post_meta( $post_id, 'culqi_cip', $culqi_cip );

		update_post_meta( $post_id, 'culqi_creation_date', fullculqi_convertToDate( $culqi_order->creation_date ) );

		$basic = [
			'culqi_expiration'		=> fullculqi_convertToDate( $culqi_order->expiration_date ),
			'culqi_amount'			=> $amount,
			'culqi_currency'		=> $culqi_order->currency_code,
		];

		update_post_meta( $post_id, 'culqi_basic', $basic );

		// Metavalues
		if( isset( $culqi_order->metadata ) && ! empty( $culqi_order->metadata ) )
			update_post_meta( $post_id, 'culqi_metadata', $culqi_order->metadata );

		// Customers
		$customer = [
			'post_id'	=> 0,
			'culqi_email'		=> '',
			'culqi_first_name'	=> '',
			'culqi_last_name'	=> '',
			'culqi_city'		=> '',
			'culqi_country'		=> '',
			'culqi_phone'		=> '',
		];

		// Save customer
		if( isset( $culqi_order->metadata->post_customer_id ) )
			$customer[ 'post_id' ] = $culqi_order->metadata->post_customer_id;

		if( isset( $culqi_order->metadata->customer_email ) )
			$customer[ 'culqi_email' ] = $culqi_order->metadata->customer_email;

		if( isset( $culqi_order->metadata->customer_first ) )
			$customer[ 'culqi_first_name' ] = $culqi_order->metadata->customer_first;

		if( isset( $culqi_order->metadata->customer_last ) )
			$customer[ 'culqi_last_name' ] = $culqi_order->metadata->customer_last;

		if( isset( $culqi_order->metadata->customer_city ) )
			$customer[ 'culqi_city' ] = $culqi_order->metadata->customer_city;

		if( isset( $culqi_order->metadata->customer_country ) )
			$customer[ 'culqi_country' ] = $culqi_order->metadata->customer_country;

		if( isset( $culqi_order->metadata->customer_phone ) )
			$customer[ 'culqi_phone' ] = $culqi_order->metadata->customer_phone;

		// Customer
		update_post_meta( $post_id, 'culqi_customer', $customer );


		do_action( 'fullculqi/orders/wppost', $culqi_order, $post_id );

		return $post_id;
	}

    public static function create_wppost_wh( $culqi_order, $post_id = 0 ) {

        if( empty( $post_id ) ) {

            // Create Post
            $args = [
                'post_title'	=> $culqi_order->id,
                'post_type'		=> 'culqi_orders',
                'post_status'	=> 'publish',
            ];

            $post_id = wp_insert_post( $args );
        }

        $amount = round( $culqi_order->amount/100, 2 );

        update_post_meta( $post_id, 'culqi_id', $culqi_order->id );
        //update_post_meta( $post_id, 'culqi_data', $culqi_order );
        update_post_meta( $post_id, 'culqi_status', $culqi_order->state );
        update_post_meta( $post_id, 'culqi_status_date', date('Y-m-d H:i:s') );

        // CIP CODE
        $culqi_cip = '';
        if( ! empty( $culqi_order->payment_code ) )
            $culqi_cip = $culqi_order->payment_code;
        elseif( isset( $culqi_order->metadata->cip_code ) )
            $culqi_cip = $culqi_order->metadata->cip_code;

        //update_post_meta( $post_id, 'culqi_cip', $culqi_cip );

        //update_post_meta( $post_id, 'culqi_creation_date', fullculqi_convertToDate( $culqi_order->creation_date ) );

        $basic = [
            'culqi_expiration'		=> fullculqi_convertToDate( $culqi_order->expiration_date ),
            'culqi_amount'			=> $amount,
            'culqi_currency'		=> $culqi_order->currency_code,
        ];

        //update_post_meta( $post_id, 'culqi_basic', $basic );

        // Metavalues
        if( isset( $culqi_order->metadata ) && ! empty( $culqi_order->metadata ) )
            //update_post_meta( $post_id, 'culqi_metadata', $culqi_order->metadata );

        // Customers
        $customer = [
            'post_id'	=> 0,
            'culqi_email'		=> '',
            'culqi_first_name'	=> '',
            'culqi_last_name'	=> '',
            'culqi_city'		=> '',
            'culqi_country'		=> '',
            'culqi_phone'		=> '',
        ];

        // Save customer
        if( isset( $culqi_order->metadata->post_customer_id ) )
            $customer[ 'post_id' ] = $culqi_order->metadata->post_customer_id;

        if( isset( $culqi_order->metadata->customer_email ) )
            $customer[ 'culqi_email' ] = $culqi_order->metadata->customer_email;

        if( isset( $culqi_order->metadata->customer_first ) )
            $customer[ 'culqi_first_name' ] = $culqi_order->metadata->customer_first;

        if( isset( $culqi_order->metadata->customer_last ) )
            $customer[ 'culqi_last_name' ] = $culqi_order->metadata->customer_last;

        if( isset( $culqi_order->metadata->customer_city ) )
            $customer[ 'culqi_city' ] = $culqi_order->metadata->customer_city;

        if( isset( $culqi_order->metadata->customer_country ) )
            $customer[ 'culqi_country' ] = $culqi_order->metadata->customer_country;

        if( isset( $culqi_order->metadata->customer_phone ) )
            $customer[ 'culqi_phone' ] = $culqi_order->metadata->customer_phone;

        // Customer
        //update_post_meta( $post_id, 'culqi_customer', $customer );


        do_action( 'fullculqi/orders/wppost', $culqi_order, $post_id );

        return $post_id;
    }

	/**
	 * Delete Posts
	 * @return mixed
	 */
	public static function delete_wpposts() {
		global $wpdb;

		$query = sprintf(
			'DELETE
				a, b, c
			FROM
				%s a
			LEFT JOIN
				%s b
			ON
				(a.ID = b.object_id)
			LEFT JOIN
				%s c
			ON
				(a.ID = c.post_id)
			WHERE
				a.post_type = "culqi_orders"',
			$wpdb->posts,
			$wpdb->term_relationships,
			$wpdb->postmeta
		);

		// @codingStandardsIgnoreStart
		$wpdb->query( $query );
		// @codingStandardsIgnoreEnd

		do_action( 'fullculqi/orders/wpdelete' );

		return true;
	}
}
