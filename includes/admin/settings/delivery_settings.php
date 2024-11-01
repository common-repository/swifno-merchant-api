<?php

function swifno_shipping_delivery_settings(){
	$swifno_opt = get_option('swifno_opts');
	if($swifno_opt['swifno_package_category'] == NULL && $swifno_opt['swifno_package_size'] == NULL && $swifno_opt['swifno_insurance'] == NULL && $swifno_opt['swifno_itemvalue'] == NULL && $swifno_opt['swifno_pickup_from_time'] == NULL && $swifno_opt['swifno_pickup_to_time'] == NULL && $swifno_opt['swifno_pickup_location'] == NULL && $swifno_opt['swifno_full_state'] == NULL){
		echo '<span class="dashicons dashicons-no" style="color:red"></span>';
	}else{
		echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
	}
}