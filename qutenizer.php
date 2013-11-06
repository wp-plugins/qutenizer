<?php
/* 
	Plugin Name: QutenizeR
	Plugin URI: http://qutenizer.com 
	Description: QutenizeR plugin for WordPress is a funny way to easy create cute & colorful QR codes.
	Author: Dweius 
	Version: 0.1.0 
	Author URI: http://dweius.com
	License: GPLv2 or later
*/  

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

if ( ! function_exists( 'add_action' ) ) {
	
	echo "Hi there!  I'm just a plugin, don't tickle me!";
	
	exit;
	
}

//*************** functionality ***************
	
	add_action( 'admin_menu', 'qutenizer_admin_menu' );

	add_action( 'wp_ajax_qtzr_generate', 'qtzr_generate_callback' );
	
	add_action( 'post_submitbox_misc_actions', 'show_qutenizer_post_box' );	
	
	add_action( 'wp_ajax_qtzr_post_generate', 'qtzr_post_generate' );
	

	
function qtzr_generate_callback(){
		
		check_ajax_referer( 'qtnz_ajax_generate', 'qtzr_anonce', true );
		
		$url = "http://qutenizer.com/wordpress/generator.php";
	
		$url_status = "http://qutenizer.com/wordpress/generated/status.php";
		
		$url_generated = "http://qutenizer.com/wordpress/generated/";
		
		unset($_GET['qtzr_anonce']);
				
		$_GET['txt'] = rawUrlDecode( $_GET['txt'] );
		
		$data = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 1,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => $_GET,
			'cookies' => array()
		);
		
		$c_counter = 0;
		
		$c_end = false;
		
		$message = "Sorry, something went wrong.";
		
		$g_text = $_GET['txt'];
		
		$g_shape = $_GET['shp'];
		
		$g_colors = $_GET['col'];
		
		$g_kolors = $_GET['kol'];
		

      $qtzr_data_r_p = wp_remote_post($url,$data);
		 
		 if ( is_wp_error($qtzr_data_r_p) || ! isset($qtzr_data_r_p['body']) ){
				
				echo 'NOK'.$message;
				
				die();
				
		}
			
		$qtzr_data = explode(";", $qtzr_data_r_p['body']);
		
		if($qtzr_data[0] !== 'OK'){
			
			echo 'NOK'.$message;
				
			die();
				
			}
			
		$gen_code =  trim($qtzr_data[1]);
		
		$data_inm = array();
		
		$data_inm['inm'] = trim($gen_code);
		
		$data['body'] = $data_inm;
		
		sleep(17);
		
		while($c_counter < 4 && !$c_end){
		

		
		$qtzr_data_r_p = wp_remote_post($url_status,$data);
		 
		
		 
		 if ( is_wp_error($qtzr_data_r_p) || ! isset($qtzr_data_r_p['body']) ){
				
				echo 'NOK;'.$message;
				
				die();
				
			}
			
			$qtzr_status = trim($qtzr_data_r_p['body']);
						
			if($qtzr_status === 'NOK'){
			
				sleep(7);
			
			
			}else if($qtzr_status === 'OK'){
			
				$c_end = true;
				
				$generated_dir = plugin_dir_path(__FILE__) . 'generated/';

				$image_name = $gen_code .'.png';
			
				$qtzr_gen_data = wp_remote_post($url_generated . $image_name);
			
		 		if ( is_wp_error($qtzr_gen_data) || ! isset($qtzr_gen_data['body']) ){
				
					echo 'NOK'.$message;
				
					die();
				
				}
			
				file_put_contents( $generated_dir.$image_name, $qtzr_gen_data['body'] );
				
				$g_dtime = date("Y-m-d H:i:s");
			
				$g_data = $g_shape.','. $g_colors.','. $g_kolors . ',' . $g_text;
			
				$g_text = $g_dtime . ' ' . $image_name . ' ' . $g_data . PHP_EOL;
			
				$generated_textfile = $generated_dir . 'generated.txt';
			
				file_put_contents($generated_textfile, $g_text, FILE_APPEND);
			
				echo  "OK;". plugins_url( 'generated/'.$image_name , __FILE__ );
			
				die();
			
		}
		
		$c_counter++;
		
		unset($b_data_a_d);
		
		unset($qtzr_data_r_p);
		
		unset($qtzr_data);
		
		}
		
	echo $message;
	
	die();
	
}

function load_js_scripts($hook) {
			
		if( 'edit.php' != $hook  &&  'qutenizer_admin.php' != $hook ){
				
    		wp_enqueue_script( 'qutenizer-jscolor-script', plugin_dir_url( __FILE__ ) . 'jscolor/jscolor.js' );
    		
    		wp_enqueue_script( 'qutenizer-script', plugin_dir_url( __FILE__ ) . 'js/qutenizer.js' );
    		
    	}
}

function show_qutenizer_post_box(){


 if( function_exists('current_user_can') && ( ( current_user_can( 'edit_posts') && current_user_can( 'upload_files' ) ) || 
		( current_user_can( 'upload_files' ) && current_user_can( 'edit_published_posts' ) ) ) ){

	if( get_option( 'qutenizer_post_direct' ) === 'on'){
		
		   
		$qtzr_last_image = qtzr_get_last_post_generated( get_the_ID() );
	
		$qtzr_image_url = plugins_url( 'images/qutenizer-bgimage.png' , __FILE__ );
	
		$qtzr_limg = 0;
	
		if($qtzr_last_image[1] > -1){
		
			$qtzr_image_url_a = wp_get_attachment_image_src( $qtzr_last_image[0] );
		
			$qtzr_image_url = $qtzr_image_url_a[0];
		
		}
		
		
		$hinputs = '<input type="hidden" id="qtzr_post_shape" value="'.get_option( 'qutenizer_shape' ).'" />'.
				'<input type="hidden" id="qtzr_post_colors" value="'.get_option( 'qutenizer_color_type' ).'" />'.
				'<input type="hidden" id="qtzr_post_kolors" value="'. strtolower( get_option( 'qutenizer_color_one' ) ).
				'-'. strtolower( get_option( 'qutenizer_color_two' ) ) .
				'-'. strtolower( get_option( 'qutenizer_color_three' ) ).'" />'.
				'<input type="hidden" id="qtzr_post_text" value="'. htmlentities(get_permalink(get_the_ID())) .'" />'.
				'<input type="hidden" id="qtzr_pid" value="'. get_the_ID() .'" />'.
				'<input type="hidden" id="qtzr_aurl" value="'. get_site_url() .'/wp-admin/admin-ajax.php" />';
  	
  		if ( function_exists('wp_create_nonce') ) {
  		
				$gen_nonce = wp_create_nonce('qtnz_ajax_post_generate');
				
				$hinputs = $hinputs . '<input type="hidden" id="qtzr_anonce" value="'. $gen_nonce .'" />';
		}
	
		echo '<div class="misc-pub-section"><table><form><tr><td>'.$hinputs.
			'<img id="qtzr_image" src="'. $qtzr_image_url . '" style="width:90px; height:90px"/>'.
			'</td><td style="text-align: center;vertical-align: top;"><span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>QutenizeR</b></span><br/>'.
			'&nbsp;<br/>&nbsp;<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="post_generate" type="button" class="button" name="Submit" value="Generate" />'.
			'&nbsp;&nbsp;&nbsp;</td></tr><tr><td colspan="2">&nbsp;<span style="color: #AF3030;font-weight: bold;" id="qtzr_message" >&nbsp;</span></td></tr></form></table></div>';
	}
 }
}

function qtzr_get_last_post_generated($g_pid){
			
			
		$qtzr_last_image_id = -1;
			
		$qtzr_last_image_c = -1;
			
		$qtzr_images_a = get_attached_media( 'image', $g_pid );
			
		foreach($qtzr_images_a as &$qtzr_image){
									
			$aux_name = $qtzr_image -> post_name;
					
			if(substr($aux_name,0,4) === 'qtzr'){
						
				$qtzr_aux_pos_a = explode("-",$aux_name);
						
				if( isset($qtzr_aux_pos_a[1]) && is_numeric( $qtzr_aux_pos_a[1] )){
							
					$qtzr_aux_pos = $qtzr_aux_pos_a[1];
					
					$qtzr_aux_c = intval($qtzr_aux_pos);
	
					if( $qtzr_aux_c > $qtzr_last_image_c){
							
						$qtzr_last_image_c = $qtzr_aux_c;
							
						$qtzr_last_image_id = 	$qtzr_image -> ID;	
					}			
				}
			}
		}	
			
		$aux_a = array();
			
		$aux_a[0] = $qtzr_last_image_id;
			
		$aux_a[1] = $qtzr_last_image_c;
			
		return $aux_a;		
}

function qtzr_post_generate(){
		
		check_ajax_referer( 'qtnz_ajax_post_generate', 'qtzr_anonce', true);
		
		$url = "http://qutenizer.com/wordpress/generator.php";
	
		$url_status = "http://qutenizer.com/wordpress/generated/status.php";
		
		$url_generated = "http://qutenizer.com/wordpress/generated/";
		
		unset($_GET['qtzr_anonce']);
		
		$_GET['txt'] = rawUrlDecode( $_GET['txt'] );
		
		$data = array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 1,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => $_GET,
			'cookies' => array()
		);
		
		$c_counter = 0;
		
		$c_end = false;
		
		$message = "Sorry, something went wrong.";
		
		$g_pid = $_GET['pid'];
		
		$g_text = $_GET['txt'];
		
		$g_shape = $_GET['shp'];
		
		$g_colors = $_GET['col'];
		
		$g_kolors = $_GET['kol'];
		
					
      $qtzr_data_r_p = wp_remote_post($url,$data);
		 
		 if ( is_wp_error($qtzr_data_r_p) || ! isset($qtzr_data_r_p['body']) ){
				
				echo 'NOK'.$message;
				
				die();
				
		}
			
		$qtzr_data = explode(";", $qtzr_data_r_p['body']);
		
		if($qtzr_data[0] !== 'OK'){
			
			echo 'NOK'.$message;
				
			die();
				
			}
		
		$gen_code =  trim($qtzr_data[1]);
		
		$data_inm = array();
		
		$data_inm['inm'] = trim($gen_code);
		
		$data['body'] = $data_inm;
		
		sleep(17);
		
		while($c_counter < 4 && !$c_end){
			
		$qtzr_data_r_p = wp_remote_post($url_status,$data);
		
		$qtzr_status = trim($qtzr_data_r_p['body']);
		
		
		  if($qtzr_status === 'NOK'){
			
				sleep(7);
				
			
		  }else if($qtzr_status === 'OK'){
			
			$c_end = true;
			
			$generated_dir = plugin_dir_path(__FILE__) . 'generated/';
			
			
			$image_name = $gen_code .'.png';
			
			$qtzr_gen_data = wp_remote_post($url_generated . $image_name);
			
		 	if ( is_wp_error($qtzr_gen_data) || ! isset($qtzr_gen_data['body']) ){
				
				echo 'NOK'.$message;
				
				die();
				
			}
									
			file_put_contents( $generated_dir.$image_name, $qtzr_gen_data['body'] );
			
			$g_dtime = date("Y-m-d H:i:s");
			
			$g_data = $g_shape.','. $g_colors.','. $g_kolors . ',' . $g_text;
			
			$g_text = $g_dtime . ' ' . $image_name . ' ' . $g_data . PHP_EOL;
			
			$generated_textfile = $generated_dir . 'generated.txt';
			
			file_put_contents($generated_textfile, $g_text, FILE_APPEND);
			
			$qtzr_last_image_id;
			
			$qtzr_last_image_c = -1;
			
			$qtzr_images_a = get_attached_media( 'image', $g_pid );
			
			foreach($qtzr_images_a as &$qtzr_image){
									
					$aux_name = $qtzr_image -> post_name;
					
					if(substr($aux_name,0,4) === 'qtzr'){
						
						$qtzr_aux_pos_a = explode("-",$aux_name);
						
						if( isset($qtzr_aux_pos_a[1]) && is_numeric( $qtzr_aux_pos_a[1] )){
							
							$qtzr_aux_pos = $qtzr_aux_pos_a[1];
					
							$qtzr_aux_c = intval($qtzr_aux_pos);
	
						if( $qtzr_aux_c > $qtzr_last_image_c){
							
							$qtzr_last_image_c = $qtzr_aux_c;	
						}			
					}
				}
			}	
						
			$g_lim_i = $qtzr_last_image_c + 1;
			
			$g_title_s_san = preg_replace('/[^a-zA-Z0-9\']/', '_', get_the_title($g_pid) );
			
			$g_title_s = str_replace("'", '', $g_title_s_san);

			$g_loaded = media_sideload_image( $url_generated . $image_name, $g_pid , 'qtzr-' . $g_lim_i . '-' . substr( $g_title_s, 0, 24 ) . '-' . substr($image_name,0, 7));
			
			$demi_g_loaded = substr($g_loaded, stripos($g_loaded, "src='") + strlen("src='"),  stripos($g_loaded, "'", stripos($g_loaded, "src='") + strlen("src='") + 1) - (stripos($g_loaded, "src='") + strlen("src='")));
			
			echo 'OK;'.$demi_g_loaded;
			
			die();
			
		}
		
		$c_counter++;
		
		unset($qtzr_data_r_p);
		
		unset($qtzr_data);
		
	}
		
	echo 'NOK;'.$message;
	
	die();
}

//*************** Maintenance functions *********

include_once  plugin_dir_path(__FILE__) . 'qutenizer_maintenance.php';

//de-/activation/uninstall procedure...

register_activation_hook( __FILE__, array( 'QutenizerInit', 'on_activate' ) );

register_deactivation_hook( __FILE__, array( 'QutenizerInit', 'on_deactivate' ) );


//*************** Admin function ***************

function qutenizer_admin_menu() { 
		
 if( function_exists('current_user_can') && current_user_can( 'manage_options') ) {
	
	$page = add_submenu_page( 'options-general.php', // The parent page of this menu
                              __( 'QutenizeR', 'qutenizer' ), // The Menu Title
                              __( 'QutenizeR', 'qutenizer' ), // The Page title
              						'manage_options', // The capability required for access to this item
              						'qutenizer-options', // the slug to use for the page in the URL
                              'qutenizer_manager' // The function to call to render the page
                           	);
                           	
   add_action('admin_print_scripts-' . $page, 'qutenizer_admin_scripts');
	
	wp_register_script( 'qutenizer-script', plugins_url( 'js/qutenizer.js' , __FILE__), array( 'jquery' ) );

	wp_register_script( 'qutenizer-jscolor-script', plugins_url( 'jscolor/jscolor.js',  __FILE__ )  );

 }
 
 if( function_exists('current_user_can') && ( ( current_user_can( 'edit_posts') && current_user_can( 'upload_files' ) ) || 
		( current_user_can( 'upload_files' ) && current_user_can( 'edit_published_posts' ) ) ) ){


	add_action('admin_print_scripts-post.php', 'qutenizer_post_scripts');
	
	add_action('admin_print_scripts-post-new.php', 'qutenizer_post_scripts');
	
	}
	
}

function qutenizer_admin_scripts() {

   wp_enqueue_script( 'qutenizer-script' );
    
   wp_enqueue_script( 'qutenizer-jscolor-script' );
    
}

function qutenizer_post_scripts(){
	
	wp_enqueue_script( 'qutenizer-script' );

}
function qutenizer_manager(){
	
	include_once plugin_dir_path(__FILE__) .  'qutenizer_admin.php' ;

}
     
function qutenizer_admin_options() {
	

	wp_register_script( 'qutenizer-script', plugins_url( 'js/qutenizer.js' , __FILE__) );

	wp_register_script( 'qutenizer-jscolor-script', plugins_url( 'jscolor/jscolor.js',  __FILE__ )  );
	
}
?>