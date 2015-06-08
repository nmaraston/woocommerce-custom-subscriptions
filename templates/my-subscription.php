<?php
/**
 * The Template for displaying a user's "my subscription" management page.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

get_header( 'mysubscription' ); ?>

	<?php
		do_action( 'woocommerce_before_main_content' );

		$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

		if ( ! $uism ) {
			?>
			<p>You have not subscribed to a custom subscription product.</p>
			<?php
		} else {
			switch ( $uism->get_state() ) {
				case WCCS_UISM_State::$ACTIVE_BILLING:
					wc_get_template( 'my-active-subscription.php', array(), '', WCCS()->default_template_path() );
					break;
				case WCCS_UISM_State::$ACTIVE_NONBILLING:
				case WCCS_UISM_State::$INACTIVE:
					wc_get_template( 'my-inactive-subscription.php', array(), '', WCCS()->default_template_path() );
					break;
			}
		}
	?>

<?php get_footer( 'mysubscription' ); ?>
