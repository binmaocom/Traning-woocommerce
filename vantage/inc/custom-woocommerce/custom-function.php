<?php
// add_filter( 'woocommerce_enqueue_styles', '__return_false');
add_action('woocommerce_before_main_content','binmaocom_echo');
function binmaocom_echo(){
	echo 'lasfjlasfjsdlf';
}