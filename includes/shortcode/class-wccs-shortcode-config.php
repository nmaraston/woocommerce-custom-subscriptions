<?php
/**
 * Shortcode configuration. This class defines all shortcodes for the plugin.
 *
 * @interface   WCCS_Manage_Subscription_Shortcode
 * @package     WooCommerceCustomSubscriptions/shortcode
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Shortcode_Config {

	/**
	 * The shortcode configuration map. This map defines all shortcodes that the
	 * plugin provides. Keys are shortcode names and values are the shortcode
	 * class that implements the shortcode. A shortcode class must implement the
	 * WCCS_I_Shortcode interface.
	 *
	 * @since 1.0
	 */
	private static $shortcode_config_map = array(
		'manage_subscription' => 'WCCS_Manage_Subscription_Shortcode'
	);

	/**
	 * Initialize all the shortcodes defined in the the shortcode configuration
	 * map.
	 *
	 * @since 1.0
	 */
	public static function init_shortcodes() {
		foreach ( self::$shortcode_config_map as $shortcode_name => $shortcode_class ) {
			add_shortcode( $shortcode_name, $shortcode_class . '::output' );
		}
	}
}
