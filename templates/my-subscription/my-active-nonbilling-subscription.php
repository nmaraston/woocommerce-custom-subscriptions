<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The Template for displaying a user's inactive "my subscription" management
 * page.
 *
 * Override this template by copying it to
 * yourtheme/woocommerce/archive-product.php
 *
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );
?>

<h1 class="page-title">My Subscription</h1>

<div class="subscription-status-message">
	<p>Your subscription status is <span class="custom-subscription-state-inactive">INACTIVE</span>.</p>
	<p><a href="<?php echo wc_get_page_permalink('checkout'); ?>">Checkout</a> now to activate.</p>
</div>

<hr/>

<h4>Products in your subscription</h4>

<?php echo do_shortcode("[manage_subscription]"); ?>

<hr/>

<div class="my-subscription-checkout-footer">
	<a class="my-subscription-checkout-btn" href="<?php echo wc_get_page_permalink('checkout'); ?>">
		<input type="submit" value="Checkout" />
	</a>

	<p class="my-subscription-start-msg">Start your subscription now!</p>
</div>
