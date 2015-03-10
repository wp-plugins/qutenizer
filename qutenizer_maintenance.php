<?php
if ( ! class_exists( 'QutenizeRInit' ) ) :
/**
 * This class triggers functions that run during activation/deactivation & uninstallation
 * 
 */
class QutenizeRInit {

    function __construct( $case = false ) {
		
		    if ( ! $case )
            wp_die( 'Busted! You should not call this class directly', 'Doing it wrong!' );

        switch( $case ) {
			
            case 'activate' :
                // add_action calls and else
                $this->activate_cb();
                break;

            case 'deactivate' : 
                // reset the options
                $this->deactivate_cb();
                break;

            case 'uninstall' : 
                // delete the tables
                $this->uninstall_cb();
                break;
                
            default : return;
        }
    }

    /**
     * Set up tables, add options, etc. - All preparation that only needs to be done once
     */
    function on_activate() {
		
        new QutenizeRInit( 'activate' );
    }

    /**
     * Do nothing like removing settings, etc. 
     * The user could reactivate the plugin and wants everything in the state before activation.
     * Take a constant to remove everything, so you can develop & test easier.
     */
    function on_deactivate() {
		
        new QutenizeRInit( 'deactivate' );
    }

    /**
     * Remove/Delete everything - If the user wants to uninstall, then he wants the state of origin.
     * 
     * Will be called when the user clicks on the uninstall link that calls for the plugin to uninstall itself
     */
    function on_uninstall() {
		
        // important: check if the file is the one that was registered with the uninstall hook (function)
        if ( __FILE__ != WP_UNINSTALL_PLUGIN )
            return;

        new QutenizeRInit( 'uninstall' );
    }

    function activate_cb() {

        //include common functions
        include_once  plugin_dir_path( __FILE__ ) . 'qutenizer_functions.php' ;
        
        /**
         * we now add QutenizeR internals
         */
        $upgrade = false;

        $generated = "generated";
        $generated_dir = plugin_dir_path( __FILE__ ) . $generated;
        $generated_file = $generated_dir . "/generated.txt";
        
        $generated_header_row_1 = "###############################################";
        $generated_header_row_2 = " ###                                       ### ";
        $generated_header_row_3 = "  ###   QutenizeR Plugin for WordPRess    ###  ";
        $generated_header_row_4 = "  ###  2013·2015   by @dweius    enjoy!   ###  ";
        
        $generated_header = $generated_header_row_1 . PHP_EOL . $generated_header_row_2 . PHP_EOL .
        			$generated_header_row_3 .PHP_EOL . $generated_header_row_2 .PHP_EOL . $generated_header_row_4 .PHP_EOL .
        			$generated_header_row_2 . PHP_EOL . $generated_header_row_1 .PHP_EOL;
                		         
		  file_put_contents($generated_file, $generated_header);
				
				
		  //*** we continue with options
			
			update_option ( 'qutenizer_version', '0.2.0' );

			update_option( 'qutenizer_shape',  'tears' );
			update_option( 'qutenizer_color_type',  'duet' );
			update_option( 'qutenizer_color_one',  '5E19FF');
			update_option( 'qutenizer_color_two',  'FFA136');
			update_option( 'qutenizer_color_three',  'FF42AD');
			
			update_option( 'qutenizer_post_direct',  'on' );
									
   	 //end of activate.

    }

function deactivate_cb() {
		
		//
    }

}
endif;

?>
