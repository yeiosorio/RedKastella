<?php
/**
 * Flickr Widget Class
 */
class Wt_Widget_Flickr extends WP_Widget {

	function Wt_Widget_Flickr() {
		$widget_ops = array('classname' => 'widget_flickr', 'description' => __( 'Photos from a Flickr ID', 'wt_admin' ) );
		parent::__construct('flickr', THEME_SLUG.': '.__('Flickr', 'wt_admin'), $widget_ops);
	}
	function add_flickr_script(){
		wp_enqueue_script('jquery-flickr');
	}
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Photos on flickr', 'wt_front') : $instance['title'], $instance, $this->id_base);
		$type = empty( $instance['type'] ) ? 'flickr' : $instance['type'];
		$flickr_id = $instance['flickr_id'];
		$number = (int)$instance['count'];
		//$display = empty( $instance['display'] ) ? 'latest' : $instance['display'];
		
		$flick_id = rand(100,1000);	
		
		if ( is_active_widget(false, false, $this->id_base) && $type == "lightbox"){
			add_action( 'wp_print_scripts', array(&$this, 'add_flickr_script') );
		}
		if(empty($number)){
			$number = 6;
		} else if ($number < 1){
			$number = 1;
		}
		if ( !empty( $flickr_id ) ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title;
		?>
        
		<div id="wt_flickrWrap_<?php echo $flick_id; ?>" class="wt_flickrWrap">    
        <?php
		if ($type == "flickr") {
		?>  			           
			<script type="text/javascript">
			/* <![CDATA[ */	
			(function($){					
				jQuery(window).load(function() {
					$.getJSON("http://api.flickr.com/services/feeds/photos_public.gne?id=<?php echo $flickr_id; ?>&format=json&jsoncallback=?", 
					function(data){
						$.each(data.items, function(i,item){
							if(i < <?php echo $number; ?>){	
								var smallpic = item.media.m.replace('_m.jpg', '_s.jpg');							
								$("#wt_flickrWrap_<?php echo $flick_id; ?>").append("<div class=\"flickr_badge_image\"><a title='" + item.title + "' href='" + item.link + "' target='_blank'><img src='" + smallpic + "' /></a></div>");	

							}
						});
					});	
				});						
			})(jQuery);	
			/* ]]> */			
            </script>		
        <?php
		} else if ($type == "lightbox") {
		?>  			
			<script type="text/javascript">	
			/* <![CDATA[ */	
			(function($){					
				jQuery(window).load(function() {
					var thisFlickr = $("#wt_flickrWrap_<?php echo $flick_id; ?>");					
					thisFlickr.jflickrfeed({
						limit: <?php echo $number; ?>,
						qstrings: {
							id: '<?php echo $flickr_id; ?>'
						},
						itemTemplate: '<div class="flickr_badge_image"><a href="{{image_b}}" rel="lightbox[flickr]"><img src="{{image_s}}" alt="{{title}}" /></a></div>'
						}, function(data) {
						thisFlickr.find('a').prettyPhoto({theme:'pp_default',social_tools:false, deeplinking:false});
					});					
				});					
			})(jQuery);
			/* ]]> */		
            </script>			
        <?php
		}
		?> 	
		</div>
		<?php
			echo $after_widget;
		}
	}
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['type'] = strip_tags($new_instance['type']);
		$instance['flickr_id'] = strip_tags($new_instance['flickr_id']);
		$instance['count'] = (int) $new_instance['count'];
		//$instance['display'] = strip_tags($new_instance['display']);
		
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$type = isset($instance['type']) ? esc_attr($instance['type']) : 'flickr';
		$flickr_id = isset($instance['flickr_id']) ? esc_attr($instance['flickr_id']) : '';
		$number = isset($instance['count']) ? absint($instance['count']) : 3;
		//$display = isset( $instance['display'] ) ? $instance['display'] : 'latest';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('type'); ?>"><?php _e( 'Type:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('type'); ?>" id="<?php echo $this->get_field_id('type'); ?>" class="widefat">
				<option value="flickr"<?php selected($type,'flickr');?>>Flickr</option>
				<option value="lightbox"<?php selected($type,'lightbox');?>>Lightbox</option>
			</select>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('flickr_id'); ?>"><?php _e('Flickr ID (<a href="http://www.idgettr.com" target="_blank">idGettr</a>):', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('flickr_id'); ?>" name="<?php echo $this->get_field_name('flickr_id'); ?>" type="text" value="<?php echo $flickr_id; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('Number of photo to show:', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

        
<?php
	}
}