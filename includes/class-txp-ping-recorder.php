<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://techxplorer.com/
 * @since      1.0.0
 *
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Txp_Ping_Recorder
 * @subpackage Txp_Ping_Recorder/includes
 * @author     techxplorer <corey@techxplorer.com>
 */
class Txp_Ping_Recorder {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Txp_Ping_Recorder_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Store a reference to the admin class for later use.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @var     Txp_Content_Tweaks_Admin $plugin_admin An instance of the plugin admin class
	 */
	protected $plugin_admin;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'txp-ping-recorder';
		$this->version = '1.1.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Txp_Ping_Recorder_Loader. Orchestrates the hooks of the plugin.
	 * - Txp_Ping_Recorder_I18n. Defines internationalization functionality.
	 * - Txp_Ping_Recorder_Admin. Defines all hooks for the admin area.
	 * - Txp_Ping_Recorder_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-ping-recorder-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-txp-ping-recorder-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-txp-ping-recorder-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-txp-ping-recorder-public.php';

		$this->loader = new Txp_Ping_Recorder_Loader();

		// Make sure an instance of this class is available for later.
		$this->plugin_admin = new Txp_Ping_Recorder_Admin( $this->plugin_name, $this->version );

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Txp_Ping_Recorder_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Txp_Ping_Recorder_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $this->plugin_admin, 'enqueue_scripts' );

		// Add menu item.
		$this->loader->add_action( 'admin_menu', $this->plugin_admin, 'add_plugin_admin_menu' );

		// Add Settings link to the plugin.
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->get_plugin_name() . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $this->plugin_admin, 'add_action_links' );

		// Register the options.
		$this->loader->add_action( 'admin_init', $this->plugin_admin, 'options_update' );

		// Register the WP Cron hook.
		$this->loader->add_action( 'txp_ping_recorder_cron_hook', $this->plugin_admin, 'run_cleanup_cron' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Txp_Ping_Recorder_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_action( 'parse_request', $plugin_public, 'record_ping' );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_vars' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();

		// Do any tasks that may be needed on upgrade.
		if ( $this->get_version() !== get_option( $this->plugin_name . '_version' ) ) {
			update_option( $this->plugin_name . '_version', $this->get_version() );

			// Update the options with appropriate defaults, or removing unused options.
			$options = $this->plugin_admin->validate( get_option( $this->get_plugin_name() ) );
			update_option( $this->get_plugin_name(), $options );
		}
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Txp_Ping_Recorder_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
