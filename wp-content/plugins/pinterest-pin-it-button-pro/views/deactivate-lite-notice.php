<?php

/**
 * Show notice after plugin install/activate in admin dashboard if Pib Lite was active and now has been deactivated
 *
 * @package    PIB
 * @subpackage Views
 * @author     Phil Derksen <pderksen@gmail.com>, Nick Young <mycorpweb@gmail.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<style>
	#pib-install-notice .button-primary,
	#pib-install-notice .button-secondary {
		margin-left: 15px;
	}
</style>

<div id="pib-deactivate-lite-notice" class="error">
	<p>
		<?php echo __( 'Pinterest "Pin It" Button Lite has been deactivated to avoid conflicts with the Pro Version. It is strongly recommended you delete the "Pin It" Button Lite plugin.', 'pib' ); ?>
		<a href="<?php echo esc_url( add_query_arg( 'pib-dismiss-lite-nag', 1 ) ); ?>" class="button-secondary"><?php _e( 'Hide this', 'pib' ); ?></a>
	</p>
</div>
