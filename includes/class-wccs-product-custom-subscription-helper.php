<?php
/**
 * Helper class for Custom Subscription Product Class.
 *
 * Includes hooks/data related to Custom Subscription Product Class that can not be
 * invoked in the Product class due to how WooCommerceSubscriptions product classes 
 * are loaded.
 *
 * @class       WC_Product_Custom_Subscription_Helper
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Product_Custom_Subscription_Helper {

	public static $PRODUCT_TYPE_NAME = "custom subscription";

	/**
	 * The WooCommerce Subscriptions plugin loads it's added (WC_Product_Subscription) product classes on
	 * the 'plugins-loaded' action. Since the WC_Product_Custom_Subscription class extends the WC_Product_Subscription
	 * class, we need to wait for WooCommerce Subscriptions to load WC_Product_Subscription first. Then load
	 * the WC_Product_Custom_subscription class here.
	 *
	 * @since 1.0
	 */
	public static function ah_plugins_loaded() {
		include_once( WCCS()->plugin_path() . 'includes/class-wccs-product-custom-subscription.php' );
	}

	/**
	 * WooCommerce assumes a certain class name format for WooCommerce product types. This assumed format 
	 * is used when looking up a WC product via WC_Product_Factory. 
	 * 
	 * The assumed format is:
	 *         'WC_Product' + <Product Specific Name>
	 * where: 
	 *         <Product Specific Name> == implode( '_', array_map( 'ucfirst', explode( '-', $product_type ) ) ) 
	 * 
	 * You'll notice that the WC_Product_Custom_Subscription follows this format. This handler is implemented 
	 * as a backup.
	 * 
	 * See http://docs.woothemes.com/wc-apidocs/source-class-WC_Product_Factory.html for the implementation of product 
	 * class resolution.
	 *
	 * @since 1.0
	 */
	public static function fh_woocommerce_product_class( $classname, $product_type, $post_type, $product_id ) {
		if ( $product_type == self::$PRODUCT_TYPE_NAME ) {
			$classname = __CLASS__;
		}
		return $classname;
	}

	/**
	 * WooCommerce Subscription applys filter "woocommerce_subscription_product_types" when resolving a list of
	 * all valid WooCommerce Subscription product types incase an additional type has been added/extends (our use case).
	 *
	 * @since 1.0
	 */
	public static function fh_woocommerce_subscription_product_types() {
		array_push( $product_types, self::$PRODUCT_TYPE_NAME );
		return $product_types;
	}

	/**
	 * WooCommerce Subscription applys filter "woocommerce_is_subscription" when it is is checking if a WC Product is of
	 * some Subscription product type. WooCommerce Subscriptions only checks against it's provided subscription product 
	 * types.
	 * 
	 * @since 1.0
	 */
	public static function fh_woocommerce_is_subscription( $product_id ) {
		$subscription_product_types = array( 'subscription', 'subscription_variation', 'variable-subscription', self::$PRODUCT_TYPE_NAME );
		$product = get_product( $product_id );
		if ( $product->is_type( $subscription_product_types ) ) {
			return true;
		}
		return false;
	}
}
