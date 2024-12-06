<?php
/**
 * Plugin Name: Culqi Payment Gateway
 * Description: Adds Culqi as a payment method in WooCommerce.
 * Version: 1.0.0
 * Author: Your Name
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
    // Perform cleanup tasks, if necessary
}

// Load plugin after WooCommerce is initialized
add_action('plugins_loaded', 'culqi_gateway_init', 11);

function culqi_gateway_init() {
    if (!class_exists('WC_Payment_Gateway')) {
        return;
    }

    // Include the Culqi Payment Gateway Class
    require_once plugin_dir_path(__FILE__) . 'includes/class-culqi-payment.php';

    // Add the gateway to WooCommerce
    add_filter('woocommerce_payment_gateways', 'add_culqi_gateway');
    
    function add_culqi_gateway($methods)
    {
        $methods[] = 'WC_Gateway_Culqi'; // Payment Gateway class
        return $methods;
    }
}

// Include admin functions
require_once plugin_dir_path(__FILE__) . 'admin/class-culqi-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/functions/disable-reduce-stock.php';
