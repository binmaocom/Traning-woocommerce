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
global $product, $woocommerce_loop;
// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;
// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );
// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;
// Increase loop count
$woocommerce_loop['loop']++;
// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

//Message
	wc_print_notices()

?>
<div <?php post_class( $classes ); ?>>
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<!--box-thumbnail-->
	<a href="<?php the_permalink(); ?>">
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
			// do_action( 'woocommerce_before_shop_loop_item_title' );
			if(has_post_thumbnail()){
			the_post_thumbnail();
			}
			else{
				echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
			}
		?>
		<div class="clear"></div>
	</a>
	<!--/box-thumbnail-->
	<!--box-gallery-->
	<div class="box-gallery">
	<?php
		$attachment_ids = $product->get_gallery_attachment_ids();
		if ( $attachment_ids ) {
		$loop 		= 0;
		$columns 	= apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
		?>
		<div class="thumbnails <?php echo 'columns-' . $columns; ?>"><?php

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'zoom' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'first';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) );
				$image_class = esc_attr( implode( ' ', $classes ) );
				$image_title = esc_attr( get_the_title( $attachment_id ) );

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[product-gallery]">%s</a>', $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );

				$loop++;
			}

		?>
		</div>
		<?php
		}
	?>
	</div>
	<!--/box-gallery-->
	<!--box-rating-->
	<?php
		/**
		 * woocommerce_after_shop_loop_item_title hook
		 *
		 * @hooked woocommerce_template_loop_rating - 5
		 * @hooked woocommerce_template_loop_price - 10
		 */
		remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',5);
		remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 10);
		add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price', 9);
		add_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',10);
		// do_action( 'woocommerce_after_shop_loop_item_title' );
	
	
	if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) {
		return;
	}
	else{
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();
		if ( $rating_count > 0 ) : ?>
	<div class="woocommerce-product-rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
		<div class="star-rating" title="<?php printf( __( 'Rated %s out of 5', 'woocommerce' ), $average ); ?>">
			<span style="width:<?php echo ( ( $average / 5 ) * 100 ); ?>%">
				<strong itemprop="ratingValue" class="rating"><?php echo esc_html( $average ); ?></strong> <?php printf( __( 'out of %s5%s', 'woocommerce' ), '<span itemprop="bestRating">', '</span>' ); ?>
				<?php printf( _n( 'based on %s customer rating', 'based on %s customer ratings', $rating_count, 'woocommerce' ), '<span itemprop="ratingCount" class="rating">' . $rating_count . '</span>' ); ?>
			</span>
		</div>
		<?php if ( comments_open() ) : ?><a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<?php printf( _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ), '<span itemprop="reviewCount" class="count">' . $review_count . '</span>' ); ?>)</a><?php endif ?>
	</div>
	<!--/box-rating-->
	<?php 
	endif;
	}
	?>
	<!--box-add-to-cart-->
	<div class="box-add-to-cart">
	<?php
	/**
	 * woocommerce_after_shop_loop_item hook
	 *
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	// do_action( 'woocommerce_after_shop_loop_item' ); 
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button %s product_type_%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
			esc_attr( $product->product_type ),
			esc_html( $product->add_to_cart_text() )
		),
	$product );
	?>
	</div>
	<!--/box-add-to-cart-->
	<!--box-price-->
	<?php
	var_dump($product->product_type );
	if($product->product_type == 'grouped'){
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
	<?php
	}
	if ( $price_html = $product->get_price_html() ) : ?>
	<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

		<p class="price"><?php echo $product->get_price_html(); ?></p>

		<meta itemprop="price" content="<?php echo $product->get_price(); ?>" />
		<meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
		<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

	</div>
	<?php
		endif;
	?>
	<!--/box-price-->
	<!--description-->
	<div itemprop="description">
		<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
	</div>
	<!--/description-->
	<!--box-tab-->
	<div class="box-tab">
		<div class="box-description">
			<?php
			$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', __( 'Product Description', 'woocommerce' ) ) );
			?>
			<?php if ( $heading ): ?>
			  <h2><?php echo $heading; ?></h2>
			<?php endif; ?>
			<?php the_content(); ?>
		</div>
		<div class="box-additional-information">
			<?php
			$heading = apply_filters( 'woocommerce_product_additional_information_heading', __( 'Additional Information', 'woocommerce' ) );
			?>
			<?php if ( $heading ): ?>
				<h2><?php echo $heading; ?></h2>
			<?php endif; ?>
			<?php $product->list_attributes(); ?>
		</div>
	</div>
	<!--/box-tab-->
</div>