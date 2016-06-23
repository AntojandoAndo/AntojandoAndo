<?php

/**
 *  Admin settings page update notices for Pro tabs.
 *
 * @package    PIB Pro
 * @subpackage Includes
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}



/**
* Plugin admin notices for saving settings
*
* @since   3.0.0
*
*/
function pib_register_admin_notices_pro() {
	global $pib_vars;
	
	$is_pib_settings_page = strpos( ( isset( $_GET['page'] ) ? $_GET['page'] : '' ), 'pinterest-pin-it-button' ); 
	
	if ( ( $is_pib_settings_page !== false ) && ( isset( $_GET['tab'] ) && 'image_hover' == $_GET['tab'] ) && ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) ) {
		add_settings_error( 'pib-notices-pro', 'pib-image-hover-updated', __( 'Image Hover settings updated. ' . $pib_vars['cache_message'], 'pib' ), 'updated' );
	}

	if ( ( $is_pib_settings_page !== false ) && ( isset( $_GET['tab'] ) && 'below_image' == $_GET['tab'] ) && ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) ) {
		add_settings_error( 'pib-notices-pro', 'pib-image-misc-updated', __( 'Below Image settings updated. ' . $pib_vars['cache_message'], 'pib' ), 'updated' );
	}

	if ( ( $is_pib_settings_page !== false ) && ( isset( $_GET['tab'] ) && 'share_bar' == $_GET['tab'] ) && ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) ) {
		add_settings_error( 'pib-notices-pro', 'pib-share-bar-updated', __( 'Share Bar settings updated. ' . $pib_vars['cache_message'], 'pib' ), 'updated' );
	}
	
	if ( ( $is_pib_settings_page !== false ) && ( isset( $_GET['tab'] ) && 'support' == $_GET['tab'] ) && ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) ) {
		add_settings_error( 'pib-notices-pro', 'pib-support-updated', __( 'Support settings updated.', 'pib' ), 'updated' );
	}
	
	settings_errors( 'pib-notices-pro' );
}

add_action( 'admin_notices', 'pib_register_admin_notices_pro' );
