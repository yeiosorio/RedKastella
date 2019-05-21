<?php
global $theme_options;
/**
 * Retrieve option value based on name of option.
 * 
 * If the option does not exist or does not have a value, then the return value will be false.
 * 
 * @param string $page page name
 * @param string $name option name
 */
function wt_get_option($page, $name = NULL) {
	global $theme_options;

	if($theme_options === NULL){
		return wt_get_option_from_db($page, $name);
	}

	if ($name == NULL) {
		if (isset($theme_options[$page])) {
			return $theme_options[$page];
		} else {
			return false;
		}
	} else {
		if (isset($theme_options[$page][$name])) {
			return $theme_options[$page][$name];
		} else {
			return false;
		}
	}
}

function wt_get_option_from_db($page, $name = NULL){
	$options = get_option(THEME_SLUG . '_' . $page);

	if($name == NULL){
		return $options;
	}else{
		if(is_array($options) && isset($options[$name])){
			return $options[$name];
		}
		return false;
	}
}
/** 
   * @param string $option
   * @return mixed option value or false on fail
   */
function wt_set_option($page, $name, $value) {
	global $theme_options;
	$theme_options[$page][$name] = $value;
	
	update_option(THEME_SLUG . '_' . $page, $theme_options[$page]);
}
/**
 * It will return a boolean value.
 * If the value to be checked is empty, it will use default value instead of.
 * 
 * @param mixed $value
 * @param mixed $default
 */
function wt_is_enabled($value, $default = false) {
	if(is_bool($value)){
		return $value;
	}
	switch($value){
		case '1'://for theme compatibility
		case 'true':
			return true;
		case '-1'://for theme compatibility
		case 'false':
			return false;
		case '0':
		case '':
		default:
			return $default;
	}
}

function wt_check_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES);;
    return $data;
}
function wpml_t($context, $name, $original_value){
	if(function_exists('icl_t')){
		return icl_t($context, $name, $original_value);
	}else{
		return $original_value;
	}
}

function wpml_register_string($context, $name, $value){
	if(function_exists('icl_register_string') && trim($value)){
		icl_register_string($context, $name, $value);
	}	
}
$get_options = get_option(THEME_SLUG . '_' . 'slideshow');
$flex_count = $get_options['flex_custom_slider_count'] + 1 ;
for($z = 0; $z < $flex_count; $z++) {
	if ($flex_count = $get_options['flex_custom_slider_count'] ) {
	wpml_register_string( THEME_NAME , 'Flex Caption_'. $z, stripslashes($get_options['flex_custom_slider_caption_'. $z]));
	}
}
wpml_register_string( THEME_NAME , 'Copyright Footer Text', stripslashes(wt_get_option('footer','copyright')));
function wt_set_custom_post_types_admin_order($wp_query) {
  if (is_admin()) {

    // Get the post type from the query
    $post_type = $wp_query->query['post_type'];

    if ( $post_type == 'POST_TYPE') {

      // 'orderby' value can be any column name
      $wp_query->set('orderby', 'title');

      // 'order' value can be ASC or DESC
      $wp_query->set('order', 'ASC');
    }
  }
}
add_filter('pre_get_posts', 'wt_set_custom_post_types_admin_order');

function wt_get_excluded_pages(){
	$excluded_pages = wt_get_option('general', 'excluded_pages');
	$home = wt_get_option('general','home_page');
	if (! empty($excluded_pages)) {
		//Exclude a parent and all of that parent's child Pages
		$excluded_pages_with_childs = '';
		foreach($excluded_pages as $parent_page_to_exclude) {
			if ($excluded_pages_with_childs) {
				$excluded_pages_with_childs .= ',' . $parent_page_to_exclude;
			} else {
				$excluded_pages_with_childs = $parent_page_to_exclude;
			}
			$descendants = get_pages('child_of=' . $parent_page_to_exclude);
			if ($descendants) {
				foreach($descendants as $descendant) {
					$excluded_pages_with_childs .= ',' . $descendant->ID;
				}
			}
		}
		if($home){
			$excluded_pages_with_childs .= ',' .$home;
		}
	} else {
		$excluded_pages_with_childs = $home;
	}
	return $excluded_pages_with_childs;
}

if(!function_exists("get_queried_object_id")){
	/**
	* Retrieve ID of the current queried object.
	*/
	function get_queried_object_id(){
		global $wp_query;
		return $wp_query->get_queried_object_id();
	}
}
function get_object_id($element_id, $element_type='post', $return_original_if_missing=false, $ulanguage_code=null){
    if(function_exists('icl_object_id')){
        return icl_object_id($element_id, $element_type, $return_original_if_missing, $ulanguage_code);
    }else{
        return $element_id;
    }    
}
// use for template_blog.php
function is_blog() {
	global $is_blog;
	
	if($is_blog == true){return true;}
	$blog_page_id = wt_get_option('blog','blog_page');
	if(empty($blog_page_id)){
		return false;
	}
	if(get_object_id($blog_page_id,'page') == get_queried_object_id()){
		$is_blog = true;
		return true;
	}
	
	return false;
}
function wt_get_image_src($src){
	return $src;
}
function wt_blog_pagenavi($before = '', $after = '', $blog_query, $paged) {
	global $wpdb, $wp_query;
	
	if (is_single())
		return;
	
	$pagenavi_options = array(
		'pages_text' => '',
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
						echo '<a href="' . esc_url(get_pagenum_link($i)) . '" class="active" title="' . $current_page_text . '">' . $current_page_text . '</a>';
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

function wt_pagination($pages = '', $range = 2){
     $showitems = $range;  
     global $paged;
     if(empty($paged)) $paged = 1;
     if($pages == '') {
		global $wp_query;
         $pages = $wp_query->max_num_pages;
         if(!$pages)
         {
             $pages = 1;
         }
     }   
     if(1 != $pages)
     {
         echo "<div class=\"wp-pagenavi\">";         
		// echo "<span class=\"pagenavi\">";
		 //for ($i=1; $i <= $pages; $i++) {
			 //echo ($paged == $i)? $i : " ";
		// }
		// echo " of ".$pages." </span>";
		 
         if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."' title='Previous'> &laquo; </a>";
         for ($i=1; $i <= $pages; $i++)
         {
             if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
             {
                echo ($paged == $i)? "<a class='currentPosts'>".$i."</a>":"<a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a>";			
             }
         }
         if ($paged < $pages && $showitems < $pages) echo "<a href=\"".get_pagenum_link($paged + 1)."\" title='Next'> &raquo; </a>";
         echo "</div>\n";
     }
}
function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}
/*
 * add a span element for style in the page
 */
function wt_comment_style($return) {
	return str_replace($return, "<i class=\"fa fa-comments\"></i>$return", $return);
}
add_filter('get_comment_author_link', 'wt_comment_style');

function wt_span_before_link_list_categories( $list ) {
	$list = str_replace('<a href=','<i class="fa fa-angle-right"></i><a href=',$list);
	return $list;
}
add_filter ( 'wp_list_categories', 'wt_span_before_link_list_categories' );

function wt_span_before_link_list_pages( $list ) {
	$list = str_replace('<a href=','<i class="fa fa-angle-right"></i><a href=',$list);
	return $list;
}
add_filter ( 'wp_list_pages', 'wt_span_before_link_list_pages' );

function wt_span_before_link_recent_entries( $list ) {
	$list = str_replace('<a href=','<i class="fa fa-angle-right"></i><a href=',$list);
	return $list;
}
add_filter ( 'widget_recent_entries', 'wt_span_before_link_recent_entries' );

function wt_span_before_archives_link( $list ) {
	$list = str_replace('<a href=','<i class="fa fa-angle-right"></i><a href=',$list);
	return $list;
}
add_filter ( 'get_archives_link', 'wt_span_before_archives_link' );

function wt_span_before_post_meta( $list ) {
	$list = str_replace('<a href=','<i class="fa fa-angle-right"></i><a href=',$list);
	return $list;
}
add_filter ( 'get_post_meta', 'wt_span_before_post_meta' );

function wt_more_link($more_link, $more_link_text) {
		
	return str_replace('more-link', 'read_more_link', $more_link);
}
add_filter('the_content_more_link', 'wt_more_link', 10, 2);

function wt_excerpt_more($excerpt) {
	return str_replace('[...]', '...', $excerpt);
}
add_filter('wp_trim_excerpt', 'wt_excerpt_more');

function wt_excerpt_length($length) {
	return 100;
}
add_filter('excerpt_length', 'wt_excerpt_length');

// remove parentheses from category list and add span class to post count
function categories_postcount_filter ($variable) {
$variable = str_replace('(', '<span class="post-count"> ', $variable);
$variable = str_replace(')', ' </span>', $variable);
   return $variable;
}
add_filter('wp_list_categories','categories_postcount_filter');

function wt_exclude_category_feed() {
	$exclude_cats = wt_get_option('blog','exclude_categorys');
	if(is_array($exclude_cats)){
		foreach ($exclude_cats as $key => $cat) {
			$exclude_cats[$key] = -$cat;
		}
		if ( is_feed() ) {
			set_query_var("cat", implode(",",$exclude_cats));
		}
	}
}
add_filter('pre_get_posts', 'wt_exclude_category_feed');

/*
 * Remove Blog categories from category widget
 */
function wt_exclude_category_widget($cat_args)
{
	$exclude_cats = wt_get_option('blog','exclude_categorys');
	
	if(is_array($exclude_cats)){
		$cat_args['exclude'] = implode(",",$exclude_cats);
	}
 	return $cat_args;
}
add_filter('widget_categories_args', 'wt_exclude_category_widget');


function wt_exclude_the_categorys($thelist,$separator=' ') {
	if(!defined('WP_ADMIN') && !empty($separator)) {
		//Category IDs to exclude
		$exclude = wt_get_option('blog','exclude_categorys');

		$exclude2 = array();
		$cats = explode($separator,$thelist);
		$newlist = array();
		foreach($cats as $cat) {
			$catname = trim(strip_tags($cat));
			if(!in_array($catname,$exclude2))
				$newlist[] = $cat;
		}
		return implode($separator,$newlist);
	} else {
		return $thelist;
	}
}
add_image_size( 'thumb', 55, 55, true);
add_image_size( 'portfThumb', 465, 170, true);
add_filter('the_category','wt_exclude_the_categorys',10,2);

function wt_widget_title_remove_space($return){
	$return = trim($return);
	if('&nbsp;' == $return){
		return '';	
	}else{
		return $return;
	}
}
add_filter('widget_title', 'wt_widget_title_remove_space');

if (!function_exists('body_classes')) {
	function body_classes($classes) {
		if(wt_get_option('general','scroll_to_top')) :
			$classes[] = 'wt-top';
		endif;
		
		if(wt_get_option('general','page_loader')) :
			$classes[] = 'wt_loader';
		endif;
		if (is_front_page()) {
			$bgType = wt_get_option('background','background_type');
			if($bgType == 'image_bg') :
				$classes[] = 'wt_image_bg';
			elseif($bgType == 'pattern') :
				$classes[] = 'wt_pattern';
			elseif($bgType == 'slideshow') :
				$classes[] = 'wt_slideshow';
			endif;
		}
	
		return $classes;
	}
}

add_filter('body_class','body_classes');
if (!function_exists('get_the_slug')) {
	function get_the_slug( $id=null ){
	  if( empty($id) ):
		global $post;
		if( empty($post) )
		  return ''; // No global $post var available.
		$id = $post->ID;
	  endif;
	
	  $slug = basename( get_permalink($id) );
	  return $slug;
	}
}
// Allow Shortcodes in Sidebar Widgets
add_filter('widget_text', 'do_shortcode');

global $wp_version;
if(version_compare($wp_version, "3.1", '<')){
	/*
	 * Thank to Bob Sherron.
	 * http://stackoverflow.com/questions/1155565/query-multiple-custom-taxonomy-terms-in-wordpress-2-8/2060777#2060777
	 */
	function wt_multi_tax_terms($where) {
		global $wp_query;
		global $wpdb;
		if (isset($wp_query->query_vars['term']) && (strpos($wp_query->query_vars['term'], ',') !== false && strpos($where, "AND 0") !== false) ) {
			// it's failing because taxonomies can't handle multiple terms
			//first, get the terms
			$term_arr = explode(",", $wp_query->query_vars['term']);
			foreach($term_arr as $term_item) {
				$terms[] = get_terms($wp_query->query_vars['taxonomy'], array('slug' => $term_item));
			}

			//next, get the id of posts with that term in that tax
			foreach ( $terms as $term ) {
				$term_ids[] = $term[0]->term_id;
			}

			$post_ids = get_objects_in_term($term_ids, $wp_query->query_vars['taxonomy']);

			if ( !is_wp_error($post_ids) && count($post_ids) ) {
				// build the new query
				$new_where = " AND $wpdb->posts.ID IN (" . implode(', ', $post_ids) . ") ";
				// re-add any other query vars via concatenation on the $new_where string below here

				// now, sub out the bad where with the good
				$where = str_replace("AND 0", $new_where, $where);
			} else {
				// give up
			}
		}
		return $where;
	}
	add_filter("posts_where", "wt_multi_tax_terms");
}
// Search
if(!function_exists('wt_logo'))
{
	
	//first append search item to main menu
	add_filter( 'wp_nav_menu_items', 'wt_logo', 10, 2 );

	function wt_logo ($logo, $args) {
	    if ($args->theme_location == 'primary-menu' && wt_get_option('general','menu_position') == 'side') {
			ob_start();
			$custom_logo = wt_get_option('general','logo');
			$retinaLogo    = wt_get_option('general', 'logo_retina');
			$enable_retina = wt_get_option('general', 'enable_retina');
			$logo .=' <li id="menu-item-search" class="menu-item menu-item-logo notMobile">';
				if(!wt_get_option('general','display_logo') && $custom_logo = wt_get_option('general','logo')) {		
                   $logo .='<div id="logo">';
					   if($enable_retina) {
							 $logo .='<a href="'.home_url( '/' ).'"><img src="'. $custom_logo .'" data-at2x="'. $retinaLogo .'" /></a>';
					   } else {
							 $logo .='<a href="'. home_url( '/' ) .'"><img src="'. $custom_logo .'" /></a>';
					   }
					$logo .='</div>';
				
				}else {
				   $logo .='<div id="logo_text">';
						 $logo .='<a href="'.home_url( '/' ).'"><span>'. wt_get_option('general','plain_logo').'</span>';
						if(wt_get_option('general','display_site_desc')){
								$site_desc = get_bloginfo( 'description' );
								if(!empty($site_desc)) {
									$logo .='<span id="siteDescription">'. get_bloginfo( 'description' ).'</span>';
								}
						}
						$logo .='</a>';
				   $logo .=' </div>';
				}
			$logo .='</li>';
		}
	    return $logo;
	}
}

class description_walker extends Walker_Nav_Menu {
      function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		   $depth= $depth + 1;
           $class_names = $value = '';
			if(wt_get_option('general','menu_icons')) {
				//$icons = '<i></i>';
				//$icons_class = 'menu_icon ';
				//$icons_list_class = ' icon_list ';
				$icons = '';
				$icons_class = '';
				$icons_list_class = '';
			}
			else {
				$icons = '';
				$icons_class = '';
				$icons_list_class = '';
			}
			
           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . $icons_list_class . ' level-'.$depth.'-li"';
		   
		   $output .=  $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';
           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
                $varpost = get_post($item->object_id);
				if($item->object == 'page') {
					if(is_single() || is_archive() || is_page() || is_blog()){
						  $attributes .= ' href="' . get_site_url() . '/#' . $varpost->post_name . '"';
						}
						else{
							$attributes .= ' href="#' . $varpost->post_name . '"';
						}
			  		}
				else{
				   $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url ) .'"' : '';
                }
			if($depth == 0) {
				$item_output = $args->before;
				$item_output .= '<a'. $attributes .' class="'.$icons_class.'level-'.$depth.'-a">'. $icons .'<span data-hover="'.$args->link_before .apply_filters( 'the_title', $item->title, $item->ID ).'">';
				$item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
				$item_output .= '</span></a>';
				$item_output .= $args->after;
			} else {
				$item_output = $args->before;
				$item_output .= '<a'. $attributes .' class="'.$icons_class.'level-'.$depth.'-a">'. $icons .'<span data-hover="'.$args->link_before .apply_filters( 'the_title', $item->title, $item->ID ).'">';
				$item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
				$item_output .= '</span></a>';
				$item_output .= $args->after;
			}
            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
			
}

class My_Walker extends Walker_Nav_Menu {
      function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
           global $wp_query;
           $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		   $depth= $depth + 1;
           $class_names = $value = '';

           $classes = empty( $item->classes ) ? array() : (array) $item->classes;

           $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
           $class_names = ' class="'. esc_attr( $class_names ) . ' level-'.$depth.'-li"';

           $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

           $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
           $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
           $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
           $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
			if(wt_get_option('general','menu_icons')) {
				$icons = '<i></i>';
				$icons_class = 'menu_icon ';
			}
			else {
				$icons = '';
				$icons_class = '';
			}
            $item_output = $args->before;
            $item_output .= '<a'. $attributes .' class="'.$icons_class.'level-'.$depth.'-a">'. $icons .'<span data-hover="'.$args->link_before .apply_filters( 'the_title', $item->title, $item->ID ).'">';
            $item_output .= $args->link_before .apply_filters( 'the_title', $item->title, $item->ID );
            $item_output .= '</span></a>';
            $item_output .= $args->after;

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
            }
}
class My_Page_Walker extends Walker_Page { 
/**
 * Filter in the classes for parents.
 */
	function _filterClass( $class ) {
		 $class[] = 'parent'; // change this to whatever classe(s) you require
		 return $class;
	}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		if ( !empty($args['has_children']) )
			 add_filter( 'page_css_class', array( &$this, '_filterClass') );
	
		if ( $depth )
			$indent = str_repeat("\t", $depth);
		else
			$indent = '';
			$depth= $depth + 1;
		extract($args, EXTR_SKIP);
		$css_class = array('page_item', 'page-item-'.$item->ID);
		if ( !empty($current_page) ) {
			$_current_page = get_page( $current_page );
			if ( isset($_current_page->ancestors) && in_array($item->ID, (array) $_current_page->ancestors) )
				$css_class[] = 'current_page_ancestor';
			if ( $item->ID == $current_page )
				$css_class[] = 'current_page_item';
			elseif ( $_current_page && $item->ID == $_current_page->post_parent )
				$css_class[] = 'current_page_parent';
		} elseif ( $item->ID == get_option('page_for_posts') ) {
			$css_class[] = 'current_page_parent';
		}
	
		$css_class = implode(' ', apply_filters('page_css_class', $css_class, $item));
	
		$output .= $indent . '<li class="' . $css_class . ' level-'.$depth.'-li"><a class="level-'.$depth.'-a" href="' . get_permalink($item->ID) . '">' . $link_before;
		$image = get_post_meta($item->ID, 'title_image', true);
		if ( isset($image) && !empty($image) ) {
			$output .= '<img src="'. $image .'" alt="'. apply_filters( 'the_title', $item->post_title, $item->ID ) .'" />';
		}
		else {
			$output .= apply_filters( 'the_title', $item->post_title, $item->ID );
		}
		$output .=  $link_after . '</a>';
	
		if ( !empty($show_date) ) {
			if ( 'modified' == $show_date )
				$time = $page->post_modified;
			else
				$time = $page->post_date;
	
			$output .= " " . mysql2date($date_format, $time);
		}
	
	
		 if ( !empty($args['has_children']) )
			 remove_filter( 'page_css_class', array( &$this, '_filterClass') );
	}
}
/*
 * add menu order support for Single Portfolio Item Previous & Next Navigation
 */
$order = wt_get_option('portfolio','single_navigation_order');
if($order = 'menu_order'){
	function wt_get_previous_portfolio_menu_order_where($where){
		global $post, $wpdb;
		if($post->post_type == 'wt_portfolio'){
			$current_menu_order = $post->menu_order;
			$where = $wpdb->prepare("WHERE p.menu_order < %s AND p.post_type = 'wt_portfolio' AND p.post_status = 'publish'", $current_menu_order);
		}
		return $where;
	}
	function wt_get_next_portfolio_menu_order_where($where){
		global $post, $wpdb;
		if($post->post_type == 'wt_portfolio'){
			$current_menu_order = $post->menu_order;
			$where = $wpdb->prepare("WHERE p.menu_order > %s AND p.post_type = 'wt_portfolio' AND p.post_status = 'publish'", $current_menu_order);
		}
		return $where;
	}
	add_filter("get_previous_post_where", "wt_get_previous_portfolio_menu_order_where");
	add_filter("get_next_post_where", "wt_get_next_portfolio_menu_order_where");

	function wt_get_previous_portfolio_menu_order_sort($sort){
		global $post;
		if($post->post_type == 'wt_portfolio'){
			$sort = "ORDER BY p.menu_order DESC LIMIT 1";
		}
		return $sort;
	}
	function wt_get_next_portfolio_menu_order_sort($sort){
		global $post;
		if($post->post_type == 'wt_portfolio'){
			$sort = "ORDER BY p.menu_order ASC LIMIT 1";	
		}
		return $sort;
	}

	add_filter("get_previous_post_sort", "wt_get_previous_portfolio_menu_order_sort");
	add_filter("get_next_post_sort", "wt_get_next_portfolio_menu_order_sort");
}

/*
 * Single Portfolio Item Document Type Navigation
 */
if(wt_get_option('portfolio','single_navigation')){
	function wt_get_adjacent_portfolio_join($join){
		global $post, $wpdb;
		if($post->post_type == 'wt_portfolio'){
			$join .= " JOIN $wpdb->postmeta ON (p.ID = $wpdb->postmeta.post_id) ";
		}
		return $join;	
	}
	add_filter("get_previous_post_join", "wt_get_adjacent_portfolio_join");
	add_filter("get_next_post_join", "wt_get_adjacent_portfolio_join");

	function wt_get_adjacent_portfolio_where($where){
		global $post, $wpdb;
		if($post->post_type == 'wt_portfolio'){
			$where .= $wpdb->prepare(" AND $wpdb->postmeta.meta_key = %s ", '_type');
			$where .= $wpdb->prepare("AND $wpdb->postmeta.meta_value = %s ", 'doc');
		}
		return $where;
	}
	add_filter("get_previous_post_where", "wt_get_adjacent_portfolio_where");
	add_filter("get_next_post_where", "wt_get_adjacent_portfolio_where");
}

/*
 * Fix for rel="category tag" in WordPress - HTML5.
   WordPress adds the categories but without the rel="nofollow" so a search engine will still move through those links when indexing. 
 */
add_filter( 'the_category', 'wt_add_nofollow_cat' ); 
function wt_add_nofollow_cat( $text ) {
	$text = str_replace('rel="category tag"', "", $text); return $text;
}

function wt_meta() {
 if ( $keys = get_post_custom_keys() ) {
	echo "<ul class='post-meta'>\n";
	foreach ( (array) $keys as $key ) {
		$keyt = trim($key);
		if ( '_' == $keyt{0} || 'dfiFeatured' == $keyt || 'textfalse' == $keyt  || 'post_views_count' == $keyt || 'slide_template' == $keyt)
			continue;
			$values = array_map('trim', get_post_custom_values($key));
			$value = implode($values,', ');
			echo apply_filters('the_meta_key', "
			<li><span class='post-meta-key'>$key:</span> $value</li>
			\n", $key, $value);
			}
			echo "\n";
	}
}
function wt_getPostViews($postID){
    //$count_key = 'post_views_count';
    $count_key = '';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
       // add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}

function wt_setPostViews($postID) {
    //$count_key = 'post_views_count';
    $count_key = '';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
       // add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}

/*
Plugin Name: Ambrosite Next/Previous Post Link Plus
Plugin URI: http://www.ambrosite.com/plugins
Description: Upgrades the next/previous post link template tags to reorder or loop adjacent post navigation links, return multiple links, truncate link titles, and display post thumbnails. IMPORTANT: If you are upgrading from plugin version 1.1, you will need to update your templates (refer to the <a href="http://www.ambrosite.com/plugins/next-previous-post-link-plus-for-wordpress">documentation</a> on configuring parameters).
Version: 2.4
Author: J. Michael Ambrosio
Author URI: http://www.ambrosite.com
License: GPL2
*/

/**
 * Retrieve adjacent post link.
 *
 * Can either be next or previous post link.
 *
 * Based on get_adjacent_post() from wp-includes/link-template.php
 *
 * @param array $r Arguments.
 * @param bool $previous Optional. Whether to retrieve previous post.
 * @return array of post objects.
 */
function wt_get_adjacent_post_plus($r, $previous = true ) {
	global $post, $wpdb;

	extract( $r, EXTR_SKIP );

	if ( empty( $post ) )
		return null;

//	Sanitize $order_by, since we are going to use it in the SQL query. Default to 'post_date'.
	if ( in_array($order_by, array('post_date', 'post_title', 'post_excerpt', 'post_name', 'post_modified')) ) {
		$order_format = '%s';
	} elseif ( in_array($order_by, array('ID', 'post_author', 'post_parent', 'menu_order', 'comment_count')) ) {
		$order_format = '%d';
	} elseif ( $order_by == 'custom' && !empty($meta_key) ) { // Don't allow a custom sort if meta_key is empty.
		$order_format = '%s';
	} elseif ( $order_by == 'numeric' && !empty($meta_key) ) {
		$order_format = '%d';
	} else {
		$order_by = 'post_date';
		$order_format = '%s';
	}
	
//	Sanitize $order_2nd. Only columns containing unique values are allowed here. Default to 'post_date'.
	if ( in_array($order_2nd, array('post_date', 'post_title', 'post_modified')) ) {
		$order_format2 = '%s';
	} elseif ( in_array($order_2nd, array('ID')) ) {
		$order_format2 = '%d';
	} else {
		$order_2nd = 'post_date';
		$order_format2 = '%s';
	}
	
//	Sanitize num_results (non-integer or negative values trigger SQL errors)
	$num_results = intval($num_results) < 2 ? 1 : intval($num_results);

//	Queries involving custom fields require an extra table join
	if ( $order_by == 'custom' || $order_by == 'numeric' ) {
		$current_post = get_post_meta($post->ID, $meta_key, TRUE);
		$order_by = ($order_by === 'numeric') ? 'm.meta_value+0' : 'm.meta_value';
		$meta_join = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $meta_key );
	} elseif ( $in_same_meta ) {
		$current_post = $post->$order_by;
		$order_by = 'p.' . $order_by;
		$meta_join = $wpdb->prepare(" INNER JOIN $wpdb->postmeta AS m ON p.ID = m.post_id AND m.meta_key = %s", $in_same_meta );
	} else {
		$current_post = $post->$order_by;
		$order_by = 'p.' . $order_by;
		$meta_join = '';
	}

//	Get the current post value for the second sort column
	$current_post2 = $post->$order_2nd;
	$order_2nd = 'p.' . $order_2nd;
	
//	Get the list of post types. Default to current post type
	if ( empty($post_type) )
		$post_type = "'$post->post_type'";

//	Put this section in a do-while loop to enable the loop-to-first-post option
	do {
		$join = $meta_join;
		$excluded_categories = $ex_cats;
		$included_categories = $in_cats;
		$excluded_posts = $ex_posts;
		$included_posts = $in_posts;
		$in_same_term_sql = $in_same_author_sql = $in_same_meta_sql = $ex_cats_sql = $in_cats_sql = $ex_posts_sql = $in_posts_sql = '';

//		Get the list of hierarchical taxonomies, including customs (don't assume taxonomy = 'category')
		$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );

		if ( ($in_same_cat || $in_same_tax || $in_same_format || !empty($excluded_categories) || !empty($included_categories)) && !empty($taxonomies) ) {
			$cat_array = $tax_array = $format_array = array();

			if ( $in_same_cat ) {
				$cat_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
			}
			if ( $in_same_tax && !$in_same_cat ) {
				if ( $in_same_tax === true ) {
					if ( $taxonomies != array('category') )
						$taxonomies = array_diff($taxonomies, array('category'));
				} else
					$taxonomies = (array) $in_same_tax;
				$tax_array = wp_get_object_terms($post->ID, $taxonomies, array('fields' => 'ids'));
			}
			if ( $in_same_format ) {
				$taxonomies[] = 'post_format';
				$format_array = wp_get_object_terms($post->ID, 'post_format', array('fields' => 'ids'));
			}

			$join .= " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id AND tt.taxonomy IN (\"" . implode('", "', $taxonomies) . "\")";

			$term_array = array_unique( array_merge( $cat_array, $tax_array, $format_array ) );
			if ( !empty($term_array) )
				$in_same_term_sql = "AND tt.term_id IN (" . implode(',', $term_array) . ")";

			if ( !empty($excluded_categories) ) {
//				Support for both (1 and 5 and 15) and (1, 5, 15) delimiter styles
				$delimiter = ( strpos($excluded_categories, ',') !== false ) ? ',' : 'and';
				$excluded_categories = array_map( 'intval', explode($delimiter, $excluded_categories) );
//				Three category exclusion methods are supported: 'strong', 'diff', and 'weak'.
//				Default is 'weak'. See the plugin documentation for more information.
				if ( $ex_cats_method === 'strong' ) {
					$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
					if ( function_exists('get_post_format') )
						$taxonomies[] = 'post_format';
					$ex_cats_posts = get_objects_in_term( $excluded_categories, $taxonomies );
					if ( !empty($ex_cats_posts) )
						$ex_cats_sql = "AND p.ID NOT IN (" . implode($ex_cats_posts, ',') . ")";
				} else {
					if ( !empty($term_array) && !in_array($ex_cats_method, array('diff', 'differential')) )
						$excluded_categories = array_diff($excluded_categories, $term_array);
					if ( !empty($excluded_categories) )
						$ex_cats_sql = "AND tt.term_id NOT IN (" . implode($excluded_categories, ',') . ')';
				}
			}

			if ( !empty($included_categories) ) {
				$in_same_term_sql = ''; // in_cats overrides in_same_cat
				$delimiter = ( strpos($included_categories, ',') !== false ) ? ',' : 'and';
				$included_categories = array_map( 'intval', explode($delimiter, $included_categories) );
				$in_cats_sql = "AND tt.term_id IN (" . implode(',', $included_categories) . ")";
			}
		}

//		Optionally restrict next/previous links to same author		
		if ( $in_same_author )
			$in_same_author_sql = $wpdb->prepare("AND p.post_author = %d", $post->post_author );

//		Optionally restrict next/previous links to same meta value
		if ( $in_same_meta && $r['order_by'] != 'custom' && $r['order_by'] != 'numeric' )
			$in_same_meta_sql = $wpdb->prepare("AND m.meta_value = %s", get_post_meta($post->ID, $in_same_meta, TRUE) );

//		Optionally exclude individual post IDs
		if ( !empty($excluded_posts) ) {
			$excluded_posts = array_map( 'intval', explode(',', $excluded_posts) );
			$ex_posts_sql = " AND p.ID NOT IN (" . implode(',', $excluded_posts) . ")";
		}
		
//		Optionally include individual post IDs
		if ( !empty($included_posts) ) {
			$included_posts = array_map( 'intval', explode(',', $included_posts) );
			$in_posts_sql = " AND p.ID IN (" . implode(',', $included_posts) . ")";
		}

		$adjacent = $previous ? 'previous' : 'next';
		$order = $previous ? 'DESC' : 'ASC';
		$op = $previous ? '<' : '>';

//		Optionally get the first/last post. Disable looping and return only one result.
		if ( $end_post ) {
			$order = $previous ? 'ASC' : 'DESC';
			$num_results = 1;
			$loop = false;
			if ( $end_post === 'fixed' ) // display the end post link even when it is the current post
				$op = $previous ? '<=' : '>=';
		}

//		If there is no next/previous post, loop back around to the first/last post.		
		if ( $loop && isset($result) ) {
			$op = $previous ? '>=' : '<=';
			$loop = false; // prevent an infinite loop if no first/last post is found
		}
		
		$join  = apply_filters( "get_{$adjacent}_post_plus_join", $join, $r );

//		In case the value in the $order_by column is not unique, select posts based on the $order_2nd column as well.
//		This prevents posts from being skipped when they have, for example, the same menu_order.
		$where = apply_filters( "get_{$adjacent}_post_plus_where", $wpdb->prepare("WHERE ( $order_by $op $order_format OR $order_2nd $op $order_format2 AND $order_by = $order_format ) AND p.post_type IN ($post_type) AND p.post_status = 'publish' $in_same_term_sql $in_same_author_sql $in_same_meta_sql $ex_cats_sql $in_cats_sql $ex_posts_sql $in_posts_sql", $current_post, $current_post2, $current_post), $r );

		$sort  = apply_filters( "get_{$adjacent}_post_plus_sort", "ORDER BY $order_by $order, $order_2nd $order LIMIT $num_results", $r );

		$query = "SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort";
		$query_key = 'adjacent_post_' . md5($query);
		$result = wp_cache_get($query_key);
		if ( false !== $result )
			return $result;

//		echo $query . '<br />';

//		Use get_results instead of get_row, in order to retrieve multiple adjacent posts (when $num_results > 1)
//		Add DISTINCT keyword to prevent posts in multiple categories from appearing more than once
		$result = $wpdb->get_results("SELECT DISTINCT p.* FROM $wpdb->posts AS p $join $where $sort");
		if ( null === $result )
			$result = '';

	} while ( !$result && $loop );

	wp_cache_set($query_key, $result);
	return $result;
}

/**
 * Display previous post link that is adjacent to the current post.
 *
 * Based on previous_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if previous post link is found, otherwise false.
 */
function wt_previous_post_link_plus($args = '') {
	return wt_adjacent_post_link_plus($args, '<span>&#171;</span> %link', true);
}

/**
 * Display next post link that is adjacent to the current post.
 *
 * Based on next_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @return bool True if next post link is found, otherwise false.
 */
function wt_next_post_link_plus($args = '') {
	return wt_adjacent_post_link_plus($args, '%link <span>&#187;</span>', false);
}

/**
 * Display adjacent post link.
 *
 * Can be either next post link or previous.
 *
 * Based on adjacent_post_link() from wp-includes/link-template.php
 *
 * @param array|string $args Optional. Override default arguments.
 * @param bool $previous Optional, default is true. Whether display link to previous post.
 * @return bool True if next/previous post is found, otherwise false.
 */
function wt_adjacent_post_link_plus($args = '', $format = '%link &rarr;', $previous = true) {
	$defaults = array(
		'order_by' => 'post_date', 'order_2nd' => 'post_date', 'meta_key' => '', 'post_type' => '',
		'loop' => false, 'end_post' => false, 'thumb' => false, 'max_length' => 0,
		'format' => '', 'link' => '%title', 'date_format' => '', 'tooltip' => '%title',
		'in_same_cat' => true, 'in_same_tax' => false, 'in_same_format' => false,
		'in_same_author' => false, 'in_same_meta' => false,
		'ex_cats' => '', 'ex_cats_method' => 'weak', 'in_cats' => '', 'ex_posts' => '', 'in_posts' => '',
		'before' => '', 'after' => '', 'num_results' => 1, 'return' => false, 'echo' => true
	);

//	If Post Types Order plugin is installed, default to sorting on menu_order
	if ( function_exists('CPTOrderPosts') )
		$defaults['order_by'] = 'menu_order';
	
	$r = wp_parse_args( $args, $defaults );
	if ( empty($r['format']) )
		$r['format'] = $format;
	if ( empty($r['date_format']) )
		$r['date_format'] = get_option('date_format');
	if ( !function_exists('get_post_format') )
		$r['in_same_format'] = false;

	if ( $previous && is_attachment() ) {
		$posts = array();
		$posts[] = & get_post($GLOBALS['post']->post_parent);
	} else
		$posts = wt_get_adjacent_post_plus($r, $previous);

//	If there is no next/previous post, return false so themes may conditionally display inactive link text.
	if ( !$posts )
		return false;

//	If sorting by date, display posts in reverse chronological order. Otherwise display in alpha/numeric order.
	if ( ($previous && $r['order_by'] != 'post_date') || (!$previous && $r['order_by'] == 'post_date') )
		$posts = array_reverse( $posts, true );
		
//	Option to return something other than the formatted link		
	if ( $r['return'] ) {
		if ( $r['num_results'] == 1 ) {
			reset($posts);
			$post = current($posts);
			if ( $r['return'] === 'id')
				return $post->ID;
			if ( $r['return'] === 'href')
				return get_permalink($post);
			if ( $r['return'] === 'object')
				return $post;
			if ( $r['return'] === 'title')
				return $post->post_title;
			if ( $r['return'] === 'date')
				return mysql2date($r['date_format'], $post->post_date);
		} elseif ( $r['return'] === 'object')
			return $posts;
	}

	$output = $r['before'];

//	When num_results > 1, multiple adjacent posts may be returned. Use foreach to display each adjacent post.
	foreach ( $posts as $post ) {
		$title = $post->post_title;
		if ( empty($post->post_title) )
			$title = $previous ? __('Previous Post', 'wt_front') : __('Next Post', 'wt_front');

		$title = apply_filters('the_title', $title, $post->ID);
		$date = mysql2date($r['date_format'], $post->post_date);
		$author = get_the_author_meta('display_name', $post->post_author);
	
//		Set anchor title attribute to long post title or custom tooltip text. Supports variable replacement in custom tooltip.
		if ( $r['tooltip'] ) {
			$tooltip = str_replace('%title', $title, $r['tooltip']);
			$tooltip = str_replace('%date', $date, $tooltip);
			$tooltip = str_replace('%author', $author, $tooltip);
			$tooltip = ' title="' . esc_attr($tooltip) . '"';
		} else
			$tooltip = '';

//		Truncate the link title to nearest whole word under the length specified.
		$max_length = intval($r['max_length']) < 1 ? 9999 : intval($r['max_length']);
		if ( strlen($title) > $max_length )
			$title = substr( $title, 0, strrpos(substr($title, 0, $max_length), ' ') ) . '...';
	
		$rel = $previous ? 'prev' : 'next';

		$anchor = '<a href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>';
		$link = str_replace('%title', $title, $r['link']);
		$link = str_replace('%date', $date, $link);
		$link = $anchor . $link . '</a>';
	
		$format = str_replace('%link', $link, $r['format']);
		$format = str_replace('%title', $title, $format);
		$format = str_replace('%date', $date, $format);
		$format = str_replace('%author', $author, $format);
		if ( ($r['order_by'] == 'custom' || $r['order_by'] == 'numeric') && !empty($r['meta_key']) ) {
			$meta = get_post_meta($post->ID, $r['meta_key'], true);
			$format = str_replace('%meta', $meta, $format);
		} elseif ( $r['in_same_meta'] ) {
			$meta = get_post_meta($post->ID, $r['in_same_meta'], true);
			$format = str_replace('%meta', $meta, $format);
		}

//		Get the category list, including custom taxonomies (only if the %category variable has been used).
		if ( (strpos($format, '%category') !== false) && version_compare(PHP_VERSION, '5.0.0', '>=') ) {
			$term_list = '';
			$taxonomies = array_filter( get_post_taxonomies($post->ID), "is_taxonomy_hierarchical" );
			if ( $r['in_same_format'] && get_post_format($post->ID) )
				$taxonomies[] = 'post_format';
			foreach ( $taxonomies as &$taxonomy ) {
//				No, this is not a mistake. Yes, we are testing the result of the assignment ( = ).
//				We are doing it this way to stop it from appending a comma when there is no next term.
				if ( $next_term = get_the_term_list($post->ID, $taxonomy, '', ', ', '') ) {
					$term_list .= $next_term;
					if ( current($taxonomies) ) $term_list .= ', ';
				}
			}
			$format = str_replace('%category', $term_list, $format);
		}

//		Optionally add the post thumbnail to the link. Wrap the link in a span to aid CSS styling.
		if ( $r['thumb'] && has_post_thumbnail($post->ID) ) {
			if ( $r['thumb'] === true ) // use 'post-thumbnail' as the default size
				$r['thumb'] = 'post-thumbnail';
			$thumbnail = '<a class="post-thumbnail" href="'.get_permalink($post).'" rel="'.$rel.'"'.$tooltip.'>' . get_the_post_thumbnail( $post->ID, $r['thumb'] ) . '</a>';
			$format = $thumbnail . '<span class="post-link">' . $format . '</span>';
		}

//		If more than one link is returned, wrap them in <li> tags		
		if ( intval($r['num_results']) > 1 )
			$format = '<li>' . $format . '</li>';
		
		$output .= $format;
	}

	$output .= $r['after'];

	//	If echo is false, don't display anything. Return the link as a PHP string.
	if ( !$r['echo'] || $r['return'] === 'output' )
		return $output;

	$adjacent = $previous ? 'previous' : 'next';
	echo apply_filters( "{$adjacent}_post_link_plus", $output, $r );

	return true;
}
/**
* Title		: Aqua Resizer
* Description	: Resizes WordPress images on the fly
* Version	: 1.1.6
* Author	: Syamil MJ
* Author URI	: http://aquagraphite.com
* License	: WTFPL - http://sam.zoy.org/wtfpl/
* Documentation	: https://github.com/sy4mil/Aqua-Resizer/
*
* @param	string $url - (required) must be uploaded using wp media uploader
* @param	int $width - (required)
* @param	int $height - (optional)
* @param	bool $crop - (optional) default to soft crop
* @param	bool $single - (optional) returns an array if false
* @uses		wp_upload_dir()
* @uses		image_resize_dimensions() | image_resize()
* @uses		wp_get_image_editor()
*
* @return str|array
*/
if(!function_exists('aq_resize')) {
	function aq_resize( $url, $width, $height = null, $crop = null, $single = true ) {
	
		//validate inputs
		if(!$url OR !$width ) return false;
	
		//define upload path & dir
		$upload_info = wp_upload_dir();
		$upload_dir = $upload_info['basedir'];
		$upload_url = $upload_info['baseurl'];
	
		//check if $img_url is local
		if(strpos( $url, $upload_url ) === false) return false;
	
		//define path of image
		$rel_path = str_replace( $upload_url, '', $url);
		$img_path = $upload_dir . $rel_path;
	
		//check if img path exists, and is an image indeed
		if( !file_exists($img_path) OR !getimagesize($img_path) ) return false;
	
		//get image info
		$info = pathinfo($img_path);
		$ext = $info['extension'];
		list($orig_w,$orig_h) = getimagesize($img_path);
	
		//get image size after cropping
		$dims = image_resize_dimensions($orig_w, $orig_h, $width, $height, $crop);
		$dst_w = $dims[4];
		$dst_h = $dims[5];
	
		//use this to check if cropped image already exists, so we can return that instead
		$suffix = "{$dst_w}x{$dst_h}";
		$dst_rel_path = str_replace( '.'.$ext, '', $rel_path);
		$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";
	
		if(!$dst_h) {
			//can't resize, so return original url
			$img_url = $url;
			$dst_w = $orig_w;
			$dst_h = $orig_h;
		}
		//else check if cache exists
		elseif(file_exists($destfilename) && getimagesize($destfilename)) {
			$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
		} 
		//else, we resize the image and return the new resized image url
		else {
	
			// Note: This pre-3.5 fallback check will edited out in subsequent version
			if(function_exists('wp_get_image_editor')) {
	
				$editor = wp_get_image_editor($img_path);
	
				if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
					return false;
	
				$resized_file = $editor->save();
	
				if(!is_wp_error($resized_file)) {
					$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path']);
					$img_url = $upload_url . $resized_rel_path;
				} else {
					return false;
				}
	
			} else {
	
				$resized_img_path = image_resize( $img_path, $width, $height, $crop ); // Fallback foo
				if(!is_wp_error($resized_img_path)) {
					$resized_rel_path = str_replace( $upload_dir, '', $resized_img_path);
					$img_url = $upload_url . $resized_rel_path;
				} else {
					return false;
				}
	
			}
	
		}
	
		//return the output
		if($single) {
			//str return
			$image = $img_url;
		} else {
			//array return
			$image = array (
				0 => $img_url,
				1 => $dst_w,
				2 => $dst_h
			);
		}
	
		return $image;
	}
}

function wt_video_featured($url=0,$type='full',$layout='',$height='',$width='') {
	if($height ='' && $width=''){
		if($layout == 'full'){
			$width = 1140;
			$height = 529;
		}else{
			$width = 750;
			$height = 360;
		}
	}
    if (strpos($url, 'youtube.com') != false) {
      return wt_video_youtube($url,$type,$layout,$height,$width);
    } 
    elseif (strpos($url, 'vimeo.com') != false) {
      return wt_video_vimeo($url,$type,$layout,$height,$width);
    }
    elseif (strpos($url, 'dailymotion.com') != false) {
      return wt_video_dailymotion($url,$type,$layout,$height,$width);
    } 
    elseif (strpos($url, 'metacafe.com') != false) {
      return wt_video_metacafe($url,$type,$layout,$height,$width);
    } 
	else {
		//
	}       
}

function wt_get_video($typeurl,$url,$type='full',$layout,$height,$width) {	
    $videodiv = '<div class="wt_video_holder"';
	if($type=='left'){
		if($layout == 'full'){
			$video_width = wt_get_option('blog', 'left_width');
			$videodiv .= ' style="width:'.$video_width.'px"';
		} else {
			$video_width = wt_get_option('blog', 'sidebar_left_width');
			$videodiv .= ' style="width:'.$video_width.'px"';
		}
	}
    $videodiv .= '>';
	global $post;
    switch ($typeurl) {
      case 'youtube':	  		
	      $videodiv .= "<iframe class='youtube_video' src='http://{$url}?autohide=1&amp;autoplay=0&amp;controls=1&amp;disablekb=0&amp;fs=1&amp;hd=0&amp;loop=0&amp;rel=0&amp;showinfo=0&amp;showsearch=0&amp;wmode=transparent&amp;enablejsapi=0' width={$width} height={$height} frameborder='0'></iframe>";		
      	  break;
      case 'vimeo':		
		  $videodiv .= "<iframe class='vimeo_video' src='http://player.vimeo.com/video/{$url}?title=0&amp;byline=0&amp;portrait=0&amp;autoplay=0&amp;loop=0' width='$width' height='$height' frameborder='0'></iframe>";
          break;
      case 'dailymotion':
		   $videodiv .= "<iframe class='dailymotion_video' src='http://www.dailymotion.com/embed/video/{$url}?width=$width&amp;autoPlay=0&amp;related=0&amp;chromeless=0&amp;expandVideo=1&amp;theme=none&amp;foreground=%23F7FFFD&amp;highlight=%23FFC300&amp;background=%23171D1B&amp;iframe=1&amp;wmode=transparent' width='$width' height='$height' frameborder='0'></iframe>";
          break;
      case 'metacafe':
		  $videodiv .= "<embed flashVars='playerVars=autoPlay=no' src='http://www.metacafe.com/fplayer/{$url}.swf' width='{$width}' height='{$height}' wmode='transparent' allowFullScreen='true' allowScriptAccess='always' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed>";		
          break;
    }
    $videodiv .= "</div>";
    return $videodiv;
}
?>
<?php
function wt_video_youtube($url,$type,$layout,$height,$width) {
    $videourl = wt_get_youtube_url($url);
    $embed_object = wt_get_video('youtube', $videourl,$type,$layout,$height,$width);
    return $embed_object;  
}
// Needed for featured video
function wt_get_youtube_url($url) {    
    if (strpos($url, 'embed') != false) {
      return wt_get_youtube($url);
    }
    if (strpos($url, 'feature=hd') != false) {
    }
    if (!preg_match('/(http:\/\/)([a-zA-Z]{2,3}\.)(youtube\.com\/)(.*)/', $url, $matches)) {
      return;    
    }
    $domain = $matches[2] . $matches[3];
    $path = $matches[4]; 
    if (!preg_match('/^(watch\?v=)([a-zA-Z0-9_-]*)(&.*)?$/',$path, $matches)) {
      return;        
    }
    $hash = $matches[2];
    return $domain . 'embed/' . $hash;
}

function wt_get_youtube($url) {
    if (!preg_match('/(value=")(http:\/\/)([a-zA-Z]{2,3}\.)(youtube\.com\/)(v\/)([a-zA-Z0-9_-]*)(&hl=[a-zA-Z]{2})(.*")/', $url, $matches)) {
      return;    
    }
    $domain = $matches[3] . $matches[4];
    $hash   = $matches[6];        
    return $domain . 'v/' . $hash;  
}

function wt_video_vimeo($url,$type,$layout,$height,$width) {
    $videourl = wt_get_vimeo_url($url);
    $embed_object = wt_get_video('vimeo', $videourl,$type,$layout,$height,$width);
    return $embed_object;   
}

// Needed for featured video
function wt_get_vimeo_url($url) {        
    if (strpos($url, 'object') != false) {
      return wt_get_vimeo($url);
    }
    if (strpos($url, 'groups') != false) {
      if (!preg_match('/(http:\/\/)(www\.)?(vimeo\.com\/groups)(.*)(\/videos\/)([0-9]*)/', $url, $matches)) {
        return;    
      }
      $hash = $matches[6];
    }
    else {
      if (!preg_match('/(http:\/\/)(www\.)?(vimeo.com\/)([0-9]*)/', $url, $matches)) {
        return;    
      }
      $hash = $matches[4];
    }
    return $hash;
}

function wt_get_vimeo($url) {
    if (!preg_match('/(value="http:\/\/vimeo\.com\/moogaloop\.swf\?clip_id=)([0-9-]*)(&)(.*" \/)/', $url, $matches)) {
      return;    
    }
    $hash   = $matches[2];
    return $hash;  
}

function wt_video_dailymotion($url,$type,$layout,$height,$width) {
    $videourl = wt_get_dailymotion_url($url);
    $embed_object = wt_get_video('dailymotion', $videourl,$type,$layout,$height,$width);
    
    return $embed_object;   
}

// Needed for featured video
function wt_get_dailymotion_url($url) {        
    if (strpos($url, 'embed') != false) {
      return wt_get_dailymotion($url);
    }
    if (!preg_match('/(http:\/\/www\.dailymotion\.com\/.*\/)([0-9a-z]*)/', $url, $matches)) {
      return;    
    }          
    $hash = $matches[2];
    return $hash;
}

function wt_video_metacafe($url,$type,$layout,$height,$width) {
    $videourl = wt_get_metacafe_url($url);
    $embed_object = wt_get_video('metacafe', $videourl,$type,$layout,$height,$width);
    return $embed_object;   
}

// Needed for featured video
function wt_get_metacafe_url($url) {    
	if (strpos($url, 'fplayer') != false) {
      return wt_get_metacafe($url);
    }
    if (!preg_match('/(http:\/\/)(www\.)?(metacafe\.com\/watch\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)/', $url, $matches)) {
      return;    
    }
    $hash = $matches[4] . $matches[5];
    return $hash;
}

function wt_get_metacafe($url) {
     if (!preg_match('/(http:\/\/)(www\.)?(metacafe\.com\/fplayer\/)([0-9a-zA-Z_-]*)(\/[0-9a-zA-Z_-]*)(.swf)/', $url, $matches)) {
      return;    
    }
   	$hash = $matches[4] . $matches[5];  
    return $hash;
}

function wt_media_player($type='full',$layout='',$height='',$width='') {
	
	if($layout == 'full'){
		$width = 1140;
	}else{
		$width = 750;
	}
	if($type=='left'){
		if($layout == 'full'){
			$width = wt_get_option('blog', 'left_width');
		} else {
			$width = wt_get_option('blog', 'sidebar_left_width');
		}
	}
	$uri = THEME_URI;
    global $post;
	$player_link = get_post_meta($post->ID,'_thumbnail_player', true);	
	
	$html_output='';
	$media = '';
	
	$media .= '<iframe width="100%" height="166px" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=' . urlencode($player_link) . '&amp;show_comments=false&amp;auto_play=false"></iframe>';
		
	$html_output .= '<div class="wt_audio_holder"';
	if($type=='left'){
		$html_output .= ' style="width:'.$width.'px"';
	}
	$html_output .= '>'.$media.'</div>';
		
	return $html_output;	

}

function wt_hex2rgb( $colour ) {
        if ( $colour[0] == '#' ) {
                $colour = substr( $colour, 1 );
        }
        if ( strlen( $colour ) == 6 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[1], $colour[2] . $colour[3], $colour[4] . $colour[5] );
        } elseif ( strlen( $colour ) == 3 ) {
                list( $r, $g, $b ) = array( $colour[0] . $colour[0], $colour[1] . $colour[1], $colour[2] . $colour[2] );
        } else {
                return false;
        }
        $r = hexdec( $r );
        $g = hexdec( $g );
        $b = hexdec( $b );
		$rgb = $r.','.$g.','.$b;
        return $rgb;
}

function wt_get_slide($type='full',$layout='',$set_width='',$set_height='') {
			
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
			$height = wt_get_option('blog', 'left_slide_height');
		} else {
			$inline_width = wt_get_option('blog', 'sidebar_left_width'); // Sidebar Layout - left image inline width
			$height = wt_get_option('blog', 'sidebar_left_slide_height');
		}
	}else{
		if($layout == 'full'){
			$height = wt_get_option('blog', 'slide_height');
		} else {
			$height = wt_get_option('blog', 'sidebar_slide_height');
		}
	}	
	
	$uri = THEME_URI;
    global $post;
	$html_output='';
	$content = '';
	
	$slide_type = get_post_meta($post->ID,'_slide_type', true);
	$flex_effect = get_post_meta($post->ID,'_flex_slide_effect', true);
	$nivo_effect = get_post_meta($post->ID,'_slide_effect', true);
	
	if ($slide_type == "flex") { 
		$li = '<li>';
		$end_li = '</li>';
	} else {
		$li = '';
		$end_li = '';
	}
	
	if( class_exists('Dynamic_Featured_Image') ) {	
		ob_start();	
		global $dynamic_featured_image;
		$featured_images = $dynamic_featured_image->get_featured_images( $post->ID );
		if (has_post_thumbnail()) {
			$image_src_array = wp_get_attachment_image_src(get_post_thumbnail_id(),'full', true);
			$image_src = wt_get_image_src($image_src_array[0]);
			
			$title = get_post(get_post_thumbnail_id())->post_title; //The Title
			$alt = get_post_meta(get_post_thumbnail_id(), '_wp_attachment_image_alt', true); //The Alt
			$caption = get_post(get_post_thumbnail_id())->post_excerpt; //The Caption
			$description = get_post(get_post_thumbnail_id())->post_content; // The Description
		}
		if( !is_null($featured_images) ){
			
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
			
			// The WP Featured Image 
			if (has_post_thumbnail()) {
				$content .= $li . '<img src="'.aq_resize( $image_src, $width, $height, true ).'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'" />' . $end_li;
			}
			
			// The others Dynamic Featured Images
			foreach($featured_images as $images) {
				  
				$image_url = $images['full'];
				
				$title = $dynamic_featured_image -> get_image_title( $image_url ); //The (dynamic image) Title
				$alt = $dynamic_featured_image -> get_image_alt( $image_url ); //The (dynamic image) Alt
				$caption = $dynamic_featured_image -> get_image_caption( $image_url ); //The (dynamic image) Caption
				$description = $dynamic_featured_image -> get_image_description( $image_url ); // The (dynamic image) Description

				$content .= $li . '<img src="'.aq_resize( $image_url, $width, $height, true ).'" alt="'.$title.'" width="'.$width.'" height="'.$height.'" />' . $end_li;
			}
		}
		ob_get_clean();
	}
	if ($slide_type == "owl") {
		wp_print_scripts('owlCarousel');
		$html_output .= '<div class="wt_slide_holder wt_owl_rotator" data-owl-autoPlay="7000" data-owl-stopOnHover="true" data-owl-navigation="false" data-owl-slideSpeed="800" data-owl-pagination="true" data-owl-pagSpeed="800" data-owl-autoHeight="true"';
		if($type=='left'){
			$html_output .=  'style="width:'.$inline_width.'px"'; 
		}
		$html_output .= '>';	
		$html_output .= $content;
		$html_output .= '</div>';	
	}
	elseif ($slide_type == "nivo") {
		wp_print_scripts('nivo');
		$html_output .= '<div class="wt_slide_holder nivo_container"';
		if($type=='left'){
			$html_output .=  ' style="width:'.$inline_width.'px"'; 
		}
		$html_output .= '>'.'<div class="nivoslider_wrap" data-nivo_effect="'.$nivo_effect.'" data-nivo_slices="10" data-nivo_boxCols="8"  data-nivo_boxRows="4" data-nivo_animSpeed="500" data-nivo_pauseTime="7000" data-nivo_directionNav="true" data-nivo_controlNav="true" data-nivo_controlNavThumbs="false" data-nivo_pauseOnHover="true" data-nivo_manualAdvance="false">'.$content.'</div></div>';	
	} else {
		wp_print_scripts('flex');
		$html_output .= '<div class="wt_slide_holder flex_container"';
		if($type=='left'){
			$html_output .=  ' style="width:'.$inline_width.'px"'; 
		}
		$html_output .= '>'.'<div class="flexslider_wrap flexslider" data-flex_animation="'.$flex_effect.'" data-flex_easing="easeOutCirc"  data-flex_direction="horizontal" data-flex_animationSpeed="800" data-flex_slideshowSpeed="7000" data-flex_directionNav="true" data-flex_controlNav="true" data-flex_controlNavThumbs="false"  data-flex_controlNavThumbsSlider="false" data-flex_pauseOnAction="true" data-flex_pauseOnHover="true" data-flex_slideshow="true" data-flex_animationLoop="true">';
		$html_output .= '<ul class="slides">'."\n\t\t\t";
		$html_output .= $content;
		$html_output .= '</ul>'."\n\t\t\t";	
		$html_output .= '</div></div>';
	}
	return $html_output;
}

/**
 * Check if current page is login page.
 *
 * @return boolean
 */
function wt_is_login_page() {

	return in_array( $GLOBALS['pagenow'], array( 'wp-login.php', 'wp-register.php' ) );
}

/**
 * Get current admin page name.
 *
 * @return string
 */
function wt_get_current_page_name() {

	if ( isset($GLOBALS['pagenow']) && is_admin() ) {
		return $GLOBALS['pagenow'];
	} else {
		return false;
	}
}

function wt_add_cufon_code(){
	$code = stripslashes(wt_get_option('fonts','cufon_code'));
	$cufonfonts = wt_get_option('fonts','cufonfonts');
	if(trim($code) == '' && isset($cufonfonts[0])){
		$file_content = file_get_contents(THEME_FONT_DIR.'/'.$cufonfonts[0]);
		if(preg_match('/font-family":"(.*?)"/i',$file_content,$match)){
			$font_name = $match[1];
		}
		if($font_name){
			$code = <<<CODE
			Cufon.replace("h1, h2, h3, h4, h5, #intro p, #logo_text #logo, .dropcap1, .dropcap2, .dropcap3, .custom_links span, .pp_description", {fontFamily : "{$font_name}"});
Cufon.replace("#nav a.level-1-a", {
	hover: true,
	fontFamily : "{$font_name}"
});
CODE;
		}
	}
	echo <<<HTML
<script type='text/javascript'>
{$code}
</script>
HTML;
}
?>
<?php

function wt_add_cufon_code_footer(){
if(wt_get_option('fonts','enable_cufon')){
	echo <<<HTML
<script type='text/javascript'>
HTML;
	echo <<<HTML
Cufon.now();
HTML;
	echo <<<HTML
if(jQuery.browser.msie && parseInt(jQuery.browser.version, 10)==8){
	jQuery("#nav a.level-1-a ul").css({display:'block', visibility:'hidden'});
}
</script>
HTML;
}
}
?>
<?php
function wt_add_buoop(){
	echo <<<HTML
<script type="text/javascript"> 
/* <![CDATA[ */
var $buoop = {vs:{i:7,f:3.6,o:10.6,s:4,n:9}}
$buoop.ol = window.onload; 
window.onload=function(){ 
 try {if ($buoop.ol) $buoop.ol();}catch (e) {} 
 var e = document.createElement("script"); 
 e.setAttribute("type", "text/javascript"); 
 e.setAttribute("src", "http://browser-update.org/update.js"); 
 document.body.appendChild(e); 
} 
/* ]]> */
</script> 
HTML;
}
?>
<?php
/* Style Switcher */
add_action('wp_ajax_wt_style_switcher', 'wt_switcher');
add_action('wp_ajax_nopriv_wt_style_switcher', 'wt_switcher');
function wt_switcher() {
	global $wt_skin;
	$wt_skin = array();

	$color = $_POST['color'];

	$wt_skin = array_merge($wt_skin, $color);

	ob_start();
	include(locate_template('style_switcher_skin.php', false));
	$html = ob_get_clean();

	echo $html;

	die();
}

?>
<?php
/*------------------------------------------------*/
/*	- Dynamic Featured Image custom post
/*------------------------------------------------*/

add_filter('dfi_post_types', 'filter_post_types');
function filter_post_types() {
	return array('post', 'page', 'wt_portfolio'); //will display DFI in post and page
}


/*------------------------------------------------*/
/*	- Visual Composer Tweaks
/*------------------------------------------------*/

if (class_exists('WPBakeryVisualComposerAbstract')) {
	
	// Set Visual Composer to run in Theme Mode - Remove Visual Composer notifier
	if( function_exists( 'vc_set_as_theme' ) ) {		
		vc_set_as_theme(true);
	}
	
	// Override directory where Visual Composer should look for template files for content elements
	if( function_exists( 'vc_set_shortcodes_templates_dir' ) ) {	
		$templates_dir = THEME_FUNCTIONS . '/visual-composer/wt_vcsc_templates/';
		vc_set_shortcodes_templates_dir($templates_dir);
	}

	// Remove certain default VC modules
	require_once( THEME_FUNCTIONS . '/visual-composer/remove.php' );	
		
	// Make js composer stylesheet to load in onepage themes.
	add_action('wp_enqueue_scripts', 'force_js_composer_front_load');
	function force_js_composer_front_load() {
		wp_enqueue_style('js_composer_front');
	}
	
}

/*------------------------------------------------*/
/*	- TGM Plugins Activation
/*------------------------------------------------*/

add_action('tgmpa_register', 'wt_register_required_plugins');
function wt_register_required_plugins() {
	$plugins = array(	
		array(
            'name'					=> 'WPBakery Js Visual Composer', // The plugin name
            'slug'					=> 'js_composer', // The plugin slug (typically the folder name)
            'source'				=> get_template_directory_uri() . '/framework/plugins/js_composer.zip', // The plugin source
            'required'				=> true, // If false, the plugin is only 'recommended' instead of required
            'version'				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'		=> '', // If set, overrides default API URL and points to an external URL
        ),
		array(
			'name'     				=> 'Mailchimp', // The plugin name
			'slug'     				=> 'mailchimp-for-wp', // The plugin slug (typically the folder name)
			'source'   				=> get_template_directory_uri() . '/framework/plugins/mailchimp-for-wp.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		/*
		array(
            'name'					=> 'Visual Composer Extensions', // The plugin name
            'slug'					=> 'ts-visual-composer-extend', // The plugin slug (typically the folder name)
            'source'				=> get_template_directory_uri() . '/framework/plugins/ts-visual-composer-extend.zip', // The plugin source
            'required'				=> true, // If false, the plugin is only 'recommended' instead of required
            'version'				=> '2.3.0', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'		=> '', // If set, overrides default API URL and points to an external URL
        ),
		*/
		array(
			'name'     				=> 'Dynamic Featured Image', // The plugin name
			'slug'     				=> 'dynamic-featured-image', // The plugin slug (typically the folder name)
			'source'   				=> get_template_directory_uri() . '/framework/plugins/dynamic-featured-image.zip', // The plugin source
			'required' 				=> false, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),
		/*array(
			'name'     				=> 'RevSlider', // The plugin name
			'slug'     				=> 'revslider', // The plugin slug (typically the folder name)
			'source'   				=> get_template_directory_uri() . '/framework/plugins/revslider.zip', // The plugin source
			'required' 				=> true, // If false, the plugin is only 'recommended' instead of required
			'version' 				=> '', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
			'force_activation' 		=> false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
			'force_deactivation' 	=> true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
			'external_url' 			=> '', // If set, overrides default API URL and points to an external URL
		),*/
	);

	// Change this to your theme text domain, used for internationalising strings
	$theme_text_domain = 'wt';

	/**
	 * Array of configuration settings. Amend each line as needed.
	 * If you want the default strings to be available under your own theme domain,
	 * leave the strings uncommented.
	 * Some of the strings are added into a sprintf, so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'domain'       		=> $theme_text_domain,         	// Text domain - likely want to be the same as your theme.
		'default_path' 		=> '',                         	// Default absolute path to pre-packaged plugins
		'parent_menu_slug' 	=> 'admin.php', 				// Default parent menu slug
		'parent_url_slug' 	=> 'admin.php', 				// Default parent URL slug
		'menu'         		=> 'install-required-plugins', 	// Menu slug
		'has_notices'      	=> true,                       	// Show admin notices or not
		'is_automatic'    	=> true,					   	// Automatically activate plugins after installation or not
		'message' 			=> '',							// Message to output right before the plugins table
		'strings'      		=> array(
			'page_title'                       			=> __( 'Install Required Plugins', $theme_text_domain ),
			'menu_title'                       			=> __( 'Install Plugins', $theme_text_domain ),
			'installing'                       			=> __( 'Installing Plugin: %s', $theme_text_domain ), // %1$s = plugin name
			'oops'                             			=> __( 'Something went wrong with the plugin API.', $theme_text_domain ),
			'notice_can_install_required'     			=> _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_install_recommended'			=> _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_install'  					=> _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
			'notice_can_activate_required'    			=> _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_can_activate_recommended'			=> _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_activate' 					=> _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
			'notice_ask_to_update' 						=> _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
			'notice_cannot_update' 						=> _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
			'install_link' 					  			=> _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
			'activate_link' 				  			=> _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
			'return'                           			=> __( 'Return to Required Plugins Installer', $theme_text_domain ),
			'plugin_activated'                 			=> __( 'Plugin activated successfully.', $theme_text_domain ),
			'complete' 									=> __( 'All plugins installed and activated successfully. %s', $theme_text_domain ), // %1$s = dashboard link
			'nag_type'									=> 'updated' // Determines admin notice type - can only be 'updated' or 'error'
		)
	);

	tgmpa($plugins, $config);
}

function wt_get_superlink($link, $default=''){
	if(!empty($link)){
		$link_array = explode('||',$link);
		switch($link_array[0]){
			case 'page':
				return get_page_link($link_array[1]);
			case 'cat':
				return get_category_link($link_array[1]);
			case 'post':
				return get_permalink($link_array[1]);
			case 'wt_portfolio':
				return get_permalink($link_array[1]);
			case 'manually':
				return $link_array[1];
		}
	}
	return $default;
}
if ( ! isset( $content_width ) )
	$content_width = 1140;
load_theme_textdomain('wt_front');

/**
 * Detecting mobile devices
 */
 
if (class_exists('Mobile_Detect')) {
	global $wt_mobile_detect;
	$wt_mobile_detect = new Mobile_Detect;
} else {
	require_once 'mobile_detect.php';
	global $wt_mobile_detect;
	$wt_mobile_detect = new Mobile_Detect;
}
?>