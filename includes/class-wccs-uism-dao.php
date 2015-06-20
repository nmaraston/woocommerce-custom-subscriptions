<?php
/**
 * Data acess object for UISM.
 *
 * @class       WCCS_UISM_Dao
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_UISM_Dao {

    private static $RAW_UISMS_TABLE_NAME = "wccs_uisms";
    private static $RAW_UISM_PRODUCTS_TABLE_NAME = "wccs_uism_products";

    private $id = NULL;
    private $user_id = NULL;
    private $products = array();
    private $state = NULL;
    private $base_product = NULL;
    private $order_id = NULL;

    /**
     * Constructs a UISM with a given identifier.
     *
     * @param int $id
     * @since 1.0
     */
    public function __construct() {
    }

    /**
     * Set the UISM ID.
     *
     * @param int $id
     * @since 1.0
     */
    public function set_id( $id ) {
        $this->id = $id;
    }

    /**
     * Get the UISM ID.
     *
     * @return int
     * @since 1.0
     */
    public function get_id() {
        return $this->id;
    }

    /**
     * Set the user ID that owns the UISM.
     *
     * @param int $user_id
     * @since 1.0
     */
    public function set_user_id( $user_id ) {
        $this->user_id = $user_id;
    }

    /**
     * Get the user ID that owns the UISM.
     *
     * @return int
     * @since 1.0
     */
    public function get_user_id() {
        return $this->user_id;
    }

    /**
     * Set the UISM product at the given $slot number. If the $slot number is
     * out of bounds no action is taken.
     *
     * @param WC_Product $product
     * @param int $slot
     * @since 1.0
     */
    public function set_product_at_slot( $product, $slot ) {
        $wc_product = get_product( $product );
        if ( $wc_product && $this->is_valid_slot( $slot ) ) {
            $this->products[ $slot ] = $wc_product;
        }
    }

    /**
     * Get the UISM products.
     *
     * @return array( WC_Product )
     * @since 1.0
     */
    public function get_products() {
        return $this->products;
    }

    /**
     * Set the state of the UISM. See WCCS_UISM_State class.
     *
     * @param String $state
     * @since 1.0
     */
    public function set_state( $state ) {
        $this->state = $state;
    }

    /**
     * Get the state of the UISM. See WCCS_UISM_State class.
     *
     * @return String
     * @since 1.0
     */
    public function get_state() {
        return $this->state;
    }

    /**
     * Set the Custom Subscription base product that this UISM is an
     * instantiation of. Input can be a product (post) id or
     * WC_Product_Custom_Subscription instance. If the input can not identify a
     * WC_Product_Custom_Subscription then no action is taken.
     *
     * @param mix (int | WC_Product_Custom_Subscription) $product
     * @since 1.0
     */
    public function set_base_product( $product ) {
        $wc_product = get_product( $product );
        if ( $wc_product ) {
            $this->base_product = $wc_product;
        }
    }

    /**
     * Get the WC_Product_Custom_Subscription product that this UISM is an
     * instantiation of.
     *
     * @return WC_Product_Custom_Subscription
     * @since 1.0
     */
    public function get_base_product() {
        return $this->base_product;
    }

    /**
     * Set the id of the order that the Custom Subscription base product was
     * purchased in.
     *
     * @param int $order_id
     * @since 1.0
     */
    public function set_order_id( $order_id ) {
        $this->order_id = $order_id;
    }

    /**
     * Get the id of the order that the Custom Subscription base product was
     * purchased in.
     *
     * @return int
     * @since 1.0
     */
    public function get_order_id() {
        return $this->order_id;
    }

    /**
     * Get the number of products allowed to customize in this UISM. If no base
     * product is set, return false.
     *
     * @return mix (int | bool)
     * @since 1.0
     */
    public function get_product_count() {
        if ( is_null( $this->base_product ) ) {
            return false;
        }
        return $this->base_product->get_product_count();
    }

    /**
     * Return the subscription key associated with this UISM. The Woocommerce
     * Subscriptions plugin defines the subscription key to be:
     *
     * 	"$productid_$orderid"
     *
     * where $productid is the base Custom Subscription
     * product id and $orderid is the id of the purchase order that the Custom
     * Subscription product was purchased in. Return false if the UISM order id
     * is not set.
     *
     * @return mix (string | bool)
     * @since 1.0
     */
    public function get_subscription_key() {
        if ( is_null( $this->order_id ) ) {
            return false;
        }
        return $this->order_id . '_' . $this->base_product->id;
    }

    /**
     * Return true iff the given $slot number is valid. That is, it is within
     * the bounds of the UISM product count.
     *
     * @return bool
     * @since 1.0
     */
    private function is_valid_slot( $slot ) {
        $product_count = $this->get_product_count();
        if ( is_int( $slot ) && $slot >= 0 && $slot < $product_count ) {
            return true;
        }
        return false;
    }

    /**
     * Save this UISM. Returns true iff the UISM was successfully persisted to
     * the DB.
     *
     * @return bool
     * @since 1.0
     */
    public function save() {
        if ( $this->save_uism() ) {
            // If this is a new UISM, we need to load the id field to properly
            // save the products.
            if ( is_null( $this->id ) ) {
                global $wpdb;
                $selectors = array(
                    "user_id" => $this->user_id,
                    "product_id" => $this->base_product->id
                );
                $uism_row = $wpdb->get_row( self::build_get_uism_query( $selectors ), OBJECT );
                $this->set_id( $uism_row->id );
            }
            $this->save_products();
        } else {
            return false;
        }
        return true;
    }

    /**
     * Save this UISM in the wccs_uisms table. Return true iff the UISM was
     * successfully saved.
     *
     * @return bool
     * @since 1.0
     */
    private function save_uism() {
        if ( ! $this->can_save() ) {
            // UISM is in invalid state for saving
            return false;
        }

        global $wpdb;
        $table_name = self::get_uisms_table_name();
        $base_product_id = $this->base_product->id;
        $order_id = is_null( $this->order_id ) ? 'NULL' : $this->order_id;
        $wpdb->query(
            "
            INSERT INTO $table_name
            ( user_id, product_id, state, order_id )
            VALUES
            ( $this->user_id, $base_product_id, '$this->state', $order_id )
            ON DUPLICATE KEY UPDATE state = '$this->state', order_id = $order_id
            ;"
        );

        return true;
    }

    /**
     * Save this UISMs products in the wccs_uism_products table.
     *
     * @since 1.0
     */
    private function save_products() {
        if ( is_null( $this->products ) || empty( $this->products ) ) {
            return;
        }

        global $wpdb;

        foreach ( $this->products as $slot => $product ) {
            $wpdb->replace(
                self::get_uism_products_table_name(),
                array(
                        "uism_id"     => $this->id,
                        "slot_number" => $slot,
                        "product_id"  => $product->id
                ),
                array(
                        "%d",
                        "%d",
                        "%d"
                )
            );
        }
    }

    /**
     * Return true iff the UISM is in a valid state for saving. A UISM is valid
     * for saving if the following properties are non-null: $user_id,
     * $base_products, $state.
     *
     * @return bool
     * @since 1.0
     */
    public function can_save() {
        return ! ( is_null( $this->user_id ) &&
                   is_null( $this->base_product ) &&
                   is_null( $this->state ) );
    }

    /**
     * Get a UISM with a given $selectors array map. The $selectors correspond
     * to UISM attributes and it's key set should define a key in the wccs_uism
     * table as to uniquely specify a UISM.
     *
     * See method is_uism_key() for what is considered a valid key.
     *
     * Return a WCCS_UISM_Dao object or false if the selector key set does not
     * define a valid key.
     *
     * @param array $selectors
     * @return mix ( WCCS_UISM_Dao | bool )
     * @since 1.0
     */
    public static function get_uism( $selectors ) {
        if ( ! self::is_uism_key( array_keys( $selectors ) ) ) {
            return false;
        }

        global $wpdb;

        $uism_row = $wpdb->get_row( self::build_get_uism_query( $selectors ), OBJECT );

        if ( is_null( $uism_row ) ) {
            return NULL;
        }

        $uism_produts_table_name = self::get_uism_products_table_name();
        $product_rows = $wpdb->get_results(
            "
            SELECT *
            FROM $uism_produts_table_name
            WHERE uism_id = $uism_row->id
            ;", OBJECT );

        $uism = new WCCS_UISM_Dao();
        $uism->set_id( $uism_row->id );
        $uism->set_user_id( $uism_row->user_id );
        $uism->set_state( $uism_row->state );
        $uism->set_base_product( $uism_row->product_id );
        $uism->set_order_id( $uism_row->order_id );

        foreach ( $product_rows as $product_row ) {
            $uism->set_product_at_slot(
                get_product( $product_row->product_id ), intval( $product_row->slot_number ) );
        }

        return $uism;
    }

    /**
     * Returns true iff the given $key is a valid MySQL key for table wccs_uism.
     * See method get_uisms_table_schema() documentation for valid keys.
     *
     * @param array( string ) $key
     * @return bool
     * @since 1.0
     */
    public static function is_uism_key( $key ) {
        $has_id = in_array( "id", $key );
        $has_user_id = in_array( "user_id", $key );
        $has_product_id = in_array( "product_id", $key );
        $has_state = in_array( "state", $key );

        return ( $has_id || ( $has_user_id && $has_product_id ) || ( $has_user_id && $has_state) );
    }

    /**
     * Build a SELECT query against the UISMs table. The $selectors array
     * creates a WHERE clause.
     *
     * Example:
     *    $selectors == array( "user_id" => 1, "product_id" => 99 )
     *        ... translates to ...
     *    SELECT * FROM wccs_uisms WHERE user_id = 1 AND product_id = 99;
     *
     * @param array $selectors
     * @return string
     * @since 1.0
     */
    private static function build_get_uism_query( $selectors=array() ) {
        global $wpdb;

        $table_name = self::get_uisms_table_name();
        $query =
            "
            SELECT *
            FROM $table_name
            ";

        $selector_count = count( $selectors );

        if ( $selector_count > 0 ) {
            $query .= " WHERE";
            $index = 0;
            foreach ( $selectors as $column => $value ) {
                $query .= " " . $column . " = ";

                // Output quotes if the column date type is a string.
                if ( $column === "state" ) {
                    $query .= "'" . $value . "'";
                } else {
                    $query .= $value;
                }

                if ( $index != $selector_count - 1 ) {
                    $query .= " AND";
                }
                $index += 1;
            }
        }

        $query .= ";";

        return $query;
    }

    /**
     * Return the MySQL table name for the UISM table.
     *
     * @return string
     * @since 1.0
     */
    public static function get_uisms_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$RAW_UISMS_TABLE_NAME;
    }

    /**
     * Return the MySQL table name for the UISM products table.
     *
     * @return string
     * @since 1.0
     */
    public static function get_uism_products_table_name() {
        global $wpdb;
        return $wpdb->prefix . self::$RAW_UISM_PRODUCTS_TABLE_NAME;
    }

    /**
     * Performs preparation tasks that need occur before attempting to
     * update/install UISM tables.
     *
     * @since 1.0
     */
    public static function prepare_uism_tables() {
        self::prepare_uisms_table();
        self::prepare_uism_products_table();
    }

    /**
     * Performs preparation tasks that need occur before attempting to
     * update/install the uisms SQL table.
     *
     * @since 1.0
     */
    private static function prepare_uisms_table() {
        global $wpdb;
        $table_name = self::get_uisms_table_name();

        // Remove unique keys due to dbDelta's failure to update existing keys
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) ) {
            $wpdb->query( "ALTER TABLE $table_name DROP INDEX user_product" );
            $wpdb->query( "ALTER TABLE $table_name DROP INDEX user_state" );
        }
    }

    /**
     * Performs preparation tasks that need occur before attempting to
     * update/install the uisms_products SQL table.
     *
     * @since 1.0
     */
    private static function prepare_uism_products_table() {
        global $wpdb;
        $table_name = self::get_uism_products_table_name();

        // Remove unique keys due to dbDelta's failure to update existing keys
        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name';" ) ) {
            $wpdb->query( "ALTER TABLE $table_name DROP INDEX uism_slot" );
        }
    }

    /**
     * Return the UISM table schema.
     *
     * The UISM table stores UISMs. Each row in the table represents a user
     * instantiated custom suscription.
     *
     * Table keys: (id), (user_id, product_id), (user_id, state)
     *
     * @return string
     * @since 1.0
     */
    public static function get_uisms_table_schema() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = self::get_uisms_table_name();

        $sql_schema =
            "
            CREATE TABLE {$table_name} (
                id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id BIGINT(20) UNSIGNED NOT NULL,
                product_id BIGINT(20) UNSIGNED NOT NULL,
                state ENUM('ACTIVE_NONBILLING', 'ACTIVE_BILLING', 'INACTIVE'),
                order_id BIGINT(20) UNSIGNED,
                PRIMARY KEY  (id),
                UNIQUE KEY user_product (user_id, product_id),
                UNIQUE KEY user_state (user_id, state)
            ) $charset_collate;
            ";

        return $sql_schema;
    }

    /**
     * Return the UISM products table schema.
     *
     * The UISM products table stores the contents (selected products) for the
     * user's custom subscription.
     *
     * Table key: (uism_id, slot_number)
     *
     * @return string
     * @since 1.0
     */
    public static function get_uism_products_table_schema() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = self::get_uism_products_table_name();

        $sql_schema =
            "
            CREATE TABLE {$table_name} (
                uism_id BIGINT(20) UNSIGNED NOT NULL,
                slot_number TINYINT(8) UNSIGNED NOT NULL,
                product_id BIGINT(20) UNSIGNED NOT NULL,
                UNIQUE KEY uism_slot (uism_id, slot_number)
            ) $charset_collate;
            ";

        return $sql_schema;
    }
}
