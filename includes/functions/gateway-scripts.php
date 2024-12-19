<?php
add_action('wp_enqueue_scripts', 'culqi_payment_enqueue_scripts');

function culqi_payment_enqueue_scripts() {
    wp_enqueue_style( 'culqi-gateway', plugin_dir_url(__FILE__) . '../../assets/css/gateway.css', [], PLUGIN_VERSION );
    wp_enqueue_script('culqi-script', plugin_dir_url(__FILE__) . '../../assets/gateway.js', ['jquery'], PLUGIN_VERSION, true);
}
