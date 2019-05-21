<?php
/**
 * Social Icon Widget Class
 */
class Wt_Widget_Social extends WP_Widget {

	var $sites = array(
		'Aim','Apple','Behance','Blogger','Delicious','Deviantart','Digg','Dribbble','Email','Ember','Facebook','Flickr','Forrst','Google','Googleplus','Html5','Lastfm','Linkedin','Metacafe','Netvibes','Paypal','Picassa','Pinterest','Reddit','Rss','Skype','Stumbleupon','Technorati','Tumblr','Twitter','Vimeo','Wordpress','Yahoo','Yelp','Youtube'
	);
	var $packages = array(
		'social' => array(
			'name'=>'Social',
			'path'=>'{:name}.png',
			'path_32'=>'{:name}_32.png',
			'class'=>'{:name}',
		),
	);
	
	
	function Wt_Widget_Social() {
		$widget_ops = array('classname' => 'widget_social', 'description' => __( 'A list of Social Icons', 'wt_admin') );
		parent::__construct('social', THEME_SLUG.' - '.__('Social Icon', 'wt_admin'), $widget_ops);
		
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
		$alt = isset($instance['alt'])?$instance['alt']:'';
		
		$package = $instance['package'];
		$custom_count = $instance['custom_count'];
		$tooltip = $instance['tooltip'] ? '1' : '0';
		$icons32 = $instance['icons32'] ? '1' : '0';
		$icons_type = $instance['icons_type'] ? $instance['icons_type'] :'round';
		$output = '';
		if( !empty($instance['enable_sites']) ){
			foreach($instance['enable_sites'] as $site){
				$path = str_replace('{:name}',strtolower($site),$this->packages[$package]['path']);
				$path_32 = str_replace('{:name}',strtolower($site),$this->packages[$package]['path_32']);
				$link = isset($instance[$site])?$instance[$site]:'#';
				$class = str_replace('{:name}',strtolower($site),$this->packages[$package]['class']);
				if ($icons32 == false) { 
					if(file_exists(THEME_DIR . '/img/social/'.$path)){
						$output .= '<a href="'.$link.'" class="'.$icons_type.' '.$class.'"';
						if ($tooltip == true) { 
                            $output .= ' data-toggle="tooltip"';
                        }
                        $output .= ' rel="nofollow" title="'.$alt.' '.$site.'" target="_blank"><span>'.$alt.' '.$site.'</span>';
                        $output .= '</a>';
					}
				}
				else {
					if(file_exists(THEME_DIR . '/img/social/'.$path_32)){
						$output .= '<a href="'.$link.'" class="'.$icons_type.' '.$class.'_32" title="'.$alt.' '.$site.'"';
						if ($tooltip == true) { 
							$output .= ' data-toggle="tooltip"';
						}
						$output .= '  rel="nofollow" target="_blank"><span>'.$alt.' '.$site.'</span> ';
						$output .= '</a>';
					}
				}
			}
		}
		if( $custom_count > 0){
			for($i=1; $i<= $custom_count; $i++){
				$name = isset($instance['custom_'.$i.'_name'])?$instance['custom_'.$i.'_name']:'';
				$icon = isset($instance['custom_'.$i.'_icon'])?$instance['custom_'.$i.'_icon']:'';
				$link = isset($instance['custom_'.$i.'_url'])?$instance['custom_'.$i.'_url']:'#';
				if(!empty($icon)){
					$output .= '<a href="'.$link.'" rel="nofollow" target="_blank"><img src="'.$icon.'" alt="'.$alt.' '.$name.'" title="'.$alt.' '.$name.'"/></a>';
				}
			}
		}
		if ( !empty( $output ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>
        <div class="wt_socialWrapper clearfix">
            <div class="wt_social_wrap<?php if ($icons32 == true) {echo ' icons_32';} ?>">
                <?php echo $output; ?>
            </div>
        </div>
		<?php
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['alt'] = strip_tags($new_instance['alt']);
		$instance['tooltip'] = !empty($new_instance['tooltip']) ? 1 : 0;
		$instance['icons32'] = !empty($new_instance['icons32']) ? 1 : 0;
		$instance['icons_type'] = $new_instance['icons_type'];
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
		$alt = isset($instance['alt']) ? esc_attr($instance['alt']) : 'Follow Us on';
		$tooltip = isset( $instance['tooltip'] ) ? (bool) $instance['tooltip'] : false;
		$icons32 = isset( $instance['icons32'] ) ? (bool) $instance['icons32'] : false;
		$icons_type = isset( $instance['icons_type'] ) ? $instance['icons_type'] : 'round';
		$package = isset($instance['package']) ? $instance['package'] : '';
		$enable_sites = isset($instance['enable_sites']) ? $instance['enable_sites'] : array();
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
		<p><label for="<?php echo $this->get_field_id('alt'); ?>"><?php _e('Icon Alt Title:', 'wt_admin'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('alt'); ?>" name="<?php echo $this->get_field_name('alt'); ?>" type="text" value="<?php echo $alt; ?>" /></p>
        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('tooltip'); ?>" name="<?php echo $this->get_field_name('tooltip'); ?>"<?php checked( $tooltip ); ?> />
		<label for="<?php echo $this->get_field_id('tooltip'); ?>"><?php _e( 'Use Tooltip?', 'wt_admin' ); ?></label></p>
        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('icons32'); ?>" name="<?php echo $this->get_field_name('icons32'); ?>"<?php checked( $icons32 ); ?> />
		<label for="<?php echo $this->get_field_id('icons32'); ?>"><?php _e( 'Use 32px icons?', 'wt_admin' ); ?></label></p>
        <p>
			<label for="<?php echo $this->get_field_id('display_extra_type'); ?>"><?php _e( 'Choose icon style:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('icons_type'); ?>" id="<?php echo $this->get_field_id('icons_type'); ?>" class="widefat">
				<option value="round"<?php selected($icons_type,'round');?>><?php _e( 'Round', 'wt_admin' ); ?></option>
				<option value="small_round"<?php selected($icons_type,'small_round');?>><?php _e( 'Small Round', 'wt_admin' ); ?></option>
				<option value="square"<?php selected($icons_type,'square');?>><?php _e( 'Square', 'wt_admin' ); ?></option>
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
		<div class="social_icon_wrap">
		<?php foreach($this->sites as $site):?>
		<p class="social_icon_<?php echo $site;?>" <?php if(!in_array($site, $enable_sites)):?>style="display:none"<?php endif;?>>
			<label for="<?php echo $this->get_field_id( $site ); ?>"><?php echo $site.' '.__('URL:', 'wt_admin')?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( $site ); ?>" name="<?php echo $this->get_field_name( $site ); ?>" type="text" value="<?php echo $$site; ?>" />
		</p>
		<?php endforeach;?>
		</div>

		<p><label for="<?php echo $this->get_field_id('custom_count'); ?>"><?php _e('How many custom icons to add?', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('custom_count'); ?>" class="social_icon_custom_count" name="<?php echo $this->get_field_name('custom_count'); ?>" type="text" value="<?php echo $custom_count; ?>" size="3" /></p>

		<div class="social_custom_icon_wrap">
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