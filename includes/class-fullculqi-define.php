<?php
class FullCulqi_Define {
	function __construct() {
		add_action( 'plugins_loaded', [$this, 'include_file'] );
		add_filter( 'woocommerce_payment_gateways', [$this, 'include_class'] );
	}


	function include_file() {
		$settings = fullculqi_get_settings();

		if ( !class_exists( 'WC_Gateway_FullCulqi' ) && $settings['woo_payment'] == 'yes' )
			require_once FULLCULQI_PLUGIN_DIR . '/includes/class-fullculqi-method.php';
	}


	function include_class( $methods ) {
		$settings = fullculqi_get_settings();

		if( $settings['woo_payment'] == 'yes' )
			$methods[] = 'WC_Gateway_FullCulqi';
		
		return $methods;
	}
}