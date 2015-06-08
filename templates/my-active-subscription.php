<?php
/**
 * The Template for displaying a user's active "my subscription" management
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
$custom_subscription_product = $uism->get_base_product();
?>

<h1 class="page-title">My Subscription</h1>

<div class="subscription-status-message">
	<p>Your subscription plan is <span class="custom-subscription-state-active">ACTIVE</span>.</p>
</div>

<hr/>

<p><span class="current-subscription-message">Current Subscription:</span> <?php echo get_the_title( $uism->get_base_product()->id ); ?></p>

<h4>Products in your subscription</h4>

<?php echo do_shortcode("[manage_subscription]"); ?>

<hr/>

<div class="my-subscription-checkout-footer">
	<a href="">
		<input type="submit" value="Upgrade" />
	</a>
	<p>You can cancel at any time. <a href="" class="custom-subscription-cancel-message">Cancel my subscription</a></p>	
</div>
