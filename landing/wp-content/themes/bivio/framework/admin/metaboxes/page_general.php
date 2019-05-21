<?php
$config = array(
	'title' => sprintf(__('Page General Options','wt_admin'),THEME_NAME),
	'id' => 'page_general',
	'pages' => array('page','post'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'low',
);
function get_sidebar_options(){
	$sidebars = wt_get_option('sidebar','sidebars');
	if(!empty($sidebars)){
		$sidebars_array = explode(',',$sidebars);
		
		$options = array();
		foreach ($sidebars_array as $sidebar){
			$options[$sidebar] = $sidebar;
		}
		return $options;
	}else{
		return array();
	}
}
$options = array(
	array(
		"name" => __("Page Intro Area Type",'wt_admin'),
		"desc" => __("Choose which type of header area you want to display on this page. Static images / videos are setted in the \"Featured Image\" / \"Whoathemes Featured Video\" areas.",'wt_admin'),
		"id" => "_intro_type",
		"options" => array(
			"default" => "Default",
			"title" => "Title only",
			"custom" => "Custom text only",
			"title_custom" => "Title with custom text",
			//"slideshow" => "Slideshow",
			/*"static_image" => "Static Image",
			"static_video" => "Static Video",*/
			"disable" => "Disable",
		),
		"default" => "default",
		"chosen" => "true", 
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "intro_title",
		"group_class" => "intro_type",
	),
	array(
		"name" => __("Page Intro Custom Title",'wt_admin'),
		"desc" => __('If you enter a text here, this will override the default header title.','wt_admin'),
		"id" => "_custom_title",
		"default" => "",
		"class" => 'full',
		"type" => "wt_text"		
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "intro_text",
		"group_class" => "intro_type",
	),
	array(
		"name" => __("Page Intro Custom Text",'wt_admin'),
		"desc" => __('If you enter a text here, this will override your default header custom text only if custom text option above is selected.','wt_admin'),
		"id" => "_custom_introduce_text",
		"rows" => "2",
		"default" => "",
		"type" => "wt_textarea"
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "intro_slideshow",
		"group_class" => "intro_type",
	),
	array(
		"name" => __("SlideShow Type",'wt_admin'),
		"desc" => __("Select which type of slideshow you want on this page/post.",'wt_admin'),
		"id" => "_slideshow_type",
		"prompt" => __("Choose Slideshow Type",'wt_admin'),
		"default" => '',
		"options" => array(
			"rev" => __('Revolution Slider','wt_admin'),
			"flex" => __('Flex Slider','wt_admin'),
			"nivo" => __('Nivo Slider','wt_admin'),
			"cycle" => __('Cycle Slider','wt_admin'),
		),
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "slideshow_rev",
		"group_class" => "slideshow_type",
	),
	array(
		"name" => __("Rev SlideShow Type",'wt_admin'),
		"prompt" => __("Choose Slideshow Type",'wt_admin'),
		"desc" => __("Select which type of slideshow you want on this page/post.",'wt_admin'),
		"id" => "_rev_slideshow",
		"type" => "wt_selectRev",
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "slideshow_layerS",
		"group_class" => "slideshow_type",
	),
	array(
		"name" => __("Layer SlideShow Type",'wt_admin'),
		"prompt" => __("Choose Slideshow Type",'wt_admin'),
		"desc" => __("Select which type of slideshow you want on this page/post.",'wt_admin'),
		"id" => "_layer_slideshow",
		"type" => "wt_selectLayerS",
	),
	array(
		"type" => "wt_group_end",
	),
	/*
	array(
		"name" => __("Full Width Section",'wt_admin'),
		"desc" => __('If True this section will have full width.','wt_admin'),
		"id" => "_enable_fullwidth",
		"default" => false,
		"type" => "wt_toggle"
	),
	array(
		"name" => __("Display Full Width Contact Section",'wt_admin'),
		"desc" => __('This option enable Full Width Contact Section. Use only on your contact page.','wt_admin'),
		"id" => "_enable_fullcontact",
		"default" => false,
		"type" => "wt_toggle"
	),	
	array(
		"type" => "wt_group_start",
		"group_id" => "fullcontact_gmap",
		"group_class" => "fullcontact_gmap",
	),
	array(
		"name" => __("Gmap code for contact page",'wt_admin'),
		"desc" => __('Only for your contact page...','wt_admin'),
		"id" => "_fullcontact_gmap",
		"default" => "",
		"class" => 'full',
		"type" => "wt_textarea"		
	),
	array(
		"type" => "wt_group_end",
	),*/
	array(
		"name" => __("Disable Breadcrumbs",'wt_admin'),
		"desc" => __('This option disables breadcrumbs on a page/post.','wt_admin'),
		"id" => "_disable_breadcrumb",
		"label" => "Check to disable breadcrumbs on this post",
		"default" => "",
		"type" => "wt_tritoggle"
	),	
	/*
	array( 
		"name" => __("Section (optional)",'wt_admin'), 
		"prompt" => __("Choose section",'wt_admin'),
		"id" => "_section", 
		"default" => array(), 
		"target" => 'wt_section', 
		"type" => "wt_select" 
	),
	array(
		"name" => __("Page Background Type",'wt_admin'),
		"desc" => __("Choose which type of background area you want to display on this section.",'wt_admin'),
		"id" => "_bg_type",
		"options" => array(
			"pattern" => "Pattern",
			"parallax" => "Parallax",
			"cover" => "Cover Image",
			"video" => "Video",
			"color" => "Color",
		),
		"default" => "pattern",
		"chosen" => "true", 
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "pattern",
		"group_class" => "bg_type",
	),
        array(
            "name" => __("Page Background Pattern Image",'wt_admin'),
            "desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
            "id" => "_bg_style_image",
            "default" => "",
            "type" => "wt_upload"
        ),
        array(
            "name" => __("Page Background Position",'wt_admin'),
            "desc" => "Choose the background image position.",
            "id" => "_bg_style_position_x",
            "default" => 'center',
            "options" => array(
                "left" => __('Left','wt_admin'),
                "center" => __('Center','wt_admin'),
                "right" => __('Right','wt_admin'),
            ),
            "type" => "wt_select",
        ),
        array(
            "name" => __("Page Background Repeat",'wt_admin'),
            "desc" => "Choose the background image repeat style.",
            "id" => "_bg_style_repeat",
            "default" => 'no-repeat',
            "options" => array(
                "no-repeat" => __('No Repeat','wt_admin'),
                "repeat" => __('Repeat','wt_admin'),
                "repeat-x" => __('Repeat Horizontally','wt_admin'),
                "repeat-y" => __('Repeat Vertically','wt_admin'),
            ),
            "type" => "wt_select",
        ),
        array(
            "name" => __("Background Style Color",'wt_admin'),
            "desc" => __("Choose background style for sections", 'wt_admin'),
            "id" => "_background_style_color",
            "default" => '',
            "type" => "wt_color",
        ),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "parallax",
		"group_class" => "bg_type",
	),

        array(
            "name" => __("Page Background Parallax Image",'wt_admin'),
            "desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
            "id" => "_bg_style_parallax",
            "default" => "",
            "type" => "wt_upload"
        ),
		array(
			"name" => __("Parallax",'wt_admin'),
			"desc" => __("Set the button ON if you want to have 'Parallax' for this separator section. You should upload a background image also.", 'wt_admin'),
			"id" => "_parallax",
			"default" => '',
			"type" => "wt_toggle",
		),
   	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "cover",
		"group_class" => "bg_type",
	),

        array(
            "name" => __("Page Background Cover Image",'wt_admin'),
            "desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background cover image or you can simply upload it using the button.",'wt_admin'),
            "id" => "_bg_style_cover",
            "default" => "",
            "type" => "wt_upload"
        ),
   	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "video",
		"group_class" => "bg_type",
	),
        array(
            "name" => __("Youtube Video Background",'wt_admin'),
			 "desc" =>__( "You need to paste only the video ID (for example http://www.youtube.com/watch?v=<code>Ufnf0ecwzVI</code>).",'wt_admin'),
            "id" => "_bg_video",
            "default" => "",
            "type" => "wt_text"
        ),
   	array(
		"type" => "wt_group_end",
	),

	array(
		"type" => "wt_group_start",
		"group_id" => "color",
		"group_class" => "bg_type",
	),
        array(
            "name" => __("Background Style Color",'wt_admin'),
            "desc" => __("Choose background style for sections", 'wt_admin'),
            "id" => "_bg_style_color",
            "default" => '',
            "type" => "wt_color",
        ),
	array(
		"type" => "wt_group_end",
	),
	
	array(
		"name" => __("Background Overlay Color",'wt_admin'),
		"desc" => __("Choose background style for parallax overlay color", 'wt_admin'),
		"id" => "_bg_overlay",
		"default" => '',
		"type" => "wt_color",
	),
	array(
		"name" => __("Disable top section margins",'wt_admin'),
		"desc" => __("Set the button Off if you want to disable the top margins for section.", 'wt_admin'),
		"id" => "_disable_top_margins",
		"default" => '',
		"type" => "wt_toggle",
	),
	
	array(
		"name" => __("Disable bottom section margins",'wt_admin'),
		"desc" => __("Set the button Off if you want to disable the bottom margins for section.", 'wt_admin'),
		"id" => "_disable_bottom_margins",
		"default" => '',
		"type" => "wt_toggle",
	),
	*/
	array(
		"name" => __("Custom Sidebar",'wt_admin'),
		"desc" => __("If there are any custum sidebars created in your theme option panel then you can choose one of them to be displayed on this.",'wt_admin'),
		"id" => "_sidebar",
		"prompt" => __("Choose one...",'wt_admin'),
		"default" => '',
		"options" => get_sidebar_options(),
		"type" => "wt_select",
	),
	
);

new wt_metaboxes($config,$options);