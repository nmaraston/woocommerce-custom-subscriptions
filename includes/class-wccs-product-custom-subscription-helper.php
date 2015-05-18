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

	public static $PRODUCT_TYPE_NAME = 'custom subscription';
	public static $PRODUCT_COUNT_META_KEY = '_custom_subscription_product_count';

	private static $UI_PRODUCT_NAME = "Custom Subscription";
	private static $PRODUCT_SLOT_COUNT_OPTION_LIMIT = 10;

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
	 * Action handler for saving a post. If a "Custom Subscription" product type post is being saved, this handler
	 * reads the product count selection input and persists it as post metadata.
	 *
	 * @since 1.0
	 */
	public static function ah_save_post( $post_id ) {
		if ( ! isset( $_POST['product-type'] ) || ! $_POST['product-type'] == self::$PRODUCT_TYPE_NAME ) {
			return;
		}
		$custom_subscription_product_count = intval(  $_REQUEST[ self::$PRODUCT_COUNT_META_KEY ] );
		update_post_meta( $post_id, self::$PRODUCT_COUNT_META_KEY, $custom_subscription_product_count );
	}

	/**
	 * The WooCommerce Subscriptions plugin invokes action 'woocommerce_subscriptions_product_options_pricing'
	 * when displaying the editiable product data form. Here we add a drop down selection to modify the
	 * product count for a Custom Subscription.
	 *
	 * @since 1.0
	 */
	public static function ah_woocommerce_subscriptions_product_options_pricing() {
		global $post;
		$product = get_product( $post->ID );

		if ( $product->is_type( array( self::$PRODUCT_TYPE_NAME ) ) ) {

			$product_slot_count_options = array();
			for ( $n = 1; $n <= self::$PRODUCT_SLOT_COUNT_OPTION_LIMIT; $n++ ) {
				$product_slot_count_options[ $n ] = strval( $n );
			}

			// Display Custom Subscription Product Count select drop down
			woocommerce_wp_select( array(
				'id'          => self::$PRODUCT_COUNT_META_KEY,
				'class'       => 'wc_input_custom_subscription_product_count',
				'label'       => 'Product Count',
				'options'     => $product_slot_count_options
				)
			);
		}
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
			$classname = 'WC_Product_Custom_Subscription';
		}
		return $classname;
	}

	/**
	 * WooCommerce Subscription applys filter "woocommerce_subscription_product_types" when resolving a list of
	 * all valid WooCommerce Subscription product types incase an additional type has been added/extends (our use case).
	 *
	 * @since 1.0
	 */
	public static function fh_woocommerce_subscription_product_types( $product_types ) {
		array_push( $product_types, self::$PRODUCT_TYPE_NAME );
		return $product_types;
	}

	/**
	 * WooCommerce Subscription applys filter "woocommerce_is_subscription" when it is checking if a WC Product is of
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

	/**
	 * Woocommerce applies filter "product_type_selector" when it resolves a complete list of product types to display
	 * in the "Add Product" page selector. Here we add selection for "Custom Subscription" product creation.
	 *
	 * See http://docs.woothemes.com/wc-apidocs/source-class-WC_Meta_Box_Product_Data.html#33
	 *
	 * @since 1.0
	 */
	public static function fh_product_type_selector( $product_types ) {
		// For some reason WooCommerce applies sanitize_title() to the product_type term associated with the product when
		// creating the select markup. Therefore, we must also apply sanitize_title() to our added product type name so
		// WooCommerce can properly resolve it.
		$product_types[ sanitize_title( self::$PRODUCT_TYPE_NAME ) ] = self::$UI_PRODUCT_NAME;
		return $product_types;
	}
}
