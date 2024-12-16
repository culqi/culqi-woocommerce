<?php
add_action('admin_enqueue_scripts', 'culqi_gateway_admin_scripts');

function culqi_gateway_admin_scripts() {
    wp_enqueue_script('culqi-script', plugin_dir_url(__FILE__) . '../../assets/admin.js', ['jquery'], PLUGIN_VERSION, true);

    wp_localize_script('culqi-script', 'culqiGatewayAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('culqi_gateway_toggle'),
    ]);
}

add_action('wp_ajax_culqi_gateway_toggle', 'culqi_gateway_toggle_action');

function culqi_gateway_toggle_action() 
{
    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'culqi_gateway_toggle')) {
        wp_send_json_error(['message' => 'Nonce verification failed.'], 403);
    }

    $enabled = isset($_POST['enabled']) ? sanitize_text_field(wp_unslash($_POST['enabled'])) : 'no';

    global $wpdb;

    $plugin_status = ($enabled === 'yes');
    $table_name = esc_sql($wpdb->prefix . 'culqi_merchant_data');
    $limit = 1;
    $cache_key = 'culqi_merchant_data_' . $limit;
    $existing_user = wp_cache_get($cache_key, 'culqi');

    if ($existing_user === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $existing_user = $wpdb->get_results(
            $wpdb->prepare("SELECT * FROM {$wpdb->prefix}culqi_merchant_data LIMIT %d", $limit)
        );
        wp_cache_set($cache_key, $existing_user, 'culqi', HOUR_IN_SECONDS);
    }
    
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $wpdb->update(
        $table_name,
        [
            'plugin_status' => $plugin_status,
            'updated_at' => current_time('mysql'),
        ],
        ['id' => $existing_user[0]->id],
        ['%d', '%s'],
        ['%d']
    );
    wp_cache_delete($cache_key, 'culqi');

    wp_send_json_success(['message' => 'Toggle state updated to ' . $enabled]);
}
