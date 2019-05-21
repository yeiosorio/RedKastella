<?php
/**
 * Remove elements and params from the default Visual Composer
 *
 */

// ! Removing unwanted composer elements
// --------------------------------------------	

if ( function_exists('vc_remove_element') ) {
	if ( !function_exists( 'wt_vc_modules_remove' )) {
		function wt_vc_modules_remove() {
			/* vc_remove_element("vc_widget_sidebar"); */
			vc_remove_element("vc_wp_search");
			vc_remove_element("vc_wp_meta");
			vc_remove_element("vc_wp_recentcomments");
			vc_remove_element("vc_wp_calendar");
			vc_remove_element("vc_wp_pages");
			vc_remove_element("vc_wp_tagcloud");
			vc_remove_element("vc_wp_custommenu");
			vc_remove_element("vc_wp_text");
			vc_remove_element("vc_wp_posts");
			vc_remove_element("vc_wp_links");
			vc_remove_element("vc_wp_categories");
			vc_remove_element("vc_wp_archives");
			vc_remove_element("vc_wp_rss");			
			
			vc_remove_element("vc_empty_space");
			vc_remove_element("vc_custom_heading");
		} // End function
	} // End if
	add_action( 'init', 'wt_vc_modules_remove' );
} // End if

// ! Removing certain composer params
// --------------------------------------------	

if ( function_exists('vc_remove_param') ) {
	
	// Rows
	vc_remove_param( 'vc_row', 'font_color' );
	vc_remove_param( 'vc_row', 'css' );
	
	// Columns
	vc_remove_param( 'vc_column', 'font_color' );
	vc_remove_param( 'vc_column', 'css' );
}