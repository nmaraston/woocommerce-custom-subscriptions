<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The Template for displaying the subscription update confirmation UI.
 *
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

if ( ! $uism || ! isset( $_GET['product_id'] ) || ! is_numeric( $_GET['product_id'] ) ) {
    wp_redirect( WCCS_Page_Configuration::get_page_link( 'subscriptionselection' ) );
}

$wc_product = wc_get_product( intval( $_GET['product_id'] ) );

get_header( 'subscriptionconfirmation' ); ?>

    <br/>
    <br/>
    <br/>
    <br/>

    <div>
        <div>
            <div>

                <div>
                    <h1><span>STEP 2:</span>Please confirm your selection</h1>
                </div>

                <div>
                    <div>

                        <p>
                            You've selected the <?php echo $wc_product->get_title(); ?> Plan.
                            You will be charged <span><?php echo $wc_product->get_price_html(); ?></span> for your next subscription period.
                        </p>

                        <a
                            class="button wccs_soft_switch"
                            data-product_id="<?php echo $wc_product->id; ?>"
                            >Confirm
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>

<?php get_footer( 'subscriptionconfirmation' ); ?>
