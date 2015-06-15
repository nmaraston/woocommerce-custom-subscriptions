<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The template for displaying custom subscription selection UI.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
?>

<li>

    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

    <a href="<?php the_permalink(); ?>">

        <?php woocommerce_template_loop_product_thumbnail(); ?>

        <h3 id="custom-subscription-product-title"><?php the_title(); ?></h3>

        <?php woocommerce_template_single_excerpt(); ?>

        <?php
            $sign_up_url = get_permalink( WCCS_Page_Configuration::get_page_id('mysubscription') );
            $sign_up_url .= '&add-to-cart=' . get_the_ID();
        ?>

        <a href="<?php echo $sign_up_url ?>"
            rel="nofollow" >
            <input type="submit" value="Select" />
        </a>

    </a>

</li>