<?php
/**
 * !!! THIS TEMPLATE IS OVERRIDABLE VIA PATH <THEME ROOT>/woocommerce/custom-subscriptions !!!
 *
 * The template for displaying custom subscription product content within Loops.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */
?>

<li>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php woocommerce_template_loop_product_thumbnail(); ?>

		<h3 id="custom-subscription-product-title"><?php the_title(); ?></h3>

		<?php woocommerce_template_single_excerpt(); ?>

	</a>

	<?php wccs_get_template( 'change-product-button.php' ); ?>

</li>
