<?php
$config = array(
	'title' => sprintf(__('%s Product Hover','wt_admin'),THEME_NAME),
	'id' => 'wt_product_hover',
	'pages' => array('product'),
	'callback' => '',
	'context' => 'side',
	'priority' => 'low',
);
$options = array(
	array(
		"name" => __("Hover effect on Overview Pages",'wt_admin'),
		"desc" => "Display a hover effect on overview pages and replace the default featured thumbnail with the first image of the gallery?",
		"id" => "_product_hover",
		"default" => 'no_hover_active',
		"options" => array(
			"hover_active" => __('Yes - show first gallery image on hover','wt_admin'),
			"no_hover_active" => __('No hover effect','wt_admin'),
		),
		"type" => "wt_select",
	),
);
new wt_metaboxes($config,$options);