<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('admin_menu', 'custom_plugin_add_submenu');

function custom_plugin_add_submenu()
{
    add_submenu_page(
        'woocommerce',
        'Culqi',
        'Culqi',
        'manage_options',
        'culqi-settings',
        'culqi_settings_page_callback'
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
