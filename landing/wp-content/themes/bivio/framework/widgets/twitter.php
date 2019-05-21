<?php
/**
 * Twitter Widget Class
 */
class Wt_Widget_Twitter extends WP_Widget {

	function Wt_Widget_Twitter() {
		$widget_ops = array('classname' => 'widget_twitter', 'description' => __( 'A list of twitter feeds', 'wt_admin' ) );
		parent::__construct('wt_twitter', THEME_SLUG.' - '.__('Twitter', 'wt_admin'), $widget_ops);
		
		if ( is_active_widget(false, false, $this->id_base) ){
			add_action( 'wp_print_scripts', array(&$this, 'add_tweet_script') );
		}
		
	}

	function add_tweet_script(){
		wp_enqueue_script('jquery-tweet');
	}
	
	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Tweets', 'wt_front') : $instance['title'], $instance, $this->id_base);
		$username= $instance['username'];
		
		$user_array = explode(',',$username);
		foreach($user_array as $key => $user){
			$user_array[$key] = '"'.$user.'"';
		}
		
		$query= empty($instance['query'])?'null':'"'.$instance['query'].'"';
		$avatar_size = (int)$instance['avatar_size'];
		if(empty($avatar_size)){
			$avatar_size = 'null';
		}
		$count = (int)$instance['count'];
		if($count < 1){
			$count = 1;
		}
		$enable_cycle_tweets = $instance['enable_cycle_tweets'] ? '1' : '0';
		$enable_cycle_buttons = $instance['enable_cycle_buttons'] ? '1' : '0';
		
		if($enable_cycle_tweets) { 
			$enable_cycle_tweets = ' cycle_tweets';
			wp_print_scripts('cycle');
			// Load script for IOS6 swipe bug
			global $wt_mobile_detect;
			if ( $wt_mobile_detect->isIOS() || $wt_mobile_detect->isiOS() ) {
				wp_enqueue_script('ios6-bug');
			}
		} else {
			$enable_cycle_tweets = '';
		}
		$buttons = ($enable_cycle_buttons) ? '<div class="cycle_nav"><a class="cycle_prev"><span>prev</span></a><a class="cycle_next"><span>next</span></a></div>' : '';
		
		echo '<div class="recentTweets'.$enable_cycle_tweets.'">';
		if ( !empty( $user_array )|| $query!="null" ) {
			echo $before_widget;
			if ( $title)
				echo $before_title . $title . $after_title . '
';		
		$id = rand(1,1000);
		?>
		<script type="text/javascript">
				jQuery(document).ready(function($) {
					 jQuery("#twitter_wrap_<?php echo $id;?>").tweet({
						username: [<?php echo implode(',',$user_array);?>],
						count: <?php echo $count;?>,
						query: <?php echo $query;?>,
						avatar_size: <?php echo $avatar_size;?>
					 });
				});
		</script>
        <?php echo $buttons;?>
		<div id="twitter_wrap_<?php echo $id;?>"<?php if($avatar_size != 'null'):?> class="with_avatar"<?php endif;?>></div>
		<?php
			echo $after_widget;
			echo '</div>';
		}
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['avatar_size'] = $new_instance['avatar_size']?(int) $new_instance['avatar_size']:'';
		$instance['count'] = (int) $new_instance['count'];
		$instance['query'] = strip_tags($new_instance['query']);
		$instance['enable_cycle_tweets'] = !empty($new_instance['enable_cycle_tweets']) ? 1 : 0;
		$instance['enable_cycle_buttons'] = !empty($new_instance['enable_cycle_buttons']) ? 1 : 0;
		return $instance;
	}

	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$username = isset($instance['username']) ? esc_attr($instance['username']) : '';
		$avatar_size = isset($instance['avatar_size']) ? absint($instance['avatar_size']) : '';
		$query = isset($instance['query']) ? esc_attr($instance['query']) : '';
		$count = isset($instance['count']) ? absint($instance['count']) : 3;
		$enable_cycle_tweets = isset( $instance['enable_cycle_tweets'] ) ? (bool) $instance['enable_cycle_tweets'] : false;
		$enable_cycle_buttons = isset( $instance['enable_cycle_buttons'] ) ? (bool) $instance['enable_cycle_buttons'] : false;
		$display = isset( $instance['display'] ) ? $instance['display'] : 'latest';
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Username:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" /></p>
		
		<p>
			<?php _e("Note: Use ',' separate multi user.<br> (e.g <code>user1,user2</code>)", 'wt_admin');?>
		</p>
		
		<p><label for="<?php echo $this->get_field_id('avatar_size'); ?>"><?php _e('height and width of avatar if displayed (48px max)(optional)', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('avatar_size'); ?>" name="<?php echo $this->get_field_name('avatar_size'); ?>" type="text" value="<?php echo $avatar_size; ?>" size="3" /></p>
		
		
		<p><label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('How many tweets to display?', 'wt_admin'); ?></label>
		<input id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" size="3" /></p>
		
		<p><label for="<?php echo $this->get_field_id('query'); ?>"><?php _e('Query (optional):', 'wt_admin'); ?></label>
		<textarea class="widefat" rows="4" cols="20" id="<?php echo $this->get_field_id('query'); ?>" name="<?php echo $this->get_field_name('query'); ?>"><?php echo $query; ?></textarea></p>  
		
		<p>
			<?php _e("Query uses <a href='http://apiwiki.twitter.com/Twitter-Search-API-Method%3A-search' target='_blank'>Twitter's Search API</a>, so you can display any tweets you like.", 'wt_admin');?>
		</p>
        
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('enable_cycle_tweets'); ?>" name="<?php echo $this->get_field_name('enable_cycle_tweets'); ?>"<?php checked( $enable_cycle_tweets ); ?> />
		<label for="<?php echo $this->get_field_id('enable_cycle_tweets'); ?>"><?php _e( 'Enable Cycle Tweets?', 'wt_admin' ); ?></label></p>
        
		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('enable_cycle_buttons'); ?>" name="<?php echo $this->get_field_name('enable_cycle_buttons'); ?>"<?php checked( $enable_cycle_buttons ); ?> />
		<label for="<?php echo $this->get_field_id('enable_cycle_buttons'); ?>"><?php _e( 'Enable Cycle Buttons?', 'wt_admin' ); ?></label></p>
<?php
	}
}