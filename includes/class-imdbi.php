<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @package    Imdbi
 * @subpackage Imdbi/includes
 * @author     mohammad azami <iazami@outlook.com>
*/
class Imdbi {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @access   protected
	 * @var      Imdbi_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
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
	 */
	public function __construct() {

		$this->plugin_name = 'imdbi';
		$this->version = '2.0.0-beta';

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
	 * - Imdbi_Loader. Orchestrates the hooks of the plugin.
	 * - Imdbi_i18n. Defines internationalization functionality.
	 * - Imdbi_Admin. Defines all hooks for the admin area.
	 * - Imdbi_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-imdbi-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-imdbi-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-imdbi-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-imdbi-public.php';

		/**
		* dom parser Class
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/simpleHtmlDom.php';

		/**
		* Crawler source class (omdbapi.com)
		*/
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/OMDbAPI.php';

		/**
		* loading required functions to uploading poster via url.
		*/
		require_once (ABSPATH . "wp-admin" . '/includes/file.php');
		require_once (ABSPATH . "wp-admin" . '/includes/media.php');


		$this->loader = new Imdbi_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Imdbi_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Imdbi_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {

		$options = get_option($this->plugin_name);

		$plugin_admin = new Imdbi_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'imdbi_add_admin_menu' );

		// Add settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'imdbi_action_links' );

		//Add general settings option
		$this->loader->add_action( 'admin_init', $plugin_admin, 'imdbi_update_options' );

		// Add post metabox setup
		$this->loader->add_action( 'load-post.php', $plugin_admin, 'imdbi_post_metabox_setup' );
		$this->loader->add_action( 'load-post-new.php', $plugin_admin, 'imdbi_post_metabox_setup' );

		// initialize crawler function to listening into incomming data
		$this->loader->add_action('admin_init', $plugin_admin, 'imdbi_run_crawler');

		if($options['download_posters'] == '1'){
			// upload poster via url
			$this->loader->add_action('admin_init', $plugin_admin, 'imdbi_save_poster');
		}

		//display warning message for first time.
		//$this->loader->add_action('admin_notices', $plugin_admin, 'imdbi_warning');

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Imdbi_Public( $this->get_plugin_name(), $this->get_version() );

		/* Loading public functions */
		$this->loader->add_action('plugins_loaded', $plugin_public, 'imdbi_function_loader');

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Imdbi_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
