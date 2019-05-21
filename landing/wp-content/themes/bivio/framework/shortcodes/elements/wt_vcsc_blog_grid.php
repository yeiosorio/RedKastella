<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_blog_grid extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		global $wp_filter;
		$the_content_filter_backup = $wp_filter['the_content'];
		
		extract( shortcode_atts( array(
			'pagination'	      => 'false',
			'columns'             => 1,
			'grid'                => 'false',
			'masonry'             => 'false',
			'count'               => 4,
			'featured_entry'      => 'true',
			'featured_entry_type' => 'full',
			'title'               => 'true',
			'meta'                => 'true',
			'excerpt'             => 'true',
			'excerpt_length'	  => 15,
			'posts'               => '',
			'category'            => '',
			'category__and'       => '',
			'category__not_in'    => '',
			'author'              => '',
			'order'               => 'DESC',
			'orderby'             => 'date',
			'read_more'           => 'true',
			'read_more_text'      => __( 'Read more', 'wt_vcsc' ),
			'full'                => 'false',
			
			'el_id'               => '',
			'el_class'            => '',
    		'css_animation'       => '',
    		'css_animation_right' => '',
    		'anim_type'           => '',
    		'anim_delay'          => '',			
			'css'                 => ''		
		), $atts ) );
		
		$sc_class = 'wt_blog_grid_sc';
				
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
				
		$el_class = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);			
		
		$columns        = (int)$columns;
		$count          = (int)$count;
		$excerpt_length = (int)$excerpt_length;			
		$read_more_text = esc_html($read_more_text);
																
		$query = array(
			'post_type'      =>'post',
			'posts_per_page' => $count,
			'order'			 => $order,
			'orderby'		 => $orderby,
		);
		if($category){
			$query['cat'] = $category;
		}
		if($category__and){
			$query['category__and'] = explode(',',$category__and);
		}
		if($category__not_in){
			$query['category__not_in'] = explode(',',$category__not_in);
		}
		if($author){
			$query['author'] = $author;
		}
		if($posts){
			$query['post__in'] = explode(',',$posts);
		}
		
		if ($pagination == 'true') {
			global $wp_version;
			global $paged;
			
			if (is_front_page() && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query
				$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
			} else {
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
			$query['paged'] = $paged;
		} else {
			$query['showposts'] = $count;
			$paged = NULL;
		}
		
		$wt_query = new WP_Query($query);
	
		if($columns >= 5){
			$columns = 6;
		} elseif ($columns < 1){
			$columns = 1;
		}
		
		/* Display all post if count = -1 */
		if($count == '-1'){
			$query['posts_per_page'] = $wt_query->post_count;
		} 
			
		$posts_per_column = ceil($query['posts_per_page']/$columns);		
		
		$atts = array(
			'posts_per_column'    => $posts_per_column,
			'posts_per_page'      => $count,
			'excerpt'             => $excerpt,
			'excerpt_length'      => $excerpt_length,
			'title'               => $title,
			'meta'                => $meta,
			'featured_entry'      => $featured_entry,
			'featured_entry_type' => $featured_entry_type,
			'columns'             => $columns,
			'masonry'             => $masonry,
			'grid'	              => $grid,
			'read_more'           => $read_more,
			'read_more_text'      => $read_more_text,
			'full'                => $full,
			
    		'css_animation'       => $css_animation,
    		'css_animation_right' => $css_animation_right,
    		'anim_type'           => $anim_type,
    		'anim_delay'          => $anim_delay,
		);
				
		$output = '';
		
		$output .= '<div id="'.$el_id.'" class="'.$css_class.'">';
		$output .= '<span class="time">'.get_the_date('Y').'</span>';
		
		if ($columns != 1){
			$class = array('half','third','fourth','sixth');
			$cssColumn = $class[$columns-2];
			
			if( $cssColumn == 'half' ) {
				$css = 'col-lg-6 col-md-6 col-sm-6';
			} elseif ( $cssColumn == 'third' ) {
				$css = 'col-lg-4 col-md-4 col-sm-4';
			} elseif ( $cssColumn == 'fourth' ) {
				$css = 'col-lg-3 col-md-3 col-sm-3';
			} elseif ( $cssColumn == 'sixth' ) {
				$css = 'col-lg-2 col-md-2 col-sm-2';
			}
			
			for($i=1; $i<=$columns; $i++){
				$output .= "<div class=\"{$css}\">".$this->WT_VCSC_BlogList($wt_query,$atts,$i)."</div>";
			}
		} else {
			$output .= $this->WT_VCSC_BlogList($wt_query,$atts,1);
		}
		
		$output .= "</div>"; // close blog_shortcode div
		
		if ($pagination == 'true') {
			ob_start();
			WT_VCSC_BlogPageNavi('', '', $wt_query, $paged);
			$output .= ob_get_clean();
		}
		
		// Set things back to normal
		wp_reset_postdata();		
		$wp_filter['the_content'] = $the_content_filter_backup;
		return $output;
    }
	
	protected function WT_VCSC_BlogList(&$wt_query, $atts, $current) {
		extract($atts);		
		
		$anim_class_left  = $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_class_right = $this->wt_sc->getWTCSSAnimationClass($css_animation_right,$anim_type);		
		$anim_data_left   = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		$anim_data_right  = $this->wt_sc->getWTCSSAnimationData($css_animation_right,$anim_delay);
					
		if ($grid == 'true') {
			$class = array('half','third','fourth','sixth');
			$cssColumn = $class[$columns-2];
			
			if( $cssColumn == 'half' ) {
				$css = 'col-lg-6 col-md-6 col-sm-6';
			} elseif ( $cssColumn == 'third' ) {
				$css = 'col-lg-4 col-md-4 col-sm-4';
			} elseif ( $cssColumn == 'fourth' ) {
				$css = 'col-lg-3 col-md-3 col-sm-3';
			} elseif ( $cssColumn == 'sixth' ) {
				$css = 'col-lg-2 col-md-2 col-sm-2';
			}
		} else {
			$start = ($current-1) * $posts_per_column + 1;
			$end = $current * $posts_per_column;
			if( $wt_query->post_count < $start){
				return '';
			}
		}
		
		//global $layout;	
		$layout     = 'full';	
		$output     = '';
				
		// If sortable blog shortcode
		if ($masonry == 'true') {
			wp_enqueue_script('jquery-isotope');
			wp_enqueue_script('jquery-init-isotope');
			$output .= '<div class="wt_isotope">';
			$element = 'wt_element ';
		} else {
			$element = '';
		}
					
		$i = 0;
		// Get global $post var
		global $post;
		
		if ($wt_query->have_posts()):
			while ($wt_query->have_posts()) : 
				$i++;
				
				$anim_class = '';
				$anim_data  = '';
				
				if ($grid == 'false') {
					if($i < $start) continue;
					if($i > $end) break;
				}
							
				$wt_query->the_post();
				
				if ($grid == 'true' && $columns != 1) {
					$output .= "<div class=\"{$element}{$css}\">";
				}
				
					if ($columns == 1) {
						
						if($i&1) {
							$anim_class = $anim_class_left;
							$anim_data  = $anim_data_left;
						} else {
							$anim_class = $anim_class_right;
							$anim_data  = $anim_data_right;
						}
						
						$output .= '<article data-order="'.$i.'" id="post-'.get_the_ID().'" class="blogEntry col-lg-12 col-md-12 col-sm-12'.$anim_class.' clearfix"'.$anim_data.'><span class="note-arrow"></span>';
					} else {
						$output .= '<article data-order="'.$i.'" id="post-'.get_the_ID().'" class="blogEntry clearfix"><span class="note-arrow"></span>';
					}
					
					/* Display featured entry */
					if($featured_entry == 'true'){
						$output .= '<header class="blogEntry_frame entry_'.$featured_entry_type.'">';
						$thumbnail_type = get_post_meta($post->ID, '_thumbnail_type', true);
		
						// Default sizes for featured image / slide
						$width  = 705;
						$height = 380;
						
							switch($thumbnail_type){
							
								case "timage" : 
									$output .= wt_theme_generator('wt_blog_featured_image',$featured_entry_type,$layout,$width,$height);
									break;
								case "tvideo" : 
									$video_link = get_post_meta($post->ID,'_featured_video', true);
									$output .= '<div class="blog-thumbnail-video">';
									$output .= wt_video_featured($video_link,$featured_entry_type,$layout);
									
									$output .=  '</div>';							
									break;
								case "tplayer" :						
									wp_enqueue_script('mediaelementjs-scripts'); 
									$player_link = get_post_meta($post->ID,'_thumbnail_player', true);
									$output .= '<div class="blog-thumbnail-player">';
									$output .= wt_media_player($featured_entry_type,$layout,$player_link);
									$output .= '</div>';							
									break;
								case "tslide" : 
									$output .= '<div class="blog-thumbnail-slide">';
									$output .= wt_get_slide($featured_entry_type,$layout,$width,$height);
									$output .= '</div>';							
									break;
						}
						$output .= '</header>';
					}
					
					$output .=  '<div class="wt_dates"><div class="entry_date">';
					$output .=  '<a href="'.get_month_link(get_the_time('Y'), get_the_time('m')).'"><span class="day">'.get_the_time('d').'</span><span class="month">'.get_the_time('M').'</span></a></div>';
					$output .=  '</div>';
					
					/* Display description (post excerpt / content) */
					if($excerpt == 'false'){
						
						$output .= '<div class="blogEntry_content">';
						
							if ( $title == 'true' ) {
								$output .= '<h3 class="blogEntry_title"><a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'wt_vcsc'), get_the_title() ).'">'.get_the_title().'</a></h3>';
							}
							
							if ( $meta == 'true' ){
								$output .= '<footer class="blogEntry_metadata">';								
								$output .= wt_theme_generator('wt_blog_meta');							
								$output .= '</footer>';			
							}
							if ( $read_more == 'true' ) {		
								$readmore_link = '<p class="readMore"><a href="'. get_permalink( $id ) .'" title="'.$read_more_text .'" rel="bookmark" class="read_more_link">'.$read_more_text .' <span class="wt-readmore-rarr">&raquo;</span></a></p>';
								$output .= apply_filters( 'wt_readmore_link', $readmore_link );
							}
						
						$output .= '</div>'; // End blogEntry_content div
					} else { /* If description is YES */	
						$output .= '<div class="blogEntry_content">';
						
							if ( $title == 'true' ) {
								$output .= '<h3 class="blogEntry_title"><a href="'.get_permalink().'" rel="bookmark" title="'.sprintf( __("Permanent Link to %s", 'wt_vcsc'), get_the_title() ).'">'.get_the_title().'</a></h3>';
							}
							
							if ( $meta == 'true' ){
								$output .= '<footer class="blogEntry_metadata">';							
								$output .= wt_theme_generator('wt_blog_meta');
								$output .= '</footer>';				
							}
							
							/* Display all post content or post excerpt */
							if ( $full == 'true' ){
								global $more;
								$more = 0;
								$content = get_the_content(__("Read More", 'wt_vcsc'),false);
								$content = apply_filters('the_content', $content);
								$content = str_replace(']]>', ']]&gt;', $content);
								$output .= $content;
							} else {
								/*						
								$content = get_the_excerpt();
								$content = apply_filters('the_excerpt', $content);							
								$output .= '<div class="blogEntry_excerpt">'.$content.'</div>';
								$output .= '<p class="readMore"><a class="read_more_link" href="'.get_permalink().'">'. __('Read more &raquo;','wt_vcsc').'</a></p>';	
								*/
								$content = WT_VCSC_Excerpt( $excerpt_length, $read_more, $read_more_text );
								$output .= '<div class="blogEntry_excerpt">'.$content.'</div>';
							}
						$output .= '</div>'; // End blogEntry_content div
						
						if ( $featured_entry_type == 'left' ) {
							$output .= '<div class="wt_clearboth"></div>';
						}
					}
					
					$output .= '</article>';
				
				if ($grid == 'true' && $columns != 1) {
					$output .= '</div>';
				}
				
			endwhile;			
		endif;
			
		return $output;
	
	}
	
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/

if (function_exists('vc_map')) {

	$add_wt_sc_func             = new WT_VCSC_SHORTCODE;	
	$add_wt_extra_id            = $add_wt_sc_func->getWTExtraId();
	$add_wt_extra_class         = $add_wt_sc_func->getWTExtraClass();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	vc_map( array(
		'name' => __('WT Blog Grid', 'wt_vcsc'),
		'base' => 'wt_blog_grid',
		'icon' => 'wt_vc_ico_blog_grid',
		'class' => 'wt_vc_sc_blog_grid',
		'category' => __('by WhoaThemes', 'wt_vcsc'),
		'description' => __('Recent blog posts grid', 'wt_vcsc'),
		'params' => array(
			/*
			array(
				'type'          => 'dropdown',
				'heading'       => __('Pagination', 'wt_vcsc'),
				'param_name'    => 'pagination',
				'value' => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('Display pagination. Important: Pagination won\'t work on your homepage because of how WordPress works.', 'wt_vcsc')
			),
			array(
				'type'			=> 'dropdown',
				'class'			=> '',
				'heading'		=> __( 'Columns', 'wt_vcsc' ),
				'param_name'	=> 'columns',
				'admin_label'	=> true,
				'value' 		=> array(
					//__( 'One', 'wt_vcsc' )		=> '1',
					__( 'Two', 'wt_vcsc' )		=> '2',
					//__( 'Three', 'wt_vcsc' )	=> '3',
					//__( 'Four', 'wt_vcsc' )	=> '4',
					//__( 'Six', 'wt_vcsc' )	=> '6',
				),
				'std'	        => '2',
				'description'	=> __( 'How many columns for your grid? Only \'1, 2, 3, 4, 6\' are accepted.', 'wt_vcsc' ),
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Grid Layout', 'wt_vcsc'),
				'param_name'    => 'grid',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Display posts in a grid layout. \'Columns\' above option should be other than \'One\'.', 'wt_vcsc')
			),
			*/
			array(
				'type'          => 'textfield',
				'heading'       => __('Count (posts number)', 'wt_vcsc'),
				'param_name'    => 'count',
				'value'         => '4',
				'description'   => __('How many items do you wish to show? Set -1 to display all.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Show featured entry?', 'wt_vcsc'),
				'param_name'    => 'featured_entry',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Display featured post entries? These could be: images, slides, videos or audios.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Title', 'wt_vcsc'),
				'param_name'    => 'title',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Display post title?', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Meta information', 'wt_vcsc'),
				'param_name'    => 'meta',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Display post meta information? These are: author, categories, tags, comments.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Excerpt (post content)', 'wt_vcsc'),
				'param_name'    => 'excerpt',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Display post excerpt / content?', 'wt_vcsc')
			),
			array(
				'type'               => 'textfield',
				'heading'            => __('Excerpt (post content) length', 'wt_vcsc'),
				'param_name'         => 'excerpt_length',
				'value'              => '15',
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	=> 'excerpt',
					'value'		=> 'true'
				),
				'description'        => __('Enter a custom excerpt length. Will trim the excerpt by this number of words.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Display full post?', 'wt_vcsc'),
				'param_name'    => 'full',
				'value' => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('Display all posts content instead of the auto excerpt. Excerpt option above should be \'YES\'', 'wt_vcsc')
			),		
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Display specific posts (optional)', 'wt_vcsc'),
				'param_name'    => 'posts',
				'value'         => '',
				'target'        => 'post',
				'description'   => __('Display only specific / selected posts. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Display from category (optional)', 'wt_vcsc'),
				'param_name'    => 'category',
				'value'         => '',
				'target'        => 'category',
				'description'   => __('Display posts from selected categories. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Multiple Categories (optional)', 'wt_vcsc'),
				'param_name'    => 'category__and',
				'value'         => '',
				'target'        => 'category',
				'description'   => __('Display posts that are in multiple categories. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Exclude Categories (optional)', 'wt_vcsc'),
				'param_name'    => 'category__not_in',
				'value'         => '',
				'target'        => 'category',
				'description'   => __('Exclude selected categories. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Display by author (optional)', 'wt_vcsc'),
				'param_name'    => 'author',
				'value'         => '',
				'target'        => 'author',
				'description'   => __('Display posts by specific authors. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'			=> 'dropdown',
				'class'			=> '',
				'heading'		=> __( 'Order', 'wt_vcsc' ),
				'param_name'	=> 'order',
				'description'	=> sprintf( __( 'Designates the ascending or descending order. More at %s.', 'wt_vcsc' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>' ),
				'value'			=> array(
					 __( 'DESC', 'wt_vcsc')	=> 'DESC',
					 __( 'ASC', 'wt_vcsc' )	=> 'ASC',
				),
			),
			array(
				'type'			=> 'dropdown',
				'class'			=> '',
				'heading'		=> __( 'Order By', 'wt_vcsc' ),
				'param_name'	=> 'orderby',
				'description'	=> sprintf( __( 'Select how to sort retrieved posts. More at %s.', 'wt_vcsc' ), '<a href="http://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters" target="_blank">WordPress codex</a>' ),
				'value'			=> array(
					__( 'None', 'wt_vcsc')			    => 'none',
					__( 'Id', 'wt_vcsc')			    => 'ID',
					__( 'Author', 'wt_vcsc' )			=> 'author',
					__( 'Title', 'wt_vcsc' )		    => 'title',
					__( 'Date', 'wt_vcsc')				=> 'date',
					__( 'Modified', 'wt_vcsc')			=> 'modified',
					__( 'Random', 'wt_vcsc')			=> 'rand',
					__( 'Comment Count', 'wt_vcsc' )	=> 'comment_count',
					__( 'Menu Order', 'wt_vcsc' )	    => 'menu_order',
				),
				'std'	        => 'date',
			),
			array(
				'type'			=> 'dropdown',
				'class'			=> '',
				'heading'		=> __( 'Read More', 'wt_vcsc' ),
				'param_name'	=> 'read_more',
				'value'			=> array(
					__( 'Yes', 'wt_vcsc')   => 'true',
					__( 'No', 'wt_vcsc' )	=> 'false',
				),
				'description'	=> __( 'Display post readmore button after excerpt?', 'wt_vcsc' ),
			),
			array(
				'type'			     => 'textfield',
				'class'			     => '',
				'heading'		     => __( 'Read More Text', 'wt_vcsc' ),
				'param_name'	     => 'read_more_text',
				'value'			     => '',
				'description'	     => __('Enter your custom text for the read more button.','wt_vcsc'),
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	=> 'read_more',
					'value'		=> 'true'
				),
			),	
			
			$add_wt_extra_id,
			$add_wt_extra_class,
			
			array(
				"type" => "dropdown",
				"heading" => __("CSS WT Animation (Left Column)", "wt_vcsc"),
				"param_name" => "css_animation",
				"value" => array(__("No", "wt_vcsc") => '', __("Hinge", "wt_vcsc") => "hinge", __("Flash", "wt_vcsc") => "flash", __("Shake", "wt_vcsc") => "shake", __("Bounce", "wt_vcsc") => "bounce", __("Tada", "wt_vcsc") => "tada", __("Swing", "wt_vcsc") => "swing", __("Wobble", "wt_vcsc") => "wobble", __("Pulse", "wt_vcsc") => "pulse", __("Flip", "wt_vcsc") => "flip", __("FlipInX", "wt_vcsc") => "flipInX", __("FlipOutX", "wt_vcsc") => "flipOutX", __("FlipInY", "wt_vcsc") => "flipInY", __("FlipOutY", "wt_vcsc") => "flipOutY", __("FadeIn", "wt_vcsc") => "fadeIn", __("FadeInUp", "wt_vcsc") => "fadeInUp", __("FadeInDown", "wt_vcsc") => "fadeInDown", __("FadeInLeft", "wt_vcsc") => "fadeInLeft", __("FadeInRight", "wt_vcsc") => "fadeInRight", __("FadeInUpBig", "wt_vcsc") => "fadeInUpBig", __("FadeInDownBig", "wt_vcsc") => "fadeInDownBig", __("FadeInLeftBig", "wt_vcsc") => "fadeInLeftBig", __("FadeInRightBig", "wt_vcsc") => "fadeInRightBig", __("FadeOut", "wt_vcsc") => "fadeOut", __("FadeOutUp", "wt_vcsc") => "fadeOutUp", __("FadeOutDown", "wt_vcsc") => "fadeOutDown", __("FadeOutLeft", "wt_vcsc") => "fadeOutLeft", __("FadeOutRight", "wt_vcsc") => "fadeOutRight", __("fadeOutUpBig", "wt_vcsc") => "fadeOutUpBig", __("FadeOutDownBig", "wt_vcsc") => "fadeOutDownBig", __("FadeOutLeftBig", "wt_vcsc") => "fadeOutLeftBig", __("FadeOutRightBig", "wt_vcsc") => "fadeOutRightBig", __("BounceIn", "wt_vcsc") => "bounceIn", __("BounceInUp", "wt_vcsc") => "bounceInUp", __("BounceInDown", "wt_vcsc") => "bounceInDown", __("BounceInLeft", "wt_vcsc") => "bounceInLeft", __("BounceInRight", "wt_vcsc") => "bounceInRight", __("BounceOut", "wt_vcsc") => "bounceOut", __("BounceOutUp", "wt_vcsc") => "bounceOutUp", __("BounceOutDown", "wt_vcsc") => "bounceOutDown", __("BounceOutLeft", "wt_vcsc") => "bounceOutLeft", __("BounceOutRight", "wt_vcsc") => "bounceOutRight", __("RotateIn", "wt_vcsc") => "rotateIn", __("RotateInUpLeft", "wt_vcsc") => "rotateInUpLeft", __("RotateInDownLeft", "wt_vcsc") => "rotateInDownLeft", __("RotateInUpRight", "wt_vcsc") => "rotateInUpRight", __("RotateInDownRight", "wt_vcsc") => "rotateInDownRight", __("RotateOut", "wt_vcsc") => "rotateOut", __("RotateOutUpLeft", "wt_vcsc") => "rotateOutUpLeft", __("RotateOutDownLeft", "wt_vcsc") => "rotateOutDownLeft", __("RotateOutUpRight", "wt_vcsc") => "rotateOutUpRight", __("RotateOutDownRight", "wt_vcsc") => "rotateOutDownRight", __("RollIn", "wt_vcsc") => "rollIn", __("RollOut", "wt_vcsc") => "rollOut", __("LightSpeedIn", "wt_vcsc") => "lightSpeedIn", __("LightSpeedOut", "wt_vcsc") => "lightSpeedOut" ),
				"description" => __("Select type of animation (for left blog column) if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "wt_vcsc"),
				'group' => __('Extra settings', 'wt_vcsc')
			),
			array(
				"type" => "dropdown",
				"heading" => __("CSS WT Animation (Right Column)", "wt_vcsc"),
				"param_name" => "css_animation_right",
				"value" => array(__("No", "wt_vcsc") => '', __("Hinge", "wt_vcsc") => "hinge", __("Flash", "wt_vcsc") => "flash", __("Shake", "wt_vcsc") => "shake", __("Bounce", "wt_vcsc") => "bounce", __("Tada", "wt_vcsc") => "tada", __("Swing", "wt_vcsc") => "swing", __("Wobble", "wt_vcsc") => "wobble", __("Pulse", "wt_vcsc") => "pulse", __("Flip", "wt_vcsc") => "flip", __("FlipInX", "wt_vcsc") => "flipInX", __("FlipOutX", "wt_vcsc") => "flipOutX", __("FlipInY", "wt_vcsc") => "flipInY", __("FlipOutY", "wt_vcsc") => "flipOutY", __("FadeIn", "wt_vcsc") => "fadeIn", __("FadeInUp", "wt_vcsc") => "fadeInUp", __("FadeInDown", "wt_vcsc") => "fadeInDown", __("FadeInLeft", "wt_vcsc") => "fadeInLeft", __("FadeInRight", "wt_vcsc") => "fadeInRight", __("FadeInUpBig", "wt_vcsc") => "fadeInUpBig", __("FadeInDownBig", "wt_vcsc") => "fadeInDownBig", __("FadeInLeftBig", "wt_vcsc") => "fadeInLeftBig", __("FadeInRightBig", "wt_vcsc") => "fadeInRightBig", __("FadeOut", "wt_vcsc") => "fadeOut", __("FadeOutUp", "wt_vcsc") => "fadeOutUp", __("FadeOutDown", "wt_vcsc") => "fadeOutDown", __("FadeOutLeft", "wt_vcsc") => "fadeOutLeft", __("FadeOutRight", "wt_vcsc") => "fadeOutRight", __("fadeOutUpBig", "wt_vcsc") => "fadeOutUpBig", __("FadeOutDownBig", "wt_vcsc") => "fadeOutDownBig", __("FadeOutLeftBig", "wt_vcsc") => "fadeOutLeftBig", __("FadeOutRightBig", "wt_vcsc") => "fadeOutRightBig", __("BounceIn", "wt_vcsc") => "bounceIn", __("BounceInUp", "wt_vcsc") => "bounceInUp", __("BounceInDown", "wt_vcsc") => "bounceInDown", __("BounceInLeft", "wt_vcsc") => "bounceInLeft", __("BounceInRight", "wt_vcsc") => "bounceInRight", __("BounceOut", "wt_vcsc") => "bounceOut", __("BounceOutUp", "wt_vcsc") => "bounceOutUp", __("BounceOutDown", "wt_vcsc") => "bounceOutDown", __("BounceOutLeft", "wt_vcsc") => "bounceOutLeft", __("BounceOutRight", "wt_vcsc") => "bounceOutRight", __("RotateIn", "wt_vcsc") => "rotateIn", __("RotateInUpLeft", "wt_vcsc") => "rotateInUpLeft", __("RotateInDownLeft", "wt_vcsc") => "rotateInDownLeft", __("RotateInUpRight", "wt_vcsc") => "rotateInUpRight", __("RotateInDownRight", "wt_vcsc") => "rotateInDownRight", __("RotateOut", "wt_vcsc") => "rotateOut", __("RotateOutUpLeft", "wt_vcsc") => "rotateOutUpLeft", __("RotateOutDownLeft", "wt_vcsc") => "rotateOutDownLeft", __("RotateOutUpRight", "wt_vcsc") => "rotateOutUpRight", __("RotateOutDownRight", "wt_vcsc") => "rotateOutDownRight", __("RollIn", "wt_vcsc") => "rollIn", __("RollOut", "wt_vcsc") => "rollOut", __("LightSpeedIn", "wt_vcsc") => "lightSpeedIn", __("LightSpeedOut", "wt_vcsc") => "lightSpeedOut" ),
				"description" => __("Select type of animation (for right blog column) if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "wt_vcsc"),
				'group' => __('Extra settings', 'wt_vcsc')
			),
			
			$add_wt_css_animation_type,
			$add_wt_css_animation_delay,
			
			array(
				'type' => 'css_editor',
				'heading' => __('Css', 'wt_vcsc'),
				'param_name' => 'css',
				'group' => __('Design options', 'wt_vcsc')
			)
		)
	));
	
}