<?php
/**
 * Pinterest "Pin It" Button Pro
 *
 * @package   PIB Pro
 * @author    Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 * @license   GPL-2.0+
 * @link      http://pinplugins.com
 * @copyright 2012-2015 Phil Derksen
 *
 * @wordpress-plugin
 * Plugin Name: Pinterest "Pin It" Button Pro
 * Plugin URI: http://pinplugins.com/pin-it-button-pro/
 * Description: Add a Pinterest "Pin It" Button to your site and get your visitors to start pinning your awesome content! **Pro Version**
 * Version: 3.2.5.1
 * Author: Phil Derksen
 * Author URI: http://philderksen.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: pib
 * Domain Path: /languages/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'PIB_MAIN_FILE' ) ) {
	define ( 'PIB_MAIN_FILE', __FILE__ );
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// Check for PIB Lite 2.x. If active then make sure to 'die' otherwise a fatal error will stop everything.
if( is_plugin_active( 'pinterest-pin-it-button/pinterest-pin-it-button.php' ) ) {
	die( "You must deactivate Pinterest Pin It Button Lite before activating the Pro version." );
} 

if( ! class_exists( 'Pinterest_Pin_It_Button' ) ) {
	require_once( plugin_dir_path( __FILE__ ) . 'class-pinterest-pin-it-button.php' );
}

require_once( plugin_dir_path( __FILE__ ) . 'class-pinterest-pin-it-button-pro.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'Pinterest_Pin_It_Button_Pro', 'activate' ) );

// We don't actually need to load an instance of the parent class but just load the file
// Then we can create an instance of our child class and overwrite/call the functions we need.
Pinterest_Pin_It_Button_Pro::get_instance();
