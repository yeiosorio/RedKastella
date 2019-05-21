<?php
$wt_options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"page" => __('Homepage','wt_admin'),
			"header" => __('Header','wt_admin'),
			"footer" => __('Footer','wt_admin'),
		),
		"type" => "wt_navigation",
	),
	
	array(
		"type" => "wt_group_start",
		"group_id" => "page",
	),
		array(
			"name" => __("Background Type",'wt_admin'),
			"type" => "wt_open",
		),
			array(
				"name" => __("",'wt_admin'),
				"one_col" => "true",
				"id" => "background_type",
				"default" => 'image_bg',
				"options" => array(
					"pattern" => __('Pattern Background','wt_admin'),
					"image_bg" => __('Image Background','wt_admin'),
					"slideshow" => __('Slideshow','wt_admin'),
					"video" => __('Video','wt_admin'),
				),
				"chosen" => "true",
				"type" => "wt_select",
			),	
			
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
			array(
				"open_class" => "patternSwitch",
				"type" => "wt_open_group",
			),		
				array(
					"type" => "wt_group_start",
					"group_id" => "pattern_bg",
				),
					array(
						"name" => __("Pattern Background",'wt_admin'),
						"type" => "wt_open"
					),		
						array(
							"name" => __("Pattern Background Image",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "pattern_bg",
							"default" => "",
							"type" => "wt_upload"
						),
						array(
							"name" => __("Pattern Background Position",'wt_admin'),
							"desc" => "Choose the background image position.",
							"id" => "pattern_position_x",
							"default" => 'center',
							"options" => array(
								"left" => __('Left','wt_admin'),
								"center" => __('Center','wt_admin'),
								"right" => __('Right','wt_admin'),
							),
							"type" => "wt_select",
						),
						array(
							"name" => __("Pattern Background Repeat",'wt_admin'),
							"desc" => "Choose the background image repeat style.",
							"id" => "pattern_repeat",
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
							"name" => __("Pattern Background Color",'wt_admin'),
							"desc" => __("Here you can choose a specific page background color. Set it to transparent in order to disable this.",'wt_admin'),
							"id" => "pattern_bg_color",
							"default" => "",
							"type" => "wt_color"		
						),
					array(
						"type" => "wt_close"
					),
					array(
						"type" => "wt_reset"
					),
				array(
					"type" => "wt_group_end",
				),
			array(
				"type" => "wt_close_group"
			),	
			array(
				"open_class" => "imageSwitch",
				"type" => "wt_open_group",
			),		
				array(
					"type" => "wt_group_start",
					"group_id" => "parallax_bg",
				),
					array(
						"name" => __("Image Background",'wt_admin'),
						"type" => "wt_open"
					),		
						array(
							"name" => __("Image Background",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "image_bg",
							"default" => "",
							"type" => "wt_upload"
						),
					array(
						"type" => "wt_close"
					),
					array(
						"type" => "wt_reset"
					),
				array(
					"type" => "wt_group_end",
				),
			array(
				"type" => "wt_close_group"
			),
			array(
				"open_class" => "slideshowSwitch",
				"type" => "wt_open_group",
			),		
				array(
					"type" => "wt_group_start",
					"group_id" => "slideshow_bg",
				),
					array(
						"name" => __("Slideshow Background",'wt_admin'),
						"type" => "wt_open"
					),		
						array(
							"name" => __("Slideshow Background Image 1",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "slide_bg_1",
							"default" => "",
							"type" => "wt_upload"
						),
						array(
							"name" => __("Slideshow Background Image 2",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "slide_bg_2",
							"default" => "",
							"type" => "wt_upload"
						),
						array(
							"name" => __("Slideshow Background Image 3",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "slide_bg_3",
							"default" => "",
							"type" => "wt_upload"
						),
						array(
							"name" => __("Slideshow Background Image 4",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "slide_bg_4",
							"default" => "",
							"type" => "wt_upload"
						),
						array(
							"name" => __("Slideshow Background Image 5",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
							"id" => "slide_bg_5",
							"default" => "",
							"type" => "wt_upload"
						),
					array(
						"type" => "wt_close"
					),
					array(
						"type" => "wt_reset"
					),
				array(
					"type" => "wt_group_end",
				),
			array(
				"type" => "wt_close_group"
			),	
			array(
				"open_class" => "videoSwitch",
				"type" => "wt_open_group",
			),		
				array(
					"type" => "wt_group_start",
					"group_id" => "video_bg",
				),
					array(
						"name" => __("Video Background",'wt_admin'),
						"type" => "wt_open"
					),		
						array(
							"name" => __("Video Link",'wt_admin'),
							"desc" =>__( "You need to paste the full URL (including <code>http://</code>) of the video to be used as a background video. Only youtube accepted.",'wt_admin'),
							"id" => "video_link",
							"default" => "",
							"type" => "wt_text"
						),
						array(
							"name" => __("Background Mobile",'wt_admin'),
							"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image on mobile or you can simply upload it using the button.",'wt_admin'),
							"id" => "video_mobile_bg",
							"default" => "",
							"type" => "wt_upload"
						),
					array(
						"type" => "wt_close"
					),
					array(
						"type" => "wt_reset"
					),
				array(
					"type" => "wt_group_end",
				),
			array(
				"type" => "wt_close_group"
			),						
		array(
			"type" => "wt_group_end",
		),
		
	array(
		"type" => "wt_group_start",
		"group_id" => "header",
	),
		array(
			"name" => __("Header Background",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Header Background Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "header_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Header Background Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "header_position_x",
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
			"id" => "header_repeat",
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
			"id" => "header_bg_color",
			"default" => "",
			"type" => "wt_color"		
		),
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
	array(
		"type" => "wt_group_end",
	),	
	
	array(
		"type" => "wt_group_start",
		"group_id" => "footer",
	),
	
		array(
			"name" => __("Footer Top Background",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Custom Footer Top Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "footer_top_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Top Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "footer_top_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Footer Top Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "footer_top_repeat",
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
			"desc" => __("If you specify a color below, this option will override the global configuration. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "footer_top_color",
			"default" => "",
			"type" => "wt_color"		
		),

		array(
			"type" => "wt_close"
		),
		array(
			"name" => __("Footer Background",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Custom Footer Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "footer_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "footer_position_x",
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
			"id" => "footer_repeat",
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
			"id" => "footer_color",
			"default" => "",
			"type" => "wt_color"		
		),
		
		array(
			"type" => "wt_close"
		),
		array(
			"name" => __("Footer Bottom Background",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Custom Footer Bottom Image",'wt_admin'),
			"desc" =>__( "You can paste the full URL (including <code>http://</code>) of the image to be used as a background image or you can simply upload it using the button.",'wt_admin'),
			"id" => "footer_bottom_bg",
			"default" => "",
			"type" => "wt_upload"
		),
		array(
			"name" => __("Footer Bottom Position",'wt_admin'),
			"desc" => "Choose the background image position.",
			"id" => "footer_bottom_position_x",
			"default" => 'center',
			"options" => array(
				"left" => __('Left','wt_admin'),
				"center" => __('Center','wt_admin'),
				"right" => __('Right','wt_admin'),
			),
			"type" => "wt_select",
		),
		array(
			"name" => __("Footer Bottom Repeat",'wt_admin'),
			"desc" => "Choose the background image repeat style.",
			"id" => "footer_bottom_repeat",
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
			"desc" => __("If you specify a color below, this option will override the global configuration. Set it to transparent in order to disable this.",'wt_admin'),
			"id" => "footer_bottom_color",
			"default" => "",
			"type" => "wt_color"		
		),
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
	array(
		"type" => "wt_group_end",
	),	
);
return array(
	'auto' => true,
	'name' => 'background',
	'options' => $wt_options
);