<?php
/**
 * URL listener. Listens for configured query variables and/or URL patterns.
 *
 * @class 		WCCS_URL_Listener
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_URL_Listener {

    /**
     * @var array defines all custom query parameters that the plugin listens
     *            for.
     */
    private static $wccs_url_param_route_map = array(
        'GET' => array(
            array(
                'query_pattern' => '/updateproduct=(\d+)-(\d+)/',
                'arg_count'     => '2',
                'handler'       => 'update_product_handler'
            )
        )
    );

    /**
     * Action handle to handle the 'parse_request' WP action. Here, we
     * trigger the plugin router to act on plugin defined query parameters.
     *
     * @since 1.0
     */
    public static function ah_parse_request( $query ) {
        self::route_url();
    }

    /**
     * Router for custom URL parameter's that the plugin listens for.
     *
     * @since 1.0
     */
    private static function route_url() {
        $request_method = $_SERVER['REQUEST_METHOD'];
        $request_uri = $_SERVER["REQUEST_URI"];

        if ( ! isset( self::$wccs_url_param_route_map[$request_method] ) ) {
            return;
        }

        foreach ( self::$wccs_url_param_route_map[$request_method] as $url_entry ) {

            $params = array();
            $matches = array();

            // Check for url match
            if ( preg_match( $url_entry['query_pattern'], $request_uri, $matches ) ) {

                // Validate that the URL has the number of expected parameters
                if ( count( $matches ) === $url_entry['arg_count'] + 1 ) {

                    // Build parameter list
                    for ( $index = 1; $index < count( $matches ); $index++ ) {
                        $params[ $index - 1 ] = $matches[ $index ];
                    }

                    // Call handler
                    call_user_func( __CLASS__ . '::' . $url_entry['handler'], $params );


                    // Return. Only take action on the first match
                    return;
                }
            }
        }
    }

    /**
     * Handler to handle the query parameter "updateproduct".
     *
     * @since 1.0
     */
    private static function update_product_handler( $params ) {
        if ( count( $params ) !== 2 ) {
            return false;
        }

        if ( ! is_numeric( $params[0] ) || ! is_numeric( $params[1] ) ) {
            return false;
        }

        $user_id = get_current_user_id();
        $product_id = intval( $params[0] );
        $product_slot = intval( $params[1] );

        return WCCS_UISM_Manager::uism_update_product( $user_id, $product_id, $product_slot );
    }
}
