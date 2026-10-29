<?php
if (!defined('ABSPATH')) {
    exit;
}
add_action('wp_ajax_culqi_save_config', 'culqi_save_config');

function culqi_save_config()
{
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'No permission.'], 403);
    }

    $logger = Culqi_Logger::get_instance();
    $logger->debug('Config', 'Config save initiated');

    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'culqi_gateway_toggle')) {
        $logger->warning('Config', 'Nonce verification failed');
        wp_send_json_error(['message' => 'Nonce verification failed.'], 403);
    }
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $data = $_POST;

    $plugin_status = isset($data['pluginStatus']) ? $data['pluginStatus'] : null;
    $plugin_status = (bool) ($plugin_status == "true");
    $env = sanitize_text_field($data['env'] ?? '');
    $rsa_pk_culqi = isset($data['rsa_pk_culqi']) ? $data['rsa_pk_culqi'] : null;
    $rsa_sk_plugin = isset($data['rsa_sk_plugin']) ? $data['rsa_sk_plugin'] : null;
    $payment_methods = isset($data['payment_methods']) ? sanitize_text_field($data['payment_methods']) : null;

    if (empty($env)) {
        $logger->warning('Config', 'Missing env/public key, config not saved');
        wp_send_json_error(['message' => 'Missing env/public key.'], 400);
    }

    $existing_config = culqi_get_config();

    if (empty($rsa_pk_culqi) && !empty($existing_config->rsa_pk_culqi)) {
        $rsa_pk_culqi = $existing_config->rsa_pk_culqi;
    }
    if (empty($rsa_sk_plugin) && !empty($existing_config->rsa_sk_plugin)) {
        $rsa_sk_plugin = $existing_config->rsa_sk_plugin;
    }
    if (empty($payment_methods) && !empty($existing_config->payment_methods)) {
        $payment_methods = $existing_config->payment_methods;
    }

    $data = [
        'plugin_status' => (int) $plugin_status,
        'env' => $env,
        'rsa_pk_culqi' => $rsa_pk_culqi,
        'rsa_sk_plugin' => $rsa_sk_plugin,
        'payment_methods' => $payment_methods,
        'created_at' => current_time('mysql'),
    ];

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $wpdb->query('START TRANSACTION');

    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.DirectQuery
    $deleted = $wpdb->query("DELETE FROM {$wpdb->prefix}culqi_merchant_data");

    if ($deleted === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $wpdb->query('ROLLBACK');
        $logger->error('Config', 'Failed to delete previous config record', [
            'env' => $env,
            'error' => $wpdb->last_error,
            'table' => $table_name,
        ]);
        wp_send_json_error(['message' => 'Failed to save config.'], 500);
    }

    $inserted = $wpdb->insert(
        $table_name,
        $data,
        ['%d', '%s', '%s', '%s', '%s', '%s']
    );

    if ($inserted === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $wpdb->query('ROLLBACK');
        $logger->error('Config', 'Failed to insert config record', [
            'env' => $env,
            'error' => $wpdb->last_error,
            'table' => $table_name,
        ]);
        wp_send_json_error(['message' => 'Failed to save config.'], 500);
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $committed = $wpdb->query('COMMIT');

    if ($committed === false) {
        $logger->error('Config', 'Failed to commit config record', [
            'env' => $env,
            'error' => $wpdb->last_error,
            'table' => $table_name,
        ]);
        wp_send_json_error(['message' => 'Failed to save config.'], 500);
    }

    $logger->info('Config', 'Config record saved', [
        'env' => $env,
        'fields' => array_keys($data),
        'table' => $table_name,
    ]);

    $available_gateways = get_option('woocommerce_gateway_order') ? get_option('woocommerce_gateway_order') : [];
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

    $logger->info('Config', 'Plugin status changed', [
        'enabled' => $plugin_status
    ]);

    $logger->info('Config', 'WooCommerce settings updated', [
        'culqi_enabled' => $plugin_status ? 'yes' : 'no'
    ]);

    wp_send_json_success(['message' => 'Payment gateway updated.']);
}

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('wp-api');
});

add_action('wp_ajax_check_admin_session', function() {
    if (is_user_logged_in() && current_user_can('manage_options')) {
        wp_send_json_success(['active' => true]);
    } else {
        wp_send_json_error(['active' => false]);
    }
});
