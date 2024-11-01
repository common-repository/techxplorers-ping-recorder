<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/public
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Ping_Recorder_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

	}

	/**
	 * Add our query var to the list of recognised values
	 *
	 * @param    array $vars The existing list of recognised values.
	 *
	 * @return   The updated list of recognised values
	 *
	 * @since    1.0.0
	 */
	public function add_query_vars( $vars ) {
		$vars[] = $this->plugin_name;
		return $vars;
	}

	/**
	 * Process the ping request
	 *
	 * @param    WP $wp Current WordPress environment instance.
	 *
	 * @return   void
	 *
	 * @since    1.0.0
	 */
	public function record_ping( WP $wp ) {
		// Make sure we only process our onw requests.
		if ( array_key_exists( $this->plugin_name, $wp->query_vars ) ) {
			// Get the plugin options.
			$options = get_option( $this->plugin_name );

			// Make sure the secret key is correct.
			if ( $options['secretkey'] === $wp->query_vars[ $this->plugin_name ] ) {
				// Secret key is correct.
				// Check to see if the remote IP address can be determined.
				// @codingStandardsIgnoreStart
				if ( isset( $_SERVER['REMOTE_ADDR'] ) ) {
					// Save the IP address.
					global $wpdb;
					$data = array(
						'created' => current_time( 'mysql' ),
						'ip'      => sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ),
					);

					$table_name = $wpdb->prefix . 'txp_ping_recorder';

					$wpdb->insert( $table_name, $data );

					wp_die( esc_html__( 'Ping successfully recorded.', 'txp-ping-recorder' ), 200 );
				}
				// @codingStandardsIgnoreEnd

				// If we get here, the IP address could not be determined.
				wp_die( esc_html__( 'Unable to determine remote IP address.' ) );

			} else {
				// Secret key is incorrect.
				wp_die( esc_html__( 'The secret key is incorrect!', 'txp-ping-recorder' ) );
			}
		}
	}
}
