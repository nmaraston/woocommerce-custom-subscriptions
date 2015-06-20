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

<?php
    global $wccs_loop;

    // Build Custom Subscription Product sign up URL
    $my_subscription_page_link = get_page_link( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) );
    $default_prouduct_id = WCCS_Option_Configuration::get_option( WCCS_Setting_Configuration::$DEFAULT_PRODUCT_ID_OPTION_KEY );
    $update_product_url = add_query_arg( 'updateproduct', $default_prouduct_id . '-' . $wccs_loop['loop'], $my_subscription_page_link );
    $update_product_url = esc_url( $update_product_url );
?>

<div class="custom-subscription-product-change">
    <a href="<?php echo wc_get_page_permalink('shop'); ?>"
       rel="nofollow" >
        <input type="submit" value="Change Product" />
    </a>
</div>

<br/>

<div class="custom-subscription-product-change">
    <a href="<?php echo $update_product_url; ?>"
       rel="nofollow" >
        <input type="submit" value="Set Default" />
    </a>
</div>
