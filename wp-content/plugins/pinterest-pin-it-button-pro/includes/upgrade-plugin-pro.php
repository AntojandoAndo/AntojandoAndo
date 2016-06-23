<?php

/**
 * Run the upgrade process from version 1.x of the plugin to current.
 *
 * @package    PIB
 * @subpackage Includes
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Need to first check if there is currently a version option stored to compare it later
if ( ! get_option( 'pib_version' ) ) {
	add_option( 'pib_version', $this->version );
} else {
	add_option( 'pib_old_version', get_option( 'pib_version' ) );
}

// Only if the old version is less than the new version do we run our upgrade code.
if ( version_compare( get_option( 'pib_old_version' ), $this->version, '<' ) ) {
	// need to update pib_upgrade_has_run so that we don;t load the defaults in too
	update_option( 'pib_upgrade_has_run', 1 );
	pib_do_all_upgrades();
} else {
	// Delete our holder for the old version of PIB.
	delete_option( 'pib_old_version' );
}

/**
 *  Check and run through all necessary upgrades
 *
 * @since 2.0.0
 */
function pib_do_all_upgrades() {
	
	$current_version = get_option( 'pib_old_version' );
	
	// if less than version 2 then upgrade
	if ( version_compare( $current_version, '3.2.4', '<' ) ) {
		   pib_v324_upgrade();
	}
	
	// if less than version 2 then upgrade
	if ( version_compare( $current_version, '3.2.5', '<' ) ) {
		   pib_v325_upgrade();
	}
	
	delete_option( 'pib_old_version' );
	
}

function pib_v325_upgrade() {
	$general = get_option( 'pib_settings_general' );
	
	if( isset( $general['always_enqueue'] ) ) {
		unset( $general['always_enqueue'] );
	}
	
	update_option( 'pib_settings_general', $general );
}

/**
 *  Run all needed upgrades for users coming from pre-2.0.0 
 *
 * @since 2.0.0
 */
function pib_v324_upgrade() {
	
	$advanced_options = get_option( 'pib_settings_advanced' );
	
	$advanced_options['always_enqueue'] = 1;
	
	update_option( 'pib_settings_advanced', $advanced_options );
			
}
pib_do_all_upgrades();
