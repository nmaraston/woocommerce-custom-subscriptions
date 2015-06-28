<?php
/**
 * The Script Loader manages all script assets for the plugin. It is in charge
 * of declaring correct script dependencies and enqeueing all scripts.
 *
 * @class       WCCS_Script_Loader
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Asset_Loader {

    // Relative path to plugin root containing all scripts.
    private static $SCRIPTS_DIR = 'assets/js';

    // Relative path to plugin root containing all styles.
    private static $STYLES_DIR = 'assets/css';

    /**
     * Called via Wordpress to enqeue additional assets for admin pages.
     * Here we enqueue the 'wccs_product_creation_form.js' Javascript asset
     * which is responsible for displaying the Custom Subscription product
     * creation form in the "Add Product" page.
     *
     * @since 1.0
     */
    public static function ah_admin_enqueue_scripts() {
        $screen = get_current_screen();
        $is_wc_add_product_screen = ( $screen->id === 'product' ) ? 1 : 0;

        if ( $is_wc_add_product_screen ) {
            self::enqueue_wccs_product_creation_script();
            self::enqueue_wccs_product_creation_style();
        }
    }

    /**
     * Enqueue the 'wccs_product_creation_form.js' script. A dependency
     * should be declared on the top level WooCommerce Subscriptions plugin
     * admin script.
     *
     * @since 1.0
     */
    public static function enqueue_wccs_product_creation_script() {
        $script = self::$SCRIPTS_DIR . '/admin/wccs_product_creation_form.js';
        $script_handle = 'wccs_product_creation_form_script';
        $script_dependencies = array( 'woocommerce_subscriptions_admin' );

        self::enqueue_wccs_script( $script, $script_handle, $script_dependencies, null );
    }

    /**
     * Enqueue the 'wccs-update-product.js' script. The given $redirect URL
     * indicates which link the string redirects to post product update.
     *
     * @param string $redirect url
     * @since 1.0
     */
    public static function enqueue_wccs_update_product_script( $redirect_url ) {
        $script = self::$SCRIPTS_DIR . '/frontend/wccs-update-product.js';
        $script_handle = 'wccs_update_product_script';
        $script_dependencies = array( 'jquery' );

        $params = array(
            'name' => 'wccs_update_product_params',
            'data' => array(
                'ajax_url'     => admin_url( 'admin-ajax.php' ),
                'redirect_url' => $redirect_url
            )
        );

        self::enqueue_wccs_script( $script, $script_handle, $script_dependencies, $params );
    }

    /**
     * Enqueue the 'wccs-soft-upgrade.js' script. The given $redirect URL
     * indicates which link the string redirects to post product update.
     *
     * @param $redirect_url
     * @since 1.0
     */
    public static function enqueue_wccs_soft_upgrade_script( $redirect_url ) {
        $script = self::$SCRIPTS_DIR . '/frontend/wccs-soft-upgrade.js';
        $script_handle = 'wccs_soft_upgrade_script';
        $script_dependencies = array( 'jquery' );

        $params = array(
            'name' => 'wccs_soft_upgrade_params',
            'data' => array(
                'ajax_url'     => admin_url( 'admin-ajax.php' ),
                'redirect_url' => $redirect_url
            )
        );

        self::enqueue_wccs_script( $script, $script_handle, $script_dependencies, $params );
    }

    /**
     * Enqueue the given script. $script_path is the relative path (relative to
     * plugin root) of the script. $handle is the script handle to be used in
     * WordPress.
     *
     * @param string $path
     * @param string $handle
     * @param array( string ) $dependencies
     * @param array(
     *            name: string,
     *            data: array(
     *                key: value
     *            )
     *        )
     * @since 1.0
     */
    private static function enqueue_wccs_script( $script_path, $handle, $dependencies, $params ) {
        $url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $script_path;
        $version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $script_path );

        wp_enqueue_script( $handle, $url, $dependencies, $version );

        if ( ! is_null( $params ) ) {
            wp_localize_script( $handle, $params['name'], $params['data']);
        }
    }

    /**
     * Enqueue the 'wccs_product_creation_form.css' styles. The CSS styles are
     * depedent on the WooCommerce Subscriptions plugin admin styles.
     *
     * @since 1.0
     */
    public static function enqueue_wccs_product_creation_style() {
        $style = self::$STYLES_DIR . '/wccs_product_creation_form.css';
        $style_handle = 'wccs_product_creation_form_style';
        $style_url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $style;
        $style_dependencies = array( 'woocommerce_subscriptions_admin' );
        $style_version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $style );

        wp_enqueue_style( $style_handle, $style_url, $style_dependencies, $style_version );
    }

    /**
     *
     *
     * @since 1.0
     */
    public static function enqueue_wccs_my_subscription_styles() {
        $style = self::$STYLES_DIR . '/wccs_my_subscription.css';
        $style_handle = 'wccs_my_subscription_style';
        $style_url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $style;
        $style_dependencies = array();
        $style_version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $style );

        wp_enqueue_style( $style_handle, $style_url, $style_dependencies, $style_version );
    }

    /**
     *
     *
     * @since 1.0
     */
    public static function enqueue_wccs_manage_subscription_styles() {
        $style = self::$STYLES_DIR . '/wccs_manage_subscription.css';
        $style_handle = 'wccs_manage_subscription';
        $style_url = plugin_dir_url( WC_Custom_Subscriptions::$plugin_file ) . $style;
        $style_dependencies = array();
        $style_version = filemtime( plugin_dir_path( WC_Custom_Subscriptions::$plugin_file ) . $style );

        wp_enqueue_style( $style_handle, $style_url, $style_dependencies, $style_version );
    }
}
