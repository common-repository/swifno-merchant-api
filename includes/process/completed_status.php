<?php

function swifno_shipping_completed_status( $order_id ) {
    global $wpdb;
    
    $data = array(
        'order_status' => 'success',
    );
    
    $tid = array('woocommerce_order_id'=> $order_id);
    
    $order_exists = $wpdb->get_results("SELECT * FROM ".swifno_shipping_table()." WHERE woocommerce_order_id = '".$order_id."'");
    
    if ($order_exists)
        $success = $wpdb->update( swifno_shipping_table(), $data , $tid);
    
    $is_in_database = $wpdb->get_row( 
        $wpdb->prepare("SELECT * FROM ".swifno_shipping_table()." WHERE woocommerce_order_id = '$order_id'" ), ARRAY_A
    );
    
    if ($is_in_database ) { 
        if ($is_in_database["order_status"] == 'success'){

            $swifno_opt = get_option('swifno_opts');

            $mode = $swifno_opt['swifno_mode'];

            $key = $swifno_opt['swifno_key'];
            
            $swifno_secret_key = $swifno_opt['swifno_auth_token'];

            $url = swifno_shipping_state_mode($mode);
            
            $response = wp_remote_get($url.'?action=delivery_request'.
                '&request_token='.$is_in_database["bid_token"].
                '&recipient_name='.$is_in_database["recipient_name"].
                '&recipient_mobile='.$is_in_database["recipient_mobile"].
                '&recipient_email='.$is_in_database["recipient_email"].
                '&package_category='.$is_in_database["package_category"].
                '&package_size='.$is_in_database["package_size"].
                '&pickup_from_time='.$is_in_database["pickup_from_time"].
                '&dropoff_from_time='.$is_in_database["pickup_from_time"].
                '&pickup_to_time='.$is_in_database["pickup_to_time"].
                '&dropoff_to_time='.$is_in_database["pickup_to_time"].
                '&additional_info='.$is_in_database["additional_info"].
                '&package_name='.$is_in_database["package_name"].
                '&bid_id='.$is_in_database["bid_id"]
            );

            $vars = json_decode($response['body'], true);

            if(array_key_exists('RESPONSEMESSAGE', $vars)){
                $message = $vars['RESPONSEMESSAGE'];
                $delivery_status = 'transferred';
            }

            if(array_key_exists('ERROR_MSG', $vars)){
                $message = $vars['ERROR_MSG'];
                $delivery_status = 'failed';
            }

            array_key_exists('RESPONSEMESSAGE', $vars);
            
            $data = array(
                'api_response' => $message,
                'delivery_status' => $delivery_status,
            );
            
            $tid = array('woocommerce_order_id'=> $order_id);
            
            $order_exists = $wpdb->get_results("SELECT * FROM ".swifno_shipping_table()." WHERE woocommerce_order_id = '".$order_id."'");
            
            if ($order_exists && $order_exists['delivery_status'] != 'transferred')
                $success = $wpdb->update( swifno_shipping_table(), $data , $tid);
                        
        }
    }         
}