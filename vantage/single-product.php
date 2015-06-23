<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header();
?>
<?php
	global $product, $woocommerce, $post;
	// var_dump($post);
?><article id="post-162" class="post post-162 type-post status-publish format-standard hentry category-uncategorized">

	<div class="entry-main">
		<div class="content-product">
			<?php while ( have_posts() ) : the_post(); ?>
			<header class="entry-header">
				<h1 class="entry-title">
					<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
				<p>
					<?php 
						_e('SKU: '); echo $product->get_sku() ? $product->get_sku(): 'N/A' ; 
					?>
				</p>
			</header>
			<?php get_template_part( 'content', 'product-single' ); ?>
			<?php endwhile; // end of the loop. ?>
		</div>
		
	</div>

</article>
<?php
get_footer();
?>
