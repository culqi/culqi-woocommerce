<?php
/**
 * Endpoints Class
 * @since  1.0.0
 * @package Includes / Endpoints
 */
#[\AllowDynamicProperties]
class FullCulqi_Endpoints {

	/**
	 * Construct
	 */
	public function __construct() {
		// Create Endpoint
		add_action( 'init', [ $this, 'add_endpoint' ], 0 );

		// Add name endpoint to query vars
		add_filter( 'query_vars', [ $this, 'add_query_vars' ], 0 );

		// New enpoint content
		add_action( 'parse_request', array( $this, 'handle_api_requests' ), 0 );
	}

	/**
	 * EndPoint Fullculqi
	 * @return mixed
	 */
	public function add_endpoint() {
		add_rewrite_endpoint( 'fullculqi-api', EP_ALL );
	}

	/**
	 * Query variables
	 * @param  array $vars
	 * @return array
	 */
	public function add_query_vars( $vars = [] ) {
		$vars[] = 'fullculqi-api';
		return $vars;
	}


	/**
	 * Trigger and API Request
	 * @return mixed
	 */
	public function handle_api_requests() {
		global $wp;

		if ( ! empty( $_GET['fullculqi-api'] ) ) { // WPCS: input var okay, CSRF ok.
			$wp->query_vars['fullculqi-api'] = sanitize_key( wp_unslash( $_GET['fullculqi-api'] ) ); // WPCS: input var okay, CSRF ok.
		}

		// wc-api endpoint requests.
		if ( ! empty( $wp->query_vars['fullculqi-api'] ) ) {

			// Buffer, we won't want any output here.
			ob_start();

			// No cache headers.
			nocache_headers();

			// Clean the API request.
			$api_request = strtolower( wc_clean( $wp->query_vars['fullculqi-api'] ) );

			// Trigger generic action before request hook.
			do_action( 'fullculqi/api/request', $api_request );

			// Is there actually something hooked into this API request? If not trigger 400 - Bad request.
			status_header( has_action( 'fullculqi/api/' . $api_request ) ? 200 : 400 );

			// Trigger an action which plugins can hook into to fulfill the request.
			do_action( 'fullculqi/api/' . $api_request );

			// Done, clear buffer and exit.
			ob_end_clean();
			$array = array('success'=>true);
			echo json_encode($array);
			die();
		}
	}
}

new FullCulqi_Endpoints();
