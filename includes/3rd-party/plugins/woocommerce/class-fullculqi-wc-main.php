<?php
/**
 * WooCommerce Class
 * @since  1.0.0
 * @package Includes / 3rd-party / plugins / WooCommerce
 */

 #[\AllowDynamicProperties]
class FullCulqi_WC {

	public $log;

	public function __construct() {
		// Load the method payment
		add_action( 'plugins_loaded', [ $this, 'include_file' ] );

		// Include Class
		add_filter( 'woocommerce_payment_gateways', [ $this, 'include_class' ] );

		// Actions
		add_action( 'fullculqi/api/wc-actions', [ $this, 'actions' ] );

		// Webhook Update order
		add_action( 'fullculqi/orders/update', [ $this, 'update_order' ] );

		// Old Webhook update order
		add_action( 'woocommerce_api_fullculqi_update_order', [ $this, 'old_update_order' ] );
	}


	/**
	 * Include the method payment
	 * @return mixed
	 */
	public function include_file() {

		// Check if WC is installed
		if ( ! class_exists( 'WC_Payment_Gateway' ) )
			return;

		// Check if WC has the supported currency activated
		$supported_currencies = array_keys( fullculqi_currencies() );
		if ( ! in_array( get_woocommerce_currency(), $supported_currencies ) ) {
			add_action( 'admin_notices', [ $this, 'notice_currency'] );
			return;
		}

		require_once MPCULQI_WC_DIR . 'class-fullculqi-wc-method.php';
	}

	/**
	 * Include the gateway class
	 * @param  array $methods
	 * @return array
	 */
	public function include_class( $methods = [] ) {

		// Check if FullCulqi Class is included
		if ( ! class_exists( 'WC_Gateway_FullCulqi' ) )
			return $methods;

		$methods[] = 'WC_Gateway_FullCulqi';

		return $methods;
	}

	/**
	 * Actions
	 * @return mixed
	 */
	public function actions() {

		if( ! isset( $_POST['action'] ) )
			return;

		// Run a security check.
		check_ajax_referer( 'fullculqi', 'wpnonce' );

		$return = false;
		$post_data = fullculqi_esc_html( $_POST );


		switch( $post_data['action'] ) {
			case 'order' : $return = FullCulqi_WC_Process::order( $post_data ); break;
			case 'charge' : $return = FullCulqi_WC_Process::charge( $post_data ); break;
		}
		//echo var_dump($return);
		$return = apply_filters( 'fullculqi/wc-actions', $return, $post_data );

		if($post_data['action'] == 'charge' && isset($return['data']['culqi_charge_id'])) {
			wp_send_json_success(array('charge'=>$return));
		}

		if( $return===true )
			wp_send_json_success();
		else
			wp_send_json_error(array('message'=>$return));
	}





	/**
	 * Update Order
	 * @param  OBJECT $culqi_order
	 * @return mixed
	 */
	public function update_order( $culqi_order ) {
		$settings = fullculqi_get_settings();
		if( ! isset( $culqi_order->id ) )
			return;

		$order_id = fullculqi_post_from_meta( '_culqi_order_id', $culqi_order->id );
		$order = new WC_Order( $order_id );

		if( ! $order )
			return;

		// Log
        if (version_compare(WC_VERSION, "2.7", "<")) {
            $log = new FullCulqi_Logs($order_id);
        }else{
            $log = new FullCulqi_Logs($order->get_id());
        }

		// Payment Settings
		$method = get_option( 'woocommerce_fullculqi_settings', [] );


		switch( $culqi_order->state ) {
			case 'paid' :

				$notice = sprintf(
					esc_html__( 'The CIP %s was paid', 'fullculqi' ),
					$cip_code
				);

				$order->add_order_note( $notice );
				$log->set_notice( $notice );

                /*$notice = sprintf(
                    'xxx',
                    $cip_code
                );

                $order->add_order_note( $notice );
                $log->set_notice( $notice );*/

				// Status

					$order->update_status( $settings['estado_pedido'],
						sprintf(
							esc_html__( 'Estado cambiado (a %s)', 'fullculqi' ),
							$method['status_success']
						)
					);


				break;


			case 'expired' :

				$error = sprintf(
					esc_html__( 'The CIP %s expired', 'fullculqi' ),
					$cip_code
				);

				$log->set_error( $error );
				$order->update_status( 'cancelled', $error );

				break;

			case 'deleted' :

				$error = sprintf(
					esc_html__( 'The CIP %s was deleted', 'fullculqi' ),
					$cip_code
				);

				$log->set_error( $error );
				$order->update_status( 'cancelled', $error );

				break;
		}

		return true;
	}

	/**
	 * [old_update_order description]
	 * @return [type] [description]
	 */
	/*
	public function old_update_order() {

		$inputJSON	= file_get_contents('php://input');

		if( empty( $inputJSON ) )
			return;

		$input = json_decode( $inputJSON );

		if( $input->object != 'event' )
			return;

		$data = json_decode( $input->data );

		if( $input->type != 'order.status.changed' )
			return;

		// Update Order
		FullCulqi_Orders::update( $data );

		http_response_code(200);
		echo wp_send_json( ['result' => 'success' ] );
		die();
	}
	*/
	/**
	 * Notice Currency
	 * @return html
	 */
	public function notice_currency() {
		fullculqi_get_template( 'layouts/notice_currency.php', [], MPCULQI_WC_DIR );
	}

}

new FullCulqi_WC();
