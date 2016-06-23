<?php

/**
 * Show admin license key notice if blank or invalid license saved.
 *
 * @package    PIB
 * @subpackage Views
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $pib_options;

// Exit now if license key is entered and valid.
if ( ! empty( $pib_options['pib_license_key'] ) ) {
	return;
}

?>

<style>
	#pib-license-notice .button-primary,
	#pib-license-notice .button-secondary {
		margin: 2px 0;
	}
</style>

<div id="pib-license-notice" class="error">
	<p>
		<?php

		// Check for empty key first.
		echo '<strong>' . __( 'Notice: You must enter your "Pin It" Button Pro license key to receive automatic updates and support.', 'pib' ) . '</strong><br />' . "\n";

		// Show "below" message on Support tab.
		if ( !( isset( $_GET['tab'] ) && ( 'support' == $_GET['tab'] ) ) ) {
			// Render link to Support tab on other plugin tabs.
			echo '<a href="' . esc_url( add_query_arg( array ( 'page' => $this->plugin_slug, 'tab' => 'support' ), admin_url( 'admin.php' ) ) ) . '">' .
					__( 'Go to the Support tab to enter your license key', 'pib' ) . '</a>.<br />' . "\n";
		}

		// In all cases show message and link to purchase a key.
		?>

		<?php _e( 'If you would like to purchase a license ', 'pib' ); ?>
		<a href="<?php echo pib_ga_campaign_url( PINPLUGIN_BASE_URL . 'pin-it-button-pro', 'pib_pro_3', 'license_notice', 'license_notice' ); ?>"
		   target="_blank"><?php _e( 'please visit our store', 'pib' ); ?></a>.
	</p>
</div>
