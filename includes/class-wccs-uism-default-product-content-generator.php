<?php
/**
 * A UISM Content Generator that generates products uniformly based on a set
 * product default. The default product can be set in the Admin Subscription
 * settings tab UI.
 *
 * @class       WCCS_UISM_Random_Content_Generator
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_UISM_Default_Product_Content_Generator implements WCCS_UISM_I_Content_Generator {

    /**
     * Generates product contents uniformly based on a set product default. If
     * the product default is not set, return false.
     *
     * @return mix ( array | bool )
     * @since 1.0
     */
    public function generate_products( $user_id, $product_id ) {
        $default_product_id = WCCS_Option_Configuration::get_option(
            WCCS_Setting_Configuration::$DEFAULT_PRODUCT_ID_OPTION_KEY
        );

        if ( ! $default_product_id ) {
            return false;
        }

        $default_product = get_product( $default_product_id );

        $products = array();
        $base_product = get_product( $product_id );
        for ( $index = 0; $index < $base_product->get_product_count(); $index++ ) {
            $products[] = $default_product;
        }

        return $products;
    }
}