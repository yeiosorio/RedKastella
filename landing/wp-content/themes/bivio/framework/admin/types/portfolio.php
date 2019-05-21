<?php
/*-----------------------------------------------------------------------------------*/
/* Manage portfolio's columns */
/*-----------------------------------------------------------------------------------*/
function edit_wt_portfolio_columns($columns) {
	$columns['portfolio_categories'] = __('Categories', 'wt_admin' );
	$columns['description'] = __('Description', 'wt_admin' );
	$columns['thumbnail'] = __('Thumbnail', 'wt_admin' );
	
	return $columns;
}
add_filter('manage_edit-wt_portfolio_columns', 'edit_wt_portfolio_columns');

function manage_wt_portfolio_columns($column) {
	global $post;
	
	if ($post->post_type == "wt_portfolio") {
		switch($column){
			case "description":
				the_excerpt();
				break;
			case "portfolio_categories":
				$terms = get_the_terms($post->ID, 'wt_portfolio_category');
				
				if (! empty($terms)) {
					foreach($terms as $t)
						$output[] = "<a href='edit.php?post_type=wt_portfolio&wt_portfolio_category=$t->slug'> " . esc_html(sanitize_term_field('name', $t->name, $t->term_id, 'wt_portfolio_category', 'display')) . "</a>";
					$output = implode(', ', $output);
				} else {
					$t = get_taxonomy('wt_portfolio_category');
					$output = "No $t->label";
				}
				
				echo $output;
				break;
			
			case 'thumbnail':
				echo the_post_thumbnail('thumbnail');
				break;
		}
	}
}
add_action('manage_posts_custom_column', 'manage_wt_portfolio_columns', 10, 2);