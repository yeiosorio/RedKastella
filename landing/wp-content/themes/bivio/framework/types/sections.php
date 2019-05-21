<?php
/*-----------------------------------------------------------------------------------*/
/* Register Custom Post Types - Sections */
/*-----------------------------------------------------------------------------------*/
function wt_register_section_post_type(){
	// Rewriting Permalink Slug
	$permalink_slug = trim(wt_get_option('section','permalink_slug'));
	if ( empty($permalink_slug) ) {
		$permalink_slug = 'section';
	}
	register_post_type('wt_section', array(
		'labels' => array(
			'name' => _x('Sections', 'post type general name', 'wt_admin' ),
			'singular_name' => _x('Sections', 'post type singular name', 'wt_admin' ),
			'add_new' => _x('Add New', 'wt_section', 'wt_admin' ),
			'add_new_item' => __('Add New Section', 'wt_admin' ),
			'edit_item' => __('Edit Section', 'wt_admin' ),
			'new_item' => __('New Section', 'wt_admin' ),
			'view_item' => __('View Section', 'wt_admin' ),
			'search_items' => __('Search Section', 'wt_admin' ),
			'not_found' =>  __('No section found', 'wt_admin' ),
			'not_found_in_trash' => __('No section found in Trash', 'wt_admin' ), 
			'parent_item_colon' => '',
			'menu_name' => __('Sections', 'wt_admin' ),
		),
		'singular_label' => __('wt_section', 'wt_admin' ),
		'public' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'show_ui' => true,
		'show_in_menu' => true,
		//'menu_position' => 20,
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array('title', 'editor'),
		'has_archive' => false,
		'rewrite' => array( 'slug' => $permalink_slug, 'with_front' => true, 'pages' => true, 'feeds'=>false ),
		'query_var' => false,
		'can_export' => true,
		'show_in_nav_menus' => true,
	));

	//register taxonomy for portfolio
	/*register_taxonomy('wt_portfolio_category','wt_portfolio',array(
		'hierarchical' => true,
		'labels' => array(
			'name' => _x( 'Portfolio Categories', 'taxonomy general name', 'wt_admin' ),
			'singular_name' => _x( 'Portfolio Category', 'taxonomy singular name', 'wt_admin' ),
			'search_items' =>  __( 'Search Categories', 'wt_admin' ),
			'popular_items' => __( 'Popular Categories', 'wt_admin' ),
			'all_items' => __( 'All Categories', 'wt_admin' ),
			'parent_item' => null,
			'parent_item_colon' => null,
			'edit_item' => __( 'Edit Portfolio Category', 'wt_admin' ), 
			'update_item' => __( 'Update Portfolio Category', 'wt_admin' ),
			'add_new_item' => __( 'Add New Portfolio Category', 'wt_admin' ),
			'new_item_name' => __( 'New Portfolio Category Name', 'wt_admin' ),
			'separate_items_with_commas' => __( 'Separate Portfolio category with commas', 'wt_admin' ),
			'add_or_remove_items' => __( 'Add or remove portfolio category', 'wt_admin' ),
			'choose_from_most_used' => __( 'Choose from the most used portfolio category', 'wt_admin' ),
			'menu_name' => __( 'Categories', 'wt_admin' ),
		),
		'public' => false,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => false,
		'query_var' => true,
		'rewrite' => false,
		
	));*/
	
	
}
add_action('init','wt_register_section_post_type',0);

function wt_section_context_fixer() {
	if ( get_query_var( 'post_type' ) == 'wt_section' ) {
		global $wp_query;
		$wp_query->is_home = false;
	}
	if ( get_query_var( 'taxonomy' ) == 'wt_section_category' ) {
		global $wp_query;
		$wp_query->is_404 = false;
		$wp_query->is_tax = true;
		$wp_query->is_archive = true;
	}
}
add_action( 'template_redirect', 'wt_section_context_fixer' );