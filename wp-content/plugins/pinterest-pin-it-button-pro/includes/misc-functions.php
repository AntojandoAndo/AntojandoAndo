<?php

/**
 * Misc functions to use throughout the plugin.
 *
 * ### PRO misc-functions.php is different than LITE. Don't just copy this between projects.
 *
 * @package    PIB Pro
 * @subpackage Includes
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* 
 * Reusable variables
 * 
 * @since 3.1.1
 */
global $pib_vars;

$pib_vars['cache_message']     = 'If your site has caching enabled please empty it before viewing your changes.';
$pib_vars['post_meta_message'] = '(and sharebar)';

/**
 * Google Analytics campaign URL.
 *
 * @since     2.0.0
 *
 * @param   string  $base_url Plain URL to navigate to
 * @param   string  $source   GA "source" tracking value
 * @param   string  $medium   GA "medium" tracking value
 * @param   string  $campaign GA "campaign" tracking value
 * @return  string  $url     Full Google Analytics campaign URL
 */
function pib_ga_campaign_url( $base_url, $source, $medium, $campaign ) {
	// $source is always 'pib_lite_2' for Pit It Button Lite 2.x
	// $medium examples: 'sidebar_link', 'banner_image'

	$url = esc_url( add_query_arg( array(
		'utm_source'   => $source,
		'utm_medium'   => $medium,
		'utm_campaign' => $campaign
	), $base_url ) );

	return $url;
}

/**
 * Render RSS items from pinplugins.com in unordered list.
 * http://codex.wordpress.org/Function_Reference/fetch_feed
 *
 * @since   3.0.0
 */
function pib_rss_news() {
	// Get RSS Feed(s).
	include_once( ABSPATH . WPINC . '/feed.php' );

	// Get a SimplePie feed object from the specified feed source.
	$rss = fetch_feed( PINPLUGIN_BASE_URL . 'feed/' );

	if ( ! is_wp_error( $rss ) ) {
		// Checks that the object is created correctly.
		// Figure out how many total items there are, but limit it to 5.
		$maxitems = $rss->get_item_quantity( 3 );

		// Build an array of all the items, starting with element 0 (first element).
		$rss_items = $rss->get_items( 0, $maxitems );
	}
	?>

	<ul>
		<?php if ($maxitems == 0): ?>
			<li><?php _e( 'No items.', 'pib' ); ?></li>
		<?php else: ?>
			<?php
			// Loop through each feed item and display each item as a hyperlink.
			foreach ( $rss_items as $item ): ?>
				<?php $post_url = esc_url( add_query_arg( array(

					/******************************************
					 * Different GA campaign source than LITE *
					 ******************************************/
					'utm_source'   => 'pib_pro_3',
					'utm_medium'   => 'sidebar_link',
					'utm_campaign' => 'blog_post_link'

				), $item->get_permalink() ) ); ?>

				<li>
					<div class="dashicons dashicons-arrow-right-alt2"></div>
					<a href="<?php echo $post_url; ?>" target="_blank" class="pib-external-link"><?php echo esc_html( $item->get_title() ); ?></a>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
	</ul>

<?php
}

/**
 * Check if the WooCommerce plugin is active.
 *
 * @since   3.0.0
 *
 * @return  boolean
 */
function pib_is_woo_commerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Check if the NextGEN Gallery plugin is active.
 *
 * @since   3.1.1
 *
 * @return  boolean
 */
function pib_is_nextgen_active() {
	return class_exists( 'C_NextGEN_Bootstrap' );
}

/**
 * Checks if use featured image option is selected and returns the image URL if it is
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_featured_image( $force = false ) {
	
	global $post, $pib_options;
	$image_url = '';
	
	$postID = $post->ID;
	
	//Use featured image if specified
    if ( ! empty( $pib_options['use_featured'] ) || $force ) {
		if ( has_post_thumbnail( $postID ) ) {
			$featured_img = wp_get_attachment_image_src( get_post_thumbnail_id( $postID ), 'single-post-thumbnail' );
        
			if ( $featured_img ) {
				$image_url = $featured_img[0];
			}
		}
    }
	
	return $image_url;
}

/**
 * Share Bar item html based on button type
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_sharebar_item_html( $btn, $pib_btn_html = '' ) { // $btn_type = '', $image_url = ''  ) {
    global $pib_options;
	global $post;
    $postID = $post->ID;
    
    $sharebar_hide_count       = ( ! empty( $pib_options['sharebar_hide_count'] ) );
	$sharebar_twitter_via      = ( ! empty( $pib_options['sharebar_twitter_via'] ) ? $pib_options['sharebar_twitter_via'] : '' );
	$sharebar_twitter_hashtags = ( ! empty( $pib_options['sharebar_twitter_hashtags'] ) ? $pib_options['sharebar_twitter_hashtags'] : '' );

    switch ( strtolower( $btn ) ) {
        case 'pinterest':
			if( ! empty( $pib_btn_html ) ) {
				return '<li class="pib-sharebar-pinterest">' . $pib_btn_html . '</li>';
			} else {
				return '<li class="pib-sharebar-pinterest">' . pib_button_html( pib_featured_image() ) . '</li>';
			}
        case 'facebook like':
            return '<li class="pib-sharebar-facebook">' . pib_share_facebook_like( $postID, $sharebar_hide_count ) . '</li>';
		case 'facebook share':
			return '<li class="pib-sharebar-facebook-share">'. pib_facebook_share( $postID, $sharebar_hide_count ) . '</li>';
        case 'twitter':
            return '<li class="pib-sharebar-twitter">' . pib_share_twitter( $postID, $sharebar_hide_count, $sharebar_twitter_via, $sharebar_twitter_hashtags ) . '</li>';
        case 'google +1':
            return '<li class="pib-sharebar-gplus">' . pib_share_gplus( $postID, $sharebar_hide_count ) . '</li>';
		case 'google share':
			return '<li class="pib-sharebar-gplus-share">' . pib_share_gplus_share( $postID, $sharebar_hide_count ) . '</li>';
        case 'linked in':
            return '<li class="pib-sharebar-linkedin">' . pib_share_linkedin( $postID, $sharebar_hide_count ) . '</li>';
        default:
            return '';
    }
}

function pib_check_license( $license, $item ) {
	
	$check_params = array(
		'edd_action' => 'check_license',
		'license'    => $license,
		'item_name'  => urlencode( $item ),
		'url'        => home_url(),
	);
	
	$response = wp_remote_post( PIB_EDD_SL_STORE_URL, array( 'timeout' => 15, 'body' => $check_params, 'sslverify' => false ) );

	if( is_wp_error( $response ) )
	{
		return 'error';
	}
	
	$is_valid = json_decode( wp_remote_retrieve_body( $response ) );
	
	if( ! empty( $is_valid ) ) {
		return json_decode( wp_remote_retrieve_body( $response ) )->license;
	} else {
		return 'notfound';
	}
}

function pib_activate_license() {
	$pib_licenses = get_option( 'pib_licenses' );
	
	$current_license = $_POST['license'];
	$item            = $_POST['item'];
	$action          = $_POST['pib_action'];
	$id              = $_POST['id'];
	
	// Need to trim the id of the excess stuff so we can update our option later
	$length = strpos( $id, ']' ) - strpos( $id, '[' );
	$id = substr( $id, strpos( $id, '[' ) + 1, $length - 1 );
	
	// Do activation
	$activate_params = array(
		'edd_action' => $action,
		'license'    => $current_license,
		'item_name'  => urlencode( $item ),
		'url'        => home_url(),
	);

	$response = wp_remote_post( PIB_EDD_SL_STORE_URL, array( 'timeout' => 15, 'body' => $activate_params, 'sslverify' => false ) );

	if( is_wp_error( $response ) )
	{
		echo 'ERROR';
		
		die();
	}
	
	$activate_data = json_decode( wp_remote_retrieve_body( $response ) );
	
	if( $activate_data->license == 'valid' ) {
		$pib_licenses[$item] = 'valid';
		
		$pib_settings_licenses = get_option( 'pib_settings_support' );
		
		$pib_settings_licenses['pib_license_key'] = $current_license;
		
		update_option( 'pib_settings_support', $pib_settings_licenses );
		
		
	} else if( $activate_data->license == 'deactivated' ) {
		$pib_licenses[$item] = 'deactivated';
	} else {
		$pib_licenses[$item] = 'invalid';
	}
	
	update_option( 'pib_licenses', $pib_licenses );
	
	echo $activate_data->license;
	
	die();
}
add_action( 'wp_ajax_pib_activate_license', 'pib_activate_license' );

/**
 * Check if the Article Rich Pins plugin is active.
 *
 * @since   3.0.6
 *
 * @return  boolean
 */
function pib_is_article_rich_pins_active() {
	return class_exists( 'Article_Rich_Pins' );
}

/**
 * Check if the WooCommerce Rich Pins plugin is active.
 *
 * @since   3.0.3
 *
 * @return  boolean
 */
function pib_is_wc_rich_pins_active() {
	return class_exists( 'WooCommerce_Rich_Pins' );
}

/**
 * Check if we should render the Pinterest button
 * NOTE: The order of the checks in this function are VERY important and should not be altered unless an issue is encountered
 *
 * @since   3.1.3
 *
 * @return  boolean
 */
function pib_render_button( $post = null, $load_scripts = false ) {
	global $pib_options;
	
	// If $post parameter is not sent then we load the global $post object
	if( $post === null ) {
		global $post;
	}
	
	$return = array();
	
	// Check individual post meta option
	$disable = get_post_meta( $post->ID, 'pib_sharing_disabled', true );
	
	if( $disable && ! isset( $pib_options['always_enqueue'] ) ) {
		$return[] = 'no_buttons';
		
		return $return;
	}
	
	if ( isset( $pib_options['always_enqueue'] ) ) {
		$return[] = 'always_enqueue';
	}
	
	// Check if a shortcode exists
	if( has_shortcode( $post->post_content, 'pinit' ) ) {
		$return[] = 'shortcode';
	}
	
	// Check if there is a widget
	if( is_active_widget( false, false, 'pib_button', true ) ) {
		$return[] = 'widget';
	}

	// Determine if button displayed on current page from main admin settings
	if (
			( is_home() && ( ! empty( $pib_options['post_page_types']['display_home_page'] ) ) ) ||
			( is_front_page() && ( ! empty( $pib_options['post_page_types']['display_front_page'] ) ) ) ||
			( is_single() && ( ! empty( $pib_options['post_page_types']['display_posts'] ) ) ) ||
			( is_page() && ( ! empty( $pib_options['post_page_types']['display_pages'] ) ) && !is_front_page() ) ||

			//archive pages besides categories (tag, author, date, search)
			//http://codex.wordpress.org/Conditional_Tags
			( is_archive() && ( ! empty( $pib_options['post_page_types']['display_archives'] ) ) &&
			   ( is_tag() || is_author() || is_date() || is_search() || is_category() )
			)
		) {
		
			$return[] = 'button';
			
		} else {
			// check for custom post types
			if( empty( $return ) ) {
				if( ! ( get_post_type_object( get_post_type( $post->ID ) )->_builtin ) ) {
					if( empty( $pib_options['custom_post_types'] ) ) {
						$return[] = 'no_buttons';
					} else { 
						$post_type = strtolower( get_post_type_object( $post->post_type )->labels->name );

						foreach( $pib_options['custom_post_types'] as $k => $v ) {
							if( ! ( $post_type == strtolower( $v ) ) ) {
								$return[] = 'no_buttons';
							}
						}
					}

					if( ! in_array( 'no_buttons', $return ) ) {
						$return[] = 'button';
					}
				}
			}
		}
	
	if( empty( $return ) ) {
		$return[] = 'no_buttons';
	} 
	
	if( $load_scripts ) {
		global $pib_options;
		
		$post_meta_disable = get_post_meta( $post->ID, 'pib_sharing_disabled', true );
		$post_meta_disable = ! empty( $post_meta_disable ) ? '1' : '0';

		/// Get sharebar buttons
		$enabledButtons = get_option( 'pib_sharebar_buttons' );
		
		// Page-level custom buttons
		$pageCustomBtnClass     = ( ! empty( $pib_options['use_custom_img_btn'] ) ? $pib_options['custom_btn_img_url'] : null );
		$pageCustomBtnWidth     = ( ! empty( $pib_options['custom_btn_width'] ) ? $pib_options['custom_btn_width'] : 32 );
		$pageCustomBtnHeight    = ( ! empty( $pib_options['custom_btn_height'] ) ? $pib_options['custom_btn_height'] : 32 );

		// Hover buttons
		$useHoverButton         = ( ! empty( $pib_options['use_img_hover_btn'] ) ? $pib_options['use_img_hover_btn'] : 0 );
		$hoverBtnPlacement      = ( ! empty( $pib_options['hover_btn_placement'] ) ? $pib_options['hover_btn_placement'] : 'top-left' );
		$hoverMinImgWidth       = ( ! empty( $pib_options['hover_min_img_width'] ) ? $pib_options['hover_min_img_width'] : 200 );
		$hoverMinImgHeight      = ( ! empty( $pib_options['hover_min_img_height'] ) ? $pib_options['hover_min_img_height'] : 200 );
		$alwaysShowHover        = ( ! empty( $pib_options['always_show_img_hover'] ) ? $pib_options['always_show_img_hover'] : 0 );
		$hoverBtnWidth          = ( ! empty( $pib_options['hover_btn_img_width'] ) ? $pib_options['hover_btn_img_width'] : 58 );
		$hoverBtnHeight         = ( ! empty( $pib_options['hover_btn_img_height'] ) ? $pib_options['hover_btn_img_height'] : 27 );
		$useOldHover            = ( ! empty( $pib_options['use_old_hover'] ) ? $pib_options['use_old_hover'] : 0 );
		$hoverIgnoreClasses     = ( ! empty( $pib_options['hover_btn_ignore_classes'] ) ? $pib_options['hover_btn_ignore_classes'] : '' );

		$showZeroCount          = ( ! empty( $pib_options['show_zero_count'] ) ? 'true' : 'false' );

		// Sharebar options
		$sharebarEnabled        = ( ! empty( $pib_options['use_other_sharing_buttons'] ) ? 1 : 0 );
		$enabledSharebarButtons = ( ! empty( $enabledButtons['button_order'] ) ? $enabledButtons['button_order'] : array() );
		$sharebarFbAppId        = ( ! empty( $pib_options['sharebar_fb_app_id'] ) ? $pib_options['sharebar_fb_app_id'] : '' );

		// Grab our post meta options to pass to the JS
		$pmOverride    = get_post_meta( $post->ID, 'pib_override_hover_description', true );
		$pmDescription = get_post_meta( $post->ID, 'pib_description', true );

		$pmOverride    = ( ! empty( $pmOverride ) ? 1 : 0 );

		// Send over if we should include pinit.js or not
		$disablePinitJS = ( ! empty( $pib_options['no_pinit_js'] ) ? 1 : 0 );

		// Other plugins
		$otherPlugins = pib_other_plugins();

		// Pass in variables to JS. Reference slug of async script loader.
		wp_localize_script( 'pib-async-script-loader', 'pibJsVars',
			array(
				// Folder for script files.
				'scriptFolder'            => PIB_PLUGIN_URL . 'js/',

				// Page-level custom buttons
				'pageCustomBtnClass'      => $pageCustomBtnClass,
				'pageCustomBtnWidth'      => $pageCustomBtnWidth,
				'pageCustomBtnHeight'     => $pageCustomBtnHeight,

				// Hover buttons
				'useHoverButton'          => $useHoverButton,
				'hoverBtnPlacement'       => $hoverBtnPlacement,
				'hoverMinImgWidth'        => $hoverMinImgWidth,
				'hoverMinImgHeight'       => $hoverMinImgHeight,
				'alwaysShowHover'         => $alwaysShowHover,
				'hoverBtnWidth'           => $hoverBtnWidth,
				'hoverBtnHeight'          => $hoverBtnHeight,
				'useOldHover'             => $useOldHover,
				'hoverIgnoreClasses'      => $hoverIgnoreClasses,
				'showZeroCount'           => $showZeroCount,

				// Sharebar options
				'sharebarEnabled'        => $sharebarEnabled,
				'enabledSharebarButtons' => $enabledSharebarButtons,
				'appId'                  => $sharebarFbAppId,

				// Post meta
				'pmOverride'             => $pmOverride,
				'pmDescription'          => $pmDescription,

				// pinit.js tracker
				'disablePinitJS'        => $disablePinitJS,

				// Other plugins
				'otherPlugins'          => $otherPlugins
			)
		);
	}
	
	return $return;
}

/**
 * Return a correctly checked and encoded string for UTM Variables to be added to pins
 *
 * @since   3.1.3
 *
 * @return  string
 */
function pib_clean_and_encode_utm( $url, $utm_string ) {
	
	$begin = '';
	
	if( strrpos( $url, '?' ) === false ) {
		$begin = '?';
	} else {
		$begin = '&';
	}
	
	// Check UTM string for '?' and '&'
	if( strrpos( $utm_string, '?' ) !== false ) {
		if( $utm_string{0} == '?' ) {
			$utm_string = substr( $utm_string, 1 );
		}
	}
	
	// Check only first character for '&'
	if( $utm_string{0} == '&' ) {
		$utm_string = substr( $utm_string, 1 );
	}
	
	return rawurlencode( $begin . $utm_string );
}

function pib_other_plugins() {
	return class_exists( 'Pinterest_Widgets' ) || class_exists( 'Top_Pinned_Posts' );
}
