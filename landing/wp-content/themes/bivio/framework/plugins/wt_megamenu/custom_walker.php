<?php
/**
 * Custom Walker
 *
 * @access      public
 * @since       1.0 
 * @return      void
*/
class Wt_mega_walker extends Walker_Nav_Menu {
	
	function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output)
    {
        // check, whether there are children for the given ID and append it to the element with a (new) ID
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

	function start_el(&$output, $item, $depth = 0, $args = array(), $current_object_id = 0) {
		
		global $wp_query;
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		//$depth= $depth + 1;
		$class_names = $value = '';
		$megamenu = 0;
        $column = 1;
	    if($depth == 1){            
			$column = get_post_meta( $item->menu_item_parent, '_menu_item_wt_megamenu_columns', true );
			$megamenu = get_post_meta( $item->menu_item_parent, '_menu_item_wt_megamenu_enabled', true );
	   }
	   if($depth == 0){           
			$column = $item->_menu_item_wt_megamenu_columns;
			$megamenu_col = ' megamenu_col'.$column.'';
	   }
		
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		$hideheadings = empty( $item->wt_megamenu_hide_title ) ? "" : "no-headings";
		$styledtitles = empty( $item->wt_megamenu_styled_title ) ? "" : "styledtitles";
		if (($item->wt_megamenu_enabled == 1) && ($depth == 0)) {
			$wt_megamenu_width = empty( $item->wt_megamenu_fullwidth ) ? "wt_megamenu_autowidth" : "wt_megamenu_fullwidth";
		} else {$wt_megamenu_width = '';}
		$labelclass = (isset($item->nav_label) && ($item->nav_label == 1))? ' show-label' : ' hide-label';
	    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );          
	    $class_megamenu = (($item->wt_megamenu_enabled == 1) && ($depth == 0))? ' wt_megamenu'.$megamenu_col.'': '';
	   
		if($megamenu == 1 ){
			$class_megamenu .= ' col-md-'.(12/$column).' col-sm-'.(12/$column).' col-xs-12';
		}
		//$class_names = ' class="'. esc_attr( $class_names ) .$class_megamenu. $labelclass.'"';

	   
		//$output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names.'>';
		
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
		$class_names = ' class="menu-item-'. $item->ID . ' '. esc_attr( $class_names ) . ' '.$class_megamenu.' '.$wt_megamenu_width.' '.$labelclass.' '.$hideheadings.' '.$styledtitles.'"';
		
		$output .= $indent . '<li ' . $value . $class_names .'>';
		
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
		
		$prepend = '';
		$append = '';
		
		$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';
		
		if ($depth != 0) {
		   $description = $append = $prepend = "";
		}	
		if (empty( $item->wt_megamenu_hide_title )) {
			
			$item_output = $args->before;
				if (empty( $item->wt_megamenu_remove_link )) {
					$item_output .= '<a'. $attributes .'>';
				} else {
					$item_output .= '<span class="noLink">';
				}
				if ( $item->wt_megamenu_styled_title ) {
					$item_output .= '<div class="wt_title wt_heading_3"><h4><span>';
				}
				if (!empty( $item->wt_megamenu_icon )) {
					$item_output .= '<i class="'.$item->wt_megamenu_icon.'"></i>';
				}
				$item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
				$item_output .= $args->link_after;
				if ( $item->wt_megamenu_styled_title ) {
					$item_output .= '</h4></span></div>';
				};
				if (empty( $item->wt_megamenu_remove_link )) {
					$item_output .= '</a>';
				} else {
					$item_output .= '</span>';
				}
			if (!empty( $item->wt_megamenu_html_content )) {
				$item_output .= '<div class="mega-menu-widget">'.do_shortcode($item->wt_megamenu_html_content).'</div>';
			}
			
			
		} else {
			$item_output = $args->before;
			if (!empty( $item->wt_megamenu_icon )) {
				$item_output .= '<i class="'.$item->wt_megamenu_icon.'"></i>';
			}
			if (!empty( $item->wt_megamenu_html_content )) {
				$item_output .= '<div class="mega-menu-widget">'.do_shortcode($item->wt_megamenu_html_content).'</div>';
			$item_output .= $args->after;
			}
			
		}
		
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		
	}
}