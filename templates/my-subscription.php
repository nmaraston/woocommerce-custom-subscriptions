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
			<p>You have not signed up for a custom subscription product.</p>
			<?php
		} else {
			switch ( $uism->get_state() ) {
				case WCCS_UISM_State::$ACTIVE_BILLING:
					wccs_get_template( 'my-active-billing-subscription.php' );
					break;
				case WCCS_UISM_State::$ACTIVE_NONBILLING:
					wccs_get_template( 'my-active-nonbilling-subscription.php' );
					break;
			}
		}
	?>

<?php get_footer( 'mysubscription' ); ?>
