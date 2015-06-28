/**
 *
 */
jQuery( function( $ ) {

    // wccs_soft_switch_params is required to continue, ensure object exists
    if ( typeof wccs_soft_switch_params === 'undefined' ) {
        return false;
    }

    // AJAX soft switch
    $( document).on( 'click', '.wccs_soft_switch', function(e) {

        var $thisbutton = $( this );

        if ( ! $thisbutton.attr( 'data-product_id' ) ) {
            return true;
        }

        var data = {
            action: 'wccs_soft_switch'
        };

        $.each( $thisbutton.data(), function( key, value ) {
            data[key] = value;
        });

        var product_id = data['product_id'];

        $.post( wccs_soft_switch_params.ajax_url, data, function( response ) {

            var url = wccs_soft_switch_params.redirect_url;

            // Add 'add-to-cart' query param to redirect URL to trigger cart contents changes
            var sep = (url.indexOf('?') > -1) ? '&' : '?';
            var param_key = 'add-to-cart';
            var param_value = product_id;
            url += sep + param_key + '=' +  param_value;

            window.location = url;
        });

        return true;
    });

});
