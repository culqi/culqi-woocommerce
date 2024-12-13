<?php

add_action('wp_ajax_culqi_save_config', 'culqi_save_config');

function culqi_save_config() 
{
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'culqi_save_config')) {
        wp_send_json_error(['message' => 'Nonce verification failed.'], 403);
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $data = $_POST;
    
    $plugin_status = isset($data['pluginStatus']) ? $data['pluginStatus'] : null;
    $plugin_status = (bool) ($plugin_status == "true");
    $public_key = sanitize_text_field($data['publicKey']);
    $merchant = isset($data['merchant']) ? sanitize_text_field($data['merchant']) : null;
    $rsa_pk = isset($data['rsa_pk']) ? sanitize_text_field($data['rsa_pk']) : null;
    $payment_methods = isset($data['payment_methods']) ? sanitize_text_field($data['payment_methods']) : null;

    $limit = 1;
    $cache_key = 'culqi_merchant_data_' . $limit;
    $existing_entry = wp_cache_get($cache_key, 'culqi');
    if ($existing_entry === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $existing_entry = $wpdb->get_var(
            $wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}culqi_merchant_data WHERE public_key = %s", $public_key)
        );
        wp_cache_set($cache_key, $existing_entry, 'culqi', HOUR_IN_SECONDS);
    }
    
    if ($existing_entry > 0) {
        $update_data = [];
        if (!is_null($plugin_status)) {
            $update_data['plugin_status'] = $plugin_status;
        }
        if (!is_null($merchant)) {
            $update_data['merchant'] = $merchant;
        }
        if (!is_null($rsa_pk)) {
            $update_data['rsa_pk'] = $rsa_pk;
        }
        if (!is_null($payment_methods)) {
            $update_data['payment_methods'] = $payment_methods;
        }
        $update_data['created_at'] = current_time('mysql');
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $wpdb->update(
            $table_name,
            $update_data,
            ['public_key' => $public_key]
        );
        wp_cache_delete($cache_key, 'culqi');
    } else {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $wpdb->insert(
            $table_name,
            [
                'plugin_status' => $plugin_status,
                'public_key' => $public_key,
                'merchant' => $merchant,
                'rsa_pk' => $rsa_pk,
                'payment_methods' => $payment_methods,
                'created_at' => current_time('mysql'),
            ]
        );
    }
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $wpdb->insert(
        $table_name,
        [
            'plugin_status' => $plugin_status,
            'public_key' => $public_key,
            'merchant' => $merchant,
            'rsa_pk' => $rsa_pk,
            'payment_methods' => $payment_methods,
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

    wp_send_json_success(['message' => 'Payment gateway updated.']);
}
