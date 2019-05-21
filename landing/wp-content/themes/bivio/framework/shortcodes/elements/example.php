<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_tag extends WPBakeryShortCode {
	
    public function jsScripts() {
		wp_register_script( 'customScript', THEME_VC_JS . '/custom.js', array('jquery'), null, true);	
    }
	
    public function __construct($settings) {
        parent::__construct($settings);
        $this->addAction('wp_enqueue_scripts', 'jsScripts');
    }
	
	protected function content($atts, $content = null) {

        $output = $el_class = $css_animation = '';
		
		extract( shortcode_atts( array(
			'foo' => 'something',
			'color' => '#FFF',
			'el_class' => '',
			'css' => ''		
		), $atts ) );
		
		//wp_enqueue_script('customScript');
		
		$el_class = $this->getExtraClass($el_class);		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG,'wt_tag_sc'.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		$css_class .= $this->getCSSAnimation($css_animation);
		
		$content = wpb_js_remove_wpautop($content,true); // fix unclosed/unwanted paragraph tags in $content
		
		$output = '<div class="'.$css_class.'" style="color:'.$color.'" data-foo="'.$foo.'">';
	        $output .= "\n\t\t\t".'<h3>' . $foo . '</h3>';
	        $output .= "\n\t\t\t".$content;
        $output .= '</div>';
		
        return $output;
    }
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/
vc_map( array(
	'name' => __('Tag', 'wt_vcsc'),
	'base' => 'wt_tag',
	'icon' => 'wt_vc_ico_tag',
	'class' => 'wt_vc_sc_tag',
	'category' => __('by WhoaThemes', 'wt_vcsc'),
    'description' => __('HTML tag', 'wt_vcsc'),
	//'admin_enqueue_js' => array(THEME_URI . '/framework/shortcodes/assets/wt_vcsc_admin.js'),
	//'admin_enqueue_css' => array(THEME_URI . '/framework/shortcodes/assets/wt_vcsc_admin.css'),
	'params' => array(
		array(
			'type' => 'textfield',
			'class' => '',
			'heading' => __('Text', 'wt_vcsc'),
			'param_name' => 'foo',
			'value' => __('Default params value', 'wt_vcsc'),
			'description' => __('Description for foo param.', 'wt_vcsc')
		),
		array(
			'type' => 'colorpicker',
			'class' => '',
			'heading' => __('Text color', 'wt_vcsc'),
			'param_name' => 'color',
			'value' => '#FF0000', //Default Red color
			'description' => __('Choose text color', 'wt_vcsc')
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'class' => '',
			'heading' => __('Content', 'wt_vcsc'),
			'param_name' => 'content',
			'value' => __('<p>I am test text block. Click edit button to change this text.</p>', 'wt_vcsc'),
			'description' => __('Enter your content.', 'wt_vcsc')
		),
		array(
			'type' => 'textfield',
			'heading' => __('Extra class name', 'wt_vcsc'),
			'param_name' => 'el_class',
			'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc')
		),
		array(
			'type' => 'css_editor',
			'heading' => __('Css', 'wt_vcsc'),
			'param_name' => 'css',
			// 'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc'),
			'group' => __('Design options', 'wt_vcsc')
		)
	)
));