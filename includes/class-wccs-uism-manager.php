<?php
/**
 * The WCCS_UISM_Manager class contains all logic for UISM workflows and state 
 * changes. Example: UISM sign-up, upgrading, cancelation etc.
 *
 * @class       WCCS_UISM_Manager
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_UISM_Manager {

    /**
     * Sign-up user $user_id with a new UISM based on the
     * WC_Product_Custom_Subscription identified by $product_id.
     * The default state of the UISM is ACTIVE_NONBILLING. Returns true iff the
     * UISM was successfully created.
     *
     * @param int $user_id
     * @param int $product_id
     * @return bool
     * @since 1.0
     */
    public static function uism_sign_up( $user_id, $product_id ) {
        if ( ! WCCS_Product_Custom_Subscription_Helper::is_custom_subscription( $product_id ) ) {
            return false;
        }

        $uism = self::get_active_uism( $user_id );

        if ( $uism ) {
            // If any active UISM already exists, abort sign-up.
            return false;
        }

        $uism = WCCS_UISM_Dao::get_uism(
            array( "user_id" => $user_id, "product_id" => $product_id ) );

        if ( $uism ) {
            // Our check for an active UISM above should imply that this UISM is
            // inactive. As above, if it is not, abort sign-up. If the UISM is
            // INACTIVE, reactivate it and use it's product contents.
            if ( $uism->get_state() === WCCS_UISM_State::$ACTIVE_NONBILLING ||
                 $uism->get_state() === WCCS_UISM_State::$ACTIVE_BILLING ) {
                return false;
            }
        } else {
            $uism = new WCCS_UISM_Dao();
        }

        $uism->set_base_product( $product_id );
        $uism->set_user_id( get_current_user_id() );
        $uism->set_state( WCCS_UISM_State::$ACTIVE_NONBILLING );

        $curr_products = $uism->get_products();
        if ( empty( $curr_products ) ) {
            $products = self::generate_uism_products( $user_id, $product_id);

            for ( $index = 0; $index < count( $products ); $index++ ) {
                $uism->set_product_at_slot( $products[$index], $index );
            }
        }

        return $uism->save();
    }

    /**
     * Update the given user's ($user_id) active UISM product identified by
     * $product_id at the given $product_slot number. The user must have an
     * active UISM.
     *
     * Return true iff the product update was successfully persisted to the DB.
     *
     * @param int $user_id
     * @param int $product_id
     * @param int $product_slot
     * @return bool
     * @since 1.0
     */
    public static function uism_update_product( $user_id, $product_id, $product_slot ) {
        $uism = self::get_active_uism( $user_id );

        if ( ! $uism ) {
            return false;
        }

        $wc_product = get_product( $product_id );
        if ( ! $wc_product->is_type( 'simple' ) ) {
            return false;
        }

        if ( $product_slot  < 0 || $product_slot >= $uism->get_product_count() ) {
            return false;
        }

        $uism->set_product_at_slot( $wc_product, $product_slot );

        return $uism->save();
    }

    /**
     * Upgrade/downgrade a user's subscription to the Custom Subscription
     * identified by the given $product_id.
     *
     * @since 1.0
     */
    public static function uism_switch( $user_id, $product_id ) {
        if ( ! WCCS_Product_Custom_Subscription_Helper::is_custom_subscription( $product_id ) ) {
            return false;
        }

        $old_uism = self::get_active_uism( $user_id );

        if ( ! $old_uism ) {
            // User does not have an active UISM. Nothing to upgrade/downgrade.
            return false;
        }

        if ( $old_uism->get_base_product()->id === $product_id ) {
            // User's UISM is already based on given $product_id
            return false;
        }

        // Invalidate the current user's UISM
        $old_uism->set_state( WCCS_UISM_State::$INACTIVE );
        $old_uism->save();

        // Sign up new UISM
        if ( ! self::uism_sign_up( $user_id, $product_id ) ) {
            return false;
        }
        $new_uism = self::get_active_uism( $user_id );

        // Prefix the new UISM's product list with the initial UISM products
        $old_products = $old_uism->get_products();
        for ( $index = 0; $index < min( $old_uism->get_product_count(), $new_uism->get_product_count() ); $index++ ) {
            $new_uism->set_product_at_slot( $old_products[$index], $index);
        }

        return $new_uism->save();
    }

    /**
     * Apply checkout logic to the UISM identified by the given $user_id. The
     * UISM must be in a ACTIVE_NONBILLING state before being processed by
     * checkout. On Custom Subscription checkout, the UISM state is set to
     * ACTIVE_BILLING and associated with the given $order_id.
     *
     * Return's true iff the UISM state is updated successfully.
     *
     * @param int $user_id
     * @param int $product_id
     * @return bool
     * @since 1.0
     */
    public static function uism_checkout( $user_id, $order_id ) {
        $uism = self::get_active_uism( $user_id );

        if ( !$uism ||  $uism->get_state() !== WCCS_UISM_State::$ACTIVE_NONBILLING ) {
            // The UISM must be in the ACTIVE_NONBILLING state before it can be chcecked out.
            // A INACTIVE UISM can only transition to state ACTIVE_NONBILLING via sign up.
            return false;
        }

        $uism->set_state( WCCS_UISM_State::$ACTIVE_BILLING );
        $uism->set_order_id( $order_id );
        return $uism->save();
    }

    /**
     * Apply cancellation logic to the UISM identified by the given $user_id.
     * The UISM must be in a ACTIVE_BILLING state. On Custom Subscription
     * cancellation, the UISM state is set to ACTIVE_NONBILLING.
     *
     * Return's true iff the UISM state is updated successfully.
     *
     * @param int $user_id
     * @return bool
     * @since 1.0
     */
    public static function uism_cancel( $user_id ) {
        $uism = self::get_active_uism( $user_id );

        if ( !$uism || $uism->get_state() !== WCCS_UISM_State::$ACTIVE_BILLING ) {
            // User must have an active billing (checked-out subscription) to
            // cancel.
            return false;
        }

        $uism->set_state( WCCS_UISM_State::$ACTIVE_NONBILLING ) ;
        return $uism->save();
    }

    /**
     * Generate a UISM's product contents. Return false if product generator
     * fails.
     *
     * @return ( array() | bool )
     * @since 1.0
     */
    private static function generate_uism_products( $user_id, $product_id ) {
        $product_generator_class = WCCS_Option_Configuration::get_option(
            WCCS_Setting_Configuration::$PRODUCT_GENERATION_METHOD_OPTION_KEY
        );

        if ( is_null( $product_generator_class ) ) {
            // Fall back on random content generation
            $product_generator_class = 'WCCS_UISM_Random_Content_Generator';
        }

        $product_generator = new $product_generator_class;
        return $product_generator->generate_products( $user_id, $product_id );
    }

    /**
     * Get a user's active UISM. A UISM can be in one of two active states:
     * ACTIVE_NONBILLING or ACTIVE_BILLING. Return NULL if no active UISM
     * exists for the user.
     *
     * @param int $user_id
     * @return WCCS_UISM_Dao
     * @since 1.0
     */
    public static function get_active_uism( $user_id ) {
        $uism = WCCS_UISM_Dao::get_uism(
            array( "user_id" => $user_id, "state" => WCCS_UISM_State::$ACTIVE_NONBILLING ) );

        if ( !$uism ) {
            $uism = WCCS_UISM_Dao::get_uism(
                array( "user_id" => $user_id, "state" => WCCS_UISM_State::$ACTIVE_BILLING ) );
        }

        return $uism;
    }
}
