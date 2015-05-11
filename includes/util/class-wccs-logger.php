<?php
/**
 * Simple logging wrapper used exclusivly by the plugin.
 * Log entries are logged to php-error.log file.
 *
 * @class       WCCS_Logger
 * @package     WooCommerceCustomSubscriptions/util
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_Logger {

	/**
	 * Log levels to represent the intent of a log entry.
	 * Automatically printed with log message.
	 */
	private static $LOG_LEVEL_INFO = 'INFO';
	private static $LOG_LEVEL_WARN = 'WARN';
	private static $LOG_LEVEL_ERROR = 'ERROR';

	/**
	 * @var The single instance of WCCS_Log_System class
	 * @since 1.0
	 */
	private static $_instance = null;

	/**
	 * The plugin header log entry header. Default is empty.
	 * @since 1.0
	 */
	private $plugin_name = '';

	/**
	 * Main WCCS_Log_System instance
	 *
	 * Ensures only one instance of WCCS_Log_System is loaded or can be loaded.
	 *
	 * @static
	 * @see WCCS_Log_System()
	 * @return WCCS_Log_System - main instance
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
	 * WCCS_Log_System constructor. Hidden for singleton implementation.
	 * 
	 * @since 1.0
	 */
	private function __construct() {
	}

	/**
	 * Set the plugin name that the logger will use in log entries.
	 *
	 * @since 1.0
	 */
	public function set_plugin_name( $plugin_name ) {
		$this->plugin_name = $plugin_name;
	}

	/**
	 * Log a message with log level "INFO". Intented for non-issue data logging.
	 *
	 * @since 1.0
	 */
	public function info( $message, $classname ) {
		$this->log( $message, self::$LOG_LEVEL_INFO, $classname );
	}

	/**
	 * Log a message with log level "WARN". Intented for logging non-fatal errors.
	 *
	 * @since 1.0
	 */
	public function warn( $message, $classname ) {
		$this->log( $message, self::$LOG_LEVEL_WARN, $classname );
	}

	/**
	 * Log a message with log level "ERROR". Intented for logging fatal errors.
	 *
	 * @since 1.0
	 */
	public function error( $message, $classname ) {
		$this->log( $message, self::$LOG_LEVEL_ERROR, $classname );
	}

	/**
	 * Build and print a log entry with given message, log_level and classname.
	 *
	 * @since 1.0
	 */
	private function log( $message, $log_level, $classname ) {
		error_log( $this->build_log_entry( $message, $log_level, $classname ) );
	}

	/**
	 * Build and return a log entry string. Log entries a sequence of log headers followed by the 
	 * provided log message. The log entry format we use is:
	 *
	 *        "[<PLUGIN_NAME_HEADER>][<CLASS_NAME_HEADER>][<LOG_LEVEL_HEADER>] message"
	 *
	 * Log timestamps are provided by the built in "error_log" function used to write logs.
	 *
	 * @since 1.0
	 */
	private function build_log_entry( $message, $log_level, $classname ) {
		$now = new DateTime();
		$plugin_header = '[' . $this->plugin_name . ']';
		$class_header = '[' . $classname . ']';
		$log_level_header = '[' . $log_level . ']';

		return $plugin_header . $class_header . $log_level_header . ' ' . $message;
	}
}

/**
 * Returns the main instance of WCCS_Log_System() to prevent the need to use globals.
 *
 * @return WCCS_Log_System - main instance.
 * @since 1.0
 */
function WCCS_Logger() {
	return WCCS_Logger::instance();
}
