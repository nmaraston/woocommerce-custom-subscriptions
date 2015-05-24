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
	 * @param int $user_id
	 * @param int $product_id
	 * @return bool
	 * @since 1.0
	 */
	public static function uism_sign_up( $user_id, $product_id ) {
		if ( ! WCCS_Product_Custom_Subscription_Helper::is_custom_subscription( $product_id ) ) {
			return false;
		}

		$uism = WCCS_UISM_Dao::get_uism( 
			array( "user_id" => $user_id, "product_id" => $product_id ) );

		if ( $uism ) {
			// UISM already exists
			return false;
		}

		$uism = new WCCS_UISM_Dao();
		$uism->set_base_product( $product_id );
		$uism->set_user_id( get_current_user_id() );
		$uism->set_state( WCCS_UISM_State::$ACTIVE_NONBILLING );

		$content_generator = new WCCS_UISM_Random_Content_Generator();
		$products = $content_generator->generate_products( $user_id, $product_id );

		for ( $index = 0; $index < count( $products ); $index++ ) {
			$uism->set_product_at_slot( $products[$index], $index );
		}

		return $uism->save();
	}
}
