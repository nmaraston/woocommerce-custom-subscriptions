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

    $default_product_id = WCCS_Option_Configuration::get_option(
        WCCS_Setting_Configuration::$DEFAULT_PRODUCT_ID_OPTION_KEY );
?>

<div class="custom-subscription-product-change">
    <a href="<?php echo wc_get_page_permalink('shop'); ?>"
       rel="nofollow" >
        <input type="submit" value="Change Product" />
    </a>
</div>

<br/>

<div class="custom-subscription-product-change">
    <a
       class="wccs_update_product"
       rel="nofollow"
       data-product_id="<?php echo $default_product_id; ?>"
       data-slot_number="<?php echo $wccs_loop['loop']; ?>"
       >
        <input type="submit" value="Set Default" />
    </a>
</div>
