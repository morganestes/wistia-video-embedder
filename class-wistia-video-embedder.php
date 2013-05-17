<?php
/**
 * Wistia Video Embedder.
 *
 * @package   WistiaVideoEmbedder
 * @author    Morgan Estes <morgan.estes@gmail.com>
 * @copyright 2013 Morgan Estes
 * @license   GPL-2.0+
 * @link      http://github.com/morganestes/wistia-video-embedder
 */

require_once( 'WistiaApi.class.php' );

/**
 * Plugin class.
 * TODO: Rename this class to a proper name for your plugin.
 *
 * @package WistiaVideoEmbedder
 * @author  Morgan Estes <morgan.estes@gmail.com>
 */
class WistiaVideoEmbedder {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 * @var     string
	 */
	protected $version = '1.0.0';

	protected $db_version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_slug = 'wistia-video-embedder';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = 'toplevel_page_wistia-video-embedder';

	/**
	 * The API key set in the Settings page.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public $wistia_api_key = '';

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the editor buttons
		add_action( 'init', array( $this, 'add_editor_button' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		//add_action( 'TODO', array( $this, 'action_method_name' ) );
		//add_filter( 'TODO', array( $this, 'filter_method_name' ) );

	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
		self::_install();
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
		// TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		$which_page         = $_GET['page'];
		$is_our_admin_pages = strpos( $which_page, $this->plugin_slug );

		if ( 'true' == $is_our_admin_pages ) {
			wp_enqueue_style( $this->plugin_slug . '-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), $this->version );
		}
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		$which_page         = $_GET['page'];
		$is_our_admin_pages = strpos( $which_page, $this->plugin_slug );

		if ( 'true' == $is_our_admin_pages ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {
		add_menu_page(
			__( 'Wistia Video Embedder', 'wistia-video-embedder' ),
			__( 'Wistia', 'wistia-video-embedder' ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' ),
				plugin_dir_url( __FILE__ ) . 'assets/wistia-ico.png'
		);

		//add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' )
		add_submenu_page(
			$this->plugin_slug,
			__( 'Wistia Projects', 'wistia-video-embedder' ),
			__( 'Projects', 'wistia-video-embedder' ),
			'read',
				$this->plugin_slug . '-projects',
			array( $this, 'display_plugin_projects_page' )
		);

		// Change the name of the automatically-generated first submenu. Thanks Yoast!
		global $submenu;
		if ( isset( $submenu[$this->plugin_slug] ) )
			$submenu[$this->plugin_slug][0][0] = __( 'Settings', 'wistia-video-embedder' );
	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		add_action( 'admin_init', array( $this, 'register_plugin_settings' ) );
		$this->update_plugin_settings();

		include_once( 'views/admin.php' );
	}

	/**
	 *
	 */
	public function display_plugin_projects_page() {
		$this->update_projects_list();
		include_once( 'views/projects.php' );
	}


	/**
	 *
	 */
	public function register_plugin_settings() {
		register_setting( 'wistia-settings-api-key', 'wistia_api_key' );
		register_setting( 'wistia-settings-projects', 'wistia_projects' );

//		register_setting( 'yoast_wpseo_options', 'wpseo' );
//		register_setting( 'yoast_wpseo_permalinks_options', 'wpseo_permalinks' );
//		register_setting( 'yoast_wpseo_titles_options', 'wpseo_titles' );
//		register_setting( 'yoast_wpseo_rss_options', 'wpseo_rss' );
//		register_setting( 'yoast_wpseo_internallinks_options', 'wpseo_internallinks' );
//		register_setting( 'yoast_wpseo_xml_sitemap_options', 'wpseo_xml' );
//		register_setting( 'yoast_wpseo_social_options', 'wpseo_social' );
	}

	/**
	 *
	 */
	public function update_plugin_settings() {

		$wistia_api_key = get_option( 'wistia_api_key' );

		if ( isset( $_POST['action'] ) && 'update' === $_POST['action'] && isset( $_POST['wistia_api_key'] ) ) {
			$wistia_api_key = sanitize_text_field( $_POST['wistia_api_key'] );
			update_option( 'wistia_api_key', $wistia_api_key );

			$account = $this->api_get_account_info( $wistia_api_key );
			update_option( 'wistia_account', json_encode( $account ) );
		}
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

	/**
	 * Register a button with TinyMCE to quickly add the shortcode.
	 */
	public function register_editor_button( $buttons ) {
		array_push( $buttons, '|', 'wistiaVideoEmbedder' );

		return $buttons;
	}

	/**
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	public function add_editor_plugin( $plugin_array ) {
		$plugin_array['wistiaVideoEmbedder'] = plugins_url( 'wistia-video-embedder' ) . '/js/wistiaVideoEmbedder.js';

		return $plugin_array;
	}

	/**
	 *
	 */
	public function add_editor_button() {
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;

		if ( 'true' == get_user_option( 'rich_editing' ) ) {
			add_filter( 'mce_external_plugins', array( $this, 'add_editor_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_editor_button' ) );
		}
	}

	/**** API Functions ****/
	public function api_connect() {
		$wistia_api_key = get_option( 'wistia_api_key' );

		return new WistiaApi( $wistia_api_key );
	}

	/**
	 * @return stdClass
	 */
	public function api_get_account_info() {
		$wistia_api = $this->api_connect();

		return $wistia_api->accountRead();
	}

	/**
	 * Get the projects from the API.
	 */
	public function api_get_projects() {
		/** Query the API for the project lists */
		$wistia_api = $this->api_connect();

		return $wistia_api->projectList();
	}

	public function save_projects_list() {

		$projects = $this->api_get_projects();

		/** Save the projects into the `wistia` table in the DB */
		/** @var wpdb $wpdb */
		global $wpdb;

		$table            = $wpdb->prefix . 'wistia_projects';
		$current_projects = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $table", '%d' ) );
		$fetched_projects = array();

		foreach ( $projects as $project ) {
			// add to the list of projects Wistia reports
			array_push( $fetched_projects, $project->id );

			// only insert projects that we don't already know about
			if ( ! in_array( $project->id, $current_projects ) ) {
				$wpdb->insert(
					$table,
					array(
						'id'          => $project->id,
						'name'        => $project->name,
						'hashedId'    => $project->hashedId,
						'description' => $project->description,
					),
					array( '%d', '%s', '%s', '%s', )
				);
			}
		}

		/** Remove deleted projects from the database */
		$deleted_projects = array_diff( $current_projects, $fetched_projects );
		foreach ( $deleted_projects as $deleted_project ) {
			$wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE id = %d", $deleted_project ) );
		}

		/** Get last update time for the Projects page */
		$last_db_update = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT UPDATE_TIME
				FROM information_schema.tables
				WHERE TABLE_SCHEMA = %s
				AND TABLE_NAME = %s",
				DB_NAME,
				$table
			) );

		update_option( 'wistia_projects_update', $last_db_update) );
	}

	public static function display_projects_list() {
		/** @todo Query db for projects */
		/** @var wpdb $wpdb */
		global $wpdb;
		$table    = $wpdb->prefix . 'wistia_projects';
		$projects = $wpdb->get_results( "SELECT * FROM $table" );

		/** @todo Display on the page */
		//var_dump( $project_list );

		foreach ( $projects as $project ) {
			$list = <<<HTML
		<div id="project-{$project->id}">
		<h3>$project->name</h3>
		$project->description
		<code>$project->hashedId</code>
		</div>
HTML;

			echo __( $list, 'wistia-video-embedder' );
		}
	}

	public function update_projects_list() {
		/** @todo Update the list and display it when button pressed */

		if ( isset( $_POST['action'] ) && 'update' === $_POST['action'] &&
				isset( $_POST['option_page'] ) && 'wistia-settings-projects' == $_POST['option_page']
		) {
			$this->save_projects_list();

			date_default_timezone_set( 'America/Chicago' );

			$last_check     = date( 'Y-m-d H:i:s' );
			$updated = get_option( 'wistia_projects_update' );
			$is_expired = false;

			if ( $is_expired ) {
				/** @todo figure out if this is the latest update
				 * set transient, check against times
				 */

			}
		}
	}

	/**
	 *
	 */
	public function api_get_videos() {
		/** @todo Query the API for the videos */

		/** @todo Save the videos into the wistia table in the DB */
	}

	/**
	 *
	 */
	function _install() {
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		/** @var wpdb $wpdb */
		global $wpdb;

		$table_projects = $wpdb->prefix . 'wistia_projects';
		$table_videos   = $wpdb->prefix . 'wistia_videos';

		/** @todo Create table based on data we expect back from API. */
		/**
		 * int id*
		 * string name*
		 * int mediaCount
		 * date created
		 * date updated
		 * string hashedId*
		 * bool anonymousCanUpload
		 * bool anonymousCanDownload
		 * bool public
		 * string publicId
		 * string description HTML*
		 */
		$sql = "CREATE TABLE $table_projects (
id mediumint(9) NOT NULL,
  name tinytext NOT NULL,
  hashedId text NOT NULL,
  description text,
  UNIQUE KEY id (id)
);";

		dbDelta( $sql );
	}
}
