<?php
/**
 * Fired during plugin activation
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Ping_Recorder_Activator {

	/**
	 * Undertake tasks necessary when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {

		// Create a table to record the pings from the home server.
		global $wpdb;

		// Define some constants for consistency.
		$table_name = $wpdb->prefix . 'txp_ping_recorder';
		$charset_collate = $wpdb->get_charset_collate();

		// Define the SQL to create the table.
		$sql = "CREATE TABLE $table_name (
			    id mediumint(8) NOT NULL AUTO_INCREMENT,
				created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
				ip varchar(45) DEFAULT '' NOT NULL,
				PRIMARY KEY  (id)
		) $charset_collate;";

		// Create the table.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

		// Schedule the cleanup cron to run.
		if ( ! wp_next_scheduled( 'txp_ping_recorder_cron_hook' ) ) {
			wp_schedule_event( time(), 'daily', 'txp_ping_recorder_cron_hook' );
		}
	}

}
