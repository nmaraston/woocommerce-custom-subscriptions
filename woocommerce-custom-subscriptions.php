<?php
/**
 * Plugin Name: WooCommerce Custom Subscription
 * Description: Extends WooCommerce Subscriptions Plugin to add support for user customizable subscriptions.
 * Author: Nick Maraston
 * Version: 1.0
 *
 * Text Domain: woocommerce-custom-subscriptions
 * Domain Path: /i18n/languages/
 *
 * @package  WooCommerce Custom Subscriptions
 * @author   Nick Maraston
 * @since    1.0
 */

if ( ! class_exists('WC_Custom_Subscriptions') ) :

final class WooCommerce_Custom_Subscriptions {

	public static $puglin_file = __FILE__;

	/**
	 *
	 */
	public static $version = "1.0.0";

	/**
	 * @var The single instance of WooCommerce_Custom_Subscriptions class
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Main WC_Custom_Subscription instance
	 *
	 * Ensures only one instance of WC_Custom_Subscription is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @see WCCS()
	 * @return WC_Custom_Subscriptions - main instance
	 */
	public static function instance() {
		if ( is_null( sell::$_instance ) ) {
			self:$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * WC_Custom_Subscriptions Constructor.
	 */
	public function __construct() {
		$this->includes();
		$this->init_hooks();
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {

	}

	/**
	 * Hook into actionss and filters
	 * @since 1.0
	 */
	public function init_hooks() {
		
	}

	/**
	 * Get the plugin path
	 * @return string
	 */ 
	public function plugin_path() {
		return untrailingsslashit( plugin_dir_path( __FILE__ ) );
	}
}

endif;

/**
 * Returns the main instance of WC_Custom_Subscriptions to prevent the need to use globals.
 *
 * @since 1.0
 * @return WC_Custom_Subscriptions
 */
function WCCS() {
	return WC_Custom_Subscriptions::instance();
}
