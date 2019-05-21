<?php
$config = array(
	'title' => sprintf(__('Page Bg Options','wt_admin'),THEME_NAME),
	'id' => 'page_bg',
	'pages' => array(),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'low',
);
$options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"page" => __('Page','wt_admin'),
			"header" => __('Header','wt_admin'),
			"intro" => __('Intro','wt_admin'),
			"container" => __('Container','wt_admin'),
			"content" => __('Content','wt_admin'),
			"footer" => __('Footer','wt_admin'),
		),
		"type" => "wt_navigation",
	),	
	array(
		"type" => "wt_option_group_start",
		"group_id" => "page",
	),
		array(
			"name" => __("Page Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_page_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Page Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_page_position_x",
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
			"id" => "_page_repeat",
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
			"name" => __("Page Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific page background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_page_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_option_group_start",
		"group_id" => "header",
	),
		array(
			"name" => __("Header Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_header_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Header Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_header_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Header Background Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_header_repeat",
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
			"name" => __("Header Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific page background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_header_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
		
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_option_group_start",
		"group_id" => "intro",
	),	
		array(
			"name" => __("Intro Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_intro_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Intro Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_intro_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Intro Background Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_intro_repeat",
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
			"name" => __("Intro Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific intro background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_intro_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
		array(
			"name" => __("Intro Text Color",'wt_admin'),
			"desc" => __("Here you can choose a specific intro text color.",'wt_admin'),
			"id" => "_intro_text_color",
			"default" => "",
			"type" => "wt_color"		
		),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_option_group_start",
		"group_id" => "container",
	),	
		array(
			"name" => __("Container Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_container_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Container Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_container_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Container Background Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_container_repeat",
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
			"name" => __("Container Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific intro background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_container_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_option_group_start",
		"group_id" => "content",
	),	
		array(
			"name" => __("Content Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_content_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Content Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_content_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Content Background Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_content_repeat",
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
			"name" => __("Content Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific intro background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_content_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_option_group_start",
		"group_id" => "footer",
	),
		array(
			"name" => __("Footer Top Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_footer_top_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_footer_top_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Footer Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_footer_top_repeat",
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
			"name" => __("Footer Top Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific page background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_footer_top_color",
			"default" => "",
			"type" => "wt_color"		
		),
		array(
			"name" => __("Footer Bottom Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_footer_bottom_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_footer_bottom_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Footer Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_footer_bottom_repeat",
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
			"name" => __("Footer Bottom Background Color",'wt_admin'),
			"desc" => __("Here you can choose a specific page background color. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_footer_bottom_color",
			"default" => "",
			"type" => "wt_color"		
		),
		array(
			"name" => __("Footer Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "_footer_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "_footer_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Footer Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "_footer_repeat",
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
			"name" => __("Footer Background Color",'wt_admin'),
			"desc" => __("If you specify a color below, this option will override the global configuration. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "_footer_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
	array(
		"type" => "wt_group_end",
	),	
);

new wt_metaboxes($config,$options);