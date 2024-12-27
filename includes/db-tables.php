<?php
function culqi_create_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        plugin_status tinyint(1) NOT NULL,
        public_key varchar(255) NOT NULL,
        merchant varchar(255) NOT NULL,
        rsa_pk text,
        payment_methods text,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function culqi_delete_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'culqi_merchant_data';

    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}