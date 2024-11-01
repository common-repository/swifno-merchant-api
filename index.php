<?php

/**
 * @package Swifno Merchant API
 */
/*
 * 	Plugin Name: Swifno Shipping Merchant API
 * 	Plugin URI: https://swifno.com/
 	Description: Used by millions for shipping service delivery as we have several delivery services under the Swifno Platform. It relieves your client delivery stress and sends delivery activities to www.swifno.com to handle for your client. To get started: activate the swifno plugin and then go to your Swifno Settings page to set up your Merchant API key.
 *	Version: 5.0.3
 *	Author: Swifno Team & Schneider Komolafe
 *	Author URI: https://swifno.com/
 *	Version: 1.0
 *	Text Domain: Swifno
*/

if( !function_exists( 'add_action' ) ){
	echo "Hi there! I'm Just a plugin. There's Not much i can do when called directly";
	exit;
}

if (!defined("ABSPATH")) {
    exit;
}

// Includes
include( 'includes/url.php' );
include( 'includes/tb.php' );
include( 'includes/activate.php' );
include( 'includes/deactivate.php' );
include( 'includes/menus.php' );
include( 'includes/admin/assets.php' );
include( 'includes/admin/settings.php' );
include( 'includes/admin/settings/install_woocommerce.php' );
include( 'includes/admin/settings/set_pickup_location.php' );
include( 'includes/admin/settings/merchant_setup.php' );
include( 'includes/admin/settings/delivery_settings.php' );
include( 'includes/admin/notifications.php' );
include( 'includes/process/save_merchant.php' );
include( 'includes/process/save_options.php' );
include( 'includes/process/pending_status.php' );
include( 'includes/process/completed_status.php' );

// Hooks
register_activation_hook( __FILE__, 'swifno_shipping_activate_plugin');
register_deactivation_hook( __FILE__, 'swifno_shipping_deactivate_plugin');
add_action( 'admin_menu', 'swifno_shipping_admin_menus');
add_action( 'admin_enqueue_scripts', 'swifno_shipping_admin_enqueue');
add_action( 'admin_notices', 'swifno_shipping_admin_notifications');
add_action( 'admin_post_swifno_shipping_save_merchant', 'swifno_shipping_save_merchant');
add_action( 'admin_post_swifno_shipping_save_settings', 'swifno_shipping_save_settings');
add_action( 'woocommerce_checkout_order_processed', 'swifno_shipping_pending_order',  1 , 1 );
add_action( 'woocommerce_thankyou', 'swifno_shipping_completed_status');

function swifno_shipping_admin_enqueue(){
    if( !isset($_GET['page']) || $_GET['page'] != 'swifno-settings'){
        return;
    }
    wp_enqueue_style('swifno', plugin_dir_url(__FILE__) . "/assets/css/swifno.css", '');
    wp_enqueue_script('script.js', plugin_dir_url(__FILE__) . "/assets/js/script.js", "", true);
    wp_localize_script('script.js', 'swifnoshippingajaxurl', admin_url('admin-ajax.php'));
}



if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    function swifno_shipping_method_init() {
        if ( ! class_exists( 'WC_Swifno_Shipping_Method' ) ) {
            class WC_Swifno_Shipping_Method extends WC_Shipping_Method {
                /**
                 * Constructor for your shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct() {
                    $this->id                 = 'swifno_shipping_vendor'; // Id for your shipping method. Should be uunique.
                    $this->method_title       = __( 'Your Swifno Shipping Method' );  // Title shown in admin
                    $this->method_description = __( 'Description of your shipping method' ); // Description shown in admin
                    $this->enabled            = "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->title              = "My Shipping Method"; // This can be added as an setting but for this example its forced.
                    $this->init();
                }
                /**
                 * Init your settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // Load the settings API
                    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
                    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.
                    // Save settings in admin if you have any defined
                    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }
                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                function calculate_shipping( $package = array() ) {
                    if ($_POST['has_full_address']){
                        
                        $swifno_opt = get_option('swifno_opts');

                        $mode = $swifno_opt['swifno_mode'];

                        $key = $swifno_opt['swifno_auth_token'];

                        $url = swifno_shipping_state_mode($mode);
                        
                        $package_category = $swifno_opt['swifno_package_category'];
                        $package_size = $swifno_opt['swifno_package_size'];
                        $insurance = $swifno_opt['swifno_insurance'];
                        $itemvalue = $swifno_opt['swifno_itemvalue'];
                        
                        $drop_state = $_POST ? $_POST['state'] : $_POST['s_state']; 
                        
                        if($drop_state == 'AB'){
                            $drop_state= 'Abia';
                        }
                        if($drop_state == 'FC'){
                            $drop_state= 'Abuja';
                        }
                        if($drop_state == 'AD'){
                            $drop_state= 'Adamawa';
                        }
                        if($drop_state == 'AK'){
                            $drop_state= 'Akwa Ibom';
                        }
                        if($drop_state == 'AN'){
                            $drop_state= 'Anambra';
                        }
                        if($drop_state == 'BA'){
                            $drop_state= 'Bauchi';
                        }
                        if($drop_state == 'BY'){
                            $drop_state= 'Bayelsa';
                        }
                        if($drop_state == 'BE'){
                            $drop_state= 'Benue';
                        }
                        if($drop_state == 'BO'){
                            $drop_state= 'Borno';
                        }
                        if($drop_state == 'CR'){
                            $drop_state= 'Cross River';
                        }
                        if($drop_state == 'DE'){
                            $drop_state= 'Delta';
                        }
                        if($drop_state == 'EB'){
                            $drop_state= 'Ebonyi';
                        }
                        if($drop_state == 'ED'){
                            $drop_state= 'Edo';
                        }
                        if($drop_state == 'EK'){
                            $drop_state= 'Ekiti';
                        }
                        if($drop_state == 'EN'){
                            $drop_state= 'Enugu';
                        }
                        if($drop_state == 'GO'){
                            $drop_state= 'Gombe';
                        }
                        if($drop_state == 'IM'){
                            $drop_state= 'Imo';
                        }
                        if($drop_state == 'JI'){
                            $drop_state= 'Jigawa';
                        }
                        if($drop_state == 'KD'){
                            $drop_state= 'Kaduna';
                        }
                        if($drop_state == 'KN'){
                            $drop_state= 'Kano';
                        }
                        if($drop_state == 'KT'){
                            $drop_state= 'Katsina';
                        }
                        if($drop_state == 'KE'){
                            $drop_state= 'Kebbi';
                        }
                        if($drop_state == 'KO'){
                            $drop_state= 'Kogi';
                        }
                        if($drop_state == 'KW'){
                            $drop_state= 'Kwara';
                        }
                        if($drop_state == 'LA'){
                            $drop_state= 'Lagos';
                        }
                        if($drop_state == 'NA'){
                            $drop_state= 'Nasarawa';
                        }
                        if($drop_state == 'NI'){
                            $drop_state= 'Niger';
                        }
                        if($drop_state == 'OG'){
                            $drop_state= 'Ogun';
                        }
                        if($drop_state == 'ON'){
                            $drop_state= 'Ondo';
                        }
                        if($drop_state == 'OS'){
                            $drop_state= 'Osun';
                        }
                        if($drop_state == 'OY'){
                            $drop_state= 'Oyo';
                        }
                        if($drop_state == 'PL'){
                            $drop_state= 'Plateau';
                        }
                        if($drop_state == 'RI'){
                            $drop_state= 'Rivers';
                        }
                        if($drop_state == 'SO'){
                            $drop_state= 'Sokoto';
                        }
                        if($drop_state == 'TA'){
                            $drop_state= 'Taraba';
                        }
                        if($drop_state == 'YO'){
                            $drop_state= 'Yobe';
                        }
                        if($drop_state == 'ZA'){
                            $drop_state= 'Zamfara';
                        }
                        
                        $droparea = $_POST ? $_POST['city'] : $_POST['s_city'];
                        
                        $address = $_POST ? $_POST['address'] : $_POST['s_address'];
                        
                        $drop_location = $address .' '.$droparea.' '.$drop_state.', Nigeria';
                        
                        
                        if(!empty($_POST['billing_address_1'])){
                            $droplocation = sanitize_text_field($_POST['billing_address_1']);
                        }elseif(!empty($_POST['shipping_address_1'])){
                            $droplocation = sanitize_text_field($_POST['shipping_address_1']);
                        }

                        if(!empty($_POST['billing_first_name'])){
                            $first_name = sanitize_text_field($_POST['billing_first_name']);
                        }elseif(!empty($_POST['shipping_first_name'])){
                            $first_name = sanitize_text_field($_POST['shipping_first_name']);
                        }
                        
                        if(!empty($_POST['billing_last_name'])){
                            $last_name = sanitize_text_field($_POST['billing_last_name']);
                        }elseif(!empty($_POST['shipping_last_name'])){
                            $last_name = sanitize_text_field($_POST['shipping_last_name']);
                        }
                        
                        if(!empty($_POST['billing_phone'])){
                            $billing_phone = sanitize_text_field($_POST['billing_phone']);
                        }
                        
                        if(!empty($_POST['billing_email'])){
                            $billing_email = sanitize_email($_POST['billing_email']);
                        }
                        
                        if(!empty($_POST['vendor_id'])){
                            $vendor_id = sanitize_text_field($_POST['vendor_id']);
                        }
                        
                        if(!empty($_POST['bid_amount'])){
                            $bid_amount = sanitize_text_field($_POST['bid_amount']);
                        }
                        
                        if(!empty($_POST['order_comments'])){
                            $order_comments = sanitize_text_field($_POST['order_comments']);
                        }
                        
                        $pickup_location = $swifno_opt['swifno_pickup_location'];
                        
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
                        
                        
                        // var_dump($_POST);
                        // die();
                        
                           
                        $response = wp_remote_get( $url . 
                        	'?action=bids&auth_token='.$key.
                            '&pickup_location='.$pickup_location.
                        	'&drop_location='.$drop_location.
                        	'&package_category='.$package_category.
                        	'&package_size='.$package_size.
                        	'&insurance='.$insurance.
                        	'&itemvalue='.$itemvalue
                        );
                        

                        $vars = json_decode($response['body'], true); 
                        
                        // var_dump($vars);

                        if($vars['RESPONSECODE'] == 1 && isset($vars['COURIERLIST'])){


                            $token_swifno_opts = get_option('swifno_opts');

                            $token_swifno_opts['swifno_current_token'] = $vars['TOKEN'];

                            update_option('swifno_opts', $token_swifno_opts);


                            if (is_array($vars['COURIERLIST'])){  

                                foreach($vars['COURIERLIST'] as $mydata){

                                    $rate = array(
                                        'id'       => $this->id . $mydata['bid_id'],
                                        'label'    => $mydata['company_name'] .' - '. $mydata['delivery_speed'],
                                        'cost'     => $mydata['bid_amount'],
                                    );
                                    
                                    $this->add_rate( $rate );
                                }  
                            }
                        }
                    }
                }
            }
        }
    }
    add_action( 'woocommerce_shipping_init', 'swifno_shipping_method_init' );
    function add_swifno_shipping_method( $methods ) {
        $methods['swifno_shipping_method'] = 'WC_Swifno_Shipping_Method';
        return $methods;
    }
    add_filter( 'woocommerce_shipping_methods', 'add_swifno_shipping_method' );
}