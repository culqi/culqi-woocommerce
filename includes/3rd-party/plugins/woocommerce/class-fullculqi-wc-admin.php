<?php
/**
 * WooCommerce Class
 * @since  1.0.0
 * @package Includes / 3rd-party / plugins / WooCommerce
 */

 #[\AllowDynamicProperties]
class FullCulqi_WC_Admin {

	public function __construct() {
		// Metaboxes to Shop Order CPT
		add_action( 'add_meta_boxes_shop_order', [ $this, 'metaboxes'], 10, 1 );

		// Metaboxes Charges columns
		add_filter( 'fullculqi/charges/column_name', [ $this, 'column_name' ], 10, 2 );
		add_filter( 'fullculqi/charges/column_value', [ $this, 'column_value' ], 10, 3 );
		add_filter( 'fullculqi/orders/column_name', [ $this, 'column_name' ], 10, 2 );
		add_filter( 'fullculqi/orders/column_value', [ $this, 'column_value' ], 10, 3 );

		// Metaboxes Charges Edit
		add_action(  'fullculqi/charges/basic/print_data', [ $this, 'basic_print_order' ] );
		add_action(  'fullculqi/orders/basic/print_data', [ $this, 'basic_print_order' ] );

		// Create WPPost
		add_action( 'fullculqi/charges/sync/loop', [ $this, 'link_to_wc_orders' ], 10, 2 );
		add_action( 'fullculqi/customers/link_to_email', [ $this, 'link_to_wc_email' ], 10, 2 );

		// Ajax Refund
		//add_action( 'fullculqi/refunds/create/args', [ $this, 'create_refund_args' ], 10, 2 );
		add_filter( 'fullculqi/ajax/refund/process', [ $this, 'create_refund_process' ], 10, 2 );

		// Upgrader to 2.0.0
		add_action( 'fullculqi/upgrader/2_0_0/charges', [ $this, 'upgrader_charges_2_0_0' ], 10, 1 );
		add_action( 'fullculqi/upgrader/2_0_0/after', [ $this, 'upgrader_wc_orders_2_0_0' ] );
	}

	/**
	 * Add Meta Boxes to Shop Order CPT
	 * @param  WP_POST $post
	 * @return mixed
	 */
	public function metaboxes( $post ) {

		$culqi_log = get_post_meta( $post->ID, 'culqi_log', true );

		if( empty( $culqi_log ) )
			return;

		add_meta_box(
			'fullculqi_payment_log',
			esc_html__( 'Culqi Logs', 'fullculqi' ),
			[ $this, 'metabox_log' ],
			'shop_order',
			'normal',
			'core'
		);
	}


	/**
	 * Metaboxes Log
	 * @param  WP_POST $post
	 * @return mixed
	 */
	public function metabox_log( $post ) {

		$args = [
			'logs' => get_post_meta( $post->ID, 'culqi_log', true )
		];

		fullculqi_get_template( 'layouts/order_log.php', $args, MPCULQI_WC_DIR );
	}


	/**
	 * Charges Column Name
	 * @param  array $newCols]
	 * @param  [type] $cols
	 * @return array
	 */
	public function column_name( $newCols = [], $cols ) {

		if( ! class_exists( 'WooCommerce' ) )
			return $newCols;

		$newCols['culqi_wc_order_id']	= esc_html__( 'WC Order', 'fullculqi' );

		return $newCols;
	}


	/**
	 * Charge Column Value
	 * @param  string  $value
	 * @param  string  $col
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function column_value( $value = '', $col = '', $post_id = 0 ) {
		if( $col != 'culqi_wc_order_id' )
			return $value;

		$value = '';
		$order_id = get_post_meta( $post_id, 'culqi_wc_order_id', true );

		if( ! empty( $order_id ) ) {
			$url = get_edit_post_link( $order_id );
			if(!$url) {
				$url = esc_url( admin_url( 'admin.php?page=wc-orders&action=edit&id=' . $order_id ) );
			}

			$value = sprintf(
				'<a target="_blank" href="%s">%s</a>',
				$url, $order_id
			);
		}

		return $value;
	}


	/**
	 * Print WC Order in Metaboxes Basic
	 * @param  integer $post_id
	 * @return html
	 */
	public function basic_print_order( $post_id = 0 ) {

		if( empty( $post_id ) )
			return;

		$args = [
			'order_id' => get_post_meta( $post_id, 'culqi_wc_order_id', true ),
		];

		fullculqi_get_template( 'layouts/charge_basic.php', $args, MPCULQI_WC_DIR );
	}


	/**
	 * Link Charge to WC Orders
	 * @param  Culqi Object  $charge
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function link_to_wc_orders( $charge, $post_id = 0 ) {

		if( empty( $charge ) || empty( $post_id ) )
			return;

		$order_id = fullculqi_post_from_meta( '_culqi_charge_id', $charge->id );
		$order = wc_get_order( $order_id );

		if( ! $order )
			return;

		// WC Order Meta - Customer
		$culqi_customer_id = get_post_meta( $post_id, 'culqi_customer_id', true );

		if( ! empty( $culqi_customer_id ) ) {
			$post_customer_id = fullculqi_post_from_meta( 'culqi_id', $culqi_customer_id );

			// WC Order - Charge
			update_post_meta( $order->get_id(), '_culqi_customer_id', $culqi_customer_id );
			update_post_meta( $order->get_id(), '_post_customer_id', $post_customer_id );
		}

		// Update WC Order in Charge CPT
		update_post_meta( $post_id, 'culqi_wc_order_id', $order_id );

		// WC Order - Charge
		update_post_meta( $order->get_id(), '_culqi_charge_id', $charge->id );
		update_post_meta( $order->get_id(), '_post_charge_id', $post_id );

		return true;
	}


	/**
	 * [link_to_wc_email description]
	 * @param  Culqi Object  $customer
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function link_to_wc_email( $customer, $post_id = 0 ) {

		$user_id = fullculqi_user_from_meta( 'billing_email', $customer->email );

		if( empty( $user_id ) ) {
			$user = get_user_by( 'email', $customer->email );

			if( empty( $user ) )
				return;

			$user_id = $user->ID;
		}

		update_post_meta( $post_id, 'culqi_wp_user_id', $user_id );
		update_user_meta( $user_id, '_culqi_customer_id', $customer->id );
		update_user_meta( $user_id, '_post_customer_id', $post_id );

		return true;
	}


	/**
	 * Create Args to Refund
	 * @param  array   $args
	 * @param  integer $post_charge_id
	 * @return array
	 */
	public function create_refund_args( $args = [], $post_charge_id = 0 ) {

		if( isset( $args['metadata']['order_id'] ) )
			return $args;

		$order_id = get_post_meta( $post_charge_id, 'culqi_wc_order_id', true );

		$order 	= wc_get_order( $order_id );

		if( ! $order )
			return $args;

		$args['metadata']['order_id'] = $order->get_id();
		$args['metadata']['order_key'] = $order->get_order_key();

		return $args;
	}


	/**
	 * Create Refund
	 * @param  array $refund
	 * @param  integer $post_charge_id
	 * @return mixed
	 */
	public function create_refund_process( $refund = [], $post_charge_id = 0 ) {

		if( empty( $post_charge_id ) )
			return [ 'status' => 'error', 'data' => esc_html__( 'Post Charge empty', 'fullculqi' ) ];

		// WC Order ID
		$order_id = get_post_meta( $post_charge_id, 'culqi_wc_order_id', true );

		$order 	= wc_get_order( $order_id );

		if( ! $order )
			return [ 'status' => 'error', 'data' => esc_html__( 'WC Order doesnt exist', 'fullculqi' ) ];

		$log = new FullCulqi_Logs( $order->get_id() );

		// WC Refund
		$basic = get_post_meta( $post_charge_id, 'culqi_basic', true );

		$wc_refund = wc_create_refund( [
			'amount'			=> wc_format_decimal( $basic['culqi_amount'] ),
			'reason'			=> 'solicitud_comprador',
			'order_id'			=> $order_id,
			'line_items'		=> [],
			'refund_payment'	=> true,
			'restock_items'		=> true,
		] );

		return [ 'status' => 'ok' ];
	}


	/**
	 * Update the new version 2.0.0 to charges
	 * @param  integer $post_id
	 * @return mixed
	 */
	public function upgrader_charges_2_0_0( $post_id = 0 ) {

		if( empty( $post_id ) )
			return;

		// Get WC Order ID from Charges
		$wc_order_id = get_post_meta( $post_id, 'culqi_order_id', true );

		// Update WC Order ID in Charges
		if( ! empty( $wc_order_id ) )
			update_post_meta( $post_id, 'culqi_wc_order_id', $wc_order_id );

		// Delete old WC Order ID in Charges
		if( ! empty( $wc_order_id ) )
			delete_post_meta( $post_id, 'culqi_order_id', $wc_order_id );

	}

	/**
	 * Update the new version 2.0.0 to WC Orders
	 * @return mixed
	 */
	public function upgrader_wc_orders_2_0_0() {

		$args = [ 'payment_method' => 'fullculqi' ];

		$orders = wc_get_orders( $args );

		foreach( $orders as $order ) {

			// get the meta values
			$post_charge_id = get_post_meta( $order->get_id(), 'culqi_post_id', true );
			$culqi_charge_id = get_post_meta( $order->get_id(), 'culqi_charge_id', true );
			$culqi_order_id = get_post_meta( $order->get_id(), 'culqi_order', true );
			$culqi_cip = get_post_meta( $order->get_id(), 'culqi_cip', true );

			// Update/Delete the post charge id
			if( ! empty( $post_charge_id ) ) {
				update_post_meta( $order->get_id(), '_post_charge_id', $post_charge_id );
				delete_post_meta( $order->get_id(), 'culqi_post_id', $post_charge_id );
			}

			// Update/Delete the culqi charge id

			if( ! empty( $culqi_charge_id ) ) {
				update_post_meta( $order->get_id(), '_culqi_charge_id', $culqi_charge_id );
				delete_post_meta( $order->get_id(), 'culqi_charge_id', $culqi_charge_id );
			}

			// Update/Delete the culqi order id
			if( ! empty( $culqi_order_id ) ) {
				update_post_meta( $order->get_id(), '_culqi_order_id', $culqi_order_id );
				delete_post_meta( $order->get_id(), 'culqi_order', $culqi_order_id );
			}

			// Update/Delete the culqi cip
			if( ! empty( $culqi_cip ) ) {
				update_post_meta( $order->get_id(), '_culqi_cip', $culqi_cip );
				delete_post_meta( $order->get_id(), 'culqi_cip', $culqi_cip );
			}

		}

	}
}

new FullCulqi_WC_Admin();
