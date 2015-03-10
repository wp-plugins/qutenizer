//qutenizer.js
function qutenize_check(){

	var check = qtzr_checkdata();
	var message = check.substring(1);
	
	if(check.charAt(0) == 'N'){
		
		qtzr_message_show(check.substring(1));
		document.getElementById('generate').value = 'Generate';
		document.getElementById('generate').disabled = false;
		return -1;		
	}
	return 0;
}
function qtzr_message_show(_qtzr_mssg){
	
	document.getElementById('qtzr_message').innerHTML = _qtzr_mssg;
	setTimeout("qtzr_message_fadeout()", 7000);
	
}
function qtzr_message_fadeout(){
	
	document.getElementById('qtzr_message').innerHTML = "";
}
	
jQuery(document).ready(function(){
	
	jQuery('#generate').click(function() { 
		document.getElementById('generate').disabled = true;
		document.getElementById('generate').value = 'Busy... ...';
		if(qutenize_check() < 0) return;
		
		jQuery.ajax({
			 url: qtzr_getUrl(),
			 dataType: 'text',
    		 async: true,
			 data: { 'action': 'qtzr_generate',txt: qtzr_getText(), shp: qtzr_getShape(), col: qtzr_getColorType(), kol: qtzr_getColors(), 'qtzr_anonce': qtzr_get_gtd_nonce() },
			
			 }).done(function(qtzr_response){ 
				var _qtzr_response = qtzr_response.split(";");
				if(_qtzr_response[0] == 'OK'){
						document.getElementById('qtzr_image').src = _qtzr_response[1];
				 }else{
						qtzr_message_show(_qtzr_response[1]);	
				 } 
			}).fail(function(){
				qtzr_message_show("Sorry. Something went wrong.");
			}).always(function(){
				document.getElementById('generate').value = 'Generate';
				document.getElementById('generate').disabled = false;
			});
	})
});
jQuery(document).ready(function(){
	
	jQuery('#post_generate').click(function() { 
		document.getElementById('post_generate').disabled = true;
		document.getElementById('post_generate').value = 'Busy... ...';
		
		jQuery.ajax({
			 url: qtzr_getUrl(),
			 dataType: 'text',
    		 async: true,
			 data: { 'action': 'qtzr_post_generate', txt: qtzr_getPost_Text(), shp: qtzr_getPost_Shape(), col: qtzr_getPost_ColorType(), 
			 		pid: qtzr_get_pid(), kol: qtzr_getPost_Colors(), 'qtzr_anonce': qtzr_get_gtd_nonce() },
			
			 }).done(function(qtzr_response){ 
				var _qtzr_response = qtzr_response.split(";");
				if(_qtzr_response[0] == 'OK'){
						document.getElementById('qtzr_image').src =  _qtzr_response[1]; 
						qtzr_message_show('Image loaded to Media Libray');				
				 }else{
						qtzr_message_show(_qtzr_response[1]);	
				 } 
			}).fail(function(){
				qtzr_message_show("Sorry. Something went wrong.")
			}).always(function(){
				document.getElementById('post_generate').value = 'Generate';
				document.getElementById('post_generate').disabled = false;
			});
	})
});
function get_crb(radiogroup){
	
	for(_c = 0; _c < radiogroup.length; _c++){
		
		var raux = document.getElementsByName(radiogroup)[_c];	
		
		if(raux.checked){
			
			return raux; 	
					
			}
	}
}
function qtzr_checkdata(){
	
	var sok = 'O';
	var nok = 'N';
	
	var t = document.getElementById('qtzr_text');
	
	if(!t || !t.value || t.value == ''){
		return nok+'Text cannot be empty';
	}
	var digest = t.value.replace(/^\s+|\s+$/gi,'');
	if(digest == ''){
		return nok+'Text cannot be empty';
	}else	if(digest.length > 255){
		return nok+'Text too long (255 chars Max)';
	}
	
	var s = get_crb('qtzr_shape');
	
		if(!s || !s.value || s.value == ''){
		return nok+'Shape type must be set';
	}
	
	var c = get_crb('qtzr_colors');

	if(!c || !c.value || c.value == ''){
		return nok+'Color type must be set';
	}
	
	var ki = document.getElementById('qtzr_colone');
	
	if( !ki || !ki.color || ki.value == ''){
			
		return nok+'Colors must be properly set';
	}	
	if(qtzr_getColorType() == 'duet' || qtzr_getColorType() == 'splash'){
		
		var kii = document.getElementById('qtzr_coltwo');
		if( !kii || !kii.color || kii.value == ''){
			
			return nok+'Colors must be properly set';
		}	
	}
	if(qtzr_getColorType() == 'splash'){
		
		var kiii = document.getElementById('qtzr_colthree');
		if( !kiii || !kiii.color || kiii.value == ''){
			
			return nok+'Colors must be properly set';
		}	
	}
			
	return sok+'Generating...';
}
function qtzr_getText(){
	
	var stext =  document.getElementById('qtzr_text').value;
	return encodeURIComponent( stext );
}
function qtzr_getPost_Text(){
	
	var stext = document.getElementById('qtzr_post_text').value;
	return encodeURIComponent( stext );
}
function qtzr_getShape(){
	
	return get_crb('qtzr_shape').value;
}
function qtzr_getPost_Shape(){
	
	return document.getElementById('qtzr_post_shape').value;
}
function qtzr_getColorType(){
	
	return get_crb('qtzr_colors').value;
}
function qtzr_getPost_ColorType(){
	
	return document.getElementById('qtzr_post_colors').value;
}
function qtzr_getColors(){
		
	return document.getElementById('qtzr_colone').color+'-'+document.getElementById('qtzr_coltwo').color+'-'+
			document.getElementById('qtzr_colthree').color;
}
function qtzr_getPost_Colors(){
		
	return document.getElementById('qtzr_post_kolors').value;
}
function qtzr_getUrl(){return document.getElementById('qtzr_aurl').value};
function qtzr_get_gtd_nonce(){return document.getElementById('qtzr_anonce').value};
function qtzr_get_pid(){return document.getElementById('qtzr_pid').value};
