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

		if( empty( $inputJSON ) )
			return;

		$input = json_decode( $inputJSON );

		if( $input->object != 'event' )
			return;

		$data = json_decode( $input->data );

        if (empty($data->metadata)) {
            exit("Error: Metadata vacia");
        }

        if (empty($data->amount)) {
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
                        esc_html__('Estado cambiado (to %s)', 'fullculqi'),'refund'
                    )
                );
                fullculqi_update_post_meta('culqi_status', $charge_id, 'refunded');
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
		//var_dump(array_unshift( $webhooks_saved, $webhooks_in )); exit(1);
		update_option( 'fullculqi_webhooks', $webhooks_saved );

		return true;
	}
}

new FullCulqi_Webhooks();
