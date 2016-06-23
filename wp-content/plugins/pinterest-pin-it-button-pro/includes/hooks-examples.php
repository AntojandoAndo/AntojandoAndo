<?php

/*************************
 * FILTER HOOKS
 ************************/
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Modifies the HTML output of the shortcode button
 * 
 * @since 3.1.2
 */
function test_pib_shortcode_html( $html ) {
	return '<div style="border: 5px solid #f00; padding: 15px;">' . $html . '</div>';
}
//add_filter( 'pib_shortcode_html', 'test_pib_shortcode_html' );


/**
 * Used to modify the widget HTML
 * 
 * @since 3.1.2
 */
function test_pib_widget_html( $html ) {
	return '<div style="border: 5px solid #f00; padding: 15px;">' . $html . '</div>';
}
//add_filter( 'pib_widget_html', 'test_pib_widget_html' );


/**
 * Used to modify the regular button HTML
 * 
 * @since 3.1.2
 */
function test_pib_button_html( $html ) {
	return '<div style="border: 5px solid #f00; padding: 15px;">' . $html . '</div>';
}
//add_filter( 'pib_button_html', 'test_pib_button_html' );


/**
 * Outputs additional HTML before the PIB shortcode
 * 
 * @since 3.1.2
 */
function test_pib_shortcode_before( $before_html ) {
	return $before_html . '<p>Before</p>';
}
//add_filter( 'pib_shortcode_before', 'test_pib_shortcode_before' );


/**
 * Outputs additional HTML after the PIB shortcode
 * 
 * @since 3.1.2
 */
function test_pib_shortcode_after( $after_html ) {
	return $after_html . '<p>After</p>';
}
//add_filter( 'pib_shortcode_after', 'test_pib_shortcode_after' );


/**
 * Outputs additional HTML before the default button
 * 
 * @since 3.1.2
 */
function test_pib_button_before( $before_html ) {
	return '<p>Button Before</p>';
}
//add_filter( 'pib_button_before', 'test_pib_button_before' );


/**
 * Outputs additional HTML after the default button
 * 
 * @since 3.1.2
 */
function test_pib_button_after( $after_html ) {
	return '<p>Button After</p>';
}
//add_filter( 'pib_button_after', 'test_pib_button_after' );


/**
 * Output before the sharebar HTML
 * 
 * @since 3.1.2
 */
function test_pib_sharebar_before( $before_html ) {
	return '<p>Share This: </p>';
}
add_filter( 'pib_sharebar_before', 'test_pib_sharebar_before' );


/**
 * Modify the sharebar HTML
 * 
 * @since 3.1.2
 */
function test_pib_sharebar_html( $html ) {
	return '';
}
//add_filter( 'pib_sharebar_html', 'test_pib_sharebar_html' );


/**
 * Output after the sharebar HTML
 * 
 * @since 3.1.2
 */
function test_pib_sharebar_after( $after_html ) {
	return '';
}
//add_filter( 'pib_sharebar_after', 'test_pib_sharebar_after' );


/**
 * Output before the below image button HTML
 * 
 * @since 3.1.2
 */
function test_pib_below_image_button_before( $before_html ) {
	return '<p>Before Below Image Button</p>';
}
//add_filter( 'pib_below_image_button_before', 'test_pib_below_image_button_before' );


/**
 * Modify the below image button HTML
 * 
 * @since 3.1.2
 */
function test_pib_below_image_button_html( $button_html ) {
	return '<div style="border: 5px solid #f00; padding: 15px;">' . $button_html . '</div>';
}
//add_filter( 'pib_below_image_button_html', 'test_pib_below_image_button_html' );


/**
 * Output after the below image button HTML
 * 
 * @since 3.1.2
 */
function test_pib_below_image_button_after( $after_html ) {
	return '<p>After Below Image Button</p>';
}
//add_filter( 'pib_below_image_button_after', 'test_pib_below_image_button_after' );

/*************************
 * ACTION HOOKS
 ************************/

/**
 * Outputs before the PIB widget button HTML
 * 
 * @since 3.1.2
 */
function test_pib_widget_before() {
	echo '<p>Widget Before</p>';
}
//add_action( 'pib_widget_before', 'test_pib_widget_before' );


/**
 * Outputs after the PIB widget button HTML
 * 
 * @since 3.1.2
 */
function test_pib_widget_after() {
	echo '<p>Widget After</p>';
}
//add_action( 'pib_widget_after', 'test_pib_widget_after' );
