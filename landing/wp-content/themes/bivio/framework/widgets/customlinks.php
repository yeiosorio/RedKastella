<?php
/**
 * Custom Links Widget Class
 */
class Wt_Widget_Custom_Links extends WP_Widget {

	function Wt_Widget_Custom_Links() {
		$widget_ops = array('classname' => 'widget_custom_links', 'description' => __( 'A list of custom links with images', 'wt_admin' ) );
		parent::__construct('custom_links', THEME_SLUG.' - '.__('Custom Links', 'wt_admin'), $widget_ops);
		
		if ('widgets.php' == basename($_SERVER['PHP_SELF'])) {
			add_action( 'admin_enqueue_scripts', array(&$this, 'add_admin_script') );
		}
	}
	
	function add_admin_script(){
		wp_enqueue_script( 'customlinks-widget', THEME_ADMIN_ASSETS_URI . '/js/customlinksWidget.js', array('jquery'));
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Links', 'wt_front') : $instance['title'], $instance, $this->id_base);

		$count = (int)$instance['count'];
		$id = rand(1,1000);
		
		$output = '';
		if( $count > 0){
			for($i=1; $i<= $count; $i++){
				$image = isset($instance['cl_'.$i.'_image'])?$instance['cl_'.$i.'_image']:'';
				$linktitle = isset($instance['cl_'.$i.'_linktitle'])?$instance['cl_'.$i.'_linktitle']:'';
				$link = isset($instance['cl_'.$i.'_link'])?$instance['cl_'.$i.'_link']:'';
				$target = isset($instance['cl_'.$i.'_target'])?$instance['cl_'.$i.'_target']:'_blank';
				$output .= '<li class="'.$linktitle.'"><a href="'.$link.'" target="'.$target.'" title="'.$linktitle.'">';
				if(empty($image)){
					$output .= '<i class="fa fa-link"></i>';
				} else {
					$output .= '<img src="'.$image.'" alt="'.$linktitle.'"/>';
				}
				$output .= '<span>'.$linktitle.'</span></a></li>';
			}
		}
		
		if ( !empty( $output ) ) {
			echo '<div id="wt_custom_links-'.$id.'" class="widget widget_custom_links">';
			if ( $title)
				echo $before_title . $title . $after_title;
				
            echo '<ul class="wt_custom_links">';
			echo $output;
			echo '</ul></div>';
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = (int) $new_instance['count'];
		for($i=1;$i<=$instance['count'];$i++){
			$instance['cl_'.$i.'_image'] = strip_tags($new_instance['cl_'.$i.'_image']);
			$instance['cl_'.$i.'_linktitle'] = strip_tags($new_instance['cl_'.$i.'_linktitle']);
			$instance['cl_'.$i.'_link'] = strip_tags($new_instance['cl_'.$i.'_link']);
			$instance['cl_'.$i.'_target'] = strip_tags($new_instance['cl_'.$i.'_target']);
		}
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 4;
		for($i=1;$i<=20;$i++){
			$cl_image = 'cl_'.$i.'_image';
			$$cl_image = isset($instance[$cl_image]) ? $instance[$cl_image] : '';
			$cl_linktitle = 'cl_'.$i.'_linktitle';
			$$cl_linktitle = isset($instance[$cl_linktitle]) ? $instance[$cl_linktitle] : '';
			$cl_link = 'cl_'.$i.'_link';
			$$cl_link = isset($instance[$cl_link]) ? $instance[$cl_link] : '';
			$cl_target = 'cl_'.$i.'_target';
			$$cl_target = isset($instance[$cl_target]) ? $instance[$cl_target] : '';
		}
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many custom links to display?', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" class="customlinks_count" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>

		<p>
			<em><?php _e("Note: Please input FULL URL <br/>(e.g. <code>http://www.example.com</code>)", 'wt_admin');?></em>
		</p>

		<div class="customlinks_wrap">
		<?php for($i=1;$i<=20;$i++): $cl_image = 'cl_'.$i.'_image';$cl_linktitle = 'cl_'.$i.'_linktitle';$cl_link = 'cl_'.$i.'_link';$cl_target = 'cl_'.$i.'_target'; ?>
			<div class="customlinks_<?php echo $i;?>" <?php if($i>$count):?>style="display:none"<?php endif;?>>
				<p><label for="<?php echo $this->get_field_id( $cl_image ); ?>"><?php printf(__('#%s Image URL:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $cl_image ); ?>" name="<?php echo $this->get_field_name( $cl_image ); ?>" type="text" value="<?php echo $$cl_image; ?>" /></p>
                <p><label for="<?php echo $this->get_field_id( $cl_linktitle ); ?>"><?php printf(__('#%s Link Title:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $cl_linktitle ); ?>" name="<?php echo $this->get_field_name( $cl_linktitle ); ?>" type="text" value="<?php echo $$cl_linktitle; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $cl_link ); ?>"><?php printf(__('#%s Link:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $cl_link ); ?>" name="<?php echo $this->get_field_name( $cl_link ); ?>" type="text" value="<?php echo $$cl_link; ?>" /></p>
				<p>
					<label for="<?php echo $this->get_field_id( $cl_target ); ?>"><?php printf(__('#%s Link target:', 'wt_admin'),$i);?></label>
					<select name="<?php echo $this->get_field_name( $cl_target ); ?>" id="<?php echo $this->get_field_id( $cl_target ); ?>" class="widefat">
						<option value="_blank"<?php selected($$cl_target,'_blank');?>><?php _e( 'Load in a new window', 'wt_admin' ); ?></option>
						<option value="_self"<?php selected($$cl_target,'_self');?>><?php _e( 'Load in the same frame as it was clicked', 'wt_admin' ); ?></option>
						<option value="_parent"<?php selected($$cl_target,'_parent');?>><?php _e( 'Load in the parent frameset', 'wt_admin' ); ?></option>
						<option value="_top"<?php selected($$cl_target,'_top');?>><?php _e( 'Load in the full body of the window', 'wt_admin' ); ?></option>
					</select>
				</p>
			</div>
		<?php endfor;?>
		</div>
<?php
	}
}