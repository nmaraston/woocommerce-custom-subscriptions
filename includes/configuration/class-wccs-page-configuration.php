<?php
/**
 * Page configuration class. Manages pages created by the plugin.
 *
 * @class       WCCS_Page_Configuration
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Page_Configuration {

	/**
	 * The page configuration map defines all pages that are installed
	 * with the WCCS plugin.
	 */
	private static $page_config_map = array(
		'mysubscription' => array(
			'name'  => 'my-subscription',
			'title' => 'My Subscription',
			'content' => ''
		),
		'subscriptionselection' => array(
			'name' => 'subscription-selection',
			'title' => 'Subscription Selection',
			'content' => ''
		)
	);

	/** 
	 * Create all pages that come with the WCCS plugin.
	 *
	 * @since 1.0
	 */
	public static function create_pages() {
		foreach ( self::$page_config_map as $key => $page_meta ) {
			wc_create_page( 
				esc_sql( $page_meta['name'] ), 
				'wccs_' . $key . '_page_id', 
				$page_meta['title'], 
				$page_meta['content']
			);
		}
	}

	/**
	 * Given a page name (as defined in the $page_config_map), get the page id
	 * (post id of the page).
	 */
	public static function get_page_id( $page_name ) {
		return get_option( 'wccs_' . $page_name . '_page_id' );
	}
}