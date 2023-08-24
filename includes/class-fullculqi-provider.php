<?php
#[\AllowDynamicProperties]
class FullCulqi_Provider {

	/**
	 * Payment List
	 * @param  integer $records [description]
	 * @return [type]           [description]
	 */
	static public function list_payments( $records = 100 ) {
		global $culqi;

		// Validate $culqi global
		if( ! $culqi )
			return [ 'status' => 'error', 'msg' => esc_html__('There is not Culqi credentials', 'culqi') ];

		// Connect to the API Culqi
		try {
			$payments = $culqi->Charges->all( [ 'limit' => $records ] );

			if( isset($payments->data) && count($payments->data) > 0 )
				$output = [ 'status' => 'ok', 'data' => $payments->data ];
			else
				$output = [ 'status' => 'error', 'msg' => $payments->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}


	/**
	 * Create Payment
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	static public function create_payment( $args ) {
		global $culqi;

		try {
			$payment = $culqi->Charges->create($args);

			if( isset($payment->object) && $payment->object != 'error' )
				$output = [ 'status' => 'ok', 'data' => $payment ];
			else
				$output = [ 'status' => 'error', 'msg' => $payment->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}

	/**
	 * Create Order
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	static public function create_order($args) {
		global $culqi;

		try {
			$order = $culqi->Orders->create( $args );

			if( isset($order->object) && $order->object != 'error' )
				$output = [ 'status' => 'ok', 'data' => $order ];
			else
				$output = [ 'status' => 'error', 'msg' => $order->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}

	/**
	 * Create Token
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	static public function create_token($args) {
		global $culqi_token;

		try {
			$token = $culqi_token->Tokens->create( $args );

			if( isset($token->object) && $token->object != 'error' )
				$output = [ 'status' => 'ok', 'data' => $token ];
			else
				$output = [ 'status' => 'error', 'msg' => $token->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}

	/**
	 * Capture Payment
	 * @param  [type] $charge_id [description]
	 * @return [type]            [description]
	 */
	static public function capture_payment( $charge_id ) {
		global $culqi;

		try {
			$capture = $culqi->Charges->capture( $charge_id );

			if( isset($capture->object) && $capture->object != 'error' )
				$output = [ 'status' => 'ok', 'data' => $capture ];
			else
				$output = [ 'status' => 'error', 'msg' => $capture->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}

	/**
	 * Refund Payment
	 * @param  [type] $args [description]
	 * @return [type]       [description]
	 */
	static public function refund_payment( $args ) {
		global $culqi;

		try {
			$refund = $culqi->Refunds->create( $args );

			if( isset($refund->object) && $refund->object != 'error' )
				$output = [ 'status' => 'ok', 'data' => $refund ];
			else
				$output = [ 'status' => 'error', 'msg' => $refund->merchant_message ];

		} catch(Exception $e) {
			$output = [ 'status' => 'error', 'msg' => $e->getMessage() ];
		}

		return $output;
	}
}
?>
