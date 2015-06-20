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
     * Output UI for selecting a custom subscription for sign up.
     *
     * @return string
     * @since 1.0
     */
    public static function output( $atts = array() ) {
		if ( !isset( $atts['id'] ) ) {
			return '';
		}

		$args = array(
			"post_type"      => "product",
			"posts_per_page" => 1,
			"post_status"    => "publish",
			"p"              => $atts['id']
		);

		ob_start();

		$posts = new WP_Query( $args );

		if ( $posts->have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>

					<?php wccs_get_template( 'loop/content-custom-subscription-selection.php' ); ?>

				<?php endwhile; ?>

			<?php woocommerce_product_loop_end(); ?>

		<?php endif;

		wp_reset_postdata();

		return ob_get_clean();
	}
}
