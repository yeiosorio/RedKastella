<?php
/**
 * Social Icon Widget Class
 */
class Wt_Widget_Social_Font_Awesome extends WP_Widget {

	var $sites = array(
		'Facebook','Twitter','Pinterest','Linkedin','Google','Behance','Dribbble','Youtube','Vimeo','Rss','Github','Delicious','Flickr','Lastfm','Tumblr','Deviantart','Skype','Instagram','Stumbleupon','Soundcloud'
	
	);
	var $packages = array(
		'social' => array(
			'name'=>'Social',
			//'path'=>'{:name}.png',
			//'path_32'=>'{:name}_32.png',
			'class'=>'{:name}',
		),
	);
	
	
	function Wt_Widget_Social_Font_Awesome() {
		$widget_ops = array('classname' => 'widget_social_font_awesome', 'description' => __( 'A list of Social Icons With Font', 'wt_admin') );
		parent::__construct('social_font_aw', THEME_SLUG.' - '.__('Social Icon Font', 'wt_admin'), $widget_ops);
		
		if ('widgets.php' == basename($_SERVER['PHP_SELF'])) {
			add_action( 'admin_enqueue_scripts', array(&$this, 'add_admin_script') );
		}
	}
	
	function add_admin_script(){
		wp_enqueue_script( 'social-icon-widget', THEME_ADMIN_ASSETS_URI . '/js/social.js', array('jquery'));
	}
	

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base);
		
		$package = $instance['package'];
		$custom_count = $instance['custom_count'];
		$tooltip = $instance['tooltip'] ? '1' : '0';
		$icons_type = $instance['icons_type'] ? $instance['icons_type'] :'type_1';
		$icons_style = $instance['icons_style'] ? $instance['icons_style'] :'simple';
		$icons_size = $instance['icons_size'] ? $instance['icons_size'] :'34';
		$output = '';
		if( !empty($instance['enable_sites']) ){
			foreach($instance['enable_sites'] as $site){
				$link = isset($instance[$site])?$instance[$site]:'#';
				$class = str_replace('{:name}',strtolower($site),$this->packages[$package]['class']);
				$output .= '<li data-alt="'.$site.'"><a href="'.$link.'" class="'.$class.'"';
				if ($tooltip == true) { 
					$output .= ' data-toggle="tooltip"';
				}
				switch( $class ) {
					case 'website'     : $icon_output = '<i class="entypo-link"></i>';       break;
					case 'email'       : $icon_output = '<i class="fa-envelope"></i>';       break;
					case 'facebook'    : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'twitter'     : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'pinterest'   : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'linkedin'    : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'google' 	   : $icon_output = '<i class="entypo-gplus"></i>';      break;
					case 'behance'     : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'dribbble'    : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'youtube'     : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'vimeo'       : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'rss'         : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'github'      : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'delicious'   : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'flickr'      : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					//case 'forrst'      : $icon_output = '<i class="fa-'.$icon.'"></i>'; break;
					case 'lastfm'      : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'tumblr'      : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'deviantart'  : $icon_output = '<i class="fa-'.$class.'"></i>';     break;
					case 'skype'       : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'instagram'   : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'stumbleupon' : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
					case 'soundcloud'  : $icon_output = '<i class="entypo-'.$class.'"></i>'; break;
				}
				$output .= ' rel="nofollow" title="'.$site.'" target="_blank">'.$icon_output;
				$output .= '</a></li>';
			}
		}
		if( $custom_count > 0){
			for($i=1; $i<= $custom_count; $i++){
				$name = isset($instance['custom_'.$i.'_name'])?$instance['custom_'.$i.'_name']:'';
				$icon = isset($instance['custom_'.$i.'_icon'])?$instance['custom_'.$i.'_icon']:'';
				$link = isset($instance['custom_'.$i.'_url'])?$instance['custom_'.$i.'_url']:'#';
				if(!empty($icon)){
					$output .= '<a href="'.$link.'" rel="nofollow" target="_blank"><img src="'.$icon.'" alt="'.$name.'" title="'.$name.'"/></a>';
				}
			}
		}
		if ( !empty( $output ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>
	
        <div class="wt_socialWrapperAw clearfix">
            <div class="wt_social_networks_sc">
                <ul class="wt_icon_<?php echo $icons_size; ?> wt_icon_<?php echo $icons_type; ?> wt_<?php echo $icons_style; ?>">
                    <?php echo $output; ?>
                </ul>
            </div>
        </div>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tooltip'] = !empty($new_instance['tooltip']) ? 1 : 0;
		$instance['icons_type'] = $new_instance['icons_type'];
		$instance['icons_style'] = $new_instance['icons_style'];
		$instance['icons_size'] = $new_instance['icons_size'];
		$instance['package'] = strip_tags($new_instance['package']);
		$instance['enable_sites'] = $new_instance['enable_sites'];
		$instance['custom_count'] = (int) $new_instance['custom_count'];

		if(!empty($instance['enable_sites'])){
			foreach($instance['enable_sites'] as $site){
				$instance[$site] = isset($new_instance[$site])?strip_tags($new_instance[$site]):'';
			}
		}
		for($i=1;$i<=$instance['custom_count'];$i++){
			$instance['custom_'.$i.'_name'] = strip_tags($new_instance['custom_'.$i.'_name']);
			$instance['custom_'.$i.'_url'] = strip_tags($new_instance['custom_'.$i.'_url']);
			$instance['custom_'.$i.'_icon'] = strip_tags($new_instance['custom_'.$i.'_icon']);
		}
		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$tooltip = isset( $instance['tooltip'] ) ? (bool) $instance['tooltip'] : false;
		$package = isset($instance['package']) ? $instance['package'] : '';
		$icons_style = isset( $instance['icons_style'] ) ? $instance['icons_style'] : 'simple';
		$icons_type = isset( $instance['icons_type'] ) ? $instance['icons_type'] : 'type_1';
		$enable_sites = isset($instance['enable_sites']) ? $instance['enable_sites'] : array();
		$icons_size = isset( $instance['icons_size'] ) ? $instance['icons_size'] : '34';
		foreach($this->sites as $site){
			$$site = isset($instance[$site]) ? esc_attr($instance[$site]) : '';
		}

		$custom_count = isset($instance['custom_count']) ? absint($instance['custom_count']) : 0;
		for($i=1;$i<=10;$i++){
			$custom_name = 'custom_'.$i.'_name';
			$$custom_name = isset($instance[$custom_name]) ? $instance[$custom_name] : '';
			$custom_url = 'custom_'.$i.'_url';
			$$custom_url = isset($instance[$custom_url]) ? $instance[$custom_url] : '';
			$custom_icon = 'custom_'.$i.'_icon';
			$$custom_icon = isset($instance[$custom_icon]) ? $instance[$custom_icon] : '';
		}
	?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
         <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('tooltip'); ?>" name="<?php echo $this->get_field_name('tooltip'); ?>"<?php checked( $tooltip ); ?> />
		<label for="<?php echo $this->get_field_id('tooltip'); ?>"><?php _e( 'Use Tooltip?', 'wt_admin' ); ?></label></p>
        <p>
			<label for="<?php echo $this->get_field_id('display_extra_size'); ?>"><?php _e( 'Choose icon size:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('icons_size'); ?>" id="<?php echo $this->get_field_id('icons_size'); ?>" class="widefat">
				<option value="26"<?php selected($icons_size,'26');?>><?php _e( '26', 'wt_admin' ); ?></option>
				<option value="32"<?php selected($icons_size,'32');?>><?php _e( '32', 'wt_admin' ); ?></option>
				<option value="34"<?php selected($icons_size,'34');?>><?php _e( '34', 'wt_admin' ); ?></option>
				<option value="38"<?php selected($icons_size,'38');?>><?php _e( '38', 'wt_admin' ); ?></option>
				<option value="40"<?php selected($icons_size,'40');?>><?php _e( '40', 'wt_admin' ); ?></option>
				<option value="42"<?php selected($icons_size,'42');?>><?php _e( '42', 'wt_admin' ); ?></option>
				<option value="44"<?php selected($icons_size,'44');?>><?php _e( '44', 'wt_admin' ); ?></option>
				<option value="50"<?php selected($icons_size,'50');?>><?php _e( '50', 'wt_admin' ); ?></option>
			</select>
		</p>
         <p>
			<label for="<?php echo $this->get_field_id('display_extra_type'); ?>"><?php _e( 'Choose icon type:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('icons_type'); ?>" id="<?php echo $this->get_field_id('icons_type'); ?>" class="widefat">
				<option value="type_1"<?php selected($icons_type,'type_1');?>><?php _e( 'Type #1', 'wt_admin' ); ?></option>
				<option value="type_2"<?php selected($icons_type,'type_2');?>><?php _e( 'Type #2', 'wt_admin' ); ?></option>
				<option value="type_3"<?php selected($icons_type,'type_3');?>><?php _e( 'Type #3', 'wt_admin' ); ?></option>
				<option value="type_4"<?php selected($icons_type,'type_4');?>><?php _e( 'Type #4', 'wt_admin' ); ?></option>
			</select>
		</p>
        <p>
			<label for="<?php echo $this->get_field_id('display_extra_style'); ?>"><?php _e( 'Choose icon style:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('icons_style'); ?>" id="<?php echo $this->get_field_id('icons_style'); ?>" class="widefat">
				<option value="simple"<?php selected($icons_style,'simple');?>><?php _e( 'Simple', 'wt_admin' ); ?></option>
				<option value="circle"<?php selected($icons_style,'circle');?>><?php _e( 'Circle', 'wt_admin' ); ?></option>
				<option value="rounded"<?php selected($icons_style,'rounded');?>><?php _e( 'Rounded', 'wt_admin' ); ?></option>
				<option value="square"<?php selected($icons_style,'square');?>><?php _e( 'Square', 'wt_admin' ); ?></option>
			</select>
		</p>
		<p style="display:none;">
			<label for="<?php echo $this->get_field_id('package'); ?>"><?php _e( 'Icon Package:' , 'wt_admin'); ?></label>
			<select name="<?php echo $this->get_field_name('package'); ?>" id="<?php echo $this->get_field_id('package'); ?>" class="widefat">
				<?php foreach($this->packages as $name => $value):?>
				<option value="<?php echo $name;?>"<?php selected($package,$name);?>><?php echo $value['name'];?></option>
				<?php endforeach;?>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('enable_sites'); ?>"><?php _e( 'Enable Social Icon: (CTRL + Click)', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('enable_sites'); ?>[]" style="height:10em" id="<?php echo $this->get_field_id('enable_sites'); ?>" class="social_icon_select_sites widefat" multiple="multiple">
				<?php foreach($this->sites as $site):?>
				<option value="<?php echo $site;?>"<?php echo in_array($site, $enable_sites)? 'selected="selected"':'';?>><?php echo $site;?></option>
				<?php endforeach;?>
			</select>
		</p>
		
		<p>
			<em><?php _e("Note: Please input FULL URL <br/>(e.g. <code>http://www.example.com</code>)", 'wt_admin');?></em>
		</p>
		<div class="social_icon_wrap_2">
		<?php foreach($this->sites as $site):?>
		<p class="social_icon_<?php echo $site;?>" <?php if(!in_array($site, $enable_sites)):?>style="display:none"<?php endif;?>>
			<label for="<?php echo $this->get_field_id( $site ); ?>"><?php echo $site.' '.__('URL:', 'wt_admin')?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( $site ); ?>" name="<?php echo $this->get_field_name( $site ); ?>" type="text" value="<?php echo $$site; ?>" />
		</p>
		<?php endforeach;?>
		</div>

		<p><label for="<?php echo $this->get_field_id('custom_count'); ?>"><?php _e('How many custom icons to add?', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('custom_count'); ?>" class="social_icon_custom_count" name="<?php echo $this->get_field_name('custom_count'); ?>" type="text" value="<?php echo $custom_count; ?>" size="3" /></p>

		<div class="social_custom_icon_wrap_2">
		<?php for($i=1;$i<=10;$i++): $custom_name='custom_'.$i.'_name';$custom_url='custom_'.$i.'_url'; $custom_icon='custom_'.$i.'_icon'; ?>
			<div class="social_icon_custom_<?php echo $i;?>" <?php if($i>$custom_count):?>style="display:none"<?php endif;?>>
				<p><label for="<?php echo $this->get_field_id( $custom_name ); ?>"><?php printf(__('Custom %s Name:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_name ); ?>" name="<?php echo $this->get_field_name( $custom_name ); ?>" type="text" value="<?php echo $$custom_name; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $custom_url ); ?>"><?php printf(__('Custom %s URL:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_url ); ?>" name="<?php echo $this->get_field_name( $custom_url ); ?>" type="text" value="<?php echo $$custom_url; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $custom_icon ); ?>"><?php printf(__('Custom %s Icon:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $custom_icon ); ?>" name="<?php echo $this->get_field_name( $custom_icon ); ?>" type="text" value="<?php echo $$custom_icon; ?>" /></p>
			</div>

		<?php endfor;?>
		</div>


		
<?php
	}
}