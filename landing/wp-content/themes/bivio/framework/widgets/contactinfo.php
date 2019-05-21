<?php
/**
 * Contact Info Widget Class
 */
class Wt_Widget_Contact_Info extends WP_Widget {

	function Wt_Widget_Contact_Info() {
		$widget_ops = array('classname' => 'widget_contact_info', 'description' => __( 'A list of contact informations', 'wt_admin') );
		parent::__construct('contact_info',THEME_SLUG.' - '. __('Contact Info', 'wt_admin'), $widget_ops);
		
	}
	
	function widget( $args, $instance ) {		
		extract( $args );
		$title = apply_filters('widget_title', empty($instance['title']) ? __('', 'wt_front') : $instance['title'], $instance, $this->id_base);
		
		$name = $instance['name'];
		$email= $instance['email'];
		$email = str_replace('@','(at)',$email);
		$link = $instance['link'];
		$twitter = $instance['twitter'];
		$phone = $instance['phone'];
		$cellphone = $instance['cellphone'];
		$address = $instance['address'];
		$city = $instance['city'];
		$state = $instance['state'];
		$zip = $instance['zip'];
		$text = $instance['text'];		
						
		echo $before_widget;
		if ( $title)
			echo $before_title . $title . $after_title;
		
		?>
        <div class="wt_contactInfo">
			<div class="wt_contactInfoWrap">   
			<?php if(!empty($text)):?><p class="wt_contactText"><?php echo $text;?></p><?php endif;?>	
			<?php if(!empty($name)):?><p class="wt_contactName"><i class="fa fa-user"></i><?php echo $name;?></p><?php endif;?>
            <?php if(!empty($address)):?><p class="wt_contactAddress"><i class="fa fa-map-marker"></i><?php echo $address; ?><?php if(!empty($city)||!empty($zip)):?>, <?php endif;?><?php endif;?>
			<?php if(!empty($city)||!empty($zip)):?>
           		<?php if(empty($address)):?><p class="wt_contactAddress"><i class="fa fa-map-marker"></i><?php endif;?>
				<?php if(!empty($city)):?><?php echo $city;?>, <?php endif;?>
				<?php if(!empty($zip)):?><?php echo $zip;?>, <?php endif;?>
				<?php if(!empty($state)):?><?php echo $state;?><?php endif;?>
			</p><?php endif;?>
			<?php if(!empty($phone)):?><p class="wt_contactPhone"><i class="fa fa-phone"></i><?php echo $phone;?></p><?php endif;?>
			<?php if(!empty($cellphone)):?><p class="wt_contactCellPhone"><i class="fa fa-phone-square"></i><?php echo $cellphone;?></p><?php endif;?>
			<?php if(!empty($email)):?><p class="wt_contactMail"><i class="fa fa-envelope"></i><a href="mailto:<?php echo $email;?>" class="nospam"><?php echo $email;?></a></p><?php endif;?>
			<?php if(!empty($link)):?><p class="wt_contactLink"><i class="fa fa-link"></i><a href="<?php echo $link;?>" target="_blank"><?php echo $link;?></a></p><?php endif;?>
			<?php if(!empty($twitter)):?><p class="wt_contactTwitter"><i class="fa fa-twitter"></i><a href="https://twitter.com/<?php echo $twitter;?>" target="_blank"><?php echo $twitter;?></a></p><?php endif;?>
					
			</div>
        </div>
		<?php
		echo $after_widget;

	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['name'] = strip_tags($new_instance['name']);
		$instance['email'] = strip_tags($new_instance['email']);
		$instance['link'] = strip_tags($new_instance['link']);
		$instance['twitter'] = strip_tags($new_instance['twitter']);
		$instance['phone'] = strip_tags($new_instance['phone']);
		$instance['cellphone'] = strip_tags($new_instance['cellphone']);
		$instance['address'] = strip_tags($new_instance['address']);
		$instance['city'] = strip_tags($new_instance['city']);
		$instance['state'] = strip_tags($new_instance['state']);
		$instance['zip'] = strip_tags($new_instance['zip']);
		$instance['text'] = strip_tags($new_instance['text']);
		

		return $instance;
	}

	function form( $instance ) {
		//Defaults		
		$title = isset($instance['title']) ? esc_attr($instance['title']) : '';
		$name = isset($instance['name']) ? esc_attr($instance['name']) : '';
		$email = isset($instance['email']) ? esc_attr($instance['email']) : '';
		$link = isset($instance['link']) ? esc_attr($instance['link']) : '';
		$twitter = isset($instance['twitter']) ? esc_attr($instance['twitter']) : '';
		$phone = isset($instance['phone']) ? esc_attr($instance['phone']) : '';
		$cellphone = isset($instance['cellphone']) ? esc_attr($instance['cellphone']) : '';
		$address = isset($instance['address']) ? esc_attr($instance['address']) : '';
		$city = isset($instance['city']) ? esc_attr($instance['city']) : '';
		$state = isset($instance['state']) ? esc_attr($instance['state']) : '';
		$zip = isset($instance['zip']) ? esc_attr($instance['zip']) : '';
		$text = isset($instance['text']) ? esc_attr($instance['text']) : '';
	?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo $name; ?>" /></p>
		
        <p><label for="<?php echo $this->get_field_id('email'); ?>"><?php _e('Email:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('email'); ?>" name="<?php echo $this->get_field_name('email'); ?>" type="text" value="<?php echo $email; ?>" /></p>     
        
		<p><label for="<?php echo $this->get_field_id('link'); ?>"><?php _e('Link:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('link'); ?>" name="<?php echo $this->get_field_name('link'); ?>" type="text" value="<?php echo $link; ?>" /></p>   
        
		<p><label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" /></p>   
        
		<p><label for="<?php echo $this->get_field_id('phone'); ?>"><?php _e('Phone:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('phone'); ?>" name="<?php echo $this->get_field_name('phone'); ?>" type="text" value="<?php echo $phone; ?>" /></p>
        
		<p><label for="<?php echo $this->get_field_id('cellphone'); ?>"><?php _e('Cell phone:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('cellphone'); ?>" name="<?php echo $this->get_field_name('cellphone'); ?>" type="text" value="<?php echo $cellphone; ?>" /></p>
				
		<p><label for="<?php echo $this->get_field_id('address'); ?>"><?php _e('Address:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('address'); ?>" name="<?php echo $this->get_field_name('address'); ?>" type="text" value="<?php echo $address; ?>" /></p>
		
		<p><label for="<?php echo $this->get_field_id('city'); ?>"><?php _e('City:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('city'); ?>" name="<?php echo $this->get_field_name('city'); ?>" type="text" value="<?php echo $city; ?>" /></p>
        
		<p><label for="<?php echo $this->get_field_id('zip'); ?>"><?php _e('Zip:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('zip'); ?>" name="<?php echo $this->get_field_name('zip'); ?>" type="text" value="<?php echo $zip; ?>" /></p>
        
		<p><label for="<?php echo $this->get_field_id('state'); ?>"><?php _e('State:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('state'); ?>" name="<?php echo $this->get_field_name('state'); ?>" type="text" value="<?php echo $state; ?>" /></p>	
        
		<p><label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Introduce text:', 'wt_admin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" /></p>	
		
<?php
	}

}
