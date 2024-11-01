<?php 

function swifno_shipping_admin_menus(){
	add_menu_page('Swifno Shipping Merchant', 'Swifno Merchant', 'manage_options', 'swifno-settings', 'swifno_shipping_settings_page', 'dashicons-book-alt', 26);

    add_submenu_page("swifno-shipping-list", 'Settings', 'Settings', 'manage_options', 'swifno-settings', 'swifno_shipping_settings_page');
    
    // add_submenu_page("swifno-shipping-list", 'Delivery History ', 'Delivery History', 'manage_options', 'set-swifno-shipping-merchant', 'delivery_list');
}