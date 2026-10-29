<?php
if (!defined('ABSPATH')) {
    exit;
}
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
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'No permission.'], 403);
    }

    $nonce = isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '';
    if (empty($nonce) || !wp_verify_nonce($nonce, 'culqi_gateway_toggle')) {
        wp_send_json_error(['message' => 'Nonce verification failed.'], 403);
    }

    $enabled = isset($_POST['enabled']) ? sanitize_text_field(wp_unslash($_POST['enabled'])) : 'no';

    global $wpdb;

    $plugin_status = ($enabled === 'yes');
    $table_name = esc_sql($wpdb->prefix . 'culqi_merchant_data');
    $existing_user = culqi_get_config();

    if (!isset($existing_user->id)) {
        wp_send_json_error(['message' => 'Config not found.'], 404);
    }

    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $updated = $wpdb->update(
        $table_name,
        [
            'plugin_status' => $plugin_status,
            'updated_at' => current_time('mysql'),
        ],
        ['id' => (int) $existing_user->id],
        ['%d', '%s'],
        ['%d']
    );

    if ($updated === false) {
        $logger = Culqi_Logger::get_instance();
        $logger->error('Config', 'Failed to update plugin status', [
            'id' => (int) $existing_user->id,
            'error' => $wpdb->last_error,
        ]);
        wp_send_json_error(['message' => 'Failed to update plugin status.'], 500);
    }

    wp_send_json_success(['message' => 'Toggle state updated to ' . $enabled]);
}
