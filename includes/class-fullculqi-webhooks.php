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

		// Webhook History
		$this->register( $input );

		switch( $input->type ) {
			case 'order.status.changed' : FullCulqi_Orders::update( $data ); break;

            case 'refund.creation.succeeded' : FullCulqi_Orders::update( $data ); break;
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
			'event_name'		=> $input->data,
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
