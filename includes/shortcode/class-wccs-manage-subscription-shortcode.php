<?php
/**
 * Implements the manage_subscription shortcode. Displays subscription contents
 * management UI.
 *
 * @interface   WCCS_Manage_Subscription_Shortcode
 * @package     WooCommerceCustomSubscriptions/shortcode
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Manage_Subscription_Shortcode implements WCCS_I_Shortcode {

	/**
	 * Output the product contents of the user's UISM with a subscription
	 * upgrade button.
	 *
	 * @return string
	 * @since 1.0
	 */
	public static function output( $atts = array() ) {
		$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

		ob_start();

		if ( ! $uism ) {
			echo "You have not subscribed to a custom subscription.";
		} else {
			woocommerce_product_loop_start();

			// To allow for duplicate product display (when user's UISM contains
			// multiples of the same products) we need to WP_Query each post
			// individually and output in an individual Loop.
			foreach ( $uism->get_products() as $product ) {
				$product_ids[] = $product->id;

				$args = array(
					"post_type"      => "product",
					"posts_per_page" => 1,
					"post_status"    => "publish",
					"post__in"       => array( $product->id )
				);

				$posts = new WP_Query( $args );

				if ( $posts->have_posts() ) : ?>
					<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
						<?php wccs_get_template( 'content-custom-subscription-product.php' ); ?>
					<?php endwhile; ?>
				<?php endif;
			}

			woocommerce_product_loop_end();
			wp_reset_postdata();

			wccs_get_template( 'custom-subscription-upgrade-button.php' );
		}

		WCCS_Asset_Loader::enqueue_wccs_manage_subscription_styles();

		return "<div class='woocommerce wccs-manage-subscription'>" . ob_get_clean() . "</div>";
	}
}
