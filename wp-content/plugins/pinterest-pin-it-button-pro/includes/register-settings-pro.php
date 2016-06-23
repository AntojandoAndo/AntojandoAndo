<?php

/**
 * Register all settings needed for the Settings API for use in PIB Pro
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
 * Register all of the plugins admin settings
 *
 * @since   3.0.0
 *
 */
function pib_register_settings_pro() {

	$pib_settings_pro = array(
		
		/* General Settings */
		'general' => array(
			'use_featured' => array(
				'id'   => 'use_featured',
				'name' => __( 'Use Featured Image', 'pib' ),
				'desc' => __( 'Default to featured image when button type is <strong>"Image is pre-selected"</strong>', 'pib' ) .
					// Show additional message for WooCommerce users.
					( pib_is_woo_commerce_active() ? '<br/><p class="description">' . __( 'Check this to default to your WooCommerce product featured image.', 'pib' ) . '</p>' : '' ),
				'type' => 'checkbox'
			),
			'use_custom_img_btn' => array(
				'id'   => 'use_custom_img_btn',
				'name' => __( 'Custom Page-Level Button', 'pib' ),
				'desc' => __( 'Use a custom "Pin It" button instead of an official button from Pinterest. Select below.', 'pib' ),
				'type' => 'checkbox'
			),
			'custom_btn_img_url' => array(
				'id'      => 'custom_btn_img_url',
				'name'    => '',
				'desc'    => '',
				'type'    => 'custom_button',
				'options' => array(
					'width'  => 6,
					'height' => 5
				),
				'std'     => PIB_PLUGIN_URL . '/assets/pin-it-buttons/set01/a04.png'
			),
			'custom_btn_width' => array(
				'id'   => 'custom_btn_width',
				'name' => __( 'Custom Button Width', 'pib' ),
				'desc' => __( 'px', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 43
			),
			'custom_btn_height' => array(
				'id'   => 'custom_btn_height',
				'name' => __( 'Custom Button Height', 'pib' ),
				'desc' => __( 'px', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 20
			)
		),
		
		/* Post Visibility Options */
		'post_visibility' => array(
			'custom_post_types' => array(
				'id'      => 'custom_post_types',
				'name'    => __( 'Custom Post Types', 'pib' ),
				'desc'    => '',
				'type'    => 'multicheck',
				'options' => pib_get_cpt()
			),
			'pib_woocommerce' => array(
				'id'      => 'pib_woocommerce',
				'name'    => __( 'WooCommerce Products', 'pib' ),
				'desc'    => __( 'Tip: On the General tab, if you\'re using the button type <strong>"Image is pre-selected"</strong>, ', 'pib' ) .
				             __( 'make sure <strong>"Use Featured Image"</strong> is also checked to force pinning of your product featured images.', 'pib' ),
				'type'    => 'multicheck',
				'options' => pib_woocommerce_options()
			)
		),
		
		/* Image Hover options */
		'image_hover' => array(
			'use_img_hover_btn' => array(
				'id'   => 'use_img_hover_btn',
				'name' => __( 'Enable Image Hover Button', 'pib' ),
				'desc' => __( 'Show a custom "Pin It" button when hovering over images (select below)', 'pib' ),
				'type' => 'checkbox'
			),
			'hover_btn_img_url' => array(
				'id'      => 'hover_btn_img_url',
				'name'    => '',
				'desc'    => '',
				'type'    => 'custom_button',
				'options' => array(
					'width'  => 6,
					'height' => 5
				),
				'std'     => PIB_PLUGIN_URL . '/assets/pin-it-buttons/set01/b04.png'
			),
			'hover_btn_placement' => array(
				'id'      => 'hover_btn_placement',
				'name'    => __( 'Hover Button Placement', 'pib' ),
				'desc'    => __( 'Select what corner of each image you\'d like the hover button to appear in.', 'pib' ),
				'type'    => 'select',
				'options' => array(
					'top-left'     => __( 'Top Left', 'pib' ),
					'top-right'    => __( 'Top Right', 'pib' ),
					'bottom-left'  => __( 'Bottom Left', 'pib' ),
					'bottom-right' => __( 'Bottom Right', 'pib' )
				)
			),
			'hover_btn_img_width' => array(
				'id'   => 'hover_btn_img_width',
				'name' => __( 'Hover Button Width', 'pib' ),
				'desc' => 'px',
				'type' => 'number',
				'size' => 'small',
				'std'  => 58
			),
			'hover_btn_img_height' => array(
				'id'   => 'hover_btn_img_height',
				'name' => __( 'Hover Button Height', 'pib' ),
				'desc' => 'px',
				'type' => 'number',
				'size' => 'small',
				'std'  => 27
			),
			'hover_min_img_width' => array(
				'id'   => 'hover_min_img_width',
				'name' => __( 'Minimum Image Width to Hover On', 'pib' ),
				'desc' => 'px. ' . __( 'The minimum width an image needs to be to show a "Pin It" button on hover (default: 200px).', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 200
			),
			'hover_min_img_height' => array(
				'id'   => 'hover_min_img_height',
				'name' => __( 'Minimum Image Height to Hover On', 'pib' ),
				'desc' => 'px. ' . __( 'The minimum height an image needs to be to show a "Pin It" button on hover (default: 200px).', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 200
			),
			'hover_btn_ignore_classes' => array(
				'id'   => 'hover_btn_ignore_classes',
				'name' => __( 'CSS Classes to Ignore', 'pib' ),
				'desc' => __( 'Prevent the hover button on these CSS classes. Separate classes with commas and no spaces (i.e. "class1,class2,class3"). ' .
				              'Can also be used on container elements. The built-in CSS class pib-nohover already prevents hovering.', 'pib' ),
				'type' => 'text',
				'size' => 'regular-text'
			),
			'always_show_img_hover' => array(
				'id'   => 'always_show_img_hover',
				'name' => __( 'Always Show Hover Button', 'pib' ),
				'desc' => __( 'Always show a custom "Pin It" button on images, even when not hovering over them', 'pib' ),
				'type' => 'checkbox'
			),
			'use_old_hover' => array(
				'id'   => 'use_old_hover',
				'name' => __( 'Enable Image Protection', 'pib' ),
				'desc' => __( 'Use <strong>old</strong> hover button option that prevents viewers from right-clicking to save images.', 'pib' ),
				'type' => 'checkbox'
			)
		),
		
		/* Below Image options */
		'image_misc' => array(
			'use_below_img_btn' => array(
				'id'   => 'use_below_img_btn',
				'name' => __( 'Enable Button Below Images', 'pib' ),
				'desc' => __( 'Show a "Pin It" button below each image', 'pib' ),
				'type' => 'checkbox'
			),
			'data_pin_size_below' => array(
				'id'      => 'data_pin_size_below',
				'name'    => __( 'Button Size', 'pib' ),
				'desc'    => '',
				'type'    => 'select',
				'options' => array(
					'small' => __( 'Small', 'pib' ),
					'large' => __( 'Large', 'pib' )
				)
			),
			'data_pin_shape_below' => array(
				'id'      => 'data_pin_shape_below',
				'name'    => __( 'Button Shape', 'pib' ),
				'desc'    => '',
				'type'    => 'select',
				'options' => array(
					'rectangular' => __( 'Rectangular', 'pib' ),
					'circular'    => __( 'Circular', 'pib' )
				)
			),
			'data_pin_color_below' => array( 
				'id'      => 'data_pin_color_below',
				'name'    => __( 'Button Color', 'pib' ),
				'desc'    => __( 'Color ignored if button shape is <strong>Circular</strong>.', 'pib' ),
				'type'    => 'select',
				'options' => array(
					'gray'  => __( 'Gray', 'pib' ),
					'red'   => __( 'Red', 'pib' ),
					'white' => __( 'White', 'pib' )
				)
			),
			'use_custom_below_img_btn' => array(
				'id'   => 'use_custom_below_img_btn',
				'name' => __( 'Custom Button Below Images', 'pib' ),
				'desc' => __( 'Use a custom "Pin It" button instead of an official button from Pinterest. Select below.', 'pib' ),
				'type' => 'checkbox'
			),
			'below_img_btn_url' => array(
				'id'      => 'below_img_btn_url',
				'name'    => '',
				'desc'    => '',
				'type'    => 'custom_button',
				'options' => array(
					'width'  => 6,
					'height' => 5
				),
				'std'     => PIB_PLUGIN_URL . '/assets/pin-it-buttons/set01/a04.png'
			),
			'below_img_custom_btn_width' => array(
				'id'   => 'below_img_custom_btn_width',
				'name' => __( 'Below Image Button Width', 'pib' ),
				'desc' => 'px',
				'type' => 'number',
				'size' => 'small',
				'std'  => 58
			),
			'below_img_custom_btn_height' => array(
				'id'   => 'below_img_custom_btn_height',
				'name' => __( 'Below Image Button Height', 'pib' ),
				'desc' => 'px',
				'type' => 'number',
				'size' => 'small',
				'std'  => 27
			),
			'below_img_min_width' => array(
				'id'   => 'below_img_min_width',
				'name' => __( 'Minimum Image Width to Show Button On', 'pib' ),
				'desc' => 'px. ' . __( 'The minimum height an image needs to be to show a "Pin It" button below (default: 200px).', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 200
			),
			'below_img_min_height' => array(
				'id'   => 'below_img_min_height',
				'name' => __( 'Minimum Image Height to Show Button On', 'pib' ),
				'desc' => 'px. ' . __( 'The minimum height an image needs to be to show a "Pin It" button below (default: 200px).', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 200
			),
			'below_img_ignore_classes' => array(
				'id'   => 'below_img_ignore_classes',
				'name' => __( 'CSS Classes to Ignore', 'pib' ),
				'desc' => __( 'Prevent the button from showing under images with these CSS classes. Separate classes with commas and no spaces (i.e. "class1,class2,class3"). ' .
				              'Can also be used on container elements.', 'pib' ),
				'type' => 'text',
				'size' => 'regular-text'
			)
		),
		
		/* Sharebar options */
		'share_bar' => array(
			'use_other_sharing_buttons' => array(
				'id'   => 'use_other_sharing_buttons',
				'name' => __( 'Use Social Share Bar', 'pib' ),
				'desc' => __( 'Enable other social sharing buttons in addition to the "Pin It" button', 'pib' ),
				'type' => 'checkbox'
			),
			'sharebar_button_order' => array(
				'id'      => 'sharebar_button_order',
				'name'    => __( 'Share Bar Button Selection', 'pib' ),
				'desc'    => __( 'Drag and drop to rearrange button order', 'pib' ),
				'type'    => 'sortablelist',
				'options' => array(
					'Twitter',
					'Pinterest',
					'Facebook Like',
					'Facebook Share',
					'Google +1',
					'Google Share',
					'Linked In'
				)
			),
			'sharebar_btn_width' => array(
				'id'   => 'sharebar_btn_width',
				'name' => __( 'Share Bar Button Width', 'pib' ),
				'desc' => 'px ' . __( '(default: 105px)', 'pib' ),
				'type' => 'number',
				'size' => 'small',
				'std'  => 105
			),
			'sharebar_align' => array(
				'id'      => 'sharebar_align',
				'name'    => __( 'Share Bar Alignment', 'pib' ),
				'desc'    => '',
				'type'    => 'select',
				'options' => array(
					'none'   => __( 'Not Set (default)', 'pib' ),
					'left'   => __( 'Left', 'pib' ),
					'right'  => __( 'Right', 'pib' ),
					'center' => __( 'Center', 'pib' )
				)
			),
			'sharebar_hide_count' => array(
				'id'   => 'sharebar_hide_count',
				'name' => __( 'Hide Count Bubbles', 'pib' ),
				'desc' => __( 'Hide count bubbles for non-Pinterest buttons', 'pib' ) . '<br/>' .
					'<p class="description">' . __( 'Facebook Like does not currently support hiding the count bubble. This method of cutting off the right edge may not work for non-English sites.', 'pib' ) . '</p>',
				'type' => 'checkbox'
			),
			'sharebar_fb_app_id' => array(
				'id'   => 'sharebar_fb_app_id',
				'name' => __( 'Facebook App ID', 'pib' ),
				'desc' => __( 'Improve your posts shared on Facebook by adding a unique App ID.', 'pib' ) .
					' <a href="https://developers.facebook.com/apps/?action=create" target="_blank">' . __( 'Create App ID.', 'pib' ) . '</a>' .  
					' <a href="https://developers.facebook.com/docs/wordpress/register-facebook-application/" target="_blank">' . __( 'View Instructions.', 'pib' ) . '</a>',
				'type' => 'text',
				'size' => 'regular-text' 
			),
			'sharebar_twitter_via' => array(
				'id'   => 'sharebar_twitter_via',
				'name' => __( 'Tweet "via" Username', 'pib' ),
				'desc' => __( 'Prepended with "@". Ignored if left blank.', 'pib' ),
				'type' => 'text',
				'size' => ''
			),
			'sharebar_twitter_hashtags' => array(
				'id'   => 'sharebar_twitter_hashtags',
				'name' => __( 'Tweet Hashtag (#)', 'pib' ),
				'desc' => __( 'Prepended with "#". Ignored if left blank.', 'pib' ),
				'type' => 'text',
				'size' => ''
			),
			'sharebar_no_tweet' => array(
				'id'   => 'sharebar_no_tweet',
				'name' => __( 'Remove "Tweet" Text', 'pib' ),
				'desc' => __( 'Remove the default "Tweet" text for Twitter buttons', 'pib' ) . '<br/>' .
				          '<p class="description">' . __( 'If you are seeing the unwanted "Tweet" text in post excerpts or other places try enabling this.', 'pib' ) . '</p>',
				'type' => 'checkbox'
			)
		),
		
		/* Advanced options */
		'advanced' => array(
			'disable_image_data_pin_attrs' => array(
				'id'   => 'disable_image_data_pin_attrs',
				'name' => __( 'Disable Image <code>data-pin</code> Attributes', 'pib' ),
				'desc' => __( 'Prevent Pinterest-specific data attributes from being added to image tags.', 'pib' ) . '<br/>' .
				          '<p class="description">' . __( 'Currently data-pin-media and data-pin-url attributes are added in server-side PHP.', 'pib' ) . '</p>',
				'type' => 'checkbox'
			),
			'utm_string' => array(
				'id'   => 'utm_string',
				'name' => __( 'UTM Variables', 'pib' ),
				'desc' => __( 'UTM tracking querystring to add to the end of your pin URLs. Include everything after the "?".', 'pib' ) . 
							'<br /><strong>' . __( 'This feature currently only works for "Image pre-selected" ( General &rarr; Button Type )', 'pib' ) . '</strong>' . 
							'<br />' . __( 'Example: utm_source=pinterest&utm_medium=pin&utm_campaign=special-deal', 'pib' ),
				'type' => 'text',
				'size' => 'regular-text'
			),
		),
		
		/* Support options */
		'support' => array(
			'pib_license_key' => array(
				'id'      => 'pib_license_key',
				'name'    => __( 'License Key', 'pib' ),
				'desc'    => __( 'Enter your license key here to receive automatic updates and support.', 'pib' ),
				'type'    => 'license',
				'size'    => 'regular-text',
				'std'     => '',
				'product' => 'Pin It Button Pro'
			),
			'no_input_widget' => array(
				'id'    => 'no_input_widget',
				'name'  => __( 'Looking for a Widget?', 'pib' ),
				'desc'  => sprintf( __( 'Go to Appearance &rarr; <a href="%s">Widgets</a> to add a "Pin It" button to your sidebar or other widget area.', 'pib' ), admin_url( 'widgets.php' ) ) . '<br />' .
				           sprintf( __( 'Need more widgets? Check out our free <a href="%s">Pinterest Widgets</a> plugin.', 'pib' ),
								esc_url( add_query_arg( array(
									'tab'  => 'search',
									'type' => 'term',
									's'    => urlencode( 'pinterest widgets' )
								), admin_url( 'plugin-install.php' ) ) )
					),
				'type'  => 'no_input'
			),
			'no_input_shortcodes' => array(
				'id'    => 'no_input_shortcodes',
				'name'  => __( 'Need to use Shortcodes?', 'pib' ),
				'desc'  => sprintf( __( 'Visit the <a href="%s">Help Section</a> for shortcode attributes and examples.', 'pib' ),
					esc_url( add_query_arg( 'page', PIB_PLUGIN_SLUG . '_help', admin_url( 'admin.php' ) ) ) ),
				'type'  => 'no_input'
			),
			'no_input_kb' => array(
				'id'    => 'no_input_kb',
				'name'  => __( 'Knowledgebase & Support', 'pib' ),
				'desc'  => sprintf( __( 'Visit our <a target="_blank" href="%s">Knowledgebase</a> where you can also submit a support request.', 'pib' ),
					pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'support', 'pib_pro_3', 'support_tab', 'support' ) ),
				'type'  => 'no_input'
			)
		)
	);
	
	// Add our options if they don't already exist
	if( false == get_option( 'pib_settings_image_hover' ) ) {
		add_option( 'pib_settings_image_hover' );
	}

	if( false == get_option( 'pib_settings_image_misc' ) ) {
		add_option( 'pib_settings_image_misc' );
	}
	if( false == get_option( 'pib_settings_share_bar' ) ) {
		add_option( 'pib_settings_share_bar' );
	}
	
	if( false == get_option( 'pib_settings_support' ) ) {
		add_option( 'pib_settings_support' );
	}
	
	if( false == get_option( 'pib_sharebar_buttons' ) )
		add_option( 'pib_sharebar_buttons' );
	
	/* add additional general settings */
	foreach ( $pib_settings_pro['general'] as $option ) {
		add_settings_field(
			'pib_settings_general[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_general',
			'pib_settings_general',
			pib_get_settings_field_args( $option, 'general' )
		);
	}
	
	/* add additional post visibility settings */
	foreach ( $pib_settings_pro['post_visibility'] as $option ) {
		
		if( $option['id'] == 'custom_post_types' )
			if( empty( $option['options'] ) )
				continue;
				
		if( $option['id'] == 'pib_woocommerce' )
			if( empty( $option['options'] ) )
				continue;
		
		add_settings_field(
			'pib_settings_post_visibility[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_post_visibility',
			'pib_settings_post_visibility',
			pib_get_settings_field_args( $option, 'post_visibility' )
		);
	}
	
	/* Add the Image Hover settings section */
	add_settings_section(
		'pib_settings_image_hover',
		__( 'Image Hover Settings', 'pib' ),
		'__return_false',
		'pib_settings_image_hover'
	);
	
	foreach ( $pib_settings_pro['image_hover'] as $option ) {
		add_settings_field(
			'pib_settings_image_hover[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_image_hover',
			'pib_settings_image_hover',
			pib_get_settings_field_args( $option, 'image_hover' )
		);
	}

	/* Add the Image Misc settings section */
	add_settings_section(
		'pib_settings_image_misc',
		__( 'Below Image Button Settings', 'pib' ),
		'__return_false',
		'pib_settings_image_misc'
	);

	foreach ( $pib_settings_pro['image_misc'] as $option ) {
		add_settings_field(
			'pib_settings_image_misc[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_image_misc',
			'pib_settings_image_misc',
			pib_get_settings_field_args( $option, 'image_misc' )
		);
	}

	/* Add the Share Bar settings section */
	add_settings_section(
		'pib_settings_share_bar',
		__( 'Share Bar Settings', 'pib' ),
		'__return_false',
		'pib_settings_share_bar'
	);
	
	foreach ( $pib_settings_pro['share_bar'] as $option ) {
		add_settings_field(
			'pib_settings_share_bar[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_share_bar',
			'pib_settings_share_bar',
			pib_get_settings_field_args( $option, 'share_bar' )
		);
	}
	
	// Before adding advanced options we need to see if the user is using NextGEN and add an extra option if they are
	if( pib_is_nextgen_active() ) {
		$pib_settings_pro['advanced']['nextgen_thumbs'] = array(
			'id'   => 'nextgen_thumbs',
			'name' => __( 'Fix NextGen Thumbnail Pins', 'pib' ),
			'desc' => __( 'Enable to force pins of NextGen thumbnails to use their linked full-size images. However, this disables the below image "Pin It" button option.', 'pib' ) . '<br/>' .
			          '<p class="description">' . __( 'Sorry, but we could not yet get both to work for NextGen users.', 'pib' ) . '</p>',
			'type' => 'checkbox'
		);
	}
	
	
	/* Add additional options to Advanced section */
	foreach ( $pib_settings_pro['advanced'] as $option ) {
		add_settings_field(
			'pib_settings_advanced[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_advanced',
			'pib_settings_advanced',
			pib_get_settings_field_args( $option, 'advanced' )
		);
	}
	
	/* Add the Support settings section */
	add_settings_section(
		'pib_settings_support',
		__( 'Support', 'pib' ),
		'__return_false',
		'pib_settings_support'
	);
	
	foreach ( $pib_settings_pro['support'] as $option ) {
		add_settings_field(
			'pib_settings_support[' . $option['id'] . ']',
			$option['name'],
			function_exists( 'pib_' . $option['type'] . '_callback' ) ? 'pib_' . $option['type'] . '_callback' : 'pib_missing_callback',
			'pib_settings_support',
			'pib_settings_support',
			pib_get_settings_field_args( $option, 'support' )
		);
	}
	
	register_setting( 'pib_settings_image_hover', 'pib_settings_image_hover', 'pib_settings_sanitize' );
	register_setting( 'pib_settings_image_misc',  'pib_settings_image_misc',  'pib_settings_sanitize' );
	register_setting( 'pib_settings_share_bar',   'pib_settings_share_bar',   'pib_settings_sanitize' );
	register_setting( 'pib_settings_support',     'pib_settings_support',     'pib_settings_sanitize' );
	
}
add_action( 'admin_init', 'pib_register_settings_pro' );

/**
 * No Input callback function
 *
 * @since   3.0.0
 *
 */
function pib_no_input_callback( $args ) {
	global $pib_options;

	$html = '';

	// Render and style description text underneath if it exists.
	if ( ! empty( $args['desc'] ) )
		$html .= '<p class="description pib-no-input-description">' . $args['desc'] . '</p>' . "\n";

	echo $html;
}

/**
 * License callback function
 *
 * @since   3.0.0
 *
 */
function pib_license_callback( $args ) {
	global $pib_options;

	if ( isset( $pib_options[ $args['id'] ] ) ) {
		$value = $pib_options[ $args['id'] ];
	} else {
		$value = isset( $args['std'] ) ? $args['std'] : '';
	}

	$item = '';

	$html  = '<div class="pib-license-wrap">' . "\n";

	$size  = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular-text';
	$html .= '<input type="text" class="pib-license-input ' . $size . '" id="pib_settings_' . $args['section'] . '[' . $args['id'] . ']" ' .
	         'name="pib_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . trim( esc_attr( $value ) ) . '"/>' . "\n";

	$licenses = get_option( 'pib_licenses' );

	$license_action = '';
	$button_text = '';

	// Add button on side of input
	if ( ! empty( $licenses[ $args['product'] ] ) && $licenses[ $args['product'] ] == 'valid' && ! empty( $value ) ) {
		$license_action = 'deactivate_license';
		$button_text = __( 'Deactivate', 'pib' );
	} else {
		$license_action = 'activate_license';
		$button_text = __( 'Activate', 'pib' );
	}

	$html .= '<button class="pib-license-action button" data-pib-action="' . $license_action . '" ' .
	         'data-pib-item="' . ( ! empty( $args['product'] ) ? $args['product'] : 'none' ) . '">' . $button_text . '</button>';

	$valid = pib_check_license( $value, $args['product'] );

	$license_class = '';
	$valid_message = '';

	if ( $valid == 'valid' ) {
		$license_class = 'pib-valid';
		$valid_message = __( 'License is valid and active.', 'pib' );
	} else if( $valid == 'notfound' ) {
		$license_class = 'pib-invalid';
		$valid_message = __( 'License service could not be found. Please contact support for additional help.', 'pib' );
	} else {
		$license_class = 'pib-inactive';
		$valid_message = __( 'License is inactive.', 'pib' );
	}

	$html .= '<span class="pib-spinner spinner"></span>';
	$html .= '<span class="pib-license-message ' . $license_class . '">' . $valid_message . '</span>';

	// Render and style description text underneath if it exists.
	if ( ! empty( $args['desc'] ) ) {
		$html .= '<p class="description">' . $args['desc'] . '</p>' . "\n";
	}

	$html .= '</div>';

	echo $html;
}

/**
 * Sortable List callback function
 *
 * @since   3.0.0
 *
 */
function pib_sortablelist_callback( $args ) {
	global $pib_options;
	
	$sharebar_buttons = get_option( 'pib_sharebar_buttons' );

	// Available Buttons

	$html =  '<div id="pib-share-available">' . "\n";
	$html .= '<h3>' . __( 'Available Buttons', 'pib' );
	$html .= '<small>&nbsp;- ' . __( 'Drag and drop the buttons you\'d like to enable on your site into the box below.', 'pib' ) . '</small>' . "\n";
	$html .= '</h3>' . "\n";
	$html .= '<ul>' . "\n";
		
	foreach( $args['options'] as $key => $value ) {
		
		if( ! empty( $sharebar_buttons['button_order'] ) && ! in_array( $value, $sharebar_buttons['button_order'] ) ) {
			$html .= '<li class="pib-admin-share-item ' . $value . '" data-id="'. $value .'">' . $value . '</li>' . "\n";
		} elseif ( empty( $sharebar_buttons['button_order'] ) ) {
			$html .= '<li class="pib-admin-share-item ' . $value . '" data-id="'. $value .'">' . $value . '</li>' . "\n";
		}
	}
		
	$html .= '</ul>' . "\n";
	$html .= '<br style="clear: both;" />' . "\n";
	$html .= '</div>' . "\n";
	
	// Enabled Buttons

	$html .=  '<div id="pib-share-enabled">' . "\n";
	$html .= '<h3>' . __( 'Enabled Buttons', 'pib' );
	$html .= '<small>&nbsp;- ' . __( 'Buttons dragged here will be enabled on your site. Drag back above to disable.', 'pib' ) . '</small>' . "\n";
	$html .= '</h3>' . "\n";
	$html .= '<ul>' . "\n";
	
	if( ! empty( $sharebar_buttons['button_order'] ) ) {
		foreach( $sharebar_buttons['button_order'] as $key => $value ) {
			$html .= '<li class="pib-admin-share-item ' . $value . '" data-id="'. $value .'">' . $value . '</li>';
		}
	}
	
	$html .= '</ul>' . "\n";
	$html .= '<br style="clear: both;" />' . "\n";
	$html .= '</div>' . "\n";
	
	echo $html;
}

/**
 * Custom callback for custom buttons
 *
 * @since   3.0.0
 *
 */
function pib_custom_button_callback( $args ) {
	global $pib_options;

	//echo "<pre>" . print_r( $pib_options, TRUE ) . "</pre>";

	$table_width  = $args['options']['width'];
	$table_height = $args['options']['height'];

	if ( isset( $pib_options[ $args['id'] ] ) )
		$value = $pib_options[ $args['id'] ];
	else
		$value = isset( $args['std'] ) ? $args['std'] : '';

	$html  = '<table class="pib-cb-admin">';
	$html .= '<tr>';
	$html .= '<td>Current:</td>';
	$html .= '<td>';
	$html .= '<img id="' . $args['id'] . '_source" src="' . $value . '" />';
	$html .= '</td>';
	$html .= '<td>';
	$html .= '<a id="'. $args['id'] .'_select_link" href="#TB_inline?width=600&amp;height=400&amp;inlineId=' . $args['id'] . '_selector" class="thickbox" title=\'Select a "Pin It" Button\'>' . __( 'Select new page-level button', 'pib' ) . '</a>';
	$html .= '<br/>';
	$html .= '<a id="'. $args['id'] .'_media_library_link" href="#">' . __( 'Upload custom button image or use media library', 'pib' ) . '</a>';
	$html .= '<br/>';
	$html .= '<input type="text" id="'. $args['id'] .'_url" class="regular-text" name="pib_settings_' . $args['section'] . '[' . $args['id'] . ']" value="' . esc_attr( $value ) . '" />';
	$html .= '</td>';
	$html .= '</tr>';
	$html .= '</table>';

	$html .= '<div class="pib-cb-admin" id="' . $args['id'] . '_selector" style="display:none;">';
    $html .= '<form class="pib-cb-admin" id="' . $args['id'] . '_selector_form" action="" method="post">';
    $html .= '<table class="pib-cb-admin-popup">';

	$abc_string = 'abcdefghijklmnopqrstuvwxyz';

	for( $y = 0; $y < $table_height; $y++ ) {
		$html .= '<tr>';
		for( $x = 0; $x < $table_width; $x++ ) {
			$html .= pib_btn_img_cell( $abc_string{$x}, str_pad($y + 1, 2, '0', STR_PAD_LEFT) );
		}
		$html .= '</tr>';
	}

    $html .= '</table>';
    $html .= '<p> Click any button image to select.</p>';
    $html .= '</form>';
	$html .= '</div>';

	echo $html;
}

/**
 * Generate clickable table cell & image html for each button image
 *
 * @since   3.0.0
 *
 */
function pib_btn_img_cell( $major, $minor ) {
    $imgUrl = plugins_url() . '/' . PIB_PLUGIN_SLUG . '/assets/pin-it-buttons/set01/' . $major . $minor . '.png';
    
    return '<td class="custom-btn-img-cell" data-img-url="' . $imgUrl . '" href="javascript:void(0)"><img src="' . $imgUrl . '" /></td>';
}

/**
 * Find all custom post types and arrange them in an array we can return to use for options
 *
 * @since   3.0.0
 *
 */
function pib_get_cpt() {
	
	$args = array ( 'public' => true, '_builtin' => false );
	
	$returnArr = array();
	
	foreach( get_post_types( $args, 'objects' ) as $cpt ) {
		if( "wooframework" == $cpt->name )
			continue;
		
		$returnArr['display_post_type_' . $cpt->name] = $cpt->labels->name;
	}
	
	return $returnArr;
}

/**
 * Function to check if WooCommerce is installed and return a set of options if it is
 *
 * @since   3.0.0
 *
 */
function pib_woocommerce_options() {
	
	//if( ! class_exists( 'WooCommerce' ) )
	if ( ! pib_is_woo_commerce_active() )
		return array();
	
	$options = array(
		'display_above_summary'     => __( 'Above WooCommerce Product Short Description', 'pib' ),
		'display_below_summary'     => __( 'Below WooCommerce Product Short Description', 'pib' ),
		'display_above_cart_button' => __( 'Above WooCommerce Add to Cart Button', 'pib' ),
		'display_below_cart_button' => __( 'Below WooCommerce Add to Cart Button', 'pib' )
	);
	
	return $options;
}

/**
 * Add our new Pro settings to the regular settings
 *
 * @since   3.0.0
 *
 */
function pib_get_settings_pro() {
	
	global $pib_options;
	
	$image_hover_settings = is_array( get_option( 'pib_settings_image_hover' ) ) ? get_option( 'pib_settings_image_hover' ) : array();
	$image_misc_settings  = is_array( get_option( 'pib_settings_image_misc' ) ) ? get_option( 'pib_settings_image_misc' ) : array();
	$share_bar_settings   = is_array( get_option( 'pib_settings_share_bar' ) ) ? get_option( 'pib_settings_share_bar' ) : array();
	$support_settings     = is_array( get_option( 'pib_settings_support' ) ) ? get_option( 'pib_settings_support' ) : array();
	
	return array_merge( $pib_options, $image_hover_settings, $image_misc_settings, $share_bar_settings, $support_settings );
}
