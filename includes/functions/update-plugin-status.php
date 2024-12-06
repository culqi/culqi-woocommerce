<?php
add_action('admin_enqueue_scripts', 'culqi_gateway_admin_scripts');

function culqi_gateway_admin_scripts() {
    wp_enqueue_script('culqi-script', plugin_dir_url(__FILE__) . '../../assets/admin.js', ['jquery'], null, true);

    wp_localize_script('culqi-script', 'culqiGatewayAjax', [
        'ajax_url' => admin_url('admin-ajax.php'),
    ]);
}

add_action('wp_ajax_culqi_gateway_toggle', 'culqi_gateway_toggle_action');

function culqi_gateway_toggle_action() {
    $enabled = isset($_POST['enabled']) ? sanitize_text_field($_POST['enabled']) : 'no';

    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $plugin_status = ($enabled == 'yes');

    $existing_user = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");

    if ($existing_user) {
        $wpdb->update(
            $table_name,
            [
                'plugin_status' => $plugin_status ? 1 : 0,
                'updated_at' => current_time('mysql'),
            ],
            ['id' => $existing_user->id]
        );
    }
    wp_send_json_success(['message' => 'Toggle state updated to ' . $enabled]);
}
