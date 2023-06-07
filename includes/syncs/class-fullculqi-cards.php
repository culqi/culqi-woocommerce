<?php
/**
 * Customers Class
 * @since  1.0.0
 * @package Includes / Sync / Customers
 */
class FullCulqi_Cards {

	/**
	 * Create Card
	 * @param  array  $args
	 * @return array
	 */
	public static function create( $args = [] ) {
		global $culqi;

		$args = apply_filters( 'fullculqi/cards/create/args', $args );

		try {
			$card = $culqi->Cards->create( $args );
		} catch(Exception $e) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}

		if( ! isset( $card->object ) || $card->object == 'error' )
			return [ 'status' => 'error', 'data' => $card->merchant_message ];

		do_action( 'fullculqi/cards/create', $card );

		return apply_filters( 'fullculqi/cards/create/success', [
			'status'	=> 'ok',
			'data'		=> [ 'culqi_card_id' => $card->id, 'culqi_card_data' => $card ]
		] );
	}


	/**
	 * Get Culqi Card ID
	 * @param  string $card_id
	 * @return array
	 */
	public static function get( $card_id = '' ) {
		global $culqi;

		$card_id = apply_filters( 'fullculqi/cards/get/id', $card_id );

		try {
			$card = $culqi->Cards->get( $card_id );
		} catch(Exception $e) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}

		if( ! isset( $card->object ) || $card->object == 'error' )
			return [ 'status' => 'error', 'data' => $card->merchant_message ];

		do_action( 'fullculqi/cards/get/after', $card );

		return apply_filters( 'fullculqi/cards/get/success', [
			'status'	=> 'ok',
			'data'		=> [ 'culqi_card_id' => $card->id, 'culqi_card_data' => $card ]
		] );
	}
}