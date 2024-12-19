<?php

function culqi_get_config() {
    global $wpdb;
    $limit = 1;
    $cache_key = 'culqi_merchant_data_' . $limit;
    $existing_config = wp_cache_get($cache_key, 'culqi');

    if ($existing_config === false) {
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
        $existing_config = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}culqi_merchant_data LIMIT 1");
        wp_cache_set($cache_key, $existing_config, 'culqi', HOUR_IN_SECONDS);
    }

    return $existing_config ?? [];
}