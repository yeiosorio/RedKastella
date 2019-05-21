<?php
if (! function_exists("wt_home_page_process")) {
	function wt_home_page_process($option,$value) {
		update_option( 'page_on_front', $value );
		if(!empty($value)){
			update_option( 'show_on_front', 'page' );
		}else{
			if(!get_option('page_for_posts')){
				update_option( 'show_on_front', 'posts' );
			}
		}
		return $value;
	}
}
$wt_options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"general_settings" => __('General','wt_admin'),
			"homepage_settings" => __('Homepage','wt_admin'),
			"custom_favicons" => __('Custom Favicons','wt_admin'),
			"google_analytics" => __('Google Analytics','wt_admin'),
			"custom_stylesheet" => __('Custom Css','wt_admin'),
		),
		"type" => "wt_navigation",
	),	
	array(
		"type" => "wt_group_start",
		"group_id" => "general_settings",
	),
		array(
			"name" => __("General Settings",'wt_admin'),
			"type" => "wt_open"
		),		
		array(
			"name" => __("Enable Responsive",'wt_admin'),
			"desc" => sprintf(__('Set ON to enable responsive mode.','wt_admin')),
			"id" => "enable_responsive",
			"default" => true,
			"type" => "wt_toggle"
		),	
		array(
			"name" => __("Custom Logo",'wt_admin'),
			"desc" =>__( "Enter the full URL of your logo image: e.g http://www.site.com/logo.png",'wt_admin'),
			"id" => "logo",
			"default" =>  get_template_directory_uri(). "/img/logo/bivio.png",
			"type" => "wt_upload",
			"crop" => "false"
		),
		array(
			"name" => __("Custom Logo High-DPI (retina) ",'wt_admin'),
			"desc" =>__( "Enter the full URL of your logo image: e.g http://www.site.com/logo@2x.png",'wt_admin'),
			"id" => "logo_retina",
			"default" =>  get_template_directory_uri(). "/img/logo/bivio.png",
			"type" => "wt_upload",
			"crop" => "false"
		),
		array(
			"name" => __("Custom Logo Alt",'wt_admin'),
			"desc" =>__( "Enter the full URL of your logo image: e.g http://www.site.com/logo.png",'wt_admin'),
			"id" => "logo_alt",
			"default" =>  get_template_directory_uri(). "/img/logo/bivio-alt.png",
			"type" => "wt_upload",
			"crop" => "false"
		),
		array(
			"name" => __("Custom Logo High-DPI Alt (retina) ",'wt_admin'),
			"desc" =>__( "Enter the full URL of your logo image: e.g http://www.site.com/logo@2x.png",'wt_admin'),
			"id" => "logo_retina_alt",
			"default" =>  get_template_directory_uri(). "/img/logo/bivio-alt.png",
			"type" => "wt_upload",
			"crop" => "false"
		),
		array(
			"name" => __("Display Text Logo",'wt_admin'),
			"desc" => sprintf(__('Set ON if you want to use plain logo','wt_admin')),
			"id" => "display_logo",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Enter Plain Text Logo",'wt_admin'),
			"desc" => sprintf(__('Please insert a text here to use a plain text logo rather than an image.','wt_admin')),
			"id" => "plain_logo",
			"default" => 'Bivio',
			"type" => "wt_text"
		),
		array(
			"name" => __("Display Site Description",'wt_admin'),
			"desc" => sprintf(__('This enables site description, only if you disable custom logo.','wt_admin'),get_option('siteurl')),
			"id" => "display_site_desc",
			"default" => true,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Color Schemes",'wt_admin'),
			"desc" => __("Select which color schemes type to use.",'wt_admin'),
			"id" => "skin",
			"default" => 'default',
			"options" => array(
				"default" => __('Default','wt_admin'),
				"amethyst" => __('Amethyst','wt_admin'),
				"bluesky" => __('Blue Sky','wt_admin'),
				"carrot" => __('Carrot','wt_admin'),
				"green" => __('Green','wt_admin'),
				"orange" => __('Orange','wt_admin'),
				"pink" => __('Pink','wt_admin'),
				"red" => __('Red','wt_admin'),
				"turquoise" => __('Turquoise','wt_admin'),
				"yellow" => __('Yellow','wt_admin'),
			),
			"chosen" => "true",
			"type" => "wt_select",
		),
		array(
			"name" => __("Custom Skin",'wt_admin'),
			"desc" => __("Create your own skin. This option creates skins which affects only colors, background colors and border colors. Unfortunatelly for images/background images doesn't work. So you need to edit the images with your own color skin and paste them in 'img' folder from theme root with the same names as the older ones. You can keep the older ones under different names. <code>Please use the HEX format here. Ex: \"#000000\"</code>",'wt_admin'),
			"id" => "custom_skin",
			"default" => "",
			"format" => "hex",
			"type" => "wt_color"
		),
		/*array(
			"name" => __("Skin Type",'wt_admin'),
			"desc" => __("Select a skin type to use.",'wt_admin'),
			"id" => "skin_type",
			"light" => 'light',
			"options" => array(
				"light" => __('Light','wt_admin'),
				"dark" => __('Dark','wt_admin'),
			),
			"chosen" => "true",
			"type" => "wt_select",
		),
		/*
		array(
			"name" => __("Menu Position",'wt_admin'),
			"desc"=>__('Which side would you like your navigation? ','wt_admin'),
			"id" => "menu_position",
			"options" => array( "top" => "Top", "side" => "Side"),
			"default" => "top",
			"type" => "wt_radio"
		),
		*/		
		array(
			"name" => __("Disable Breadcrumbs",'wt_admin'),
			"desc" => __("This option disables your website's breadcrumb navigation.",'wt_admin'),
			"id" => "disable_breadcrumb",
			"default" => 0,
			"type" => "wt_toggle"
		),		
		array(
			"name" => __("Sticky Header",'wt_admin'),
			"desc" => __("This option enables the sticky header when scrolling down.",'wt_admin'),
			"id" => "sticky_header",
			"default" => true,
			"type" => "wt_toggle"
		),			
		array(
			"name" => __("Disable Sticky Header On Smaller Screens",'wt_admin'),
			"desc" => __("This option disables sticky header on smaller screens.",'wt_admin'),
			"id" => "no_sticky_on_ss",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Show Responsive Navigation under:",'wt_admin'),
			"desc" => "Here you can set when (which window size) the responsive navigation should be displayed.",
			"id" => "responsive_nav",
			"default" => '767',
			"options" => array(
				"991" => __('< 991 px','wt_admin'),
				"767" => __('< 767 px','wt_admin'),
				//"480" => __('< 480 px','wt_admin'),
			),
			"type" => "wt_select",
		),	
		array(
			"name" => __("Nice Scrolling",'wt_admin'),
			"desc" => sprintf(__('Set ON to enable a better and nice scroll on desktop and mobile device.', 'wt_admin' )),
			"id" => "nice_scroll",
			"default" => false,
			"type" => "wt_toggle"
		),	
		array(
			"name" => __("Smooth Scrolling",'wt_admin'),
			"desc" => sprintf(__('Set ON to enable smooth scroll (a Google Chrome extension for smooth scrolling with the mouse wheel and keyboard buttons). This disables the above Nice Scroll option.', 'wt_admin' )),
			"id" => "smooth_scroll",
			"default" => false,
			"type" => "wt_toggle"
		),		
		array(
			"name" => __("Page Loader Animation",'wt_admin'),
			"desc" => __("This option enables the page loader animation.",'wt_admin'),
			"id" => "page_loader",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Scroll to Top",'wt_admin'),
			"desc" => __("This option enables a scroll to top button at the right bottom corner of site pages.",'wt_admin'),
			"id" => "scroll_to_top",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("WooCommerce",'wt_admin'),
			"desc"=>__('Set ON if you want to use woocommerce.','wt_admin'),
			"id" => "woocommerce",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Shop page layout",'wt_admin'),
			"desc" => __("Select which layout do you want for your Shop page.",'wt_admin'),
			"id" => "woo_layout",
			"default" => 'right',
			"options" => array(
				"full" => __('Full Layout','wt_admin'),
				"right" => __('Right Sidebar','wt_admin'),
				"left" => __('Left Sidebar','wt_admin'),
			),
			"chosen" => "true",
			"type" => "wt_select",
		),
		array(
			"name" => __("Enable Animations",'wt_admin'),
			"desc" => __("This option enables site animations.",'wt_admin'),
			"id" => "enable_animation",
			"default" => false,
			"type" => "wt_toggle"
		),

		array(
			"name" => __("High-DPI (retina) images",'wt_admin'),
			"desc" => __("This option allows you to use High-DPI (retina) images.",'wt_admin'),
			"id" => "enable_retina",
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
		"group_id" => "homepage_settings",
	),	
		array(
			"name" => __("Homepage Settings",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Home Page",'wt_admin'),
			"desc" => __("The selected page here will be displayed in the homepage.",'wt_admin'),
			"id" => "home_page",
			"page" => 0,
			"default" => 0,
			"prompt" => __("None",'wt_admin'),
			"chosen" => "true",
			"type" => "wt_select",
			"process" => "wt_home_page_process"
			),
		
		/*array(
			"name" => __("Home Section Overlay Type",'wt_admin'),
			"desc" => __("Select an overlay type to use into home section.",'wt_admin'),
			"id" => "overlay_type",
			"pattern" => 'Pattern',
			"options" => array(
				"none" => __('None','wt_admin'),
				"pattern" => __('Pattern','wt_admin'),
				"color" => __('Color','wt_admin'),
			),
			"chosen" => "true",
			"type" => "wt_select",
		),*/
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),
		/*array(
			"name" => __("Home Area Text",'wt_admin'),
			"type" => "wt_open",
		),	
		array(
			"name" => __("Home Text",'wt_admin'),
			"one_col" => "true",
			//"desc" => __("The text you enter here will display on the home section",'wt_admin'),
			"id" => "editor",
			"default" => "",
			"type" => "wt_editor",
		),
		array(
			"type" => "wt_close"
		),
		array(
			"type" => "wt_reset"
		),*/
	array(
		"type" => "wt_group_end",
	),	
	array(
		"type" => "wt_group_start",
		"group_id" => "custom_favicons",
	),	
		array(
			"name" => __("Favicons",'wt_admin'),
			"type" => "wt_open"
		),					
			array(	
				"name" => __("Favicon", 'wt_admin'),
				"desc" => __("Enter the full URL of your favicon e.g. http://www.site.com/favicon.ico", 'wt_admin'),
				"id" => "favicon",
				"default" => 'http://whoathemes.com/files/pics/favicons/bivio/favicon.ico',
				"type" => "wt_upload",
				"crop" => "false"
			),			
			array(	
				"name" => __("Apple Touch Icon 57x57", 'wt_admin'),
				"desc" => __("Enter the full URL of your favicon e.g. http://www.site.com/favicon_57.png", 'wt_admin'),
				"id" => "favicon_57",
				"default" => 'http://whoathemes.com/files/pics/favicons/bivio/favicon_57.png',
				"type" => "wt_upload",
				"crop" => "false"
			),		
			array(	
				"name" => __("Apple Touch Icon 72x72", 'wt_admin'),
				"desc" => __("Enter the full URL of your favicon e.g. http://www.site.com/favicon_72.png", 'wt_admin'),
				"id" => "favicon_72",
				"default" => 'http://whoathemes.com/files/pics/favicons/bivio/favicon_72.png',
				"type" => "wt_upload",
				"crop" => "false"
			),		
			array(	
				"name" => __("Apple Touch Icon 114x114", 'wt_admin'),
				"desc" => __("Enter the full URL of your favicon e.g. http://www.site.com/favicon_114.png", 'wt_admin'),
				"id" => "favicon_114",
				"default" => 'http://whoathemes.com/files/pics/favicons/bivio/favicon_114.png',
				"type" => "wt_upload",
				"crop" => "false"
			),	
			array(	
				"name" => __("Apple Touch Icon 144x144", 'wt_admin'),
				"desc" => __("Enter the full URL of your favicon e.g. http://www.site.com/favicon_144.png", 'wt_admin'),
				"id" => "favicon_144",
				"default" => 'http://whoathemes.com/files/pics/favicons/bivio/favicon_144.png',
				"type" => "wt_upload",
				"crop" => "false"
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
		"group_id" => "google_analytics",
	),	
		array(
			"name" => __("Google Analytics",'wt_admin'),
			"type" => "wt_open"
		),			
			array(
				"name" => __("Google Analytics Code",'wt_admin'),
				"desc" => __("Paste your <a href='http://www.google.com/analytics/' target='_blank'>analytics code</a> here and it will be applied to each page.",'wt_admin'),
				"id" => "analytics",
				"default" => "",
				"elastic" => "true",
				"type" => "wt_textarea"
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
		"group_id" => "custom_stylesheet",
	),	
		array(
			"name" => __("Custom Css",'wt_admin'),
			"type" => "wt_open"
		),			
			array(	
				"name" => __("Custom Css", 'wt_admin'),
				//"desc" => __("Custom Css", 'wt_admin'),
				"id" => "custom_css",
				"default" => "",
				"elastic" => "true",
				"type" => "wt_textarea"
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
	'name' => 'general',
	'options' => $wt_options
);