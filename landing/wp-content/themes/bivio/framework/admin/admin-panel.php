<?php
class wt_admin {
	function wt_init(){
		$this->wt_functions();
		add_action('admin_menu', array(&$this,'wt_menus'));
		add_action('admin_notices',  array(&$this,'wt_warnings'));
		
		$this->wt_post_types();
		$this->wt_metaboxes();	
		add_action('wp_ajax_theme-flush-rewrite-rules', array(&$this,'flush_rewrite_rules'));
		
		add_action('admin_init', array(&$this,'wt_after_theme_is_activated'));
	}
	/**
	 * Check if users need to set the file permissions in order to support the theme, and if not, displays warnings messages in admin option page.
	 */
	function wt_warnings(){
		global $wp_version;

		$warnings = array();
		if(!wt_check_wp_version()){
			$warnings[]='Wordpress version(<b>'.$wp_version.'</b>) is too low. Please upgrade to the latest version.';
		}
		if(!function_exists("imagecreatetruecolor")){
			$warnings[]='GD Library Error: <b>imagecreatetruecolor does not exist</b>. Please contact your host provider and ask them to install the GD library, otherwise this theme won\'t work properly.';
		}
		if(!is_writeable(THEME_CACHE_DIR)){
			$warnings[]='The cache folder (<b>'.str_replace( WP_CONTENT_DIR, '', THEME_CACHE_DIR ).'</b>) is not writeable. Please set the correct file permissions (<b>\'777\' or \'755\'</b>), otherwise this theme won\'t work properly.';
		}		
		if(!file_exists(THEME_CACHE_DIR.DIRECTORY_SEPARATOR.'skin.css')){
			$warnings[]='The skin style file (<b>'.str_replace( WP_CONTENT_DIR, '', THEME_CACHE_DIR ).'/skin.css'.'</b>) doesn\'t exists or it was deleted. Please manually create this file or click on \'Save changes\' and it will be automatically created.';
		}
		if(!is_writeable(THEME_CACHE_DIR.DIRECTORY_SEPARATOR.'skin.css')){
			$warnings[]='The skin style file (<b>'.str_replace( WP_CONTENT_DIR, '', THEME_CACHE_DIR ).'/skin.css'.'</b>) is not writeable. Please set the correct permissions (<b>\'777\' or \'755\'</b>), otherwise this theme won\'t work properly.';
		}			
		
		$str = '';
		if(!empty($warnings)){
			$str = '<ul>';
			foreach($warnings as $warning){
				$str .= '<li>'.$warning.'</li>';
			}
			$str .= '</ul>';
			echo "
				<div id='theme-warning' class='error fade'><p><strong>".sprintf(__('%1$s Error Messages','wt_admin'), THEME_NAME)."</strong><br/>".$str."</p></div>
			";
		}
		
	}
	function wt_functions(){
		require_once(THEME_ADMIN_FUNCTIONS .'/theme-functions.php');
		require_once(THEME_ADMIN_FUNCTIONS .'/custom_scripts.php');
		require_once(THEME_ADMIN_FUNCTIONS .'/option-media-upload.php');
	}
	/**
	 * Create theme options menu
	 */
	function wt_menus(){
		add_menu_page(THEME_NAME, THEME_NAME, 'edit_theme_options', 'general', array(&$this,'_load_option_page'),'', '59.7');
		add_submenu_page('general', 'General', 'General', 'edit_theme_options', 'general', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Blog', 'Blog', 'edit_theme_options', 'blog', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Portfolio', 'Portfolio', 'edit_theme_options', 'portfolio', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Fonts', 'Fonts', 'edit_theme_options', 'fonts', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Background', 'Background', 'edit_theme_options', 'background', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Color', 'Color', 'edit_theme_options', 'color', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Sidebar', 'Sidebar', 'edit_theme_options', 'sidebar', array(&$this,'_load_option_page'));
		//add_submenu_page('general', 'Top Widget', 'Top Widget', 'edit_theme_options', 'top_widget', array(&$this,'_load_option_page'));
		add_submenu_page('general', 'Footer', 'Footer', 'edit_theme_options', 'footer', array(&$this,'_load_option_page'));
		if(class_exists('Wt_twitter')) {
			add_submenu_page('general', 'Twitter', 'Twitter', 'edit_theme_options', 'twitter', array(&$this,'_load_option_page'));
		}
	}
	
	/**
	 * call and display the requested options page
 	 */
	function _load_option_page(){
		include_once (THEME_FILES . '/options.php');
		$page = include(THEME_ADMIN_OPTIONS . "/" . $_GET['page'] . '.php');
	
		if($page['auto']){
			new wt_options($page['name'],$page['options']);
		}
	}
	/**
	 * Manage custom post type.
	 */
	function wt_post_types(){
		require_once (THEME_ADMIN_TYPES . '/portfolio.php');
	}
	
	/**
	 * Create post type metabox.
	 */
	function wt_metaboxes(){
		require_once (THEME_FILES . '/metaboxes.php');
		require_once (THEME_ADMIN_METABOXES . '/portfolio.php');
		require_once (THEME_ADMIN_METABOXES . '/section.php');
		require_once (THEME_ADMIN_METABOXES . '/page_general.php');
		require_once (THEME_ADMIN_METABOXES . '/page_bg.php');
		require_once (THEME_ADMIN_METABOXES . '/single.php');
		require_once (THEME_ADMIN_METABOXES . '/featured_video.php');
		require_once (THEME_ADMIN_METABOXES . '/product_options.php');
	}
	
	function flush_rewrite_rules(){
		flush_rewrite_rules();
		die (1);
	}
	
	function wt_after_theme_is_activated(){
		if ('themes.php' == basename($_SERVER['PHP_SELF']) && isset($_GET['activated']) && $_GET['activated']=='true' ) {
			wt_generate_skin_css();
			wp_redirect( admin_url('admin.php?page=general') );
		}
	}
}