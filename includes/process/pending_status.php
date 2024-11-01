<?php

function swifno_shipping_pending_order($order_id){
    
    $order = wc_get_order( $order_id );
    
    if(!empty($_POST['shipping_state'])){
        $drop_state = sanitize_text_field($_POST['shipping_state']);
    }
    
    if(!empty($_POST['billing_city'])){
        $droparea = sanitize_text_field($_POST['billing_city']);
    }elseif(!empty($_POST['shipping_city'])){
        $droparea = sanitize_text_field($_POST['shipping_city']);
    }
    
    if(!empty($_POST['billing_address_1'])){
        $droplocation = sanitize_text_field($_POST['billing_address_1']);
    }elseif(!empty($_POST['shipping_address_1'])){
        $droplocation = sanitize_text_field($_POST['shipping_address_1']);
    }
    
    if(!empty($_POST['shipping_first_name'])){
        $first_name = sanitize_text_field($_POST['shipping_first_name']);
    }
    
    if(!empty($_POST['shipping_last_name'])){
        $last_name = sanitize_text_field($_POST['shipping_last_name']);
    }
    
    if(!empty($_POST['billing_phone'])){
        $billing_phone = sanitize_text_field($_POST['billing_phone']);
    }
    
    if(!empty($_POST['billing_email'])){
        $billing_email = sanitize_email($_POST['billing_email']);
    }
    
    if(!empty($_POST['bid_id'])){
        $bid_id = sanitize_text_field($_POST['bid_id']);
    }
    
    if(!empty($_POST['bid_amount'])){
        $bid_amount = sanitize_text_field($_POST['bid_amount']);
    }
    
    if(!empty($_POST['order_comments'])){
        $order_comments = sanitize_text_field($_POST['order_comments']);
    }
    
    if(get_option('woocommerce_store_city')){
        $pickup_area = get_option('woocommerce_store_city');
    }
    
    
    $pickup_location = $swifno_opt['swifno_pickup_location'];

    $bid_amount   = preg_replace("/[^0-9]/", "", WC()->cart->get_cart_shipping_total());
    
    $chosen_methods = WC()->session->get( 'chosen_shipping_methods' );
    $chosen_shipping = $chosen_methods[0];

    $res = preg_replace("/[^0-9]/", "", $chosen_shipping );
    
    $bid_id   = $res;
    $bid_id   = empty($bid_id) ? $res : $bid_id;
    
    $pImagesUrl = array();
    $lists = array();
    
    $items = WC()->cart->get_cart();
    foreach($items as $item => $values) { 
        $_product =  wc_get_product( $values['data']->get_id() );
        $pImagesUrl[] = get_the_post_thumbnail_url($values['product_id']);
        $price = get_post_meta($values['product_id'] , '_price', true);
        $lists[] = $_product->get_title() .' Quantity: '.$values['quantity'];
    }
    
    $first_image = $pImagesUrl[0];
    $package_name = "";
    foreach ($lists as $list){
        $package_name .=  "$list ";
    }
    
    $swifno_opt = get_option('swifno_opts');

    $mode = $swifno_opt['swifno_mode'];

    $key = $swifno_opt['swifno_key'];

    $url = swifno_shipping_state_mode($mode);
    
    global $woocommerce;

    $cart = $woocommerce->cart;

    $cart_total = preg_replace("/[^0-9]/", "", $woocommerce->cart->get_cart_total());
    
    $data = array(
        'pickup_location' => $pickup_location,
        'drop_location' => $droplocation,
        'recipient_name' => $first_name .' '.$last_name,
        'recipient_mobile' => $billing_phone,
        'recipient_email' => $billing_email,
        'package_category' => $swifno_opt['swifno_package_category'],
        'package_name' => $package_name,
        'package_size' => $swifno_opt['swifno_package_size'],
        'package_deliveryspeed' => $chosen_methods[1],
        'pickup_from_time' => $swifno_opt['swifno_pickup_from_time'],
        'pickup_to_time' => $swifno_opt['swifno_pickup_to_time'],
        'insurance' => $swifno_opt['swifno_insurance'],
        'itemvalue' => $swifno_opt['swifno_itemvalue'],
        'order_amount' => $cart_total,
        'additional_info' => $order_comments . ' Shipping order from merchant api',
        'bid_id' => $bid_id,
        'bid_token' => $swifno_opt['swifno_current_token'],
        'order_status' => 'Pending',
        'bid_amount' => $bid_amount,
        'woocommerce_order_id' => $order_id,
    );
    
    global $wpdb;
    
    $tid = array('woocommerce_order_id'=> $order_id);
    
    $order_exists = $wpdb->get_results("SELECT * FROM ".swifno_shipping_table()." WHERE woocommerce_order_id = '".$order_id."'");
    
    if ($order_exists)
        $wpdb->update( swifno_shipping_table(), $data , $tid);
    else
        $wpdb->insert(swifno_shipping_table(), $data);
}