<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The template for displaying the custom subscription update UI in a product page.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

    global $product;

?>

<div class="container" id="subsc-product-change">
    <a href="#" id="dismiss-product-change"><i class="fa fa-times"></i></a>
    <div class="clearfix"></div>
    <div class="large-12 columns">

        <h2>You’re adding to your subscription</h2>
        <div class="subsc-product-selected-current">
            <div class="product">
                <span class="name"><?php echo $product->get_title(); ?></span>,
                <span class="strength">6mg</span>,
                <span clas="ratio">70PG/30VG</span>
            </div>
        </div>

        <?php echo do_shortcode("[manage_subscription]"); ?>

    </div>
</div>
