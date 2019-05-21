<?php
$wt_options = array(
	array(
		"type" => "wt_group_start",
		"group_id" => "twitter_api",
	),	
		array(
			"name" => __("Twitter",'wt_admin'),
			"type" => "wt_open"
		),	
			array(
				"desc" => __("With the advent of Twitter's 1.1 API and the deprecation of 1.0, client side timeline fetching and parsing is no longer feasable due to the authentication restrictions imposed by OAuth. That's why you need to have a twitter App for your usage in order to obtain OAuth credentials, see <a href=\"https://dev.twitter.com/apps\">https://dev.twitter.com/apps</a> for help. After creating your app, fill below fields with your OAuth credentials.", 'wt_admin'),
				"one_col" => true,
				"type" => "wt_desc"
			),		
			array(	
				"name" => __("Consumer key", 'wt_admin'),
				"desc" => __("YOUR_CONSUMER_KEY.", 'wt_admin'),
				"id" => "consumer_key",
				"default" => "",
				"type" => "wt_text"
			),	
			array(	
				"name" => __("Consumer secret", 'wt_admin'),
				"desc" => __("YOUR_CONSUMER_SECRET.", 'wt_admin'),
				"id" => "consumer_secret",
				"default" => "",
				"type" => "wt_text"
			),	
			array(	
				"name" => __("Access token", 'wt_admin'),
				"desc" => __("YOUR_ACCESS_TOKEN.", 'wt_admin'),
				"id" => "access_token",
				"default" => "",
				"type" => "wt_text"
			),
			array(	
				"name" => __("Access token secret", 'wt_admin'),
				"desc" => __("YOUR_ACCESS_TOKEN_SECRET.", 'wt_admin'),
				"id" => "access_token_secret",
				"default" => "",
				"type" => "wt_text"
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
	'name' => 'twitter',
	'options' => $wt_options
);