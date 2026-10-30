<?php
if (!defined('ABSPATH')) {
    exit;
}
function culqi_get_config() {
    global $wpdb;
    // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
    $existing_config = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}culqi_merchant_data ORDER BY id DESC LIMIT 1");

    return $existing_config ?: (object) [];
}
