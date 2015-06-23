<?php
/**
 *Template for category product
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header();
//select order by
woocommerce_catalog_ordering();
?>
<div class="box-archive-product">
<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
	<h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
		<?php 
			get_template_part('custom-woocommerce/pagination');
		?>
		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php get_template_part( 'loop','no-products-found' ); ?>

		<?php endif; ?>
	<p>Category product</p>
</div>
<?php
get_footer();
?>