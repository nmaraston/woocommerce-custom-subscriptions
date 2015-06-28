<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The template for displaying the custom subscription upgrade button.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
?>

<div class="custom-subscription-upgrade-item-button">
    <a href="<?php echo get_page_link( WCCS_Page_Configuration::get_page_id( 'subscriptionselection' ) ); ?>"
       rel="nofollow" >
        <input type="submit" value="Upgrade Subscription" />
    </a>
</div>
