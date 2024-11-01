<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Ping_Recorder_Deactivator {

	/**
	 * Undertake tasks necessary when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		// Unschedule the cleanup cron event.
		$timestamp = wp_next_scheduled( 'txp_ping_recorder_cron_hook' );
		if ( false !== $timestamp ) {
			wp_unschedule_event( $timestamp, 'txp_ping_recorder_cron_hook' );
		}
	}
}
