<?php
/**
 * Advertisement 125 Widget Class
 */
class Wt_Widget_Advertisement_125 extends WP_Widget {

	function Wt_Widget_Advertisement_125() {
		$widget_ops = array('classname' => 'widget_advertisement_125', 'description' => __( 'A list of advertisement', 'wt_admin' ) );
		parent::__construct('advertisement_125', THEME_SLUG.' - '.__('Advertisement', 'wt_admin').' 125', $widget_ops);
		
		if ('widgets.php' == basename($_SERVER['PHP_SELF'])) {
			add_action( 'admin_enqueue_scripts', array(&$this, 'add_admin_script') );
		}
	}
	
	function add_admin_script(){
		wp_enqueue_script( 'advertisement-widget', THEME_ADMIN_ASSETS_URI . '/js/advertisementWidget.js', array('jquery'));
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Sponsored Ads', 'wt_front') : $instance['title'], $instance, $this->id_base);

		$count = (int)$instance['count'];

		$output = '';
		if( $count > 0){
			for($i=1; $i<= $count; $i++){
				$image = isset($instance['ad_'.$i.'_image'])?$instance['ad_'.$i.'_image']:'';
				$link = isset($instance['ad_'.$i.'_link'])?$instance['ad_'.$i.'_link']:'';
				$target = isset($instance['ad_'.$i.'_target'])?$instance['ad_'.$i.'_target']:'_blank';
				$image = THEME_IMAGES.'/placeholderAd.png';
				$output .= '<a href="'.$link.'" rel="nofollow" target="'.$target.'" title="Advertisment"><img src="'.$image.'" alt="Advertisement"/></a>';
			}
		}
		
		if ( !empty( $output ) ) {
			echo $before_widget;
			
			if ( $title)
				echo $before_title . $title . $after_title . '
';	

            echo '<div class="wt_adsWrap">';
			echo $output;			
			echo '</div>';
			
			echo $after_widget;
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['count'] = (int) $new_instance['count'];
		for($i=1;$i<=$instance['count'];$i++){
			$instance['ad_'.$i.'_image'] = strip_tags($new_instance['ad_'.$i.'_image']);
			$instance['ad_'.$i.'_link'] = strip_tags($new_instance['ad_'.$i.'_link']);
			$instance['ad_'.$i.'_target'] = strip_tags($new_instance['ad_'.$i.'_target']);
		}
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 4;
		for($i=1;$i<=10;$i++){
			$ad_image = 'ad_'.$i.'_image';
			$$ad_image = isset($instance[$ad_image]) ? $instance[$ad_image] : '';
			$ad_link = 'ad_'.$i.'_link';
			$$ad_link = isset($instance[$ad_link]) ? $instance[$ad_link] : '';
			$ad_target = 'ad_'.$i.'_target';
			$$ad_target = isset($instance[$ad_target]) ? $instance[$ad_target] : '';
		}
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many advertisement to display?', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" class="advertisement_count" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>

		<p>
			<em><?php _e("Note: Please input FULL URL <br/>(e.g. <code>http://www.example.com</code>)", 'wt_admin');?></em>
		</p>

		<div class="advertisement_wrap">
		<?php for($i=1;$i<=10;$i++): $ad_image = 'ad_'.$i.'_image';$ad_link = 'ad_'.$i.'_link';$ad_target = 'ad_'.$i.'_target'; ?>
			<div class="advertisement_<?php echo $i;?>" <?php if($i>$count):?>style="display:none"<?php endif;?>>
				<p><label for="<?php echo $this->get_field_id( $ad_image ); ?>"><?php printf(__('#%s Image URL:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $ad_image ); ?>" name="<?php echo $this->get_field_name( $ad_image ); ?>" type="text" value="<?php echo $$ad_image; ?>" /></p>
				<p><label for="<?php echo $this->get_field_id( $ad_link ); ?>"><?php printf(__('#%s Link:', 'wt_admin'),$i);?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( $ad_link ); ?>" name="<?php echo $this->get_field_name( $ad_link ); ?>" type="text" value="<?php echo $$ad_link; ?>" /></p>
				<p>
					<label for="<?php echo $this->get_field_id( $ad_target ); ?>"><?php printf(__('#%s Link target:', 'wt_admin'),$i);?></label>
					<select name="<?php echo $this->get_field_name( $ad_target ); ?>" id="<?php echo $this->get_field_id( $ad_target ); ?>" class="widefat">
						<option value="_blank"<?php selected($$ad_target,'_blank');?>><?php _e( 'Load in a new window', 'wt_admin' ); ?></option>
						<option value="_self"<?php selected($$ad_target,'_self');?>><?php _e( 'Load in the same frame as it was clicked', 'wt_admin' ); ?></option>
						<option value="_parent"<?php selected($$ad_target,'_parent');?>><?php _e( 'Load in the parent frameset', 'wt_admin' ); ?></option>
						<option value="_top"<?php selected($$ad_target,'_top');?>><?php _e( 'Load in the full body of the window', 'wt_admin' ); ?></option>
					</select>
				</p>
			</div>
		<?php endfor;?>
		</div>
<?php
	}
}