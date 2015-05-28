<?php
/**
 * Shortcode interface. All shortcodes in the plugin implement this interface.
 *
 * @interface   WCCS_I_Shortcode
 * @package     WooCommerceCustomSubscriptions/shortcode
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
interface WCCS_I_Shortcode {

	/**
	 * Output the content of the shortcode.
	 *
	 * @since 1.0
	 */
	public static function output();
}