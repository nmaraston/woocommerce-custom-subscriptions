<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The Template for displaying the subscription-selection page.
 *
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

$uism = WCCS_UISM_Manager::get_active_uism( get_current_user_id() );

$page_title = ( $uism ) ? "Upgrade Subscription" : "Select Subscription";

get_header( 'subscriptionselection' ); ?>

	<br/>
	<br/>
	<br/>
	<br/>

	<h1 class="page-title"><?php echo $page_title; ?></h1>

	<div>
		<div>
			<div>

				<div>
					<h1>Choose your preferred subscription plan</h1>
					<p>You will not be charged until you check out. You can cancel at any time.</p>
				</div>

				<?php
					// Query for all Custom Subscription products.
					// Order by ascending price.
					$args = array(
						'post_type'      => 'product',
						'post_status'    => 'publish',
						'posts_per_page' => -1,
						'meta_key'       => '_price',
						'orderby'        => 'meta_value_num',
						'order'          => 'ASC',
						'tax_query'      => array(
							array(
								'taxonomy' => 'product_type',
								'field'    => 'slug',
								'terms'    => 'custom-subscription'
							)
						)
					);

					$posts = new WP_Query( $args );
				?>

				<?php if ( $posts->have_posts() ) : ?>

					<?php while ( $posts->have_posts() ) : $posts->the_post(); ?>

						<?php wccs_get_template( 'loop/content-custom-subscription-selection.php' ); ?>

					<?php endwhile; ?>

				<?php endif; ?>

			</div>
		</div>
	</div>

<?php get_footer( 'subscriptionselection' ); ?>
