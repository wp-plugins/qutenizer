<?php


	if( !defined( 'ABSPATH' ) && ! defined( 'WP_UNINSTALL_PLUGIN') ){
		exit();
	}
	
	 //  ok, you want nothing from me... good bye!
	  
	delete_option( 'qutenizer_version' );
	
	 // we now remove options... too sad.
	
        
    delete_option( 'qutenizer_status' );
    delete_option( 'qutenizer_shape' );
    delete_option( 'qutenizer_color_type' );
    delete_option( 'qutenizer_color_one' );
    delete_option( 'qutenizer_color_two' );
    delete_option( 'qutenizer_color_three' );
    delete_option( 'qutenizer_post_direct' );
    

?>
