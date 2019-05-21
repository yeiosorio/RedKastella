<?php 			
	/* google fonts settings */
	$googlefonts = wt_get_option('fonts');	
	$googlefonts_css = '';		
	$used_gfonts = wt_get_option('fonts','used_googlefonts');			
	$gfont_str = '';
	if(is_array($used_gfonts)){
		foreach($used_gfonts as $font){
			$gfont_str = $font;
		}
		
		$gfont_info = explode(":", $gfont_str);
		
		if($googlefonts['enable_googlefonts']){
			$custom_code = stripslashes(wt_get_option('fonts','gfonts_code'));
			
			if(trim($custom_code) == ''){
				$googlefonts_css .=  <<<CSS
h1, h2, h3, h4, h5, #wt_intro p, #logo, .dropcap1, .dropcap2, .dropcap3, .custom_links span, .pp_description, #nav a.level-1-a {
	font-family: '{$gfont_info[0]}';
}
CSS;
			}
			$googlefonts_css .= $custom_code;
		}
	}
	if($googlefonts['enable_googlefonts']){
		$googlefonts_code =  <<<CSS
	.wt_contactInfo p { font-weight: 500; }
	.wt_title h1, .wt_title h2, .wt_title h3, .wt_title h4, .wt_title h5, .wt_title h6 { font-weight: 400; }
	#wt_sidebar .widgettitle, .wt_testimonial_autor h4, .intro_box_alt h2 { font-weight: 600; }
	.intro_text h1, .wt_title.wt_heading_3 h1, .wt_title.wt_heading_3 h2, .wt_title.wt_heading_3 h3, #wt_footerWrapper .widgettitle, .tagcloud a, .intro_box h1, .intro_box h2, .wt_bxslider blockquote, .accordion-heading .wt_toggle_title, .wt_acc_title, .wt_portofolio_details h4 { font-weight: 600; }
	.wt_testimonial_autor h4 a, .post-meta li .post-meta-key, .aboutTheAuthor_content h4 a, .wt_button.bold span { font-weight: 700; }
CSS;
	}
	/* font face settings */
	$fontface = wt_get_option('fonts');
	$fontface_css = '';
	if($fontface['enable_fontface']){
		if(is_array($fontface['fonts'])){
			foreach ($fontface['fonts'] as $font_str){
				$font_info = explode("|", $font_str);
				$stylesheet = THEME_FONTFACE_DIR.'/'.$font_info[0].'/stylesheet.css';
				if(file_exists($stylesheet)){
					$file_content = file_get_contents($stylesheet);
					if( preg_match("/@font-face\s*{[^}]*?font-family\s*:\s*('|\")$font_info[1]\\1.*?}/is", $file_content, $match) ){
						$fontface_css .= preg_replace("/url\s*\(\s*['|\"]\s*/is","\\0../font-faces/$font_info[0]/",$match[0])."\n";
					}
				}
			}
		}
		
		$code = stripslashes(wt_get_option('fonts','fontface_code'));
		if(trim($code) == '' && isset($font_info[1])){
			$code =  <<<CSS
h1, h2, h3, h4, h5, #wt_intro p, #logo, .dropcap1, .dropcap2, .dropcap3, .custom_links span, .pp_description, #nav a {
	font-family: {$font_info[1]};
}
CSS;
		}
		$fontface_css .= $code;
	}

	
	/* Custom Skin */	
			
	$general = wt_get_option('general');		
	if(!empty($general['custom_skin'])){
		$rgbEq = wt_hex2rgb($general['custom_skin']);
		$custom_skin = <<<CSS
/* Custom Skin */

a:not(.wt_button),
a:not(.wt_button):hover,
.wt_skin_color,
#nav.wt_nav_top ul li.active > a, 
#nav.wt_nav_top ul li.current_page_item > a,
#nav.wt_nav_top ul li:hover > a,
#nav ul li .mega-menu-widget a:hover,
.wt_services:hover .wt_icon,
#breadcrumbs .breadcrumbs a,
#breadcrumbs .breadcrumbs-separator,
#wt_footer .widget a:hover,
#copyright .copyright span,
#flexSlider .flex-caption .wt_button.button_slide:hover,
#nivo_slider_wrap .nivo-caption .wt_button.button_slide:hover,
#cycle_slider .cycle-overlay .wt_button.button_slide:hover,
.wt_styled_list li:before,
.widget_calendar a:hover,
#wp-calendar caption, 
#wp-calendar th,
.wt_postInfo a:hover,
ul.wt_tabs li a.current,
ul.wt_tabs li a.current i,
#wt_footerWrapper ul.wt_tabs li a.current,
.wt_faq_row h2,
.wt_services_buttons a,
.wt_services_slider h3,
#wt_container .wt_services_slider h3 a,
.wt_testimonials_slider_sc .wt_bxslider li h3,
#wt_container .wt_testimonials_slider_sc .wt_bxslider li h3 a,
.wt_counter_sc,
.blogEntry .entry_date .day,
.blogEntry .entry_date span,
.wt_service_box_sc .wt_icon_type_1.wt_icon_default,
.wt_service_box_sc .wt_icon_type_3.wt_icon_default,
.blogEntry_metadata div:hover,
#wt_sidebar .widget li a:hover, .widget li a:hover, 
#wt_footerWrapper .widget li .wt_postInfo a:hover, 
.widget_content ul.arrows_list1 li:hover i, 
.widget_content ul.archives_list li:hover i {
	color: {$general['custom_skin']}; }
.home #nav.wt_nav_top ul li:hover > a,
.home #nav.wt_nav_top ul li.current_page_item > a,
.wt_services .wt_icon,
.sortableLinks a:hover,
.sortableLinks a.selected,
#wt_footerTop,
#wt-top:hover,
#wt-top:active,
#nivo_slider_wrap .nivo-controlNav a,
.cycle-pager span,
.tparrows:hover,
.flex-control-paging li a.flex-active,
.nivo-controlNav a,
h6.wt_framed_box_title,
.error_page .wt_button,
a.read_more:hover,
.wp-pagenavi > .active,
.wp-pagenavi > .active > span,
.wp-pagenavi > .active > a:hover,
.wp-pagenavi > .active > span:hover,
.wp-pagenavi > .active > a:focus,
.wp-pagenavi > .active > span:focus,
.wp-pagenavi .currentPosts,
.blogEntry a.read_more_link,
.form.mc4wp-form input[type="submit"],
.wpcf7 input[type="submit"],
#today, #wt_containerWrapp #today,
.wt_search_form .wt_search_go,
.tagcloud a:hover,
#wt_footerWrapper .tagcloud a:hover,
.widget_categories ul li:hover span,
.overlay,
.comment_wrap .gravatar img:hover,
.comment_wrap .gravatar:hover:after,
.contact_button,
.wt_flickrWrap div .hover,
.btn-theme,
.form.mc4wp-form input[type="submit"]:hover,
.wpcf7 input[type="submit"]:hover,
.wt_team_info,
.wt_services_buttons a.active,
.wt_testimonials_slider_sc .bx-pager.bx-default-pager a:hover,
.wt_testimonials_slider_sc .bx-pager.bx-default-pager a.active,
.blogEntry .wt_dates:hover,
.wt_blog_grid_sc .time,
.wt_service_box_sc .wt_icon_type_2.wt_icon_default,
.wt_service_box_sc:hover .wt_icon_type_3.wt_icon_default,
.wt_pricing_box_sc.wt_pricing_featured h3,
.wt_pricing_box_sc.wt_pricing_featured.wt_price_has_icon .wt_icon,
.wt_pricing_box_sc.wt_pricing_featured ul,
.wt_pricing_box_sc.wt_pricing_featured .wt_pricing_btn,
.wt_image_zoom,
.wt_icon_lightbox,
.wt_team_info,
.btn-primary, .btn-primary, .btn-primary, .btn-primary.active, .open .dropdown-toggle.btn-primary,
.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span, 
.img_blog.owl-carousel.owl-theme .owl-controls .owl-page.active span, 
.img_blog.owl-carousel.owl-theme .owl-controls .owl-page span:hover,
.woocommerce-page a.button.wc-backward,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range, 
.woocommerce-page .widget_price_filter .ui-slider .ui-slider-range,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, 
.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle:last-child, 
.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle:last-child {
	background-color: {$general['custom_skin']}; }	
.wt_skin_bg_color,
#cancel-comment-reply-link:hover, 
#cancel-comment-reply-link:focus, 
#cancel-comment-reply-link:active,
.wt_search_form .sb-search-submit:hover,
.woocommerce .woocommerce-message:before, 
.woocommerce-page .woocommerce-message:before,
.woocommerce span.onsale, .woocommerce-page span.onsale,
.woocommerce button.button.alt,
.woocommerce-page button.button.alt,
.woocommerce input.button.alt,
.woocommerce-page input.button.alt,
.woocommerce button.button:hover,
.woocommerce-page button.button:hover,
.woocommerce input.button:hover,
.woocommerce-page input.button:hover,
.nicescroll-rails div {
	background-color: {$general['custom_skin']} !important; }
.woocommerce-page a.button:hover,
.woocommerce-page .price,
.woocommerce .star-rating, .woocommerce-page .star-rating {
	color: {$general['custom_skin']} !important; }
.wt_skin_border_color,
#cancel-comment-reply-link:hover, 
#cancel-comment-reply-link:focus, 
#cancel-comment-reply-link:active,
a.read_more:hover,
.wp-pagenavi > .active,
.wp-pagenavi > .active > span,
.wp-pagenavi > .active > a:hover,
.wp-pagenavi > .active > span:hover,
.wp-pagenavi > .active > a:focus,
.wp-pagenavi > .active > span:focus,
.wp-pagenavi .currentPosts,
.avatar:hover img,
.form.mc4wp-form input[type="submit"]:hover,
.wt_services_slider h3,
.wt_service_box_sc .wt_icon_type_3.wt_icon_default,
.btn-primary, .btn-primary, .btn-primary,
.btn-primary:hover, .btn-primary:focus, 
.btn-primary:active, .btn-primary.active, 
.open .dropdown-toggle.btn-primary {
	border-color: {$general['custom_skin']}; }
.woocommerce .woocommerce-message, 
.woocommerce-page .woocommerce-message,
.woocommerce button.button.alt,
.woocommerce-page button.button.alt,
.woocommerce input.button.alt,
.woocommerce-page input.button.alt,
.woocommerce button.button:hover,
.woocommerce-page button.button:hover,
.woocommerce input.button:hover,
.woocommerce-page input.button:hover,
.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, 
.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle {
	border-color: {$general['custom_skin']} !important; }
.wt_skin_border_left_color,
.wt_services_buttons a,
.wt_blog_grid_sc .blogEntry:before,
.wt_blog_grid_sc .wt_dates {
	border-left-color: {$general['custom_skin']}; }
.wt_skin_border_right_color,
.wt_services_buttons a,
.wt_blog_grid_sc .blogEntry:before,
.wt_blog_grid_sc .wt_dates {
	border-right-color: {$general['custom_skin']}; }
.wt_skin_border_top_color,
ul.wt_tabs li a.current {
	border-top-color: {$general['custom_skin']}; }
.wt_skin_border_bottom_color,
#nav ul ul.sub-menu, 
#nav ul ul.sub-menu li ul.sub-menu,
#nav ul ul.children, 
#nav ul ul.children li ul.children,
.wt_pricing_box_sc.wt_pricing_featured .wt_pricing_header {
	border-bottom-color: {$general['custom_skin']}; }
.row-fluid textarea:focus, .row-fluid input:focus {
    border-color: {$general['custom_skin']} !important; }
.wt_pricing_box_sc.wt_pricing_featured .wt_plan_price {
	border-bottom-color: {$general['custom_skin']}; }
.wt_pricing_box_sc.wt_pricing_featured .wt_plan_price,
.btn-theme:hover,
.owl-theme .owl-controls .owl-page.active span, .owl-theme .owl-controls.clickable .owl-page:hover span, .img_blog.owl-carousel.owl-theme .owl-controls .owl-page.active span, .img_blog.owl-carousel.owl-theme .owl-controls .owl-page span:hover,
.wt_image_zoom:hover, .wt_image_zoom:focus {
	background: {$general['custom_skin']}; }
.wt_pricing_box_sc.wt_pricing_featured ul li,
.wt_pricing_box_sc.wt_pricing_featured .wt_plan_price,
.wt_pricing_box_sc.wt_pricing_featured .wt_pricing_btn {
	border-top-color: {$general['custom_skin']}; }
.wt_contact_form_wrap .contact_button:hover {
	background: rgba({$rgbEq}, 0.6); }
.wt_color_overlay,
.btn-primary:hover, .btn-primary:focus, 
.btn-primary:active, .btn-primary.active, 
.open .dropdown-toggle.btn-primary {
	background: rgba({$rgbEq}, 0.8); }
.cta-btn {
	color: #fff !important;
	background: rgba(0,0,0, 0.1); }
.cta-btn:hover {
	background: rgba(0,0,0, 0.2); }
.wt_loader_html {
	background-image: url("../img/loader_skin.gif"); }  
	
	
/* End Custom Skin */	
CSS;
	}else{
		$custom_skin = '';
	}
	
	/* background settings */
	$background = wt_get_option('background');
	if(!empty($background['pattern_bg'])){
		$pattern_image = <<<CSS
	background-image: url('{$background['pattern_bg']}');
	background-repeat: {$background['pattern_repeat']};
	background-position: top {$background['pattern_position_x']};
	background-attachment: fixed
CSS;
	}else{
		$pattern_image = '';
	}
	
	
	if(!empty($background['image_bg'])){
		$parallax_image = <<<CSS
	background: url('{$background['image_bg']}') no-repeat center center fixed; 
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
CSS;
	}else{
		$parallax_image = '';
	}
	
	if(!empty($background['header_bg'])){
		$header_image = <<<CSS
	background-image: url('{$background['header_bg']}');
	background-repeat: {$background['header_repeat']};
	background-position: top {$background['header_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$header_image = '';
	}
	
	if(!empty($background['nav_bg'])){
		$nav_image = <<<CSS
	background-image: url('{$background['nav_bg']}');
	background-repeat: {$background['nav_repeat']};
	background-position: top {$background['nav_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$nav_image = '';
	}
	
	if(!empty($background['header_left_bg'])){
		$topLeft_image = <<<CSS
	background-image: url('{$background['header_left_bg']}');
	background-repeat: {$background['header_left_repeat']};
	background-position: top {$background['header_left_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$topLeft_image = '';
	}
	
	if(!empty($background['content_bg'])){
		$content_image = <<<CSS
	background-image: url('{$background['content_bg']}');
	background-repeat: {$background['content_repeat']};
	background-position: top {$background['content_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$content_image = '';
	}
	
	if(!empty($background['footer_top_bg'])){
		$footer_top_image = <<<CSS
	background-image: url('{$background['footer_top_bg']}');
	background-repeat: {$background['footer_top_repeat']};
	background-position: top {$background['footer_top_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$footer_top_image = '';
	}
	
	if(!empty($background['footer_bg'])){
		$footer_image = <<<CSS
	background-image: url('{$background['footer_bg']}');
	background-repeat: {$background['footer_repeat']};
	background-position: top {$background['footer_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$footer_image = '';
	}
	
	if(!empty($background['footer_bottom_bg'])){
		$footer_bottom_image = <<<CSS
	background-image: url('{$background['footer_bottom_bg']}');
	background-repeat: {$background['footer_bottom_repeat']};
	background-position: top {$background['footer_bottom_position_x']};
	background-attachment: scroll
CSS;
	}else{
		$footer_bottom_image = '';
	}
		
/* Sections
========================================================== */
	if(!empty($background['section_1_bg_image'])){
		$section_1_bg_image = <<<CSS
	background-image: url('{$background['section_1_bg_image']}');
CSS;
	}else{
		$section_1_bg_image = '';
	}
	if(!empty($background['section_1_color'])){
		$section_1_color = <<<CSS
	background-color: {$background['section_1_color']};
CSS;
	}else{
		$section_1_color = '';
	}
	if(!empty($background['section_1_border_color'])){
		$section_1_border_color = <<<CSS
	border-color: {$background['section_1_border_color']};
CSS;
	}else{
		$section_1_border_color = '';
	}
	
	if(!empty($background['section_2_bg_image'])){
		$section_2_bg_image = <<<CSS
	background-image: url('{$background['section_2_bg_image']}');
CSS;
	}else{
		$section_2_bg_image = '';
	}
	if(!empty($background['section_2_color'])){
		$section_2_color = <<<CSS
	background-color: {$background['section_2_color']};
CSS;
	}else{
		$section_2_color = '';
	}
	if(!empty($background['section_2_border_color'])){
		$section_2_border_color = <<<CSS
	border-color: {$background['section_2_border_color']};
CSS;
	}else{
		$section_2_border_color = '';
	}
	
	if(!empty($background['section_3_bg_image'])){
		$section_3_bg_image = <<<CSS
	background-image: url('{$background['section_3_bg_image']}');
CSS;
	}else{
		$section_3_bg_image = '';
	}
	if(!empty($background['section_3_color'])){
		$section_3_color = <<<CSS
	background-color: {$background['section_3_color']};
CSS;
	}else{
		$section_3_color = '';
	}
	if(!empty($background['section_3_border_color'])){
		$section_3_border_color = <<<CSS
	border-color: {$background['section_3_border_color']};
CSS;
	}else{
		$section_3_border_color = '';
	}
	
	if(!empty($background['section_4_bg_image'])){
		$section_4_bg_image = <<<CSS
	background-image: url('{$background['section_4_bg_image']}');
CSS;
	}else{
		$section_4_bg_image = '';
	}
	if(!empty($background['section_4_color'])){
		$section_4_color = <<<CSS
	background-color: {$background['section_4_color']};
CSS;
	}else{
		$section_4_color = '';
	}
	if(!empty($background['section_4_border_color'])){
		$section_4_border_color = <<<CSS
	border-color: {$background['section_4_border_color']};
CSS;
	}else{
		$section_4_border_color = '';
	}	
	
	if(!empty($background['section_5_bg_image'])){
		$section_5_bg_image = <<<CSS
	background-image: url('{$background['section_5_bg_image']}');
CSS;
	}else{
		$section_5_bg_image = '';
	}
	if(!empty($background['section_5_color'])){
		$section_5_color = <<<CSS
	background-color: {$background['section_5_color']};
CSS;
	}else{
		$section_5_color = '';
	}
	if(!empty($background['section_5_border_color'])){
		$section_5_border_color = <<<CSS
	border-color: {$background['section_5_border_color']};
CSS;
	}else{
		$section_5_border_color = '';
	}
	
	if(!empty($background['section_6_bg_image'])){
		$section_6_bg_image = <<<CSS
	background-image: url('{$background['section_6_bg_image']}');
CSS;
	}else{
		$section_6_bg_image = '';
	}
	if(!empty($background['section_6_color'])){
		$section_6_color = <<<CSS
	background-color: {$background['section_6_color']};
CSS;
	}else{
		$section_6_color = '';
	}
	if(!empty($background['section_6_border_color'])){
		$section_6_border_color = <<<CSS
	border-color: {$background['section_6_border_color']};
CSS;
	}else{
		$section_6_border_color = '';
	}
	
	if(!empty($background['section_7_bg_image'])){
		$section_7_bg_image = <<<CSS
	background-image: url('{$background['section_7_bg_image']}');
CSS;
	}else{
		$section_7_bg_image = '';
	}
	if(!empty($background['section_7_color'])){
		$section_7_color = <<<CSS
	background-color: {$background['section_7_color']};
CSS;
	}else{
		$section_7_color = '';
	}
	if(!empty($background['section_7_border_color'])){
		$section_7_border_color = <<<CSS
	border-color: {$background['section_7_border_color']};
CSS;
	}else{
		$section_7_border_color = '';
	}
	
	if(!empty($background['section_8_bg_image'])){
		$section_8_bg_image = <<<CSS
	background-image: url('{$background['section_8_bg_image']}');
CSS;
	}else{
		$section_8_bg_image = '';
	}
	if(!empty($background['section_8_color'])){
		$section_8_color = <<<CSS
	background-color: {$background['section_8_color']};
CSS;
	}else{
		$section_8_color = '';
	}
	if(!empty($background['section_8_border_color'])){
		$section_8_border_color = <<<CSS
	border-color: {$background['section_8_border_color']};
CSS;
	}else{
		$section_8_border_color = '';
	}
	
	if(!empty($background['section_9_bg_image'])){
		$section_9_bg_image = <<<CSS
	background-image: url('{$background['section_9_bg_image']}');
CSS;
	}else{
		$section_9_bg_image = '';
	}
	if(!empty($background['section_9_color'])){
		$section_9_color = <<<CSS
	background-color: {$background['section_9_color']};
CSS;
	}else{
		$section_9_color = '';
	}
	if(!empty($background['section_9_border_color'])){
		$section_9_border_color = <<<CSS
	border-color: {$background['section_9_border_color']};
CSS;
	}else{
		$section_9_border_color = '';
	}
	
	if(!empty($background['section_10_bg_image'])){
		$section_10_bg_image = <<<CSS
	background-image: url('{$background['section_10_bg_image']}');
CSS;
	}else{
		$section_10_bg_image = '';
	}
	if(!empty($background['section_10_color'])){
		$section_10_color = <<<CSS
	background-color: {$background['section_10_color']};
CSS;
	}else{
		$section_10_color = '';
	}
	if(!empty($background['section_10_border_color'])){
		$section_10_border_color = <<<CSS
	border-color: {$background['section_10_border_color']};
CSS;
	}else{
		$section_10_border_color = '';
	}
	
		
	/* color settings */
	$color = wt_get_option('color');	
	
	if($color['content_h1']==''){
		$color['content_h1']=$color['content_header'];
	}
	if($color['content_h2']==''){
		$color['content_h2']=$color['content_header'];
	}
	if($color['content_h3']==''){
		$color['content_h3']=$color['content_header'];
	}
	if($color['content_h4']==''){
		$color['content_h4']=$color['content_header'];
	}
	if($color['content_h5']==''){
		$color['content_h5']=$color['content_header'];
	}
	if($color['content_h6']==''){
		$color['content_h6']=$color['content_header'];
	}			
	
	if( !empty($background['section_1_bg_image']) || !empty($background['section_1_color']) || !empty($background['section_1_border_color']) ){
		$section_1_css = <<<CSS
.wt_section_1 {	
{$section_1_bg_image}
{$section_1_color}
{$section_1_border_color}
}
CSS;
	} else {
		$section_1_css = '';
	}
		
	if( !empty($background['section_2_bg_image']) || !empty($background['section_2_color']) || !empty($background['section_2_border_color']) ){
		$section_2_css = <<<CSS
.wt_section_2 {	
{$section_2_bg_image}
{$section_2_color}
{$section_2_border_color}
}
CSS;
	} else {
		$section_2_css = '';
	}
		
	if( !empty($background['section_3_bg_image']) || !empty($background['section_3_color']) || !empty($background['section_3_border_color']) ){
		$section_3_css = <<<CSS
.wt_section_3 {	
{$section_3_bg_image}
{$section_3_color}
{$section_3_border_color}
}
CSS;
	} else {
		$section_3_css = '';
	}
		
	if( !empty($background['section_4_bg_image']) || !empty($background['section_4_color']) || !empty($background['section_4_border_color']) ){
		$section_4_css = <<<CSS
.wt_section_4 {	
{$section_4_bg_image}
{$section_4_color}
{$section_4_border_color}
}
CSS;
	} else {
		$section_4_css = '';
	}
		
	if( !empty($background['section_5_bg_image']) || !empty($background['section_5_color']) || !empty($background['section_5_border_color']) ){
		$section_5_css = <<<CSS
.wt_section_5 {	
{$section_5_bg_image}
{$section_5_color}
{$section_5_border_color}
}
CSS;
	} else {
		$section_5_css = '';
	}
		
	if( !empty($background['section_6_bg_image']) || !empty($background['section_6_color']) || !empty($background['section_6_border_color']) ){
		$section_6_css = <<<CSS
.wt_section_6 {	
{$section_6_bg_image}
{$section_6_color}
{$section_6_border_color}
}
CSS;
	} else {
		$section_6_css = '';
	}
		
	if( !empty($background['section_7_bg_image']) || !empty($background['section_7_color']) || !empty($background['section_7_border_color']) ){
		$section_7_css = <<<CSS
.wt_section_7 {	
{$section_7_bg_image}
{$section_7_color}
{$section_7_border_color}
}
CSS;
	} else {
		$section_7_css = '';
	}
		
	if( !empty($background['section_8_bg_image']) || !empty($background['section_8_color']) || !empty($background['section_8_border_color']) ){
		$section_8_css = <<<CSS
.wt_section_8 {	
{$section_8_bg_image}
{$section_8_color}
{$section_8_border_color}
}
CSS;
	} else {
		$section_8_css = '';
	}
		
	if( !empty($background['section_9_bg_image']) || !empty($background['section_9_color']) || !empty($background['section_9_border_color']) ){
		$section_9_css = <<<CSS
.wt_section_9 {	
{$section_9_bg_image}
{$section_9_color}
{$section_9_border_color}
}
CSS;
	} else {
		$section_9_css = '';
	}
		
	if( !empty($background['section_10_bg_image']) || !empty($background['section_10_color']) || !empty($background['section_10_border_color']) ){
		$section_10_css = <<<CSS
.wt_section_10 {	
{$section_10_bg_image}
{$section_10_color}
{$section_10_border_color}
}
CSS;
	} else {
		$section_10_css = '';
	}
	
	/* section settings */	
	$sections_css = '';
	$sections_css .=  <<<CSS
{$section_1_css}
{$section_2_css}
{$section_3_css}
{$section_4_css}
{$section_5_css}
{$section_6_css}
{$section_7_css}
{$section_8_css}
{$section_9_css}
{$section_10_css}
CSS;

	/* blog settings */
	$posts_gap = wt_get_option('blog', 'posts_gap');
	
	/* slideshows settings */
	$height_anything = wt_get_option('slideshow', 'anything_height');
	$anything_caption_height = $height_anything-40;
	$top_controlNav_anything= $height_anything+15;	
		
	/* font size settings */
	$font = wt_get_option('fonts');
	$font['font_family']=stripslashes($font['font_family']);
	
	/* menu settings */	
	$menu_css = '';
	if(wt_get_option('general','menu_alignment')== 'right'){
		$menu_css .=  <<<CSS
#nav {
	float: right;
}
CSS;
	}		
	
	/* responsive settings */	
	$responsive_css = '';
	if(wt_get_option('general','enable_responsive')){
		$responsive_css .=  <<<CSS
#wt_page {
	/*overflow: hidden;*/
}
CSS;
	}
	
	/* non responsive settings */	
	$non_responsive_css = '';
	if(!wt_get_option('general','enable_responsive')){
		$non_responsive_css .=  <<<CSS
#wt_wrapper {
	min-width: 1000px;
}
CSS;
	}	

	/* custom css */
	$custom_css = wt_get_option('general','custom_css');

	/* Css output */
	return <<<CSS
body {
	font-size: {$font['content_page']}px;
	font-family: {$font['font_family']};
	color: {$color['page_content']};	
}
{$non_responsive_css}
{$responsive_css}	
{$fontface_css}
{$googlefonts_css}
{$sections_css}	
{$custom_skin}
#anything_slider_wrap,
#anything_slider_loading, 
#anything_slider {
	height: {$height_anything}px;
}
.caption_left, .caption_right {
	height: {$anything_caption_height}px;
}
#anything_slider .anything_sidebar_content {
	height: {$anything_caption_height}px;
}
body.wt_pattern {	
	background-color: {$background['pattern_bg_color']};
{$pattern_image}		
}
body.wt_image_bg {	
{$parallax_image}		
}
#wt_header,
#stickyHeaderBg,
.sticky-wrapper #wt_header {
	background-color: {$background['header_bg_color']};
{$header_image}
}
#topWidgetWrapper {
	background-color: {$background['header_bg_color']};
}
#wt_footerTop {	
	background-color: {$background['footer_top_color']};
{$footer_top_image}
}
#wt_footer {	
	background-color: {$background['footer_color']};
{$footer_image}
}
#wt_footerBottom {	
	background-color: {$background['footer_bottom_color']};
{$footer_bottom_image}
}

h1, h2, h3, h4, h5, h6 {
	color: {$color['content_header']};
}

h1 {
	font-size: {$font['content_h1']}px;
	color: {$color['content_h1']};
}
h2 {
	font-size: {$font['content_h2']}px;
	color: {$color['content_h2']};
}
h3 {
	font-size: {$font['content_h3']}px;
	color: {$color['content_h3']};
}
h4 {
	font-size: {$font['content_h4']}px;
	color: {$color['content_h4']};
}
h5 {
	font-size: {$font['content_h5']}px;
	color: {$color['content_h5']};
}
h6 {
	font-size: {$font['content_h6']}px;
	color: {$color['content_h6']};
}
#logo_text a {
	color: {$color['logo_color']};
	font-size: {$font['logo_size']}px;
}
#siteDescription {
	color: {$color['logo_color_desc']};
	font-size: {$font['logo_size_desc']}px;
}
{$menu_css}
#nav.wt_nav_top ul li a,
#nav.wt_nav_side ul li a {
	color: {$color['menu_top']};
	font-size: {$font['menu_top']}px;
}
#nav ul li > a:hover,
#nav ul > li:hover > a {
	color: {$color['menu_top_hover']};
}
#nav ul li.current_page_item a.level-1-a, 
#nav ul li.current_page_item.level-2-li a.level-2-a,
#nav ul li.current_page_item.level-3-li a.level-3-a, 
#nav ul li.current_page_item.level-4-li a.level-4-a,
#nav ul li.current-page-ancestor a.level-1-a,
#nav ul li.current-page-ancestor.level-2-li a.level-2-a,
#nav ul li.current-page-ancestor.level-3-li a.level-3-a,
#nav ul li.current-page-ancestor.level-4-li a.level-4-a,
#nav ul li.current_page_ancestor a.level-1-a,
#nav ul li.current_page_ancestor.level-2-li a.level-2-a,
#nav ul li.current_page_ancestor.level-3-li a.level-3-a,
#nav ul li.current_page_ancestor.level-4-li a.level-4-a,
#nav ul li.current-menu-ancestor a.level-1-a {
	color: {$color['menu_top_current']};
	border-bottom-color: {$color['menu_top_current']};
}
#nav ul ul a,
#nav ul ul.sub-menu li a, 
#nav ul ul.children li a,
#nav.wt_nav_side ul ul a, 
#nav.wt_nav_side ul ul.sub-menu li a, 
#nav.wt_nav_side ul ul.children li a {
	color: {$color['menu_sub']};
	font-size: {$font['menu_sub']}px;
}
#nav ul li ul li a:hover {
	color: {$color['menu_sub_hover']} !important;
}
#copyright .copyright {
	color: {$color['copyright']};
	font-size: {$font['copyright']}px;
}
#wt_footer .widget,
#wt_footer p {
	color: {$color['footer_text']};
	font-size: {$font['footer_text']}px;
}
#wt_footerWrapper  h3.widgettitle,
#wt_footerWrapper  h4.widgettitle  {
	color: {$color['footer_title']};
	font-size: {$font['footer_title']}px;
}
#wt_sidebar .widgettitle {
	font-size: {$font['sidebar_widget_title']}px;
}
.blogEntry {
	margin-bottom:{$posts_gap}px;
}
.wt_social_wrap a.aim:hover, .wt_social_wrap a.aim_32:hover, .wt_social_wrap_alt a.aim, .wt_social_wrap_alt a.aim:hover, .wt_social_wrap_alt a.aim_32, .wt_social_wrap_alt a.aim_32:hover { 
	background-color: {$color['aim_color']}; 
}
.wt_social_wrap a.apple:hover, .wt_social_wrap a.apple_32:hover, .wt_social_wrap_alt a.apple, .wt_social_wrap_alt a.apple:hover, .wt_social_wrap_alt a.apple_32, .wt_social_wrap_alt a.apple_32:hover, .wt_social_wrap_aw a.apple:hover { 
	background-color: {$color['apple_color']}; 
}
.wt_social_wrap a.behance:hover, .wt_social_wrap a.behance_32:hover, .wt_social_wrap_alt a.behance, .wt_social_wrap_alt a.behance:hover, .wt_social_wrap_alt a.behance_32, .wt_social_wrap_alt a.behance_32:hover { 
	background-color: {$color['behance_color']}; 
}
.wt_social_wrap a.blogger:hover, .wt_social_wrap a.blogger_32:hover, .wt_social_wrap_alt a.blogger, .wt_social_wrap_alt a.blogger:hover, .wt_social_wrap_alt a.blogger_32, .wt_social_wrap_alt a.blogger_32:hover { 
	background-color: {$color['blogger_color']}; 
}
.wt_social_wrap a.delicious:hover, .wt_social_wrap a.delicious_32:hover, .wt_social_wrap_alt a.delicious, .wt_social_wrap_alt a.delicious:hover, .wt_social_wrap_alt a.delicious_32, .wt_social_wrap_alt a.delicious_32:hover { 
	background-color: {$color['delicious_color']}; 
}
.wt_social_wrap a.deviantart:hover, .wt_social_wrap a.deviantart_32:hover, .wt_social_wrap_alt a.deviantart, .wt_social_wrap_alt a.deviantart:hover, .wt_social_wrap_alt a.deviantart_32, .wt_social_wrap_alt a.deviantart_32:hover { 		
	background-color: {$color['deviantart_color']}; 
}
.wt_social_wrap a.digg:hover, .wt_social_wrap a.digg_32:hover, .wt_social_wrap_alt a.digg, .wt_social_wrap_alt a.digg:hover, .wt_social_wrap_alt a.digg_32, .wt_social_wrap_alt a.digg_32:hover { 
	background-color: {$color['digg_color']}; 
}
.wt_social_wrap a.dribbble:hover, .wt_social_wrap a.dribbble_32:hover, .wt_social_wrap_alt a.dribbble, .wt_social_wrap_alt a.dribbble:hover, .wt_social_wrap_alt a.dribbble_32, .wt_social_wrap_alt a.dribbble_32:hover, .wt_social_wrap_aw a.dribbble:hover { 
	background-color: {$color['dribbble_color']}; 
}
.wt_social_wrap a.email:hover, .wt_social_wrap a.email_32:hover, .wt_social_wrap_alt a.email, .wt_social_wrap_alt a.email:hover, .wt_social_wrap_alt a.email_32, .wt_social_wrap_alt a.email_32:hover { 
	background-color: {$color['email_color']}; 
}
.wt_social_wrap a.ember:hover, .wt_social_wrap a.ember_32:hover, .wt_social_wrap_alt a.ember, .wt_social_wrap_alt a.ember:hover, .wt_social_wrap_alt a.ember_32, .wt_social_wrap_alt a.ember_32:hover { 
	background-color: {$color['ember_color']}; 
}
.wt_social_wrap a.facebook:hover, .wt_social_wrap a.facebook_32:hover, .wt_social_wrap_alt a.facebook, .wt_social_wrap_alt a.facebook:hover, .wt_social_wrap_alt a.facebook_32, .wt_social_wrap_alt a.facebook_32:hover, .wt_social_wrap_aw a.facebook:hover { 
	background-color: {$color['facebook_color']}; 
}
.wt_social_wrap a.flickr:hover, .wt_social_wrap a.flickr_32:hover, .wt_social_wrap_alt a.flickr, .wt_social_wrap_alt a.flickr:hover, .wt_social_wrap_alt a.flickr_32, .wt_social_wrap_alt a.flickr_32:hover, .wt_social_wrap_aw a.flickr:hover { 
	background-color: {$color['flickr_color']}; 
}
.wt_social_wrap a.forrst:hover, .wt_social_wrap a.forrst_32:hover, .wt_social_wrap_alt a.forrst, .wt_social_wrap_alt a.forrst:hover, .wt_social_wrap_alt a.forrst_32, .social_wrap_alt a.forrst_32:hover { 
	background-color: {$color['forrst_color']}; 
}
.wt_social_wrap a.google:hover, .wt_social_wrap a.google_32:hover, .wt_social_wrap_alt a.google, .wt_social_wrap_alt a.google:hover, .wt_social_wrap_alt a.google_32, .wt_social_wrap_alt a.google_32:hover, .wt_social_wrap_aw a.google:hover { 
	background-color: {$color['google_color']}; 
}
.wt_social_wrap a.googleplus:hover, .wt_social_wrap a.googleplus_32:hover, .wt_social_wrap_alt a.googleplus, .wt_social_wrap_alt a.googleplus:hover, .wt_social_wrap_alt a.googleplus_32, .wt_social_wrap_alt a.googleplus_32:hover, .wt_social_wrap_aw a.google-plus:hover { 
	background-color: {$color['googleplus_color']}; 
}
.wt_social_wrap a.html5:hover, .wt_social_wrap a.html5_32:hover, .wt_social_wrap_alt a.html5, .wt_social_wrap_alt a.html5:hover, .wt_social_wrap_alt a.html5_32, .wt_social_wrap_alt a.html5_32:hover { 
	background-color: {$color['html5_color']}; 
}
.wt_social_wrap a.lastfm:hover, .wt_social_wrap a.lastfm_32:hover, .wt_social_wrap_alt a.lastfm, .wt_social_wrap_alt a.lastfm:hover, .wt_social_wrap_alt a.lastfm_32, .wt_social_wrap_alt a.lastfm_32:hover { 
	background-color: {$color['lastfm_color']}; 
}
.wt_social_wrap a.linkedin:hover, .wt_social_wrap a.linkedin_32:hover, .wt_social_wrap_alt a.linkedin, .wt_social_wrap_alt a.linkedin:hover, .wt_social_wrap_alt a.linkedin_32, .wt_social_wrap_alt a.linkedin_32:hover, .wt_wt_social_wrap_aw a.linkedin:hover { 
	background-color: {$color['linkedin_color']}; 
}
.wt_social_wrap a.metacafe:hover, .wt_social_wrap a.metacafe_32:hover, .wt_social_wrap_alt a.metacafe, .wt_social_wrap_alt a.metacafe:hover, .wt_social_wrap_alt a.metacafe_32, .wt_social_wrap_alt a.metacafe_32:hover { 
	background-color: {$color['metacafe_color']}; 
}
.wt_social_wrap a.netvibes:hover, .wt_social_wrap a.netvibes_32:hover, .wt_social_wrap_alt a.netvibes, .wt_social_wrap_alt a.netvibes:hover, .wt_social_wrap_alt a.netvibes_32, .wt_social_wrap_alt a.netvibes_32:hover  { 
	background-color: {$color['netvibes_color']}; 
}
.wt_social_wrap a.paypal:hover, .wt_social_wrap a.paypal_32:hover, .wt_social_wrap_alt a.paypal, .wt_social_wrap_alt a.paypal:hover, .wt_social_wrap_alt a.paypal_32, .wt_social_wrap_alt a.paypal_32:hover { 
	background-color: {$color['paypal_color']}; 
}
.wt_social_wrap a.picasa:hover, .wt_social_wrap a.picasa_32:hover, .wt_social_wrap_alt a.picasa, .wt_social_wrap_alt a.picasa:hover, .wt_social_wrap_alt a.picasa_32, .wt_social_wrap_alt a.picasa_32:hover { 
	background-color: {$color['picasa_color']}; 
}
.wt_social_wrap a.pinterest:hover, .wt_social_wrap a.pinterest_32:hover, .wt_social_wrap_alt a.pinterest, .wt_social_wrap_alt a.pinterest:hover, .wt_social_wrap_alt a.pinterest_32, .wt_social_wrap_alt a.pinterest_32:hover, .wt_social_wrap_aw a.pinterest:hover { 
	background-color: {$color['pinterest_color']}; 
}
.wt_social_wrap a.reddit:hover, .wt_social_wrap a.reddit_32:hover, .wt_social_wrap_alt a.reddit, .wt_social_wrap_alt a.reddit:hover, .wt_social_wrap_alt a.reddit_32, .wt_social_wrap_alt a.reddit_32:hover { 
	background-color: {$color['reddit_color']}; 
}
.wt_social_wrap a.rss:hover, .wt_social_wrap a.rss_32:hover, .wt_social_wrap_alt a.rss, .wt_social_wrap_alt a.rss:hover, .wt_social_wrap_alt a.rss_32, .wt_social_wrap_alt a.rss_32:hover, .wt_social_wrap_aw a.rss:hover { 
	background-color: {$color['rss_color']}; 
}
.wt_social_wrap a.skype:hover, .wt_social_wrap a.skype_32:hover, .wt_social_wrap_alt a.skype, .wt_social_wrap_alt a.skype:hover, .wt_social_wrap_alt a.skype_32, .wt_social_wrap_alt a.skype_32:hover, .wt_social_wrap_aw a.skype:hover { 
	background-color: {$color['skype_color']}; 
}
.wt_social_wrap a.stumbleupon:hover, .wt_social_wrap a.stumbleupon_32:hover, .wt_social_wrap_alt a.stumbleupon, .wt_social_wrap_alt a.stumbleupon:hover, .wt_social_wrap_alt a.stumbleupon_32, .wt_social_wrap_alt a.stumbleupon_32:hover { 
	background-color: {$color['stumbleupon_color']}; 
}
.wt_social_wrap a.technorati:hover, .wt_social_wrap a.technorati_32:hover, .wt_social_wrap_alt a.technorati, .wt_social_wrap_alt a.technorati:hover, .wt_social_wrap_alt a.technorati_32, .wt_social_wrap_alt a.technorati_32:hover { 
	background-color: {$color['technorati_color']}; 
}
.wt_social_wrap a.tumblr:hover, .wt_social_wrap a.tumblr_32:hover, .wt_social_wrap_alt a.tumblr, .wt_social_wrap_alt a.tumblr:hover, .wt_social_wrap_alt a.tumblr_32, .wt_social_wrap_alt a.tumblr_32:hover, .wt_wt_social_wrap_aw a.tumblr:hover { 
	background-color: {$color['tumblr_color']}; 
}
.wt_social_wrap a.twitter:hover, .wt_social_wrap a.twitter_32:hover, .wt_social_wrap_alt a.twitter, .wt_social_wrap_alt a.twitter:hover, .wt_social_wrap_alt a.twitter_32, .wt_social_wrap_alt a.twitter_32:hover, .wt_wt_social_wrap_aw a.twitter:hover { 
	background-color: {$color['twitter_color']}; 
}
.wt_social_wrap a.vimeo:hover, .wt_social_wrap a.vimeo_32:hover, .wt_social_wrap_alt a.vimeo, .wt_social_wrap_alt a.vimeo:hover, .wt_social_wrap_alt a.vimeo_32, .wt_social_wrap_alt a.vimeo_32:hover { 
	background-color: {$color['vimeo_color']}; 
}
.wt_social_wrap a.wordpress:hover, .wt_social_wrap a.wordpress_32:hover, .wt_social_wrap_alt a.wordpress, .wt_social_wrap_alt a.wordpress:hover, .wt_social_wrap_alt a.wordpress_32, .wt_social_wrap_alt a.wordpress_32:hover { 
	background-color: {$color['wordpress_color']}; 
}
.wt_social_wrap a.yahoo:hover, .wt_social_wrap a.yahoo_32:hover, .wt_social_wrap_alt a.yahoo, .wt_social_wrap_alt a.yahoo:hover, .wt_social_wrap_alt a.yahoo_32, .swt_ocial_wrap_alt a.yahoo_32:hover { 
	background-color: {$color['yahoo_color']}; 
}
.wt_social_wrap a.yelp:hover, .wt_social_wrap a.yelp_32:hover, .wt_social_wrap_alt a.yelp, .wt_social_wrap_alt a.yelp:hover, .wt_social_wrap_alt a.yelp_32, .wt_social_wrap_alt a.yelp_32:hover { 
	background-color: {$color['yelp_color']}; 
}
.wt_social_wrap a.youtube:hover, .wt_social_wrap a.youtube_32:hover, .wt_social_wrap_alt a.youtube, .wt_social_wrap_alt a.youtube:hover, .wt_social_wrap_alt a.youtube_32, .wt_social_wrap_alt a.youtube_32:hover, .wt_social_wrap_aw a.youtube:hover { 
	background-color: {$color['youtube_color']}; 
}
.wt_social_wrap_fa a.apple:hover { 
	border-color: {$color['apple_color']};  }
.wt_social_wrap_fa a.apple:hover i { 
	color: {$color['apple_color']};  }
	
.wt_social_wrap_fa a.dribbble:hover { 
	border-color: {$color['dribbble_color']};  }
.wt_social_wrap_fa a.dribbble:hover i { 
	color: {$color['dribbble_color']};  }
	
.wt_social_wrap_fa a.dropbox:hover { 
	border-color: {$color['dropbox_color']};  }
.wt_social_wrap_fa a.dropbox:hover i { 
	color: {$color['dropbox_color']};  }
	
.wt_social_wrap_fa a.facebook:hover { 
	border-color: {$color['facebook_color']};  }
.wt_social_wrap_fa a.facebook:hover i { 
	color: {$color['facebook_color']};  }
	
.wt_social_wrap_fa a.flickr:hover { 
	border-color: {$color['flickr_color']};  }
.wt_social_wrap_fa a.flickr:hover i { 
	color: {$color['flickr_color']};  }
	
.wt_social_wrap_fa a.github:hover { 
	border-color: {$color['github_color']};  }
.wt_social_wrap_fa a.github:hover i { 
	color: {$color['github_color']};  }
	
.wt_social_wrap_fa a.google-plus:hover { 
	border-color: {$color['googleplus_color']};  }
.wt_social_wrap_fa a.google-plus:hover i { 
	color: {$color['googleplus_color']};  }
	
.wt_social_wrap_fa a.html5:hover { 
	border-color: {$color['html5_color']};  }
.wt_social_wrap_fa a.html5:hover i { 
	color: {$color['html5_color']};  }
	
.wt_social_wrap_fa a.instagram:hover { 
	border-color: {$color['instagram_color']};  }
.wt_social_wrap_fa a.instagram:hover i { 
	color: {$color['instagram_color']};  }
	
.wt_social_wrap_fa a.linkedin:hover { 
	border-color: {$color['linkedin_color']};  }
.wt_social_wrap_fa a.linkedin:hover i { 
	color: {$color['linkedin_color']};  }
	
.wt_social_wrap_fa a.pinterest:hover { 
	border-color: {$color['pinterest_color']};  }
.wt_social_wrap_fa a.pinterest:hover i { 
	color: {$color['pinterest_color']};  }
	
.wt_social_wrap_fa a.rss:hover { 
	border-color: {$color['rss_color']};  }
.wt_social_wrap_fa a.rss:hover i { 
	color: {$color['rss_color']};  }
	
.wt_social_wrap_fa a.skype:hover { 
	border-color: {$color['skype_color']};  }
.wt_social_wrap_fa a.skype:hover i { 
	color: {$color['skype_color']};  }
	
.wt_social_wrap_fa a.tumblr:hover { 
	border-color: {$color['tumblr_color']};  }
.wt_social_wrap_fa a.tumblr:hover i { 
	color: {$color['tumblr_color']};  }
	
.wt_social_wrap_fa a.twitter:hover { 
	border-color: {$color['twitter_color']};  }
.wt_social_wrap_fa a.twitter:hover i { 
	color: {$color['twitter_color']};  }
	
.wt_social_wrap_fa a.vimeo-square:hover { 
	border-color: {$color['vimeo_color']};  }
.wt_social_wrap_fa a.vimeo-square:hover i { 
	color: {$color['vimeo_color']};  }
	
.wt_social_wrap_fa a.youtube:hover { 
	border-color: {$color['youtube_color']};  }
.wt_social_wrap_fa a.youtube:hover i { 
	color: {$color['youtube_color']};  }

/* ----------------- Custom css ----------------- */
{$custom_css}
CSS;
?>