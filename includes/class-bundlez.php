<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Bundlez
 * @subpackage Bundlez/includes
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
 * @package    Bundlez
 * @subpackage Bundlez/includes
 * @author     Your Name <email@example.com>
 */
class Bundlez {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bundlez_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $bundlez    The string used to uniquely identify this plugin.
	 */
	protected $bundlez;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

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
		if ( defined( 'BUNDLEZ_VERSION' ) ) {
			$this->version = BUNDLEZ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bundlez';

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
	 * - Bundlez_Loader. Orchestrates the hooks of the plugin.
	 * - Bundlez_i18n. Defines internationalization functionality.
	 * - Bundlez_Admin. Defines all hooks for the admin area.
	 * - Bundlez_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bundlez-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bundlez-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bundlez-admin.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-bundlez-main.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-bundlez-settings-api.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-bundlez-memberpress-api.php';

        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/lib/class-bundlez-register-txn-api.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bundlez-public.php';

		$this->loader = new Bundlez_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bundlez_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Bundlez_i18n();

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

		$plugin_admin = new Bundlez_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'check_memberpress_installed' );        
        $this->loader->add_filter( 'wp_mail', $plugin_admin, 'wp_mail');

        $main = new Bundlez_Main( new Bundlez_Memberpress_Api( $this->get_plugin_name(), $this->get_version() ), $this->get_plugin_name(), $this->get_version() );
        $this->loader->add_action( 'admin_menu', $main, 'add_menu_pages' );
        $this->loader->add_action( 'admin_post_bundlez_add_new_bundle', $main, 'save_new_bundle' );
        $this->loader->add_action( 'admin_post_bundlez_delete_bundle', $main, 'delete_bundle' );

        $settings_api = new Bundlez_Settings_Api( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $settings_api, 'register_options_page' );
        $this->loader->add_action( 'admin_head', $settings_api, 'options_page_css' );
        $this->loader->add_action( 'admin_init', $settings_api, 'register_settings' );

        $register_txn = new Bundlez_Register_Txn_Api();
        $this->loader->add_action( 'mepr-txn-status-complete', $register_txn, 'mepr_txn_status_complete' );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Bundlez_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
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
	 * @return    Bundlez_Loader    Orchestrates the hooks of the plugin.
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
