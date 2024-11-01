<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://techxplorer.com/
 * @since             1.0.0
 * @package           Txp_Ping_Recorder
 *
 * @wordpress-plugin
 * Plugin Name:       Techxplorer's Ping Recorder
 * Plugin URI:        https://techxplorer.com/
 * Description:       Records 'pings' from a home server to make it easy to remotely access it.
 * Version:           1.1.1
 * Author:            techxplorer
 * Author URI:        https://techxplorer.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       txp-ping-recorder
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-txp-ping-recorder-activator.php
 */
function activate_txp_ping_recorder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-txp-ping-recorder-activator.php';
	Txp_Ping_Recorder_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-txp-ping-recorder-deactivator.php
 */
function deactivate_txp_ping_recorder() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-txp-ping-recorder-deactivator.php';
	Txp_Ping_Recorder_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_txp_ping_recorder' );
register_deactivation_hook( __FILE__, 'deactivate_txp_ping_recorder' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-txp-ping-recorder.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_txp_ping_recorder() {

	$plugin = new Txp_Ping_Recorder();
	$plugin->run();

}
run_txp_ping_recorder();
