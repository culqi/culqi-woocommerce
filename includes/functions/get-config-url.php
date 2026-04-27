<?php
if (!defined('ABSPATH')) {
    exit;
}

function culqi_get_config_url(): string
{
    $culqi_token = culqi_generate_token(true);
    return CULQI_CONFIG_URL . '?platform=' . PLATFORM . '&shop=' . get_site_url() . '&token=' . urlencode($culqi_token);
}

function culqi_get_config_url_ajax()
{
    check_ajax_referer('culqi_gateway_toggle', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('No permission', 403);
    }

    wp_send_json_success(['url' => culqi_get_config_url()]);
}
