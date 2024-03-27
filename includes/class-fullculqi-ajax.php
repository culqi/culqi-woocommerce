<?php
/**
 * This file contains the FullCulqi_Ajax class.
 *
 * PHP version 7.4
 *
 * @category Class
 * @package  Includes/Ajax
 * @author   FullCulqi <username@example.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     culqi
 * @since    1.0.0
 */

/**
 * My Ajax Class Commentent
 *
 * @category Class
 * @package  Includes/Ajax
 * @author   FullCulqi <username@example.com>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     culqi
 */

#[\AllowDynamicProperties]
class FullCulqi_Ajax {
	/**
	 * Constructor
	 */
	public function __construct() {

		// merchants.
		add_action( 'wp_ajax_culqi_merchants', [ $this, 'get_merchants' ] );

		// merchant.
		add_action( 'wp_ajax_culqi_merchant', [ $this, 'get_merchant' ] );

		// Create a refund.
		add_action( 'wp_ajax_create_culqi_refund', [ $this, 'create_refund' ] );

		// Delete All Charges.
		add_action( 'wp_ajax_delete_culqi_charges', [ $this, 'delete_charges' ] );

		// Delete All Orders.
		add_action( 'wp_ajax_delete_culqi_orders', [ $this, 'delete_orders' ] );

		// Delete All Customers.
		add_action( 'wp_ajax_delete_culqi_customers', [ $this, 'delete_customers' ] );

		// Sync Charges from the admin.
		add_action( 'wp_ajax_sync_culqi_charges', [ $this, 'sync_charges' ] );

		// Sync Orders from the admin.
		add_action( 'wp_ajax_sync_culqi_orders', [ $this, 'sync_orders' ] );

		// Sync Customers from the admin.
		add_action( 'wp_ajax_sync_culqi_customers', [ $this, 'sync_customers' ] );
	}

	/**
	 * Login from culqi
	 *
	 * @return void json
	 */
	public function get_merchants() {
		// Only verificate the nonce for token.
		check_ajax_referer( 'url-merc-wpnonce', 'nonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission.', 'fullculqi_subs' ) );
		}

		$token = isset( $_GET['token'] )
		? sanitize_text_field( wp_unslash( $_GET['token'] ) )
		: null;

		// Run a security check.
		$urlMerc = isset( $_GET['url_merchant'] )
		? sanitize_text_field( wp_unslash( $_GET['url_merchant'] ) )
		: null;

		$url_get_merchants = esc_url_raw( $urlMerc );

		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			),
			'timeout' => 120,
			'body'    => '',
		);

		$response  = wp_safe_remote_request( $url_get_merchants, $args );
		$body      = $response['body'];
		$obj       = json_decode( $body, true );
		$merchants = $obj['data'];
		wp_send_json_success( $merchants );
	}
	/**
	 * Get Merchant
	 *
	 * @return void json
	 */
	public function get_merchant() {
		// Only verificate the nonce for token.
		check_ajax_referer( 'url-merc-wpnonce', 'nonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission.', 'fullculqi_subs' ) );
		}

		$token = isset( $_GET['token'] )
		? sanitize_text_field( wp_unslash( $_GET['token'] ) )
		: null;

		// Run a security check.
		$url_get_merchant = isset( $_GET['url_merchant'] )
		? sanitize_text_field( wp_unslash( $_GET['url_merchant'] ) )
		: null;

		$public_key = isset( $_GET['public_key'] )
		? sanitize_text_field( wp_unslash( $_GET['public_key'] ) )
		: null;

		$url_get_merchant_info = $url_get_merchant . $public_key;

		$args = array(
			'method'  => 'GET',
			'headers' => array(
				'Content-Type'  => 'application/json',
				'Accept'        => 'application/json',
				'Authorization' => 'Bearer ' . $token,
			),
			'timeout' => 120,
			'body'    => '',
		);

		$response = wp_safe_remote_request( $url_get_merchant_info, $args );
		$body     = $response['body'];
		$obj      = json_decode( $body, true );
		$keys     = $obj['data'];

		foreach ( $keys as $key => $keyValue ) {
			if ( $keyValue['active'] === true ) {
				wp_send_json_ssuccess( $keyValue['key'] );
			}
		}
	}

	/**
	 * Sync Charges from Admin
	 *
	 * @return void json
	 */
	public function sync_charges() {

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( esc_html__( 'You do not have permission.', 'fullculqi_subs' ) );
		}

		$record = isset( $_POST['record'] ) ? intval( $_POST['record'] ) : 100;

		$after_id = isset( $_POST['after_id'] )
		? sanitize_text_field( wp_unslash( $_POST['after_id'] ) )
		: '';

		$result = FullCulqi_Charges::sync( $record, $after_id );

		if ( $result['status'] === 'ok' ) {
			wp_send_json_success( $result['data'] );
		} else {
			wp_send_json_error( $result['data'] );
		}
	}

	/**
	 * Sync Charges from Admin
	 *
	 * @return void json
	 */
	public function sync_orders() {

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		$record = isset( $_POST['record'] ) ? intval( $_POST['record'] ) : 100;
		$after_id = isset( $_POST['after_id'] )
		? sanitize_text_field( wp_unslash( $_POST['after_id'] ) )
		: '';

		$result = FullCulqi_Orders::sync( $record, $after_id );

		if ( $result['status'] === 'ok' ) {
			wp_send_json_success( $result['data'] );
		} else {
			wp_send_json_error( $result['data'] );
		}
	}

	/**
	 * Sync Customer from Admin
	 *
	 * @return void json
	 */
	public function sync_customers() {
		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		$record = isset( $_POST['record'] ) ? intval( $_POST['record'] ) : 100;
		$after_id = isset( $_POST['after_id'] )
		? sanitize_text_field( wp_unslash( $_POST['after_id'] ) )
		: '';

		$result = FullCulqi_Customers::sync( $record, $after_id );

		if ( $result['status'] === 'ok' ) {
			wp_send_json_success( $result['data'] );
		} else {
			wp_send_json_error( $result['data'] );
		}
	}

	/**
	 * Delete all the charges posts
	 *
	 * @return mixed
	 */
	public function delete_charges() {
		global $wpdb;

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		$result = FullCulqi_Charges::delete_wpposts();

		if ( $result ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Delete all the orders posts
	 *
	 * @return mixed
	 */
	public function delete_orders() {
		global $wpdb;

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		$result = FullCulqi_Orders::delete_wpposts();

		if ( $result ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Delete all the customers posts
	 *
	 * @return mixed
	 */
	public function delete_customers() {
		global $wpdb;

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		$result = FullCulqi_Customers::delete_wpposts();

		if ( $result ) {
			wp_send_json_success();
		} else {
			wp_send_json_error();
		}
	}

	/**
	 * Create Refund from CPT
	 *
	 * @return mixed
	 */
	public function create_refund() {

		// Run a security check.
		check_ajax_referer( 'fullculqi-wpnonce', 'wpnonce' );

		// Check the permissions.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error(
				esc_html__( 'You do not have permission.', 'fullculqi_subs' )
			);
		}

		// Check if the post exists.
		if ( ! isset( $_POST['post_id'] ) || empty( $_POST['post_id'] ) ) {
			wp_send_json_error();
		}

		// Charge Post ID.
		$post_charge_id = absint( $_POST['post_id'] );

		// 3rd-party
		$refund = apply_filters( 'fullculqi/ajax/refund/process', false, $post_charge_id );

		if ( empty( $refund ) ) {

			// Meta Basic from Charges.
			$charge_basic = get_post_meta( $post_charge_id, 'culqi_basic', true );
			$amount = floatval( $charge_basic['culqi_amount'] ) -
			floatval( $charge_basic['culqi_amount_refunded'] );

			// Culqi Charge ID.
			$culqi_charge_id = get_post_meta( $post_charge_id, 'culqi_id', true );

			$args = [
				'amount'    => round( $amount * 100, 0 ),
				'charge_id' => $culqi_charge_id,
				'reason'    => 'solicitud_comprador',
				'metadata'  => [
					'post_id' => $post_charge_id,
				],
			];

			$refund = FullCulqi_Refunds::create( $args, $post_charge_id );
		}

		do_action( 'fullculqi/ajax/refund/create', $refund );

		if ( $refund['status'] === 'ok' ) {
			wp_send_json_success();
		} else {
			wp_send_json_error( $refund['data'] );
		}
	}
}

new FullCulqi_Ajax();
