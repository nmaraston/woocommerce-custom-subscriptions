<?php
/**
 * UI interface for display a Custom Subscription product count.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

global $product;

if ( !$product->is_type( array( WCCS_Product_Custom_Subscription_Helper::$PRODUCT_TYPE_NAME ) ) ) {
    return;
}

?>

<span class="custom-subscription-product-count">
    Contains <?php echo $product->get_product_count(); ?> customizable items.
</span>
