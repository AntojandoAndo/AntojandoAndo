<?php
define( 'WPTOUCH_RESPONSIVE_IMAGES_VERSION', '1.2.1' );
define( 'WPTOUCH_RESPONSIVE_IMAGES_PAGENAME', 'Responsive Images' );
define( 'WPTOUCH_EXTENSION_RESPONSIVE_INSTALLED', '1' );
define( 'WPTOUCH_RESPONSIVE_CACHE_KEY_PREFIX', '1' );
define( 'WPTOUCH_RESPONSIVE_IMAGE_DIRECTORY', WPTOUCH_CACHE_DIRECTORY . '/responsive-images' );

add_filter( 'wptouch_addon_options', 'wptouch_media_addon_options' );
add_filter( 'the_content', 'wptouch_media_content_filter', 99 );
add_action( 'wptouch_addon_admin_init', 'wptouch_media_check_directories' );
add_action( 'init', 'wptouch_media_add_js' );

add_action( 'wp_ajax_wptouch_media_ajax', 'wptouch_media_handle_ajax' );
add_action( 'wp_ajax_nopriv_wptouch_media_ajax', 'wptouch_media_handle_ajax' );

add_filter( 'wptouch_setting_defaults_addons', 'wptouch_media_settings_defaults' );

function wptouch_media_settings_defaults( $settings ) {
	$settings->media_optimize_on_desktop = true;
	$settings->media_optimize_preference = 'speed';

	return $settings;
}

function wptouch_media_get_jpeg_quality() {
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	if ( $settings->media_optimize_preference == 'speed' ) {
		return 60;
	} else {
		return 85;
	}
}

function wptouch_media_handle_ajax() {
	global $wptouch_pro;

	if ( isset( $wptouch_pro->post[ 'wptouch_media_action' ] ) ) {
		$nonce = $wptouch_pro->post[ 'wptouch_media_nonce' ];

		if ( wp_verify_nonce( $nonce, 'wptouch-responsive' ) ) {
			$orientation = $wptouch_pro->post['device_orientation'];

			switch( $orientation ) {
				case 0:
					$max_device_dimension = max( $wptouch_pro->post[ 'device_width' ], $wptouch_pro->post[ 'device_height' ] );
					break;
				case 1: // landscape
					$max_device_dimension = $wptouch_pro->post[ 'device_height' ];
					break;
				case 2: // portrait
					$max_device_dimension = $wptouch_pro->post[ 'device_width' ];
					break;
			}

			$localized_media_url = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $wptouch_pro->post[ 'media_url' ] );

			if ( !strstr( $localized_media_url, 'http' ) && is_writable( $localized_media_url ) ) {
				$media_size = floor( filesize( $localized_media_url ) / 1000 ) . 'kb';
			} else {
				$media_size = 'remote file or file is not writable - cannot stat';
			}

			WPTOUCH_DEBUG( WPTOUCH_INFO, 'Trying to load image on [' . $wptouch_pro->post[ 'device_width' ] . 'x' . $wptouch_pro->post[ 'device_height' ] . '] URL [' . $wptouch_pro->post[ 'media_url' ] . '], original size [' . $media_size . '], orientation is ' . $orientation . ', constraining to ' . $max_device_dimension . 'px' );

			$serve_cached_file = false;

			if ( !strstr( $localized_media_url, 'http' ) ) {
				$cached_file_name = false;
				if ( wptouch_media_cached_photo_exists( $localized_media_url, $max_device_dimension, $cached_file_name ) ) {
					// We have a cached photo for this device, so serve that
					$serve_cached_file = true;
				} else {
					// No cached photo exists
					$image_file_name = false;
					$aspect_ratio = 1;
					if ( wptouch_media_should_resize_photo( $localized_media_url, $max_device_dimension, $image_file_name, $aspect_ratio ) ) {
						$cached_file_name = wptouch_media_get_cached_image_file_name( $localized_media_url, $max_device_dimension );

						$serve_cached_file = wptouch_media_resize_photo( $image_file_name, $cached_file_name, $aspect_ratio, $max_device_dimension );
					}
				}
			}

			// Serve the cached file, AJAX or non-AJAX
			if ( $serve_cached_file ) {
				WPTOUCH_DEBUG( WPTOUCH_INFO, 'Serving cached media file [' . str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $cached_file_name ) . '], size [' . floor ( filesize( $cached_file_name ) / 1000 ) . 'kb]' );
				echo str_replace( WP_CONTENT_DIR, WP_CONTENT_URL, $cached_file_name );
			} else {
				echo $wptouch_pro->post['media_url'];
			}
		}
	}

	die;
}

function wptouch_media_addon_options( $page_options ) {
	wptouch_add_sub_page(
        WPTOUCH_RESPONSIVE_IMAGES_PAGENAME,
        'wptouch-addon-responsive-images',
        $page_options
    );

	wptouch_add_page_section(
		WPTOUCH_RESPONSIVE_IMAGES_PAGENAME,
		__( 'Responsive Images', 'wptouch-pro' ),
		'addons-media',
		array(
			wptouch_add_setting(
				'checkbox',
				'media_optimize_on_desktop',
				__( 'Include desktop optimizations for mobile devices', 'wptouch-pro' ),
				__( 'Normally only images on mobile are optimized', 'wptouch-pro' ),
				WPTOUCH_SETTING_BASIC,
				'3.1'
			),
			wptouch_add_setting(
				'radiolist',
				'media_optimize_preference',
				__( 'Performance preference', 'wptouch-pro' ),
				'',
				WPTOUCH_SETTING_BASIC,
				'3.1',
				array(
					'speed' => __( 'Optimize for page speed', 'wptouch-pro' ),
					'quality' => __( 'Optimize for quality', 'wptouch-pro' )
				)
			)
		),
		$page_options,
		ADDON_SETTING_DOMAIN
	);

	return $page_options;
}

function wptouch_media_can_optimize() {
	global $wptouch_pro;
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	return wptouch_is_mobile_theme_showing() || ( $settings->media_optimize_on_desktop && $wptouch_pro->is_mobile_device );
}

function wptouch_media_add_js() {
	wp_enqueue_script( 'wptouch_extension_responsive', WPTOUCH_BASE_CONTENT_URL . '/extensions/responsive-images/wptouch-responsive.js', array( 'jquery'), md5( WPTOUCH_RESPONSIVE_IMAGES_VERSION ), true );

	$data = array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'wptouch-responsive' )
	);

	wp_localize_script( 'wptouch_extension_responsive', 'WPtouchResponsiveImages', $data );
}

function wptouch_media_check_directories() {
	wptouch_create_directory_if_not_exist( WPTOUCH_RESPONSIVE_IMAGE_DIRECTORY );
}

function wptouch_media_get_filename_from_url( $photo ) {
	$file_url = str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $photo );
	return $file_url;
}

function wptouch_media_resize_photo( $image_file_name, $cached_file_name, $aspect_ratio, $max_device_dimension ) {
	$image_info = getimagesize( $image_file_name );
	if ( $image_info ) {
		$new_width = min( $max_device_dimension, $image_info[0] );
		$new_height = floor( $new_width / $aspect_ratio + 0.5 );

		WPTOUCH_DEBUG( WPTOUCH_INFO, 'Resizing photo to [' . $new_width . 'x' . $new_height . ']' );

		$new_image = imagecreatetruecolor( $new_width, $new_height );
		$source_image = false;

		switch( $image_info['mime'] ) {
			case 'image/jpeg':
			case 'image/jpg':
				$source_image = imagecreatefromjpeg( $image_file_name );
				break;
			case 'image/gif':
				$source_image = imagecreatefromgif( $image_file_name );
				break;
			case 'image/png':
				$source_image = imagecreatefrompng( $image_file_name );
				break;
		}

		if ( $source_image && $new_image ) {
			imagecopyresampled( $new_image, $source_image, 0, 0, 0, 0, $new_width, $new_height, $image_info[0], $image_info[1] );

			switch( $image_info['mime'] ) {
				case 'image/jpeg':
				case 'image/jpg':
					imagejpeg( $new_image, $cached_file_name, wptouch_media_get_jpeg_quality() );
					break;
				case 'image/gif':
					imagepng( $new_image, $cached_file_name, 9 );
					break;
				case 'image/png':
					imagepng( $new_image, $cached_file_name );
					break;
			}

			return true;
		}
	}

	return false;
}

function wptouch_media_should_resize_photo( $photo, $device_max_dimension, &$image_file_name, &$aspect_ratio ) {
	$image_file_name = wptouch_media_get_filename_from_url( $photo );
	if ( file_exists( $image_file_name ) ) {
		$size_info = getimagesize( $image_file_name, $image_info );
		$max_dimension = max( $size_info[0], $size_info[1] );

		if ( $max_dimension > $device_max_dimension ) {
			// we need to resize the photo
			$aspect_ratio = $size_info[0] / $size_info[1];
			return true;
		}
	}

	return false;
}

function wptouch_media_cached_photo_exists( $photo, $max_device_dimension, &$file_name ) {
	$file_name = wptouch_media_get_cached_image_file_name( $photo, $max_device_dimension );

	return file_exists( $file_name );
}

function wptouch_media_get_cached_image_file_name( $photo, $max_device_dimension ) {
	global $wptouch_pro;
	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	$hash = md5( WPTOUCH_RESPONSIVE_CACHE_KEY_PREFIX . '-' . $photo . '-' . $max_device_dimension . '-' . $settings->media_optimize_preference );

	$parsed_info = parse_url( $photo );
	if ( isset( $parsed_info['path'] ) && !strstr( $photo, 'http:' ) ) {
		$path = $parsed_info['path'];
		$params = explode( '/', $path );

		if ( count( $params ) ) {
			$filename = $params[ count( $params ) - 1];
			$file_info = pathinfo( $filename );

			return WPTOUCH_RESPONSIVE_IMAGE_DIRECTORY . '/' . $hash . '.' . $file_info['extension'];
		}
	}

	return false;
}

function wptouch_get_inside_html_fragment( $fragment, $content ) {
	$inside = false;

	if ( preg_match_all( '#' . $fragment . '=["\'](.*)[\'"]#iUs', $content, $matches ) ) {
		$inside = $matches[1][0];
	}

	return $inside;
}

function wptouch_media_content_filter( $content ) {
	global $wptouch_pro;

	$settings = wptouch_get_settings( ADDON_SETTING_DOMAIN );

	if ( !wptouch_media_can_optimize() ) {
		return $content;
	}

	$find_images = array();
	$replace_images = array();

	$result = preg_match_all( "#(<img(.*)>)#iUs", $content, $matches );
	if ( $result ) {
		// Found a big array of images
		$position = 0;
		foreach( $matches[1] as $image_url ) {
			// Find classes
			$classes = wptouch_get_inside_html_fragment( 'class', $image_url );

			if ( !strstr( $classes, 'ngg_displayed_gallery' ) ) {
				$image_src = wptouch_get_inside_html_fragment( 'src', $image_url );

				$old_image_tag = $matches[0][$position];

				// Update our replacement array
				$find_images[] = $old_image_tag;

				$new_tag = '<span style="text-align: center;" class="wptouch-responsive-image ' . $classes . '" data-url="' . $image_src . '" data-classes="' . $classes . '">';
				$new_tag .= '</span>';

				$replace_images[] = $new_tag;
			}
			$position++;
		}

		if ( count( $find_images ) ) {
			$content = str_replace( $find_images, $replace_images, $content );
		}
	}

	return $content;
}
