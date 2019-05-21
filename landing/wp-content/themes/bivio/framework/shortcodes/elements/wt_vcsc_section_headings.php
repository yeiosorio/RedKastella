<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_section_headings extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'el_class'      => '',
    		'css_animation' => '',
    		'anim_type'     => '',
    		'anim_delay'    => '',			
			'css'           => ''		
		), $atts ) );
		
		$sc_class   = 'wt_section_heading intro_text';
									
		$el_class   = esc_attr( $this->getExtraClass($el_class) );		
		$css_class  = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data  = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
				
		$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content
		
		$output = '<div class="'.$css_class.'"'.$anim_data.'>';
	        $output .= "\n\t".$content;
        $output .= '</div>';
		
        return $output;
    }
	
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/

if (function_exists('vc_map')) {

	$add_wt_sc_func             = new WT_VCSC_SHORTCODE;
	$add_wt_extra_class         = $add_wt_sc_func->getWTExtraClass();
	$add_wt_css_animation       = $add_wt_sc_func->getWTAnimations();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	vc_map( array(
		'name'          => __('WT Section Headings', 'wt_vcsc'),
		'base'          => 'wt_section_headings',
		'icon'          => 'wt_vc_ico_section_headings',
		'class'         => 'wt_vc_sc_section_headings',
		'wrapper_class' => 'clearfix',		
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Add headings for custom sections', 'wt_vcsc'),
		'params'        => array(			
			array(
				'type'          => 'textarea_html',
				'holder'        => 'div',
				'heading'       => __( 'Section Headings', 'js_composer' ),
				'param_name'    => 'content',
				'value'         => __( '
					<h2>I am H2 text block. Click edit button to change this text.</h2>
					<h3>I am H3 text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</h3>				
				', 'js_composer' )
			),
			
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