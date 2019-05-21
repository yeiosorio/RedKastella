<?php
$wt_options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"single_portfolio_settings" => __('Single Portfolio','wt_admin'),
			"featured_entry_settings" => __('Featured Entry','wt_admin'),
		),
		"type" => "wt_navigation",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "single_portfolio_settings",
	),
		array(
			"name" => __("Single Portfolio Item",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Layout",'wt_admin'),
				"desc" => "Choose the layout of the Single Portfolio Item.",
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
				"name" => __("Previous & Next Navigation",'wt_admin'),
				"desc" => "Displays the Previous & Next Navigation.",
				"id" => "single_navigation",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Previous & Next Navigation Order",'wt_admin'),
				"desc" => "The style you want to order Previous & Next Navigation.",
				"id" => "single_navigation_order",
				"default" => 'post_data',
				"options" => array(
					"post_data" => __('Post Data','wt_admin'),
					"menu_order" => __('Menu Order','wt_admin'),
				),
				"type" => "wt_select",
			),
			array(
				"name" => __("Document Type Navigation",'wt_admin'),
				"desc" => "If the button is set to ON then Previous & Next Navigation will be applied just for Portfolio Document Type.",
				"id" => "single_doc_navigation",
				"default" => true,
				"type" => "wt_toggle"
			),			
			array(
				"name" => __("Enable Comments",'wt_admin'),
				"desc" => "If the button is set to ON then you enable comments on the Single Portfolio Item.",
				"id" => "enable_comment",
				"default" => false,
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
			"name" => __("Featured Entry",'wt_admin'),
			"type" => "wt_open"
		),
			array(
				"name" => __("Featured Image",'wt_admin'),
				"desc" => __("If the button is set to ON then the Featured Image will be diplayed in Portfolio Item page.",'wt_admin'),
				"id" => "featured_image",
				"default" => true,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Featured Image for Lightbox",'wt_admin'),
				"desc" => __("If the button is set to ON then when you click on the Featured Image from Portfolio Item page, the full image will be opened in a lightbox.",'wt_admin'),
				"id" => "featured_image_lightbox",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Adaptive Height",'wt_admin'),
				"desc" => __("If the button is set to ON then the Featured Image height depends on the original image.",'wt_admin'),
				"id" => "adaptive_height",
				"default" => false,
				"type" => "wt_toggle"
			),
			array(
				"name" => __("Fixed Height",'wt_admin'),
				"desc" => __("You can set a fixed height for the Featured Image only if the option above is OFF.",'wt_admin'),
				"id" => "fixed_height",
				"min" => "1",
				"max" => "1000",
				"step" => "1",
				"unit" => 'px',
				"default" => "530",
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
	'name' => 'portfolio',
	'options' => $wt_options
);