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
?>

<li>

    <?php
        $wc_product = wc_get_product( get_the_ID() );

        $product_title = get_the_title();
        $product_price_html = $wc_product->get_price_html();

        // Build Custom Subscription Product sign up url
        $my_subscription_page_link = get_page_link( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) );
        $raw_sign_up_url = add_query_arg( 'add-to-cart', $wc_product->id, $my_subscription_page_link );
        $sign_up_url = esc_url( $raw_sign_up_url );
    ?>

    <div>
        <ul>
            <li>
                <h3 class="plan-name"><?php echo $product_title; ?></h3>
            </li>
            <li>
                <p>
                    <?php echo $product_price_html; ?>
                </p>
            </li>
            <a href="<?php echo $sign_up_url; ?>">Choose</a>
        </ul>
    </div>

</li>
