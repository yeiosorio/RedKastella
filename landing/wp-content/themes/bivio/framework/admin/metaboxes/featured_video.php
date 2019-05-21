<?php
$config = array(
	'title' => sprintf(__('%s Featured Video','wt_admin'),THEME_NAME),
	'id' => 'featured_video',
	'pages' => array('page', 'post', 'portfolio'),
	'callback' => '',
	'context' => 'side',
	'priority' => 'default',
);
$options = array(
	array(
		"name" => "Paste video link below:",
		"desc" => "Accepted videos: YouTube, Vimeo, Daylimotion, Metacafe",
		"id" => "_featured_video",
		"class" => "large_width featured_video",
		"default" => "",
		"type" => "wt_featured_video"
	),	
);
new wt_metaboxes($config,$options);