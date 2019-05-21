<?php
/**
 * The `metaboxes` class help generate the html code for meta boxes.
 */
class wt_metaboxes {
	var $config;
	var $options;
	var $saved_options;
	
	/**
	 * Constructor
	 * 
	 * @param string $name
	 * @param array $options
	 */
	function wt_metaboxes($config, $options) {
		$this->config = $config;
		$this->options = $options;
		
		add_action('admin_menu', array(&$this, 'wt_create'));
		add_action('save_post', array(&$this, 'wt_save'));
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
	
	function wt_save($post_id) {
		if (! isset($_POST[$this->config['id'] . '_noncename'])) {
			return $post_id;
		}
		
		if (! wp_verify_nonce($_POST[$this->config['id'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		
		if ('page' == $_POST['post_type']) {
			if (! current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} else {
			if (! current_user_can('edit_post', $post_id)) {
				return $post_id;
			}
		}
	
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		add_post_meta($post_id, 'textfalse', false, true);
		
		foreach($this->options as $option) {
			if (isset($option['id']) && ! empty($option['id'])) {
				
				if (isset($_POST[$option['id']])) {
					switch ($option['type']) {
						case 'wt_multidropdown':
							$value = array_unique(explode(',', $_POST[$option['id']]));
							break;
						case 'wt_tritoggle':
							switch($_POST[$option['id']]){
								case 'true':
									$value = 'true';
									break;
								case 'false':
									$value = 'false';
									break;
								case 'default':
									$value = '';
							}
							break;
						case 'wt_toggle':
							$value = 'true';
							break;
						default:
							$value = $_POST[$option['id']];
					}
				} else if ($option['type'] == 'toggle') {
					$value = 'false';
				} else {
					$value = false;
				}
				
				if (get_post_meta($post_id, $option['id']) == "") {
					add_post_meta($post_id, $option['id'], $value, true);
				} elseif ($value != get_post_meta($post_id, $option['id'], true)) {
					update_post_meta($post_id, $option['id'], $value);
				} elseif ($value == "") {
					delete_post_meta($post_id, $option['id'], get_post_meta($post_id, $option['id'], true));
				}
			}
		}
	}
	
	function wt_render() {
		global $post;
		
		foreach($this->options as $option) {
			if (method_exists($this, $option['type'])) {
				if (isset($option['id'])) {
					$default = get_post_meta($post->ID, $option['id'], true);
					if ($default != "") {
						$option['default'] = $default;
					}
				}
				$this->{$option['type']}($option);
			}
		}
		
		echo '<input type="hidden" name="' . $this->config['id'] . '_noncename" id="' . $this->config['id'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	}
	
	/**
	 * prints out the navigation tabs 
	 */
	function wt_navigation($get_options) {
		if (isset($get_options['target'])) {
			if (isset($get_options['options'])) {
				$get_options['options'] = $get_options['options'] + $this->wt_get_select_target_options($get_options['target']);
			} else {
				$get_options['options'] = $this->wt_get_select_target_options($get_options['target']);
			}
		}
				
		echo '<div class="whoathemes_options_tabs">';
		echo '<div id="icon-themes" class="icon32"></div>';
		echo '<div class="' . $get_options['class'] . '">';		
		
		if (isset($get_options['options'])) {
			foreach($get_options['options'] as $key => $option) {
				echo "<a id='wt-option-" . $key . "-tab'";				
				echo " class='nav-tab'";							
				echo " href='#wt-option-" . $key . "'";				
				echo " title='" . $option . "'";
			
				echo '>' . $option . '</a>';
			}
		}
		
		echo '</div>';				
		echo '</div>';
	}
		
	/**
	 * prints out the options page groups
	 */
	function wt_option_group_start($value){
		echo '<div class="wt-option-group';		
		if(isset($value['group_class'])){
			echo ' '.$value['group_class'];
		}		
		echo '"';
		if(isset($value['group_id'])){
			echo ' id="wt-option-'.$value['group_id'].'"';
		}
		echo '>';
	}
	
	/**
	 * prints out the options for select fields
	 */		
	function wt_group_start($value){
		echo '<div class="meta-box-group';		
		if(isset($value['group_class'])){
			echo ' '.$value['group_class'];
		}		
		echo '"';
		if(isset($value['group_id'])){
			echo ' id="'.$value['group_id'].'"';
		}
		global $wp_version;
		if(version_compare($wp_version, "3.5", '>')){
			echo ' data-version="gt3_5"';
		}
		echo '>';
	}
	
	function wt_group_end($value){
		echo '</div>';
	}
	
	/**
	 * prints the title and desc
	 */
	function wt_title($value) {
		echo '<div class="meta-box-item">';
		if (isset($value['name'])) {
			echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4></div>';
		}
		if (isset($value['desc'])) {
			echo '<p>' . $value['desc'] . '</p>';
		}
		echo '</div>';
	}
	
	/**
	 * displays a text input
	 */
	function wt_text($value) {
		$size = isset($value['size']) ? $value['size'] : '';
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		echo '<input'.(isset($value['class'])?' class="'.$value['class'].'"':'').' name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" size="' . $size . '" value="' . wt_check_input($value['default']) . '" />';
		echo '<br /></div>';
		echo '</div>';
	}	
			
	/**
	 * displays a textarea
	 */
	function wt_textarea($value) {
		$rows = isset($value['rows']) ? $value['rows'] : '7';
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content"><textarea rows="' . $rows . '" name="' . $value['id'] . '" type="' . $value['type'] . '" class="code">' . wt_check_input($value['default']) . '</textarea>';
		echo '<br /></div>';
		echo '</div>';
	
	}
	
	function wt_featured_video($value) {
		$width = 256;
		$height = 144;
		echo '<div class="meta-box-item">';		
		echo '<div class="meta-box-item-content">';	
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="featured_video_frame">'.wt_featured_video($value['default'], false, false, $height,$width).'</div>';
		echo '<textarea rows="1" name="' . $value['id'] . '" type="' . $value['type'] . '" class="elastic code '.$value['class'].'">' . wt_check_input($value['default']) . '</textarea>';
		echo '</div></div>';
	}	
		
	/**
	 * displays a select
	 */
	function wt_select($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		if (isset($value['chosen'])=="true") {
			echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '" class="chzn-select">';
		}else{
			echo '<span class="wt_select_wrapp"><select name="' . $value['id'] . '" id="' . $value['id'] . '">';
		}
		$selected_value = '';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
			$selected_value = $value['prompt'];
		}
		
		foreach($value['options'] as $key => $option) {
			echo '<option value="' . $key . '"';
			if ($key == $value['default']) {
				echo ' selected="selected"';
				$selected_value = $option;
			}
			
			echo '>' . $option . '</option>';
		}
		if (isset($value['page'])){
			$depth = $value['page'];
			$selected = $value['default'];
			$args = array(
				'depth' => $depth, 'child_of' => 0,
				'selected' => $selected, 'echo' => 1,
				'name' => 'page_id', 'id' => '',
				'show_option_none' => '', 'show_option_no_change' => '',
				'option_none_value' => ''
			);
			$pages = get_pages($args);
			
			echo walk_page_dropdown_tree($pages,$depth,$args);
		}
		
		if (isset($value['chosen'])=="true") {
			echo '<select>';
		}else{
			echo '</select>';
			echo '<span class="wt_option_selected">'.$selected_value.'</span>';
			echo '</span>';
		}
		echo '<br /></div></div>';
	
	}	
				
	/**
	 * displays select gallery field
	 */
	function wt_selectGallery($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		echo '<select name="' . $value['id'] . '" id="' . $value['id'] . '">';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
		}
		foreach($value['options'] as $key => $option) {
			query_posts("post_type=galleryR&posts_per_page=99999");
			  
			if (have_posts()) : 
				while (have_posts()) : 
					the_post();	
					global $post; 
					$key =  $post->ID; 
					
					echo '<option '; 
					echo  'value="';
					echo  '' . $key . '"'; 
					if ($key == $value['default']) {
						echo ' selected="selected"';
					}
					 
					echo '>';
					echo '' .  the_title() . '</option>'; 
				endwhile;
				break;
			endif;
			wp_reset_query();
		}
		
		echo '</select><br /></div>';
		echo '</div>';
	
	}
	/**
	 * displays a select for Revolution Slider
	 */
	function wt_selectRev($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content"><select name="' . $value['id'] . '" id="' . $value['id'] . '">';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
		}
		if ( class_exists( 'GlobalsRevSlider' ) ) {
		global $wpdb;
			$table_name = $wpdb->base_prefix . "revslider_sliders";
			$slidedata = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id");
			$sql = "SELECT * FROM $table_name";
			foreach($slidedata  as $key => $option) {
			$valueS = $option->alias;
			   echo "<option name='".$option->title."'";
				echo " value='".$option->alias."'";
				if ($valueS == $value['default']) {
					echo ' selected="selected"';
				}
				echo ">".$option->title."</option>";
			}
		}
		echo '</select><br /></div>';
		echo '</div>';
	}
	
	/**
	 * displays a select for Layer Slider
	 */
	function wt_selectLayerS($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content"><select name="' . $value['id'] . '" id="' . $value['id'] . '">';
		if(isset($value['prompt'])){
			echo '<option value="">'.$value['prompt'].'</option>';
		}
		if(function_exists('layerslider_activation_scripts')){
			global $wpdb;
			$table_name = $wpdb->base_prefix . "layerslider";
			$slidedata = $wpdb->get_results("SELECT * FROM $table_name	WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY id");
			$sql = "SELECT * FROM $table_name";
			$result = mysql_query($sql) or die (mysql_error()); 
			foreach($slidedata  as $key => $option) {
			$valueS = $option->id;
			   echo "<option name='".$option->name."'";
				echo " value='".$option->id."'";
				if ($valueS == $value['default']) {
					echo ' selected="selected"';
				}
				echo ">".$option->name."</option>";
			}
		}
		echo '</select><br /></div>';
		echo '</div>';
	}

	/**
	 * displays a multiselect
	 */
	function wt_multiselect($value) {
		$size = isset($value['size']) ? $value['size'] : '5';
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		if (isset($value['chosen'])=="true") {
			echo '<div class="meta-box-item-content"><select name="' . $value['id'] . '[]" id="' . $value['id'] . '" class="chzn-select" multiple="multiple" size="' . $size . '" style="height:auto"';
		}else{
			echo '<select name="' . $value['id'] . '[]" id="' . $value['id'] . '" multiple="multiple" size="' . $size . '" style="height:auto">';
		}
		foreach($value['options'] as $key => $option) {
			echo '<option value="' . $key . '"';
			if (in_array($key, $value['default'])) {
				echo ' selected="selected"';
			}
			echo '>' . $option . '</option>';
		}
		
		echo '</select><br /></div>';
		echo '</div>';
	
	}
		
	/**
	 * displays a multidropdown
	 */
	function wt_multidropdown($value) {
		if (isset($value['target'])) {
			if (isset($value['options'])) {
				$value['options'] = $value['options'] + $this->wt_get_select_target_options($value['target']);
			} else {
				$value['options'] = $this->wt_get_select_target_options($value['target']);
			}
		}
		if (! is_array($value['default'])) {
			$value['default'] = array();
		}
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		
		echo '<div class="meta-box-item-content">';
		echo '<input type="hidden" id="' . $value['id'] . '" name="' . $value['id'] . '" value="' . implode(',', $value['default']) . '"/>';
		echo '<div class="multidropdown-wrap">';
		
		$i = 0;
		if (is_array($value['default'])) {
			foreach($value['default'] as $selected) {
				echo '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '">';
				echo '<option value="">Choose one...</option>';
				foreach($value['options'] as $key => $option) {
					echo '<option value="' . $key . '"';
					if ($selected == $key) {
						echo ' selected="selected"';
					}
					echo '>' . $option . '</option>';
				}
				$i++;
				echo '</select>';
			}
		}
		
		echo '<select name="' . $value['id'] . '_' . $i . '" id="' . $value['id'] . '_' . $i . '">';
		echo '<option value="">Choose one...</option>';
		foreach($value['options'] as $key => $option) {
			echo '<option value="' . $key . '">' . $option . '</option>';
		}
		echo '</select></div></div>';
		echo '</div>';
	}
	
	function wt_superlink($value) {
		$target = '';
		if (! empty($value['default'])) {
			list($target, $target_value) = explode('||', $value['default']);
		}
		if ( empty($value['shows'])) {
			$value['shows'] = array('page','cat','post','wt_portfolio','manually');
		}
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		
		echo '<div class="meta-box-item-content">';
		echo '<input type="hidden" id="' . $value['id'] . '" name="' . $value['id'] . '" value="' . $value['default'] . '"/>';
		
		$method_options = array(
			'page' => 'Link to page',
			'cat' => 'Link to category',
			'post' => 'Link to post',
			'portfolio'=> 'Link to portfolio',
			'manually' => 'Link manually'
		);
		
		foreach ($method_options as $key => $v){
			if(!in_array($key,$value['shows'])){
				unset($method_options[$key]);
			}
		}
		
		echo '<select name="' . $value['id'] . '_selector" id="' . $value['id'] . '_selector">';
		echo '<option value="">Select Linking method</option>';
		foreach($method_options as $key => $option) {
			echo '<option value="' . $key . '"';
			if ($key == $target) {
				echo ' selected="selected"';
			}
			echo '>' . $option . '</option>';
		}
		echo '</select>';
		
		echo '<div class="superlink-wrap">';
		
		if(in_array('page',$value['shows'])){
			//render page selector
			$hidden = ($target != "page") ? 'class="hidden"' : '';
			echo '<select name="' . $value['id'] . '_page" id="' . $value['id'] . '_page" ' . $hidden . '>';
			echo '<option value="">Select Page</option>';
			
			$selected = ($target == "page")?$target_value:0;
			$args = array(
				'depth' => 0, 'child_of' => 0,
				'selected' => $selected, 'echo' => 1,
				'name' => 'page_id', 'id' => '',
				'show_option_none' => '', 'show_option_no_change' => '',
				'option_none_value' => ''
			);
			$pages = get_pages($args);
			echo walk_page_dropdown_tree($pages,$args['depth'],$args);
			
			/*
			foreach($this->wt_get_select_target_options('page') as $key => $option) {
				echo '<option value="' . $key . '"';
				if ($target == "page" && $key == $target_value) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
			*/
			echo '</select>';
		}
		
		if(in_array('portfolio',$value['shows'])){
			//render portfolio selector
			$hidden = ($target != "portfolio") ? 'class="hidden"' : '';
			echo '<select name="' . $value['id'] . '_page" id="' . $value['id'] . '_portfolio" ' . $hidden . '>';
			echo '<option value="">Select Portfolio</option>';
			foreach($this->wt_get_select_target_options('portfolio') as $key => $option) {
				echo '<option value="' . $key . '"';
				if ($target == "portfolio" && $key == $target_value) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
			echo '</select>';
		}

		if(in_array('cat',$value['shows'])){
			//render category selector
			$hidden = ($target != "cat") ? 'class="hidden"' : '';
			echo '<select name="' . $value['id'] . '_cat" id="' . $value['id'] . '_cat" ' . $hidden . '>';
			echo '<option value="">Select Category</option>';
			foreach($this->wt_get_select_target_options('cat') as $key => $option) {
				echo '<option value="' . $key . '"';
				if ($target == "cat" && $key == $target_value) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
			echo '</select>';
		}
		
		if(in_array('post',$value['shows'])){
			//render post selector
			$hidden = ($target != "post") ? 'class="hidden"' : '';
			echo '<select name="' . $value['id'] . '_post" id="' . $value['id'] . '_post" ' . $hidden . '>';
			echo '<option value="">Select Post</option>';
			foreach($this->wt_get_select_target_options('post') as $key => $option) {
				echo '<option value="' . $key . '"';
				if ($target == "post" && $key == $target_value) {
					echo ' selected="selected"';
				}
				echo '>' . $option . '</option>';
			}
			echo '</select>';
		}
		
		if(in_array('manually',$value['shows'])){
			//render manually
			$hidden = ($target != "manually") ? 'class="hidden"' : '';
			echo '<input name="' . $value['id'] . '_manually" id="' . $value['id'] . '_manually" type="text" value="';
			if ($target == 'manually') {
				echo $target_value;
			}
			echo '" size="35" ' . $hidden . '/>';
		}
		
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	
	/**
	 * displays a checkbox
	 */
	function wt_checkbox($value) {
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		
		echo '<div class="meta-box-item-content">';
		$i = 0;
		foreach($value['options'] as $key => $option) {
			$i++;
			$checked = '';
			if (is_array($value['default']) && in_array($key, $value['default'])) {
				$checked = ' checked="checked"';
			}
			echo '<input type="checkbox" name="' . $value['id'] . '[]" id="' . $value['id'] . '_' . $i . '" value="' . $key . '" ' . $checked . ' />';
			echo '<label for="' . $value['id'] . '_' . $i . '">' . $option . '</label><br />';
		}
		echo '</div>';
		echo '</div>';
	
	}
	
	/**
	 * displays a radio
	 */
	function wt_radio($value) {
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		$i = 0;
		foreach($value['options'] as $key => $option) {
			$i++;
			$checked = '';
			if ($key == $value['default']) {
				$checked = ' checked="checked"';
			}
			
			echo '<input type="radio" id="' . $value['id'] . '_' . $i . '" name="' . $value['id'] . '" value="' . $key . '" ' . $checked . ' />';
			echo '<label for="' . $value['id'] . '_' . $i . '">' . $option . '</label><br />';
		}
		echo '</div>';
		echo '</div>';
	
	}
	
	/**
	 * displays a upload field
	 */
	function wt_upload($value) {
		$button = isset($value['button']) ? $value['button'] : 'Insert Image';
		$removebutton = isset($value['button']) ? $value['button'] : 'Remove Image';
		global $post_ID, $temp_ID;
		$postid = (int) (0 == $post_ID ? $temp_ID : $post_ID);
		
		echo '<div class="meta-box-item clearfix">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		
		echo '<div class="meta-box-item-content">';
		
		echo '<div id="' . $value['id'] . '_preview" class="image-preview">';

		if (! empty($value['default'])) {			
			
			$filename = substr($value['default'], 0, strrpos($value['default'], '.'));
			$extension = substr($value['default'], strrpos($value['default'], '.') + 1);

			echo '<a class="thickbox" href="' . $value['default'] . '" target="_blank"><img src="' . $filename . '-150x150.'.$extension.'"/></a>';
		}
		echo '</div>';
		
		echo '<input type="text" class="input_uploader upload-value" id="' . $value['id'] . '" name="' . $value['id'] . '"  value="';
		echo wt_check_input($value['default']);
		global $wp_version;
		if(version_compare($wp_version, "3.5", '<')){
			echo '" /><div class="theme-upload-buttons"><a class="thickbox button theme-upload-button button-primary" id="' . $get_options['id'] . '_button" href="media-upload.php?&post_id=' . $postid . '&target=' . $value['id'] . '&option_image_upload=1&TB_iframe=1&width=640&height=529">' . $button . '</a>';
		} else {
			echo '" /><div class="theme-upload-buttons"><a href="#" class="button theme-upload-button button-primary" data-target="' .  $value['id']  . '" data-uploader_title="'.$button.'" data-uploader_button_text="'.$button.'" title="' . $button . '">' .$button . '</a>';
		}
		echo '<a class="button-secondary theme-upload-media-button theme-upload-remove" id="' . $value['id'] . '_remove">' . $removebutton . '</a>';
		//echo '" /><div class="theme-upload-buttons"><a class="thickbox button theme-upload-button button-primary" id="' . $value['id'] . '" href="media-upload.php?&post_id=' . $postid . '&target=' . $value['id'] . '&option_image_upload=1&type=image&TB_iframe=1&width=640&height=529">'.$button.'</a></div>';
		
		echo '</div></div>';
		echo '</div>';
	}
/**
	 * displays a range input
	 */
	function wt_range($value) {
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		echo '<div class="range-input-wrap"><input name="' . $value['id'] . '" id="' . $value['id'] . '" type="range" value="'.$value['default'];
		
		if (isset($value['min'])) {
			echo '" min="' . $value['min'];
		}
		if (isset($value['max'])) {
			echo '" max="' . $value['max'];
		}
		if (isset($value['step'])) {
			echo '" step="' . $value['step'];
		}
		echo '" />';
		if (isset($value['unit'])) {
			echo '<span>' . $value['unit'] . '</span>';
		}
		echo '<br /></div></div>';
		echo '</div>';
	}
	
	/**
	 * displays a color input
	 */
	function wt_color($value) {
		$size = isset($value['size']) ? $value['size'] : '10';
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		$val = wt_check_input($value['default']);
		
		if(empty($val)){
			$transparent = true;
		}else{
			$transparent = false;
		}
		
		echo '<div class="meta-box-item-content">';
		echo '<div class="color-input-wrap"><input'.(isset($value['class'])?' class="'.$value['class'].'"':'').' name="' . $value['id'] . '" id="' . $value['id'] . '" type="text" '.($transparent?'data-transparent="true" ':'').'data-hex="true" size="' . $size . '" value="' . $val . '" /></div>';
		echo '<br /></div>';
		echo '</div>';
	}
	
	/**
	 * displays a toggle button
	 */
	function wt_toggle($value) {
		$checked = '';
		if ($value['default'] == 'true') {
			$checked = 'checked="checked"';
		}
		
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		
		echo '<div class="meta-box-item-content"><input type="checkbox" class="toggle-button" name="' . $value['id'] . '" id="' . $value['id'] . '" value="true" ' . $checked . ' />';
		echo '</div>';
		echo '</div>';
	
	}
	
	/**
	 * displays a toggle button
	 */
	function wt_tritoggle($value) {
		if($value['default']==='-1'){
			$value['default']='';//for theme compatibility
		}
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		echo '<select class="tri-toggle-button" name="' . $value['id'] . '" id="' . $value['id'] . '">';
		echo '<option value="true"'.selected($value['default'],'true').'>On</option>';
		echo '<option value="false"'.selected($value['default'],'false').'>Off</option>';
		echo '<option value="default"'.selected($value['default'],'').'>default</option>';
		echo '</select>';
		echo '</div>';
		echo '</div>';
	}
	
	/**
	 * displays a editor
	 */
	function wt_editor($value) {
		echo '<div class="meta-box-item">';
		echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
		if (isset($value['desc'])) {
			echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
		} else {
			echo '</div>';
		}
		echo '<div class="meta-box-item-content">';
		wp_editor($value['default'],$value['id']);
		echo '</div>';
		echo '</div>';
	}

	/**
	 * displays a custom field
	 */
	function wt_custom($value) {
		if(isset($value['layout']) && $value['layout']==false){
			if (isset($value['function']) && function_exists($value['function'])) {
				$value['function']($value, $value['default']);
			} else {
				echo $value['html'];
			}
		}else{
			echo '<div class="meta-box-item">';
			echo '<div class="meta-box-item-title"><h4>' . $value['name'] . '</h4>';
			if (isset($value['desc'])) {
				echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $value['desc'] . '</p>';
			} else {
				echo '</div>';
			}
			echo '<div class="meta-box-item-content">';
		
			if (isset($value['function']) && function_exists($value['function'])) {
				$value['function']($value, $value['default']);
			} else {
				echo $value['html'];
			}
			echo '</div>';
			echo '</div>';
		}
	
	}
		
	function wt_get_select_target_options($type) {
		$options = array();
		switch($type){
			case 'page':
				$entries = get_pages('title_li=&orderby=name');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'cat':
				$entries = get_categories('title_li=&orderby=name&hide_empty=0');
				foreach($entries as $key => $entry) {
					$options[$entry->term_id] = $entry->name;
				}
				break;
			case 'post':
				$entries = get_posts('orderby=title&numberposts=-1&order=ASC');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'wt_portfolio':
				$entries = get_posts('post_type=wt_portfolio&orderby=title&numberposts=-1&order=ASC');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
				break;
			case 'wt_portfolio_category':
				$entries = get_terms('wt_portfolio_category','orderby=name&hide_empty=0');
				foreach($entries as $key => $entry) {
					$options[$entry->slug] = $entry->name;
				}
				break;
			case 'wt_section':
				$entries = get_posts('post_type=wt_section&orderby=title&numberposts=-1&order=ASC&suppress_filters=0');
				foreach($entries as $key => $entry) {
					$options[$entry->ID] = $entry->post_title;
				}
			break;
		}
		
		return $options;
	}
}
