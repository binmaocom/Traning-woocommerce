<?php
/**Template Name: Checkout Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.8
 */

get_header();

?>
<div id="primary" class="content-area woocommerce">
	<div id="content" class="site-content" role="main">
	<?php
		wc_print_notices();
		// get_template_part('custom-woocommerce/checkout/form-coupon');
		// get_template_part('custom-woocommerce/checkout/form-billing');
		// get_template_part('custom-woocommerce/checkout/form-shipping');
		get_template_part('custom-woocommerce/checkout/form-checkout');
	?>
	</div><!-- #content .site-content -->
</div><!-- #primary .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>