<?php

/**
 * Represents the view for the public-facing component of the plugin.
 *
 * @package    PIB Pro
 * @subpackage Views
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Add filter/hooks based on if user wants data-pin-* attributes added
 * 
 * @since 3.0.0
 * 
 */
function pib_add_data_pin_filters() {
	global $pib_options;
	
	if( empty( $pib_options['disable_image_data_pin_attrs'] ) ) {
		
		//data-pin-media attributes
		// First we check if it is nextGen and add filters accordingly
		if( ! pib_is_nextgen_active() ) {
			add_filter( 'the_content', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER - 1 );
			add_filter( 'the_excerpt', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER - 1 );
		} else {
			// NextGEN is active so we need to check the option before setting any filters
			
			if( ! empty( $pib_options['nextgen_thumbs'] ) ) {
				// NextGen: Use PIB_DEFAULT_FILTER constant since NG uses (PIB_DEFAULT_FILTER - 1) frequently.
				add_filter( 'the_content', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER );
				add_filter( 'the_excerpt', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER );
				
				// remove the filter for below image buttons since we are disabling it to allow for data-pin-* attributes to be added correctly
				remove_filter( 'the_content', 'pib_add_below_image_buttons', PIB_DEFAULT_FILTER );
			} else {
				add_filter( 'the_content', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER - 1 );
				add_filter( 'the_excerpt', 'pib_add_data_pin_media_url_attributes', PIB_DEFAULT_FILTER - 1 );
			}
			
		}
		
		// data-pin-url for featured images
		add_filter( 'post_thumbnail_html', 'pib_add_data_pin_url_attribute_featured_images', 100, 2 );
		// Genesis specific
		add_filter( 'genesis_get_image', 'pib_add_data_pin_url_attribute_featured_images', 100, 2 );
		
	}
}
add_action( 'init', 'pib_add_data_pin_filters' );

/*
 * Add custom below image CSS to work in conjunction with new PHP below image buttons
 * 
 * @since 3.1.1
 * 
 */
function pib_add_below_image_custom_css() {
	global $pib_options;
	
	if( ! in_array( 'no_buttons', pib_render_button() ) ) {
		$custom_button        = ( ! empty( $pib_options['below_img_btn_url'] ) ? $pib_options['below_img_btn_url'] : null );
		$custom_button_width  = ( ! empty( $pib_options['below_img_custom_btn_width'] ) ? $pib_options['below_img_custom_btn_width'] : 58 );
		$custom_button_height = ( ! empty( $pib_options['below_img_custom_btn_height'] ) ? $pib_options['below_img_custom_btn_height'] : 27 );
		
		// Set our CSS now, but decide later if we need to output it or not
		$custom_below = '<style type="text/css">' .
							'.pib-img-under-wrapper a[href*="pinterest"], a.pib-img-under { ' .
							'background-image: url(' . $custom_button . ') !important; '.
							'width: ' .  $custom_button_width . 'px !important; ' .
							'height: ' . $custom_button_height . 'px !important; ' .
							'background-position: 0 0 !important; background-size: auto !important;' .
							'</style>';
		
		if( ! empty( $pib_options['use_below_img_btn'] ) && ! empty( $pib_options['use_custom_below_img_btn'] ) ) {
			echo $custom_below;
		}
	}
}
add_action( 'wp_head', 'pib_add_below_image_custom_css' );

/*
 * Add custom below image CSS to work with shortcodes
 * 
 * @since 3.1.3
 * 
 */
function pib_add_shortcode_image_custom_css() {
	global $pib_options;
	
	if( in_array( 'shortcode', pib_render_button() ) ) {
	
		$custom_button = ( ! empty( $pib_options['custom_btn_img_url'] ) ? $pib_options['custom_btn_img_url'] : '' );
		$custom_width  = ( ! empty( $pib_options['custom_btn_width'] ) ? $pib_options['custom_btn_width'] : 32 );
		$custom_height = ( ! empty( $pib_options['custom_btn_height'] ) ? $pib_options['custom_btn_height'] : 32 );


		if( ! empty( $pib_options['use_custom_img_btn'] ) ) {
			$custom_shortcode_image  = '<style type="text/css">' .
										'.pin-it-btn-wrapper-shortcode a[href*="pinterest"] { ' .
										'background-image: url(' . $custom_button . ') !important; '.
										'width: ' .  $custom_width . 'px !important; ' .
										'height: ' . $custom_height . 'px !important; ' .
										'background-position: 0 0 !important; background-size: auto !important;' .
										'</style>';

			echo $custom_shortcode_image;
		}
	}
}
add_action( 'wp_head', 'pib_add_shortcode_image_custom_css' );

/**
 * Add below image buttons
 *
 * @since    3.1.1
 *
 * @return   string
 */
function pib_add_below_image_buttons( $content ) {
	global $post, $pib_options;
	
	if( empty( $pib_options['use_below_img_btn'] ) || in_array( 'no_buttons', pib_render_button() ) )
		return $content;
	
	$postID = $post->ID;
	
	$override_below_description = get_post_meta( $post->ID, 'pib_override_below_description', true );
	$post_meta_description      = get_post_meta( $post->ID, 'pib_description', true );
	
	$html = new simple_html_dom();
	
	$html->load( $content, true, false );
	
	$img = $html->find( 'img' );
	
	//Skip adding hover effect if image contains certain classes
	$skipTheseClasses = 'pib-hover-img,pin-it-btn-custom-img';

	//Add user-added CSS classes to skip hovering on
	if ( ! empty( $pib_options['below_img_ignore_classes'] ) ) {
		$skipTheseClasses .= ',' . $pib_options['below_img_ignore_classes'];
	}

	//Convert comma delimiters to pipes for now to work with current regex
	$skipTheseClasses = str_replace( ',', '|', $skipTheseClasses );
	$skipTheseClasses = str_replace( ' ', '', $skipTheseClasses );

	// options that aren't dependant on the current image
	$count_layout    = 'image_selected';
	$size            = ( ! empty( $pib_options['data_pin_size_below'] ) ? $pib_options['data_pin_size_below'] : '' );
	$color           = ( ! empty( $pib_options['data_pin_color_below'] ) ? $pib_options['data_pin_color_below'] : '' );
	$shape           = ( ! empty( $pib_options['data_pin_shape_below'] ) ? $pib_options['data_pin_shape_below'] : '' );
	$show_zero_count = '';
	
	$min_width  = ( ! empty( $pib_options['below_img_min_width'] ) ? $pib_options['below_img_min_width'] : 200 );
	$min_height = ( ! empty( $pib_options['below_img_min_height'] ) ? $pib_options['below_img_min_height'] : 200 );
	
	foreach( $img as $i ) {
		
		// Set variables for out button
		$post_url        = get_permalink( $postID );
		$description     = ( ! empty( $override_below_description ) ? $post_meta_description : $i->alt );
		
		$img_src = $i->src;
		
		$class = $i->getAttribute( 'class' );
		
		if ( preg_match( '/' . $skipTheseClasses . '/i', $class ) || preg_match( '/' . $skipTheseClasses . '/i', $i->parent()->getAttribute( 'class' ) ) ) { 
			continue;
		}
		
		// If the parent element of the image is an <a> tag then we will add a data-pin-media attribute
		// with the value of the parent's href attribute
		$imgParent = $i->parent();

		if( $imgParent->tag == 'a' ) {
			// We want to make sure the parent <a> tag is linking to an image so we get its filetype
			$href = wp_check_filetype( $imgParent->href );

			// Only show if the filetype being linked to comes back as any "image" MIME type
			// Should work with NextGen galleries also.
			if( strpos( $href['type'], 'image' ) !== false ) {
				//$i->setAttribute( 'data-pin-media', $imgParent->href );
				$img_src = $imgParent->href;
			}
		}
		
		if( strpos( $img_src, '//assets.pinterest') === false ) {
			
			list( $img_width, $img_height ) = @getimagesize( $img_src );
		}
		
		$is_pib = $i->getAttribute( 'data-pib-button' );
		
		// If the class to enable doesn't exist then let's not add to this image
		// Also if the image is a pib button then don't add to this image
		if( strpos( $class, 'enable-pib-img-under' ) !==  false || ( ! empty( $is_pib ) ) ) {
			continue;
		}
		
		
		
		// This is the work around for simple html dom to add elements
		// We add the image to the outer text of the parent container
		// Will need to test this if the image doesn't have a parent and see what happens
		//$button = '<div class="pib-img-under-wrapper">' . pib_button_base( $count_layout, $post_url, $i->src, $description, $count_layout, $size, $color, $shape, $show_zero_count ) . '</div>';
		$button_html = pib_button_base( $count_layout, $post_url, $img_src, $description, $count_layout, $size, $color, $shape, $show_zero_count );
		
		// Check minimum size requirements
		if( ( $img_width >= $min_width ) && ( $img_height >= $min_height ) ) {
			
			$before_html = '';
			$after_html = '';
			//$button_html = '<div class="pib-img-under-wrapper ' . $class . '">' . $i->outertext . '<br />' . $button . '</div>';
			
			$before_html = apply_filters( 'pib_below_image_button_before', $before_html );
			$button_html = apply_filters( 'pib_below_image_button_html', $button_html );
			$after_html  = apply_filters( 'pib_below_image_button_after', $after_html );
			
			$i->outertext = '<div class="pib-img-under-wrapper ' . $class . '">' . $i->outertext . '</a><br />' . $before_html . $button_html . $after_html . '</div>';
		}
	}
	
	$content = $html->save();
	
	return $content;
}
add_filter( 'the_content', 'pib_add_below_image_buttons', PIB_DEFAULT_FILTER );

/**
 * Combine add_data_pin_media_attribute + add_data_pin_url_attribute.
 * Special checks added for NextGen gallery compatibility.
 *
 * @since    3.0.0
 *
 * @return   string
 */
function pib_add_data_pin_media_url_attributes( $content ) {
	global $post, $pib_options;
	$postID = $post->ID;
	
	$utm = '';
	$utm_meta = get_post_meta( $post->ID, 'pib_utm_meta', true );
	
	if( ! empty( $utm_meta ) ) {
		$utm = pib_clean_and_encode_utm( get_permalink( $post->ID ), $utm_meta );
	} else if( ! empty( $pib_options['utm_string'] ) ) {
		$utm = pib_clean_and_encode_utm( get_permalink( $post->ID ), $pib_options['utm_string'] );
	}

	if ( ! $post )
		return false;

	// Create a new Simple HTML DOM object
	$html = new simple_html_dom();

	// Load the content of the page into our object
	$html->load( $content, true, false );

	// Find all of the images
	// NextGen: This didn't work for ignoring NG container:
	// $img = $html->find('img[class=!ngg_displayed_gallery]');
	$img = $html->find('img');

	// Run a Loop through each of the images found
	foreach( $img as $i ) {

		// Skip over pin it buttons themselves, which should contain attribute data-pib-button="true".
		if( $i->hasAttribute( 'data-pib-button' ) ) {
			continue;
		}

		// Get string that contains all the image's classes
		$imgClasses = $i->getAttribute( 'class' );

		// NextGen: Don't modify the NG gallery "base" image tag (class "ngg_displayed_gallery").
		// It's needed intact for NextGen gallery rendering after this is processed.
		if( strpos( $imgClasses, 'ngg_displayed_gallery' ) !==  false ) {
			continue;
		}

		// Set the data-pin-url attribute to the original post
		$i->setAttribute( 'data-pin-url', get_permalink( $postID ) . ( ! empty( $utm ) ? $utm : '' ) );

		// If the parent element of the image is an <a> tag then we will add a data-pin-media attribute
		// with the value of the parent's href attribute
		$imgParent = $i->parent();

		if( $imgParent->tag == 'a' ) {
			// We want to make sure the parent <a> tag is linking to an image so we get its filetype
			$href = wp_check_filetype( $imgParent->href );

			// Only show if the filetype being linked to comes back as any "image" MIME type
			// Should work with NextGen galleries also.
			if( strpos( $href['type'], 'image' ) !== false ) {
				$i->setAttribute( 'data-pin-media', $imgParent->href );
			}
		}
	}

	// We need to save the changes and set to a string that we can return
	$content = $html->save();

	// Return the new content string
	return $content;
}

/**
 * Add data-pin-url attribute to featured images.
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_add_data_pin_url_attribute_featured_images( $html, $post_id ) {
	
	$pattern = '/<img ([^>]*?)\/?\s*>/i';

	// Old regex pattern: Left an image tag ending slash before some attributes in featured images, messing up RSS feeds.
	// $pattern = "/<img([^>]*?)>/i";

	$replacement = '<img $1 data-pin-url="' . get_permalink( $post_id ) . '" />';
	
	return preg_replace($pattern, $replacement, $html);
}

/**
 * Need to get pin count through unofficial Pinterest API in some cases.
 * May not need to get pin counts on the server for now, but probably will at some point, so leave in here.
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_get_remote_pin_count( $post_url ) {
	// URL encode post url.
	$post_url = 'https://widgets.pinterest.com/v1/urls/count.json?url=' . rawurlencode( $post_url );

	/*
	 * If this needs more parameters to work lookup this in pinit.js:

	   getPinCount: function (url) {
          var query = '?url=' + url + '&ref=' + encodeURIComponent($.v.here) + '&source=' + $.a.countSource;
          $.f.call($.a.endpoint.count + query, $.f.ping.count);
        },
	 */

	// Old API endpoint used in Top Pinned Posts 1.0:
	// http://api.pinterest.com/v1/urls/count.json?url=

	// wp_remote_fopen is basically wp_safe_remote_get() + wp_remote_retrieve_body()
	// http://wpseek.com/wp_remote_fopen/
	// http://codex.wordpress.org/Function_Reference/wp_remote_fopen
	$response = wp_remote_fopen( $post_url );

	// Make sure the response was succesful or return null.
	if( empty( $response ) )
		return null;

	// Response will be in JSON-P, so run through decoding function and return count property.
	$json = pib_jsonp_decode( $response, true );
	return $json['count'];
}

/**
 * Function to decode JSON-P by simply trimming the wrapping JavaScript.
 *
 * @since   3.0.0
 *
 * @var     string
 */
function pib_jsonp_decode( $jsonp, $assoc = false ) {
	// PHP 5.3 adds depth as third parameter to json_decode.
	if( $jsonp[0] !== '[' && $jsonp[0] !== '{' ) { // we have JSONP
		$jsonp = substr( $jsonp, strpos( $jsonp, '(' ) );
	}
	return json_decode( trim($jsonp, '();' ), $assoc );
}

/**
 * CSS needed for hover button effects.
 *
 * @since   3.0.0
 *
 */
function pib_add_hover_button_css() {
	global $pib_options;
	$custom_css = '';
	
	if( ! in_array( 'no_buttons', pib_render_button() ) ) {

		if( ! empty( $pib_options['use_img_hover_btn'] ) ) {
			$hover_btn_img_url    = ( ! empty( $pib_options['hover_btn_img_url'] )    ? $pib_options['hover_btn_img_url']    : '' );
			$hover_btn_img_width  = ( ! empty( $pib_options['hover_btn_img_width'] )  ? $pib_options['hover_btn_img_width']  : 58 );
			$hover_btn_img_height = ( ! empty( $pib_options['hover_btn_img_height'] ) ? $pib_options['hover_btn_img_height'] : 27 );

			//Set background image and width/height here
			$custom_css .= "\n" .
				'a.pib-hover-btn-link, .pib-img-under-wrapper a[href*="pinterest"].pib-hover-btn-link {' . "\n" .
				'    background-image: url("' . $hover_btn_img_url . '") !important;' . "\n" .
				'    width: ' . $hover_btn_img_width . 'px !important;' . "\n" .
				'    height: ' . $hover_btn_img_height . 'px !important;' . "\n" .
				'}';
		}

		//Add custom sharebar button width
		$sharebar_btn_width = ( ! empty( $pib_options['sharebar_btn_width'] ) ? $pib_options['sharebar_btn_width'] : '105' );
		$custom_css .= "\n" . '.pib-sharebar li { width: ' . $sharebar_btn_width . 'px; }';

		// Get height of custom button if it is being used and set all list elements to that height so that the responsiveness works
		if( ! empty( $pib_options['use_custom_img_btn'] ) ) {
			$custom_css .= "\n" . '.pib-sharebar li { height: ' . ( ! empty( $pib_options['custom_btn_height'] ) ? $pib_options['custom_btn_height'] : '26' ) . 'px; }';
		}

		if( ! empty( $custom_css ) ) {
			echo '<style type="text/css">' . $custom_css . "\n" . '</style>';
		}
	}
}
// set higher priority so that our Custom CSS will be added after this
add_action( 'wp_head', 'pib_add_hover_button_css', 8 );

/**
 * Share Bar HTML to render
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_sharebar_html_pro( $pib_btn_html = '' ) {
    $options = get_option( 'pib_sharebar_buttons' );
	global $post, $pib_options;
	
	//Check for sharing disabled on individual post meta
	if ( get_post_meta( $post->ID, 'pib_sharing_disabled', 1 ) ) {
		return '';
	}
	
	$align = ( ! empty( $pib_options['sharebar_align'] ) ? ( ( $pib_options['sharebar_align'] == 'none' ) ? '' : 'pib-align-' . $pib_options['sharebar_align'] ) : '' );
	
	// Add our align class
	$html = '<div class="pib-sharebar ' . $align . ' pib-clearfix"><ul>';
       
	if( ! empty( $options['button_order'] ) ) {
		foreach( $options['button_order'] as $button) {
			$html .=  pib_sharebar_item_html( $button, $pib_btn_html );
		}
	}
	$html .= '</ul></div>';
	
	
	$before_html = '';
	$after_html  = '';
	
	$before_html = apply_filters( 'pib_sharebar_before', $before_html );
	$html        = apply_filters( 'pib_sharebar_html', $html );
	$after_html  = apply_filters( 'pib_sharebar_after', $after_html );
	
    return $before_html . $html . $after_html;
}

/**
 * New Facebook "Like" button - 11/11/13
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_share_facebook_like( $postID, $sharebar_hide_count ) {

	// Add extra CSS class to hide count bubble if needed.
	return sprintf( '<div id="fb-root"></div><div data-href="%s" class="fb-like %s" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>',
		get_permalink( $postID ),
		( $sharebar_hide_count ? 'pib-facebook-like-no-count' : '' )
	);
}

/**
 * New Facebook "Share" button - 11/11/13
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_facebook_share( $postID, $sharebar_hide_count ) {
	
	return sprintf( '<div class="fb-share-button" data-href="%s" data-type="%s"></div>',
		get_permalink( $postID ),
		( $sharebar_hide_count ? 'button' : 'button_count' )
	);
}

/**
 * Render Twitter button
 * https://twitter.com/about/resources/buttons
 * https://dev.twitter.com/docs/tweet-button (scroll down for iframe button version)
 * Includes via @username option
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_share_twitter( $postID, $sharebar_hide_count, $sharebar_twitter_via, $sharebar_twitter_hashtags ) {
	global $pib_options;
	
    return sprintf( '<a href="https://twitter.com/share" class="twitter-share-button" data-url="%s" data-text="%s" %s %s %s>%s</a>',
        get_permalink( $postID ),
	    get_the_title( $postID ),
		( $sharebar_hide_count ? 'data-count="none"' : '' ),
		( ! empty( $sharebar_twitter_via ) ? 'data-via="' . $sharebar_twitter_via . '"' : '' ),
		( ! empty( $sharebar_twitter_hashtags ) ? 'data-hashtags="' . $sharebar_twitter_hashtags . '"' : '' ),
		( ! empty( $pib_options['sharebar_no_tweet'] ) ? '' : 'Tweet' )
	);
}

/**
 * Render Google +1 button
 * https://developers.google.com/+/plugins/+1button/
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_share_gplus( $postID, $sharebar_hide_count ) {
    return sprintf( '<div class="g-plusone" data-size="medium" data-href="%s" %s></div>',
        get_permalink( $postID ),
	    ( $sharebar_hide_count ? 'data-annotation="none"' : '' )
    );
}

/**
 * Google+ Share button
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_share_gplus_share( $postID, $sharebar_hide_count ) {
	return sprintf( '<div class="g-plus" data-href="%s" data-action="share" data-height="20" data-annotation="%s"></div>',
		get_permalink( $postID ),
		( $sharebar_hide_count ? 'none' : 'bubble' )
	);
}

/**
 * Render LinkedIn share button
 * https://developer.linkedin.com/plugins/share-plugin-generator
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_share_linkedin( $postID, $sharebar_hide_count ) {
    return sprintf( '<script type="IN/Share" data-url="%s" %s></script>',
        get_permalink( $postID ),
	    ( $sharebar_hide_count ? '' : 'data-counter="right"' )
    );
}

/**
 * Add CSS class pib-hover-img for use with jQuery hover pin it button later
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_add_image_hover_css( $content ) {
	global $pib_options, $post;
	$postID = $post->ID;
	
	if ( ! $post ) {
		return false;
	}

	// If hover option isn't turned on, return content without modification.
	if( empty( $pib_options['use_img_hover_btn'] ) )
		return $content;
	
	//First determine if we should add hover capabilities to this page
	//Set vars for easier reading
	$pib_display_home_page  = ( ! empty( $pib_options['post_page_types']['display_home_page'] ) );
	$pib_display_front_page = ( ! empty( $pib_options['post_page_types']['display_front_page'] ) );
	$pib_display_posts      = ( ! empty( $pib_options['post_page_types']['display_posts'] ) );
	$pib_display_pages      = ( ! empty( $pib_options['post_page_types']['display_pages'] ) );
	$pib_display_archives   = ( ! empty( $pib_options['post_page_types']['display_archives'] ) );

	//Show on custom post type individual posts/pages
	$pib_display_custom_post_type = ( ! empty( $pib_options['custom_post_types']['display_post_type_' . get_post_type( $postID )] ) );

	//Ignore custom post types if we're on built-in post type (post, page, etc)
	$pib_built_in_post_type = get_post_type_object( get_post_type( $postID ) )->_builtin;

	$pib_render_buttons = false;

	//Determine if button displayed on current page from main admin settings
	if (
		( is_home() && $pib_display_home_page ) ||
		( is_front_page() && $pib_display_front_page ) ||
		( is_single() && $pib_display_posts && $pib_built_in_post_type ) ||
		( is_page() && $pib_display_pages && $pib_built_in_post_type && !is_front_page() ) ||
		( is_archive() && $pib_display_archives )
	) {
		$pib_render_buttons = true;
	}

	//Check for custom post type
	//For now ignoring archive pages
	if ( !$pib_built_in_post_type && ( is_single() || is_page() ) ) {
		$pib_render_buttons = $pib_display_custom_post_type;
	}

	//Check for sharing disabled on individual post meta
	if ( get_post_meta( $post->ID, 'pib_sharing_disabled', 1 ) ) {
		$pib_render_buttons = false;
	}

	//Exit here if we shouldn't render
	if ( ! $pib_render_buttons ) {
		return $content;
	}

	// Image tag matching regext pattern: matches everything between "<img" and the closing "(/)>"
	$imgPattern = "/<img([^\>]*?)>/i";

	if ( preg_match_all( $imgPattern, $content, $imgTags )) {
		foreach ( $imgTags[0] as $imgTag ) {

			//Skip adding hover effect if image contains certain classes
			$skipTheseClasses = 'pib-hover-img,pin-it-btn-custom-img,pib-nohover';

			//Add user-added CSS classes to skip hovering on
			if ( ! empty( $pib_options['hover_btn_ignore_classes'] ) ) {
				$skipTheseClasses .= ',' . $pib_options['hover_btn_ignore_classes'];
			}

			//Convert comma delimiters to pipes for now to work with current regex
			$skipTheseClasses = str_replace( ',', '|', $skipTheseClasses );

			if ( ! preg_match( '/' . $skipTheseClasses . '/i', $imgTag ) ) {

				// The ignored classes shouldn't have pib-hover-img class added to them.
				if ( ! preg_match( '/class=/i', $imgTag ) ) {
					//image is good to go -- add the hover class
					$pattern = $imgPattern;
					$replacement = '<img class="pib-hover-img" $1>';
				}
				else {
					//image matches an class to skip, so don't add hover
					$pattern     = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
					$replacement = '<img$1class=$2$3 pib-hover-img$4$5>';
				}

				$replacedImgTag = preg_replace( $pattern, $replacement, $imgTag );
				$content        = str_replace( $imgTag, $replacedImgTag, $content );
			}
		}
	}
	
    return $content;
}

//Add CSS class to all images whether regular content or excerpt being rendered
//Priority 100 to make sure it runs, hopefully the preg_replace is then executed after other plugins messed with the_content
add_filter( 'the_content', 'pib_add_image_hover_css', PIB_DEFAULT_FILTER );
add_filter( 'the_excerpt', 'pib_add_image_hover_css', PIB_DEFAULT_FILTER );

//Add to featured images/post thumbnails
add_filter( 'post_thumbnail_html', 'pib_add_image_hover_css', 100 );

/**
 * Add CSS class pib-hover-img for use with jQuery hover pin it button later
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_add_below_image_class( $content ) {
	global $pib_options, $post;
	$postID = $post->ID;
	
	if ( ! $post ) {
		return false;
	}

	// If hover option isn't turned on, return content without modification.
	if( empty( $pib_options['use_below_img_btn'] ) )
		return $content;
	
	//First determine if we should add hover capabilities to this page
	//Set vars for easier reading
	$pib_display_home_page  = ( ! empty( $pib_options['post_page_types']['display_home_page'] ) );
	$pib_display_front_page = ( ! empty( $pib_options['post_page_types']['display_front_page'] ) );
	$pib_display_posts      = ( ! empty( $pib_options['post_page_types']['display_posts'] ) );
	$pib_display_pages      = ( ! empty( $pib_options['post_page_types']['display_pages'] ) );
	$pib_display_archives   = ( ! empty( $pib_options['post_page_types']['display_archives'] ) );

	//Show on custom post type individual posts/pages
	$pib_display_custom_post_type = ( ! empty( $pib_options['custom_post_types']['display_post_type_' . get_post_type( $postID )] ) );

	//Ignore custom post types if we're on built-in post type (post, page, etc)
	$pib_built_in_post_type = get_post_type_object( get_post_type( $postID ) )->_builtin;

	$pib_render_buttons = false;

	//Determine if button displayed on current page from main admin settings
	if (
		( is_home() && $pib_display_home_page ) ||
		( is_front_page() && $pib_display_front_page ) ||
		( is_single() && $pib_display_posts && $pib_built_in_post_type ) ||
		( is_page() && $pib_display_pages && $pib_built_in_post_type && !is_front_page() ) ||
		( is_archive() && $pib_display_archives )
	) {
		$pib_render_buttons = true;
	}

	//Check for custom post type
	//For now ignoring archive pages
	if ( !$pib_built_in_post_type && ( is_single() || is_page() ) ) {
		$pib_render_buttons = $pib_display_custom_post_type;
	}

	//Check for sharing disabled on individual post meta
	if ( get_post_meta( $post->ID, 'pib_sharing_disabled', 1 ) ) {
		$pib_render_buttons = false;
	}

	//Exit here if we shouldn't render
	if ( ! $pib_render_buttons ) {
		return $content;
	}

	// Image tag matching regext pattern: matches everything between "<img" and the closing "(/)>"
	$imgPattern = "/<img([^\>]*?)>/i";

	if ( preg_match_all( $imgPattern, $content, $imgTags )) {
		foreach ( $imgTags[0] as $imgTag ) {

			//Skip adding hover effect if image contains certain classes
			$skipTheseClasses = 'pin-it-btn-custom-img,enable-pib-img-under,disable-pib-img-under';

			//Add user-added CSS classes to skip hovering on
			if ( ! empty( $pib_options['below_img_ignore_classes'] ) ) {
				$skipTheseClasses .= ',' . $pib_options['below_img_ignore_classes'];
			}

			//Convert comma delimiters to pipes for now to work with current regex
			$skipTheseClasses = str_replace( ',', '|', $skipTheseClasses );

			if ( ! preg_match( '/' . $skipTheseClasses . '/i', $imgTag ) ) {

				// The ignored classes shouldn't have pib-hover-img class added to them.
				if ( ! preg_match( '/class=/i', $imgTag ) ) {
					//image is good to go -- add the hover class
					$pattern = $imgPattern;
					$replacement = '<img class="enable-pib-img-under" $1>';
				}
				else {
					//image matches an class to skip, so don't add hover
					$pattern     = "/<img(.*?)class=('|\")([A-Za-z0-9 \/_\.\~\:-]*?)('|\")([^\>]*?)>/i";
					$replacement = '<img$1class=$2$3 enable-pib-img-under$4$5>';
				}

				$replacedImgTag = preg_replace( $pattern, $replacement, $imgTag );
				$content        = str_replace( $imgTag, $replacedImgTag, $content );
			}
		}
	}
	
    return $content;
}
//Add below image button CSS class to all images whether regular content or excerpt being rendered
//Priority 100 to make sure it runs, hopefully the preg_replace is then executed after other plugins messed with the_content
add_filter( 'the_content', 'pib_add_below_image_class', PIB_DEFAULT_FILTER );
add_filter( 'the_excerpt', 'pib_add_below_image_class', PIB_DEFAULT_FILTER );

//Add to featured images/post thumbnails
add_filter( 'post_thumbnail_html', 'pib_add_below_image_class', 100 );

/**
 * Render button HTML on pages with regular content.
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_render_content_pro( $content ) {
	global $pib_options;
	global $post;
	
	// If WooCommerce is active and Product custom post type is not checked then do not show the button
	if( 'product' == $post->post_type ) {
		if( pib_is_woo_commerce_active() ) {
			if( ! isset( $pib_options['custom_post_types']['display_post_type_product'] ) ) 
				return $content;
		}
	}

	//Determine if button displayed on current page from main admin settings
	if ( in_array( 'button', pib_render_button() ) ) {
		
	   if ( ! empty( $pib_options['post_page_placement']['display_above_content'] ) ) {
		   if( ! empty( $pib_options['use_other_sharing_buttons'] ) ) {
				$content = pib_sharebar_html_pro() . $content;
		   } else {
			   $content = pib_button_html( pib_featured_image() ) . $content;
		   }
	   }
	   if ( ! empty( $pib_options['post_page_placement']['display_below_content'] ) ) {
		  if( ! empty( $pib_options['use_other_sharing_buttons'] ) ) {
				$content .= pib_sharebar_html_pro();
		   } else {
			   $content .= pib_button_html( pib_featured_image() );
		   }
	   }
	}

	return $content;
}
add_filter( 'the_content', 'pib_render_content_pro', PIB_DEFAULT_FILTER );

/**
 * Render button HTML on pages with excerpts if option checked.
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_render_content_excerpt_pro( $content ) {
	global $pib_options;
	global $post;
	
	if( ! is_main_query() )
		return $content;
	
	if ( ! empty( $pib_options['post_page_placement']['display_on_post_excerpts'] ) ) {
	   if (
		  ( is_home() && ( ! empty( $pib_options['post_page_types']['display_home_page'] ) ) ) ||
		  ( is_front_page() && ( ! empty( $pib_options['post_page_types']['display_front_page'] ) ) )
		 ) {
		   if ( ! empty( $pib_options['post_page_placement']['display_above_content'] ) ) {
				if( ! empty( $pib_options['use_other_sharing_buttons'] ) ) {
					 $content = pib_sharebar_html_pro() . $content;
				} else {
					$content = pib_button_html( pib_featured_image() ) . $content;
				}
			}
			if ( ! empty( $pib_options['post_page_placement']['display_below_content'] ) ) {
				if( ! empty( $pib_options['use_other_sharing_buttons'] ) ) {
					  $content .= pib_sharebar_html_pro();
				 } else {
					 $content .= pib_button_html( pib_featured_image() );
				 }
			}
	   }

	}

	return $content;
}
add_filter( 'the_excerpt', 'pib_render_content_excerpt_pro', PIB_DEFAULT_FILTER );


/**
 * WooCommerce - Add before Product Short Description
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_add_before_wc_sd( $content ) {
	global $pib_options;

	if( ! empty( $pib_options['pib_woocommerce']['display_above_summary'] ) ) {
		$content = ( empty( $pib_options['use_other_sharing_buttons'] ) ? pib_button_html( pib_featured_image() ) : pib_sharebar_html_pro() ) . $content;
	}

	return do_shortcode( $content );
}
add_filter( 'woocommerce_short_description', 'pib_add_before_wc_sd' );

/**
 * WooCommerce - Add after Product Short Description
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_add_after_wc_sd( $content ) {
	global $pib_options;

	if( ! empty( $pib_options['pib_woocommerce']['display_below_summary'] ) ) {
		$content .= ( empty( $pib_options['use_other_sharing_buttons'] ) ? pib_button_html( pib_featured_image() ) : pib_sharebar_html_pro() );
	}

	return do_shortcode( $content );
}
add_filter( 'woocommerce_short_description', 'pib_add_after_wc_sd' );

/**
 * WooCommerce - Add before the Cart Button
 *
 * @since   3.0.0
 *
 */
function pib_add_before_wc_cart() {
	global $pib_options;

	if( ! empty( $pib_options['pib_woocommerce']['display_above_cart_button'] ) ) {
		echo ( empty( $pib_options['use_other_sharing_buttons'] ) ? pib_button_html( pib_featured_image() ) : pib_sharebar_html_pro() );
	}
}
add_action( 'woocommerce_before_add_to_cart_button', 'pib_add_before_wc_cart' );

/**
 * WooCommerce - Add after the Cart Button
 *
 * @since   3.0.0
 *
 */
function pib_add_after_wc_cart() {
	global $pib_options;

	if( ! empty( $pib_options['pib_woocommerce']['display_below_cart_button'] ) ) {
		echo ( empty( $pib_options['use_other_sharing_buttons'] ) ? pib_button_html( pib_featured_image() ) : pib_sharebar_html_pro() );
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'pib_add_after_wc_cart' );
