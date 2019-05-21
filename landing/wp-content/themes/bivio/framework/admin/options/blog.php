<?php

$wt_options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"blog_settings" => __('Blog','wt_admin'),
			"single_post_settings" => __('Single Post','wt_admin'),
			"meta_information_settings" => __('Meta Informations','wt_admin'),
			"featured_entry_settings" => __('Featured Entry','wt_admin'),
		),
		"type" => "wt_navigation",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "blog_settings",
	),	
		array(
			"name" => __("Blog Settings",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Layout",'wt_admin'),
				"desc" => "Here you can set the layout of the blog page.",
				"id" => "layout",
				"default" => 'right',
				"options" => array(
					"full" => __('Full Width','wt_admin'),
					"right" => __('Right Sidebar','wt_admin'),
					"left" => __('Left Sidebar','wt_admin'),
				),
				"type" => "wt_select",
			),
			array(
				"name" => __("Featured Post Entry Type",'wt_admin'),
				"desc" => "The style in which the post entry will be displayed. This could be an image/slideshow/mp3/video (youtube, vimeo, daylimotion, metacafe, google, .flv, .f4v, .mp4)",
				"id" => "featured_image_type",
				"default" => 'full',
				"options" => array(
					"full" => __('Full Width','wt_admin'),
					"left" => __('Left Float','wt_admin'),
				),
				"type" => "wt_select",
			),
			array(
				"name" => __("Display Full Blog Posts",'wt_admin'),
				"desc" => __("If the option is set to ON, the blog postswill be full displayed on the index page.",'wt_admin'),
				"id" => "display_full",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Exclude Categories",'wt_admin'),
				"desc" => __("ff you don't want to display custom categories in the blog pages, you can set them here. Also you can exclude multiple categories.",'wt_admin'),
				"id" => "exclude_categorys",
				"default" => array(),
				"target" => "cat",
				"prompt" => __("Choose category...",'wt_admin'),
				"chosen" => "true",
				"type" => "wt_multiselect",
				//"type" => "multidropdown"
			),
			array(
				"name" => __("Gap Between Posts",'wt_admin'),
				"desc" => "Here you can set the distance between the blog posts.",
				"id" => "posts_gap",
				"min" => "0",
				"max" => "200",
				"step" => "1",
				"unit" => 'px',
				"default" => "60",
				"type" => "wt_range"
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
		"group_id" => "single_post_settings",
	),		
		array(
			"name" => __("Single Post Settings",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Layout",'wt_admin'),
				"desc" => "Here you can set the layout of the blog posts.",
				"id" => "single_layout",
				"default" => 'right',
				"options" => array(
					"full" => __('Full Width','wt_admin'),
					"right" => __('Right Sidebar','wt_admin'),
					"left" => __('Left Sidebar','wt_admin'),
				),
				"type" => "wt_select",
			),
			array(
				"name" => __("Featured Post Entry",'wt_admin'),
				"desc" => __("If the button is set to ON then the Featured Image/Slideshow/Mp3/Video will be displayed on the Single Blog post.",'wt_admin'),
				"id" => "featured_image",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Featured Post Entry Type",'wt_admin'),
				"desc" => "The style in which the post entry will be displayed on Single Blog post. This could be an image/slideshow/mp3/video (youtube, vimeo, daylimotion, metacafe, google, .flv, .f4v, .mp4). ",
				"id" => "single_featured_image_type",
				"default" => 'full',
				"options" => array(
					"full" => __('Full Width','wt_admin'),
					"left" => __('Left Float','wt_admin'),
				),
				"type" => "wt_select",
			),
			array(
				"name" => __("Featured Image for Lightbox",'wt_admin'),
				"desc" => __("If the button is set to ON then the full image will be opened in the lightbox when you click on Featured Image of the Blog Single Post page.",'wt_admin'),
				"id" => "featured_image_lightbox",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("About Author Box",'wt_admin'),
				"desc" => "If the button is set to ON then the About Author Box will be displayed in the Blog Single Post page.",
				"id" => "author",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Previous & Next Navigation",'wt_admin'),
				"desc" => "Display pagination for the posts.",
				"id" => "entry_navigation",
				"default" => true,
				"type" => "wt_toggle"
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
		"group_id" => "meta_information_settings",
	),
		array(
			"name" => __("Meta Informations",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Date",'wt_admin'),
				"desc" => "Dispaly date in Meta Informations box.",
				"id" => "meta_date",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Author",'wt_admin'),
				"desc" => "Dispaly author in Meta Informations box.",
				"id" => "meta_author",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Categories",'wt_admin'),
				"desc" => "Dispaly categories in Meta Informations box.",
				"id" => "meta_category",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Tags",'wt_admin'),
				"desc" => "Dispaly tags in Meta Informations box.",
				"id" => "meta_tags",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Comments",'wt_admin'),
				"desc" => "Dispaly comments number in Meta Informations box.",
				"id" => "meta_comment",
				"default" => true,
				"type" => "wt_toggle"
			),
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
		array(
			"name" => __("Meta Informations Single Post",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Date",'wt_admin'),
				"desc" => "Dispaly date in Meta Informations box.",
				"id" => "single_meta_date",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Categories",'wt_admin'),
				"desc" => "Dispaly categories in Meta Informations box.",
				"id" => "single_meta_category",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Tags",'wt_admin'),
				"desc" => "Dispaly tags in Meta Informations box.",
				"id" => "single_meta_tags",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Comments",'wt_admin'),
				"desc" => "Dispaly comments number in Meta Informations box.",
				"id" => "single_meta_comment",
				"default" => true,
				"type" => "wt_toggle"
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
		"group_id" => "featured_entry_settings",
	),
		array(
			"name" => __("Full Width Featured Post Entry",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Blog Adaptive Height",'wt_admin'),
				"desc" => __("If the button is set to ON then the Featured Image height depends on the original image.",'wt_admin'),
				"id" => "adaptive_height",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Single Adaptive Height",'wt_admin'),
				"desc" => __("If the button is set to ON then the Featured Image height depends on the original image.",'wt_admin'),
				"id" => "single_adaptive_height",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("<em>\"Full Layout\"</em> - Featured Image Height",'wt_admin'),
				"desc" => __("You can set the Featured Image Entry height for full width layouts. ( Adaptive height option above should be OFF ).  Default height is 550px.",'wt_admin'),
				"id" => "image_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "550",
				"type" => "wt_range"
			),
			array(
				"name" => __("<em>\"Full Layout\"</em> - Featured Slide Height",'wt_admin'),
				"desc" => __("You can set the Featured Slide Entry height for full width layouts. Default height is 550px.",'wt_admin'),
				"id" => "slide_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "550",
				"type" => "wt_range"
			),
			array(
				"name" => __("<em>\"Sidebar Layout\"</em> - Featured Image Height",'wt_admin'),
				"desc" => __("You can set the Featured Image Entry height for layouts with sidebar. ( Adaptive height option above should be OFF ). Default height is 250px.",'wt_admin'),
				"id" => "sidebar_image_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "400",
				"type" => "wt_range"
			),
			array(
				"name" => __("<em>\"Sidebar Layout\"</em> - Featured Slide Height",'wt_admin'),
				"desc" => __("You can set the Featured Slide Entry height for layouts with sidebar. Default height is 400px.",'wt_admin'),
				"id" => "sidebar_slide_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "400",
				"type" => "wt_range"
			),
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
		array(
			"name" => esc_html__("Left Float Featured Post Entry",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => esc_html__("\"Full Layout\" - Width",'wt_admin'),
				"desc" => esc_html__("You can set the width of the left floated entry wrapper for Full Layouts. Original image width is 720px because on smaller screens it will be displayed at full size. Default width is 460px.",'wt_admin'),
				"id" => "left_width",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "460",
				"type" => "wt_range"
			),
			array(
				"name" => esc_html__("\"Full Layout\" - Featured Image Height",'wt_admin'),
				"desc" => esc_html__("You can set the height of the left floated Image Entry for Full Layouts. This is the height for smaller screens where the image will be displayed in full size. On larger screens the image height will be resized to fit in left image wrapper. Default height is 405px.",'wt_admin'),
				"id" => "left_image_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "405",
				"type" => "wt_range"
			),
			array(
				"name" => esc_html__("\"Full Layout\" - Featured Slide Height",'wt_admin'),
				"desc" => esc_html__("You can set the height of the left floated Slide Entry for Full Layouts. This is the height for smaller screens where the image will be displayed in full size. On larger screens the image height will be resized to fit in left image wrapper. Default height is 405px.",'wt_admin'),
				"id" => "left_slide_height",
				"min" => "1",
				"max" => "640",
				"step" => "1",
				"unit" => 'px',
				"default" => "405",
				"type" => "wt_range"
			),
			array(
				"name" => esc_html__("\"Sidebar Layout\" - Width",'wt_admin'),
				"desc" => esc_html__("You can set the width of the left floated entry wrapper for Layouts With Sidebar. Original image width is 720px because on smaller screens it will be displayed at full size. Default width is 380px.",'wt_admin'),
				"id" => "sidebar_left_width",
				"min" => "1",
				"max" => "460",
				"step" => "1",
				"unit" => 'px',
				"default" => "380",
				"type" => "wt_range"
			),
			array(
				"name" => esc_html__("\"Sidebar Layout\" - Featured Image Height",'wt_admin'),
				"desc" => esc_html__("You can set the height of the left floated Image Entry for Layouts With Sidebar. This is the height for smaller screens where the image will be displayed in full size. On larger screens the image height will be resized to fit in left image wrapper. Default height is 405px.",'wt_admin'),
				"id" => "sidebar_left_image_height",
				"min" => "1",
				"max" => "460",
				"step" => "1",
				"unit" => 'px',
				"default" => "405",
				"type" => "wt_range"
			),
			array(
				"name" => esc_html__("\"Sidebar Layout\" - Featured Slide Height",'wt_admin'),
				"desc" => esc_html__("You can set the height of the left floated Slide Entry for Layouts With Sidebar. This is the height for smaller screens where the image will be displayed in full size. On larger screens the image height will be resized to fit in left image wrapper.  Default height is 405px.",'wt_admin'),
				"id" => "sidebar_left_slide_height",
				"min" => "1",
				"max" => "460",
				"step" => "1",
				"unit" => 'px',
				"default" => "405",
				"type" => "wt_range"
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
	'name' => 'blog',
	'options' => $wt_options
);