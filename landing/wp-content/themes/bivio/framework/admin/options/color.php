<?php
$wt_options = array(
	array(
		"class" => "nav-tab-wrapper",
		"default" => '',
		"options" => array(
			"page_colors" => __('Page Element Colors','wt_admin'),
			"social_colors" => __('Social Icons Colors','wt_admin'),
		),
		"type" => "wt_navigation",
	),	
	array(
		"type" => "wt_group_start",
		"group_id" => "page_colors",
	),
		array(
			"name" => __("Color Setting",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Page Text Color",'wt_admin'),
			"id" => "page_content",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page Header Color",'wt_admin'),
			"id" => "content_header",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H1 Color",'wt_admin'),
			"id" => "content_h1",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H2 Color",'wt_admin'),
			"id" => "content_h2",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H3 Color",'wt_admin'),
			"id" => "content_h3",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H4 Color",'wt_admin'),
			"id" => "content_h4",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H5 Color",'wt_admin'),
			"id" => "content_h5",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Page H6 Color",'wt_admin'),
			"id" => "content_h6",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Logo Text Color",'wt_admin'),
			"id" => "logo_color",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Logo Description Text Color",'wt_admin'),
			"id" => "logo_color_desc",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Top Level Menu Color",'wt_admin'),
			"id" => "menu_top",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Top Level Menu Hover Color",'wt_admin'),
			"id" => "menu_top_hover",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Top Level Current Menu Color",'wt_admin'),
			"id" => "menu_top_current",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Sub Level Menu Color",'wt_admin'),
			"id" => "menu_sub",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Sub Level Menu Hover Color",'wt_admin'),
			"id" => "menu_sub_hover",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Footer Text Color",'wt_admin'),
			"id" => "footer_text",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Footer Widget Title Color",'wt_admin'),
			"id" => "footer_title",
			"default" => "",
			"type" => "wt_color"
		),
		array(
			"name" => __("Copyright Text Color",'wt_admin'),
			"id" => "copyright",
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
		"group_id" => "social_colors",
	),
		array(
			"name" => __("Social Icons Color Settings",'wt_admin'),
			"type" => "wt_open"
		),
		array(
			"name" => __("Aim",'wt_admin'),
			"id" => "aim_color",
			"default" => "#452806",
			"type" => "wt_color"
		),
		array(
			"name" => __("Apple",'wt_admin'),
			"id" => "apple_color",
			"default" => "#231f20",
			"type" => "wt_color"
		),
		array(
			"name" => __("Behance",'wt_admin'),
			"id" => "behance_color",
			"default" => "#1378fe",
			"type" => "wt_color"
		),
		array(
			"name" => __("Blogger",'wt_admin'),
			"id" => "blogger_color",
			"default" => "#fe6601",
			"type" => "wt_color"
		),
		array(
			"name" => __("Delicious",'wt_admin'),
			"id" => "delicious_color",
			"default" => "#3274d2",
			"type" => "wt_color"
		),
		array(
			"name" => __("Deviantart",'wt_admin'),
			"id" => "deviantart_color",
			"default" => "#c8da2e",
			"type" => "wt_color"
		),
		array(
			"name" => __("Digg",'wt_admin'),
			"id" => "digg_color",
			"default" => "#005f95",
			"type" => "wt_color"
		),
		array(
			"name" => __("Dribble",'wt_admin'),
			"id" => "dribbble_color",
			"default" => "#ea4b8b",
			"type" => "wt_color"
		),
		array(
			"name" => __("Dropbox",'wt_admin'),
			"id" => "dropbox_color",
			"default" => "#007ee5",
			"type" => "wt_color"
		),
		array(
			"name" => __("Email",'wt_admin'),
			"id" => "email_color",
			"default" => "#262626",
			"type" => "wt_color"
		),
		array(
			"name" => __("Ember",'wt_admin'),
			"id" => "ember_color",
			"default" => "#e11a3b",
			"type" => "wt_color"
		),
		array(
			"name" => __("Facebook",'wt_admin'),
			"id" => "facebook_color",
			"default" => "#3C5A9A",
			"type" => "wt_color"
		),
		array(
			"name" => __("Flickr",'wt_admin'),
			"id" => "flickr_color",
			"default" => "#0062dd",
			"type" => "wt_color"
		),
		array(
			"name" => __("Forrst",'wt_admin'),
			"id" => "forrst_color",
			"default" => "#166021",
			"type" => "wt_color"
		),
		array(
			"name" => __("Google",'wt_admin'),
			"id" => "google_color",
			"default" => "#4a7af6",
			"type" => "wt_color"
		),
		array(
			"name" => __("Google Plus",'wt_admin'),
			"id" => "googleplus_color",
			"default" => "#da2713",
			"type" => "wt_color"
		),
		array(
			"name" => __("Github",'wt_admin'),
			"id" => "github_color",
			"default" => "#569e3d",
			"type" => "wt_color"
		),
		array(
			"name" => __("Html5",'wt_admin'),
			"id" => "html5_color",
			"default" => "#e54d26",
			"type" => "wt_color"
		),
		array(
			"name" => __("Instagram",'wt_admin'),
			"id" => "instagram_color",
			"default" => "#517fa4",
			"type" => "wt_color"
		),
		array(
			"name" => __("Last Fm",'wt_admin'),
			"id" => "lastfm_color",
			"default" => "#c30d19",
			"type" => "wt_color"
		),
		array(
			"name" => __("LinkedIn",'wt_admin'),
			"id" => "linkedin_color",
			"default" => "#006599",
			"type" => "wt_color"
		),
		array(
			"name" => __("Metacafe",'wt_admin'),
			"id" => "metacafe_color",
			"default" => "#f88326",
			"type" => "wt_color"
		),
		array(
			"name" => __("Netvibes",'wt_admin'),
			"id" => "netvibes_color",
			"default" => "#15ae15",
			"type" => "wt_color"
		),
		array(
			"name" => __("Paypal",'wt_admin'),
			"id" => "paypal_color",
			"default" => "#2c5f8c",
			"type" => "wt_color"
		),
		array(
			"name" => __("Picasa",'wt_admin'),
			"id" => "picasa_color",
			"default" => "#b163c9",
			"type" => "wt_color"
		),
		array(
			"name" => __("Pinterest",'wt_admin'),
			"id" => "pinterest_color",
			"default" => "#cb2028",
			"type" => "wt_color"
		),
		array(
			"name" => __("Reddit",'wt_admin'),
			"id" => "reddit_color",
			"default" => "#6bbffb",
			"type" => "wt_color"
		),
		array(
			"name" => __("Rss",'wt_admin'),
			"id" => "rss_color",
			"default" => "#ff6600",
			"type" => "wt_color"
		),
		array(
			"name" => __("Skype",'wt_admin'),
			"id" => "skype_color",
			"default" => "#00aff0",
			"type" => "wt_color"
		),
		array(
			"name" => __("StumbleUpon",'wt_admin'),
			"id" => "stumbleupon_color",
			"default" => "#ea4b24",
			"type" => "wt_color"
		),
		array(
			"name" => __("Technorati",'wt_admin'),
			"id" => "technorati_color",
			"default" => "#00c400",
			"type" => "wt_color"
		),
		array(
			"name" => __("Tumblr",'wt_admin'),
			"id" => "tumblr_color",
			"default" => "#2c4661",
			"type" => "wt_color"
		),
		array(
			"name" => __("Twitter",'wt_admin'),
			"id" => "twitter_color",
			"default" => "#00acee",
			"type" => "wt_color"
		),
		array(
			"name" => __("Vimeo",'wt_admin'),
			"id" => "vimeo_color",
			"default" => "#17aacc",
			"type" => "wt_color"
		),
		array(
			"name" => __("Wordpress",'wt_admin'),
			"id" => "wordpress_color",
			"default" => "#207499",
			"type" => "wt_color"
		),
		array(
			"name" => __("Yahoo",'wt_admin'),
			"id" => "yahoo_color",
			"default" => "#65106b",
			"type" => "wt_color"
		),
		array(
			"name" => __("Yelp",'wt_admin'),
			"id" => "yelp_color",
			"default" => "#c51102",
			"type" => "wt_color"
		),
		array(
			"name" => __("Youtube",'wt_admin'),
			"id" => "youtube_color",
			"default" => "#d20200",
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
	'name' => 'color',
	'options' => $wt_options
);