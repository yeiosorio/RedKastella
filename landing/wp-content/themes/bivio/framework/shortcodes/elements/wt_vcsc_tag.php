<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_tag extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'type'          => 'div',
			'el_id'         => '',
			'el_class'      => '',
			'el_style'      => '',
    		'css_animation' => '',
    		'anim_type'     => '',
    		'anim_delay'    => '',			
			'css'           => ''		
		), $atts ) );
		
		$sc_class = 'wt_tag_sc';
				
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}		
		
		$el_style = esc_attr($el_style);
				
		$el_class = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$el_style = $this->wt_sc->getWTElementStyle($el_style);
		
		$content = wpb_js_remove_wpautop($content); // fix unclosed/unwanted paragraph tags in $content
		
		$output = '<'.$type.' id="'.$el_id.'" class="'.$css_class.'"'.$el_style.$anim_data.'>';
	        $output .= "\n\t\t\t".$content;
        $output .= '</'.$type.'>';
		
        return $output;
    }
	
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/

if (function_exists('vc_map')) {

	$add_wt_sc_func             = new WT_VCSC_SHORTCODE;
	$add_wt_css_animation       = $add_wt_sc_func->getWTAnimations();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	vc_map( array(
		'name'          => __('WT Tag', 'wt_vcsc'),
		'base'          => 'wt_tag',
		'icon'          => 'wt_vc_ico_tag',
		'class'         => 'wt_vc_sc_tag',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Place HTML tags ( div, section, span, i )', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'dropdown',
				'heading'       => __('Type', 'wt_vcsc'),
				'param_name'    => 'type',
				'value'         => array(__('Div', 'wt_vcsc') => 'div', __('Section', 'wt_vcsc') => 'section', __('Span', 'wt_vcsc') => 'span', __('I', 'wt_vcsc') => 'i' ),
				'description'   => __('Select the html tag you need. This shortcode is very useful because you can create block elements withought html coding. You can give them an id, a class attribute or set an inline style.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Extra Unique ID name', 'wt_vcsc'),
				'param_name'    => 'el_id',
				'description'   => __('If you wish to style particular content element differently, then use this field to add a UNIQUE ID name and then refer to it in your css file.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Extra class name', 'wt_vcsc'),
				'param_name'    => 'el_class',
				'description'   => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc')
			),
			array(
				'type'          => 'textarea',
				'heading'       => __('Extra style', 'wt_vcsc'),
				'param_name'    => 'el_style',
				'description'   => __('If you wish to use inline styles, then use this field. The style attribute can contain any CSS property. <br>Example: color:sienna;margin-left:20px;', 'wt_vcsc')
			),
			array(
				'type'          => 'textarea_html',
				'holder'        => 'div',
				'class'         => '',
				'heading'       => __('Content', 'wt_vcsc'),
				'param_name'    => 'content',
				'value'         => __('<p>I am test text block. Click edit button to change this text.</p>', 'wt_vcsc'),
				'description'   => __('Enter your content.', 'wt_vcsc')
			),
			
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