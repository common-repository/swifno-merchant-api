<?php

function swifno_shipping_table(){
    global $wpdb;
    return $wpdb->prefix . "swifno_shipping";
}

function swifno_setting_options(){
	return get_option('swifno_opts');
}