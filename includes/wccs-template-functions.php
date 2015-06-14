<?php
/**
 * WooCommerce Custom Subscriptions functions for the templating system.
 *
 * @package     WooCommerceCustomSubscriptions/configuration
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

/**
 * Load a template with preference to the template override directory. Templates
 * that are applicable for override are searched for in the
 * <THEME ROOT>/woocommerce/custom-subscriptions folder of the theme.
 *
 * Templates that are applicable for ovveride are stated as overridable in the
 * default template source.
 *
 * @since 1.0
 */
function wccs_get_template( $template_name ) {
    wc_get_template( $template_name, array(), 
        WCCS()->override_template_path(), WCCS()->default_template_path() );
}
