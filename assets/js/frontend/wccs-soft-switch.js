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

        $.post( wccs_soft_switch_params.ajax_url, data, function( response ) {
            window.location = wccs_soft_switch_params.redirect_url;
        });

        return true;
    });

});