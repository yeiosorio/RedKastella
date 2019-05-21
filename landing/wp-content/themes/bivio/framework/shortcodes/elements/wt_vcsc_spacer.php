<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_spacer extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'height'        => '',
			'el_id'         => '',
			'el_class'      => '',
			'el_style'      => '',
			
    		'css_animation' => '',
    		'anim_type'     => '',
    		'anim_delay'    => '',		
			'css'           => ''		
		), $atts ) );
		
		$sc_class = 'wt_spacer_sc';
				
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}
		
		$height   = (int)$height;		
		
		$sc_class .= ' wt_clearboth';
		$sc_el_style = 'height:'.$height.'px; line-height:'.$height.'px;';
		
		$el_style = esc_attr($sc_el_style.$el_style);
					
		$el_class = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$el_style = $this->wt_sc->getWTElementStyle($el_style) ;
				
		$output = '<div id="'.$el_id.'" class="'.$css_class.'"'.$el_style.'></div>';
		
        return $output;
    }
	
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/

if (function_exists('vc_map')) {
	
	vc_map( array(
		'name'          => __('WT Spacer / Clear', 'wt_vcsc'),
		'base'          => 'wt_spacer',
		'icon'          => 'wt_vc_ico_spacer',
		'class'         => 'wt_vc_sc_spacer',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Place space / clear element', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'        => 'wt_range',
				'heading'     => __( 'Spacer height', 'wt_vcsc' ),
                "admin_label" => true,
				'param_name'  => 'height',
				'value'       => 10,
				'min'         => 0,
				'max'         => 500,
				'step'        => 1,
				'unit'        => 'px',
				'description' => __( 'Define spacer height in px.', 'wt_vcsc' )
			),
			// Extra Settings
			array(
				'type'		  => 'wt_separator',
				'heading'	  => __( '', 'wt_vcsc' ),
				"param_name"  => 'separator',
				'value'	      => 'Extra Settings',
				'dependency'  => array( 'element' => 'type', 'value' => 'carousel' ),
				'description' => __( 'Below you can add extra settings.', 'wt_vcsc' )
			),
			array(
				'type'               => 'textfield',
				'heading'            => __('Extra Unique ID name', 'wt_vcsc'),
				'param_name'         => 'el_id',
				'param_holder_class' => 'border_box wt_dependency',
				'description'        => __('If you wish to style particular content element differently, then use this field to add a UNIQUE ID name and then refer to it in your css file.', 'wt_vcsc')
			),
			array(
				'type'               => 'textfield',
				'heading'            => __('Extra class name', 'wt_vcsc'),
				'param_name'         => 'el_class',
				'param_holder_class' => 'border_box wt_dependency',
				'description'        => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc')
			),
			array(
				'type'               => 'textarea',
				'heading'            => __('Extra style', 'wt_vcsc'),
				'param_name'         => 'el_style',
				'param_holder_class' => 'border_box wt_dependency',
				'description'        => __('If you wish to use inline styles, then use this field. The style attribute can contain any CSS property. <br>Example: color:sienna;margin-left:20px;', 'wt_vcsc')
			),
			
			// Load Custom CSS/JS File
			array(
				'type'               => 'wt_loadfile',
				'heading'            => __( '', 'wt_vcsc' ),
				'param_name'         => 'el_file',
				'value'              => '',
				'file_path'          => 'wt-visual-composer-extend-element.min.js',
				'param_holder_class' => 'wt_loadfile_field',
				'description'        => __( '', 'wt_vcsc' )
			),
		)
	));
	
}