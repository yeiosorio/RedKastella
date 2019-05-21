<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_pricing_table extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
				
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'featured_table'  => 'false',
			'icon'            => '',
			'icon_background' => '',
			'icon_color'      => '',
    		'plan'            => 'Basic',
    		'cost'            => '29',
			'per' 	          => 'per month',
    		'button_text'     => 'Button Text',
			'button_link'     => 32,
						
			'el_id'           => '',
			'el_class'        => '',
    		'css_animation'   => '',
    		'anim_type'       => '',
    		'anim_delay'      => '',			
			'css'             => ''		
		), $atts ) );
		
		$sc_class = 'wt_pricing_box_sc';	
					
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}		
		
		$icon_style = '';
				
		// Service Icon Output				
		if ( $icon_background != '' ) {
			$icon_background = 'background: ' . $icon_background . ';';
		}
			
		if ( $icon_color != '' ) {
			$icon_color = 'color: ' . $icon_color . ';';
		}
		
		if ( $icon_background != '' || $icon_color != '' ) {
			$icon_style = ' style="'. $icon_color . $icon_background .'"';
		}
		
		$icon        = esc_html( $icon );
		$plan        = esc_html( $plan );
		$cost        = esc_html( $cost );
		$per         = esc_html( $per );
		$button_text = esc_html( $button_text );
		
		if ($featured_table == 'true') {
			$featured_table = ' wt_pricing_featured';
		} else {
			$featured_table = '';
		}	
		
		if ( $icon != '' ) {
			$icon_out = '<span><i class="'.$icon.'"'.$icon_style.'></i></span>';
			$has_icon = ' wt_price_has_icon'; 
		} else {
			$icon_out = ''; 
			$has_icon = ''; 
		}	
			
		if ( $plan != '' ) {
			$plan_out = '<div class="wt_pricing_header">';	
			$plan_out .= '<h3>'. $plan .'</h3>';									
			if ( $cost != '' || $per != '' ) {
				$cost_and_per = '<div class="wt_plan_price">';
					$cost_and_per .= '<h4>';
						if ( $per != '' ) {
							$per = '<i>'.$per.'</i>';
						}
						$cost_and_per .= $cost . $per;
					$cost_and_per .= '</h4>';
				$cost_and_per .= '</div>';
			} else {
				$cost_and_per = '';
			}
			$plan_out .= $cost_and_per;
			$plan_out .= '</div>';
		} else {
			$plan_out = ''; 
		}
		
		if ( $button_text != '' ) {
			
			// parse button link		
			$button_link = ($button_link=='||') ? '' : $button_link;
			$button_link = vc_build_link($button_link);
			$button_url = esc_url($button_link['url']);
			
			$button_title = $button_link['title'];
			$button_title_out = ($button_title!='') ? ' title="' . esc_attr( $button_title ) .'"' : '';	
			
			$button_target = $button_link['target'];
			$button_target_out = ($button_target!='') ? ' target="' . $button_target .'"' : '';	
			
			if ( $button_url != '' ) {		
				$button_out = '<a class="btn btn-theme" href="'.$button_url.'"' . $button_title_out . $button_target_out .'>'.$button_text.'</a>';
			} else {
				$button_out = '<a class="btn btn-theme" href="#">'.$button_text.'</a>';
			}
		} else {
			$button_out = ''; 
		}
		$sc_class .= $featured_table.$has_icon;				
		$el_class = esc_attr( $this->getExtraClass($el_class) );
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);	
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$content = wpb_js_remove_wpautop($content, true); // fix unclosed/unwanted paragraph tags in $content		
			
		$output = '<div id="'.$el_id.'" class="'.$css_class.'"'.$anim_data.'>';
			if ( $icon_out != '' ) {
				$output .= "\n\t" . '<div class="wt_icon">'; 
					$output .= $icon_out;
				$output .= "\n\t" . '</div>';
			}
			if ( $plan_out != '' ) {
				$output .= "\n\t" . $plan_out;
			}			
			
			$output .= "\n\t" . '<div class="wt_pricing_content">';
				$output .= "\n\t\t" . $content;
			$output .= "\n\t" . '</div>';			
			
			if ( $button_out != '' ) {
				$output .= "\n\t" . '<div class="wt_pricing_btn">'; 
					$output .= "\n\t\t" .  $button_out;
				$output .= "\n\t" . '</div>';
			}	
			
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
		'name'          => __('WT Pricing Table', 'wt_vcsc'),
		'base'          => 'wt_pricing_table',
		'icon'          => 'wt_vc_ico_pricing_table',
		'class'         => 'wt_vc_sc_pricing_table',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Build a pricing table', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'        => 'dropdown',
				'heading'     => __('Featured Table', 'wt_vcsc'),
				'param_name'  => 'featured_table',
				'value'       => array( 
					__('No', 'wt_vcsc')  => 'false',
					__('Yes', 'wt_vcsc') => 'true',
				),
				'description' => __('Select \'Yes\' if you want this table to be featured.', 'wt_vcsc')
			),
			array(
				'type'        => 'textfield',
				'heading'     => __('Icon', 'wt_vcsc'),
				'param_name'  => 'icon',
				'description' => __('<a href="http://fortawesome.github.io/Font-Awesome/icons/" target="_blank">Font Awesome</a>, <a href="http://entypo.com/" target="_blank">Entypo</a> or <a href="http://glyphicons.com/" target="_blank">Glyphicons</a> accepted. (use "fa-", "entypo-" or "glyphicon-" prefix - for example "<strong>fa-adjust, entypo-flag or glyphicon-leaf</strong>"', 'wt_vcsc')
			),
			array(
				'type'          => 'colorpicker',
				'heading'       => __('Icon background', 'wt_vcsc'),
				'param_name'    => 'icon_background',
				'description'   => __( 'Select icon background.', 'wt_vcsc' )
			),
			array(
				'type'          => 'colorpicker',
				'heading'       => __('Icon color', 'wt_vcsc'),
				'param_name'    => 'icon_color',
				'description'   => __( 'Select icon color.', 'wt_vcsc' )
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Plan", "wt_vcsc" ),
				"param_name"  => "plan",
				"admin_label" => true,
				"value"       => "Basic"
			),
			array(
				"type"        => "textfield",
				"heading"     => __( "Cost", "wt_vcsc" ),
				"param_name"  => "cost",
				"admin_label" => true,
				"value"       => "$29"
			),
			array(
				"type"		  => "textfield",
				"heading"     => __( "Per (optional)", "wt_vcsc" ),
				"param_name"  => "per",
				"value"       => "/ month"
			),
			array(
				"type"		  => "textarea_html",
				"heading"     => __( "Features", "wt_vcsc" ),
				"param_name"  => "content",
				"value"       => "<ul>
								      <li>20GB Storage</li>
									  <li>512MB Ram</li>
									  <li>2 Core Processor</li>
									  <li>25GB Bandwidth</li>
									  <li>24/7 Free Support</li>
								  </ul>",
			),
			array(
				"type"		  => "textfield",
				"heading"	  => __( "Button: Text", "wt_vcsc" ),
				"param_name"  => "button_text",
				"value"		  => "Button Text",
				"description" => __( "Type button text here.", "wt_vcsc" )
			),
			array(
				'type'        => 'vc_link',
				'heading'     => __('Button: Link', 'wt_vcsc'),
				'param_name'  => 'button_link',
				'value'       => '',
				'description' => __('Type button link here.', 'wt_vcsc')
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