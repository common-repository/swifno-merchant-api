<?php

function swifno_shipping_deactivate_plugin(){
    global $wpdb;
    $drop = $wpdb->query("DROP TABLE IF EXISTS " . swifno_shipping_table());
}