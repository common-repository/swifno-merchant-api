<?php 

function swifno_test_mode(){
    return 'https://swifno.com/v1/api.php';
}

function swifno_live_mode(){
    return 'https://swifno.com/v1/api.php';
}

function swifno_shipping_state_mode($mode){
    if($mode == 'test'){
    	return swifno_test_mode();
    }

    if($mode == 'live'){
    	return swifno_live_mode();
    }
}