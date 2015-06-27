/**
 * This script adds support for the existing add/edit product form fields - provided by the WooCommerce
 * Subscriptions plugin - to be displayed when editing a Custom Subscription product type.
 */

jQuery(document).ready(function($){

    $.extend({
        showHideCustomSubscriptionMeta: function(){
            if ($('select#product-type').val()=='custom-subscription') {
                $('.show_if_simple').show();
                $('.show_if_subscription').show();
                $('.grouping_options').hide();
                $('.options_group.pricing ._regular_price_field').hide();
                $('#sale-price-period').show();
            } else {
                $('.options_group.pricing ._regular_price_field').show();
                $('#sale-price-period').hide();
            }
        }
    });

    if($('.options_group.pricing').length > 0) {
        $.showHideCustomSubscriptionMeta();
    }

    $('body').bind('woocommerce-product-type-change',function(){
        $.showHideCustomSubscriptionMeta();
    });
});
