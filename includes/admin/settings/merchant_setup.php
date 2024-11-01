<?php

function swifno_shipping_validate_merchant(){
	$swifno_opt = get_option('swifno_opts');
	if($swifno_opt['swifno_mode'] == NULL && get_option('swifno_key') == NULL && get_option('swifno_secret_key') == NULL){
		echo '<span class="dashicons dashicons-no" style="color:red"></span>';
	}else{
		echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
	}
}