<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_gallery_grid extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
	
	public function singleParamHtmlHolder( $param, $value ) {
		$output = '';
		// Compatibility fixes
		$old_names = array( 'yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange' );
		$new_names = array( 'alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning' );
		$value = str_ireplace( $old_names, $new_names, $value );
		//$value = __($value, "js_composer");
		//
		$param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
		$type = isset( $param['type'] ) ? $param['type'] : '';
		$class = isset( $param['class'] ) ? $param['class'] : '';

		if ( isset( $param['holder'] ) == true && $param['holder'] !== 'hidden' ) {
			$output .= '<' . $param['holder'] . ' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
		}
		if ( $param_name == 'images' ) {
			$images_ids = empty( $value ) ? array() : explode( ',', trim( $value ) );
			$output .= '<ul class="attachment-thumbnails' . ( empty( $images_ids ) ? ' image-exists' : '' ) . '" data-name="' . $param_name . '">';
			foreach ( $images_ids as $image ) {
				$img = wpb_getImageBySize( array( 'attach_id' => (int)$image, 'thumb_size' => 'thumbnail' ) );
				$output .= ( $img ? '<li>' . $img['thumbnail'] . '</li>' : '<li><img width="150" height="150" test="' . $image . '" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail" alt="" title="" /></li>' );
			}
			$output .= '</ul>';
			$output .= '<a href="#" class="column_edit_trigger' . ( ! empty( $images_ids ) ? ' image-exists' : '' ) . '">' . __( 'Add images', 'js_composer' ) . '</a>';

		}
		return $output;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'images'               => '',
			'img_width'			   => '9999',
			'img_height'		   => '9999',
			'columns'              => 4,
    		'grid_style'       	   => '',
			'thumbnail_link'   	   => '',
			'custom_links'	       => '',
			'custom_links_target'  => '_self',
    		'title'                => false,
    		'black_white'          => false,
			
			'el_id'                => '',
			'el_class'         	   => '',
    		'css_animation'        => '',
    		'anim_type'            => '',
    		'anim_delay'           => '',			
			'css'                  => ''		
		), $atts ) );
		
		$sc_class = 'wt_gallery_grid_sc';
		// Img Container Classes
		$sc_classes = array();	
		
		// Adding placeholder images if no image was set
		if ( $images == '' ) {
			switch( $columns ) {
				case 1  : $images = '-1';                break;
				case 2  : $images = '-1,-2';             break;
				case 3  : $images = '-1,-2,-3';          break;
				case 4  : $images = '-1,-2,-3,-4';       break;
				case 6  : 
				default : $images = '-1,-2,-3,-4,-5,-6'; break;
			}
		}
		
		// Get Attachments
		$images = explode( ',', $images );
		$i = -1;
					
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
		
		// Custom Links
		if ( $thumbnail_link == 'custom_link' ) {
			$custom_links = explode( ',', $custom_links);
		}
		
		// Column Class		
		if ( $columns == '1' ) {
			$sc_classes[] = 'wt_gallery_1_col';
		} else {
			$sc_classes[] = 'wt_gallery_'.$columns.'_cols';
		}		
		
		// Lightbox Class
		if ( $thumbnail_link == 'lightbox' ) {
			$sc_classes[] = 'wt_gallery_lightbox';
		}
		
		// No Margin Class
		if ( $grid_style == 'no-margins' ) {
			$sc_classes[] = 'wt_gallery_no_margins clearfix';
		} else {
			$sc_classes[] = 'row';
		}
		
		$sc_classes = implode(' ', $sc_classes);
		
		if ($sc_classes != '') { $sc_classes = ' ' . $sc_classes; }
		
		$sc_class = $sc_class . $sc_classes;
						
		$el_class = esc_attr( $this->getExtraClass($el_class) );
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);	
		//$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		//$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		// Load prettyphoto scripts & styles
		if ( $thumbnail_link == 'lightbox' ) {
			wp_enqueue_script( 'prettyphoto' );
			wp_enqueue_style( 'prettyphoto' );
		}
		
		// Output posts
		if( $images ) :
		
			// Img Container Classes
			$classes = array();
			
			switch( $columns ) {
				case 1  : $classes[] = 'col-xs-12 col-sm-12 col-lg-12 col-md-12'; break;
				case 2  : $classes[] = 'col-xs-6 col-sm-6 col-md-6 col-lg-6';     break;
				case 3  : $classes[] = 'col-xs-6 col-sm-6 col-md-4 col-lg-4';     break;
				case 4  : $classes[] = 'col-xs-6 col-sm-6 col-md-3 col-lg-3';     break;
				case 6  : 
				default : $classes[] = 'col-xs-6 col-sm-6 col-md-2 col-lg-2';     break;
			}
			
			if ( $black_white == 'yes' ) {
				$classes[] = 'wt_grayscale';
			}			
			
			$classes = implode(' ', $classes);
			
			wp_enqueue_script( 'waypoints' ); // VC file
													
			$output = '<div id="'.$el_id.'" class="'.$css_class.'">';
				
				$count = 0;	
				foreach ( $images as $attach_id ) {
					$image_output = '';				
					$i ++;					
					$delay = $count * 150;			
					$count ++;									
					
					// if image alt not set then take it's title
					$attachment_meta = WT_WpGetAttachment($attach_id);
					if (!empty($attachment_meta['alt'])) {
						$img_title = $attachment_meta['alt'];
					} else {
						$img_title = $attachment_meta['title'];
					}					
					
					$img = $attachment_meta['src'];
					//$img = wp_get_attachment_url( $attach_id );					
					
					if ('9999' != $img_width && '9999' != $img_height) {
						$width_inline  = $img_width;
						$height_inline = $img_height;
					} else {
						$image_meta = wp_get_attachment_image_src($attach_id, 'full', true);
						$width_inline  = $image_meta[1]; // get original image width
						$height_inline = $image_meta[2]; // get original image height
					}
					
					if ( $grid_style == 'no-margins' ) {
						$imgThumb = '';
					} else {
						$imgThumb = ' class="img-thumbnail"';
					}
					
					if ( $attach_id > 0 ) {
						// Crop featured images if necessary
						if( function_exists( 'aq_resize' ) ) {
							$thumb_hard_crop = ( '9999' == $img_height ) ? false : true;
							$cropped_img = aq_resize( $img, $img_width, $img_height, $thumb_hard_crop );
						}
					
						$img_output = '<img'. $imgThumb .' src="'. $cropped_img .'" width="'. $width_inline .'" height="'. $height_inline .'" alt="'. $img_title .'" />';
					} else { // If no image was set then show placeholders
						$img_output  = '<img'. $imgThumb .' src="' . vc_asset_url( 'vc/no_image.png' ) . '" />';
					}
					
					// Image output	depending of image link									
					if ( $thumbnail_link == 'lightbox' ) {
						$image_output .= $img_output;
						$icon = '<i class="entypo-search"></i>';
						$image_output .= '<div class="wt_gallery_overlay">';
							$image_output .= '<a href="'. $img .'" title="'. $img_title .'" class="wt_image_zoom" data-rel="lightbox[gallery_'.$id.']">'.$icon.'</a>';
							if ( $title == 'yes' ) {
								$image_output .= '<h3>'. $img_title .'</h3>';
							}
						$image_output .= '</div>';
					} elseif ( $thumbnail_link == 'custom_link' ) {
						$custom_link = !empty($custom_links[$i]) ? $custom_links[$i] : '#';
						if ( $custom_link == '#' ) {
							$image_output .= $img_output;
						} else {
							$image_output .= '<a href="'. $custom_link .'" title="'. $img_title .'" target="'. $custom_links_target .'">';
								$image_output .= $img_output;
							$image_output .= '</a>';
						}
					} else {
						$image_output .= $img_output;
					}
					$output .= '<article class="wt_gallery_grid_item '.$classes.' wt_col_'.$count.' wt_animate wt_animate_if_visible" data-animation="flipInX" data-animation-delay="'.$delay.'">';
						$output .= "\n\t" . '<div class="wt_gallery_item_inner">';
							$output .= "\n\t\t" . $image_output;
						$output .= "\n\t" . '</div>';
					$output .= '</article>';
					
					if ( $count == $columns ) $count = 0; // reset column number
				}
			
			$output .= '</div>';
		
		endif; // End has posts check
		
		// Reset query
		wp_reset_postdata();
		
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
	$add_wt_css_animation       = $add_wt_sc_func->getWTAnimations();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	vc_map( array(
		'name'          => __('WT Gallery Grid', 'wt_vcsc'),
		'base'          => 'wt_gallery_grid',
		'icon'          => 'wt_vc_ico_gallery_grid',
		'class'         => 'wt_vc_sc_gallery_grid',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Responsive image gallery grid', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'attach_images',
				'heading'       => __('Images', 'wt_vcsc'),
				'param_name'    => 'images',
				'value'         => '',
				'description'   => __('Select images from media library.', 'wt_vcsc')
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Image Crop Width', 'wt_vcsc' ),
				'param_name'	=> 'img_width',
				'value'			=> '9999',
				'description'	=> __( 'Enter the width in pixels.', 'wt_vcsc' ),
			),
			array(
				'type'			=> 'textfield',
				'heading'		=> __( 'Image Crop Height', 'wt_vcsc' ),
				'param_name'	=> 'img_height',
				'value'			=> '9999',
				"description"	=> __( 'Enter the height in pixels. Set to "9999" to disable vertical cropping and keep image proportions.', 'wt_vcsc' ),
			),		
			array(
				'type'          => 'dropdown',
				'heading'       => __('Columns', 'wt_vcsc'),
				'param_name'    => 'columns',
				'value' 		=> array(
					__( 'Six', 'wt_vcsc' )	 => 6,
					__( 'Four', 'wt_vcsc' )	 => 4,
					__( 'Three', 'wt_vcsc' ) => 3,
					__( 'Two', 'wt_vcsc' )   => 2,
					__( 'One', 'wt_vcsc' )   => 1,
				),
				'std'	        => 4,
				'description'   => __('Select the number of columns for your grid gallery.', 'wt_vcsc')
			),		
			array(
				'type'          => 'dropdown',
				'heading'       => __('Grid style', 'wt_vcsc'),
				'param_name'    => 'grid_style',
				'value' 		=> array(
					__( 'Default', 'wt_vcsc' )	     => '',
					__( 'No Margins', 'wt_vcsc' )	 => 'no-margins',
				),
				'description'   => __('Select the grid style for your gallery.', 'wt_vcsc')
			),
			array(
				'type'			=> 'dropdown',
				'heading'		=> __( 'Image link', 'wt_vcsc' ),
				'param_name'	=> 'thumbnail_link',
				'value'			=> array(
					__( 'None', 'wt_vcsc' )			=> '',
					__( 'Lightbox', 'wt_vcsc' )		=> 'lightbox',
					__( 'Custom Links', 'wt_vcsc' )	=> 'custom_link',
				),
				'description'	=> __( 'Where should the grid images link to?', 'wt_vcsc' ),
			),
			array(
				'type'               => 'exploded_textarea',
				'heading'            => __( 'Custom links', 'wt_vcsc' ),
				'param_name'         => 'custom_links',
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'		 => Array(
					'element'	=> 'thumbnail_link',
					'value'		=> array( 'custom_link' )
				),
				'description'        => __( 'Enter links for images here ( Ex: <strong>http://</strong>yoursite.com ). Divide links with linebreaks (Enter). For images withought links just add linebreak (Enter) or add "#" symbol.', 'wt_vcsc' )
			),
			array(
				'type'               => 'dropdown',
				'heading'            => __( 'Custom links target', 'wt_vcsc' ),
				'param_name'         => 'custom_links_target',
				'value'              => array(
					__( 'Same window', 'wt_vcsc' ) => '_self',
					__( 'New window', 'wt_vcsc' )  => "_blank"
				),
				'param_holder_class' => 'border_box wt_dependency',
				'dependency'		 => Array(
					'element'	=> 'thumbnail_link',
					'value'		=> array( 'custom_link' )
				),
				'description'        => __( 'Select where to open custom links.', 'wt_vcsc' )
			),		
			array(
				'type'          => 'checkbox',
				'heading'       => __('Display title?', 'wt_vcsc'),
				'param_name'    => 'title',
				'value'         => Array(__('Yes, please', 'wt_vcsc') => 'yes'),
				'description'   => __('If selected, the image title will be displayed.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Set black & white filter?', 'wt_vcsc'),
				'param_name'    => 'black_white',
				'value'         => Array(__('Yes, please', 'wt_vcsc') => 'yes'),
				'description'   => __('If selected, the images will be displayed with black & white filter.', 'wt_vcsc')
			),
			
			$add_wt_extra_id,
			$add_wt_extra_class,
			/*
			$add_wt_css_animation,
			$add_wt_css_animation_type,
			$add_wt_css_animation_delay,
			*/
			
			array(
				'type'          => 'css_editor',
				'heading'       => __('Css', 'wt_vcsc'),
				'param_name'    => 'css',
				'group'         => __('Design options', 'wt_vcsc')
			)
		)
	));
	
}