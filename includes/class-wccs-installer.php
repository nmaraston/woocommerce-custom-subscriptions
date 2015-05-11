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
 		self::install_wp_term_dependencies();
 		$logger->info( "Successful plugin installation.", __CLASS__ );
 	}

 	/**
 	 * Uninstall logic for WCCS plugin.
 	 * 
 	 * @since 1.0
 	 */
 	public static function uninstall() {
 		WCCS_Logger()->info( "Attempting plugin uninstallation.", __CLASS__ );
 		self::uninstall_wp_term_dependencies();
 		WCCS_Logger()->info( "Successful plugin uninstallation.", __CLASS__ );
 	}

 	/**
 	 * Create all necessary Wordpress Terms for the plugin.
 	 *
 	 * @since 1.0
 	 */
 	private static function install_wp_term_dependencies() {
 		// WooCommerce defines product types as Wordpress Terms.
 		// Install the new Custom Subscription as a new Wordpress Term.
 		if ( !get_term_by( 'name', WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME, 'product_type' ) ) {
 			wp_insert_term( WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME, 'product_type' );
 		}
 	}

 	/**
 	 * Remove all created Wordpress Terms upon plugin installation.
 	 *
 	 * @since 1.0
 	 */
 	private static function uninstall_wp_term_dependencies() {
 		// Delete the Custom Subscription product type Wordpress Term.
 		$term = get_term_by( 'name', WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME, 'product_type' );
 		if ( $term ) {
 			$term_id = $term->term_id;
 			wp_delete_term( $term_id, 'product_type' );
 		}
 	}
 }
