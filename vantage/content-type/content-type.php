<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $product;
if ( ! $product->is_purchasable() ) {
	?>
	<p>
		<b>
		<?php _e('Not availability to purchasable!'); ?>
		</b>
	</p>
	<?php
	return;
}
?>
<?php
	// Availability
	$availability      = $product->get_availability();
	$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
	echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
?>
<?php if ( $product->is_in_stock() ) : ?>
	<form class="cart" method="post" enctype='multipart/form-data'>
	 	<?php
	 		if ( ! $product->is_sold_individually() )
	 			woocommerce_quantity_input( array(
	 				'min_value' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
	 				'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product )
	 			) );
	 	?>
	 	<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />
	 	<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>
	</form>
<?php endif; ?>