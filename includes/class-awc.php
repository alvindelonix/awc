<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       writerscentre.com.au
 * @since      1.0.0
 *
 * @package    Awc
 * @subpackage Awc/includes
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
 * @package    Awc
 * @subpackage Awc/includes
 * @author     Alvin <alvin.delonix@gmail.com>
 */
class Awc {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Awc_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_name = 'awc';
		$this->version = '1.0.0';

		$this->load_dependencies();
		$this->set_locale();
		
		add_filter( 'acf/settings/path', array($this, 'awc_acf_settings_path') );
		add_filter( 'acf/settings/dir', array($this, 'awc_acf_settings_dir') );
		add_filter('acf/settings/save_json', array($this, 'awc_acf_json_save_point') );	
		add_filter('acf/settings/load_json', array($this, 'awc_acf_json_load_point') );
		add_filter('acf/settings/show_admin', '__return_false');
		
		// ** Define Custom Post Type **
		$this->define_awc_course();
		$this->define_awc_presenter();
		$this->define_awc_graduate();
		
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Awc_Loader. Orchestrates the hooks of the plugin.
	 * - Awc_i18n. Defines internationalization functionality.
	 * - Awc_Admin. Defines all hooks for the admin area.
	 * - Awc_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-awc-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-awc-i18n.php';
		
		/**
		 * The class responsible for defining Custom Post Type.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/CPT.php';

		/**
		 * Loading the vafPress. Responsible for the metaboxes, shortcode generator and Theme Option
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'vafpress/bootstrap.php';
		
		/**
		 * The AWC Course
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-awc-course.php';
		
		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'acf/acf.php';

		$this->loader = new Awc_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Awc_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Awc_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

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
	 * @return    Awc_Loader    Orchestrates the hooks of the plugin.
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
	
	/**
	 * Filter acf setting path
	 *
	 * @since     1.0.0
	 * 
	 */
	function awc_acf_settings_path( $path ) {
 
		// update path
		//$path = plugin_dir_path( dirname( __FILE__ ) ) . '/acf/';
		$path = plugin_dir_path( dirname( __FILE__ ) ) . '/acf/';
		
		// return
		return $path;
		
	}
	
	/**
	 * Filter acf setting directory
	 *
	 * @since     1.0.0
	 * 
	 */
	function awc_acf_settings_dir( $dir ) {
 
		// update path
		$dir = plugin_dir_url( dirname( __FILE__ ) ) . '/acf/';
		
		// return
		return $dir;
		
	}
	
	 /**
	 * Filter acf local JSON saving path
	 *
	 * @since     1.0.0
	 * 
	 */
	function awc_acf_json_save_point( $path ) {
		
		// update path
		$path = plugin_dir_path( dirname( __FILE__ ) ) . '/acf-json/';
		
		
		// return
		return $path;
		
	}
	
	/**
	 * Filter acf local JSON loading path
	 *
	 * @since     1.0.0
	 * 
	 */
	function awc_acf_json_load_point( $paths ) {
		
		// remove original path (optional)
		unset($paths[0]);
		
		
		// append path
		$paths[] = plugin_dir_path( dirname( __FILE__ ) ) . '/acf-json/';
		
		
		// return
		return $paths;
		
	}
	
	/**
	 * Define the Course.
	 *
	 * @since     1.0.0
	 * 
	 */
	public function define_awc_course() {
		global $awcCourse;
		
		$awcCourse = new Awc_Course( $this->get_plugin_name(), $this->get_version() );
	}
	
	/**
	 * Define the Presenter.
	 *
	 * @since     1.0.0
	 * 
	 */
	public function define_awc_presenter() {
		global $awcPresenter;
		
		$awcPresenter = new CPT(
			array(
				'post_type_name' => 'awc_presenters',
				'singular' => 'Presenter',
				'plural' => 'Presenters',
				'slug' => 'presenters'
			),
			array(
				'menu_position' => 99,
				'public' => true,
				'has_archive' => true,
				'hierarchical'       => false,
				'capability_type'    => 'post',
				'supports'           => array( 'title', 'editor', 'thumbnail' )
			)
		);
		
		$awcPresenter->menu_icon("dashicons-businessman");
		
		$awcPresenter->register_taxonomy('awc_location');
		
		$awcPresenter->columns(array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'icon' => __('Featured Image'),
			'awc_location' => __('Location'),
			'date' => __('Date')
		));
	}
	
	/**
	 * Define the Graduate.
	 *
	 * @since     1.0.0
	 * 
	 */
	public function define_awc_graduate() {
		global $awcGraduate;
		
		$awcGraduate = new CPT(
			array(
				'post_type_name' => 'awc_graduates',
				'singular' => 'Graduate',
				'plural' => 'Graduates',
				'slug' => 'graduates'
			),
			array(
				'menu_position' => 99,
				'public' => true,
				'has_archive' => true,
				'hierarchical'       => false,
				'capability_type'    => 'post',
				'supports'           => array( 'title', 'editor', 'thumbnail' )
			)
		);
		
		$awcGraduate->menu_icon("dashicons-welcome-learn-more");
		
		$awcGraduate->register_taxonomy('awc_location');
		
		$awcGraduate->columns(array(
			'cb' => '<input type="checkbox" />',
			'title' => __('Title'),
			'icon' => __('Featured Image'),
			'awc_location' => __('Location'),
			'date' => __('Date')
		));
		
	}
}
