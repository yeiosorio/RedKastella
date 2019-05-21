<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_testimonial extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
	
	public function singleParamHtmlHolder($param, $value) {
        $output = '';
        // Compatibility fixes
        $old_names = array('yellow_message', 'blue_message', 'green_message', 'button_green', 'button_grey', 'button_yellow', 'button_blue', 'button_red', 'button_orange');
        $new_names = array('alert-block', 'alert-info', 'alert-success', 'btn-success', 'btn', 'btn-info', 'btn-primary', 'btn-danger', 'btn-warning');
        $value = str_ireplace($old_names, $new_names, $value);
        //$value = __($value, "wt_vcsc");
        //
        $param_name = isset($param['param_name']) ? $param['param_name'] : '';
        $type = isset($param['type']) ? $param['type'] : '';
        $class = isset($param['class']) ? $param['class'] : '';

        if ( isset($param['holder']) == false || $param['holder'] == 'hidden' ) {
            $output .= '<input type="hidden" class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="'.$value.'" />';
            if(($param['type'])=='attach_image') {
                $img = wpb_getImageBySize(array( 'attach_id' => (int)preg_replace('/[^\d]/', '', $value), 'thumb_size' => 'thumbnail' ));
                $output .= ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . vc_asset_url( 'vc/blank.gif' ) . '" class="attachment-thumbnail"  data-name="' . $param_name . '" alt="" title="" style="display: none;" />') . '<img src="' . THEME_URI . '/framework/shortcodes/assets/lib/img/admin/wt.png' . '" class="no_image_image' . ( $img && !empty($img['p_img_large'][0]) ? ' image-exists' : '' ) . '" /><a href="#" class="column_edit_trigger' . ( $img && !empty($img['p_img_large'][0]) ? ' image-exists' : '' ) . '">' . __( 'Add image', 'wt_vcsc' ) . '</a>';
            }
        }
        else {
            $output .= '<'.$param['holder'].' class="wpb_vc_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">'.$value.'</'.$param['holder'].'>';
        }
        return $output;
    }
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'image'            => '',
			'img_size'         => 'thumbnail',
    		'style'            => '',
    		'border_color'     => '',
    		'img_link_large'   => false,
    		'link'             => '',
			'name'        	   => '',
			'name_link'        => '',
			
			'el_id'            => '',
			'el_class'         => '',
    		'css_animation'    => '',
    		'anim_type'        => '',
    		'anim_delay'       => '',			
			'css'              => ''		
		), $atts ) );
		
		$sc_class = 'wt_testimonial_sc';	
					
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
				
		$style = ($style!='') ? $style : '';
		$el_style = '';		
		
		$img_size = esc_html($img_size);
		
		if ( $border_color != '' ) {
			if ($style == 'vc_box_border' || $style == 'vc_box_border_circle' ) {
				$el_style = 'background-color:' . esc_attr( $border_color ) . ';';
			}
			if ($style == 'vc_box_outline' || $style == 'vc_box_outline_circle' ) {
				$el_style = 'border-color:' . esc_attr( $border_color ) . ';';
			}
		}
		
		$img_id = preg_replace('/[^\d]/', '', $image);
		
		if ( $border_color != '' && ($style == 'vc_box_border' || $style == 'vc_box_border_circle' || $style == 'vc_box_outline' || $style == 'vc_box_outline_circle') ) {
			$img = wt_wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => $style, 'style' => $el_style ));
		} else {
			$img = wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => $style ));
		}
		
		// parse name link
		$name_link = ($name_link=='||') ? '' : $name_link;
		$name_link = vc_build_link($name_link);
		$a_name_href = $name_link['url'];
		
		$a_name_title = $name_link['title'];
		$a_name_title_output = ($a_name_title!='') ? ' title="' . esc_attr( $a_name_title ) .'"' : '';
		
		$a_name_target = $name_link['target'];
		$a_name_target_output = ($a_name_target!='') ? ' target="' . $a_name_target .'"' : '';
		
		$name_link_to = '';
		
		 if (!empty($a_name_href)) {
			$name_link_to = esc_url( $a_name_href );
		}
					
		// parse image link
		$link = ($link=='||') ? '' : $link;
		$link = vc_build_link($link);
		$a_href = $link['url'];
		
		$a_title = $link['title'];
		$a_title_output = ($a_title!='') ? ' title="' . esc_attr( $a_title ) .'"' : '';
		
		$a_target = $link['target'];
		$a_target_output = ($a_target!='') ? ' target="' . $a_target .'"' : '';
				
		$link_to = '';
		$a_class = '';
		
		if ($img_link_large==true) {
			$link_to = wp_get_attachment_image_src( $img_id, 'large');
			$link_to = $link_to[0];
			
			wp_enqueue_script( 'prettyphoto' );
			wp_enqueue_style( 'prettyphoto' );
			$a_class = ' class="prettyphoto"';
			$a_target_output = '';
		}
		else if (!empty($a_href)) {
			$link_to = esc_url( $a_href );
		}
		
		if(!empty($link_to) && !preg_match('/^(https?\:\/\/|\/\/)/', $link_to)) $link_to = 'http://'.$link_to;
		$img_output = ($style=='vc_box_shadow_3d') ? '<span class="vc_box_shadow_3d_wrap">' . $img['thumbnail'] . '</span>' : $img['thumbnail'];
		$image_string = !empty($link_to) ? '<a'.$a_class.' href="'.$link_to.'"' . $a_title_output . $a_target_output .'>'.$img_output.'</a>' : $img_output;
				
		//trim($name) == false ? $name = esc_html( $name ) : '';
		trim($name) == false ? $name = $name : '';
		
		$el_class = esc_attr( $this->getExtraClass($el_class) );
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);	
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$name_string = !empty($name_link_to) ? '<a href="'.$name_link_to.'"' . $a_name_title_output . $a_name_target_output .'>'.$name.'</a>' : $name;
				
		$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
				
		$output = '<div id="'.$el_id.'" class="'.$css_class.'"'.$anim_data.'>';
	        $output .= "\n\t".'<div class="wt_testimonial_content">';
				$output .= "\n\t\t".$content;
	        $output .= "\n\t".'</div>';
			
	        $output .= "\n\t".'<div class="wt_testimonial_bottom clearfix">';
				$output .= "\n\t\t".'<div class="wt_testimonial_avatar">';
					$output .= "\n\t\t\t".$image_string;
				$output .= "\n\t\t".'</div>';
				$output .= "\n\t\t".'<div class="wt_testimonial_meta">';
					$output .= "\n\t\t\t".'<p class="wt_testimonial_author">'.$name_string.'</p>';
				$output .= "\n\t\t".'</div>';
	        $output .= "\n\t".'</div>';
        $output .= '</div>';
		
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
		'name'          => __('WT Testimonial', 'wt_vcsc'),
		'base'          => 'wt_testimonial',
		'icon'          => 'wt_vc_ico_testimonial',
		'class'         => 'wt_vc_sc_testimonial',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Static testimonial', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'attach_image',
				'heading'       => __('Image', 'wt_vcsc'),
				'param_name'    => 'image',
				'value'         => '',
				'description'   => __('Select image from media library.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Image size', 'wt_vcsc'),
				'param_name'    => 'img_size',
				'description'   => __('Enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Image style', 'wt_vcsc'),
				'param_name'    => 'style',
				'value'         => WT_VCSC_getShared('single image styles')
			),
			array(
				'type'          => 'colorpicker',
				'heading'       => __('Border color', 'wt_vcsc'),
				'param_name'    => 'border_color',
				'dependency'    => Array('element' => 'style', 'value' => array('vc_box_border', 'vc_box_border_circle', 'vc_box_outline', 'vc_box_outline_circle')),
				'description'   => __( 'Select border color for your element.', 'wt_vcsc' )
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Link to large image?', 'wt_vcsc'),
				'param_name'    => 'img_link_large',
				'description'   => __('If selected, image will be linked to the larger image.', 'wt_vcsc'),
				'value'         => Array(__('Yes, please', 'wt_vcsc') => 'yes')
			),
			array(
				'type'          => 'vc_link',
				'heading'       => __('URL (Link)', 'wt_vcsc'),
				'param_name'    => 'link',
				'description'   => __( 'Select URL if you want this image to have a link.', 'wt_vcsc' ),
				'dependency'    => array(
					'element'   => 'img_link_large',
					'is_empty'  => true,
					//'callback'  => 'wpb_single_image_img_link_dependency_callback'
				)
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Name', 'wt_vcsc'),
				'holder'        => 'div',
				'param_name'    => 'name',
				'description'   => __('Set testimonial author name.', 'wt_vcsc')
			),
			array(
				'type'          => 'vc_link',
				'heading'       => __('URL (Link)', 'wt_vcsc'),
				'param_name'    => 'name_link',
				'description'   => __( 'Select URL if you want the author name to have a link.', 'wt_vcsc' )
			),	
			array(
				'type'          => 'textarea_html',
				'holder'        => 'div',
				'heading'       => __('Testimonial content', 'wt_vcsc'),
				'param_name'    => 'content',
				'value'         => __('<blockquote>Here you can enter a small text for this testimonial.</blockquote>', 'wt_vcsc'),
				'description'   => __('Enter testimonial description.', 'wt_vcsc')
			),	
			
			$add_wt_extra_id,
			$add_wt_extra_class,
			$add_wt_css_animation,
			$add_wt_css_animation_type,
			$add_wt_css_animation_delay,
			
			array(
				'type'          => 'css_editor',
				'heading'       => __('Css', 'wt_vcsc'),
				'param_name'    => 'css',
				'group'         => __('Design options', 'wt_vcsc')
			)
		)
	));
	
}