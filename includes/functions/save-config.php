<?php

add_action('wp_ajax_culqi_save_config', 'culqi_save_config');

function culqi_save_config() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $data = $_POST;

    $plugin_status = isset($data['pluginStatus']) ? (bool) $data['pluginStatus'] : false;
    $public_key = sanitize_text_field($data['publicKey']);
    $merchant = sanitize_text_field($data['merchant']);

    $wpdb->query("TRUNCATE TABLE $table_name");
    $wpdb->insert(
        $table_name,
        [
            'plugin_status' => $plugin_status,
            'public_key' => $public_key,
            'merchant' => $merchant,
            'created_at' => current_time('mysql'),
        ]
    );

    $available_gateways = get_option('woocommerce_gateway_order');
    if ($plugin_status) {
        if (!in_array('culqi', $available_gateways)) {
            $available_gateways[] = 'culqi';
        }
    } else {
        if (($key = array_search('culqi', $available_gateways)) !== false) {
            unset($available_gateways[$key]);
        }
    }

    $culqi_settings = get_option('woocommerce_culqi_settings');
    $culqi_settings['enabled'] = $plugin_status ? 'yes' : 'no';

    update_option('woocommerce_culqi_settings', $culqi_settings);

    wp_send_json_success(['message' => 'Payment gateway enabled and data saved.']);
}
