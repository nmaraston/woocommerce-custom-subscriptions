<?php
/**
 * A UISM Content Generator that generates products randomly from the catalog.
 *
 * @class       WCCS_UISM_Random_Content_Generator
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_UISM_Random_Content_Generator implements WCCS_UISM_I_Content_Generator {

	/**
	 * Generates product contents randomly. WC_Subscripton products are 
	 * excluded. Returns false if no other product types exist in the catalog.
	 *
	 * @since 1.0
	 */
	public function generate_products( $user_id, $product_id ) {
		$product_posts = get_posts( array( "post_type" => "product" ) );
		$product_sample_set = array();
		foreach ( $product_posts as $product_post ) {
			$wc_product = get_product( $product_post->ID );
			if ( ! WC_Subscriptions_Product::is_subscription( $wc_product ) ) {
				$product_sample_set[] = $wc_product;
			}
		}

		if ( empty( $product_sample_set ) ) {
			return false;
		}

		$products = array();
		$base_product = get_product( $product_id );
		$sample_count = count( $product_sample_set );
		for ( $index = 0; $index < $base_product->get_product_count(); $index++ ) {
			$products[] = $product_sample_set[ rand( 0, $sample_count - 1) ];
		}

		return $products;
	}

}