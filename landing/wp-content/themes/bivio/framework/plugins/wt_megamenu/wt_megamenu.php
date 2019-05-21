<?php

class wt_mega_menu {

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	public $wt_menu = false;
	public $wt_columns = 1;
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		// add custom menu fields to menu
		add_filter( 'wp_setup_nav_menu_item', array( $this, 'wt_mega_menu_add_custom_nav_fields' ) );

		// save menu custom fields
		add_action( 'wp_update_nav_menu_item', array( $this, 'wt_mega_menu_update_custom_nav_fields'), 10, 3 );
		
		// edit menu walker
		add_filter( 'wp_edit_nav_menu_walker', array( $this, 'wt_mega_menu_edit_walker'), 10, 2 );
		
		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
		
	} // end constructor
	


	/**
	 * Add custom fields to $item nav object
	 * in order to be used in custom Walker
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function wt_mega_menu_add_custom_nav_fields( $menu_item ) {
	
	    
	    // common
		$menu_item->wt_megamenu_icon = get_post_meta( $menu_item->ID, '_menu_item_wt_icon', true );

		// first level
		$menu_item->wt_megamenu_enabled = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_enabled', true );
		$menu_item->wt_megamenu_fullwidth = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_fullwidth', true );
		$menu_item->wt_megamenu_columns = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_columns', true );

		// other levels
		$menu_item->wt_megamenu_styled_title = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_styled_title', true );
		$menu_item->wt_megamenu_hide_title = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_hide_title', true );
		$menu_item->wt_megamenu_remove_link = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_remove_link', true );
		$menu_item->wt_megamenu_html_content = get_post_meta( $menu_item->ID, '_menu_item_wt_megamenu_html_content', true );

		return $menu_item;
	}
	
	/**
	 * Save menu custom fields
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function wt_mega_menu_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

		// Check if element is properly sent
		if ( isset($_REQUEST['menu-item-wt-icon']) && is_array( $_REQUEST['menu-item-wt-icon'] ) ) {
			$icon = $_REQUEST['menu-item-wt-icon'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_wt_icon', $icon );
		}

		$enable_mega_menu = isset($_REQUEST['menu-item-wt-enable-megamenu'], $_REQUEST['menu-item-wt-enable-megamenu'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_enabled', $enable_mega_menu );

		$fullwidth = isset($_REQUEST['menu-item-wt-fullwidth-menu'], $_REQUEST['menu-item-wt-fullwidth-menu'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_fullwidth', $fullwidth );

		if ( isset($_REQUEST['menu-item-wt-columns']) && is_array( $_REQUEST['menu-item-wt-columns'] ) ) {
			$columns = absint($_REQUEST['menu-item-wt-columns'][$menu_item_db_id]);
			update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_columns', $columns );
		}

		$styled_title = isset($_REQUEST['menu-item-wt-styled-title'], $_REQUEST['menu-item-wt-styled-title'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_styled_title', $styled_title );
		
		$hide_title = isset($_REQUEST['menu-item-wt-hide-title'], $_REQUEST['menu-item-wt-hide-title'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_hide_title', $hide_title );

		$remove_link = isset($_REQUEST['menu-item-wt-remove-link'], $_REQUEST['menu-item-wt-remove-link'][$menu_item_db_id]);
		update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_remove_link', $remove_link );

		if ( !isset($_REQUEST['menu-item-wt-html-content'])) {
			$html_content = $_REQUEST['menu-item-wt-html-content'][$menu_item_db_id];
			update_post_meta( $menu_item_db_id, '_menu_item_wt_megamenu_html_content', $html_content );
		}
	    
	}
	
	/**
	 * Define new Walker edit
	 *
	 * @access      public
	 * @since       1.0 
	 * @return      void
	*/
	function wt_mega_menu_edit_walker($walker,$menu_id) {
	
	    return 'Walker_Nav_Menu_Edit_Custom';
	    
	}


	function register_admin_styles() {

		wp_enqueue_style( 'megamenu-styles', get_template_directory_uri() . '/framework/plugins/wt_megamenu/css/megamenu.css');

	}
	
	function register_admin_scripts() {

		wp_enqueue_script( 'jquery-ui-button' );
		wp_enqueue_script( 'megamenu-script', get_template_directory_uri() . '/framework/plugins/wt_megamenu/js/admin-megamenu.js');

	}

}

// instantiate plugin's class
$GLOBALS['wt_mega_menu'] = new wt_mega_menu();


include_once( 'edit_custom_walker.php' );
include_once( 'custom_walker.php' );