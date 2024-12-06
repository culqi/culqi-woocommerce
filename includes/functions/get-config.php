<?php

function culqi_get_config() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $existing_config = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1");

    return $existing_config ?? [];
}