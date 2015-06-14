<?php
/**
 * Template Loader
 *
 * @class 		WCCS_Template_Loader
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Template_Loader {

	/**
	 * Similar to class "WC_Template_Loader" provided by woocommerce.
	 *
	 * Here we override template loading to load our page templates.
	 *
	 * As in WC, templates are in the "templates" folder and theme overrides
	 * are used if provided in the /theme/woocommerce/ directory.
	 *
	 * @param mixed $template
	 * @since 1.0
	 * @return string
	 */
	public static function fh_template_include( $template ) {
		$find = array( "woocommerce-custom-subscriptions.php" );
		$file = "";
		$allow_override = true;

		if ( is_page( WCCS_Page_Configuration::get_page_id( "mysubscription" ) ) ) {

			// Do not allow template overriding for the top level my-subscription template file.
			$allow_override = false;
			$file = "my-subscription.php";
			$template = WCCS()->default_template_path() . 'my-subscription/' . $file;
			WCCS_Asset_Loader::enqueue_wccs_my_subscription_styles();
		}

		if ( $file && $allow_override ) {
			$template = locate_template( array_unique( $find ) );
			if ( ! $template ) {
				$template = WCCS()->default_template_path() . $file;
			}
		}

		return $template;
	}
}
