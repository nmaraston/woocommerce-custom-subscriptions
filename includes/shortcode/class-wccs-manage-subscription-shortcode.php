<?php
/**
 * Shortcode interface. All shortcodes in the plugin implement this interface.
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
	public static function output() {
		$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

		// To allow for duplicate product display (when user's UISM contains
		// multiples of the same products) we need to WP_Query each post
		// individually and output in an individual Loop.
		//
		// Note that for this reason we can not use the existing
		// $woocommerce_loop global array to manage the loop count since
		// WooCommerce resets the count on the 'loop_end' action.
		global $wccs_loop;
		$wccs_loop["columns"] = 4;

		ob_start();
		woocommerce_product_loop_start();

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
					<?php load_template( WCCS()->plugin_path() . "templates/content-custom-subscription-product.php", false ); ?>
				<?php endwhile; ?>
			<?php endif;
		}

		woocommerce_product_loop_end();

		// Reset $wccs_loop and post data
		$wccs_loop['loop'] = $wccs_loop['columns'] = '';
		wp_reset_postdata();

		return "<div class='woocommerce columns-4'>" . ob_get_clean() . "</div>";
	}
}
