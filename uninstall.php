<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete the option from the database.
delete_option( 'txp-ping-recorder' );

// Delete the database table.
// Ignore coding rules to suppress WordPress.VIP rules
// @codingStandardsIgnoreStart
global $wpdb;
$table_name = $wpdb->prefix . 'txp_ping_recorder';
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
// @codingStandardsIgnoreEnd
