<?php
if (! function_exists("wt_footer_column_option")) {
	function wt_footer_column_option($value, $default) {
		
		echo '<script type="text/javascript" src="' . THEME_ADMIN_ASSETS_URI . '/js/theme-footer-column.js"></script>';
		echo '<div class="theme-footer-columns">';
		//echo '<div>';
		echo '<a href="#" rel="1"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/1.png" /></a>';
		echo '<a href="#" rel="2"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/2.png" /></a>';
		echo '<a href="#" rel="3"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/3.png" /></a>';
		echo '<a href="#" rel="4"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/4.png" /></a>';
		echo '<a href="#" rel="6"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/5.png" /></a>';
		//echo '</div><div>';
		echo '<a href="#" rel="col-lg-9_col-lg-3"><img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/four_fifth_one_fifth.png" /></a>';
		echo '<a href="#" rel="col-lg-3_col-lg-9">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_fifth_four_fifth.png" /></a>';
		echo '<a href="#" rel="col-lg-2_col-lg-5_col-lg-5">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_fifth_two_fifth_two_fifth.png" /></a>';
		echo '<a href="#" rel="col-lg-3_col-lg-3_col-lg-6">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_fourth_one_fourth_one_half.png" /></a>';
		echo '<a href="#" rel="col-lg-3_col-lg-9">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_fourth_three_fourth.png" /></a>';
		//echo '</div><div>';
		echo '<a href="#" rel="col-lg-6_col-lg-3_col-lg-3">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_half_one_fourth_one_fourth.png" /></a>';
		echo '<a href="#" rel="col-lg-3_col-lg-6_col-lg-3">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_fourth_one_half_one_fourth.png" /></a>';
		echo '<a href="#" rel="col-lg-4_col-lg-8">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/one_third_two_third.png" /></a>';
		echo '<a href="#" rel="col-lg-8_col-lg-4">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/three_fifth_two_fifth.png" /></a>';
		echo '<a href="#" rel="col-lg-9_col-lg-3">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/three_fourth_one_fourth.png" /></a>';
		//echo '</div><div>';
		echo '<a href="#" rel="col-lg-5_col-lg-7">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/two_fifth_three_fifth.png" /></a>';
		echo '<a href="#" rel="col-lg-5_col-lg-5_col-lg-2">
			<img src="' . THEME_ADMIN_ASSETS_URI . '/images/footer_column/two_fifth_two_fifth_one_fifth.png" /></a>';
		//echo '</div><div>';
		echo '<input type="hidden" value="' . $default . '" name="' . $value['id'] . '" id="' . $value['id'] . '"/>';
		echo '</div>';
	}
}
$wt_options = array(
	array(
		"name" => __("Footer",'wt_admin'),
		"type" => "wt_title"
	),
	array(
		"name" => __("Footer",'wt_admin'),
		"type" => "wt_open"
	),
		array(
			"name" => __("Footer Top",'wt_admin'),
			"desc" => __("If the button is set to OFF then the footer top area won't be displayed.",'wt_admin'),
			"id" => "footer_top",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Footer",'wt_admin'),
			"desc" => __("If the button is set to OFF then the footer area won't be displayed.",'wt_admin'),
			"id" => "footer",
			"default" => false,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Footer Bottom",'wt_admin'),
			"desc" => __("If the button is set to OFF then the sub footer area won't be displayed.",'wt_admin'),
			"id" => "sub_footer",
			"default" => true,
			"type" => "wt_toggle"
		),
		array(
			"name" => __("Footer Column Layout",'wt_admin'),
			"desc" => __("Choose the footer column layout.",'wt_admin'),
			"id" => "column",
			"function" => "wt_footer_column_option",
			"default" => "4",
			"type" => "wt_custom"
		),
		array(
			"name" => __("Copyright Text",'wt_admin'),
			"desc" => __("Here you can enter the copyright text which is displayed in the sub footer.",'wt_admin'),
			"id" => "copyright",
			"default" => "© Copyright 2014 <span>Bivio</span> All Right Reserved",
			"rows" => 4,
			"type" => "wt_textarea"
		),
	array(
		"type" => "wt_close"
	),
	array(
		"type" => "wt_reset"
	),
);
return array(
	'auto' => true,
	'name' => 'footer',
	'options' => $wt_options
);