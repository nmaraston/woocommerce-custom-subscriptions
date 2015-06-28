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

        <a
        <?php if ( $uism ) { ?>

            class="wccs_soft_upgrade"
            rel="nofollow"
            data-product_id="<?php echo $wc_product->id; ?>"

        <?php } else { ?>

            <?php
                $sign_up_url = get_page_link( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) );
                $sign_up_url = add_query_arg( 'add-to-cart', $wc_product->id, $sign_up_url );
                $sign_up_url = esc_url( $sign_up_url );
            ?>

            href="<?php echo $sign_up_url; ?>"

        <?php } ?>

            >
            <?php echo ( ( $uism ) ? 'Upgrade!' : 'Sign Up!' ); ?>
        </a>
    </ul>
</div>
