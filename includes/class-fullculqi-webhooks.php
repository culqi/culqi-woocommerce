<?php
/**
 * Webhooks Class
 * @since  1.0.0
 * @package Includes / Webhooks
 */
class FullCulqi_Webhooks {

	protected $limit = 25;

	/**
	 * Construct
	 */
	public function __construct() {
		add_action( 'fullculqi/api/webhooks', [ $this, 'to_receive' ] );
	}

	/**
	 * Receives the notification
	 * @return mixed
	 */
	public function to_receive() {

		$inputJSON	= file_get_contents('php://input');
		$headers = getallheaders();
		$headers = $headers['Authorization'];
		if(!isset($headers)){
			exit("Error: Cabecera Authorization no presente");
		}
	    $authorization = substr($headers,6);
        $credenciales = base64_decode($authorization);
        $credenciales = explode( ':', $credenciales );
        $username = $credenciales[0];
        $password = $credenciales[1];
		if(!isset($username) or !isset($password)){
			exit("Error: No Autorizado");
		}
		if( empty( $inputJSON ) )
			return;

		$input = json_decode( $inputJSON );
		$settings = fullculqi_get_settings();
		$username_bd = $settings['username'];
		$password_bd = $settings['password'];

		if( $username != $username_bd || $password != $password_bd ){
			exit("Error: Crendenciales Incorrectas");
		}

		if( $input->object != 'event' )
			return;

		$data = json_decode( $input->data );

        if (empty($data->metadata)) {
            exit("Error: Metadata vacia");
        }

        if (empty($data->amount) && empty($data->actualAmount)) {
            exit("Error: No envió el amount");
        }

		// Webhook History
		$this->register( $input );

		switch( $input->type ) {
            case 'order.status.changed' :
				if (empty($data->id) || empty($data->order_number) || empty($data->currency_code) || empty($data->state)) {
					exit("Error: order_id, order_number, currency_code o state vacios");
				}
                FullCulqi_Orders::update($data, 1);
                break;

            case 'refund.creation.succeeded' :
				if (empty($data->chargeId)) {
					exit("Error: No envió el chargeId");
				}
                $order_id = fullculqi_post_from_meta('_culqi_charge_id', $data->chargeId);
                $charge_id = fullculqi_post_from_meta('culqi_id', $data->chargeId);

                $order = new WC_Order($order_id);
                if (version_compare(WC_VERSION, "2.7", "<")) {
                    $log = new FullCulqi_Logs($order_id);
                } else {
                    $log = new FullCulqi_Logs($order->get_id());
                }
                $notice = sprintf(
                    esc_html__('The CHARGE %s was refund', 'fullculqi'),
                    $input->data->chargeId
                );

                $order->add_order_note($notice);
                $log->set_notice($notice);

                $order->update_status('refunded',
                    sprintf(
                        esc_html__('Estado cambiado (a %s)', 'fullculqi'),'refund'
                    )
                );
                fullculqi_update_post_meta('culqi_status', $charge_id, 'refunded');
                break;
				
			case 'charge.status.changed' :
				$order_id = $data->metadata->order_id;
				$order = wc_get_order( $order_id );
				if($order) {
					if (version_compare(WC_VERSION, "2.7", "<")) {
						$currency = $order->get_order_currency();
						$order_status = $order->get_post_status();
					} else {
						$currency = $order->get_currency();
						$order_status = $order->get_status();
					}

					if($order->get_payment_method() == "fullculqi") {
						if($order_status == "pending") {
							$verifyCharge = $this->verifyChargeInOrders($data->id, $order_id);
							if(!$verifyCharge) {
								$amount = $order->get_total() * 100;
								if($currency == $data->currency && $amount == $data->actualAmount) {
									FullCulqi_Charges::create( $data , true);
									die("Cargo actualizado con éxito");
									break;
								}
								die("La moneda o monto no coinciden con la orden.");
								break;
							}
							die($verifyCharge);
							break;
						}
						die("No se puede actualizar, la orden no esta pendiente pago.");
						break;
					}
					die("El método de pago usado en la orden no es Culqi.");
					break;
				}

				die("No existe la orden");
				break;

        }

		do_action( 'fullculqi/webhooks/to_receive', $input, $data );
	}


	/**
	 * [register description]
	 * @param  object $input
	 * @return mixed
	 */
	private function register( $input ) {

		$webhooks_saved = get_option( 'fullculqi_webhooks', [] );

		// Delete if it has many elements
		if( count( $webhooks_saved ) > $this->limit )
			array_pop( $webhooks_saved );

		$data = json_decode( $input->data );

		$webhooks_in = [
			'event_id'			=> $input->id,
			'event_name'		=> $input->type,
			'data_id'			=> isset( $data->id ) ? $data->id : '',
			'data_description'	=> isset( $data->description ) ? $data->description : '',
			'creation_date'		=> fullculqi_convertToDate( $input->creation_date ),
		];

		array_unshift( $webhooks_saved, $webhooks_in );
		update_option( 'fullculqi_webhooks', $webhooks_saved );

		return true;
	}

	private function verifyChargeInOrders($charge_id, $current_order_id)
	{
		$args = array(
			'post_type' => array('culqi_charges', 'culqi_orders'), 
			'posts_per_page' => -1,
		);
		
		$query = new WP_Query($args);
		
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				
				$order_title = get_the_title();
				$order_id = get_post_meta(get_the_ID(), 'culqi_wc_order_id', true);
				if ($order_title === $charge_id) {
					return "El cargo ya fue asigando al pedido: " . $order_id;
				}
				if ($order_id === $current_order_id) {
					return "El pedido ya tiene una orden o cargo asignado.";
				}
			}
			
			wp_reset_postdata(); // Restore the global post data
		} else {
			echo "No posts found.";
		}

		return false;
	}
	
}

new FullCulqi_Webhooks();
