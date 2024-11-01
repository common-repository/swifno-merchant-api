<?php

function swifno_shipping_check_woocommerce(){
	if(!class_exists( 'WooCommerce' ) ){
		echo '<span class="dashicons dashicons-no" style="color:red"></span>';
	}else{
		echo '<span class="dashicons dashicons-yes-alt" style="color:green"></span>';
	}
}

function wooCommerceGuide(){
	if(!class_exists( 'WooCommerce' ) ){
		echo "<div>
			<h2>Steps</h2>
			Go to: Dashboard > Plugins > Add New > Search Plugins
			Search for “WooCommerce”
			Install
			Activate
			</div>
			";
	}else{
		echo 'Successfully installed Wordpress';
	}	
}