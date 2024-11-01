<?php

function swifno_shipping_save_merchant(){
	if( !current_user_can( 'manage_options' ) ){
		wp_die( __( 'You are not allowed to be on this page', 'Swifno'));
	}
	

	check_admin_referer( 'swifno_shipping_merchant_verify' );

	$mode = sanitize_text_field($_POST['swifno_mode']);

	$key = sanitize_text_field($_POST['swifno_key']);
	
	$swifno_secret_key = sanitize_text_field($_POST['swifno_secret_key']);

	$url = swifno_shipping_state_mode($mode);
    

    $response = wp_remote_get( 
        $url.'?action=authentication&merchant_key='.$key .'&secret_key='. $swifno_secret_key
    );
    
    $vars = json_decode($response['body'], true);
    
    // var_dump($vars);
    // die();
    
    if($vars['RESPONSECODE'] != 1){
        
        wp_redirect(admin_url('admin.php?page=swifno-settings&status=0'));
        
    }else{
		$swifno_opts   								= get_option('swifno_opts');
		$swifno_opts['swifno_mode'] 				= $mode;
		$swifno_opts['swifno_key'] 					= $key;
		$swifno_opts['swifno_secret_key'] 			= $swifno_secret_key;
		$swifno_opts['swifno_auth_token'] 			= $vars['AUTHTOKEN'];
		
		update_option('swifno_opts', $swifno_opts);
		
        wp_redirect(admin_url('admin.php?page=swifno-settings&status=1'));
    }

}