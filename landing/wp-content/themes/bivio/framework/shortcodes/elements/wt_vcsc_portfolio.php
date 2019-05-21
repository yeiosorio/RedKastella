<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_portfolio extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		$opts = shortcode_atts(array(
			'columns'            => 4,
			'grid_spaces'        => 'false',
			'overlay_desc'       => 'false',
			'max'                => -1,
			'pagination'         => 'false',
			'pagination_align'   => 'center',
			'sortable'           => 'false',
			'carousel'           => 'false',
			'auto_slide'         => 0,
			'carousel_nav'       => 'false',
			'title'              => 'true',
			'title_linkable'     => 'true',
			'excerpt'            => 'false',
			'excerpt_length'     => 15,
			'full'               => 'false',
			'category'           => 'false',
			'group'              => 'true',	
			'ids'                => '',			
			'cat'                => '',
			'cat__not'           => '',
			'order'              => 'DESC',
			'orderby'            => 'date', //none, id, author, title, date, modified, parent, rand, comment_count, menu_order
			'read_more'          => 'false',
			'read_more_text'     => __( 'Read more', 'wt_vcsc' ),				
			
			'el_id'               => '',
			'el_class'            => '',			
			'css'                 => ''	
	
		), $atts);		
		
		extract($opts);
		
		$sc_class = 'wt_portfolio_sc';
		
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
		
		$el_class = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		
		$columns        = (int)$columns;
		$max            = (int)$max;	
		$auto_slide     = (int)$auto_slide;					
		$excerpt_length = (int)$excerpt_length;				
		$read_more_text = esc_html($read_more_text);	
		
		switch($columns){
			case 1:
				$col_class = 'col-lg-12 col-md-12 col-sm-12';
				break;
			case 2:
				$col_class = 'col-lg-6 col-md-6 col-sm-6';
				break;
			case 3:
				$col_class = 'col-lg-4 col-md-4 col-sm-4';
				break;
			case 6:
				$col_class = 'col-lg-2 col-md-2 col-sm-2';
				break;
			case 4:
			default:
				$col_class = 'col-lg-3 col-md-3 col-sm-3';
		}
				
		$output = '';
		
		// Automatically disable sortable or pagination if they all are true by mistake
		if ($carousel == "true") { 
			$sortable   = 'false';
			$pagination = 'false';
		}
		if ($sortable == "true") {
			$pagination = 'false';		
		} 	
		
		// portfolio with grid spaces
		$grid_spaces_css = '';
		if ($grid_spaces == "true") {
			$grid_spaces_css = ' wt_grid_spaces';			
			$output .= '<div class="wt_portfolio_row row">';	
		} 
		
		// portfolio with overlay title or excerpt
		$overlay_desc_css = '';
		if ($overlay_desc == "true") {
			$overlay_desc_css = ' wt_portfolio_overlay';
		} else {
			$overlay_desc_css = ' wt_portfolio_no_overlay';
		}		
		
		// if portfolio with sortable links	- build the sortableLinks first			
		if ($sortable == "true") {			
			wp_enqueue_script('jquery-isotope');
			
			$output .= '<div class="sortableLinks">';
			// $output .= '<span>'.__('Show:','wt_front').'</span>';
			$output .= '<a class="selected" data-filter="*" href="#">'.__('All','wt_front').'</a>';
			$terms = array();
			if ($cat != '') {
				foreach(explode(',', $cat) as $term_slug) {
					$terms[] = get_term_by('slug', $term_slug, 'wt_portfolio_category');
				}
			} else {
				$terms = get_terms('wt_portfolio_category', 'hide_empty=1');
			}
			foreach($terms as $term) {
				$output .= '<a data-filter=".' . $term->slug . '" href="#">' . $term->name . '</a>';
			}
			$output .= '</div>';			
		}			
		
		// Output for portfolio section
		$output .= '<section id="'.$el_id.'" class="wt_portfolio_wrapper'; // Start portfolio section		
		
		// if portfolio with carousel
		if ($carousel == "true") {
			
			$output .= '_carousel wt_portfolio-grid wt_portfolio_' . $columns . $overlay_desc_css . ' '.$css_class.'" style="margin-right: 0">';
			
			wp_print_scripts('owlCarousel');
			
			$auto_slide == '0' ? $auto_slide = 'false' : '';
				
			$output .= '<div class="wt_port_carousel">';		
			$output .= '<div id="wt_owl_carousel_'.$id.'" class="wt_owl_carousel" data-owl-speed="600" data-owl-pagSpeed="1000" data-owl-autoPlay="'.$auto_slide.'" data-owl-stopOnHover="true" data-owl-navigation="'.$carousel_nav.'" data-owl-pagination="false" data-owl-items="4" data-owl-itemsDesktop="4"  data-owl-itemsSmallDesktop="4" data-owl-itemsSmallDesktop="4" data-owl-itemsTablet="3" data-owl-itemsMobile="1" data-owl-itemsMobileSmall="1">';
		}
		
		// if portfolio with sortable links		
		if ($sortable == "true") {
			$isotope = ' wt_isotope';			
			
			$output .= ' wt_portfolio-grid' . $isotope . ' wt_portfolio_' . $columns . $overlay_desc_css . $grid_spaces_css . ' '.$css_class.'">';			
		} 		
		
		// if portfolio with pagination	
		if ($pagination == "true") {
			$output .= ' wt_portfolio-grid wt_portfolio_' . $columns . $overlay_desc_css . $grid_spaces_css . ' '.$css_class.'">';
		} 
		
		// fallback - if pagination and sortable and carousel are all false
		if ($pagination == "false" && $sortable == "false" && $carousel == "false") { 
			$output .= ' wt_portfolio-grid wt_portfolio_' . $columns . $overlay_desc_css . $grid_spaces_css . ' '.$css_class.'">';
		}
		
		$output .= $this->WT_VCSC_PortfolioList($opts, $id);
		$output .= '</section>'; // End portfolio section
		
		if ($grid_spaces == "true") {			
			$output .= '</div>'; // End row div	
		}	
		
		return $output;			
	
	}
	
	protected function WT_VCSC_PortfolioList($options, $id) {
		
		global $wp_filter;
		$the_content_filter_backup = $wp_filter['the_content'];
		
		$options = shortcode_atts(array(
			'columns'            => 4,
			'grid_spaces'        => 'false',
			'overlay_desc'       => 'false',
			'max'                => -1,
			'pagination'         => 'false',
			'pagination_align'   => 'center',
			'sortable'           => 'false',
			'carousel'           => 'false',
			'auto_slide'         => 0,
			'carousel_nav'       => 'false',
			'title'              => 'true',
			'title_linkable'     => 'true',
			'excerpt'            => 'false',
			'excerpt_length'     => 15,
			'full'               => 'false',
			'category'           => 'true',
			'group'              => 'true',	
			'ids'                => '',			
			'cat'                => '',
			'cat__not'           => '',
			'order'              => 'DESC',
			'orderby'            => 'date', //none, id, author, title, date, modified, parent, rand, comment_count, menu_order
			'read_more'          => 'true',
			'read_more_text'     => __( 'Read more', 'wt_vcsc' )
		), $options);
	
		extract($options);	
			
		$output = '';
		$rel_group = 'portfolio_'.$id; // for lightbox group		
		
		if ( $read_more == 'true' ){
			$read_more = true;
		} else {
			$read_more = false;
		}
				
		// Automatically disable sortable or pagination if they all are true by mistake
		if ($carousel == "true") { 
			$sortable   = 'false';
			$pagination = 'false';
		}
		if ($sortable == "true") {
			$pagination = 'false';		
		} 
		
		if ($pagination == 'true') {
			global $wp_version;
			if(is_front_page() && version_compare($wp_version, "3.1", '>=')){//fix wordpress 3.1 paged query
				$paged = (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
			}else{
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			}
		} else {
			$paged = NULL;
		}	
		
		// Display only selected portfolios		
		if ( $ids ) {
			$portfolio_ids = explode(',',$ids);
		} else {
			$portfolio_ids = NULL;
		}	
				
		// Include categories
		if ( ! empty( $cat ) ) {
			$include_categories = array(
				'taxonomy'	=> 'wt_portfolio_category',
				'field'		=> 'slug',
				'terms'		=> explode(',', $cat),
				'operator'	=> 'IN',
			);
		} else {
			$include_categories = '';
		}
		
		// Exclude categories
		if ( ! empty( $cat__not ) ) {
			$exclude_categories = array(
				'taxonomy'	=> 'wt_portfolio_category',
				'field'		=> 'slug',
				'terms'		=> explode(',', $cat__not),
				'operator'	=> 'NOT IN',
			);
		} else {
			$exclude_categories = '';
		}	
		
		$wt_query = new WP_Query(
			array(
				'post_type'			=> 'wt_portfolio',
				'posts_per_page'	=> $max,
				'paged'				=> $paged,
				'order'				=> $order,
				'orderby'			=> $orderby,
				'post__in'			=> $portfolio_ids,
				'tax_query'			=> array(
					'relation'		=> 'AND',
					$include_categories,
					$exclude_categories,
				),
			)
		);
		
		$i     = 1;				
		$order = 0;
		
		while($wt_query->have_posts()) {
			$order++;
			$wt_query->the_post();
			$terms = get_the_terms(get_the_id(), 'wt_portfolio_category');
			$terms_slug = array();
			if (is_array($terms)) {
				foreach($terms as $term) {
					$terms_slug[] = $term->slug;
				}
			}
	
			if (has_post_thumbnail()) {
				$image_id = get_post_thumbnail_id(get_the_id());
				$image = wp_get_attachment_image_src($image_id, 'full', true);
				
				$type = get_post_meta(get_the_id(), '_portfolio_type', true);
				$iframe = '';
				$video_width = $video_height = '';
				
				// if portfolio type - image
				if ($type == 'image'){
					// if no large image link is set then get the featured image
					$href =  get_post_meta(get_the_id(), '_image', true);
					if(empty($href)){
						$href = $image[0];
					}
					
					$icon = '<i class="fa-picture-o"></i>';
					$lightbox = ' lightbox';
					if($group == 'true'){
						$rel = ' data-rel="lightbox['.$rel_group.']"';
					}else{
						$rel = '';
					}
					
				// if portfolio type - video
				} elseif ($type == 'video'){
					// if no video link is set then get the featured image
					$href =  get_post_meta(get_the_id(), '_video', true);
					if(empty($href)){
						$href = $image[0];
					}
					
					$video_width = get_post_meta(get_the_id(), '_video_width', true);
					$video_height = get_post_meta(get_the_id(), '_video_height', true);
					
					if ($video_width) {
						$video_width = '?width='.$video_width.'';
					}
					
					if($video_height){
						if ($video_width) {
							$video_height = '&amp;height='.$video_height.'';
						}
						else {
							$video_height = '?height='.$video_height.'';
						}
					}
									
					$icon = '<i class="fa-youtube-play"></i>';
					$lightbox = ' lightbox';
					
					if($group == 'true'){
						$rel =  ' data-rel="lightbox['.$rel_group.']"';
					}else{
						$rel = '';
					}
					
				// if portfolio type - link
				} elseif ($type == 'link'){
					$link = get_post_meta(get_the_ID(), '_portfolio_link', true);
					$href = wt_get_superlink($link);
					$link_target = get_post_meta(get_the_ID(), '_portfolio_link_target', true);
					
					$link_target = $link_target ? $link_target : '_self';
					
					$icon = '<i class="fa-link"></i>';
					$lightbox = '';
					$rel = '';
					
				// if portfolio type - document
				} else {
					$href = get_permalink();
					$link_target = get_post_meta(get_the_ID(), '_doc_target', true);
					$link_target = $link_target ? $link_target : '_self';
					
					$icon = '<i class="fa-file-text"></i>';
					$lightbox = '';
					$rel = '';
				}				
			} 
			/* End if (has_post_thumbnail() */
			
			if ($carousel == 'true') {
				$output .= '<div class="item">';
			}
			
			if ($columns == 1) {
				$output .= '<article data-order="'.$order.'" id="post-' . get_the_ID() . '" class="portEntry">';
				$output .= '<header class="wt_portofolio_item wt_two_third ' . implode(' ', $terms_slug) . '">';	
			} else { // $columns==2 || $columns==3 || $columns==4 || $columns==6
				$output .= '<article data-order="'.$order.'" id="post-' . get_the_ID() . '" class="portEntry wt_portofolio_item';
				$output .= ' ' . implode(' ', $terms_slug) . '">';
				$output .= '<div class="wt_portofolio_container">';
			}
			
			switch($columns){
				case 1: // portfolio one column
					$width  = '620px';
					$height = '312px';
					break;
				case 2:	// portfolio two columns
					$width  = '730px';
					$height = '465px';
					$width_inline  = '730';
					$height_inline = '654';
					break;
				case 3: // portfolio three columns
					$width  = '730px';
					$height = '465px';
					$width_inline  = '730';
					$height_inline = '465';
					break;				
				case 6: // portfolio six columns
				case 5: // portfolio five columns	
				case 4: // portfolio four columns
				default:
					$width  = '480px';
					$height = '305px';
					$width_inline  = '480';
					$height_inline = '305';
			}
							
			if (has_post_thumbnail()) {
				$output .= '<figure class="wt_image_frame">';
				$output .= '<span class="wt_image_holder">';	
				$output .= '<img src="'. aq_resize( wt_get_image_src($image[0]), $width, $height, true ).'" alt="' . get_the_title() . '" width="'.$width_inline.'" height="'.$height_inline.'" />';
				
				if($overlay_desc == 'false') { // if overlay title / excerpt is false
					$output .= '<span class="wt_image_overlay"></span>';
					$output .= '<a class="wt_icon_lightbox" '.(isset($link_target)?'target="'.$link_target.'" ':'').' '.$rel.'  href="' . $href . $video_width . $video_height . '" title="' . get_the_title() . '">'.$icon.'</a>';
				}
				
				$output .= '</span>';
				$output .= '</figure>';
			} /* End if (has_post_thumbnail() */	
			
			if ($columns == 1) {
				$output .= '</header>'; // End header - "wt_portofolio_item"
				$output .= '<div class="wt_portofolio_item wt_one_third last';
				$output .= ' ' . $term->slug . '">';
			} 
			
			if ( $columns != 1 && ($excerpt == 'true' || $title == 'true' || $category == 'true' || $read_more == true) ) {		
				$output .= '<div class="wt_portofolio_details">';
				
				if($overlay_desc == 'true') { // if overlay title / excerpt is true
					$output .= '<a class="wt_icon_lightbox" '.(isset($link_target)?'target="'.$link_target.'" ':'').' '.$rel.'  href="' . $href . $video_width . $video_height . '" title="' . get_the_title() . '">'.$icon.'</a>';
				}
			}
			
				// If portfolio title
				if($title == 'true'){
					if($title_linkable == 'true'){
						$output .= '<h4 class="wt_portfolio_title"><a href="'.get_permalink().'">' . get_the_title() . '</a></h4>';
					} else {
						$output .= '<h4 class="wt_portfolio_title">' . get_the_title() . '</h4>';
					}
				}
				
				//  If excerpt, category or read_more then build "wt_portofolio_det" wrapper
				if ($excerpt == 'true' || $category == 'true' || $read_more == true){			
					$output .= '<div class="wt_portofolio_det">';	
				}					
					
					// if category is set to Yes	
					if ($category == 'true'){		
						$output .= '<p class="wt_portfolioCategory">'.$term->name.'</p>';
					}
					
					// if excerpt is set to Yes	
					if ($excerpt == 'true'){
						
						/* Display all post content or post excerpt */						
						if ( $full == 'true' ){
							$read_more = false;
							$content = get_the_content();
							$content = apply_filters('the_content', $content);
							$content = str_replace(']]>', ']]&gt;', $content);
							$output .= $content;
						} else {
							$content = WT_VCSC_Excerpt( $excerpt_length, false, $read_more_text );
							$output .= '<div class="wt_portfolio_excerpt">'.$content.'</div>';
						}						
						
					}
				
					// if read_more is set to Yes					
					if ( $read_more == true ) {	
						$more_link = wt_get_superlink(get_post_meta(get_the_id(), '_portfolio_link', true), get_permalink());						
						$more_link_target = get_post_meta(get_the_ID(), '_portfolio_link_target', true);
						$more_link_target = $more_link_target ? $more_link_target : '_self';
							
						$readmore_link = '<p class="wt_portf_readmore"><a href="' .$more_link. '" target="'.$more_link_target.'" title="' .$read_more_text. '" rel="bookmark" class="read_more_link">' .$read_more_text. ' <span class="wt-readmore-rarr">&raquo;</span></a></p>';
						
						$output .= apply_filters( 'wt_readmore_link', $readmore_link );
					}
				
				if($excerpt == 'true' || $category == 'true' || $read_more == true){
					$output .= '</div>'; // End div - "wt_portofolio_det"
				}
			
			if ( $columns != 1 && ($excerpt == 'true' || $title == 'true' || $category == 'true' || $read_more == true) ) {	
				$output .= '</div>'; // End div - "wt_portofolio_details"
			}
			
			if ($columns==1) {				
				$output .= '</div>'; // End div - "wt_portofolio_item"			
			} else { // $columns==2 || $columns==3 || $columns==4 || $columns==6				
				$output .= '</div>'; // End div - "wt_portofolio_container"
			}
				
			$output .= '</article>'; // End portfolio article
			
			if ($carousel == 'true') {
				$output .= '</div>'; // End "item" div
			}
		}
				
		if ($carousel == 'true') {
			$output .= '</div>'; // End "wt_owl_carousel" div	
			$output .= '</div>'; // End "wt_port_carousel" div				
		}
				
		if ($pagination == 'true' && $carousel == 'false') {
			ob_start();
			WT_VCSC_PortfolioPageNavi('', '', $wt_query, $paged, $pagination_align);
			$output .= ob_get_clean();
		}
		
		wp_reset_postdata();
		$wp_filter['the_content'] = $the_content_filter_backup;
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
	
	vc_map( array(
		'name' => __('WT Portfolio', 'wt_vcsc'),
		'base' => 'wt_portfolio',
		'icon' => 'wt_vc_ico_portfolio',
		'class' => 'wt_vc_sc_portfolio',
		'category' => __('by WhoaThemes', 'wt_vcsc'),
		'description' => __('Recent portfolio posts', 'wt_vcsc'),
		'params' => array(
			array(
				'type'			=> 'dropdown',
				'class'			=> '',
				'heading'		=> __( 'Columns', 'wt_vcsc' ),
				'param_name'	=> 'columns',
				'admin_label'	=> true,
				'value' 		=> array(
					//__( 'One', 'wt_vcsc' )		=> '1',
					__( 'Two', 'wt_vcsc' )		=> '2',
					__( 'Three', 'wt_vcsc' )	=> '3',
					__( 'Four', 'wt_vcsc' )	    => '4',
					__( 'Five', 'wt_vcsc' )	    => '5',
					__( 'Six', 'wt_vcsc' )	    => '6',
				),
				'std'	        => '4',
				'description'	=> __( 'How many columns? Only \'1, 2, 3, 4, 6\' are accepted.', 'wt_vcsc' ),
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Portfolio Grid Spaces', 'wt_vcsc'),
				'param_name'    => 'grid_spaces',
				'value' => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('If selected, portfolio articles will have spaces around.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Portfolio Overlay Title / Excerpt', 'wt_vcsc'),
				'param_name'    => 'overlay_desc',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('If selected, title and portfolio excerpt will overlay the featured image on hover.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Max (posts number)', 'wt_vcsc'),
				'param_name'    => 'max',
				'value'         => '-1',
				'description'   => __('How many items do you wish to show? Set -1 to display all.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Pagination', 'wt_vcsc'),
				'param_name'    => 'pagination',
				'value' => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('Display pagination.', 'wt_vcsc')
			),		
			array(
				'type'          => 'dropdown',
				'heading'       => __('Pagination alignment', 'wt_vcsc'),
				'param_name'    => 'pagination_align',
				'value'         => array(__('Align left', 'wt_vcsc') => 'left', __('Align right', 'wt_vcsc') => 'right', __('Align center', 'wt_vcsc') => 'center'),
				'std'           => 'center',
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	     => 'pagination',
					'value'		     => 'true'
				),
				'description'   => __('Aligns pagination links to left / center / right.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Sortable', 'wt_vcsc'),
				'param_name'    => 'sortable',
				'value' => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('Display sortable portfolio. Posts will be sorted and grouped by category. This will disable "Pagination" above option.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Carousel', 'wt_vcsc'),
				'param_name'    => 'carousel',
				'value'         => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'description'   => __('Display portfolio posts with carousel. This will disable "Pagination & Sortable" above options.', 'wt_vcsc')
			),
			array(
				'type'               => 'textfield',
				'heading'            => __('Carousel Autoscrolling', 'wt_vcsc'),
				'param_name'         => 'auto_slide',
				'value'              => '0',
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	     => 'carousel',
					'value'		     => 'true'
				),
				'description'   => __('Enables autoscrolling and define the time interval (in miliseconds - Ex: 3000, 4000, 5000 etc) between transitions.', 'wt_vcsc')
			),
			array(
				'type'               => 'dropdown',
				'heading'            => __('Carousel Navigation', 'wt_vcsc'),
				'param_name'         => 'carousel_nav',
				'value' 			 => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	     => 'carousel',
					'value'		     => 'true'
				),
				'description'   => __('Enables carousel navigation ( prev / next buttons ).', 'wt_vcsc')
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
				'type'               => 'dropdown',
				'heading'            => __('Title linkable', 'wt_vcsc'),
				'param_name'         => 'title_linkable',
				'value'              => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	=> 'title',
					'value'		=> 'true'
				),
				'description'   => __('Link on title?', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Excerpt (post content)', 'wt_vcsc'),
				'param_name'    => 'excerpt',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'std'           => 'false',
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
				'type'               => 'dropdown',
				'heading'            => __('Display full post?', 'wt_vcsc'),
				'param_name'         => 'full',
				'value'              => array( 
					__('No', 'wt_vcsc')    => 'false',
					__('Yes', 'wt_vcsc')   => 'true',
				),
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'	     => Array(
					'element'	=> 'excerpt',
					'value'		=> 'true'
				),
				'description'        => __('Display all posts content instead of the auto excerpt. Excerpt option above should be \'YES\'.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Category', 'wt_vcsc'),
				'param_name'    => 'category',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'std'           => 'false',
				'description'   => __('Display portfolio post category.', 'wt_vcsc')
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
				'std'           => 'false',
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
			array(
				'type'          => 'dropdown',
				'heading'       => __('Group', 'wt_vcsc'),
				'param_name'    => 'group',
				'value' => array( 
					__('Yes', 'wt_vcsc')   => 'true',
					__('No', 'wt_vcsc')    => 'false',
				),
				'description'   => __('Portfolio item tuhmbnails (featured images) will be grouped in the lightbox.', 'wt_vcsc')
			),			
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Display specific portfolio items (optional)', 'wt_vcsc'),
				'param_name'    => 'ids',
				'value'         => '',
				'target'        => 'wt_portfolio',
				'description'   => __('Display only specific / selected portfolio items. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Display from category (optional)', 'wt_vcsc'),
				'param_name'    => 'cat',
				'value'         => '',
				'target'        => 'wt_portfolio_category',
				'description'   => __('Display portfolio items from selected categories. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
			),	
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Exclude selected categories (optional)', 'wt_vcsc'),
				'param_name'    => 'cat__not',
				'value'         => '',
				'target'        => 'wt_portfolio_category',
				'description'   => __('Display portfolio items and exclude selected categories. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>.', 'wt_vcsc')
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
					__( 'Parent', 'wt_vcsc')			=> 'parent',
					__( 'Random', 'wt_vcsc')			=> 'rand',
					__( 'Comment Count', 'wt_vcsc' )	=> 'comment_count',
					__( 'Menu Order', 'wt_vcsc' )	    => 'menu_order',
				),
				'std'	        => 'date',
			),	
			
			$add_wt_extra_id,
			$add_wt_extra_class,
			
			array(
				'type' => 'css_editor',
				'heading' => __('Css', 'wt_vcsc'),
				'param_name' => 'css',
				'group' => __('Design options', 'wt_vcsc')
			)
		)
	));
	
}