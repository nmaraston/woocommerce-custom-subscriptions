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
	 * Similar to WooCommerce, templates are in the plugin's "templates" folder
	 * and theme overrides are used if provided in the
	 * /theme/woocommerce/custom-subscriptions directory.
	 *
	 * @param mixed $template
	 * @since 1.0
	 * @return string
	 */
	public static function fh_template_include( $template ) {
		$find = array( 'woocommerce-custom-subscriptions.php' );
		$file = "";
		$allow_override = true;

		if ( is_page( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) ) ) {
			$file = 'my-subscription/my-subscription.php';

			$find[] = $file;

			// Do not allow template overriding for the top level my-subscription template file.
			$allow_override = false;

			WCCS_Asset_Loader::enqueue_wccs_my_subscription_styles();
		} elseif ( is_page( WCCS_Page_Configuration::get_page_id( 'subscriptionselection' ) ) ) {
			$file = "pages/subscription-selection.php";

			$find[] = $file;
			$find[] = WCCS()->override_template_path() . $file;

			$allow_override = true;
		}

		if ( $file ) {
			if ( $allow_override ) {
				$template = locate_template( array_unique( $find ) );
				if ( ! $template ) {
					$template = WCCS()->default_template_path() . $file;
				}
			} else {
				$template = WCCS()->default_template_path() . $file;
			}
		}

		return $template;
	}
}
