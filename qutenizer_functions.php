<?php

/**
 * lets serialize the qutenizer versioning...
 */
function serialize_version( $version ){
	
	if( $version === '0.1.0' ) {
		//first release - beta
		return 1;
	} else  {
		//unknown
		return 0;
	}
	
}
?>
