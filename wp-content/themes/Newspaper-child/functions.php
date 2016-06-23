<?php

/**
 * Enqueue the parent's style.css
 */
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

add_action('wp_head', 'add_fb_page');

function add_fb_page(){
  echo '<meta property="fb:pages" content="262931527247578" />';
}


/**
 * Custom colombianismos Search
 * Function start
 */
 function include_post_types_in_search($query) {
	if(is_search()) {
		$post_types = get_post_types(array('public' => true, 'exclude_from_search' => false), 'objects');
		$searchable_types = array();
		if($post_types) {
			foreach( $post_types as $type) {
				if($type->name == 'feature_post'){

				}else if($type->name == 'popular'){

				}else {
					$searchable_types[] = $type->name;
				}
			}
		}
		$query->set('post_type', $searchable_types);
	}
	return $query;
}
add_action('pre_get_posts', 'include_post_types_in_search');

/**
 * Custom colombianismos Search END
 */


/*CHRISTOPHER's CUSTOM MODS - 1.10.15 rev15A - (c) 2010-2015 Chris Simmons */

 remove_action( 'wp_head', 'wp_generator' ) ;
 remove_action( 'wp_head', 'wlwmanifest_link' ) ;
 remove_action( 'wp_head', 'rsd_link' ) ;
 remove_action( 'wp_head', 'feed_links', 2 );
 remove_action( 'wp_head', 'feed_links_extra', 3 );



 add_filter( 'pre_comment_content', 'wp_specialchars' );

 function no_errors_please(){
   return 'You appear to be up to no good. Please stop now!';
 }
 add_filter( 'login_errors', 'no_errors_please' );

 /* Disable YOAST SEO Admin Bar. */
 function mytheme_admin_bar_render() {
 	global $wp_admin_bar;
 	$wp_admin_bar->remove_menu('wpseo-menu');
 }
 // and we hook our function via
 add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );

 function delete_enclosure(){
  return '';
  }
  add_filter( 'get_enclosed', 'delete_enclosure' );
  add_filter( 'rss_enclosure', 'delete_enclosure' );
  add_filter( 'atom_enclosure', 'delete_enclosure' );

 function remove_cssjs_ver( $src ) {
     if( strpos( $src, '?ver=' ) )
         $src = remove_query_arg( 'ver', $src );
     return $src;
 }
 add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
 add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );

 add_filter( 'jpeg_quality', create_function( '', 'return 50;' ) );

 function remove_pingback_url( $output, $show ) {
     if ( $show == 'pingback_url' ) $output = '';
     return $output;
 }
 add_filter( 'bloginfo_url', 'remove_pingback_url', 10, 2 );

 add_action('init', 'myoverride', 100);
 function myoverride() {
     remove_action('wp_head', array(visual_composer(), 'addMetaData'));
}

/*Custom by harold*/
/*shortcode*/
function show_category_posts( $atts ){
    extract(shortcode_atts(array(
        'post_type'=> 'colombianismos',
        'number' => '',
        'order' => '',
        'orderby' => '',
        'hierarchical' => true,
       'rewrite' => true,


    ), $atts));
    query_posts('post_type='.$post_type.'&posts_per_page='.$number.'&order='.$order.'&orderby='.$orderby);




    if ( have_posts() ){
        $content = '<ul>';
        while ( have_posts() ){
            the_post();

            $content .=the_title('<li><a href="'.get_permalink().'">', '</a></li>', true);
        }

        $content .= '</ul>';
    }
    //Reset query
    wp_reset_query();
    return $content;
}
add_shortcode('list_colombianismos', 'show_category_posts');
/*****end shortcode*********/
/*
function namespace_add_custom_types( $query ) {
  if( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
    $query->set( 'post_type', array(
     'post', 'colombianismos','post','recipe','post','glossary'
                ));

          return $query;
        }

}

add_filter( 'pre_get_posts', 'namespace_add_custom_types' );
*/

function my_cptui_add_post_types_to_archives( $query ) {
  // We do not want unintended consequences.
  if ( is_admin() || ! $query->is_main_query() ) {
    return;
  }

  if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {

    // Replace these slugs with the post types you want to include.
    $cptui_post_types = array( 'colombianismos');

    $query->set(
           'post_type',
      array_merge(
        $query->get('post_type'),
        $cptui_post_types
      )
    );
  }
}
add_filter( 'pre_get_posts', 'my_cptui_add_post_types_to_archives' );

// rewrite link colombianismos test
function add_custom_rewrite_rule() {

    // First, try to load up the rewrite rules. We do this just in case
    // the default permalink structure is being used.
    if( ($current_rules = get_option('rewrite_rules')) ) {

        // Next, iterate through each custom rule adding a new rule
        // that replaces 'movies' with 'films' and give it a higher
        // priority than the existing rule.
        foreach($current_rules as $key => $val) {
            if(strpos($key, 'colombianismos') !== false) {
                add_rewrite_rule(str_ireplace('colombianismos', 'colombianismos', $key), $val, 'top');
            } // end if
        } // end foreach

    } // end if/else

    // ...and we flush the rules
    flush_rewrite_rules();

} // end add_custom_rewrite_rule
add_action('init', 'add_custom_rewrite_rule');


