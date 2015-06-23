<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
	$parent_product_post = $post;
	?>
	<form class="cart" method="post" enctype='multipart/form-data'>
		<table cellspacing="0" class="group_table">
			<tbody>
				<?php
				if ( $grouped_term = get_term_by( 'slug', 'grouped', 'product_type' ) ) {

						$posts_in = array_unique( (array) get_objects_in_term( $grouped_term->term_id, 'product_type' ) );

						if ( sizeof( $posts_in ) > 0 ) {

							$args = array(
								'post_type'        => 'product',
								// 'post_status'      => 'any',
								'numberposts'      => -1,
								// 'orderby'          => 'title',
								// 'order'            => 'asc',
								'post_parent'      => get_the_ID(),
								// 'suppress_filters' => 0,
								// 'include'          => $posts_in,
							);

							$grouped_products = get_posts( $args );

							if ( $grouped_products ) {
								foreach ( $grouped_products as $product_ids ) :
									$product_id = $product_ids->ID;
									$product = wc_get_product( $product_ids->ID );

									if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) && ! $product->is_in_stock() ) {
										continue;
									}

									$post    = $product->post;
									setup_postdata( $post );
									?>
									<tr>
										<td>
											<?php if ( $product->is_sold_individually() || ! $product->is_purchasable() ) : ?>
												<?php woocommerce_template_loop_add_to_cart(); ?>
											<?php else : ?>
												<?php
													$quantites_required = true;
													woocommerce_quantity_input( array( 'input_name' => 'quantity[' . $product_id . ']', 'input_value' => '0', 'min_value' => apply_filters( 'woocommerce_quantity_input_min', 0, $product ), 'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ) ) );
												?>
											<?php endif; ?>
										</td>

										<td class="label">
											<label for="product-<?php echo $product_id; ?>">
												<?php echo $product->is_visible() ? '<a href="' . get_permalink() . '">' . get_the_title() . '</a>' : get_the_title(); ?>
											</label>
										</td>

										<?php do_action ( 'woocommerce_grouped_product_list_before_price', $product ); ?>

										<td class="price">
											<?php
												echo $product->get_price_html();

												if ( $availability = $product->get_availability() ) {
													$availability_html = empty( $availability['availability'] ) ? '' : '<p class="stock ' . esc_attr( $availability['class'] ) . '">' . esc_html( $availability['availability'] ) . '</p>';
													echo apply_filters( 'woocommerce_stock_html', $availability_html, $availability['availability'], $product );
												}
											?>
										</td>
									</tr>
									<?php
								endforeach;
							}
						}

					}

					// Reset to parent grouped product
					$post    = $parent_product_post;
					$product = wc_get_product( $parent_product_post->ID );
					setup_postdata( $parent_product_post );
				?>
			</tbody>
		</table>

		<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->id ); ?>" />

		<?php if ( $quantites_required ) : ?>

			<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

			<button type="submit" class="single_add_to_cart_button button alt"><?php echo $product->single_add_to_cart_text(); ?></button>

			<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

		<?php endif; ?>
	</form>