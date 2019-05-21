<?php

/**
 * Check Whether the current wordpress version is support for the theme.
 */
function wt_check_wp_version(){
	global $wp_version;
	
	$check_WP   = '3.7';
	$is_ok  =  version_compare($wp_version, $check_WP, '>=');
	
	if ( ($is_ok == FALSE) ) {
		return false;
	}
	
	return true;
}

function wt_generate_skin_css() {
	if (is_writable(THEME_CACHE_DIR)) {
		if(is_multisite()){
			global $blog_id;
			$file = THEME_CACHE_DIR.'/skin_'.$blog_id.'.css';
		}else{
			$file = THEME_CACHE_DIR.'/skin.css';
		}
		$fhandle = @fopen($file, 'w+');
		$content = include(THEME_FUNCTIONS.'/skin.php');
		$content = preg_replace('/\n(\s*\n){1,}/', "\n", $content); // remove empty lines
		if ($fhandle) fwrite($fhandle, $content, strlen($content));
	}
	return false;
}

/**
 * Whether the current request is in theme options pages
 * 
 * @param mixed $post_types
 * @return bool True if inside theme options pages.
 */
function wt_is_options() {
	if ('admin.php' == basename($_SERVER['PHP_SELF'])) {
		return true;
	}
	// to be add some check code for validate only in theme options pages
	return false;
}
/**
 * Whether the current request is in post type pages
 * 
 * @param mixed $post_types
 * @return bool True if inside post type pages
 */
function wt_is_post_type($post_types = ''){
	if(wt_is_post_type_list($post_types) || wt_is_post_type_new($post_types) || wt_is_post_type_edit($post_types) || wt_is_post_type_post($post_types) || wt_is_post_type_taxonomy($post_types)){
		return true;
	}else{
		return false;
	}
}
/**
 * Whether the current request is in post type list page
 * 
 * @param mixed $post_types
 * @return bool True if inside post type list page
 */
function wt_is_post_type_list($post_types = '') {
	if ('edit.php' != basename($_SERVER['PHP_SELF'])) {
		return false;
	}
	if ($post_types == '') {
		return true;
	} else {
		$check = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($_POST['post_type']) ? $_POST['post_type'] : 'post');
		if (is_string($post_types) && $check == $post_types) {
			return true;
		} elseif (is_array($post_types) && in_array($check, $post_types)) {
			return true;
		}
		return false;
	}
}

/**
 * Whether the current request is in post type new page
 * 
 * @param mixed $post_types
 * @return bool True if inside post type new page
 */
function wt_is_post_type_new($post_types = '') {
	if ('post-new.php' != basename($_SERVER['PHP_SELF'])) {
		return false;
	}
	if ($post_types == '') {
		return true;
	} else {
		$check = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($_POST['post_type']) ? $_POST['post_type'] : 'post');
		if (is_string($post_types) && $check == $post_types) {
			return true;
		} elseif (is_array($post_types) && in_array($check, $post_types)) {
			return true;
		}
		return false;
	}
}
/**
 * Whether the current request is in post type post page
 * 
 * @param mixed $post_types
 * @return bool True if inside post type post page
 */
function wt_is_post_type_post($post_types = '') {
	if ('post.php' != basename($_SERVER['PHP_SELF'])) {
		return false;
	}
	if ($post_types == '') {
		return true;
	} else {
		$post = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post']) ? $_POST['post'] : false);
		$check = get_post_type($post);
		
		if (is_string($post_types) && $check == $post_types) {
			return true;
		} elseif (is_array($post_types) && in_array($check, $post_types)) {
			return true;
		}
		return false;
	}
}
/**
 * Whether the current request is in post type edit page
 * 
 * @param mixed $post_types
 * @return bool True if inside post type edit page
 */
function wt_is_post_type_edit($post_types = '') {
	if ('post.php' != basename($_SERVER['PHP_SELF'])) {
		return false;
	}
	$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');
	if ('edit' != $action) {
		return false;
	}
	
	if ($post_types == '') {
		return true;
	} else {
		$post = isset($_GET['post']) ? $_GET['post'] : (isset($_POST['post']) ? $_POST['post'] : false);
		$check = get_post_type($post);
		
		if (is_string($post_types) && $check == $post_types) {
			return true;
		} elseif (is_array($post_types) && in_array($check, $post_types)) {
			return true;
		}
		return false;
	}
}
/**
 * Whether the current request is in post type taxonomy pages
 * 
 * @param mixed $post_types
 * @return bool True if inside post type taxonomy pages
 */
function wt_is_post_type_taxonomy($post_types = '') {
	if ('edit-tags.php' != basename($_SERVER['PHP_SELF'])) {
		return false;
	}
	if ($post_types == '') {
		return true;
	} else {
		$check = isset($_GET['post_type']) ? $_GET['post_type'] : (isset($_POST['post_type']) ? $_POST['post_type'] : 'post');
		if (is_string($post_types) && $check == $post_types) {
			return true;
		} elseif (is_array($post_types) && in_array($check, $post_types)) {
			return true;
		}
		return false;
	}
}

add_action( 'update_option_page_on_front', 'wt_set_page_on_front',10,2);

function wt_set_page_on_front($old, $new){
	wt_set_option('general','home_page',$old);
}

/*add_action( 'update_option_page_for_posts', 'wt_set_page_for_posts',10,2);
function wt_set_page_for_posts($old, $new){
	wt_set_option('blog','blog_page',$new);
}*/

/**
 * Featured Videos
 * 
 */
 
function wt_featured_video($url=0,$type='',$layout='',$height='',$width='') {
	$width = 256;
	$height = 144;
	
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
set_post_thumbnail_size( 256,144, true);