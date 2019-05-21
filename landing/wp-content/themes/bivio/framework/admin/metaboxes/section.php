<?php
$config = array(
	'title' => sprintf(__('Section Options','wt_admin'),THEME_NAME),
	'id' => 'section',
	'pages' => array('wt_section'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'low',
);
$options = array(
	array(
		"name" => __("Page Intro Area Type",'wt_admin'),
		"desc" => __("Choose which type of header area you want to display on this page.",'wt_admin'),
		"id" => "_intro_type",
		"options" => array(
			"default" => "Default",
			"title" => "Title only",
			"custom" => "Custom text only",
			"title_custom" => "Title with custom text",
			/*"slideshow" => "Slideshow",
			"static_image" => "Static Image",
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
		"type" => "group_end",
	),
	array(
		"type" => "group_start",
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
	
	/*array(
		"name" => __("Full Width Section",'wt_admin'),
		"desc" => __('If True this section will have full width.','wt_admin'),
		"id" => "_enable_fullwidth",
		"default" => false,
		"type" => "wt_toggle"
	),*/
	array(
		"name" => __("Disable Breadcrumbs",'wt_admin'),
		"desc" => __('This option disables breadcrumbs on a page/post.','wt_admin'),
		"id" => "_disable_breadcrumb",
		"label" => "Check to disable breadcrumbs on this post",
		"default" => "",
		"type" => "wt_tritoggle"
	),	
	array(
		"name" => __("Background Style",'wt_admin'),
		"desc" => __("Choose background style for sections", 'wt_admin'),
		"id" => "_background_style",
		"default" => '',
		"prompt" => __("Choose Type",'wt_admin'),
		"options" => array(
			//"wt_section_default" => '',
			"wt_section_white" => __('White','wt_admin'),
			"wt_section_dark" => __('Dark','wt_admin'),
		),
		"type" => "wt_select",
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
		"name" => __("Disable top-bottom section margins",'wt_admin'),
		"desc" => __("Set the button Off if you want to disable the top-bottom margins for section.", 'wt_admin'),
		"id" => "_disable_margins",
		"default" => '',
		"type" => "wt_toggle",
	),
	array(
		"name" => __("Display Top Arrow",'wt_admin'),
		"desc" => __("Choose if you want to display top arrow", 'wt_admin'),
		"id" => "_display_arrow",
		"default" => '',
		"type" => "wt_toggle",
	),
	
);

new wt_metaboxes($config,$options);