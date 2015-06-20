<?php
/**
 * Settings configuration class. Manages plugin settings declared in the admin
 * UI settings page.
 *
 * WCCS Settings are appended to WooCommerce Subscription settings.
 *
 * @class       WCCS_Setting_Configuration
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Setting_Configuration {

    public static $DEFAULT_PRODUCT_ID_OPTION_KEY        = 'default_product_id';
    public static $PRODUCT_GENERATION_METHOD_OPTION_KEY = 'product_generation';

    /**
     * @var array
     */
    private static $product_generation_option_map = array(
        'WCCS_UISM_Random_Content_Generator'             => 'Random Product',
        'WCCS_UISM_Default_Product_Content_Generator'    => 'Uniform Default Product'
    );

    /**
     * Set default values for all WooCommerce Custom Subscription settings with
     * defaults.
     *
     * @since 1.0
     */
    public static function set_default_settings() {
        foreach ( self::get_settings() as $setting ) {
            if ( isset( $setting['default'] ) ) {
                WCCS_Option_Configuration::set_option( $setting['option_key'], $setting['default'] );
            }
        }
    }

    /**
     * Filter handler to add additional settings UI components to the Admin
     * Subscription settings tab.
     *
     * @param array() $settings
     * @since 1.0
     */
    public static function fh_woocommerce_subscription_settings( $settings ) {
        return array_merge( $settings, self::get_settings() );
    }

    /**
     * Return an array of arrays where each array entry represents a admin
     * settings UI component to be displayed by the WooCommerce admin fields
     * API.
     *
     * @return array
     * @since 1.0
     */
    private static function get_settings() {
        $wccs_settings = array(

            // Custom Subscription Settings Title
            array(
                'name' 			=> 'Custom Subscriptions',
                'type' 			=> 'title',
                'desc' 			=> 'Settings for Custom Subscription products'
            ),

            // Default Product ID Form
            array(
                'id'            => WCCS_Option_Configuration::get_option_name( self::$DEFAULT_PRODUCT_ID_OPTION_KEY ),
                'option_key'    => self::$DEFAULT_PRODUCT_ID_OPTION_KEY,
                'name'     		=> 'Default Product ID',
                'type'     		=> 'text',
                'desc_tip'     	=> 'Set the Custom Subscription Default Product ID',
                'css'      		=> 'min-width:150px;'
            ),

            // Custom Subscription Product Generation Method Selection
            array(
                'id'            => WCCS_Option_Configuration::get_option_name( self::$PRODUCT_GENERATION_METHOD_OPTION_KEY ),
                'option_key'    => self::$PRODUCT_GENERATION_METHOD_OPTION_KEY,
                'name'          => 'Product Generation',
                'type'          => 'select',
                'desc'          => 'custom subscription content generation.',
                'desc_tip'      => 'Select how Custom Subscription product contents are generated.',
                'options'       => self::$product_generation_option_map,
                'default'       => 'WCCS_UISM_Random_Content_Generator',
                'css'           => 'min-width:50px;'
            ),

            // End Custom Subscription Settings Section
            array( 'type' => 'sectionend' )
        );

        return $wccs_settings;
    }
}