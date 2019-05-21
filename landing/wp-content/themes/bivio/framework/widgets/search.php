<?php
/**
 * Search Widget Class
 */
class Wt_Widget_Search extends WP_Widget {

	function Wt_Widget_Search() {
		$widget_ops = array('classname' => 'widget_search', 'description' => __( 'A different search form template.', 'wt_admin') );
		parent::__construct('wt_search', THEME_SLUG.' - '.__('Search', 'wt_admin'), $widget_ops);
	}
	
	function add_search_script(){
		wp_enqueue_script('jquery-uisearch');
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );		
		if ( is_active_widget(false, false, $this->id_base)){
			add_action( 'wp_print_scripts', array(&$this, 'add_search_script') );
		}
		?> 	
        	
		<div id="sb-search" class="sb-search widget widget_search">
		<?php
		if ( $title)
			echo $before_title . $title . $after_title;		
		?>        
        <form method="get" action="<?php echo home_url(); ?>/" class="wt_search_form" role="search">
            <input type="search" size="15" placeholder="<?php _e('Enter Search keywords...','wt_front');?>" class="wt_search_field sb-search-input" name="s" value=""/>
            <input type="submit" value="&#xf002;" class="wt_search_go sb-search-submit" />
        </form>
		</div>
        
		<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
	?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
<?php
	}
}
