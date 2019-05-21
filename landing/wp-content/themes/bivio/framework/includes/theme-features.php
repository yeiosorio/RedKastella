<?php
class wt_themeFeatures {
	function wt_title(){
		global $page, $paged;
		wp_title( '|', true, 'right' );
		// Add the blog name.
		bloginfo( 'name' );
		
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && is_front_page() )
			echo " | $site_description";
		
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'wt_front' ), max( $paged, $page ) );
	}
	function wt_menu(){
		 wp_nav_menu( array( 
			 'theme_location' => 'primary-menu',
			 'container'      => false,
			 'menu_class'     => 'menu nav navbar-nav navbar-right',
			 'walker'         => new My_walker ,
		 ));
	}
	function home_menu(){
		global $home_menu;
		wt_home($home_menu);
	}
	function wt_sidebar($post_id = NULL){
		wt_sidebar_generator('wt_get_sidebar',$post_id);
	}
	function wt_footer_sidebar(){
		wt_sidebar_generator('wt_get_footer_sidebar');
	}
	function wt_top_widget(){
		wt_sidebar_generator('wt_get_top_widget');
	}	
	function wp_link_pages(){
		 wp_link_pages( array());
	}
	function comment_form(){
	}
	function search(){
		global $search;
		wt_search($search);
	}
	function wt_section($post_id = NULL){
		$ids = get_post_meta($post_id, '_section', true);
		$query = array(
			'post_type' => 'wt_section',
			'post__in' => explode(",", $ids)
		);
		$r = new WP_Query($query);
		while($r->have_posts()) {
			$r->the_post();
			$section_color = get_post_meta($ids, '_background_style', true);
			$section_img_custom = get_post_meta($ids, '_bg_style_image', true);
			$section_position_custom = get_post_meta($ids, '_bg_style_position_x', true);
			$section_repeat_custom = get_post_meta($ids, '_bg_style_repeat', true);
			$section_color_custom = get_post_meta($ids, '_background_style_color', true);
			$section_bg_color = get_post_meta($ids, '_bg_style_color', true);
			$section_parallax_custom = get_post_meta($ids, '_bg_style_parallax', true);
			$section_bg_overlay = get_post_meta($ids, '_bg_overlay', true);
			$section_img_cover = get_post_meta($ids, '_bg_style_cover', true);
			$parallax = get_post_meta($ids, '_parallax', true);
			if ($parallax==='true') {
				$parallax = ' wt_parallax';
			}
			$disable_margins = get_post_meta($ids, '_disable_margins', true);
			$bgType = get_post_meta($ids, '_bg_type', true);
			if ($disable_margins==='true') {
				$disable_margins = ' wt_no_margins';
			}
			
			if(!empty($section_bg_color) && $section_bg_color != "transparent"){
				$section_bg_color = 'background-color:'.$section_bg_color.';';
			}else{
				$section_bg_color = '';
			}
			if(!empty($section_color_custom) && $section_color_custom != "transparent"){
				$section_color_custom = 'background-color:'.$section_color_custom.';';
			}else{
				$section_color_custom = '';
			}
			if(!empty($section_img_custom)){
				$section_img_custom = 'background-image:url('.$section_img_custom.');background-position:top '.$section_position_custom.';background-repeat:'.$section_repeat_custom.'';
			}else{
				$section_img_custom = '';
			}
			
			if(!empty($section_parallax_custom)){
				$section_parallax_custom = 'background-image:url('.$section_parallax_custom.');';
			}else{
				$section_parallax_custom = '';
			}
			
			if(!empty($section_bg_overlay) && $section_bg_overlay != "transparent"){
				$section_bg_overlay = 'background-color:'.$section_bg_overlay.';';
			}else{
				$section_bg_overlay = '';
			}
			$i = 1;
			echo '<section id="'. get_the_slug($post_id) .'_section" class="wt_separator_section">';		
			if(wt_is_enabled(get_post_meta($ids, '_display_arrow', true))) {
				echo '<div class="wt_section_arrow"';
				if(!empty($section_bg_color)) {echo ' style="'.$section_bg_color.'"';} 
				echo '></div>';
			}
			if($bgType == 'parallax') {
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="wt_section_area wt_parallax'.$disable_margins.' '. $section_color .'"';
				if(!empty($section_parallax_custom)) {echo ' style="'.$section_parallax_custom.'"';
				} 			
				echo '>';
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			} elseif($bgType == 'color') {
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="wt_section_area'.$disable_margins.' '. $section_color .'"';
				if(!empty($section_bg_color)) {echo ' style="'.$section_bg_color.'"';		
				}
				echo '>';
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			} elseif($bgType == 'cover') {
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="wt_section_area'.$disable_margins.' '. $section_color .'"';
				if(!empty($section_img_cover)) {echo ' style="background-image:url('.$section_img_cover.');background-size: cover; background-attachment: fixed;"';		
				}
				echo '>';
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			} elseif($bgType == 'pattern') {
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="wt_section_area wt_pattern'.$disable_margins.' '. $section_color .'"';
				if(!empty($section_color_custom) || !empty($section_img_custom)) {echo' style="'.$section_img_custom.'"';}
				echo '>';
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			} elseif($bgType == 'video') {
				wp_enqueue_script( 'jquery-youtube');
				$videoId = get_post_meta($ids, '_bg_video', true);
			    echo '<div class="bg_video_section wt_video_'.$ids.'"><div class="wt_pattern_overlay"></div><div id="bgndVideo_'.$ids.'" class="wt_youtube_player" data-property="{videoURL:\'http://www.youtube.com/watch?v='.$videoId.'\', containment:\'.wt_video_'.$ids.'\', autoPlay:true, mute:true, startAt:0, opacity:1, ratio:\'4/3\', addRaster:true, showControls:false}"></div></div> <a class="video-volume" onclick="jQuery(\'#bgndVideo_'.$ids.'\').toggleVolume()"><i class="fa fa-volume-down"></i></a>';
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="wt_video wt_section_area'.$disable_margins.' '. $section_color .'"';
				echo '>';
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			} elseif ($bgType != 'parallax' || $bgType != 'pattern' || $bgType != 'video') {
				echo '<section id="'. get_the_slug($post_id) .'_separator" class="color wt_section_area'.$disable_margins.' '. $section_color .'"';
				if(!empty($section_bg_color) || !empty($section_img_custom)) {echo ' style="background-color:'.$section_bg_color.';"';		
				}
				echo '>'; 
				if(!empty($section_bg_overlay)) {echo ' 
					<div class="wt_section_overlay" style="'.$section_bg_overlay.'"></div>';
				} 
			}
			echo '<div class="container"><div class="row">';
		    echo wt_theme_generator('wt_section_title',$post_id);
			echo the_content();
            echo '</div></div>';
            echo '</section>';
			wp_reset_postdata();
		}	
	}
	function wt_headerWrapper($post_id = NULL) {
		echo '<div id="wt_headerWrapper" role="banner" class="clearfix">';
	}
	function wt_header($post_id = NULL) {
		$stickyHeader  = wt_get_option('general', 'sticky_header');
		$menu_position = wt_get_option('general','menu_position');
		$responsiveNav = wt_get_option('general', 'responsive_nav');
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_header_bg_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_header_bg', true));
		$bg_position = get_post_meta($post_id, '_header_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_header_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		if($stickyHeader) {
			$navbar = ' navbar-fixed-top';
		}else{
			$navbar = ' navbar-static-top';
		}
		$responsiveNav = 'wt_resp_nav_under_' . $responsiveNav . ' ';
		echo '<header id="wt_header" class="'.$responsiveNav.'navbar'.$navbar.' responsive_nav clearfix" role="banner"';
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$bg.'"';
		}
		echo '>';
	}
	function wt_nav($post_id = NULL) {
		$menu_position = wt_get_option('general','menu_position');
		$enable_retina = wt_get_option('general', 'enable_retina');
		$retinaLogo    = wt_get_option('general', 'logo_retina');
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_nav_bg_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_nav_bg', true));
		$bg_position = get_post_meta($post_id, '_nav_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_nav_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		/*if ($menu_position == 'top') {*/
			echo '<nav id="nav" class="wt_nav_top collapse navbar-collapse" role="navigation" data-select-name="-- Main Menu --"';
		/*} else  {
			echo '<nav id="nav" class="wt_nav_side" role="navigation" data-select-name="-- Main Menu --"';
		}*/
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$bg.'"';
		}
		echo '>';
		/*if(!wt_get_option('general','display_logo') && $custom_logo = wt_get_option('general','logo')){ 		
                   echo '<div id="logo">';
            	if($enable_retina){
                echo '<a href="'. home_url( '/' ) .'"><img src="'. $custom_logo .'" data-at2x="'. $retinaLogo .'" /></a>';
				} else {
                         echo '<a href=""><img src="'. $custom_logo .'" /></a>';
					}
                     echo '</div>';
			}else {
                        echo ' <div id="logo_text">';
                           echo '  <a href="'. home_url( '/' ).'"></a>';
                    if(wt_get_option('general','display_site_desc')){
                            $site_desc = get_bloginfo( 'description' );
                            if(!empty($site_desc)) {
                           echo '  <div id="siteDescription">'. bloginfo( 'description' ).'</div>';
                    }
                    }
                       echo ' </div>';
                   }*/
	}
	function wt_intro($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_intro_bg_color', true));
		$textcolor = wt_check_input(get_post_meta($post_id, '_intro_text_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_intro_bg', true));
		$bg_position = get_post_meta($post_id, '_intro_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_intro_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($textcolor) && $textcolor != "transparent"){
			$textcolor = 'color:'.$textcolor.';';
		}else{
			$textcolor = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		echo '<header id="wt_intro"';
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$textcolor.''.$bg.'"';
		}
		echo ' class="clearfix">';
	}
	function wt_containerWrapp($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_container_bg_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_container_bg', true));
		$bg_position = get_post_meta($post_id, '_container_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_container_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		echo '<div id="wt_containerWrapp"';
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$bg.'"';
		}
		if(is_single()){
			echo ' class="wt_section clearfix">';
		}
		else {
			echo ' class="clearfix">';
		}
	}
	function wt_content($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_content_bg_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_content_bg', true));
		$bg_position = get_post_meta($post_id, '_content_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_content_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		
		echo '<div id="wt_content"';
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$bg.'"';
		}
		echo ' class="clearfix"';
		if ( is_page_template('template_fullwidth.php') || is_page_template('gallery-4-columns.php') || is_page_template('gallery-3-columns.php') || is_page_template('gallery-2-columns.php') || is_page_template('galleria.php') ) { 
			echo ' role="main"';
		}
		echo '>';
	}
	function wt_footerWrapper($post_id = NULL) {
		echo ' <div id="wt_footerWrapper" class="clearfix">';
	}
	function wt_footer($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_footer_bg_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_footer_image', true));
		$bg_position = get_post_meta($post_id, '_footer_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_footer_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		echo ' <footer id="wt_footer"';
		if(!empty($color) || !empty($bg)){
			echo' style="'.$color.''.$bg.'"';
		}
		echo ' class="clearfix">';
	}
	function wt_footerTop($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_footer_top_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_footer_top_image', true));
		$bg_position = get_post_meta($post_id, '_footer_top_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_footer_top_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}		
		echo '<footer id="wt_footerTop" class="clearfix">';
		echo '<div class="container"';
		if(!empty($color)){
			echo' style="'.$color.'"';
		}
		echo '>';
	}
	function wt_footerBottom($post_id = NULL) {
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		$color = wt_check_input(get_post_meta($post_id, '_footer_bottom_color', true));
		$bg = wt_check_input(get_post_meta($post_id, '_footer_bottom_image', true));
		$bg_position = get_post_meta($post_id, '_footer_bottom_position_x', true);
		$bg_repeat = get_post_meta($post_id, '_footer_bottom_repeat', true);
		if(!empty($color) && $color != "transparent"){
			$color = 'background-color:'.$color.';';
		}else{
			$color = '';
		}
		if(!empty($bg)){
			$bg = 'background-image:url('.$bg.');background-position:top '.$bg_position.';background-repeat:'.$bg_repeat.'';
		}else{
			$bg = '';
		}
		echo '<footer id="wt_footerBottom"';
		if(!empty($color)){
			echo' style="'.$color.'"';
		}
		echo ' class="clearfix">';
		echo '<div class="container">';
	}
	function wt_breadcrumbs($post_id = NULL) {
		if(!wt_is_enabled(get_post_meta($post_id, '_disable_breadcrumb', true), wt_get_option('general','disable_breadcrumb'))){
		breadcrumbs_plus(array(
				'prefix' => '<div class="container"><div class="row"><div class="col-xs-12 text-right"><div class="breadcrumbs">',
				'suffix' => '</div></div></div></div>',
				'title' => false,
				'home' => __( 'Home', 'wt_front' ),
				'sep' => false,
				'front_page' => false,
				'bold' => false,
				'blog' => __( 'Blog', 'wt_front' ),
				'echo' => true
			));
		}
	}	
	function wt_custom_header($post_id = NULL) {
		$type = get_post_meta($post_id, '_intro_type', true);
		$textcolor = wt_check_input(get_post_meta($post_id, '_intro_text_color', true));
		if(!empty($textcolor) && $textcolor != "transparent"){
			$textcolor = 'color:'.$textcolor.';';
		}else{
			$textcolor = '';
		}
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		if (is_single() || is_page() || (is_front_page() && $post_id != NULL) || (is_home() && $post_id != NULL)){
			$type = get_post_meta($post_id, '_intro_type', true);

			if (empty($type))
				$type = 'default';			
			if (wt_get_option('introheader','slideshow_everywhere') && $type == 'default') {
				$type = 'slideshow';
			}		
			if (wt_get_option('introheader','static_image_everywhere') && $type == 'default') {
				$type = 'static_image';
			}		
			/*if ((wt_get_option('general', 'woocommerce')) && (is_woocommerce() || is_cart() || is_checkout() || is_account_page())) {
				$type = 'default';
			}*/
			if (is_front_page() && $type == 'default') {
				$type = 'slideshow';
			}
			if ($type == 'disable') {
				return;
			}
			if ($type == 'slideshow'){
				$stype = get_post_meta($post_id, '_slideshow_type', true);
				if(empty($stype) || $stype == 'default'){
					$stype= wt_get_option('introheader','slideshow_type');
				}
				//return wt_theme_generator('wt_slideShow',$stype);
			}
			if ($type == 'static_image'){
				return wt_theme_generator('wt_staticImage',$type);
			}
			if ($type == 'static_video'){
				return wt_theme_generator('wt_staticVideo',$type);
			}
			if (in_array($type, array('default', 'title', 'title_custom'))) {
				$custom_title = get_post_meta($post_id, '_custom_title', true);
				if(!empty($custom_title)){
					$title = $custom_title;
				}else{
					$title = get_the_title($post_id);
				}
			}
			$blog_page_id = wt_get_option('blog','blog_page');
			if ($type == 'default' && is_singular('post') && $post_id!=$blog_page_id) {
				//$show_in_header = wt_get_option('blog','show_in_header');
				//if($show_in_header){
					//$title = get_the_title($post_id);
					
					/*$text = '<span class="single_metadata">';
					if (wt_get_option('blog','single_meta_date')){
						$text .= '<span class="single_date">'.__('Date: ', 'wt_front').'</span>';
						$text .= '<a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_time('d F Y').'</a><span class="meta_separator">/</span>';
					}
					if (wt_get_option('blog','single_meta_category')){
						$text .= '<span class="single_category">'.__('Categories: ', 'wt_front').'</span>';
						$text .=  get_the_category_list(', ');
						$text .=  '<span class="meta_separator">/</span>';
					}
					$all_the_tags = get_the_tags();
					if (wt_get_option('blog','single_meta_tags') && $all_the_tags){
						$text .= '<span class="single_tags">'.__('Tags: ', 'wt_front').'</span>';
						$text .= get_the_tag_list('',', '); 
						$text .=  '<span class="meta_separator">/</span>';
					}
					
					$text .= get_edit_post_link( __( 'Edit', 'wt_front' ), '<span class="edit-link">', '</span>' );
					if(wt_get_option('blog','single_meta_comment') && (comments_open())){
						ob_start();
						comments_popup_link(__('No comments ','wt_front'), __('1 Comment','wt_front'), __('% Comments','wt_front'),'');
						$text .=  ob_get_clean();
					}
					$text .= '</span>';*/
				//}else{
					return $this->wt_custom_header($blog_page_id);
				//}
			}
			if (in_array($type, array('custom', 'title_custom'))) {
				$text = trim(get_post_meta($post_id, '_custom_introduce_text', true));
			}
		}
		if (is_archive()){
			if ((wt_get_option('general', 'woocommerce')) && (is_shop() || is_product_category() || is_product())) {
				$custom_title = get_post_meta($post_id, '_custom_title', true);
				$text = trim(get_post_meta($post_id, '_custom_introduce_text', true));
				if(!empty($custom_title)){
					$title = $custom_title;
				}else{
					$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
				}
			}
			else {
				$title = __('Archives','wt_front');
			}
			if(is_category()){
				$text = sprintf(__('Category Archive for: <strong>"%s"</strong>','wt_front'),single_cat_title('',false));
			}elseif(is_tag()){
				$text = sprintf(__('Tag Archives for: <strong>"%s"</strong>','wt_front'),single_tag_title('',false));
			}elseif(is_day()){
				$text = sprintf(__('Daily Archive for: <strong>"%s"</strong>','wt_front'),get_the_time('F jS, Y'));
			}elseif(is_month()){
				$text = sprintf(__('Monthly Archive for: <strong>"%s"</strong>','wt_front'),get_the_time('F, Y'));
			}elseif(is_year()){
				$text = sprintf(__('Yearly Archive for: <strong>"%s"</strong>','wt_front'),get_the_time('Y'));
			}elseif(is_author()){
				if(get_query_var('author_name')){
					$curauth = get_user_by('slug', get_query_var('author_name'));
				} else {
					$curauth = get_userdata(get_query_var('author'));
				}
				$text = sprintf(__('Author Archive for: <strong>"%s"</strong>','wt_front'),$curauth->nickname);
			}elseif(isset($_GET['paged']) && !empty($_GET['paged'])) {
				$text = __('Blog Archives','wt_front');
			}elseif(is_tax()){
				if ((wt_get_option('general', 'woocommerce')) && (is_product_category())) {
					$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$text = sprintf(__('Category: %s','wt_front'),$term->name);
				}
				else {
					$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
					$text = sprintf(__('Archives for: <strong>"%s"</strong>','wt_front'),$term->name);
				}
			}					
		
		}	
		if (is_404()) {
			$title = __("Sorry! We couldn't find it.","wt_front");
			$text = __("You have requested a page or file which does not exists anymore. Below are a few options to find what you are looking for.",'wt_front');
		}
		
		if (is_search()) {
			$title = __('Search','wt_front');
			$text = sprintf(__('Search Results for: "%s"','wt_front'),stripslashes( strip_tags( get_search_query() ) ));
		}
		if( function_exists('is_woocommerce') && is_woocommerce()){
			if(function_exists('is_shop') && is_shop()){
				$shop_id = woocommerce_get_page_id( 'shop' );
				if($shop_id != $post_id){
					$type = get_post_meta($shop_id, '_intro_type', true);
					
					if (empty($type)){
						$type = 'default';
					}
					if($type !== 'default'){
						return wt_theme_generator('wt_custom_header', $shop_id, false, true);
					}
					
				}
			}
		}
		if( function_exists('is_woocommerce') && (is_product() || is_product_category())) {
			$shop_id = woocommerce_get_page_id( 'shop' );
			if($shop_id != $post_id){
				$type = get_post_meta($shop_id, '_intro_type', true);
				
				if($type !== 'default'){
					return wt_theme_generator('wt_custom_header', $shop_id, false, true);
				}
				else {
					$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
				}
			}
			
		}
		
		echo wt_theme_generator('wt_intro',$post_id);	
			
		echo "\n\t\t".'<div class="container">'."\n";
		
		if(is_front_page() && (wt_get_option('general','intro_header_text'))){
            //echo "\n<div id='introHeaderWidget'>\n";
            //echo '<h1>'.wt_get_option('general','intro_header_text').'</h1>';
			//echo "\n</div>\n";
        } 
		
		echo "\t\t\t".'<div id="introType" class="wt_intro"><div class="intro_text">';
		if (isset($title)) {
			if(!empty($textcolor)){
				echo '<h1 style="'.$textcolor.'">' . $title . '</h1>';
			} else {
				echo '<h1>' . $title . '</h1>';
			}
		}
		if (isset($text)) {
			echo '<h3 class="custom_title">'.$text.'</h3>';
		}
		echo "</div></div>\n\t\t";
		if ($type != 'slideshow' || $type != 'disable' || $type != 'static_image' || $type != 'static_video'){
			//wt_theme_generator('wt_breadcrumbs',$post_id);
		}
		echo "</div>\n\t";
		echo "</header>\n";
	}
	
	function wt_custom_title($post_id = NULL) {
		$type = get_post_meta($post_id, '_intro_type', true);
		if (is_blog()){
			$blog_page_id = wt_get_option('blog','blog_page');
			$post_id = get_object_id($blog_page_id,'page');
		}
		if (is_single() || is_page() || (is_front_page() && $post_id != NULL) || (is_home() && $post_id != NULL)){
			$type = get_post_meta($post_id, '_intro_type', true);
			
			if (in_array($type, array('default', 'title', 'title_custom'))) {
				$custom_title = get_post_meta($post_id, '_custom_title', true);
				if(!empty($custom_title)){
					$title = $custom_title;
				}else{
					$title = get_the_title($post_id);
				}
			}
			
			if (in_array($type, array('custom', 'title_custom'))) {
				$text = '<h3 class="custom_title wt_animate wt_animate_if_visible" data-animation="bounceIn">'.trim(get_post_meta($post_id, '_custom_introduce_text', true)).'</h3>';
			}
		}	
		//echo wt_theme_generator('wt_intro',$post_id);		
			
		echo "\t\t\t".'<div class="wt_intro"><div class="intro_text">';
		if (isset($title)) {
			echo '<h2 class="title wt_animate wt_animate_if_visible" data-animation="bounceIn">' . $title . '</h2>';
		}
		if (isset($text)) {
			echo $text;
		}
		echo "</div></div>\n\t";
	}
	
	function wt_section_title() {
		$type = get_post_meta(get_the_ID(), '_intro_type', true);
			if (in_array($type, array('default', 'title', 'title_custom'))) {
				$custom_title = get_post_meta(get_the_ID(), '_custom_title', true);
				if(!empty($custom_title)){
					$title = $custom_title;
				}else{
					$title = get_the_title(get_the_ID());
				}
			}
			
			if (in_array($type, array('custom', 'title_custom'))) {
				$text = '<h3 class="custom_title wt_animate wt_animate_if_visible" data-animation="bounceIn">'.trim(get_post_meta(get_the_ID(), '_custom_introduce_text', true)).'</h3>';
			}
		//echo wt_theme_generator('wt_intro',$post_id);		
			
		echo "\t\t\t".'<div class="wt_intro"><div class="intro_text">';
		if (isset($title)) {
			echo '<h2 class="title wt_animate wt_animate_if_visible" data-animation="bounceIn">' . $title . '</h2>';
		}
		if (isset($text)) {
			echo $text;
		}
		echo "</div></div>\n\t";
	}
	
	function wt_slogan($post_id = NULL) {
		$slogan_text = get_post_meta($post_id, '_slogan_text', true);
		$slogan_author = get_post_meta($post_id, '_slogan_author', true);
		//echo wt_theme_generator('wt_intro',$post_id);	
		if (isset($slogan_text)&& $slogan_text != '') {	
			echo "\t\t\t".'<div class="quotes_box">.';
		}
		if (isset($slogan_text)&& $slogan_text != '') {
			echo "\t\t\t".'<span class="quotes_text">';
				echo $slogan_text;
			echo "</span>\n\t";
		}
		if (isset($slogan_author)&& $slogan_author != '') {
			echo "\t\t\t".'<span class="quotes_author">';
				echo $slogan_author;
			echo "</span>\n\t";
		}
		if (isset($slogan_text)&& $slogan_text != '') {
			echo "</div>\n\t";
		}
	}
	
	function wt_separator($post_id = NULL) {
		$separator = get_post_meta($post_id, '_slogan_bg', true);
		if (isset($separator)&& $separator != '') {
			echo 'style="background-image: url(\'' . $separator . '\')"';
		}
	}
		
	function wt_portfolio_featured_image($type='full',$layout='', $height=''){
		if($layout == 'full'){
			$width = 1140;
		}else{
			$width = 848;
		}
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
		$adaptive_height = wt_get_option('portfolio', 'adaptive_height');
		
		if($adaptive_height){
			$height = floor($width*($image_src_array[2]/$image_src_array[1]));
		}else{
			$height = wt_get_option('portfolio', 'fixed_height');
		}
		$image_src = aq_resize( wt_get_image_src($image_src_array[0]), $width, $height, true ); //resize & crop img
		if( class_exists('Dynamic_Featured_Image')) {
			global $dynamic_featured_image;
			$featured_images = $dynamic_featured_image->get_featured_images( $post->ID );
			if (has_post_thumbnail()) {
				$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
				$image_src = wt_get_image_src($image_src_array[0]); 
			}
		   	if( !is_null($featured_images) ){
			wp_print_scripts('owlCarousel');
				if (has_post_thumbnail()) {
					
				$title = get_post(get_post_thumbnail_id())->post_title; //The Title
				$alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); //The Alt
				$caption = get_post(get_post_thumbnail_id())->post_excerpt; //The Caption
				$description = get_post(get_post_thumbnail_id())->post_content; // The Description
				
					if($layout=='left'){
						if(wt_get_option('portfolio', 'featured_image_lightbox')){
							$content .= $li.'<a class="" href="'.$image_src.'" title="'.get_the_title().'" data-rel="wt_lightbox[wt_single]"><img src="'. aq_resize( $image_src, $img_width, $height, true ).'" alt="'.get_the_title().'" /></a>'.$end_li;
						} else {
							$content .= $li.'<img src="'. aq_resize( $image_src, $img_width, $height, true ).'" alt="'.get_the_title().'" />'.$end_li;
						}
					} else {
						if(wt_get_option('portfolio', 'featured_image_lightbox')){
							$content .= $li.'<a class="" href="'.$image_src.'" title="'.get_the_title().'" data-rel="wt_lightbox[wt_single]"><img src="'. aq_resize( $image_src, $width, $height, true ).'" alt="'.get_the_title().'" /></a>'.$end_li;
						} else {
							$content .= $li.'<img src="'. aq_resize( $image_src, $width, $height, true ).'" alt="'.get_the_title().'" />'.$end_li;
						}
					}
				}
			    foreach($featured_images as $images) {
				      
					$image_url = $images['full'];
				
					$title = $dynamic_featured_image -> get_image_title( $image_url ); //The (dynamic image) Title
					$alt = $dynamic_featured_image -> get_image_alt( $image_url ); //The (dynamic image) Alt
					$caption = $dynamic_featured_image -> get_image_caption( $image_url ); //The (dynamic image) Caption
					$description = $dynamic_featured_image -> get_image_description( $image_url ); // The (dynamic image) Description
				
					if($layout=='left'){
						if(wt_get_option('portfolio', 'featured_image_lightbox')){
							$content .= $li.'<a class="" href="'.$image_url.'" title="'.get_the_title().'" data-rel="wt_lightbox[wt_single]"><img src="'. aq_resize( $image_url, $img_width, $height, true ).'"';
						} else {
							$content .= $li.'<img src="'. aq_resize( $image_url, $img_width, $height, true ).'"';
						}
					}else{
						if(wt_get_option('portfolio', 'featured_image_lightbox')){
							$content .= $li.'<a class="" href="'.$image_url.'" title="'.get_the_title().'" data-rel="wt_lightbox[wt_single]"><img src="'. aq_resize( $image_url, $width, $height, true ).'"';
						} else {
							$content .= $li.'<img src="'. aq_resize( $image_url, $width, $height, true ).'"';
						}
						//$content .= $li.'<a href="#"><img src="'. aq_resize( $image_url, $width, $height, true ).'"';
					}	
					if(wt_get_option('portfolio', 'featured_image_lightbox')){
						$content .= ' alt="'.$title.'" /></a>'.$end_li;
					} else {
						$content .= ' alt="'.$title.'" />'.$end_li;
					}
				}
			if($type=='left'){
				$output .= ' style="width:'.$width.'px"';
			}
			if($featured_images) {
				$output .= '<div id="wt_owl_rotator" class="wt_owl_rotator" data-owl-autoPlay="3000" data-owl-stopOnHover="true" data-owl-navigation="false" data-owl-pagination="true" data-owl-pagSpeed="1000" data-owl-autoHeight="true">';
				$output .= $content;
				$output .= '</div>';	
			} else {
				if (has_post_thumbnail()) {
				$output .= '<figure class="wt_image_frame entry_image">';
				$output .= '<span class="wt_image_holder">';
				if(is_single()){
					if(wt_get_option('portfolio', 'featured_image_lightbox')){
						$output .= '<a class="overlay_zoom" href="'.$image_src_array[0].'" title="'.get_the_title().'" data-rel="wt_lightbox">';
						$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" />';
						$output .= '</a>';
						$output .= '</span>';
					} else {
						$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" /></span>'; 
					}
				} else {
					$output .= '<a class="overlay_zoom" href="'. $image_src_array[0].'" title="">';
					$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" />';
					$output .= '</a>';
					$output .= '</span>';
				}
				$output .= '</figure>';	
				}
			}
		}else {
			if (has_post_thumbnail()) {
				$output .= '<figure class="wt_image_frame entry_image">';
				$output .= '<span class="wt_image_holder">';
				if(is_single()){
					if(wt_get_option('portfolio', 'featured_image_lightbox')){
						$output .= '<a class="overlay_zoom" href="'.$image_src_array[0].'" title="'.get_the_title().'" data-rel="wt_lightbox">';
						$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" />';
						$output .= '</a>';
						$output .= '</span>';
					} else {
						$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" /></span>'; 
					}
			} else {
				$output .= '<a class="overlay_zoom" href="'. $image_src_array[0].'" title="">';
				$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" />';
				$output .= '</a>';
				$output .= '</span>';
			}
			$output .= '</figure>';
		} 
	}
	}
	else {
		if (has_post_thumbnail()) {
			$output .= '<figure class="wt_image_frame entry_image">';
			$output .= '<span class="wt_image_holder">';
			if(is_single()){
				if(wt_get_option('portfolio', 'featured_image_lightbox')){
					$output .= '<a class="overlay_zoom" href="'.$image_src_array[0].'" title="'.get_the_title().'" data-rel="wt_lightbox">';
					$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" />';
					$output .= '</a>';
					$output .= '</span>';
				} else {
					$output .= '<img src="'.$image_src .'" alt="'.get_the_title().'" /></span>'; 
				}
		} else {
			$output .= '<a class="overlay_zoom" href="'. $image_src_array[0].'" title="">';
			$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" />';
			$output .= '</a>';
			$output .= '</span>';
		}
		$output .= '</figure>';
		} 
	}
		return $output;
}
		
	function wt_blog_featured_image($type='full',$layout='',$set_width='',$set_height=''){
		$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
			
		if($layout == 'full'){
			$width = 1140;
			$left_width = 720; // content width under 991px where the image is displayed at full size
		}elseif(is_numeric($layout)){
			$width = $layout;
			$left_width = $width;
		}else{
			$width = 848;
			$left_width = 720; // main content width under 991px where the image is displayed at full size
		}
		
		if($type=='left'){
			if($layout == 'full'){
				$inline_width = wt_get_option('blog', 'left_width'); // Full Layout - left image inline width
				$height = wt_get_option('blog', 'left_image_height');
			} else {
				$inline_width = wt_get_option('blog', 'sidebar_left_width'); // Sidebar Layout - left image inline width
				$height = wt_get_option('blog', 'sidebar_left_image_height');
			}
		}else{
			$adaptive_height = wt_get_option('blog', 'adaptive_height');
			$single_adaptive_height = wt_get_option('blog', 'single_adaptive_height');
			if($adaptive_height && is_blog()){
				$height = floor($width*($image_src_array[2]/$image_src_array[1]));
			}elseif($single_adaptive_height && is_single()){
				$height = floor($width*($image_src_array[2]/$image_src_array[1]));
			}else{
				if($layout == 'full'){
					$height = wt_get_option('blog', 'image_height');
				} else {
					$height = wt_get_option('blog', 'sidebar_image_height');
				}
			}
		}
		
		// If width / height are set by default when function is called
		if ($set_width != '') {
			$width = $set_width;
		}
		if ($set_height != '') {
			$height = $set_height;
		}
		
		if($type=='left'){
			$width = $left_width; // The full width of the image
		}
		
		$image_src = aq_resize( wt_get_image_src($image_src_array[0]), $width, $height, true ); //resize & crop img
		
		$output = '';
		if (has_post_thumbnail()){
			$output .= '<div class="wt_image_frame entry_image">';
			$output .= '<span class="wt_image_holder"';
			if($type=='left'){
				$output .= ' style="width:'.$inline_width.'px"';
			}
			$output .= '>';
			if(is_single()){
				if(wt_get_option('blog', 'featured_image_lightbox')){
					$output .= '<a class="overlay_zoom" href="'.$image_src_array[0].'" title="'.get_the_title().'" data-rel="lightbox">';
					$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'" />';
					$output .= '</a>';
				} else {
					$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'" />';
				}
			} else {
				$output .= '<a class="overlay_zoom" href="'.get_permalink().'" title="">';
				$output .= '<img src="'.$image_src.'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'" />';
				$output .= '</a>';
			}
			$output .= '</span>';
			$output .= '</div>';
		}
		return $output;
	}
	
	function wt_blog_meta() {
 		global $post;
		$output = '';
		if (wt_get_option('blog','meta_date')){
			//$output .= '<div class="entry_date">';
			//$output .= '<i class="fa fa-calendar"></i><a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_time('d F Y').'</a></div>';
		}
		if (wt_get_option('blog','meta_author')){
			$output .= '<div class="entry_author">';
			$output .= '<i class="fa fa-user"></i><a class="no_link" href="">'.get_the_author_link().'</a></div>';
		}
		if (wt_get_option('blog','meta_category')){
			$output .= '<div class="entry_category"><i class="fa fa-folder-open"></i>';
			$output .= get_the_category_list(', ') .'</div>' ;
		}
		if (wt_get_option('blog','meta_tags')){
			$output .= get_the_tag_list('<div class="entry_tags"> <i class="fa fa-tags"></i>',', ','</div>'); 
		}
		
			$output .= get_edit_post_link( __( 'Edit', 'wt_front' ), '<span class="edit-link">', '</span>' );
			if(wt_get_option('blog','meta_comment') && ($post->comment_count > 0 || comments_open())){
			ob_start();
			comments_popup_link(__(' 0 ','wt_front'), __(' 1 ','wt_front'), __(' % ','wt_front'),'');
						
			$output .= '<div class="entry_comments"><i class="fa fa-comments"></i>';
			$output .= ob_get_clean().'</div>' ;			
		}
		return $output;
		
	}
	
	function wt_blog_carousel_meta() {
 		global $post;
		$output = '';
		if (wt_get_option('blog','meta_date')){
			$output .= '<span class="entry_date_carousel">';
			$output .= '<a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_time('d F Y').'</a></span>';
		}
		if (wt_get_option('blog','meta_author')){
			$output .= ' / <span class="entry_author">';
			$output .= '<a class="no_link" href="">'.get_the_author_link().'</a></span>';
		}
		if (wt_get_option('blog','meta_category')){
			$output .= ' / <span class="entry_category">';
			$output .= get_the_category_list(', ') .'</span>' ;
		}
		if (wt_get_option('blog','meta_tags')){
						
			$output .= get_the_tag_list(' / <span class="tags">',', ','</span>'); 
		}
		
			$output .= get_edit_post_link( __( 'Edit', 'wt_front' ), '<span class="edit-link">', '</span>' );
			if(wt_get_option('blog','meta_comment') && ($post->comment_count > 0 || comments_open())){
			ob_start();
			comments_popup_link(__(' 0 Comments','wt_front'), __(' 1 Comment','wt_front'), __(' % Comments','wt_front'),'');
						
			$output .= ' / <span class="entry_comments">';
			$output .= ob_get_clean().'</span>' ;			
		}
		return $output;
		
	}
	
	function wt_blog_single_meta() {
 		global $post;
		$output = '';
		
		if (wt_get_option('blog','single_meta_date')){
			//$output .= '<div class="entry_date"><span>'.__('Date: ', 'wt_front').'</span>';
			$output .= '<div class="entry_date_single">';
			$output .= '<i class="fa fa-calendar"></i><a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'">'.get_the_time('d F Y').'</a></div>';
		}
		if (wt_get_option('blog','single_meta_author')){
			//$output .= '<div class="entry_author"><span>'.__('By: ', 'wt_front').'</span>';
			$output .= '<div class="entry_author">';
			$output .= '<i class="fa fa-user"></i><a class="no_link" href="">'.get_the_author_link().'</a></div>';
		}
		if (wt_get_option('blog','single_meta_category')){
			//$output .= '<div class="entry_category"><span>'.__('In: ', 'wt_front').'</span>';
			$output .= '<div class="entry_category"><i class="fa fa-folder-open"></i>';
			$output .= get_the_category_list(', ') .'</div>' ;
		}
		if (wt_get_option('blog','single_meta_tags')){
						
			//$output .= get_the_tag_list(' <div class="entry_tags"><span>'.__('Tags: ', 'wt_front').'</span>',', ','</div>'); 
			$output .= get_the_tag_list('<div class="entry_tags"><i class="fa fa-tags"></i>',', ','</div>'); 
		}
		
			$output .= get_edit_post_link( __( 'Edit', 'wt_front' ), '<span class="edit-link">', '</span>' );
			if(wt_get_option('blog','single_meta_comment') && ($post->comment_count > 0 || comments_open())){
			ob_start();
			comments_popup_link(__(' 0 ','wt_front'), __(' 1 ','wt_front'), __(' % ','wt_front'),'');
						
			//$output .= '<div class="entry_comments"><span>'.__('Comments: ', 'wt_front').'</span>';
			$output .= '<div class="entry_comments"><i class="fa fa-comments"></i>';
			$output .= ob_get_clean().'</div>' ;			
		}
		return $output;
		
	}
	
	function wt_blog_author_info() {
?>
<section id="aboutTheAuthor">
	<div class="aboutTheAuthor_wrapp">
		<div class="gravatar"><?php echo get_avatar( get_the_author_meta('user_email'), '80' ); ?></div>
		<div class="aboutTheAuthor_content">
			<h4><?php the_author_posts_link(); ?></h4>
			<p class="author_desc"><?php the_author_meta('description'); ?></p>
		</div>
		<div class="wt_clearboth"></div>
	</div>
</section>
<?php 
	}

	function wt_blog_popular_posts(){
		$r = new WP_Query(array(
			'showposts' => 4, 
			'nopaging' => 0, 
			'orderby'=> 'comment_count', 
			'post_status' => 'publish', 
			'ignore_sticky_posts' => 1
		));
		$output = '';
		if ($r->have_posts()){
			$output .= '<ul class="posts wt_postList">';
			while ($r->have_posts()){
				$r->the_post();
				$output .= '<li>';
				$output .= '<a class="thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
				if (has_post_thumbnail() ){
					$output .= get_the_post_thumbnail(get_the_ID(),'thumb', array(70,45),array('title'=>get_the_title(),'alt'=>get_the_title()));
				}else{
					$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="70" height="45" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
				}
				$output .= '</a>';
				$output .= '<div class="wt_postInfo">';
				$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
				$output .= '<span class="date">'.get_the_date().'</span>';
				$output .= '</div>';
				$output .= '<div class="wt_clearboth"></div>';
				$output .= '</li>';
			}
			$output .= '</ul>';
		}

		wp_reset_postdata();
		echo $output;
	}

	function wt_blog_related_posts(){
		global $post;
		$backup = $post;  
		$tags = wp_get_post_tags($post->ID);
        $tagIDs = array();
        $related_post_found = false;
        $output = '';
		if ($tags) {
			$tagcount = count($tags);
			for ($i = 0; $i < $tagcount; $i++) {
				$tagIDs[$i] = $tags[$i]->term_id;
			}
			$r = new WP_Query(array(
				'tag__in' => $tagIDs,
				'post__not_in' => array($post->ID),
				'showposts'=>4,
				'ignore_sticky_posts'=>1
			));
			if ($r->have_posts()){
				$related_post_found = true;
				$output .= '<ul class="posts wt_postList">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
					if (has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),'thumb', array(70,45),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="70" height="45" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '</a>';
					$output .= '<div class="wt_postInfo">';
					$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
					$output .= '<span class="date">'.get_the_date().'</span>';
					$output .= '</div>';
					$output .= '<div class="wt_clearboth"></div>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
			$post = $backup;
		}
		if(!$related_post_found){
			$r = new WP_Query(array(
				'showposts' => 4, 
				'nopaging' => 0, 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => 1
			));
			if ($r->have_posts()){
				$output .= '<ul class="posts wt_postList">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li>';
					$output .= '<a class="thumb" href="'.get_permalink().'" title="'.get_the_title().'">';
					if (has_post_thumbnail() ){
						$output .= get_the_post_thumbnail(get_the_ID(),'thumb', array(70,45),array('title'=>get_the_title(),'alt'=>get_the_title()));
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="70" height="45" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '</a>';
					$output .= '<div class="wt_postInfo">';
					$output .= '<a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a>';
					$output .= '<span class="date">'.get_the_date().'</span>';
					$output .= '</div>';
					$output .= '<div class="wt_clearboth"></div>';
					$output .= '</li>';
				}
				$output .= '</ul>';
			}
		}
		wp_reset_postdata();

		echo $output;
	}
	
	function wt_portfolio_related_posts(){
		global $post;
		$backup = $post;  
		$tags = wp_get_post_tags($post->ID);
        $tagIDs = array();
        $related_post_found = false;
		wp_print_scripts('owlCarousel');
        $output = '';
		if ($tags) {
			$tagcount = count($tags);
			for ($i = 0; $i < $tagcount; $i++) {
				$tagIDs[$i] = $tags[$i]->term_id;
			}
			$r = new WP_Query(array(
				'post_type' => 'wt_portfolio', 
				'tag__in' => $tagIDs,
				'post__not_in' => array($post->ID),
				//'showposts'=>3,
				'ignore_sticky_posts'=>1
			));
			if ($r->have_posts()){
				$related_post_found = true;
				$output .= '<div class="wt_portfolio_wrapper_carousel">';
				$output .= '<ul class="wt_owl_carousel posts portfList" data-owl-speed="600" data-owl-pagSpeed="1000" data-owl-autoplay="false" data-owl-navigation="true" data-owl-pagination="false" data-owl-items="4" data-owl-itemsDesktop="4" data-owl-itemsSmallDesktop="4" data-owl-itemsSmallDesktop="3" data-owl-itemsTablet="2" data-owl-itemsMobile="2" data-owl-itemsMobileSmall="1">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li class="item">';
					$output .= '<article class="portEntry wt_portofolio_item  col-lg-12 col-md-12 col-sm-12"><div class="wt_portofolio_container">';
					$output .= '<header class="wt_image_frame">';
					$output .= '<span class="wt_image_holder wt_blackwhite">';
					//$output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
					
					if (has_post_thumbnail() ){
						$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
						$portfimg = wt_get_image_src($image_src_array[0]);
						$output .= '<img src="'. $portfimg .'" alt="'.get_the_title().'" />';
						//$output .= get_the_post_thumbnail(get_the_ID(),'portfThumb', array(265,170),array('title'=>get_the_title(),'alt'=>get_the_title()));		
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="265" height="170" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '<a class="wt_hover_link mfp-image" href="' . get_permalink() . '" title="' . get_the_title() . '"><span><i class="fa fa-link"></i></span></a>';
					$output .= '<a class="wt_hover_view mfp-image" href="#" title="' . get_the_title() . '"><span><i class="fa fa-search"></i></span></a>';
					//$output .= '</a>';
					$output .= '</span>';
					$output .= '</header>';
					$output .= '<div class="wt_portofolio_details">';
					$output .= '<h4 class="wt_portfolio_title"><a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a></h4>';
					$output .= '<div class="wt_portofolio_det"><p>'.get_the_date().'<p></div>';
					$output .= '</div>';
					$output .= '<div class="wt_clearboth"></div>';
					$output .= '</div></article>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</div>';
			}
			$post = $backup;
		}
		if(!$related_post_found){
			$r = new WP_Query(array(
				'post_type' => 'wt_portfolio', 
				//'showposts' => 3, 
				'nopaging' => 0, 
				'post_status' => 'publish', 
				'ignore_sticky_posts' => 1
			));
			if ($r->have_posts()){
				$output .= '<div class="wt_portfolio_wrapper_carousel">';
				$output .= '<ul class="wt_owl_carousel posts portfList" data-owl-speed="600" data-owl-pagSpeed="1000" data-owl-autoplay="false" data-owl-navigation="true" data-owl-pagination="false" data-owl-items="4" data-owl-itemsDesktop="4" data-owl-itemsSmallDesktop="4" data-owl-itemsSmallDesktop="3" data-owl-itemsTablet="2" data-owl-itemsMobile="2" data-owl-itemsMobileSmall="1">';
				while ($r->have_posts()){
					$r->the_post();
					$output .= '<li class="item">';
					$output .= '<article class="portEntry wt_portofolio_item  col-lg-12 col-md-12 col-sm-12"><div class="wt_portofolio_container">';
					$output .= '<header class="wt_image_frame">';
					$output .= '<span class="wt_image_holder wt_blackwhite">';
					//$output .= '<a href="'.get_permalink().'" title="'.get_the_title().'">';
					
					if (has_post_thumbnail() ){
						$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
						$portfimg = wt_get_image_src($image_src_array[0]);
						$output .= '<img src="'. $portfimg .'" alt="'.get_the_title().'" />';
						//$output .= get_the_post_thumbnail(get_the_ID(),'portfThumb', array(265,170),array('title'=>get_the_title(),'alt'=>get_the_title()));		
					}else{
						$output .= '<img src="'.THEME_IMAGES.'/widget_posts_thumbnail.png" width="265" height="170" title="'.get_the_title().'" alt="'. get_the_title().'"/>';
					}
					$output .= '<a class="wt_hover_link mfp-image" href="' . get_permalink() . '" title="' . get_the_title() . '"><span><i class="fa fa-link"></i></span></a>';
					$output .= '<a class="wt_hover_view mfp-image" href="#" title="' . get_the_title() . '"><span><i class="fa fa-search"></i></span></a>';
					//$output .= '</a>';
					$output .= '</span>';
					$output .= '</header>';
					$output .= '<div class="wt_portofolio_details">';
					$output .= '<h4 class="wt_portfolio_title"><a class="post_title" href="'.get_permalink().'" title="'.get_the_title().'" rel="bookmark">'.get_the_title().'</a></h4>';
					$output .= '<div class="wt_portofolio_det"><p>'.get_the_date().'<p></div>';
					$output .= '</div>';
					$output .= '<div class="wt_clearboth"></div>';
					$output .= '</div></article>';
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</div>';
			}
		}
		wp_reset_postdata();

		echo $output;
	}
	
	function wt_staticImage($type) {
		if (has_post_thumbnail() ) {
			$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
			$featured_image = wt_get_image_src($image_src_array[0]);		
		}
		if(!empty($featured_image) && $type == 'static_image'){
			$staticImg = $featured_image;
		} else {
			$staticImg= wt_get_option('introheader','static_image');
		}
		$width = 980;
		
		$static_adaptive_height = wt_get_option('introheader', 'static_adaptive_height');
		if($static_adaptive_height){
			$height = floor($width*($image_src_array[2]/$image_src_array[1]));
		}else{
			$height = wt_get_option('introheader', 'static_image_height');
		}
		
		$lightbox = '<a href="'.$staticImg.'" data-rel="lightbox" title="'. get_the_title() .'">';
		$image_src = aq_resize( $staticImg, $width, $height, true ); //resize & crop img
					
?>
	<header id="wt_intro">
		<div class="container">
        	<div class="intro_staticImage">
                <?php echo wt_get_option('introheader', 'static_image_lightbox') ? $lightbox : ''; ?>
				<img src="<?php echo $image_src; ?>" alt="<?php the_title(); ?>" />
                <?php echo wt_get_option('introheader', 'static_image_lightbox') ? '</a>' : ''; ?>
			</div>
		</div>
	</header>
<?php		
	}
	
	function wt_staticVideo($type) {
		global $post;
		if($type == 'static_video'){
			if (get_post_meta($post->ID, '_featured_video', true)) {
				$featured_video = wt_check_input(get_post_meta($post->ID, '_featured_video', true));	
			}	
		}
?>
	<header id="wt_intro">
		<div class="container">
        	<div class="intro_staticVideo">
				<?php 
				echo wt_video_featured($wt_video_featured, '', '', 720, 1280);
				?>
			</div>
		</div>
	</header>
<?php		
	}		
}// End class themeFeatures

function wt_theme_generator($function){
	global $_wt_themeFeatures;
	$_wt_themeFeatures = new wt_themeFeatures;
	$args = array_slice( func_get_args(), 1 );
	return call_user_func_array(array( &$_wt_themeFeatures, $function ), $args );
}