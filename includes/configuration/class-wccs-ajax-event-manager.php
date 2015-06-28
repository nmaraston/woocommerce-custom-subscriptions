<?php
/**
 * Configures and acts as a first responder for all ajax events.
 *
 * @class 		WCCS_AJAX_Event_Manager
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_AJAX_Event_Manager {

    /**
     * AJAX event configuration table.
     *     wccs_EVENT => nopriv (for users that are not logged in)
     *
     * @var array
     * @since 1.0
     */
    private static $ajax_event_table = array(
        'update_product' => false,
        'soft_switch'    => false
    );

    /**
     * Initialize AJAX event handlers.
     *
     * @since 1.0
     */
    public static function init_handlers() {
        foreach ( self::$ajax_event_table as $ajax_event => $nopriv ) {
            add_action( 'wp_ajax_wccs_' . $ajax_event, __CLASS__ . '::' . $ajax_event );

            if ( $nopriv ) {
                add_action( 'wp_ajax_nopriv_wccs_' . $ajax_event, __CLASS__ . '::' . $ajax_event );
            }
        }
    }

    /**
     * Update product AJAX event handler.
     *
     * @since 1.0
     */
    public static function update_product() {
        ob_start();

        $error = false;

        if ( ! isset( $_POST['product_id'] ) || ! is_numeric( $_POST['product_id'] ) ) {
            $error = true;
        }

        if ( ! isset( $_POST['slot_number'] ) || ! is_numeric( $_POST['slot_number'] ) ) {
            $error = true;
        }

        if ( ! $error ) {
            $product_id = intval( $_POST['product_id'] );
            $slot_number = intval( $_POST['slot_number'] );

            $user_id = get_current_user_id();

            $error = ( ! WCCS_UISM_Manager::uism_update_product( $user_id, $product_id, $slot_number ) );
        }

        $response_data = array(
            'error' => $error
        );

        wp_send_json( $response_data );
    }

    /**
     * Soft switch AJAX event handler. Used to upgrade/downgrade UISMs.
     *
     * @since 1.0
     */
    public static function soft_switch() {
        ob_start();

        $error = false;

        if ( ! isset( $_POST['product_id'] ) || ! is_numeric( $_POST['product_id'] ) ) {
            $error = true;
        }

        if ( ! $error ) {
            $product_id = intval( $_POST['product_id'] );

            $user_id = get_current_user_id();

            $error = ( ! WCCS_UISM_Manager::uism_switch( $user_id, $product_id ) );
        }

        $response_data = array(
            'error' => $error
        );

        wp_send_json( $response_data );
    }
}
