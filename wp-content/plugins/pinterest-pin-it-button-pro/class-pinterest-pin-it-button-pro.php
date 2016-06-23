<?php

/**
 * Main Pinterest_Pin_It_Button_Pro class
 *
 * @package PIB Pro
 * @author  Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Pinterest_Pin_It_Button_Pro extends Pinterest_Pin_It_Button {
	
	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   3.0.0
	 *
	 * @var     string
	 */

	/**************************************
	 * UPDATE VERSION HERE
	 * and main plugin file header comments
	 * and README.txt changelog
	 **************************************/

	protected $version = '3.2.5.1';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    3.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'pinterest-pin-it-button-pro';

	/**
	 * Instance of this class.
	 *
	 * @since    3.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    3.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Store URL for EDD SL Updater
	 *
	 * @since    3.1.3
	 *
	 * @var      string
	 */
	protected $pib_edd_sl_store_url = 'http://pinplugins.com/';
	
	
	/**
	 * Product Name for EDD SL Updater
	 *
	 * @since    3.0.0
	 *
	 * @var      string
	 */
	protected $pib_edd_sl_item_name = 'Pin It Button Pro';
	
	/**
	 * Author Name for EDD SL Updater
	 *
	 * @since    3.0.0
	 *
	 * @var      string
	 */
	protected $pib_edd_sl_author = 'Phil Derksen';
	
	
	/**
	 * Initialize main class object
	 *
	 * @since    3.0.0
	 *
	 */
	public function __construct() {
		$this->setup_constants();
		
		// Load plugin text domain
		add_action( 'plugins_loaded', array( $this, 'plugin_textdomain' ) );
		
		
		$old = get_option( 'pib_version' );
		
		// If $old exists then we know this plugin has been run already so we just update this here to 
		// avoid complications from this point forward (5/6/2015)
		if ( ! ( false === $old ) ) {
			update_option( 'pib_has_run', 1 );
		}
		
		if( version_compare( $old, $this->version, '<' ) ) {
			delete_option( 'pib_upgrade_has_run' );
		}
		
		// Run our upgrade checks first and update our version option.
		if( ! get_option( 'pib_upgrade_has_run' ) ) {
			add_action( 'init', array( $this, 'upgrade_plugin' ), 0 );
			update_option( 'pib_version', $this->version );
		}
		
		// Run our upgrade checks first and update our version option.
		if( ! get_option( 'pib_upgrade_has_run' ) ) {
			add_action( 'init', array( $this, 'upgrade_plugin' ), 0 );
			update_option( 'pib_version', $this->version );
		}
		
		// Include required files.
		add_action( 'init', array( $this, 'includes' ), 1 );

		// Run EDD software licensing plugin updater.
		add_action( 'init', array( $this, 'pib_edd_sl_updater' ) );
		
		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ), 2 );
		
		// Add plugin listing "Settings" action link.
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . $this->plugin_slug . '.php' ), array( $this, 'settings_link' ) );
		
		// Enqueue admin styles
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		
		// Enqueue public style and scripts.
		add_action( 'init', array( $this, 'enqueue_styles' ), 9 );
		add_action( 'init', array( $this, 'enqueue_scripts' ), 9 );

		// enqueue admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Handle ajax requests.
        add_action( 'wp_ajax_pib_save_sharebar', array( $this, 'pib_save_sharebar' ) );
        add_action( 'wp_ajax_nopriv_pib_save_sharebar', array( $this, 'pib_save_sharebar' ) );
		
		// Add Post Meta stuff.
		add_action( 'add_meta_boxes', array( $this, 'display_post_meta') );
		add_action( 'save_post', array( $this, 'save_meta_data') );
		
		// Add admin notice after plugin activation. Also check if should be hidden.
		add_action( 'admin_notices', array( $this, 'admin_install_notice' ) );
		
		// Add admin notice if license key is empty
		add_action( 'admin_notices', array( $this, 'admin_license_key_notice' ) );
		
		// Deactivate "Lite" plugin if detected.
		add_action( 'admin_init', array( $this, 'deactivate_lite') );
		
		// Check WP version
		add_action( 'admin_init', array( $this, 'check_wp_version' ) );
		
		// Load scripts when posts load so we know if we need to include them or not
		add_filter( 'the_posts', array( $this, 'load_scripts' ), 20 );
	}
	
	function load_scripts( $posts ) {

		if ( empty( $posts ) ) {
			return $posts;
		}
		
		parent::load_scripts( $posts );

		foreach ( $posts as $post ) {
			if ( ! in_array( 'no_buttons', pib_render_button( $post, true ) ) ) {
				// Load CSS
				wp_enqueue_style( $this->plugin_slug .'-public-styles' );
				
				// Load JS
				wp_enqueue_script( 'pib-async-script-loader' );
				
				break;
			}
		}

		return $posts;
	}
	
	public function check_wp_version() {
		parent::check_wp_version();
	}
	
	/**
	 * Include the upgrade file
	 *
	 * @since    3.0.0
	 *
	 */
	function upgrade_plugin() {
		include_once( 'includes/upgrade-plugin-pro.php' );
	}
	
	/**
	 * Check for and deactivate PIB Lite to avoid problems
	 *
	 * @since    3.0.0
	 *
	 */
	function deactivate_lite() {
		if( is_plugin_active( 'pinterest-pin-it-button/pinterest-pin-it-button.php' ) ) {
			deactivate_plugins( 'pinterest-pin-it-button/pinterest-pin-it-button.php' );
			update_option( 'pib_lite_deactivation_notice', 1 );
			add_action( 'admin_notices', array( $this, 'pib_lite_deactivated_notice' ) );
		} 
	}
	
	/**
	 * Display notice if PIB Lite is found and deactivated
	 *
	 * @since    3.0.0
	 *
	 */
	function pib_lite_deactivated_notice() {
		// Exit all of this is stored value is false/0 or not set.
		if ( false == get_option( 'pib_lite_deactivation_notice' ) )
			return;

		// Delete stored value if "hide" button click detected (custom querystring value set to 1).
		// or if on a PIB admin page. Then exit.
		if ( ! empty( $_REQUEST['pib-dismiss-lite-nag'] ) || $this->viewing_this_plugin() ) {
			delete_option( 'pib_lite_deactivation_notice' );
			return;
		}

		// At this point show install notice.
		include_once( 'views/deactivate-lite-notice.php' );
	}
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    2.0.0
	 *
	 */
	public static function activate() {
		parent::activate();
	}
	
	/**
	 * Setup plugin constants.
	 *
	 * @since     3.0.0
	 */
	public function setup_constants() {
		// PIB_MAIN_FILE declared in main plugin file.

		parent::setup_constants();
		
		define( 'PIB_EDD_SL_STORE_URL', $this->pib_edd_sl_store_url );
		
		define( 'PIB_EDD_SL_ITEM_NAME', $this->pib_edd_sl_item_name );
		
		if( ! class_exists( 'C_NextGEN_Bootstrap' ) )
			define( 'PIB_DEFAULT_FILTER', 100 );
		else
			define( 'PIB_DEFAULT_FILTER', PHP_INT_MAX );
		
		if( ! defined( 'PINPLUGIN_BASE_URL' ) ) {
			define( 'PINPLUGIN_BASE_URL', 'http://pinplugins.com/' );
		}
	}

	/**
	 * Include necessary files.
	 *
	 * @since     3.0.0
	 */
	public function includes() {
		global $pib_options;
		
		parent::includes();
		
		// Include Pro admin settings
		include( 'includes/register-settings-pro.php' );
		
		// Include simplehtmldom
		if( ! class_exists( 'simple_html_dom_node' ) )
			include_once( 'includes/simple_html_dom.php' );
		
		// Add our new settings to the global array
		$pib_options = pib_get_settings_pro();
		
		// Remove the base code filters so we can use the Pro filter which utilizes the Pro only features
		remove_filter( 'the_content', 'pib_render_content', 100 );
		remove_filter( 'the_excerpt', 'pib_render_content_excerpt', 100 );

		include_once( 'includes/misc-functions.php' );

		if( is_admin() ) {
			include_once( 'includes/admin-notices-pro.php' );
			include_once( 'views/post-meta-display-pro.php' );
		}
		else {
			include_once( 'includes/shortcodes-pro.php' );
			include_once( 'views/public-pro.php' );
		}
		
		
	}

	/**
	 * Easy Digital Download Plugin Updater Code.
	 *
	 * @since     3.0.0
	 */
	public function pib_edd_sl_updater() {
		global $pib_options;
		
		if( ! class_exists( 'EDD_SL_Plugin_Updater' ) ) {
			// load our custom updater
			include_once( 'includes/EDD_SL_Plugin_Updater.php' );
		}
		
		if( ! empty( $pib_options['pib_license_key'] ) ) {
			
			// Set the license key
			$license_key = $pib_options['pib_license_key'];

			// setup the updater
			$edd_updater = new EDD_SL_Plugin_Updater( $this->pib_edd_sl_store_url, PIB_MAIN_FILE, array(
					'version'   => $this->version, // current plugin version number
					'license'   => $license_key, // license key (used get_option above to retrieve from DB)
					'item_name' => $this->pib_edd_sl_item_name, // name of this plugin
					'author'    => $this->pib_edd_sl_author // author of this plugin
				)
			);
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     3.0.0
	 *
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
	 * Return the human readable title of the plugin
	 *
	 * @since    3.0.0
	 *
	 * @return      string
	 */
	public static function get_plugin_title() {
		return __( 'Pinterest "Pin It" Button Pro', 'pib' );
	}
	
	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    3.0.0
	 */
	public function add_plugin_admin_menu() {
		// Add main menu item
		$this->plugin_screen_hook_suffix[] = add_menu_page(
			$this->get_plugin_title() . __( ' Settings', 'pib' ),
			__( 'Pin It Button Pro', 'pib' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' ),
			plugins_url( 'assets/pinterest-icon-16.png', __FILE__ )
		);
		
		$this->plugin_screen_hook_suffix[] = add_submenu_page(
			$this->plugin_slug,
			$this->get_plugin_title() . ' ' . __( 'Settings', 'pib' ),
			__( 'Settings', 'pib' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
		
		// Add Help submenu page
		$this->plugin_screen_hook_suffix[] = add_submenu_page(
			$this->plugin_slug,
			$this->get_plugin_title() . __( ' Help', 'pib' ),
			__( 'Help', 'pib' ),
			'manage_options',
			$this->plugin_slug . '_help',
			array( $this, 'display_admin_help_page' )
		);
	}
	
	/**
	 * Include the view for the plguin's admin page
	 *
	 * @since    3.0.0
	 *
	 */
	public function display_plugin_admin_page() {
		include_once( 'views/admin-pro.php' );
	}
	
	/**
	 * Include the view for the plugin's help page
	 *
	 * @since    3.0.0
	 *
	 */
	public function display_admin_help_page() {
		parent::display_admin_help_page();
	}
	
	/**
	 * Enqueue CSS styles for admin side
	 *
	 * @since    3.0.0
	 *
	 */
	public function enqueue_admin_styles() {
		parent::enqueue_admin_styles();
		
		if ( $this->viewing_this_plugin() ) {
			// Pro admin CSS
			wp_enqueue_style( $this->plugin_slug .'-pro-styles', plugins_url( 'css/admin-pro.css', __FILE__ ), array(), $this->version );
		}
	}
	
	/**
	 * Enqueue CSS styles for public side
	 *
	 * @since    3.0.0
	 *
	 */
	public function enqueue_styles() {
		parent::enqueue_styles();

		wp_register_style( $this->plugin_slug .'-public-styles', plugins_url( 'css/public-pro.css', __FILE__ ), array(), $this->version );
	}
	
	/**
	 * Enqueue JavaScript for public side
	 *
	 * @since    3.0.0
	 *
	 */
	public function enqueue_scripts() {
		// Dequeue pinit.js if found from our other plugins (Pinterest Widgets, etc.)
		wp_dequeue_script( 'pinterest-pinit-js' );
		
		// Include our lazy load JS for async purposes. Need to include this first so our async loader can work properly
		wp_register_script( $this->plugin_slug . '-lazy-loader', plugins_url( 'js/lazyload.min.js', __FILE__ ), array(), $this->version, true );

		// Enqueue our async script loader that will asynchronously load all of this plugins JS files
		wp_register_script( 'pib-async-script-loader', plugins_url( 'js/async-script-loader.js', __FILE__ ), array( $this->plugin_slug . '-lazy-loader', 'jquery' ), $this->version, true );
	}

	/**
	 * Enqueue JavaScript for admin side
	 *
	 * @since    3.0.0
	 *
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		//Add thickbox JS/CSS for custom button image gallery popup, button examples, etc
		add_thickbox();

		//Add built-in media upload JS
		if ( function_exists( 'wp_enqueue_media' ) ) {
			wp_enqueue_media(); //WP 3.5+ media manager
		}
			
		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin-pro.js', __FILE__ ), array( 'jquery' ), $this->version );
		
		if( $this->viewing_this_plugin() ) {
			wp_enqueue_script( $this->plugin_slug . '-license', plugins_url( 'js/license.js', __FILE__ ), array( 'jquery' ), $this->version, true );
		}
	}
	
	/**
	 * Check if PIB Lite is activated
	 *
	 * @since   3.0.0
	 *
	 */
	public function pib_lite_active() {
		if( is_plugin_active( 'pinterest-pin-it-button/pinterest-pin-it-button.php' ) ) {
			echo '<div id="message" class="error"><p>' . __( 'Pinterest Pin It Button LITE is still active. Please deactivate it to avoid possible complications.', 'pib' ) . '</p></div>';
		}
	}
	
	/**
	 * Check if viewing one of this plugin's admin pages.
	 *
	 * @since   3.0.0
	 *
	 * @return  bool
	 */
	private function viewing_this_plugin() {
		if ( ! isset( $this->plugin_screen_hook_suffix ) )
			return false;

		$screen = get_current_screen();

		if ( in_array( $screen->id, $this->plugin_screen_hook_suffix ) )
			return true;
		else
			return false;
	}
	
	/**
	 * Function to save and update sharebar order
	 *
	 * @since   3.0.0
	 *
	 */
	public function pib_save_sharebar() {
		$items = stripslashes_deep( $_REQUEST['items'] );
		$option = get_option( 'pib_sharebar_buttons' );
		
		
		// since this option only holds our ENABLED buttons we need to clear it first so that we don't add to it, but instead recreate it
		$option = array();
		
		// Loop through the items so we can update the option
        foreach ( $items as $k => $v ) {
            $option['button_order'][$k] = $v;
        }
		
		$s = update_option( 'pib_sharebar_buttons', $option );
		
		echo  ($s + 5); // mostly for debugging. Our response will be 5 if the update failed, 6 if it was a success. If our response 0 then we now its an issue with AJAX
		die();
	}
	
	/**
	 * Code to show the Post Meta options
	 *
	 * @since    3.0.0
	 *
	 */
	function display_post_meta() {
		parent::display_post_meta();
		
		global $post, $pib_options;
		
		$post_type = ( ! empty( $post->post_type ) ? $post->post_type : '' );
		
		if( ! ( 'page' == $post_type || 'post' == $post_type || 'dashboard' == $post_type || 'link' == $post_type || 'attachment' == $post_type ) ) {
			if( ! empty( $pib_options['custom_post_types']['display_post_type_' . $post_type] ) ) {
				add_meta_box('pib-meta', '"Pin It" Button Settings', 'add_meta_form', $post_type, 'advanced', 'high');
			}
		}
	}
	
	/**
	 * Call functions to save post meta
	 *
	 * @since    3.0.0
	 *
	 */
	function save_meta_data( $post_id ) {
		parent::save_meta_data( $post_id );
	}
	
	/**
	 * Show an admin notice when plugin is installed
	 *
	 * @since    3.0.0
	 *
	 */
	public function admin_install_notice() {
		parent::admin_install_notice();
	}
	
	/**
	 * Show an admin notice for License Key
	 *
	 * @since    3.0.0
	 *
	 */
	public function admin_license_key_notice() {
		// Check if we're on viewing this plugin's admin pages first. If not ignore.
		if( $this->viewing_this_plugin() ) {
			include( 'views/admin-license-notice.php' );
		}
	}
	
	public function plugin_textdomain() {
		parent::plugin_textdomain();
	}
}
