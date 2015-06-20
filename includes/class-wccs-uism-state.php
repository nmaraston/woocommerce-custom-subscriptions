<?php
/**
 * This class contains possible states of a UISM.
 *
 * @class       WCCS_UISM_State
 * @package     WooCommerceCustomSubscriptions
 * @category    Class
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
class WCCS_UISM_State {

    /**
     * The UISM is instantiated but not checked out. The user can customize
     * their subscription content even though they have not checkedout their
     * subscription and are not undergoing recurring billing.
     *
     * @since 1.0
     */
    public static $ACTIVE_NONBILLING = "ACTIVE_NONBILLING";

    /**
     * The UISM is instantiated and checked out. The user's subscription will
     * abide by lock-in periods and recurring billing.
     *
     * @since 1.0
     */
    public static $ACTIVE_BILLING    = "ACTIVE_BILLING";

    /**
     * The UISM is instantiated but not visible to the user. This UISM is not
     * customizable and does not cause recurring billing. The UISM can be
     * re-activated upon user re-sign up.
     *
     * @since 1.0
     */
    public static $INACTIVE          = "INACTIVE";
}
