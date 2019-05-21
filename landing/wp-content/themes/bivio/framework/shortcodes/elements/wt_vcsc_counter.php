<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_counter extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'type'               => 'dark',
			'counter_icon'       => '',
			'counter_number'     => 1,
			'counter_text'       => '',
			'start_in_viewport'  => true,
			
			'el_id'              => '',
			'el_class'           => '',
    		'css_animation'      => '',
    		'anim_type'          => 'wt_animate_if_visible',
    		'anim_delay'         => '',			
			'css'                => ''		
		), $atts ) );
		
		$sc_class = 'wt_counter_sc';
		
		$counter_icon   = esc_html( $counter_icon );
		//$counter_text   = esc_textarea( $counter_text );	
		$counter_number = (int)$counter_number;	
				
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}		
						
		$el_class = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class. ' wt_'.$type.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);
		
		if ($start_in_viewport == true) {
			// Add "wt_animate_if_visible" because counters should start when they appear in viewport
			wp_enqueue_script( 'waypoints' );
			$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,false);
			$start_in_viewport = ' ' . $anim_type;
		} else {
			$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
			$start_in_viewport = '';
		}	
			
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);		
						
		if($counter_icon) {
			$counter_icons = '<i class="fontawesome-icon '.$counter_icon.'"></i>';
		} else {
			$counter_icons = ''; }		
			
		$output = '<div id="'.$el_id.'" class="'.$css_class.$start_in_viewport.'"'.$anim_data.' data-percent="'.$counter_number.'">';
		$output .= $counter_icons;
		$output .= '<span class="stat-count"></span>';
		$output .= '<h3 class="wt_stat_detail">'.$counter_text.' </h3>';
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
		'name'          => __('WT Counter', 'wt_vcsc'),
		'base'          => 'wt_counter',
		'icon'          => 'wt_vc_ico_counter',
		'class'         => 'wt_vc_sc_counter',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Place icon counter', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'dropdown',
				'heading'       => __('Type', 'wt_vcsc'),
				'param_name'    => 'type',
				'value'         => array(__('Light', 'wt_vcsc') => 'light', __('Dark', 'wt_vcsc') => 'dark' ),
				'std'           => 'dark',
				'description'   => __('Select the counter style that you need.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Counter icons', 'wt_vcsc'),
				'param_name'    => 'counter_icon',
				'description'   => __('<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>, <a href="http://entypo.com/" target="_blank">Entypo</a> or <a href="http://glyphicons.com/" target="_blank">Glyphicons</a> accepted. (use "fa-", "entypo-" or "glyphicon-" prefix - for example "<strong>fa-adjust, entypo-flag or glyphicon-leaf</strong>"', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Counter number', 'wt_vcsc'),
				'param_name'    => 'counter_number',
				'value'         => '1',
				'description'   => __('Add a number for your counter.', 'wt_vcsc')
			),
			array(
				'type'          => 'textarea',
				'heading'       => __('Counter text', 'wt_vcsc'),
				'admin_label'   => true,
				'param_name'    => 'counter_text',
				'description'   => __('Add text for your counter.', 'wt_vcsc')
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