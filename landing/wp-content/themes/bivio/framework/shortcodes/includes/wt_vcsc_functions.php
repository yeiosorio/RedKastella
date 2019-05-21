<?php
/**
 * WPBakery Visual Composer helper functions
 *
 * WhoaThemes - Helper Functions
 *
 */

// wt_wpb_getImageBySize - adding style atribute
if (!function_exists('wt_wpb_getImageBySize')){
	function wt_wpb_getImageBySize ( $params = array( 'post_id' => NULL, 'attach_id' => NULL, 'thumb_size' => 'thumbnail', 'class' => '', 'style' => '' ) ) {
		//array( 'post_id' => $post_id, 'thumb_size' => $grid_thumb_size )
		if ( (!isset($params['attach_id']) || $params['attach_id'] == NULL) && (!isset($params['post_id']) || $params['post_id'] == NULL) ) return;
		$post_id = isset($params['post_id']) ? $params['post_id'] : 0;
	
		if ( $post_id ) $attach_id = get_post_thumbnail_id($post_id);
		else $attach_id = $params['attach_id'];
	
		$thumb_size = $params['thumb_size'];
		$thumb_class = (isset($params['class']) && $params['class']!='') ? $params['class'].' ' : '';
		
		$thumb_style_str = (isset($params['style']) && $params['style']!='') ? ' style="' . $params['style'] .'"' : '';
		$thumb_style = (isset($params['style']) && $params['style']!='') ? $params['style'] : '';
	
		global $_wp_additional_image_sizes;
		$thumbnail = '';
	
		if ( is_string($thumb_size) && ((!empty($_wp_additional_image_sizes[$thumb_size]) && is_array($_wp_additional_image_sizes[$thumb_size])) || in_array($thumb_size, array('thumbnail', 'thumb', 'medium', 'large', 'full') ) ) ) {
			//$thumbnail = get_the_post_thumbnail( $post_id, $thumb_size );
			$thumbnail = wp_get_attachment_image( $attach_id, $thumb_size, false, array('class' => $thumb_class.'attachment-'.$thumb_size, 'style' => $thumb_style) );
			//TODO APPLY FILTER
		} elseif( $attach_id ) {
			if ( is_string($thumb_size) ) {
				preg_match_all('/\d+/', $thumb_size, $thumb_matches);
				if(isset($thumb_matches[0])) {
					$thumb_size = array();
					if(count($thumb_matches[0]) > 1) {
						$thumb_size[] = $thumb_matches[0][0]; // width
						$thumb_size[] = $thumb_matches[0][1]; // height
					} elseif(count($thumb_matches[0]) > 0 && count($thumb_matches[0]) < 2) {
						$thumb_size[] = $thumb_matches[0][0]; // width
						$thumb_size[] = $thumb_matches[0][0]; // height
					} else {
						$thumb_size = false;
					}
				}
			}
			if (is_array($thumb_size)) {
				// Resize image to custom size
				$p_img = wpb_resize($attach_id, null, $thumb_size[0], $thumb_size[1], true);
				$alt = trim(strip_tags( get_post_meta($attach_id, '_wp_attachment_image_alt', true) ));
	
				if ( empty($alt) ) {
					$attachment = get_post($attach_id);
					$alt = trim(strip_tags( $attachment->post_excerpt )); // If not, Use the Caption
				}
				if ( empty($alt) )
					$alt = trim(strip_tags( $attachment->post_title )); // Finally, use the title
				if ( $p_img ) {
					$img_class = '';
					//if ( $grid_layout == 'thumbnail' ) $img_class = ' no_bottom_margin'; class="'.$img_class.'"
					$thumbnail = '<img class="'.$thumb_class.'" src="'.$p_img['url'].'" width="'.$p_img['width'].'" height="'.$p_img['height'].'" alt="'.$alt.'"'.$thumb_style_str.' />';
					//TODO: APPLY FILTER
				}
			}
		}
	
		$p_img_large = wp_get_attachment_image_src($attach_id, 'large' );
		return array( 'thumbnail' => $thumbnail, 'p_img_large' => $p_img_large );
	}
}

// Check if it's Editing post / page
if (!function_exists('WT_VCSC_IsEditPagePost')){
	function WT_VCSC_IsEditPagePost($new_edit = null){
		global $pagenow, $typenow;
		if (function_exists('vc_is_inline')){
			$vc_is_inline = vc_is_inline();
			if ((!vc_is_inline()) && (!is_admin())) return false;
		} else {
			$vc_is_inline = false;
			if (!is_admin()) return false;
		}
		if ($new_edit == "edit") {
			return in_array($pagenow, array('post.php'));
		} else if ($new_edit == "new") {
			return in_array($pagenow, array('post-new.php'));
		} else if ($vc_is_inline == true) {
			return true;
		} else {
			return in_array($pagenow, array('post.php', 'post-new.php'));
		}
	}
}

// Filter function to get pages / posts / custom post types
if (!function_exists('WT_VCSC_GetSelectTargetOptions')){
	function WT_VCSC_GetSelectTargetOptions($type) {
		$options = array();
		switch($type){
			case 'page':
				$entries = get_pages('title_li=&orderby=name');
				foreach($entries as $key => $entry) {
					$options[$entry->post_title] = $entry->ID;
				}
				break;
			case 'category':
				$entries = get_categories('title_li=&orderby=name&hide_empty=0');
				foreach($entries as $key => $entry) {
					$options[$entry->name] = $entry->term_id;
				}
				break;
			case 'author':
				global $wpdb;
				$order = 'user_id';
				$user_ids = $wpdb->get_col($wpdb->prepare("SELECT $wpdb->usermeta.user_id FROM $wpdb->usermeta where meta_key='wp_user_level' and meta_value>=1 ORDER BY %s ASC",$order));
				foreach($user_ids as $user_id) :
					$user = get_userdata($user_id);
					$options[$user->display_name] = $user_id;
				endforeach;
				break;
			case 'post':
				$entries = get_posts('orderby=title&numberposts=-1&order=ASC&suppress_filters=0');
				foreach($entries as $key => $entry) {
					$options[$entry->post_title] = $entry->ID;
				}
				break;
			case 'wt_portfolio':
				$entries = get_posts('post_type=wt_portfolio&orderby=title&numberposts=-1&order=ASC&suppress_filters=0');
				foreach($entries as $key => $entry) {
					$options[$entry->post_title] = $entry->ID;
				}
				break;
			case 'wt_portfolio_category':
				$entries = get_terms('wt_portfolio_category','orderby=name&hide_empty=0&suppress_filters=0');
				foreach($entries as $key => $entry) {
					$options[$entry->name] = $entry->slug;
				}
				break;
		}
		
		return $options;
	}
}

// Excerpts
if ( !function_exists( 'WT_VCSC_Excerpt' ) ) {
	function WT_VCSC_Excerpt( $length=30, $readmore=false, $read_more_text='', $post_id='' ) {
		global $post;
		$id = $post_id ? $post_id : $post->ID;
		$custom_excerpt = apply_filters( 'the_content', $post->post_excerpt );
		$post_content = get_the_content( $id );
		$output = '';
		
		if ( '0' != $length ) {
			// Custom Excerpt
			if ( $custom_excerpt ) {
				if ( '' != $length && '-1' != $length ) {
					$excerpt = wp_trim_words( $custom_excerpt, $length );
					$excerpt = wp_kses( $excerpt, array( 'a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() ) );
					$output = apply_filters( 'the_content', $excerpt );
				} else {
					$output = $custom_excerpt;
				}
			} else {
				// Excerpt length
				$meta_excerpt = get_post_meta( $id, 'wt_excerpt_length', true );
				$length = $meta_excerpt ? $meta_excerpt : $length;
				// Readmore text
				$read_more_text = $read_more_text ? $read_more_text : __('View Post', 'wt_vcsc' );
				// Check if text shortcode in post
				if ( strpos( $post_content, '[vc_column_text]') ) {
					$pattern = '{\[vc_column_text\](.*?)\[/vc_column_text\]}is';
					preg_match( $pattern, $post_content, $match );
					if( isset( $match[1] ) ) {
						//$excerpt = str_replace('[vc_column_text]', '', $match[0] );
						//$excerpt = str_replace('[/vc_column_text]', '', $excerpt );
						$excerpt = wp_trim_words( $match[1], $length );
					} else {
						$content = strip_shortcodes( $post_content );
						$excerpt = wp_trim_words( $content, $length );
					}
				} else {
					$content = strip_shortcodes( $post_content );
					$excerpt = wp_trim_words( $content, $length );
				}
				// Output Excerpt
				$excerpt = wp_kses( $excerpt, array( 'a' => array( 'href' => array(), 'title' => array() ), 'br' => array(), 'em' => array(), 'strong' => array() ) );
				$output .= '<p>'. html_entity_decode($excerpt) .'</p>';
			}
		}
		
		if ( $readmore == true ) {		
			$readmore_link = '<p class="readMore"><a href="'. get_permalink( $id ) .'" title="'.$read_more_text .'" rel="bookmark" class="read_more_link">'.$read_more_text .' <span class="wt-readmore-rarr">&raquo;</span></a></p>';
			$output .= apply_filters( 'wt_readmore_link', $readmore_link );
		}
		
		return $output;
	}
}

// Pagination for blog shortcode
if ( !function_exists( 'WT_VCSC_BlogPageNavi' ) ) {
	function WT_VCSC_BlogPageNavi($before = '', $after = '', $blog_query, $paged) {
		global $wpdb, $wp_query;
		
		if (is_single())
			return;
		
		$pagenavi_options = array(
			//'pages_text' => __('Page %CURRENT_PAGE% of %TOTAL_PAGES%','wt_front'),
			// 'pages_text' => '',
			'current_text' => '%PAGE_NUMBER%',
			'page_text' => '%PAGE_NUMBER%',
			'next_text' => __('&raquo;','wt_front'),
			'prev_text' => __('&laquo;','wt_front'),
			'dotright_text' => __('...','wt_front'),
			'dotleft_text' => __('...','wt_front'),
			'style' => 1,
			'num_pages' => 4,
			'always_show' => 0,
			'num_larger_page_numbers' => 3,
			'larger_page_numbers_multiple' => 10,
			'use_pagenavi_css' => 0,
		);
		
		$request = $blog_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		global $wp_version;
		if(is_front_page() && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query
			$paged = (get_query_var('paged')) ?intval(get_query_var('paged')) : intval(get_query_var('page'));
		}else{
			$paged = intval(get_query_var('paged'));
		}
		
		$numposts = $blog_query->found_posts;
		$max_page = intval($blog_query->max_num_pages);
		
		if (empty($paged) || $paged == 0)
			$paged = 1;
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
		$larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor($pages_to_show_minus_1 / 2);
		$half_page_end = ceil($pages_to_show_minus_1 / 2);
		$start_page = $paged - $half_page_start;
		
		if ($start_page <= 0)
			$start_page = 1;
		
		$end_page = $paged + $half_page_end;
		if (($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		
		if ($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		
		if ($start_page <= 0)
			$start_page = 1;
		
		$larger_pages_array = array();
		if ($larger_page_multiple)
			for($i = $larger_page_multiple; $i <= $max_page; $i += $larger_page_multiple)
				$larger_pages_array[] = $i;
		
		if ($max_page > 1 || intval($pagenavi_options['always_show'])) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			echo $before . '<div class="wp-pagenavi">' . "\n";
			switch(intval($pagenavi_options['style'])){
				// Normal
				case 1:
					if (! empty($pages_text)) {
						echo '<span class="pagenavi">' . $pages_text . '</span>';
					}
					$larger_page_start = 0;
					foreach($larger_pages_array as $larger_page) {
						if ($larger_page < $start_page && $larger_page_start < $larger_page_to_show) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
							$larger_page_start++;
						}
					}
					previous_posts_link($pagenavi_options['prev_text']);
					for($i = $start_page; $i <= $end_page; $i++) {
						if ($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<a class="currentPosts">' . $current_page_text . '</a>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
						}
					}
					next_posts_link($pagenavi_options['next_text'], $max_page);
					$larger_page_end = 0;
					foreach($larger_pages_array as $larger_page) {
						if ($larger_page > $end_page && $larger_page_end < $larger_page_to_show) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
							$larger_page_end++;
						}
					}
					break;
				// Dropdown
				case 2:
					echo '<form action="' .  htmlspecialchars($_SERVER['PHP_SELF']) . '" method="get">' . "\n";
					echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">' . "\n";
					for($i = 1; $i <= $max_page; $i++) {
						$page_num = $i;
						if ($page_num == 1) {
							$page_num = 0;
						}
						if ($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '" selected="selected" class="current">' . $current_page_text . "</option>\n";
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '">' . $page_text . "</option>\n";
						}
					}
					echo "</select>\n";
					echo "</form>\n";
					break;
			}
			echo '</div>' . $after . "\n";
		}
	}
}

// Pagination for portfolio shortcode
if ( !function_exists( 'WT_VCSC_PortfolioPageNavi' ) ) {
	function WT_VCSC_PortfolioPageNavi($before = '', $after = '',$portfolio_query, $paged, $pag_align) {
		global $wpdb, $wp_query;
		
		if (is_single())
			return;
		
		$pagenavi_options = array(
			//'pages_text' => __('Page %CURRENT_PAGE% of %TOTAL_PAGES%','wt_front'),
			'pages_text' => '',
			'current_text' => '%PAGE_NUMBER%',
			'page_text' => '%PAGE_NUMBER%',
			'next_text' => __('&raquo;','wt_front'),
			'prev_text' => __('&laquo;','wt_front'),
			'dotright_text' => __('...','wt_front'),
			'dotleft_text' => __('...','wt_front'),
			'style' => 1,
			'num_pages' => 4,
			'always_show' => 0,
			'num_larger_page_numbers' => 3,
			'larger_page_numbers_multiple' => 10,
			'use_pagenavi_css' => 0,
		);
		
		$request = $portfolio_query->request;
		$posts_per_page = intval(get_query_var('posts_per_page'));
		global $wp_version;
		if(is_front_page() && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query
			$paged = (get_query_var('paged')) ?intval(get_query_var('paged')) : intval(get_query_var('page'));
		}else{
			$paged = intval(get_query_var('paged'));
		}
		$numposts = $portfolio_query->found_posts;
		$max_page = intval($portfolio_query->max_num_pages);
		
		if (empty($paged) || $paged == 0)
			$paged = 1;
		$pages_to_show = intval($pagenavi_options['num_pages']);
		$larger_page_to_show = intval($pagenavi_options['num_larger_page_numbers']);
		$larger_page_multiple = intval($pagenavi_options['larger_page_numbers_multiple']);
		$pages_to_show_minus_1 = $pages_to_show - 1;
		$half_page_start = floor($pages_to_show_minus_1 / 2);
		$half_page_end = ceil($pages_to_show_minus_1 / 2);
		$start_page = $paged - $half_page_start;
		
		if ($start_page <= 0)
			$start_page = 1;
		
		$end_page = $paged + $half_page_end;
		if (($end_page - $start_page) != $pages_to_show_minus_1) {
			$end_page = $start_page + $pages_to_show_minus_1;
		}
		
		if ($end_page > $max_page) {
			$start_page = $max_page - $pages_to_show_minus_1;
			$end_page = $max_page;
		}
		
		if ($start_page <= 0)
			$start_page = 1;
		
		$larger_pages_array = array();
		if ($larger_page_multiple)
			for($i = $larger_page_multiple; $i <= $max_page; $i += $larger_page_multiple)
				$larger_pages_array[] = $i;
		
		if ($max_page > 1 || intval($pagenavi_options['always_show'])) {
			$pages_text = str_replace("%CURRENT_PAGE%", number_format_i18n($paged), $pagenavi_options['pages_text']);
			$pages_text = str_replace("%TOTAL_PAGES%", number_format_i18n($max_page), $pages_text);
			echo $before . '<div class="wp-pagenavi"><div class="pagination wt_align_'.$pag_align.'">' . "\n";
			switch(intval($pagenavi_options['style'])){
				// Normal
				case 1:
					if (! empty($pages_text)) {
						echo '<span class="pagenavi">' . $pages_text . '</span>';
					}
					
					$larger_page_start = 0;
					foreach($larger_pages_array as $larger_page) {
						if ($larger_page < $start_page && $larger_page_start < $larger_page_to_show) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
							$larger_page_start++;
						}
					}
					//echo '<li class="inactive">';
					previous_posts_link($pagenavi_options['prev_text']);
					//echo '</li>';
					for($i = $start_page; $i <= $end_page; $i++) {
						if ($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<a href="" class="currentPosts"><span>' . $current_page_text . '</span></a>';
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
						}
					}
					//echo '<li class="inactive">';
					next_posts_link($pagenavi_options['next_text'], $max_page);
					//echo '</li>';
					$larger_page_end = 0;
					foreach($larger_pages_array as $larger_page) {
						if ($larger_page > $end_page && $larger_page_end < $larger_page_to_show) {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($larger_page), $pagenavi_options['page_text']);
							echo '<a href="' . esc_url(get_pagenum_link($larger_page)) . '" class="inactive" title="' . $page_text . '">' . $page_text . '</a>';
							$larger_page_end++;
						}
					}
					break;
				// Dropdown
				case 2:
					echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF']) . '" method="get">' . "\n";
					echo '<select size="1" onchange="document.location.href = this.options[this.selectedIndex].value;">' . "\n";
					for($i = 1; $i <= $max_page; $i++) {
						$page_num = $i;
						if ($page_num == 1) {
							$page_num = 0;
						}
						if ($i == $paged) {
							$current_page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['current_text']);
							echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '" selected="selected" class="current">' . $current_page_text . "</option>\n";
						} else {
							$page_text = str_replace("%PAGE_NUMBER%", number_format_i18n($i), $pagenavi_options['page_text']);
							echo '<option value="' . esc_url(get_pagenum_link($page_num)) . '">' . $page_text . "</option>\n";
						}
					}
					echo "</select>\n";
					echo "</form>\n";
					break;
			}
			echo '</div></div>' . $after . "\n";
		}
	}
}

if ( !function_exists( 'WT_WpGetAttachment' ) ) {
	function WT_WpGetAttachment( $attachment_id ) {	
		$attachment = get_post( $attachment_id );
		return array(
			'alt'         => get_post_meta( $attachment->ID, '_wp_attachment_image_alt', true ),
			'caption'     => $attachment->post_excerpt,
			'description' => $attachment->post_content,
			'href'        => get_permalink( $attachment->ID ),
			'src'         => $attachment->guid,
			'title'       => $attachment->post_title
		);
	}
}

// Valitate gmap markers popup
if ( !function_exists( 'WT_VCSC_ValidateMarkerText' ) ) {
	function WT_VCSC_ValidateMarkerText($text) {		
		$text = str_replace('"', '\"', $text);
		$text = str_replace("'", "\'", $text);
		$text = str_replace(array("\n", "\r"), '<br>', $text);
		$text = str_replace(array("<p>", "</p>"), '', $text);
		return $text;
	}
}

?>