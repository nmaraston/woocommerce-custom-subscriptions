<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * Template for replacing a product in the user's Manage Subscription UI.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
?>

<div class="custom-subscription-product-change">
    <a href="<?php echo wc_get_page_permalink('shop'); ?>"
       rel="nofollow" >
        <input type="submit" value="Change Product" />
    </a>
</div>
