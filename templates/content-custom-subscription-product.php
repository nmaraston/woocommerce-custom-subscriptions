<?php
/**
 * The template for displaying custom subscription product content within Loops.
 *
 * @package     WooCommerceCustomSubscriptions/templates
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

global $product, $wccs_loop;

// Store loop count we're currently on
if ( empty( $wccs_loop['loop'] ) ) {
	$wccs_loop['loop'] = 0;
}

// Store column count for displaying the grid
if ( empty( $wccs_loop['columns'] ) ) {
	$wccs_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
}

// Ensure visibility
if ( ! $product || ! $product->is_visible() ) {
	return;
}

// Increase loop count
$wccs_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $wccs_loop['loop'] - 1 ) % $wccs_loop['columns'] || 1 == $wccs_loop['columns'] ) {
	$classes[] = 'first';
}
if ( 0 == $wccs_loop['loop'] % $wccs_loop['columns'] ) {
	$classes[] = 'last';
}

?>

<li <?php post_class( $classes ); ?>>

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php woocommerce_template_loop_product_thumbnail(); ?>

		<h3><?php the_title(); ?></h3>

	</a>

	<?php load_template( WCCS()->plugin_path() . "templates/change-product.php", false ); ?>

</li>
