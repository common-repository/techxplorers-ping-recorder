<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/admin
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Ping_Recorder_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// Include the admin CSS.
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Include the admin JavaScript.
	}

	/**
	 * Add a settings page for the plugin.
	 *
	 * @since	1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_options_page(
			__( "Techxplorer's Ping Recorder" ),
			__( 'Ping Recorder' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_plugin_settings_page' )
		);
	}

	/**
	 * Add a settings action link to the plugins page.
	 *
	 * @param array $links The list of existing links.
	 *
	 * @since 1.0.0
	 */
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', 'txp-ping-recorder' ) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}

	/**
	 * Render the settings page
	 *
	 * @since 1.0.0
	 */
	public function display_plugin_settings_page() {
		include_once( 'partials/txp-ping-recorder-admin-display.php' );
	}

	/**
	 * Register the setting options
	 *
	 * @since 1.0.0
	 */
	public function options_update() {
		register_setting( $this->plugin_name, $this->plugin_name, array( $this, 'validate' ) );
	}

	/**
	 * Validate the admin settings
	 *
	 * @param array $input       The list of input from the settings form.
	 *
	 * @since 1.0.0
	 */
	public function validate( $input ) {
		// All checkbox options.
		$valid = array();

		// Secret Key.
		$valid['secretkey'] = ( isset( $input['secretkey'] ) && ! empty( $input['secretkey'] ) ) ? sanitize_user( $input['secretkey'] ) : wp_generate_password( 15, false );

		// SSH User Name.
		$valid['username'] = ( isset( $input['username'] ) && ! empty( $input['username'] ) ) ? sanitize_user( $input['username'] ) : '';

		// SSH Port.
		$valid['port'] = ( isset( $input['port'] ) && ! empty( $input['port'] ) ) ? intval( $input['port'] ) : '22';

		return $valid;
	}

	/**
	 * Regularly cleanup the ping table so it doesn't grow too large.
	 *
	 *  @since 1.0.0
	 */
	public function run_cleanup_cron() {
		// Get the maximum id number.
		global $wpdb;
		$table_name = $wpdb->prefix . 'txp_ping_recorder';

		$sql = "SELECT MAX(id)
				FROM {$table_name}
				";

		// @codingStandardsIgnoreStart
		$max_id = $wpdb->get_var($sql);
		// @codingStandardsIgnoreEnd

		// Check to make sure a value was returned.
		if ( null === $max_id ) {
			// No id found, so just exit.
			return;
		}

		// Calculate the new maximum id value.
		$limit_id = $max_id - 15;

		if ( 0 >= $limit_id ) {
			// Less than 15 records, so just exit.
			return;
		}

		// Delete the old records.
		$sql = "DELETE
				FROM {$table_name}
				WHERE id <= %d";

		// @codingStandardsIgnoreStart
		$wpdb->query( $wpdb->prepare( $sql, array( $limit_id ) ) );
		// @codingStandardsIgnoreEnd
	}
}
