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
    public static function fh_template_include($template)
    {
        $find = array('woocommerce-custom-subscriptions.php');
        $file = "";
        $allow_override = true;

        if ( is_page( WCCS_Page_Configuration::get_page_id( 'mysubscription' ) ) ) {
            $file = 'pages/my-subscription/my-subscription.php';

            $find[] = $file;
            $find[] = WCCS()->override_template_path() . $file;

            $allow_override = true;

            // Load product update script. Redirect to My Subscription page
            // after product update click
            WCCS_Asset_Loader::enqueue_wccs_update_product_script(
                WCCS_Page_Configuration::get_page_link( 'mysubscription' ) );

        } else if ( is_page( WCCS_Page_Configuration::get_page_id( 'subscriptionselection' ) ) ) {
            $file = 'pages/subscription-selection.php';

            $find[] = $file;
            $find[] = WCCS()->override_template_path() . $file;

            $allow_override = true;

            // Load UISM soft switch script. Redirect to My Subscription page
            // after soft switch click
            WCCS_Asset_Loader::enqueue_wccs_soft_switch_script(
                WCCS_Page_Configuration::get_page_link( 'mysubscription' ) );

        } else if ( is_page( WCCS_Page_Configuration::get_page_id( 'subscriptionconfirmation' ) ) ) {
            $file = 'pages/subscription-confirmation.php';

            $find[] = $file;
            $find[] = WCCS()->override_template_path() . $file;

            $allow_override = true;

            // Load UISM soft switch script. Redirect to Subscription Update
            // Thank You page after soft switch click
            WCCS_Asset_Loader::enqueue_wccs_soft_switch_script(
                WCCS_Page_Configuration::get_page_link('subscriptionthankyou'));

        } else if ( is_page( WCCS_Page_Configuration::get_page_id( 'subscriptionthankyou' ) ) ) {
            $file = 'pages/subscription-thankyou.php';

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

    /**
     * Inject the Custom Subscription product update box template above the
     * single product display.
     *
     * @since 1.0
     */
    public static function ah_woocommerce_before_single_product() {
        // Load product update script. Redirect to My Subscription page
        // after product update click
        WCCS_Asset_Loader::enqueue_wccs_update_product_script(
            WCCS_Page_Configuration::get_page_link( 'mysubscription' ) );
        wccs_get_template( 'custom-subscription-update-box.php' );
    }
}
