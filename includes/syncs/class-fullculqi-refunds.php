<?php
/**
 * Refunds Class
 * @since  1.0.0
 * @package Includes / Sync / Refunds
 */
class FullCulqi_Refunds {

	/**
	 * Create Refund
	 * @param  string $charge_id
	 * @param  integer $post_id
	 * @param  float  $amount
	 * @return bool
	 */
	public static function create( $args = [], $post_id = 0 ) {

		global $culqi;

		$args = apply_filters( 'fullculqi/refunds/create/args', $args, $post_id );

		try {
			$refunds = $culqi->Refunds->create( $args );
		} catch(Exception $e) {
			return [ 'status' => 'error', 'data' => $e->getMessage() ];
		}


		if( ! isset( $refunds->object ) || $refunds->object == 'error' ) {
			return [ 'status' => 'error', 'data' => $refund->merchant_message ];
		}

		update_post_meta( $post_id, 'culqi_data', $refunds );
		update_post_meta( $post_id, 'culqi_status', 'refunded' );

		// Save Refund
		$basic = get_post_meta( $post_id, 'culqi_basic', true );
		$refunds_ids = get_post_meta( $post_id, 'culqi_ids_refunded', true );
		$refunds_ids = ! empty( $refunds_ids ) ? $refunds_ids : [];
		
		$refunds_ids[ $refunds->id ] = number_format( $refunds->amount / 100, 2, '.', '' );
		
		$basic['culqi_amount_refunded'] = array_sum( $refunds_ids );

		update_post_meta( $post_id, 'culqi_basic', $basic );
		update_post_meta( $post_id, 'culqi_ids_refunded', $refunds_ids );

		do_action( 'fullculqi/refunds/create', $post_id, $refunds );

		return apply_filters( 'fullculqi/refunds/create/success', [
			'status' => 'ok',
			'data' => [ 'culqi_refund_id' => $refunds->id, 'post_charge_id' => $post_id ]
		] );
	}

}