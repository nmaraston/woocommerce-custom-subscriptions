/**
 *
 */
jQuery( function( $ ) {

    // wccs_update_product_params is required to continue, ensure object exists
    if ( typeof wccs_update_product_params === 'undefined' ) {
        return false;
    }

    // AJAX update custom subscription product
    $( document ).on( 'click', '.wccs_update_product', function(e) {

        var $thisbutton = $( this );

        if ( ! $thisbutton.attr( 'data-product_id' ) ) {
            return true;
        }

        var data = {
            action: 'wccs_update_product'
        };

        $.each( $thisbutton.data(), function( key, value ) {
            data[key] = value;
        });

        $.post( wccs_update_product_params.ajax_url, data, function( response ) {
            window.location = wccs_update_product_params.redirect_url;
        });

        return true;
    });

});
