<?php
/**
 * Option configuration class. Manages saving and retrieving all options used in
 * the plugin.
 *
 * This is a simple wrapper around the WP option API to centralize any option
 * logic specific to the plugin.
 *
 * @class       WCCS_Option_Configuration
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Option_Configuration {

    private static $OPTION_PREFIX = 'wccs';

    /**
     * Get the value of an option given it's $option_key
     *
     * @param String $option_key
     * @return String
     * @since 1.0
     */
    public static function get_option( $option_key, $default = false ) {
        if ( $default ) {
            return get_option( self::get_option_name( $option_key), $default );
        } else {
            return get_option( self::get_option_name( $option_key ) );
        }
    }

    /**
     * Set the value ($option_value) of an option given it's $option_key.
     *
     * @param String $option_key
     * @param String $option_value
     * @return String
     * @since 1.0
     */
    public static function set_option( $option_key, $option_value ) {
        return update_option( self::get_option_name( $option_key ), $option_value );
    }

    /**
     * Get a full option name (the name used in wp_options table) given the
     * option key used locally in the plugin.
     *
     * @param $option_key
     * @return String
     * @since 1.0
     */
    public static function get_option_name( $option_key ) {
        return self::$OPTION_PREFIX . '_' . $option_key;
    }
}
