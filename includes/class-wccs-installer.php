<?php
/**
 * Class for grouping install/uninstall logic.
 *
 * @class       WCCS_Installer
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
 class WCCS_Installer {

    /**
     * Install logic for WCCS plugin.
     *
     * @since 1.0
     */
    public static function install() {
        WCCS_Logger()->info( "Attempting plugin installation.", __CLASS__ );
        self::install_settings();
        self::install_terms();
        self::install_tables();
        self::install_pages();
        WCCS_Logger()->info( "Successful plugin installation.", __CLASS__ );
    }

    /**
     * Install plugin settings.
     *
     * @since 1.0
     */
     private static function install_settings() {
         WCCS_Setting_Configuration::set_default_settings();
     }

    /**
     * Create all necessary Wordpress Terms for the plugin.
     *
     * @since 1.0
     */
     private static function install_terms() {
         // WooCommerce defines product types as Wordpress Terms.
         // Install the new Custom Subscription as a new Wordpress Term.
         if ( !get_term_by( 'name', WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME, 'product_type' ) ) {
             wp_insert_term( WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME, 'product_type' );
         }
     }

    /**
     * Create all necessary MySQL tables for the plugin.
     *
     * @since 1.0
     */
     private static function install_tables() {
         require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

         WCCS_UISM_Dao::prepare_uism_tables();

         dbDelta( WCCS_UISM_Dao::get_uisms_table_schema() );
         dbDelta( WCCS_UISM_Dao::get_uism_products_table_schema() );
     }

     /**
      * Create all WordPress pages that are supplied by the plugin.
      *
      * @since 1.0
      */
     private static function install_pages() {
         WCCS_Page_Configuration::create_pages();
     }
 }
