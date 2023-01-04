<?php
/*
Plugin Name: Culqi
Plugin URI:https://wordpress.org/plugins/culqi-checkout
Description: Culqi acepta pago con tarjetas, pagoefectivo, billeteras móviles y cuotéalo desde tu tienda virtual.
Version: 3.0.1
Author: Culqi
Author URI: https://culqi.com/
Developer: Culqi Team
Developer URI: https://culqi.com/
Text Domain: culqi, woocommerce, method payment
License: GPLv2 or later
Text Domain: checkout
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
define( 'MPCULQI_PLUGIN_VERSION' , 'v3.0.1');

define( 'MPCULQI_FILE' , __FILE__ );
define( 'MPCULQI_DIR' , plugin_dir_path(__FILE__) );
define( 'MPCULQI_URL' , plugin_dir_url(__FILE__) );
define( 'MPCULQI_BASE' , plugin_basename( __FILE__ ) );

define('MPCULQI_URLAPI_INTEG', 'https://internal-v3-dev.culqi.xyz');
define('MPCULQI_URLAPI_PROD', 'https://internal-v3-dev.culqi.xyz');

define('MPCULQI_URLAPI_INTEG_3DS', 'https://3ds.culqi.com');
define('MPCULQI_URLAPI_PROD_3DS', 'https://3ds.culqi.com');

define('MPCULQI_URLAPI_ORDERCHARGES_INTEG', 'https://api.culqi.com/v2');
define('MPCULQI_URLAPI_CHECKOUT_INTEG', 'https://checkout.culqi.com/js/v4');

define('MPCULQI_URLAPI_LOGIN_INTEG', MPCULQI_URLAPI_INTEG.'/or-dashboard/public/auth');
define('MPCULQI_URLAPI_MERCHANT_INTEG', MPCULQI_URLAPI_INTEG.'/or-dashboard/secure/account-merchants');
define('MPCULQI_URLAPI_MERCHANTSINGLE_INTEG', MPCULQI_URLAPI_INTEG.'/or-dashboard/secure/keys/get-keys');
define('MPCULQI_URLAPI_WEBHOOK_INTEG', MPCULQI_URLAPI_INTEG.'/or-dashboard/secure/webhooks/create-merchant-event');
define('MPCULQI_URLAPI_GET_WEBHOOK_INTEG', MPCULQI_URLAPI_INTEG.'/or-dashboard/secure/webhooks/get-merchant-events');

define('MPCULQI_URLAPI_ORDERCHARGES_PROD', 'https://api.culqi.com/v2');
define('MPCULQI_URLAPI_CHECKOUT_PROD', 'https://checkout.culqi.com/js/v4');

define('MPCULQI_URLAPI_LOGIN_PROD', MPCULQI_URLAPI_PROD.'/or-dashboard/public/auth');
define('MPCULQI_URLAPI_MERCHANT_PROD', MPCULQI_URLAPI_PROD.'/or-dashboard/secure/account-merchants');
define('MPCULQI_URLAPI_MERCHANTSINGLE_PROD', MPCULQI_URLAPI_PROD.'/or-dashboard/secure/keys/get-keys');
define('MPCULQI_URLAPI_WEBHOOK_PROD', MPCULQI_URLAPI_PROD.'/or-dashboard/secure/webhooks/create-merchant-event');
define('MPCULQI_URLAPI_GET_WEBHOOK_PROD', MPCULQI_URLAPI_PROD.'/or-dashboard/secure/webhooks/get-merchant-events');

define('TIME_EXPIRATION_DEFAULT', 24);

/**
 * The core plugin class that is used to define internationalization,
 * dashboard-specific hooks, and public-facing site hooks.
 */
require_once MPCULQI_DIR . 'includes/class-fullculqi.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-fullculqi-activator.php
 */
function fullculqi_activate() {
	require_once MPCULQI_DIR . 'includes/class-fullculqi-activator.php';
	fullculqi_Activator::activate();
}

// plugin uninstallation
register_uninstall_hook( __FILE__, 'my_fn_uninstall' );
function my_fn_uninstall() {
    delete_option( 'fullculqi_options' );
}
/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-fullculqi-deactivator.php
 */
//function culqi_deactivate() {
//	require_once MPCULQI_DIR . 'includes/class-fullculqi-deactivator.php';
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
