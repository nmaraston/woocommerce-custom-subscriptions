<?php
/**
 * The Script Loader manages all script assets for the plugin. It is in charge
 * of declaring correct script dependencies and enqeueing all scripts.
 *
 * @class       WCCS_Script_Loader
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Asset_Loader {

	// Relative path to plugin root containing all scripts.
	private static $SCRIPTS_DIR = 'assets/js';

	// Relative path to plugin root containing all styles.
	private static $STYLES_DIR = 'assets/css';

	/**
	 * Called via Wordpress to enqeue additional assets for admin pages.
	 * Here we enqueue the 'wccs_product_creation_form.js' Javascript asset which is responsible for
	 * displaying the Custom Subscription product creation form in the "Add Product" page.
	 * 
	 * @since 1.0
	 */
	public static function ah_admin_enqueue_scripts() {
		$screen = get_current_screen();
		$is_wc_add_product_screen = ( $screen->id === 'product' ) ? 1 : 0;

		if ( $is_wc_add_product_screen ) {
			self::enqueue_wccs_product_creation_script();
			self::enqueue_wccs_product_creation_style();
		}
	}

	/**
	 * Enqueue the 'wccs_product_creation_form.js' script. A dependency should be declared on the top level
	 * WooCommerce Subscriptions plugin admin script.
	 *
	 * @since 1.0
	 */
	private static function enqueue_wccs_product_creation_script() {
		$script = self::$SCRIPTS_DIR . '/wccs_product_creation_form.js';
		$script_handle = 'wccs_product_creation_form_script';
		$script_url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $script;
		$script_dependencies = array( 'woocommerce_subscriptions_admin' );
		$script_version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $script );

		wp_enqueue_script( $script_handle, $script_url, $script_dependencies, $script_version);
	}

	/**
	 * Enqueue the 'wccs_product_creation_form.css' styles. The CSS styles are depedent on the WooCommerce
	 * Subscriptions plugin admin styles.
	 *
	 * @since 1.0
	 */
	private static function enqueue_wccs_product_creation_style() {
		$style = self::$STYLES_DIR . '/wccs_product_creation_form.css';
		$style_handle = 'wccs_product_creation_form_styles';
		$style_url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $style;
		$style_dependencies = array( 'woocommerce_subscriptions_admin' );
		$style_version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $style );
		
		wp_enqueue_style( $style_handle, $style_url, $style_dependencies, $style_version );
	}
}
