<?php

add_action('admin_head', 'wt_add_head');

/**
 * Change the icon on every page where theme use.
 */
function wt_add_head() {
	?>
<script>
var theme_admin_assets_uri="<?php echo THEME_ADMIN_ASSETS_URI;?>";
</script>
	<?php
}
add_action('admin_enqueue_scripts', 'wt_admin_register_script');
function wt_admin_register_script(){
	wp_enqueue_script('jquery-fitvids',THEME_ADMIN_ASSETS_URI . '/js/jquery.fitvids.js');
	wp_enqueue_script('jquery-outside-events',THEME_ADMIN_ASSETS_URI . '/js/jquery.ba-outside-events.min.js',array('jquery'),'1.1');	
	wp_enqueue_script('jquery-tools-rangeinput',THEME_ADMIN_ASSETS_URI . '/js/rangeinput.js',array('jquery'),'1.2.5');
	wp_enqueue_script('jquery-tools-tooltip',THEME_ADMIN_ASSETS_URI . '/js/tooltip.js',array('jquery'),'1.2.5');
	wp_enqueue_script('jquery-tools-validator',THEME_ADMIN_ASSETS_URI . '/js/validator.js',array('jquery'),'1.2.7');
	//wp_enqueue_script('mColorPicker',THEME_ADMIN_ASSETS_URI . '/js/mColorPicker.js',array('jquery'),'1.0 r34');
	wp_enqueue_script('mColorPicker',THEME_ADMIN_ASSETS_URI . '/js/jquery.colorInput.js',array('jquery'),'0.1.0');
	wp_enqueue_script('chosen',THEME_ADMIN_ASSETS_URI . '/js/chosen.jquery.js',array('jquery'),'0.9.8');
	wp_enqueue_script('iphone-style-checkboxes',THEME_ADMIN_ASSETS_URI . '/js/iphone-style-checkboxes.js',array('jquery'));
	wp_enqueue_script('iphone-style-tri-toggle',THEME_ADMIN_ASSETS_URI . '/js/iphone-style-tri-toggle.js',array('jquery'));
	wp_enqueue_script('jquery-tablednd',THEME_ADMIN_ASSETS_URI . '/js/jquery.tablednd.js',array('jquery'),'0.5');
	wp_enqueue_script('jquery-elastic',THEME_ADMIN_ASSETS_URI . '/js/jquery.elastic.min.js',array('jquery'),'1.6.11');
	wp_enqueue_script('theme-script', THEME_ADMIN_ASSETS_URI . '/js/script.js');
}
if(wt_is_options() || wt_is_post_type()){
	add_action('admin_enqueue_scripts', 'wt_admin_add_script');
}
function wt_admin_add_script() {
	wp_enqueue_script('theme-script');
	add_thickbox();
	
	global $wp_version;
	if(wt_is_options() && version_compare($wp_version, "3.5", '>=')){
		wp_enqueue_media();
	}
}

if(is_admin()){
	add_action('admin_enqueue_scripts', 'wt_admin_add_style');
}
function wt_admin_add_style() {
	wp_enqueue_style('thickbox');
	wp_enqueue_style('theme-style', THEME_ADMIN_ASSETS_URI . '/css/admin.css');
	wp_enqueue_style('theme-style-chosen', THEME_ADMIN_ASSETS_URI.'/css/chosen.css', false, false, 'all');
}