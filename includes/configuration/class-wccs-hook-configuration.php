<?php
/**
 * Hook configuration class. Used to wire ALL actions and filters in the plugin.
 * Implements the singleton design pattern.
 *
 * @class       WCCS_Hook_Configuration
 * @package     WooCommerceCustomSubscriptions/configuration
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Hook_Configuration {

	/**
	 * Hook types. Passed to wire_hook() to request what type of hook to wire.
	 */
	private static $ACTIVIATION_HOOK_TYPE = 'activation';
	private static $DEACTIVIATION_HOOK_TYPE = 'deactivation';
	private static $ACTION_HOOK_TYPE = 'action';
	private static $FILTER_HOOK_TYPE = 'filter';

	/**
	 * @var The single instance of WCCS_Hook_Configuration class
	 * @since 1.0
	 */
	private static $_instance = null;

	/**
	 * Main WCCS_Hook_Configuration class
	 *
	 * Ensures only one instance of WCCS_Hook_Configuration class is loaded or can be loaded.
	 *
	 * @static
	 * @see WCCS_Hook_Configuration()
	 * @return WCCS_Hook_Configuration - main instance
	 * @since 1.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'woocommerce-custom-subscriptions' ), '1.0' );
	}

	/**
	 * WCCS_Hook_Configuration Constructor. Hidden for singleton implementation.
	 * 
	 * @since 1.0
	 */
	private function __construct() {
	}

	/**
	 * Wire plugin activation and deactiviation hook
	 *
	 * @since 1.0
	 */
	public function wire_activation_hooks( $plugin_file_path ) {
		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
		WCCS_Logger()->info( "Wiring activation hooks:", __CLASS__ );

		// Trigger install logic on plugin activation
		$this->wire_hook( self::$ACTIVIATION_HOOK_TYPE,
			$plugin_file_path, 'WCCS_Installer', 'install' );


		// Trigger uninstall logic on plugin deactivation
		$this->wire_hook( self::$DEACTIVIATION_HOOK_TYPE,
			$plugin_file_path, 'WCCS_Installer', 'uninstall' );

		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
	}


	/**
	 * Wire ALL Wordpress Actions used throughout the plugin.
	 * Wordpress function "add_action" should never be called anywhere else.
	 *
	 * @since 1.0
	 */
	public function wire_action_hooks() {
		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
		WCCS_Logger()->info( "Wiring action hooks:", __CLASS__ );

		/*******************************************************
		 * Init Wordpress core action hooks.
		 *******************************************************/

		// Called via Wordpress once any activated plugins have been loaded. Use high priority argument (999) to ensure
		// handler fires after WooCommerceSubscriptions plugin handles action.
		$this->wire_hook( self::$ACTION_HOOK_TYPE,
			'plugins_loaded',
			'WCCS_Product_Custom_Subscription_Helper', 'ah_plugins_loaded', 999, 0 );

		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
	}

	/**
	 * Wire ALL Wordpress Filters used throughout the plugin.
	 * Wordpress function "add_filter" should never be called anywhere else.
	 *
	 * @since 1.0
	 */
	public function wire_filter_hooks() {
		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
		WCCS_Logger()->info( "Wiring filter hooks:", __CLASS__ );

		/*******************************************************
		 * Init WooCommerce Plugin filter hooks.
		 *******************************************************/

		// Called via WooCommerce when resolving a product-type (term name) to a product class.
		$this->wire_hook( self::$FILTER_HOOK_TYPE,
			'woocommerce_product_class',
			'WCCS_Product_Custom_Subscription_Helper', 'fh_woocommerce_product_class', 10, 4 );

		/*******************************************************
		 * Init WooCommerce Subscription Plugin filter hooks.
		 *******************************************************/

		// Called when WooCommerce Subscriptions resolves a list of valid WC Subcription product types.
		$this->wire_hook( self::$FILTER_HOOK_TYPE,
			'woocommerce_subscription_product_types',
			'WCCS_Product_Custom_Subscription_Helper', 'fh_woocommerce_subscription_product_types', 10, 1 );

		// Called when WooCommerce Subscriptions is checking if a WC Product is of some Subscription product type.
		$this->wire_hook( self::$FILTER_HOOK_TYPE,
			'woocommerce_is_subscription',
			'WCCS_Product_Custom_Subscription_Helper', 'fh_woocommerce_is_subscription', 10, 1 );

		WCCS_Logger()->info( "-----------------------------------------", __CLASS__ );
	}

	/**
	 * Wire a hook. This function is essentially a wrapper around wordpress functions 'add_action' and 'add_filter' with
	 * added logging.
	 *
	 * see: https://codex.wordpress.org/Function_Reference/add_action
	 *      https://codex.wordpress.org/Function_Reference/add_filter
	 *
	 * @param string $hook_type      - type of hook ('action' or 'filter')
	 * @param string $hook_name      - name of hook
	 * @param string $classname      - name of class with hook handler function
	 * @param int    $priority       - Used to specify the order in which the functions associated with a particular
	 *                                 action are executed.
	 * @param int    $argument_count - number of arguments the function accepts
	 */
	private function wire_hook( $hook_type, $hook_name, $classname, $fn_handler, $priority=10, $argument_count=1 ) {
		switch ( $hook_type ) {
			case self::$ACTIVIATION_HOOK_TYPE:
				$this->log_hook_wire( $hook_type, $hook_name, $classname, $fn_handler );
				register_activation_hook( $hook_name, $classname . '::' . $fn_handler );
				break;
			case self::$DEACTIVIATION_HOOK_TYPE:
				$this->log_hook_wire( $hook_type, $hook_name, $classname, $fn_handler );
				register_deactivation_hook( $hook_name, $classname . '::' . $fn_handler );
				break;
			case self::$ACTION_HOOK_TYPE:
				$this->log_hook_wire( $hook_type, $hook_name, $classname, $fn_handler, $priority, $argument_count );
				add_action( $hook_name, $classname . '::' . $fn_handler, $priority, $argument_count );
				break;
			case self::$FILTER_HOOK_TYPE:
				$this->log_hook_wire( $hook_type, $hook_name, $classname, $fn_handler, $priority, $argument_count );
				add_filter( $hook_name, $classname . '::' . $fn_handler, $priority, $argument_count );
				break;
			default:
				WCCS_Logger()->warn( "Unable to wire hook for unknown hook type: " . $hook_type );
		}
	}

	/**
	 * Log hook wiring metadata.
	 *
	 * @since 1.0
	 */
	private function log_hook_wire( $hook_type, $hook_name, $classname, $fn_handler, $priority=10, $argument_count=1 ) {
		$log_msg = "Wiring " . $hook_type . " hook:";

		if ( $hook_type === self::$ACTIVIATION_HOOK_TYPE || $hook_type === self::$DEACTIVIATION_HOOK_TYPE ) {
			$handler_meta_tuple = "( " . $classname . "::" . $fn_handler . " )";
			$log_msg = $log_msg . " " . $handler_meta_tuple;
		} else {
			$hook_meta_tuple = "( " . $hook_name . " )";
			$handler_meta_tuple = "( " . $classname . "::" . $fn_handler . ", " . $priority . ", " . $argument_count . " )";
			$log_msg = $log_msg . " " . $hook_meta_tuple . " --> " . $handler_meta_tuple;
		}

		WCCS_Logger()->info( $log_msg, __CLASS__ );
	}
}

/**
 * Returns the main instance of WCCS_Hook_Configuration.
 *
 * @since 1.0
 * @return WCCS_Hook_Configuration - main instance.
 */
function WCCS_Hook_Configuration() {
	return WCCS_Hook_Configuration::instance();
}