<?php
include_once (THEME_FILES . '/options.php');
class wt_shortcodes extends wt_options {
	
	function wt_shortcodes($config, $wt_shortcodes){
		$this->config = $config;
		$this->options = $wt_shortcodes;
		add_action('admin_init', array(&$this, 'wt_add_script'));
		add_action('admin_menu', array(&$this, 'wt_create'));
	}
	function wt_create() {
		if (function_exists('add_meta_box')) {
			if (! empty($this->config['callback']) && function_exists($this->config['callback'])) {
				$callback = $this->config['callback'];
			} else {
				$callback = array(&$this, 'wt_render');
			}
			foreach($this->config['pages'] as $page) {
				add_meta_box($this->config['id'], $this->config['title'], $callback, $page, $this->config['context'], $this->config['priority']);
			}
		}
	}
	function wt_add_script(){
		
		if( wt_is_post_type_new($this->config['pages']) || wt_is_post_type_post($this->config['pages']) ){
			wp_enqueue_script('theme-shortcode');
		}
	}
		
	function wt_render() {
		global $post;
		
		echo '<div class="shortcode_generator"><table class="theme-options-table" cellspacing="0"><tbody><tr><th scope="row" style="text-align:left"><h4><label for="shortcode_generator">Shortcode</label></h4><select class="chzn-select" name="sc_generator" autocomplete="off">';
		echo '<option value="" disabled="true" selected="selected">Select a shortcode...</option>';
		foreach($this->options as $shortcode) {
			echo '<option value="'.$shortcode['value'].'">'.$shortcode['name'].'</option>';
		}
		echo '</select></th></tr></tbody></table></div>';
		
		foreach($this->options as $shortcode) {
			echo '<div id="shortcode_'.$shortcode['value'].'" class="shortcode_wrap">';
			if(isset($shortcode['sub'])){
					echo '<div class="shortcode_sub_generator"><table cellspacing="0" class="theme-options-table"><tbody><tr><th scope="row" style="text-align:left"><h4><label for="shortcode_generator">Type</label></h4><select class="chzn-select" name="sc_'.$shortcode['value'].'_generator" autocomplete="off">';
				echo '<option value="" disabled="true" selected="selected">Choose one...</option>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<option value="'.$sub_shortcode['value'].'">'.$sub_shortcode['name'].'</option>';
				}
				echo '</select></th></tr></tbody></table></div>';
				foreach($shortcode['options'] as $sub_shortcode) {
					echo '<div id="sub_shortcode_'.$sub_shortcode['value'].'" class="sub_shortcode_wrap"><table cellspacing="0" class="theme-options-table"><tbody>';
					foreach($sub_shortcode['options'] as $option){
						if (method_exists($this, $option['type'])) {
							if (isset($option['id'])) {
								$option['id']='sc_'.$shortcode['value'].'_'.$sub_shortcode['value'].'_'.$option['id'];
							}
							$this->$option['type']($option);
						}
					}										
					echo '</tbody></table></div>';
				}
			}else{
				echo '<table cellspacing="0" class="theme-options-table"><tbody>';
				foreach($shortcode['options'] as $option){
					if (method_exists($this, $option['type'])) {
						if (isset($option['id'])) {
							$option['id']='sc_'.$shortcode['value'].'_'.$option['id'];
						}
						$this->$option['type']($option);
					}
				}
				echo '</tbody></table>';
			}
			
			echo '</div>';
		}
		echo '<p><input type="button" id="shortcode_send" class="button button-primary" value="Send Shortcode to Editor »"/></p>';
	}
}