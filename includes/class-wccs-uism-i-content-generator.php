<?php
/**
 * UISM Content Generator interface.
 *
 * A UISM Content Generator provides method generate_products(..) to generate
 * the product contents of a UISM.
 *
 * @interface   WCCS_UISM_I_Contents_Generator
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
interface WCCS_UISM_I_Content_Generator {

	/**
	 * Generates product contents for a UISM given a $user_id and $product_id 
	 * that identifies a WC_Product_Custom_Subscription base product for the 
	 * UISM. 
	 *
	 * The product contents are returned as an array of WC_Products. The array 
	 * is 0th indexed and is guaranteed to have length equal to the product 
	 * count of the given base product.
	 *
	 * Examples of product generators could:
	 *	   - Randomly generate products
	 *     - Generate products based on user preferences
	 * @param int $user_id
	 * @param int $product_id
	 * @return array ( WC_Product )
	 * @since 1.0
	 */
	public function generate_products( $user_id, $product_id );
}
