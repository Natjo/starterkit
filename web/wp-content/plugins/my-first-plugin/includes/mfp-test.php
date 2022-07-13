<?php
add_action( 'wp_ajax_test', 'test_callback' );
add_action( 'wp_ajax_nopriv_test', 'test_callback' );
function test_callback() { 
  global $wpdb;
  checkNonce('test_nonce');
	//check_ajax_referer( 'test_nonce' , '_ajax_nonce' );
	echo "This is ajax message 1234";
	//wp_die(); // this is required to terminate immediately and return a proper response
}

?>
