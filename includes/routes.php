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

add_action('wp_ajax_culqi_get_config_url', 'culqi_get_config_url');
function culqi_get_config_url() {
    check_ajax_referer('culqi_gateway_toggle', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No permission', 403);
    }

    $culqi_token = culqi_generate_token(true);
    $config_url = CULQI_CONFIG_URL . '?platform=' . PLATFORM . '&shop=' . get_site_url() . '&token=' . urlencode($culqi_token);

    wp_send_json_success(['url' => $config_url]);
}
