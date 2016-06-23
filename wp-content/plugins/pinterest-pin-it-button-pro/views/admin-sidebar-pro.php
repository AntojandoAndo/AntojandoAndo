<?php

/**
 * Sidebar portion of the administration dashboard view (and subpages) (PRO version).
 *
 * @package    PIB Pro
 * @subpackage Views
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="sidebar-container metabox-holder">
	<div class="postbox">
		<div class="inside">
			<ul>
				<li>
					<div class="dashicons dashicons-arrow-right-alt2"></div>
					<a href="<?php echo esc_url( add_query_arg( 'page', PIB_PLUGIN_SLUG . '_help', admin_url( 'admin.php' ) ) ); ?>">
						<?php _e( 'Shortcode & CSS Help', 'pib' ); ?></a>
				</li>
				<li>
					<div class="dashicons dashicons-arrow-right-alt2"></div>
					<a href="<?php echo pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'support', 'pib_pro_3', 'sidebar_link', 'support' ); ?>" target="_blank">
						<?php _e( 'Knowledgebase & Support', 'pib' ); ?></a>
				</li>
				<li>
					<div class="dashicons dashicons-arrow-right-alt2"></div>
					<a href="<?php echo pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'feature-request', 'pib_pro_3', 'sidebar_link', 'support' ); ?>" target="_blank">
						<?php _e( 'Request & Vote on New Features', 'pib' ); ?></a>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php if ( pib_is_woo_commerce_active() ): // Check if WooCommerce is active to show WC Rich Pins promo. ?>

	<?php if ( ! pib_is_wc_rich_pins_active() ): // If WooCommerce Rich Pins is already active don't show. ?>

		<div class="sidebar-container metabox-holder">
			<div class="postbox">
				<h3 class="wp-ui-primary"><span><?php _e( 'WooCommerce Rich Pins', 'pib' ); ?></span></h3>
				<div class="inside">
					<div class="main">
						<p>
							<?php _e( 'Running a WooCommerce store and want to give your product pins a boost? There\'s a plugin for that.', 'pib' ); ?>
						</p>
						<div class="centered">
							<a href="<?php echo pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'plugins/product-rich-pins-for-woocommerce/', 'pib_pro_3', 'sidebar_link', 'wc_rich_pins' ); ?>"
							   class="button-primary" target="_blank">
								<?php _e( 'Check out Rich Pins for WooCommerce', 'pib' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endif; // End WooCommerce Rich Pins check ?>

<?php else: // If not running WooCommerce show Article Rich Pins promo. ?>

	<?php if ( ! pib_is_article_rich_pins_active() ): // Unless of course Article Rich Pins is already active. ?>

		<div class="sidebar-container metabox-holder">
			<div class="postbox">
				<h3 class="wp-ui-primary"><span><?php _e( 'Article Rich Pins', 'pib' ); ?></span></h3>
				<div class="inside">
					<div class="main">
						<p>
							<?php _e( 'Want give your pins a boost with <strong>Article Rich Pins</strong>? There\'s a plugin for that.', 'pib' ); ?>
						</p>
						<div class="centered">
							<a href="<?php echo pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'plugins/article-rich-pins/', 'pib_pro_3', 'sidebar_link', 'article_rich_pins' ); ?>"
							   class="button-primary" target="_blank">
								<?php _e( 'Check out Article Rich Pins', 'pib' ); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>

	<?php endif; // End Article Rich Pins check ?>

<?php endif; // End WooCommerce check ?>

<div class="sidebar-container metabox-holder">
	<div class="postbox">
		<h3><?php _e( 'Recent News from pinplugins.com', 'pib' ); ?></h3>
		<div class="inside">
			<?php pib_rss_news(); ?>
		</div>
	</div>
</div>
