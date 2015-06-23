<?php
/**
 * The template for displaying product content within loops.
 *
 * Override content-single-product
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
if ( ! $product || ! $product->is_visible() ){
	return;
}
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
<?php
//product is sale
if($product->is_on_sale()) {
?>
	<p><strong>Product is Sale</strong></p>
<?php
}
?>
<div <?php post_class( $classes ); ?>>
	<?php //do_action( 'woocommerce_before_shop_loop_item' ); ?>
	<!--box-left-single-product-->
	<div class="box-left-single-product">
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
	</div>
	<!--/box-left-single-product-->
	<!--/box-right-single-product-->
	<div class="box-right-single-product">
		<!--box-rating-->
		<?php
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
		<?php 
		endif;
		}
		?>
		<!--/box-rating-->
		<!--box-price-->
		<?php
		// var_dump($product->product_type );
		if($product->product_type == 'grouped'){
			get_template_part('content-type/content-type', 'grouped');
		}
		elseif($product->product_type == 'variable'){
			get_template_part('content-type/content-type','variable');
		}
		elseif($product->product_type == 'external'){
			get_template_part('content-type/content-type','external');
		}
		else{
			get_template_part('content-type/content-type');
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
	</div>
	<!--/box-right-single-product-->
	<div class="clear"></div>
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
		<div class="box-rating-on-single">
		<?php
		
		comments_template( 'custom-woocommerce/rating.php', true);
		// get_template_part('single-product-reviews');
		?>
		</div>
	</div>
	<!--/box-tab-->
	<!--box-up-sells-single-->
	<div class="box-up-sells-single">
	<?php
		get_template_part('custom-woocommerce/up-sells');
	?>
	</div>
	<!--/box-up-sells-single-->
</div>