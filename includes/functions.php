<?php

/**
 * Get Settings
 * @return array
 */
function fullculqi_get_settings() {

	$settings = get_option( 'fullculqi_options', [] );
	//var_dump($settings); exit(1);
	$settings = wp_parse_args( $settings, fullculqi_get_default() );
	//var_dump(apply_filters( 'fullculqi/settings', $settings )); exit(1);
	return apply_filters( 'fullculqi/settings', $settings );
}


/**
 * Get Default Settings
 * @return array
 */
function fullculqi_get_default() {
	$default = [
		'enabled'			=> 'no',
		'commerce'			=> '',
		'enviroment'		=> '',
		'public_key'		=> '',
		'secret_key'		=> '',
		'logo_url'			=> '',
		'methods'			=> [
			'tarjeta'  		=>  '0',
			'yape'			=>  '0',
			'billetera'  	=>  '0',
			'bancaMovil'	=>  '0',
			'agente'  		=>  '0',
			'cuetealo'  	=>  '0'
		],
		'time_expiration'  	=> '',
		'notify_pay' 		=> '',
		'username' 			=> '',
		'password' 			=> '',
		'estado_pedido'		=> '',
        'color_palette'     => '',
		'rsa_id'			=> '',
		'rsa_pk'			=> '',
	];

	return apply_filters( 'fullculqi/settings_default', $default );
}


/**
 * Allowed Currencies
 * @param  string $type
 * @return array
 */
function fullculqi_currencies( $type = 'name' ) {

	switch($type) {
		case 'symbol' : $output = [  'PEN' => 'S/.', 'USD' => '$' ]; break;
		default :
			$output = [
				'PEN' => esc_html__( 'Peruvian Sol', 'fullculqi' ),
				'USD' => esc_html__( 'Dollars', 'fullculqi' ),
			];
		break;
	}

	return apply_filters('fullculqi/currencies', $output, $type);
}


/**
 * Format Price
 * @param  integer $amount
 * @param  string  $currency
 * @return string
 */
function fullculqi_format_price( $amount = 0, $currency = 'PEN' ) {

	if( empty( $currency ) )
		return floatval( $amount );

	$symbols = fullculqi_currencies( 'symbol' );

	$output = $symbols[ $currency ] . ' ' . number_format( str_replace(',', '', $amount), 2 );

	return apply_filters('fullculqi/format_price', $output, $amount, $currency );
}

/**
 * CPTs from Culqi
 * @return mixed
 */
function fullculqi_get_cpts() {
	$array_cpts = [
		'culqi_charges'		=> esc_html__( 'Charges', 'fullculqi' ),
		'culqi_orders'		=> esc_html__( 'Orders', 'fullculqi' ),
		'culqi_customers'	=> esc_html__( 'Customers', 'fullculqi' ),
	];

	return apply_filters( 'fullculqi/cpts', $array_cpts );
}

/**
 * Get the language from the Site
 * @return mixed
 */
function fullculqi_language() {

	$lang_locale = $language = get_locale();
	$allows = [ 'es', 'en' ];

	// Locale
	if( strpos( $lang_locale, '_' ) != FALSE ) {
		list( $language, $country ) = array_map(
			'strtolower', explode( '_', $lang_locale )
		);
	}

	// Default
	if( ! in_array( $language, $allows ) )
		$language = $allows[0];

	return apply_filters( 'fullculqi/language', $language );
}


/**
 * Format Total to Culqi
 * @param  integer $total
 * @return string
 */
function fullculqi_format_total( $total = 0 ) {
	$total_points = number_format( $total, 2, '.', '' );
	$total_raw = strval( $total_points * 100 );

	return apply_filters( 'fullculqi/format_total', $total_raw, $total );
}

/**
 * Check if it has posts
 * @return mixed
 */
function fullculqi_have_posts() {

	$cpts = array_keys( fullculqi_get_cpts() );

	foreach( $cpts as $cpt ) {
		$count_posts = wp_count_posts($cpt);

		if( isset($count_posts->publish) && $count_posts->publish != 0 )
			return true;
	}

	return false;
}

/**
 * Print Layout
 * @param  string $template_name
 * @param  array  $args
 * @param  string $template_path]
 * @return mixed
 */
function fullculqi_get_template( $template_name = '', $args = [], $template_path = '' ) {

	if ( ! empty( $args ) && is_array( $args ) )
		extract( $args );

	if( ! empty( $template_path ) )
		$located = trailingslashit( $template_path ) . $template_name;
	else
		$located = MPCULQI_DIR . $template_name;

	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'fullculqi/global/located', $located, $args );
	//var_dump($located); exit(1);
	if( ! file_exists( $located ) ) {
		printf( esc_html__('File %s is not exists','fullculqi'), esc_html( $located ) );
		return;
	}

	do_action( 'fullculqi/template/before', $located, $args );

	include $located;

	do_action( 'fullculqi/template/after', $located, $args );
}


/**
 * Get Charges Statuses
 * @return array
 */
function fullculqi_charges_statuses() {
	$statuses = [
		'authorized'	=> esc_html__( 'Authorized', 'fullculqi' ),
		'captured'		=> esc_html__( 'Captured', 'fullculqi' ),
		'expired'		=> esc_html__( 'Expired', 'fullculqi' ),
		'refunded'		=> esc_html__( 'Refunded', 'fullculqi' ),
	];

	return apply_filters( 'fullculqi/charges/statuses', $statuses );
}

/**
 * Get Multipayments Statuses
 * @return array
 */
function fullculqi_multipayments_statuses() {
	$statuses = [
		'paid'		=> esc_html__( 'Paid', 'fullculqi' ),
		'expired'	=> esc_html__( 'Expired', 'fullculqi' ),
		'deleted'	=> esc_html__( 'Deleted', 'fullculqi' ),
		'pending'	=> esc_html__( 'Pending', 'fullculqi' ),
		'created'	=> esc_html__( 'Created', 'fullculqi' ),
	];

	return apply_filters( 'fullculqi/multipayments/statuses', $statuses );
}


function fullculqi_class_from_status( $status = '', $type = 'charges' ) {

	$classes = [];

	if( $type == 'charges' ) {
		$classes = [
			'captured'		=> 'success',
			'authorized'	=> 'warning',
			'expired'		=> 'secondary',
			'refunded'		=> 'error',
		];
	} elseif( $type == 'orders' ) {
		$classes = [
			'paid'			=> 'success',
			'pending'		=> 'warning',
			'expired'		=> 'error',
			'deleted'		=> 'error',
			'created'		=> 'secondary',
		];
	}

	$class = isset( $classes[$status] ) ? $classes[$status] : '';

	return apply_filters( 'fullculqi/class_from_status', $class, $status, $type );
}

/**
 * Convert Unix Time to Date
 * @param  string $unixTime
 * @return string
 */
function fullculqi_convertToDate( $unixTime = '' ) {
	if( empty( $unixTime ) )
		return false;

	$date = intval( $unixTime/1000 );

	if( date( 'Y', $date ) > 2000 )
		return date( 'Y-m-d H:i:s', $date );

	$date = intval( $unixTime );

	return date( 'Y-m-d H:i:s', $date );
}

/**
 * Get PostId from MetaValues
 * @param  string $meta_key
 * @param  string $meta_value
 * @return integer
 */
function fullculqi_post_from_meta( $meta_key = '', $meta_value = '' ) {

	if( empty( $meta_key ) )
		return false;

	global $wpdb;

	// @codingStandardsIgnoreStart
	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s LIMIT 1", $meta_key, $meta_value ) );
	// @codingStandardsIgnoreEnd

	return apply_filters( 'fullculqi/post_from_meta', $post_id, $meta_key, $meta_value );
}

function fullculqi_update_post_meta( $meta_key = '', $post_id = '', $meta_value = '') {

    if( empty( $meta_key ) )
        return false;

    global $wpdb;

	// @codingStandardsIgnoreStart
    $wpdb->get_var( $wpdb->prepare( "UPDATE {$wpdb->postmeta} SET meta_value = %s WHERE meta_key = %s AND post_id = %s LIMIT 1", $meta_value, $meta_key, $post_id ) );
	// @codingStandardsIgnoreEnd

    return true;
}

/**
 * Get UserId from MetaValues
 * @param  string $meta_key
 * @param  string $meta_value
 * @return integer
 */
function fullculqi_user_from_meta( $meta_key = '', $meta_value = '' ) {

	if( empty( $meta_key ) )
		return false;

	global $wpdb;
	
	// @codingStandardsIgnoreStart
	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s LIMIT 1", $meta_key, $meta_value ) );
	// @codingStandardsIgnoreEnd

	return apply_filters( 'fullculqi/user_from_meta', $post_id, $meta_key, $meta_value );
}


/**
 * Apply esc_html recursive
 * @param  string $input
 * @return mixed
 */
function fullculqi_esc_html( $input = '' ) {

	if( is_array( $input ) )
		$output = array_map( 'fullculqi_esc_html', $input );
	else
		$output = esc_html( $input );

	return apply_filters( 'fullculqi/esc_html', $output, $input );
}


function fullculqi_get_current_admin_url() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$uri = preg_replace( '|^.*/wp-admin/|i', '', $uri );

	if ( ! $uri )
		return '';

	return remove_query_arg( array( '_wpnonce', '_wc_notice_nonce', 'wc_db_update', 'wc_db_update_nonce', 'wc-hide-notice' ), admin_url( $uri ) );
}


/**
	Depreciate Functions
 */
function fullculqi_get_woo_settings() {

	$settings = fullculqi_get_settings();
	var_dump('settings:::', $settings); exit(1);
	$method = get_option('woocommerce_fullculqi_settings', []);

	return apply_filters('fullculqi/global/get_woo_settings', $method);
}
