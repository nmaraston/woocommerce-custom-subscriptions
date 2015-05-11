<?php
/**
 * Custom Subscription Product Class
 *
 * The custom subscription product class is an extension of subscrption product class.
 *
 * @class       WC_Product_Custom_Subscription
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WC_Product_Custom_Subscription extends WC_Product_Subscription {

	/**
	 * Constructor for WC_Product_Custom_Subscription
	 *
	 * @param mixed $product
	 */
	public function __construct( $product ) {
		parent::__construct( $product );
		$this->product_type = WCCS_Product_Custom_Subscription_Helper::PRODUCT_TYPE_NAME;
	}
}