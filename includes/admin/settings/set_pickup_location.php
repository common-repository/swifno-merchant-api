<?php

function set_swifno_shipping_pickup_location(){
	if(get_option('woocommerce_default_country') === '' && get_option('woocommerce_store_city') === '' ){
		echo '<span class="dashicons dashicons-no" style="color:red"></span>';
	}else{
		echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
	}
}