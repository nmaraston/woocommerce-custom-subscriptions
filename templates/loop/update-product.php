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

    global $wccs_loop, $change_product_id;

    $default_product_id = WCCS_Option_Configuration::get_option(
        WCCS_Setting_Configuration::$DEFAULT_PRODUCT_ID_OPTION_KEY );

?>

<?php if ( $change_product_id ) {

    /*
     * We are given a product id to replace contents with. Display UI to replace
     * the Custom Subscription content at the current slot with the currently
     * displayed product.
     */
    ?>

    <div>
        <a
            class="wccs_update_product"
            rel="nofollow"
            data-product_id="<?php echo $change_product_id; ?>"
            data-slot_number="<?php echo $wccs_loop['loop']; ?>"
            >
            <input type="submit" value="Replace" />
        </a>
    </div>

<?php } else {

    /*
     * Display UI for redirecting to the Shop page and setting the Custom
     * Subscription content at the current slot with the default product.
     */
    ?>

    <div>
        <a href="<?php echo wc_get_page_permalink('shop'); ?>"
           rel="nofollow" >
            <input type="submit" value="Change Product" />
        </a>
    </div>

    <br/>

    <div>
        <a
           class="wccs_update_product"
           rel="nofollow"
           data-product_id="<?php echo $default_product_id; ?>"
           data-slot_number="<?php echo $wccs_loop['loop']; ?>"
           >
            <input type="submit" value="Set Default" />
        </a>
    </div>

<?php } ?>

