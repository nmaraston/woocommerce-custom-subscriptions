<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The template for displaying custom subscription selection UI.
 *
 * Assumes we are in The Loop.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

$wc_product = wc_get_product( get_the_ID() );

if ( ! WCCS_Product_Custom_Subscription_Helper::is_custom_subscription( $wc_product->id ) ) {
    return;
}

?>

<div>
    <ul>
        <li>
            <h3><?php echo get_the_title(); ?></h3>
        </li>
        <li>
            <p>
                <?php echo $wc_product->get_price_html(); ?>
            </p>
        </li>

        <?php if ( $uism ) { ?>

            <button type="submit"
                    class="wccs_soft_switch button"
                    rel="nofollow"
                    data-product_id="<?php echo $wc_product->id; ?>">
                Upgrade!
            </button>

        <?php } else { ?>

            <form class="cart"
                  action="<?php echo WCCS_Page_Configuration::get_page_link( 'mysubscription' ); ?>"
                  method="post"
                  enctype="multipart/form-data">

                <input type="hidden" name="add-to-cart" value="<?php echo $wc_product->id; ?>">
                <button type="submit" class="single_add_to_cart_button button alt">Sign Up!</button>

            </form>

        <?php } ?>
    </ul>
</div>
