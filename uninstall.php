<?php

if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
    exit;
}

delete_option( 'tpmd-api-key' );
delete_option( 'tpmd-api-cur' );
delete_option( 'tpmd-api-attr' );

global $wpdb;

$table_name = $wpdb->prefix . 'tpmd_product';
$sql = 'DROP TABLE ' . $table_name;
$wpdb->query( $sql );

delete_option( 'tpmd_db_version' );