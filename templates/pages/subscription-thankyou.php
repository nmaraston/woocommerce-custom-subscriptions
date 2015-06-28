<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The Template for displaying the subscription update thank you UI.
 *
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

get_header( 'subscriptionconfirmation' ); ?>

    <br/>
    <br/>
    <br/>
    <br/>

    <div>
        <div>
            <div>
                <div>
                    <h1>Thank you</h1>
                    <div>
                        <p>Your plan has been updated.</p>
                        <p>
                            <a href="<?php echo WCCS_Page_Configuration::get_page_link( 'mysubscription' ); ?>">
                                View your subscription
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php get_footer( 'subscriptionconfirmation' ); ?>
