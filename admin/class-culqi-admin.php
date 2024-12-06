<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'culqi_payment_settings_page');

function culqi_payment_settings_page()
{
    add_menu_page(
        'Culqi Payment Settings',
        'Culqi Settings',
        'manage_options',
        'culqi-settings',
        'culqi_settings_page_callback',
        '',
        100
    );
}

function culqi_settings_page_callback()
{
    include plugin_dir_path(__FILE__) . 'settings-page.php';
}

add_action('admin_enqueue_scripts', 'culqi_admin_scripts');

function culqi_admin_scripts($hook)
{
    if ($hook === 'toplevel_page_culqi-settings') {
        wp_enqueue_script('culqi-admin-js', plugin_dir_url(__FILE__) . '../assets/admin.js', array(), '1.0', true);
    }
}
