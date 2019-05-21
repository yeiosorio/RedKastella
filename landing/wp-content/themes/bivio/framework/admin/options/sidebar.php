<?php
if (! function_exists("wt_add_sidebar_option")) {
	function wt_add_sidebar_option($value, $default) {
		if(!empty($default)){
			$sidebars = explode(',',$default);
		}else{
			$sidebars = array();
		}
		
		echo <<<HTML
<script type="text/javascript">
jQuery(document).ready( function($) {
	$("#add_sidebar").validator({effect:'option'}).closest('form').submit(function(e) {
		if (!e.isDefaultPrevented() && $("#add_sidebar").val()) {
			if($('#sidebars').val()){
				$('#sidebars').val($('#sidebars').val()+','+$("#add_sidebar").val());
			}else{
				$('#sidebars').val($("#add_sidebar").val());
			}
		}
	});
	$(".sidebar-item input:button").click(function(){
		$(this).closest(".sidebar-item").fadeOut("normal",function(){
  			$(this).remove();
  			$('#sidebars').val('');
			$(".sidebar-item-value").each(function(){
				if($('#sidebars').val()){
					$('#sidebars').val($('#sidebars').val()+','+$(this).val());
				}else{
					$('#sidebars').val($(this).val());
				}
			});
 		});
		
	});
	
});
</script>
<style type="text/css">
.sidebar-title {
	margin:20px 0 5px;
	font-weight:bold;
}
.sidebar-item {
	width: 260px;
	padding: 5px 9px;   
	background-color: #F5F5F5;
    background-image: -moz-linear-gradient(center top , #F9F9F9, #F5F5F5);    
	border: 1px solid #DFDFDF;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 1px 0 #FFFFFF inset;
	text-shadow: 0 1px 0 #FFFFFF;
	margin-bottom: 10px;
	position: relative;
}
.sidebar-item .button {
	position: absolute;
	top: 2px;
	right: 9px;
	padding: 1px 8px;
}

</style>
HTML;
		
		echo '<input type="text" id="add_sidebar" name="add_sidebar" pattern="([a-zA-Z\x7f-\xff][ a-zA-Z0-9_\x7f-\xff]*){0,1}" data-message="'.__('Please input a valid name which starts with a letter, followed by letters, numbers, spaces, or underscores.').'" maxlength="20" /><span class="validator-error"></span>';
		if(!empty($sidebars)){
			echo '<div class="sidebar-title">'.__('Below are the Custom Sidebars you\'ve generated','wt_admin').'</div>';
			foreach($sidebars as $sidebar){
				echo '<div class="sidebar-item"><span>'.$sidebar.'</span><input type="hidden" class="sidebar-item-value" value="'.$sidebar.'"/><input type="button" class="button" value="'.__('Delete','wt_admin').'"/></div>';
			}
		}
		echo '<input type="hidden" value="' . $default . '" name="' . $value['id'] . '" id="sidebars"/>';
	}
}
$wt_options = array(
	array(
		"name" => __("Sidebar",'wt_admin'),
		"type" => "wt_title"
	),
	array(
		"name" => __("Sidebar",'wt_admin'),
		"type" => "wt_open"
	),
		array(
			"name" => __("Generate Custom Sidebar",'wt_admin'),
			"desc" => __("Enter the sidebar name you'd like to create.",'wt_admin'),
			"id" => "sidebars",
			"function" => "wt_add_sidebar_option",
			"default" => "",
			"type" => "wt_custom"
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
	'name' => 'sidebar',
	'options' => $wt_options
);