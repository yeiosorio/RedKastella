<?php
/**
 * Popular_Posts Widget Class
 */
class Wt_Widget_Tabs_Posts extends WP_Widget {

	function Wt_Widget_Tabs_Posts() {
		$widget_ops = array('classname' => 'widget_tabs_posts', 'description' => __( "Popular posts, recent posts and comments from your site", 'wt_admin') );
		parent::__construct('tabs_posts', THEME_SLUG.' - '.__('Tabs Posts', 'wt_admin'), $widget_ops);
		$this->alt_option_name = 'widget_tabs_posts';

		add_action( 'save_post', array(&$this, 'flush_widget_cache') );
		add_action( 'deleted_post', array(&$this, 'flush_widget_cache') );
		add_action( 'switch_theme', array(&$this, 'flush_widget_cache') );
	}

	function widget($args, $instance) {
		$cache = wp_cache_get('theme_widget_tabs_posts', 'widget');

		if ( !is_array($cache) )
			$cache = array();

		if ( isset($cache[$args['widget_id']]) ) {
			echo $cache[$args['widget_id']];
			return;
		}

		ob_start();
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? "" : $instance['title'], $instance, $this->id_base);
		if ( !$number = (int) $instance['number'] )
			$number = 10;
		else if ( $number < 1 )
			$number = 1;
		else if ( $number > 15 )
			$number = 15;
		
		if ( !$desc_length = (int) $instance['desc_length'] )
			$desc_length = 80;
		else if ( $desc_length < 1 )
			$desc_length = 1;
		
		$disable_thumbnail = $instance['disable_thumbnail'] ? '1' : '0';
		$show_popular = $instance['show_popular'] ? '1' : '0';
		$show_recent= $instance['show_recent'] ? '1' : '0';
		$show_comments = $instance['show_comments'] ? '1' : '0';
		$display_extra_type = $instance['display_extra_type'] ? $instance['display_extra_type'] :'time';
		
		if(!empty($instance['cat'])){
			$query['cat'] = implode(',', $instance['cat']);
		}

		
?>
		<section class="wt_tabs_wrap relatedPopularPosts">
        <ul class="wt_tabs"> 
         	<?php if($show_popular): ?>
                <li><a href="#tab-popular" class="current"><?php _e('Popular','wt_front') ?></a></li>
            <?php endif; ?>
            <?php if($show_recent): ?>
                <li><a href="#tab-recent"><?php _e('Recent','wt_front') ?></a></li> 
            <?php endif; ?>
            <?php if($show_comments): ?>      
                <li><a href="#tab-comments"><i class="fa fa-comments"></i></a></li>  
            <?php endif; ?>        
        </ul>
        <div class="panes blogPosts">
        	<?php if($show_popular): ?>
            <div id="tab-popular" class="pane" style="display:block">
		<?php $query = array('showposts' => $number, 'nopaging' => 0, 'orderby'=> 'comment_count', 'post_status' => 'publish', 'ignore_sticky_posts' => 1);
		$r = new WP_Query($query);
		if ($r->have_posts()) :
		 ?>
		 <?php if($display_extra_type == 'none'):?>
       		 <div class="postThumbs">
        <?php else: // end thumbsOnly ?> 
        <ul class="wt_postList">
	    <?php endif;?>	
<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php if($display_extra_type != 'none'):?><li><?php endif;?>
<?php if(!$disable_thumbnail):?>
				<a class="thumb" href="<?php echo get_permalink() ?>" title="<?php the_title();?>">
<?php if (has_post_thumbnail() ): ?>
					<?php the_post_thumbnail('thumb', array(55,55),array('title'=>get_the_title(),'alt'=>get_the_title())); ?>	
<?php else:?>
					<img src="<?php echo THEME_IMAGES;?>/widget_posts_thumbnail.png" width="55" height="55" title="<?php the_title();?>" alt="<?php the_title();?>"/>
<?php endif;//end has_post_thumbnail ?>
				</a>
<?php endif;//disable_thumbnail ?>
<?php if($display_extra_type != 'none'):?>
				<div class="wt_postInfo">
					<a href="<?php the_permalink() ?>" class="postInfoTitle" rel="bookmark" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
<?php if($display_extra_type == 'time'):?>
					<span class="date"><?php echo get_the_date(); ?></span>
<?php elseif($display_extra_type == 'description'):?>
					<p><?php echo wp_html_excerpt(get_the_excerpt(),$desc_length);?></p>
<?php elseif($display_extra_type == 'comments'):?>
					<span class="comments"><?php echo comments_popup_link(__('No response ','wt_front'), __('1 Comment','wt_front'), __('% Comments','wt_front'),''); ?></span>
<?php endif;//end display extra type ?>
				</div>
                <div class="wt_clearboth"></div>
<?php endif; //end display post information ?>	
			<?php if($display_extra_type != 'none'):?></li><?php endif;?>
<?php endwhile; ?>
		<?php if($display_extra_type != 'none'):?>
        </ul>
		<?php else: // end postThumbs ?> 
        </div> 
        	<div class="wt_clearboth"></div>
		<?php endif; ?>
	    	<?php endif;?>	
           </div>
            <?php endif;?>
           <?php // display show_recent information
           if($show_recent): ?>
           <?php if(!$show_popular){ ?>
           <div id="tab-recent" class="pane" style="display:block">
           <?php }else{ ?>	
           <div id="tab-recent" class="pane">
           <?php } ?>	
			<?php  $query = array('showposts' => $number, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1);
			$r = new WP_Query($query);
			if ($r->have_posts()) :
    		if($display_extra_type == 'none'):?>
            <div class="postThumbs">
            <?php else: // end thumbsOnly ?> 
            <ul class="wt_postList">
            <?php endif;?>	
<?php  while ($r->have_posts()) : $r->the_post(); ?>
			<?php if($display_extra_type != 'none'):?><li><?php endif;?>
<?php if(!$disable_thumbnail):?>
				<a class="thumb" href="<?php echo get_permalink() ?>" title="<?php the_title();?>">
<?php if (has_post_thumbnail() ): ?>
		<?php the_post_thumbnail('thumb', array(55,55),array('title'=>get_the_title(),'alt'=>get_the_title())); ?>	
<?php else:?>
		<img src="<?php echo THEME_IMAGES;?>/widget_posts_thumbnail.png" width="55" height="55" title="<?php the_title();?>" alt="<?php the_title();?>"/>
<?php endif;//end has_post_thumbnail ?>
				</a>
<?php endif;//disable_thumbnail ?>
<?php if($display_extra_type != 'none'):?>
				<div class="wt_postInfo">
					<a href="<?php the_permalink() ?>" class="postInfoTitle" rel="bookmark" title="<?php echo esc_attr(get_the_title() ? get_the_title() : get_the_ID()); ?>"><?php if ( get_the_title() ) the_title(); else the_ID(); ?></a>
<?php if($display_extra_type == 'time'):?>
					<span class="date"><?php echo get_the_date(); ?></span>
<?php elseif($display_extra_type == 'description'):?>
					<p><?php echo wp_html_excerpt(get_the_excerpt(),$desc_length);?></p>
<?php elseif($display_extra_type == 'comments'):?>
					<span class="comments"><?php echo comments_popup_link(__('No response ','wt_front'), __('1 Comment','wt_front'), __('% Comments','wt_front'),''); ?></span>
<?php endif;//end display extra type ?>
				</div>
                <div class="wt_clearboth"></div>
<?php endif; //end display post information ?>	
			<?php if($display_extra_type != 'none'):?></li><?php endif;?>
<?php endwhile; ?>
		<?php if($display_extra_type != 'none'):?>
        </ul>
		<?php else: // end postThumbs ?> 
        </div> 
	    <?php endif;?>
        <div class="wt_clearboth"></div>
        

	    	<?php endif;?>
            </div>
	    	<?php endif;
			// end display show_recent information
			?>
            <?php if($show_comments): ?>
           <div id="tab-comments" class="pane">
    		<ul class="wt_postList">
				<?php
                global $wpdb;
                $recent_comments = "SELECT DISTINCT ID, post_title, post_password, comment_ID, comment_post_ID, comment_author, comment_author_email, comment_date_gmt, comment_approved, comment_type, comment_author_url, SUBSTRING(comment_content,1,110) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID = $wpdb->posts.ID) WHERE comment_approved = '1' AND comment_type = '' AND post_password = '' ORDER BY comment_date_gmt DESC LIMIT $number";
                $the_comments = $wpdb->get_results($recent_comments);
                foreach($the_comments as $comment) { ?>
                <li>
                    <a class="thumb" href="<?php echo get_permalink() ?>" title="<?php the_title();?>">
						<?php echo get_avatar($comment, '52'); ?>
                    </a>
                    <div class="wt_postInfo">
                        <p class="wrap"><small class="desc"><em><?php echo strip_tags($comment->comment_author); ?></em><?php _e('says:', 'wt_front'); ?>:</small></p>
                        <div class="meta">
                            <a class="comment-text-side" href="<?php echo get_permalink($comment->ID); ?>#comment-<?php echo $comment->comment_ID; ?>" title="<?php echo strip_tags($comment->comment_author); ?> on <?php echo $comment->post_title; ?>"><?php echo string_limit_words(strip_tags($comment->com_excerpt), 8); ?>...</a>
                        </div>
                    </div>
                </li>
                <?php } ?>
            </ul>
            </div>
	    	<?php endif;?>
        </div>
        </section>

<?php
		// Reset the global $the_post as this query will have stomped on it
		wp_reset_query();


		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set('theme_widget_tabs_posts', $cache, 'widget');
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = (int) $new_instance['number'];
		$instance['desc_length'] = (int) $new_instance['desc_length'];
		$instance['disable_thumbnail'] = !empty($new_instance['disable_thumbnail']) ? 1 : 0;
		$instance['show_popular'] = !empty($new_instance['show_popular']) ? 1 : 0;
		$instance['show_recent'] = !empty($new_instance['show_recent']) ? 1 : 0;
		$instance['show_comments'] = !empty($new_instance['show_comments']) ? 1 : 0;
		$instance['display_extra_type'] = $new_instance['display_extra_type'];
		$instance['cat'] = $new_instance['cat'];
		
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['theme_widget_tabs_posts']) )
			delete_option('theme_widget_tabs_posts');

		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete('theme_widget_tabs_posts', 'widget');
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$show_popular = isset( $instance['show_popular'] ) ? (bool) $instance['show_popular'] : true;
		$show_recent = isset( $instance['show_recent'] ) ? (bool) $instance['show_recent'] : true;
		$show_comments = isset( $instance['show_comments'] ) ? (bool) $instance['show_comments'] : true;
		$disable_thumbnail = isset( $instance['disable_thumbnail'] ) ? (bool) $instance['disable_thumbnail'] : false;
		$display_extra_type = isset( $instance['display_extra_type'] ) ? $instance['display_extra_type'] : 'time';
		$cat = isset($instance['cat']) ? $instance['cat'] : array();
		
		if ( !isset($instance['number']) || !$number = (int) $instance['number'] )
			$number = 3;

		if ( !isset($instance['desc_length']) || !$desc_length = (int) $instance['desc_length'] )
			$desc_length = 80;

		$categories = get_categories('orderby=name&hide_empty=0');

?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:','wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('disable_thumbnail'); ?>" name="<?php echo $this->get_field_name('disable_thumbnail'); ?>"<?php checked( $disable_thumbnail ); ?> />
		<label for="<?php echo $this->get_field_id('disable_thumbnail'); ?>"><?php _e( 'Disable Post Thumbnail?' , 'wt_admin'); ?></label></p>
		
		<p>
			<label for="<?php echo $this->get_field_id('display_extra_type'); ?>"><?php _e( 'Display Extra infomation type:', 'wt_admin' ); ?></label>
			<select name="<?php echo $this->get_field_name('display_extra_type'); ?>" id="<?php echo $this->get_field_id('display_extra_type'); ?>" class="widefat">
				<option value="time"<?php selected($display_extra_type,'time');?>><?php _e( 'Time', 'wt_admin' ); ?></option>
				<option value="description"<?php selected($display_extra_type,'description');?>><?php _e( 'Description', 'wt_admin' ); ?></option>
				<option value="comments"<?php selected($display_extra_type,'comments');?>><?php _e( 'Comments', 'wt_admin' ); ?></option>
				<option value="none"<?php selected($display_extra_type,'none');?>><?php _e( 'None', 'wt_admin' ); ?></option>
			</select>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('desc_length'); ?>"><?php _e('Length of Description to show:', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('desc_length'); ?>" name="<?php echo $this->get_field_name('desc_length'); ?>" type="text" value="<?php echo $desc_length; ?>" size="3" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('cat'); ?>"><?php _e( 'Categorys:' , 'wt_admin'); ?></label>
			<select style="height:5.5em" name="<?php echo $this->get_field_name('cat'); ?>[]" id="<?php echo $this->get_field_id('cat'); ?>" class="widefat" multiple="multiple">
				<?php foreach($categories as $category):?>
				<option value="<?php echo $category->term_id;?>"<?php echo in_array($category->term_id, $cat)? ' selected="selected"':'';?>><?php echo $category->name;?></option>
				<?php endforeach;?>
			</select>
		</p>
        <p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_popular'); ?>" name="<?php echo $this->get_field_name('show_popular'); ?>"<?php checked( $show_popular ); ?> />
		<label for="<?php echo $this->get_field_id('show_popular'); ?>"><?php _e( 'Show popular posts?' , 'wt_admin'); ?></label></p>

<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_recent'); ?>" name="<?php echo $this->get_field_name('show_recent'); ?>"<?php checked( $show_recent ); ?> />
		<label for="<?php echo $this->get_field_id('show_recent'); ?>"><?php _e( 'Show recent posts?' , 'wt_admin'); ?></label></p>

<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('show_comments'); ?>" name="<?php echo $this->get_field_name('show_comments'); ?>"<?php checked( $show_comments ); ?> />
		<label for="<?php echo $this->get_field_id('show_comments'); ?>"><?php _e( 'Show comments?' , 'wt_admin'); ?></label></p>

<?php
	}
}