<?php

function swifno_shipping_save_settings(){
	if( !current_user_can( 'manage_options' ) ){
		wp_die( __( 'You are not allowed to be on this page', 'Swifno'));
	}

	check_admin_referer( 'swifno_shipping_settings_verify' );

	$full = sanitize_text_field($_POST['swifno_pickup_state']);

	$array = explode('-', sanitize_text_field($_POST['swifno_pickup_state']));

	$wooCommerceCountryState = $array[0];

	$swifnoCountryState = $array[1];

	$mode = sanitize_text_field($_POST['swifno_mode']);

    $swifno_opts   							= get_option('swifno_opts');
	$swifno_opts['swifno_package_category'] = sanitize_text_field($_POST['swifno_package_category']);
	$swifno_opts['swifno_package_size'] 	= sanitize_text_field($_POST['swifno_package_size']);
	$swifno_opts['swifno_insurance'] 		= sanitize_text_field($_POST['swifno_insurance'] == 'on' ? 1 : 0);
	$swifno_opts['swifno_itemvalue'] 		= sanitize_text_field($_POST['swifno_itemvalue'] == 'on' ? 1 : 0);
	$swifno_opts['swifno_pickup_from_time'] = sanitize_text_field($_POST['swifno_pickup_from_time']);
	$swifno_opts['swifno_pickup_to_time'] 	= sanitize_text_field($_POST['swifno_pickup_to_time']);
	$swifno_opts['swifno_full_state'] 		= $full;
	$swifno_opts['swifno_pickup_location'] 	= sanitize_text_field($_POST['swifno_pickup_location']);
	$swifno_opts['swifno_pickup_state'] 	= $swifnoCountryState;

	update_option('swifno_opts', $swifno_opts);

	// update_option('woocommerce_default_country', $wooCommerceCountryState);
	// 
	update_option('woocommerce_currency', "NGN");

	// update_option('woocommerce_store_address', sanitize_text_field($_POST['swifno_pickup_location']));

	// update_option('woocommerce_default_country', $swifnoCountryState);

    wp_redirect(admin_url('admin.php?page=swifno-settings&delivery-status=1'));

}