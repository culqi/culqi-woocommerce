<?php
if (!defined('ABSPATH')) {
    exit;
}

add_action('rest_api_init', 'culqi_register_custom_route');
function culqi_register_custom_route() {
    $namespace = 'culqi-api';

    register_rest_route($namespace, '/update-order/', [
        'methods' => 'POST',
        'callback' => 'culqi_update_order',
        'permission_callback' => '__return_true',
    ]);
}

require_once plugin_dir_path(__FILE__) . 'functions/save-config.php';
require_once plugin_dir_path(__FILE__) . 'functions/update-order.php';

add_action('wp_ajax_culqi_get_config_url', 'culqi_get_config_url_ajax');

// Endpoint para recuperar la URL de pago desde el Block Checkout
function culqi_register_payment_url_endpoint() {
    register_rest_route('culqi-api', '/get-payment-url/', [
        'methods'             => 'GET',
        'callback'            => 'culqi_get_payment_url',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'culqi_register_payment_url_endpoint');

function culqi_get_payment_url($request) {
    $order_id = absint($request->get_param('order_id'));

    $order = wc_get_order($order_id);
    if (!$order) {
        return new WP_Error('not_found', 'Order not found', array('status' => 404));
    }

    if ($order->get_status() !== 'pending') {
        return new WP_Error('invalid_status', 'Order not in pending state', array('status' => 400));
    }

    $url = $order->get_meta('_culqi_payment_url', true);
    if (empty($url)) {
        return new WP_Error('no_url', 'No payment URL', array('status' => 404));
    }

    return rest_ensure_response(array('url' => $url));
}
