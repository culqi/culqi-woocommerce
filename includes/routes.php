<?php
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
