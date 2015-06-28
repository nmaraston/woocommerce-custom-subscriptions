/**
 *
 */
jQuery( function( $ ) {

    // wccs_soft_upgrade_params is required to continue, ensure object exists
    if ( typeof wccs_soft_upgrade_params === 'undefined' ) {
        return false;
    }

    // AJAX soft upgrade
    $( document).on( 'click', '.wccs_soft_upgrade', function(e) {

        var $thisbutton = $( this );

        if ( ! $thisbutton.attr( 'data-product_id' ) ) {
            return true;
        }

        var data = {
            action: 'wccs_soft_upgrade'
        };

        $.each( $thisbutton.data(), function( key, value ) {
            data[key] = value;
        });

        $.post( wccs_soft_upgrade_params.ajax_url, data, function( response ) {
            window.location = wccs_soft_upgrade_params.redirect_url;
        });

        return true;
    });

});