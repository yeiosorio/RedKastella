<?php

/**
 * Add new params to the vc composer
 *
 */

// Leave file if the vc_add_param parameter doesn't exist
if ( !function_exists('vc_add_param') ) {
	return;
}
 
		
/**
	Raw HTML
**/
vc_add_param("vc_raw_html", array(			
	'type'                          => 'textfield',
	'heading'					    => __( 'Extra class name', 'wt_vcsc' ),
	'param_name' 					=> 'el_class',
	'description'                   => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc' )
));

?>