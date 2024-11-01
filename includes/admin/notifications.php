<?php

function swifno_shipping_admin_notifications(){
    global $pagenow;
    if ( $pagenow == 'options-general.php' ) {
    	if ( !class_exists( 'WooCommerce' )  ) {
		  	echo '<div class="notice notice-warning is-dismissible">
	            <p>You need WooCommerce to Initiate Swifno Deliveries.</p>
	        </div>';
      }
		}
}