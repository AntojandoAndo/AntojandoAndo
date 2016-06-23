<?php

/**
 * Represents the view for the administration dashboard. (PRO Version)
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package    PIB Pro
 * @subpackage Views
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $pib_options;

$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';

// Only show right sidebar on general and support tabs.
// Layout uses additional div containers and CSS classes to keep fluid with right sidebar.
$show_sidebar = in_array( $active_tab, array( 'general', 'support' ), TRUE );

?>

<div class="wrap">

	<div id="pib-settings" <?php echo ( ! $show_sidebar ? 'class="pib-no-sidebar"' : '' ); ?>>

		<div id="pib-settings-content">

			<h2><img src="<?php echo PIB_PLUGIN_URL; ?>assets/pinterest-icon-32.png" style="vertical-align: bottom;" /> <?php echo esc_html( get_admin_page_title() ); ?></h2>

			<h2 class="nav-tab-wrapper">
				<!-- General Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'general', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'pib' ); ?></a>
				<!-- Post Visibility Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'post_visibility', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'post_visibility' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Post Visibility', 'pib' ); ?></a>
				<!-- Image Hover Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'image_hover', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'image_hover' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Image Hover', 'pib' ); ?></a>
				<!-- Image Misc Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'below_image', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'below_image' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Below Image', 'pib' ); ?></a>
				<!-- Share Bar Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'share_bar', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'share_bar' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Share Bar', 'pib' ); ?></a>
				<!-- Styles Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'styles', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'styles' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Styles', 'pib' ); ?></a>
				<!-- Advanced Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'advanced', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Advanced', 'pib' ); ?></a>
				<!-- Support Tab -->
				<a href="<?php echo esc_url( add_query_arg( 'tab', 'support', remove_query_arg( 'settings-updated' ) ) ); ?>" class="nav-tab
					<?php echo $active_tab == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Support', 'pib' ); ?></a>
			</h2>

			<div id="tab_container">

				<form method="post" action="options.php">
					<?php
					if ( $active_tab == 'general' ) {
						settings_fields( 'pib_settings_general' );
						do_settings_sections( 'pib_settings_general' );
					} elseif ( $active_tab == 'post_visibility' ) {
						settings_fields( 'pib_settings_post_visibility' );
						do_settings_sections( 'pib_settings_post_visibility' );
					} elseif ( $active_tab == 'image_hover' ) {
						settings_fields( 'pib_settings_image_hover' );
						do_settings_sections( 'pib_settings_image_hover' );
					} elseif ( $active_tab == 'below_image' ) {
						settings_fields( 'pib_settings_image_misc' );
						do_settings_sections( 'pib_settings_image_misc' );
					} elseif ( $active_tab == 'share_bar' ) {
						settings_fields( 'pib_settings_share_bar' );
						do_settings_sections( 'pib_settings_share_bar' );
					} elseif ( $active_tab == 'styles' ) {
						settings_fields( 'pib_settings_styles' );
						do_settings_sections( 'pib_settings_styles' );
					} elseif ( $active_tab == 'advanced' ) {
						settings_fields( 'pib_settings_advanced' );
						do_settings_sections( 'pib_settings_advanced' );
					} elseif ( $active_tab == 'support' ) {
						settings_fields( 'pib_settings_support' );
						do_settings_sections( 'pib_settings_support' );
					} else {
						// Do nothing
					}

					submit_button();
					?>
				</form>
				
			</div><!-- #tab_container-->

		</div><!-- #pib-settings-content -->

		<!-- Only show sidebar on general and support tabs. -->
		<?php if ( $show_sidebar ): ?>
			<div id="pib-settings-sidebar">
				<?php include( 'admin-sidebar-pro.php' ); ?>
			</div>
		<?php endif; ?>
	</div>

</div><!-- .wrap -->

