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

$product_title = get_the_title();
$product_price_html = $wc_product->get_price_html();

// Build Custom Subscription Product sign up URL
$my_subscription_page_link = get_page_link( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) );
$sign_up_url = add_query_arg( 'add-to-cart', $wc_product->id, $my_subscription_page_link );
$sign_up_url = esc_url( $sign_up_url );

// Build Custom Subscription Product upgrade URL
$upgrade_url = get_page_link( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) );

$action_url = ( $uism ) ? $upgrade_url : $sign_up_url;
$action_msg = ( $uism ) ? 'Upgrade!' : 'Sign Up!';
?>

<div>
    <ul>
        <li>
            <h3><?php echo $product_title; ?></h3>
        </li>
        <li>
            <p>
                <?php echo $product_price_html; ?>
            </p>
        </li>
        <a href="<?php echo $action_url; ?>">
            <?php echo $action_msg; ?>
        </a>
    </ul>
</div>
