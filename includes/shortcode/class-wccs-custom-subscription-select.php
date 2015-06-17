<?php
/**
 * Implements the custom_subscription_select shortcode. Displays UI to sign-up
 * to a custom subscription.
 *
 * @interface   WCCS_Custom_Subscription_Select
 * @package     WooCommerceCustomSubscriptions/shortcode
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Custom_Subscription_Select implements WCCS_I_Shortcode {

    /**
     *
     *
     * @return string
     * @since 1.0
     */
    public static function output( $atts = array() ) {
        if ( empty( $atts ) ) {
            return '';
        }

        $ids = explode( ',', $atts['ids'] );
        $ids = array_map( 'trim', $ids );

        $args = array(
            "post_type"      => "product",
            "posts_per_page" => count( $ids ),
            "post_status"    => "publish",
            "post__in"       => $ids
        );

        ob_start();

        $products = new WP_Query( $args );

        woocommerce_product_loop_start();

        if ( $products->have_posts() ) : ?>
            <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                <?php wccs_get_template( 'content-custom-subscription-selection.php' ); ?>
            <?php endwhile; ?>
        <?php endif;

        woocommerce_product_loop_end();
        wp_reset_postdata();

        return "<div class='custom-subscription-select'>" . ob_get_clean() . "</div>";
    }
}
