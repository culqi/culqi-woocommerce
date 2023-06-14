<?php/*
class FullCulqi_WC {
	public function __construct() {

		add_action('woocommerce_api_fullculqi_create_payment', [ $this, 'do_payment' ]);
		add_action('woocommerce_api_fullculqi_create_order', [ $this, 'do_order' ]);
		add_action('woocommerce_api_fullculqi_update_order', [ $this, 'update_order' ]);
	}

	public function do_payment() {

		if( isset($_POST) ) {
			$order_id 		= sanitize_key($_POST['order_id']);
			$token_id		= sanitize_text_field($_POST['token_id']);
			$country_code	= sanitize_text_field($_POST['country_code']);
			$installments 	= isset($_POST['installments']) ? (int)sanitize_key($_POST['installments']) : 0;

			$order = new WC_Order( $order_id );

			if( $order && wp_verify_nonce( $_POST['wpnonce'], 'fullculqi' ) ) {

				$provider_payment = [];

				// Logs
				$log = new FullCulqi_Logs();
				$log->set_settings_payment($order_id);


				if( apply_filters( 'fullculqi/do_payment/conditional', false, $order, $log ) ) {

					$provider_payment = apply_filters('fullculqi/do_payment/create', $provider_payment, compact('token_id', 'installments', 'country_code'), $log, $order);

				} else {

					$provider_payment = FullCulqi_Checkout::simple($order, compact('token_id', 'installments', 'country_code'), $log );
				}


				// If empty
				if( count( $provider_payment ) == 0 ) {

					$log->set_msg_payment('error',
						esc_html__( 'Culqi Provider Payment error : There was not set any payment','culqi'
					));

					$provider_payment = [ 'status' => 'error' ];
				}

				wp_send_json($provider_payment);
			}
		}

		die();
	}

	public function do_order() {
		if( isset($_POST) ) {
			$order_id 		= sanitize_key($_POST['order_id']);
			$cip_code		= sanitize_key($_POST['cip_code']);

			$order = new WC_Order( $order_id );

			if( $order && wp_verify_nonce( $_POST['wpnonce'], 'fullculqi' ) ) {

				$provider_order = array();

				// Logs
				$log = new FullCulqi_Logs();
				$log->set_settings_payment($order_id);


				if( apply_filters('fullculqi/do_order/conditional', false, $order, $log) ) {

					$provider_order = apply_filters('fullculqi/do_order/create', $provider_order, $cip_code, $log, $order);

				} else {

					$provider_order = FullCulqi_Checkout::process_order($order, $cip_code, $log );
				}


				// If empty
				if( count($provider_order) == 0 ) {

					$log->set_msg_payment('error',
						esc_html__('Culqi Provider Order error : There was not set any payment','culqi'
					));

					$provider_order = [ 'status' => 'error' ];
				}

				wp_send_json( $provider_order );
			}
		}

		die();
	}


	public function update_order() {

		$inputJSON	= file_get_contents('php://input');
		$input 		= json_decode( $inputJSON );


		if( $input->object == 'event' && $input->type == 'order.status.changed' ) {

			$data = json_decode($input->data);
			$order_id = $data->metadata->order_id;

			$cip_code = $data->payment_code;
			$order = new WC_Order( $order_id );

			if( $order ) {

				$method_array = fullculqi_get_woo_settings();

				// Logs
				$log = new FullCulqi_Logs();
				$log->set_settings_payment($order_id);

				switch($data->state) {
					case 'paid' :
						$note = sprintf(
							esc_html__( 'The order was paid. The CIP %s was paid', 'culqi'),
							$cip_code
						);
						$order->add_order_note($note);
						$log->set_msg_payment('notice', sprintf(
							esc_html__( 'The CIP %s was paid', 'culqi' ),
							$cip_code
						));
						$order->update_status( 'Processing' );
						break;
					case 'expired' :

						$log->set_msg_payment( 'notice', sprintf(
							esc_html__( 'The CIP %s expired', 'culqi' ),
							$cip_code
						));

						$order->update_status( 'cancelled', sprintf(
							esc_html__('The order was not paid on time. The CIP %s expired','culqi'),
							$cip_code
						));

						break;

					case 'deleted' :

						$log->set_msg_payment('notice', sprintf(
							esc_html__( 'The CIP %s was deleted', 'culqi' ), $cip_code
						));

						$order->update_status( 'cancelled', sprintf(
							esc_html__( 'The order was not paid on time. The CIP %s was deleted','culqi'), $cip_code
						));

						break;
				}

				do_action( 'fullculqi/update_order/' . $data->state, $order, $log, $data );
			}
		}

		http_response_code(200);
		echo wp_send_json( ['result' => 'success' ] );
		die();
	}
}
*/
?>
