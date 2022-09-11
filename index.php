<?php
/*
Plugin Name: Culqi Checkout
Plugin URI:https://wordpress.org/plugins/wp-culqi-integration
Description: Culqi acepta pago con tarjetas, pagoefectivo, billeteras móviles y cuotéalo desde tu tienda virtual.
Version: 3.0.0
Author: Culqi
Author URI: https://culqi.com/
Developer: Juan Ysen
Developer URI: https://culqi.com/
Text Domain: culqi, woocommerce, method payment
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 5.6
Requires PHP: 5.6
WC requires at least: 2.6.11
WC tested up to:
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'FULLCULQI_FILE' , __FILE__ );
define( 'FULLCULQI_DIR' , plugin_dir_path(__FILE__) );
define( 'FULLCULQI_URL' , plugin_dir_url(__FILE__) );
define( 'FULLCULQI_BASE' , plugin_basename( __FILE__ ) );

    define('URLAPI_INTEG', 'https://qa-test-panel.culqi.xyz');
    define('URLAPI_PROD', 'https://qa-panel.culqi.xyz');

    define('URLAPI_INTEG_3DS', 'https://3ds-qa.culqi.xyz/');
    define('URLAPI_PROD_3DS', 'https://3ds-qa.culqi.xyz');

    define('URLAPI_ORDERCHARGES_INTEG', 'https://qa-api.culqi.xyz/v2');
    define('URLAPI_CHECKOUT_INTEG', 'https://qa-checkout.culqi.xyz/js/v4');

    define('URLAPI_LOGIN_INTEG', URLAPI_INTEG.'/user/login');
    define('URLAPI_MERCHANT_INTEG', URLAPI_INTEG.'/secure/merchant/');
    define('URLAPI_MERCHANTSINGLE_INTEG', URLAPI_INTEG.'/secure/keys/?merchant=');
    define('URLAPI_WEBHOOK_INTEG', URLAPI_INTEG.'/secure/events');

    define('URLAPI_ORDERCHARGES_PROD', 'https://qa-api.culqi.xyz/v2');
    define('URLAPI_CHECKOUT_PROD', 'https://qa-checkout.culqi.xyz/js/v4');

    define('URLAPI_LOGIN_PROD', URLAPI_PROD.'/user/login');
    define('URLAPI_MERCHANT_PROD', URLAPI_PROD.'/secure/merchant/');
    define('URLAPI_MERCHANTSINGLE_PROD', URLAPI_PROD.'/secure/keys/?merchant=');
    define('URLAPI_WEBHOOK_PROD', URLAPI_PROD.'/secure/events');

    define('TIME_EXPIRATION_DEFAULT', 24);

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once FULLCULQI_DIR . 'includes/class-fullculqi.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fullculqi-activator.php
 */
function fullculqi_activate() {
	require_once FULLCULQI_DIR . 'includes/class-fullculqi-activator.php';
	fullculqi_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fullculqi-deactivator.php
 */
//function culqi_deactivate() {
//	require_once FULLCULQI_DIR . 'includes/class-fullculqi-deactivator.php';
//	fullculqi_Deactivator::deactivate();
//}


register_activation_hook( __FILE__, 'fullculqi_activate' );
//register_deactivation_hook( __FILE__, 'fullculqi_deactivate' );

/**
 * Store the plugin global
 */
global $fullculqi;

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */

function fullculqi() {
	return FullCulqi::instance();
}

$fullculqi = fullculqi();
?>
