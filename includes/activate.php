<?php

function swifno_shipping_activate_plugin(){
	if( version_compare( get_bloginfo('version'), '5.0', '<') ){
		wp_die("You must update Wordpress to use this plugin");
	}

	global $wpdb;

    $sql = "CREATE TABLE ".swifno_shipping_table()." (
		    id INT(6) UNSIGNED AUTO_INCREMENT,
		    pickup_location varchar(225) DEFAULT NULL,
		    drop_location varchar(225) DEFAULT NULL,
		    recipient_name varchar(255) DEFAULT NULL,
		    recipient_mobile varchar(255) DEFAULT NULL,
		    recipient_email varchar(255) DEFAULT NULL,
		    package_category varchar(255) DEFAULT NULL,
		    package_name varchar(255) DEFAULT NULL,
		    package_size varchar(255) DEFAULT NULL,
		    insurance varchar(255) DEFAULT NULL,
		    itemvalue varchar(255) DEFAULT NULL,
		    package_deliveryspeed varchar(255) DEFAULT NULL,
		    pickup_from_time varchar(255) DEFAULT NULL,
		    pickup_to_time varchar(255) DEFAULT NULL,
		    dropoff_from_time varchar(255) DEFAULT NULL,
		    dropoff_to_time varchar(255) DEFAULT NULL,
		    additional_info text DEFAULT NULL,
		    order_amount text DEFAULT NULL,
		    bid_id text DEFAULT NULL,
		    bid_token text DEFAULT NULL,
		    bid_amount text DEFAULT NULL,
		    order_status text DEFAULT NULL,
		    delivery_status text DEFAULT NULL,
		    company_name text DEFAULT NULL,
		    woocommerce_order_id text DEFAULT NULL,
		    api_response text DEFAULT NULL,
	  		PRIMARY KEY  (id)
		)".$wpdb->get_charset_collate();

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);

    $swifno_opts = get_option('swifno_opts');

    if( !$swifno_opts ){
    	$opts 						        =    [
    		'swifno_mode'						=> NULL,
    		'swifno_key'						=> NULL,
    		'swifno_current_token'				=> NULL,
    		'swifno_secret_key'				    => NULL,
    		'swifno_auth_token'				    => NULL,
    		'swifno_package_category'			=> NULL,
    		'swifno_package_size'				=> NULL,
    		'swifno_insurance'					=> NULL,
    		'swifno_itemvalue'					=> NULL,
    		'swifno_full_state'					=> NULL,
    		'swifno_pickup_location'			=> NULL,
    		'swifno_pickup_state'				=> NULL,
    	];
        
    	add_option('swifno_opts', $opts);
    	
    }else{
        
        $opts 						=    [
    		'swifno_mode'						=> $swifno_opts['swifno_mode'] ? $swifno_opts['swifno_mode'] : NULL,
    		'swifno_key'						=> $swifno_opts['swifno_key'] ? $swifno_opts['swifno_key'] : NULL,
    		'swifno_current_token'				=> $swifno_opts['swifno_current_token'] ? $swifno_opts['swifno_current_token'] : NULL,
    		'swifno_secret_key'				    => $swifno_opts['swifno_secret_key'] ? $swifno_opts['swifno_secret_key'] : NULL,
    		'swifno_auth_token'				    => $swifno_opts['swifno_auth_token'] ? $swifno_opts['swifno_auth_token'] : NULL,
    		'swifno_package_category'			=> $swifno_opts['swifno_package_category'] ? $swifno_opts['swifno_package_category'] : NULL,
    		'swifno_package_size'				=> $swifno_opts['swifno_package_size'] ? $swifno_opts['swifno_package_size'] : NULL,
    		'swifno_insurance'					=> $swifno_opts['swifno_insurance'] ? $swifno_opts['swifno_insurance'] : NULL,
    		'swifno_itemvalue'					=> $swifno_opts['swifno_itemvalue'] ? $swifno_opts['swifno_itemvalue'] : NULL,
    		'swifno_full_state'					=> $swifno_opts['swifno_full_state'] ? $swifno_opts['swifno_full_state'] : NULL,
    		'swifno_pickup_location'			=> $swifno_opts['swifno_pickup_location'] ? $swifno_opts['swifno_pickup_location'] : NULL,
    		'swifno_pickup_state'				=> $swifno_opts['swifno_pickup_state'] ? $swifno_opts['swifno_pickup_state'] : NULL
    	];
    	
        update_option('swifno_opts', $opts);
    }
}