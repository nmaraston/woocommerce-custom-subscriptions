<?php
/**
 * Plugin Name: WooCommerce Custom Subscriptions
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

final class WC_Custom_Subscriptions {

	/**
	 * @var Version of plugin
	 */
	public static $version = "1.0.0";

	public static $plugin_file = __FILE__;

	/**
	 * @var The single instance of WooCommerce_Custom_Subscriptions class
	 * @since 1.0
	 */
	private static $_instance = null;

	/**
	 * Main WC_Custom_Subscription instance
	 *
	 * Ensures only one instance of WC_Custom_Subscription is loaded or can be loaded.
	 *
	 * @static
	 * @see WCCS()
	 * @return WC_Custom_Subscriptions - main instance
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
	 * WC_Custom_Subscriptions constructor. Hidden for singleton implementation.
	 *
	 * @since 1.0
	 */
	private function __construct() {
		$this->init_plugin();
	}

	/** 
	 * Initialize the WooCommerce Custom Subscriptions plugin.
	 * 
	 * @since 1.0
	 */
	public function init_plugin() {
		$this->includes();
		$this->init_logger();
		$this->init_hooks();
		$this->init_shortcodes();
	}

	/**
	 * Includes required core files.
	 *
	 * @since 1.0
	 */
	private function includes() {
		include_once( 'includes/util/class-wccs-logger.php' );
		include_once( 'includes/class-wccs-product-custom-subscription-helper.php' );
		include_once( 'includes/class-wccs-uism-state.php' );
		include_once( 'includes/class-wccs-uism-manager.php' );
		include_once( 'includes/class-wccs-uism-dao.php' );
		include_once( 'includes/class-wccs-uism-i-content-generator.php' );
		include_once( 'includes/class-wccs-uism-random-content-generator.php' );
		include_once( 'includes/class-wccs-installer.php' );
		include_once( 'includes/wccs-template-functions.php' );
		include_once( 'includes/configuration/class-wccs-hook-configuration.php' );
		include_once( 'includes/configuration/class-wccs-page-configuration.php' );
		include_once( 'includes/configuration/class-wccs-asset-loader.php' );
		include_once( 'includes/configuration/class-wccs-template-loader.php' );
		include_once( 'includes/shortcode/class-wccs-shortcode-config.php' );
		include_once( 'includes/shortcode/class-wccs-i-shortcode.php' );
		include_once( 'includes/shortcode/class-wccs-manage-subscription-shortcode.php' );
		include_once( 'includes/shortcode/class-wccs-custom-subscription-select.php' );
	}

	/**
	 * Initliaze the plugin specific logger.
	 *
	 * @since 1.0
	 */
	private function init_logger() {
		// Set shortform plugin name for logging
		WCCS_Logger()->set_plugin_name( 'WCCS' );
		WCCS_Logger()->info( "Logger initialized.", __CLASS__ );
	}

	/**
	 * Hook into actions and filters.
	 * 
	 * @since 1.0
	 */
	private function init_hooks() {
		WCCS_Logger()->info( "Initializing hooks (actions/filters).", __CLASS__ );

		// Wire plugin activation hooks
		WCCS_Hook_Configuration()->wire_activation_hooks( __FILE__ );

		// Wire all actions for the plugin
		WCCS_Hook_Configuration()->wire_action_hooks();

		// Wire all filters for the plugin
		WCCS_Hook_Configuration()->wire_filter_hooks();
	}

	/**
	 * Initialize the shortcodes used in the plugin.
	 * @since 1.0
	 */
	private function init_shortcodes() {
		WCCS_Shortcode_Config::init_shortcodes();
	}

	/**
	 * @return the absolute path to the plugin directory.
	 * @since 1.0
	 */ 
	public function plugin_path() {
		return plugin_dir_path( self::$plugin_file );
	}

	/**
	 * @return the absolute path to the default template directory.
	 * @since 1.0
	 */
	public function default_template_path() {
		return $this->plugin_path() . 'templates/';
	}

	/**
	 * @return the relative path (to theme root) to the folder where template
	 *         overrides are searched for.
	 * @since 1.0
	 */
	public function override_template_path() {
		return WC()->template_path() . 'custom-subscriptions/';
	}
}

endif;

/**
 * Returns the main instance of WC_Custom_Subscriptions to prevent the need to use globals.
 *
 * @return WC_Custom_Subscriptions - main instance.
 * @since 1.0
 */
function WCCS() {
	return WC_Custom_Subscriptions::instance();
}

// Will trigger plugin initialization on first call only.
WCCS();
