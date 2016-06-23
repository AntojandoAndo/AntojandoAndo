<?php

/**
 * Define plugin shortcodes.
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
 * Function to process any [pinit] shortcodes
 *
 * @since   3.0.0
 *
 * @return  string
 */
function pib_pro_shortcode( $attr ) {
    $options = get_option( 'pib_sharebar_buttons' );
	global $post, $pib_options;
	
    $postID = $post->ID;
	
	//Check for sharing disabled on individual post meta
	if ( get_post_meta( $post->ID, 'pib_sharing_disabled', 1 ) ) {
		if( current_user_can( 'manage_options' ) ) {
			return _e( 'Pinit button is disabled for this post.', 'pib' );
		}
		
		return;
	}
    
    /*
        For URL, image URL and Description, use in order:
        1) attribute value
        2) custom fields for post
        3) inherit from post: permalink, first image, post title
    */
    
    extract( shortcode_atts( array(
					'count'              => 'none',
					'url'                => '',
					'image_url'          => '',
					'description'        => '',
					'align'              => 'none',
					'remove_div'         => false,
					'button_type'        => 'any',
					'social_buttons'     => false,
					'use_featured_image' => false,
					'size'               => 'small',
					'color'              => 'gray',
					'shape'              => 'rectangular',
					'always_show_count'  => false
				), $attr ) );
	
		// set button_type to a correct parameter to be passed
		$button_type = ( $button_type == 'one' ? 'image_selected' : 'user_selects_image' );
	
		if ( empty( $url ) ) {
			$url = get_post_meta( $postID, 'pib_url_of_webpage', true);

			if ( empty( $url ) ) {
				$url = get_permalink( $postID );
			}
		}
    
		if ( empty( $image_url ) ) {
			$image_url = get_post_meta( $postID, 'pib_url_of_img', true);
			
			if ( empty( $image_url ) ) {
				if( $use_featured_image !== false && $use_featured_image !== 'false' ) {
					$image_url = pib_featured_image( true );
				} 
				
				if( empty( $image_url ) ) {
					//Get url of img and compare width and height
					$output    = preg_match_all( '/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches );

					if ( ! ( 0 == $output || false == $output ) ) {
						$image_url = $matches [1] [0];
					} else {
						$image_url = '';
					}
				}
			}
		}

		if ( empty( $description ) ) {
			$description = get_post_meta( $postID, 'pib_description', true);

			if ( empty( $description ) ) {
				$description = get_post_field( 'post_title', $postID, 'raw' );
			}
		}
		
		if( $social_buttons !== false && $social_buttons !== 'false' ) {
			// Load the JS for sharebar buttons
			wp_localize_script( 'pib-async-script-loader', 'scVars', 
				array (
					'scEnableSharebar' => 1
				)
			);
			
			$always_show_count = ( ( $always_show_count !== false && $always_show_count !== 'false' ) ? true : false );

			$base_btn = pib_button_base( $button_type, $url, $image_url, $description, $count, $size, $color, $shape, $always_show_count );

			//Don't wrap with div or set float class if "remove div" is checked
			if ( $remove_div !== false && $remove_div !== 'false' ) {
				$html = pib_sharebar_html_pro( $base_btn );
			}
			else {
				//Surround with div tag
				$align_class = '';

				if ( 'left' == $align ) {
					$align_class = 'pib-align-left';
				}
				elseif ( 'right' == $align ) {
					$align_class = 'pib-align-right';
				}
				elseif ( 'center' == $align ) {
					$align_class = 'pib-align-center';
				}

				$html = '<div class="pin-it-btn-wrapper-shortcode ' . $align_class . '">' . pib_sharebar_html_pro( $base_btn ) . '</div>';
			}

			//$html = pib_sharebar_html_pro( $html );
		} else {
			// Do not load JS for sharebar buttons
			wp_localize_script( 'pib-async-script-loader', 'scVars', 
				array (
					'scEnableSharebar' => 0
				)
			);
			
			$always_show_count = ( ( $always_show_count !== false && $always_show_count !== 'false' ) ? true : false );

			$base_btn = pib_button_base( $button_type, $url, $image_url, $description, $count, $size, $color, $shape, $always_show_count );

			//Don't wrap with div or set float class if "remove div" is checked
			if ( $remove_div !== false && $remove_div !== 'false' ) {
				$html = $base_btn;
			}
			else {
				//Surround with div tag
				$align_class = '';

				if ( 'left' == $align ) {
					$align_class = 'pib-align-left';
				}
				elseif ( 'right' == $align ) {
					$align_class = 'pib-align-right';
				}
				elseif ( 'center' == $align ) {
					$align_class = 'pib-align-center';
				}

				$html = '<div class="pin-it-btn-wrapper-shortcode ' . $align_class . '">' . $base_btn . '</div>';
			}
		}
		
		$before_html = '';
		$after_html  = '';
		
		$before_html = apply_filters( 'pib_shortcode_before', $before_html );
		$html        = apply_filters( 'pib_shortcode_html', $html );
		$after_html  = apply_filters( 'pib_shortcode_after', $after_html );
		
		
		return $before_html . $html . $after_html;
}
// remove the shortcode from the Lite basecode before adding the Pro version
remove_shortcode( 'pinit' );
add_shortcode( 'pinit', 'pib_pro_shortcode' );
