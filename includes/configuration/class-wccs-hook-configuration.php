<?php
/**
 * Hook configuration class. Used to wire ALL action and filter in the plugin.
 * Implements the singleton design pattern.
 *
 * @class       WCCS_Hook_Configuration
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Hook_Configuration {

	/**
	 * @var The single instance of WCCS_Hook_Configuration class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Main WCCS_Hook_Configuration class
	 *
	 * Ensures only one instance of WCCS_Hook_Configuration class is loaded or can be loaded.
	 *
	 * @static
	 * @see WCCS_Hook_Configuration()
	 * @return WCCS_Hook_Configuration - main instance
	 * @since 1.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * WCCS_Hook_Configuration Constructor. Hidden for singleton implementation.
	 * 
	 * @since 1.0
	 */
	private function __construct() {
	}

	/**
	 * Wire plugin activation and deactiviation hook
	 *
	 * @since 1.0
	 */
	public function wire_activation_hooks( $plugin_file_path ) {

		// Trigger install logic on plugin activation
		register_activation_hook( $plugin_file_path, 'WCCS_Installer::install' );

		// Trigger uninstall logic on plugin deactivation
		register_deactivation_hook( $plugin_file_path, 'WCCS_Installer::uninstall' );
	}


	/**
	 * Wire ALL Wordpress Actions used throughout the plugin.
	 * Wordpress function "add_action" should never be called anywhere else.
	 *
	 * @since 1.0
	 */
	public function wire_action_hooks() {

		/********************************************************
		 * Init Wordpress core action hooks.
		 *******************************************************/

		// Called via Wordpress once any activated plugins have been loaded.
		// Use high priority argument (999) to ensure handler fires after WooCommerceSubscriptions plugin handles action.
		add_action( 'plugins_loaded', 'WCCS_Product_Custom_Subscription_Helper::ah_plugins_loaded', 999, 0 );
	}

	/**
	 * Wire ALL Wordpress Filters used throughout the plugin.
	 * Wordpress function "add_filter" should never be called anywhere else.
	 *
	 * @since 1.0
	 */
	public function wire_filter_hooks() {

		/********************************************************
		 * Init WooCommerce Plugin filter hooks.
		 *******************************************************/

		// Called via WooCommerce when resolving a product-type (term name) to a product class.
		add_filter( 'woocommerce_product_class', 'WCCS_Product_Custom_Subscription_Helper::fh_woocommerce_product_class', 10, 4 );


		/********************************************************
		 * Init WooCommerce Subscription Plugin filter hooks.
		 *******************************************************/

		// Called when WooCommerce Subscriptions resolves a list of valid WC Subcription product types.
		add_filter( 'woocommerce_subscription_product_types', 'WCCS_Product_Custom_Subscription_Helper::fh_woocommerce_subscription_product_types', 10, 1 );

		// Called when WooCommerce Subscriptions is checking if a WC Product is of some Subscription product type.
		add_filter( 'woocommerce_is_subscription',            'WCCS_Product_Custom_Subscription_Helper::fh_woocommerce_is_subscription',            10, 1 );
	}
}

/**
 * Returns the main instance of WCCS_Hook_Configuration.
 *
 * @since 1.0
 * @return WCCS_Hook_Configuration - main instance.
 */
function WCCS_Hook_Configuration() {
	return WCCS_Hook_Configuration::instance();
}
