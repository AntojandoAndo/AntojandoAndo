<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add new post meta options for pro
 *
 * @since   3.1.3
 *
 */
function add_pro_post_meta() { 
	
	global $post;
	
	$override_hover = get_post_meta( $post->ID, 'pib_override_hover_description', true );
	$override_below = get_post_meta( $post->ID, 'pib_override_below_description', true );
	$utm_meta       = get_post_meta( $post->ID, 'pib_utm_meta', true );

?>
<p>
	<input type="checkbox" name="pib_override_hover_description" id="pib_override_hover_description" <?php checked( ! empty( $override_hover ) ); ?> />
	<label for="pib_override_hover_description"><?php _e( 'Override default description for all hover buttons on this post', 'pib' ); ?></label>
</p>
<p>
	<input type="checkbox" name="pib_override_below_description" id="pib_override_below_description" <?php checked( ! empty( $override_below ) ); ?> />
	<label for="pib_override_below_description"><?php _e( 'Override default description for all below image buttons on this post', 'pib' ); ?></label>
</p>
<p>
	<label for="pib_utm_meta"><?php _e( 'UTM Variables', 'pib' ); ?>:</label><br />
	<input type="text" class="widefat" name="pib_utm_meta" id="pib_utm_meta" value="<?php echo esc_attr( $utm_meta ); ?>" /><br/>
	<span class="description"><?php _e( 'UTM tracking querystring to add to the end of your pin URLs. Include everything after the "?". This will override the main UTM Variables settings.', 'pib' ); ?></span>
</p>

<?php
}
add_action( 'pib_post_meta_options', 'add_pro_post_meta' );

/**
 * Add additional post meta fields so they get saved properly
 *
 * @since   3.1.3
 *
 * @return  array
 */
function add_pro_post_meta_fields( $post_meta_fields ) {
	
	$post_meta_fields[] = 'pib_override_hover_description';
	$post_meta_fields[] = 'pib_override_below_description';
	$post_meta_fields[] = 'pib_utm_meta';
	
	return $post_meta_fields;
}
add_filter( 'pib_post_meta_fields', 'add_pro_post_meta_fields' );