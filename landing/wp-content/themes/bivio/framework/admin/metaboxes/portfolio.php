<?php

$config = array(
	'title' => __('Portfolio Item Options','wt_admin'),
	'id' => 'portfolio',
	'pages' => array('wt_portfolio'),
	'callback' => '',
	'context' => 'normal',
	'priority' => 'high',
);
function get_sidebar_portfolio(){
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
	/*array(
		"name" => __("Page Intro Area Type",'wt_admin'),
		"desc" => __("Choose which type of header area you want to display on this post. Static images / videos are setted in the \"Featured Image\" / \"Whoathemes Featured Video\" areas.",'wt_admin'),
		"id" => "_intro_type",
		"options" => array(
			"default" => "Default",
			"title" => "Title only",
			"custom" => "Custom text only",
			"title_custom" => "Title with custom text",
			"slideshow" => "Slideshow",
			"static_image" => "Static Image",
			"static_video" => "Static Video",
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
			"flex" => __('Flex Slider','wt_admin'),
			"nivo" => __('Nivo Slider','wt_admin'),
			"anything" => __('Anything Slider','wt_admin'),
			"cycle" => __('Cycle Slider','wt_admin'),
		),
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_end",
	),*/
	array(
		"name" => __("Featured Portfolio Entry",'wt_admin'),
		"desc" => __("Here you can choose to dispaly or not the Featured Portfolio Entry only for this portfolio item.",'wt_admin'),
		"id" => "_featured_image",
		"default" => '',
		"type" => "wt_tritoggle",
	),
	/*array(
		"name" => __("Layout",'wt_admin'),
		"desc" => __("Choose the layout for this portfolio item.",'wt_admin'),
		"id" => "_sidebar_alignment",
		"default" => 'default',
		"options" => array(
			"default" => __('Default','wt_admin'),
			"full" => __('Full Width','wt_admin'),
			"right" => __('Right Sidebar','wt_admin'),
			"left" => __('Left Sidebar','wt_admin'),
		),
		"type" => "wt_select",
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
		"name" => __("Portfolio Type",'wt_admin'),
		"desc" => sprintf(__("The lightbox supports just images and videos. If the portfolio is a document type then the thumbnail image is linked to the portfolio item.",'wt_admin'),THEME_NAME),
		"id" => "_portfolio_type",
		"default" => 'image',
		"options" => array(
			"image" => __('Image','wt_admin'),
			"video" => __('Video','wt_admin'),
			"doc" => __('Document','wt_admin'),
			"link" => __('Link','wt_admin'),
		),
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "portfolio_image",
		"group_class" => "portfolio_type",
	),
	array(
		"name" => __("Fullsize Image for Lightbox (optional)",'wt_admin'),
		"desc" => __("If this field is empty then the lightbox will be opened with the feature image. Otherwise you should upload a full size image to open the lightbox on click.",'wt_admin'),
		"id" => "_image",
		"button" => "Insert Image",
		"default" => '',
		"type" => "wt_upload",
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "portfolio_video",
		"group_class" => "portfolio_type",
	),
	array(
		"name" => __("Video Link for Lightbox",'wt_admin'),
		"desc" => __("If the portfolio is a video type one, you can paste here the full url of your video.",'wt_admin'),
		"size" => 30,
		"id" => "_video",
		"default" => '',
		"class" => 'full',
		"type" => "wt_text",
	),
	array(
		"name" => __("Video Width",'wt_admin'),
		"desc" => __("The width you specify here is going to override the global configuration.",'wt_admin'),
		"id" => "_video_width",
		"default" => '',
		"type" => "wt_text"
	),
	array(
		"name" => __("Video Height",'wt_admin'),
		"desc" => __("The height you specify here is going to override the global configuration.",'wt_admin'),
		"id" => "_video_height",
		"default" => '',
		"type" => "wt_text"
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "portfolio_document",
		"group_class" => "portfolio_type",
	),
	array(
		"name" => __("Document Target",'wt_admin'),
		"id" => "_doc_target",
		"default" => '_self',
		"options" => array(
			"_self" => __('Opens in the same window and same frame.','wt_admin'),
			"_top" => __('Opens in the same window, taking the full window if there is more than one frame.','wt_admin'),
			"_parent" => __('Opens in the parent frame.','wt_admin'),
			"_blank" => __('Opens in a new window.','wt_admin'),
		),
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"type" => "wt_group_start",
		"group_id" => "portfolio_link",
		"group_class" => "portfolio_type",
	),
	array(
		"name" => __("Link for Portfolio item",'wt_admin'),
		"desc" => __("If the portfolio is a link type one, you can paste here the full link.",'wt_admin'),
		"id" => "_portfolio_link",
		"default" => "",
		"shows" => array('page','cat','post','manually'),
		"type" => "wt_superlink"	
	),
	array(
		"name" => __("Link Target",'wt_admin'),
		"id" => "_portfolio_link_target",
		"default" => '_self',
		"options" => array(
			"_self" => __('Opens in the same window and same frame.','wt_admin'),
			"_top" => __('Opens in the same window, taking the full window if there is more than one frame.','wt_admin'),
			"_parent" => __('Opens in the parent frame.','wt_admin'),
			"_blank" => __('Opens in a new window.','wt_admin'),
		),
		"type" => "wt_select",
	),
	array(
		"type" => "wt_group_end",
	),
	array(
		"name" => __("Custom Sidebar",'wt_admin'),
		"desc" => __("If there are any custum sidebars created in your theme option panel then you can choose one of them to be displayed on this.",'wt_admin'),
		"id" => "_sidebar",
		"prompt" => __("Choose one...",'wt_admin'),
		"default" => '',
		"options" => get_sidebar_portfolio(),
		"type" => "wt_select",
	),
);
new wt_metaboxes($config,$options);