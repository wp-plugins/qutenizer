<?php  
	
	//check the user allowed role...
	if( ! current_user_can( 'administrator' ) ){
		echo "You shouldn't be here...";
		exit;
	}
		
	/**
	 * if there is something to save...
	 */
    if( isset($_POST['save']) && check_admin_referer( 'qutenizer-settings', 'save' ) ) {
			
			
		$correct = true;
		
		//****Shape option*******
		if( isset( $_POST['qtzr_shape'] ) ){
			
			//get shape option	
			$qtzr_shape = $_POST['qtzr_shape'];
			
			//checking shape option is ok
			if( $qtzr_shape !== 'pixel' && $qtzr_shape !== 'social' && $qtzr_shape !== 'tears' && $qtzr_shape !== 'liquid') {
				//if not, display message and continue.
		?>  
				<div class="error"><p><strong><?php _e( 'Sahpe unknown. Set to default.' ); ?></strong></p></div>  
		<?php		
				$qtzr_shape = 'social';
				$correct = false;
			}	
			// update option		
			update_option( 'qutenizer_shape' , $qtzr_shape );
		}

		//****Color type option*******
		if( isset( $_POST['qtzr_colors'] ) ){
			
			//get color option	
			$qtzr_colors = $_POST['qtzr_colors'];
			
			//checking  option is ok
			if( $qtzr_colors !== 'solid' && $qtzr_colors !== 'duet' && $qtzr_colors !== 'splash' ) {
				//if not, display message and continue.
		?>  
				<div class="error"><p><strong><?php _e( 'Color type unknown. Set to default.' ); ?></strong></p></div>  
		<?php		
				$qtzr_colors = 'duet';
				$correct = false;
			}	
			// update option		
			update_option( 'qutenizer_color_type' , $qtzr_colors );
		}
		
		//****Colors*******
		if( isset( $_POST['qtzr_colone'] ) && isset( $_POST['qtzr_coltwo'] ) && isset( $_POST['qtzr_colthree'] ) ) {
			
			//get colors
			$qtzr_colone = $_POST['qtzr_colone'];
			$qtzr_coltwo = $_POST['qtzr_coltwo'];
			$qtzr_colthree = $_POST['qtzr_colthree'];
			
			//checking colors are ok
			if( strlen($qtzr_colone) !== 6 && strlen($qtzr_coltwo) !== 6 && strlen($qtzr_colthree) !== 6) {
				//if not, display message and continue.
		?>  
				<div class="error"><p><strong><?php _e( 'Undefined colors. Set to default.' ); ?></strong></p></div>  
		<?php		
				$qtzr_colone = 'FE7A59';
				$qtzr_coltwo = 'FAC637';
				$qtzr_colthree = '59C1FE';
				$correct = false;
			}	
			// update option		
			update_option( 'qutenizer_color_one' , $qtzr_colone );
			update_option( 'qutenizer_color_two' , $qtzr_coltwo);
			update_option( 'qutenizer_color_three', $qtzr_colthree );
		}
		
		//****Post-edit option*******
		if( isset( $_POST['qtzr_post_edit'] ) ){
			
			//get post edit option	
			$qtzr_post_edit = $_POST['qtzr_post_edit'];
			
			//checking option is ok
			if( $qtzr_post_edit !== 'on' && $qtzr_post_edit !== 'off' ) {
				//if not, display message and continue.
		?>  
				<div class="error"><p><strong><?php _e( 'Edit-Post option unkown. Set to default.' ); ?></strong></p></div>  
		<?php		
				$qtzr_post_edit = 'on';
				$correct = false;
			}	
			// update option		
			update_option( 'qutenizer_post_direct' , trim($qtzr_post_edit) );
		}
	 		
     if( $correct ) {
				//if everithing went ok... display a nice message.
		?>  
            <div class="updated"><p><strong><?php _e( 'Options saved.' ); ?></strong></p></div>  
        <?php 			
 		}
 	
	}

		//show page...

		$qtzr_shape = get_option( 'qutenizer_shape' );
		$qtzr_colors = get_option( 'qutenizer_color_type' );
		$qtzr_colone = get_option( 'qutenizer_color_one' );
		$qtzr_coltwo = get_option( 'qutenizer_color_two' );
		$qtzr_colthree = get_option( 'qutenizer_color_three' );
		$qtzr_post_edit = get_option( 'qutenizer_post_direct' );
	
		?>
	
    <div class="wrap">  
        <?php echo "<h2>" . __( 'QutenizeR Manager' ) . "</h2>"; ?>  
	<hr />  
	<form id="qtzr-admin-settings" method="post" action="">
		<?php
			if ( function_exists('wp_nonce_field') ) {
				wp_nonce_field('qutenizer-settings', 'save');
			}
		?> 
        <p/>
        <p><h3 style="color: #369;"><em><?php echo _e( 'General Settings' ) ?></em></h3></p>
        <p><span><strong><?php _e("Shape: " ); ?></strong></span>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_shape" value="pixel" <?php echo ($qtzr_shape==='pixel'?'checked="checked"':'') ?> />&nbsp;<?php _e("Pixels" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_shape" value="social" <?php echo ($qtzr_shape==='social'?'checked="checked"':'') ?> />&nbsp;<?php _e("Social" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_shape" value="tears" <?php echo ($qtzr_shape==='tears'?'checked="checked"':'') ?> />&nbsp;<?php _e("Tears" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_shape" value="liquid" <?php echo ($qtzr_shape==='liquid'?'checked="checked"':'')?> />&nbsp;<?php _e("Liquid" ); ?></p>
        <p></p>
        <p><span><strong><?php _e("Color type: " ); ?></strong></span>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_colors" value="solid" <?php echo ($qtzr_colors==='solid'?'checked="checked"':'') ?> />&nbsp;<?php _e("Solid" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_colors" value="duet" <?php echo ($qtzr_colors==='duet'?'checked="checked"':'') ?> />&nbsp;<?php _e("Duet" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_colors" value="splash" <?php echo ($qtzr_colors==='splash'?'checked="checked"':'') ?> />&nbsp;<?php _e("Splash!" ); ?></p>
        <p></p>
		 <p><span><strong><?php _e("Colors: " ); ?></strong></span>&nbsp;&nbsp;<input class="color {pickerClosable:true,pickerFace:4,pickerBorderColor:'#FEB3B6',pickerFaceColor:'#DFEAFA'}" 
				style="width: 70px; margin-left: 7px;" id="qtzr_colone" name="qtzr_colone" value="<?php echo $qtzr_colone ?>" />&nbsp;&nbsp;<input class="color {pickerClosable:true,pickerFace:4,pickerBorderColor:'#FEB3B6',pickerFaceColor:'#DFEAFA'}" 
				style="width: 70px; margin-left: 7px;" id="qtzr_coltwo" name="qtzr_coltwo"  value="<?php echo $qtzr_coltwo ?>" />&nbsp;&nbsp;<input class="color {pickerClosable:true,pickerFace:4,pickerBorderColor:'#FEB3B6',pickerFaceColor:'#DFEAFA'}" 
				style="width: 70px; margin-left: 7px;" id="qtzr_colthree" name="qtzr_colthree"  value="<?php echo $qtzr_colthree ?>" /></p>
	 	<p><strong><?php _e("Enable generation from Post edit pages: " ); ?></strong>&nbsp;&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_post_edit" value="on" <?php echo ($qtzr_post_edit==='on'?'checked="checked"':'') ?> />&nbsp;<?php _e("On" ); ?>&nbsp;&nbsp;<input 
        		type="radio" name="qtzr_post_edit" value="off" <?php echo ($qtzr_post_edit==='off'?'checked="checked"':'') ?> />&nbsp;<?php _e("Off" ); ?></p>     
        <p><input type="submit" class="button-primary" name="Submit" value="<?php _e( 'Update Settings' ) ?>" /></p>
        <hr />
        </form>
        <form id="qtzr-generate-now" action='' method='POST'>
		<?php
			if ( function_exists('wp_create_nonce') ) {
				$gen_nonce = wp_create_nonce('qtnz_ajax_generate');
			}
		?> 
		<input type="hidden" id="qtzr_anonce" value="<?php echo $gen_nonce ?>" />
		<input type="hidden" id="qtzr_aurl" value="<?php echo get_site_url() ?>/wp-admin/admin-ajax.php" />
		<p/>
        <p><h3 style="color: #369;"><em><?php echo _e( 'Generate now!' ) ?></em></h3></p> 
        <p><strong><?php _e("Text : " ); ?></strong>&nbsp;<input type="text" id="qtzr_text" name="qtzr_text" value="" maxlength="250" size="55" /><?php _e(" Max. 250 chars." ); ?></p> 
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color: #AF3030;font-weight: bold;" id="qtzr_message">&nbsp;</span></p>
        <p><div id="icanvas" style="position:relative; width:290px; height:290px;text-align: center; margin:5px; overflow:hidden;">
				<img id="qtzr_image" src="<?php echo plugins_url( 'images/qutenizer-bgimage.png' , __FILE__ )?>" style="width:290px; height:290px"/>
			</div>
        </p>
        <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="button-primary" id="generate"  value="<?php _e( 'Generate' ) ?>" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
        <hr />
        </form>
       <p>
        </p>   
 </div> 
