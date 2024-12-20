<?php
/*
Plugin Name: Culqi
Plugin URI:https://wordpress.org/plugins/culqi-checkout
Description: Culqi acepta pagos con tarjetas de débito y crédito, Yape, Cuotéalo BCP y PagoEfectivo (billeteras móviles, agentes y bodegas).
Version: 4.0.0
Author: Culqi
Author URI: https://culqi.com/
Developer: Culqi Team
Developer URI: https://culqi.com/
License: GPLv2 or later
Text Domain: culqi
Requires at least: 5.0
Tested up to: 6.0
Stable tag: 5.6
Requires PHP: 7.4
WC requires at least: 2.6.11
WC tested up to: 3.0.0
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

require_once plugin_dir_path(__FILE__) . 'constants.php';
require_once plugin_dir_path(__FILE__) . 'includes/db-tables.php';
require_once plugin_dir_path(__FILE__) . 'includes/routes.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/update-plugin-status.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/gateway-scripts.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/iframe-modal.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/get-config.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/generate-token.php';

// Activation Hook
register_activation_hook(__FILE__, 'culqi_payment_activate');
function culqi_payment_activate() {
    if (!class_exists('WooCommerce')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('WooCommerce is required to use the Culqi Payment Gateway plugin. Please install and activate WooCommerce.');
    }
    
    culqi_create_table();
}

// Deactivation Hook
register_deactivation_hook(__FILE__, 'culqi_payment_deactivate');
function culqi_payment_deactivate() {
    culqi_delete_table();
}

add_action('plugins_loaded', 'culqi_gateway_init', 11);

function culqi_gateway_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    // Include the Culqi Payment Gateway Class
    require_once plugin_dir_path(__FILE__) . 'includes/class-culqi-payment.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/block-support/class-culqi-integration.php';
    require_once plugin_dir_path( __FILE__ ) . 'includes/block-support/class-culqi-block.php';

    // Add the gateway to WooCommerce
    add_filter('woocommerce_payment_gateways', 'add_culqi_gateway');
    
    function add_culqi_gateway($methods)
    {
        $methods[] = 'WC_Gateway_Culqi'; // Payment Gateway class
        return $methods;
    }
}

use Automattic\WooCommerce\Utilities\FeaturesUtil;

add_action('before_woocommerce_init', function () {
    if (class_exists(FeaturesUtil::class)) {
        FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__);
    }

    if (class_exists(FeaturesUtil::class)) {
        FeaturesUtil::declare_compatibility('cart_checkout_blocks', __FILE__);
    }
});

// Include admin functions
require_once plugin_dir_path(__FILE__) . 'admin/class-culqi-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/disable-reduce-stock.php';

function enqueue_culqi_block() {
    wp_enqueue_script(
        'culqi-block',
        plugins_url( 'assets/js/culqi-block.js', __FILE__ ),
        [ 'wp-element', 'wc-blocks-registry' ],
        '1.0.0',
        true
    );
}
add_action( 'enqueue_block_editor_assets', 'enqueue_culqi_block' );
add_action( 'wp_enqueue_scripts', 'enqueue_culqi_block' );
