<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_testimonials_slider extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
						
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'mode'           => 'horizontal',
			'effect'         => 'ease-in-out',
			"speed"          => '500',
			"pause"          => '3000',
			'autoplay'       => '',
			'controlnav'     => '',
			'pagernav'       => '',
			'img_size'       => 'thumbnail',
			'slide_count'    => 1,
									
			'el_id'          => '',
			'el_class'       => '',
    		'css_animation'  => '',
    		'anim_type'      => '',
    		'anim_delay'     => '',			
			'css'            => ''		
		), $atts ) );
		
		$sc_class = 'wt_testimonials_slider_sc';
						
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
		
		wp_print_scripts('wt-extend-bx-slider');
		wp_enqueue_style('wt-extend-bx-slider');
		
		$output = '';		
		
		$autoplay   !== "true" ? $autoplay = 'false' : '';
		$controlnav !== "true" ? $controlnav = 'false' : '';
		$pagernav   !== "true" ? $pagernav = 'false' : '';		
		
		$el_class = esc_attr( $this->getExtraClass($el_class) );
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);	
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$speed    = (int)$speed;
		$pause    = (int)$pause;
		$img_size = esc_html($img_size);
		
		$output .= '<div id="'.$el_id.'" class="'.$css_class.'"'.$anim_data.'>';
				
		$output .= "\n\t\t\t".'<ul class="wt_bxslider" data-bx-mode="'.$mode.'" data-bx-effect="'.$effect.'" data-bx-speed="'.$speed.'" data-bx-pause="'.$pause.'" data-bx-autoPlay="'.$autoplay.'" data-bx-controlNav="'.$controlnav.'" data-bx-pagerNav="'.$pagernav.'">';
		
		for($i = 1; $i <= $slide_count; $i++) {
			$item_content = '';
			$image = '';					
								
			isset($atts["content_" . $i]) && $atts["content_" . $i] != "" ? $item_content = $atts["content_" . $i] : '';
			if ($item_content != '') {
				$item_content = wpb_js_remove_wpautop($item_content, true); // fix unclosed/unwanted paragraph tags in $content
			}			
						
			if ($atts["image_" . $i] != NULL) {
				$image = $atts["image_" . $i];
				$img_id = preg_replace('/[^\d]/', '', $image);				
				$img = wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => $img_size, 'class' => 'wt_testimonial_image' ));
			} else {
				$image = '';
			}
			
			isset($atts["target_" . $i]) && $atts["target_" . $i] == "true" ? $target = '_blank' : $target = '_self';
								
			$output .= "\n\t\t\t\t".'<li class="item">';
			
				if ($image != '') {					
					$output .= "\n\t\t\t\t\t".$img['thumbnail'];
				}
										
				$output .= "\n\t\t\t\t\t".$item_content;
				
				if (isset($atts["name_" . $i]) && $atts["name_" . $i] != "") {										
					$item_name = esc_html( $atts["name_" . $i] );
										
					if (isset($atts["link_" . $i]) && $atts["link_" . $i] != "") {											
						$output .= "\n\t\t\t\t\t" . '<h3><a href="'.esc_url($atts["link_" . $i]).'" title="'.$item_name.'" target="'.$target.'">' . $item_name . '</a></h3>';
					} else {				
						$output .= "\n\t\t\t\t\t" . '<h3>'.$item_name.'</h3>';
					}
				}
			$output .= "\t\t\t\t".'</li>';
		}
		
		$output .= "\n\t\t\t".'</ul>';
		$output .= "\n\t\t\t".'</div>';			
		
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
		'name'          => __('WT Testimonials Slider', 'wt_vcsc'),
		'base'          => 'wt_testimonials_slider',
		'icon'          => 'wt_vc_ico_testimonials_slider',
		'class'         => 'wt_vc_sc_testimonials_slider',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Testimonials slider', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'dropdown',
				'heading'       => __('Mode', 'wt_vcsc'),
				'param_name'    => 'mode',
				'value'         => array( 
					__('Horizontal', 'wt_vcsc') => 'horizontal',
					__("Vertical", "wt_vcsc")   => 'vertical',
					__('Fade', 'wt_vcsc')       => 'fade'
				),
				'description'   => __('Type of transition between slides.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Effect', 'wt_vcsc'),
				'param_name'    => 'effect',
				'value'         => array( 
					__("EaseInOut", "wt_vcsc") => 'ease-in-out',
					__('EaseOut', 'wt_vcsc')   => 'ease-out',
					__('Ease', 'wt_vcsc')      => 'ease',
					__('easeIn', 'wt_vcsc')    => 'ease-in',
					__('Linear', 'wt_vcsc')    => 'linear'
				),
				'description'   => __('Here you can set the transition effect when the items are changing.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Speed', 'wt_vcsc'),
				'param_name'    => 'speed',
				'std'           => '500',
				'description'   => __('Here you can set the slide transition duration (in miliseconds). Example: \'100\', \'500\', \'1000\'.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Pause', 'wt_vcsc'),
				'param_name'    => 'pause',
				'std'           => '3000',
				'description'   => __('The amount of time between each auto transition. (in miliseconds). Example: \'100\', \'500\', \'1000\'.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Auto Start', 'wt_vcsc'),
				'param_name'    => 'autoplay',
				'value'         => array( __( 'Yes, please', 'wt_vcsc' ) => 'true' ),
				'description'   => __('If YES, slides will automatically transition.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Control Navigation', 'wt_vcsc'),
				'param_name'    => 'controlnav',
				'value'         => array( __( 'Yes, please', 'wt_vcsc' ) => 'true' ),
				'description'   => __('If YES, the Control Navigation (next & prev buttons) is displayed.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Pager (Navigation)', 'wt_vcsc'),
				'param_name'    => 'pagernav',
				'value'         => array( __( 'Yes, please', 'wt_vcsc' ) => 'true' ),
				'description'   => __('If YES, the Pager Navigation is displayed.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Image size', 'wt_vcsc'),
				'param_name'    => 'img_size',
				'description'   => __('If you set images for testimonials, then here you can enter image size. Example: "thumbnail", "medium", "large", "full" or other sizes defined by current theme. Alternatively enter image size in pixels: 200x100 (Width x Height). Leave empty to use "thumbnail" size.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Number Of Items.', 'wt_vcsc'),
				'param_name'    => 'slide_count',
				'value'         => array( 
					1  => '1', 
					2  => '2', 
					3  => '3', 
					4  => '4', 
					5  => '5', 
					6  => '6', 
					7  => '7', 
					8  => '8', 
					9  => '9',
					10 => '10', 
					11 => '11',
					12 => '12',
					13 => '13',
					14 => '14',
					15 => '15'
				),
				'description'   => __('Specify the number of slide items. <strong>Maximum allowed is \'15\' items</strong>.', 'wt_vcsc')
			),		
						
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),1),
					'param_name'         => sprintf("content_%d",1),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),1),
					'param_name'         => sprintf("name_%d",1),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),1),
					'param_name'         => sprintf("link_%d",1),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),1),
					'param_name'         => sprintf("target_%d",1),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",1), 'not_empty' => true )
				),				
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),1),
					'param_name'         => sprintf("image_%d",1),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),2),
					'param_name'         => sprintf("content_%d",2),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),2),
					'param_name'         => sprintf("name_%d",2),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),2),
					'param_name'         => sprintf("link_%d",2),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),2),
					'param_name'         => sprintf("target_%d",2),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",2), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),2),
					'param_name'         => sprintf("image_%d",2),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('2','3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),3),
					'param_name'         => sprintf("content_%d",3),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),3),
					'param_name'         => sprintf("name_%d",3),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),3),
					'param_name'         => sprintf("link_%d",3),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),3),
					'param_name'         => sprintf("target_%d",3),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",3), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),3),
					'param_name'         => sprintf("image_%d",3),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('3','4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),4),
					'param_name'         => sprintf("content_%d",4),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),4),
					'param_name'         => sprintf("name_%d",4),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),4),
					'param_name'         => sprintf("link_%d",4),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),4),
					'param_name'         => sprintf("target_%d",4),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",4), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),4),
					'param_name'         => sprintf("image_%d",4),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('4','5','6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),5),
					'param_name'         => sprintf("content_%d",5),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),5),
					'param_name'         => sprintf("name_%d",5),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('5','6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),5),
					'param_name'         => sprintf("link_%d",5),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('5','6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),5),
					'param_name'         => sprintf("target_%d",5),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",5), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),5),
					'param_name'         => sprintf("image_%d",5),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('5','6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),6),
					'param_name'         => sprintf("content_%d",6),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),6),
					'param_name'         => sprintf("name_%d",6),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('6','7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),6),
					'param_name'         => sprintf("link_%d",6),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('6','7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),6),
					'param_name'         => sprintf("target_%d",6),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",6), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),6),
					'param_name'         => sprintf("image_%d",6),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('6','7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),7),
					'param_name'         => sprintf("content_%d",7),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),7),
					'param_name'         => sprintf("name_%d",7),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('7','8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),7),
					'param_name'         => sprintf("link_%d",7),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('7','8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),7),
					'param_name'         => sprintf("target_%d",7),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",7), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),7),
					'param_name'         => sprintf("image_%d",7),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('7','8','9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),8),
					'param_name'         => sprintf("content_%d",8),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),8),
					'param_name'         => sprintf("name_%d",8),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('8','9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),8),
					'param_name'         => sprintf("link_%d",8),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('8','9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),8),
					'param_name'         => sprintf("target_%d",8),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",8), 'not_empty' => true )
				),		
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),8),
					'param_name'         => sprintf("image_%d",8),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('8','9','10','11','12','13','14','15')
					)
				),
				
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),9),
					'param_name'         => sprintf("content_%d",9),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),9),
					'param_name'         => sprintf("name_%d",9),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('9','10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),9),
					'param_name'         => sprintf("link_%d",9),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('9','10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),9),
					'param_name'         => sprintf("target_%d",9),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",9), 'not_empty' => true )
				),		
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),9),
					'param_name'         => sprintf("image_%d",9),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('9','10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),10),
					'param_name'         => sprintf("content_%d",10),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('10','11','12','13','14','15')
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),10),
					'param_name'         => sprintf("name_%d",10),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('10','11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),10),
					'param_name'         => sprintf("link_%d",10),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('10','11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),10),
					'param_name'         => sprintf("target_%d",10),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",10), 'not_empty' => true )
				),	
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),10),
					'param_name'         => sprintf("image_%d",10),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(

						'element' => 'slide_count', 
						'value'   => array('10','11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),11),
					'param_name'         => sprintf("content_%d",11),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('11','12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),11),
					'param_name'         => sprintf("name_%d",11),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('11','12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),11),
					'param_name'         => sprintf("link_%d",11),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('11','12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),11),
					'param_name'         => sprintf("target_%d",11),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",11), 'not_empty' => true )
				),		
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),11),
					'param_name'         => sprintf("image_%d",11),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('11','12','13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),12),
					'param_name'         => sprintf("content_%d",12),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('12','13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),12),
					'param_name'         => sprintf("name_%d",12),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('12','13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),12),
					'param_name'         => sprintf("link_%d",12),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('12','13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),12),
					'param_name'         => sprintf("target_%d",12),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",12), 'not_empty' => true )
				),			
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),12),
					'param_name'         => sprintf("image_%d",12),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('12','13','14','15')
					)
				),
				
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),13),
					'param_name'         => sprintf("content_%d",13),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('13','14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),13),
					'param_name'         => sprintf("name_%d",13),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('13','14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),13),
					'param_name'         => sprintf("link_%d",13),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('13','14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),13),
					'param_name'         => sprintf("target_%d",13),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",13), 'not_empty' => true )
				),			
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),13),
					'param_name'         => sprintf("image_%d",13),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('13','14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),14),
					'param_name'         => sprintf("content_%d",14),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('14','15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),14),
					'param_name'         => sprintf("name_%d",14),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('14','15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),14),
					'param_name'         => sprintf("link_%d",14),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('14','15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),14),
					'param_name'         => sprintf("target_%d",14),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",14), 'not_empty' => true )
				),			
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),14),
					'param_name'         => sprintf("image_%d",14),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('14','15')
					)
				),
					
				array(
					'type'               => 'textarea',
					'heading'            => sprintf(__("Item Content %d",'wt_vcsc'),15),
					'param_name'         => sprintf("content_%d",15),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('15')
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("Item Name %d",'wt_vcsc'),15),
					'param_name'         => sprintf("name_%d",15),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('15')
					)
				),			
				array(
					'type'               => 'textfield',
					'heading'            => sprintf(__("URL (Link) %d",'wt_vcsc'),15),
					'param_name'         => sprintf("link_%d",15),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('Don\'t forget to include the <strong>http://</strong> at the front.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('15')
					)
				),
				array(
					'type'               => 'checkbox',
					'heading'            => sprintf(__("Target %d",'wt_vcsc'),15),
					'param_name'         => sprintf("target_%d",15),
					'param_holder_class' => 'border_box wt_dependency',
					'value'              => array( __( 'Open in new window?', 'wt_vcsc' ) => 'true' ),
					'dependency'         => array( 'element' => sprintf("link_%d",15), 'not_empty' => true )
				),			
				array(
					'type'               => 'attach_image',
					'heading'            => sprintf(__("Testimonial Image %d",'wt_vcsc'),15),
					'param_name'         => sprintf("image_%d",15),
					'param_holder_class' => 'border_box wt_dependency',
					'description'        => __('If you want to use an image for this testimonial you can upload it here.', 'wt_vcsc'),
					'dependency'         => array(
						'element' => 'slide_count', 
						'value'   => array('15')
					)
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