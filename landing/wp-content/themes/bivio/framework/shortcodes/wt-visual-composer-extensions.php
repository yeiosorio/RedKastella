<?php

if (!defined('ABSPATH')) exit;

// Check for Visual Composer
// -------------------------
if (!defined('__VC_EXTENSIONS__')){
	define('__VC_EXTENSIONS__', dirname(__FILE__));
}

// Main Class for Visual Composer Extensions
// -----------------------------------------

if (!class_exists('WT_VC_EXTENSIONS')) {
	
	// Create Plugin Class
	// -------------------
	class WT_VC_EXTENSIONS {
		
		public $WT_VCSC_Elements = array(
			"WT Blog Grid"				            => array("setting" => "BlogGrid",                 "file" => "blog_grid",     	       "type" => "internal",		"active" => "true"),
			"WT Portfolio"				            => array("setting" => "Portfolio",                "file" => "portfolio",     	       "type" => "internal",		"active" => "true"),
			"WT Contact Form"					    => array("setting" => "ContactForm",              "file" => "contact_form",     	    "type" => "internal",		"active" => "true"),
			"WT Social Networks"					=> array("setting" => "SocialNetworks",           "file" => "social_networks",     	    "type" => "internal",		"active" => "true"),
			"WT Bx Rotator"					        => array("setting" => "BxRotator",                "file" => "bx_rotator",     	       "type" => "internal",		"active" => "true"),
			"WT Services Slider"					=> array("setting" => "ServicesSlider",           "file" => "services_slider",     	    "type" => "internal",		"active" => "true"),
			"WT Testimonials Slider"				=> array("setting" => "TestimonialsSlider",       "file" => "testimonials_slider",     	"type" => "internal",		"active" => "true"),
			"WT Clients"					        => array("setting" => "Clients",                  "file" => "clients",     	
"type" => "internal",		"active" => "true"),
			"WT Team"					            => array("setting" => "Team",                     "file" => "team",     	            "type" => "internal",		"active" => "true"),
			"WT Testimonial"					    => array("setting" => "Testimonial",              "file" => "testimonial",     	            "type" => "internal",		"active" => "true"),
			"WT Service Box"					    => array("setting" => "ServiceBox",               "file" => "service_box",      	    "type" => "internal",		"active" => "true"),
			"WT Services"					        => array("setting" => "Services",                 "file" => "services",         	    "type" => "internal",		"active" => "true"),
			"WT Counter"					        => array("setting" => "Counter",                  "file" => "counter",     	            "type" => "internal",		"active" => "true"),
			"WT Tag"					            => array("setting" => "Tag",                      "file" => "tag",     	                "type" => "internal",		"active" => "true"),
			"WT Spacer"					            => array("setting" => "Spacer",                   "file" => "spacer",     	                "type" => "internal",		"active" => "true"),
			"WT Pricing Table"					    => array("setting" => "PricingTable",             "file" => "pricing_table",     	
"type" => "internal",		"active" => "true"),
			"WT Custom Heading"					    => array("setting" => "CustomHeading",            "file" => "custom_heading",     	
"type" => "internal",		"active" => "true"),
			"WT Section Headings"				    => array("setting" => "SectionHeadings",          "file" => "section_headings",     	
"type" => "internal",		"active" => "true"),
			"WT Galler Grid"				        => array("setting" => "GalleryGrid",              "file" => "gallery_grid",     	
"type" => "internal",		"active" => "true"),
			"WT Google Map"				            => array("setting" => "GoogleMap",                "file" => "gmap",     	
"type" => "internal",		"active" => "true"),
		);

		function __construct() {
			
			/*
			$this->assets_dir 		= plugin_dir_path( __FILE__ ).'assets/';
			$this->assets_js 		= plugin_dir_path( __FILE__ ).'assets/lib/js/';
			$this->assets_css 		= plugin_dir_path( __FILE__ ).'assets/lib/css/';
			$this->images_dir 		= plugin_dir_path( __FILE__ ).'assets/lib/images/';			
			$this->elements_dir 	= plugin_dir_path( __FILE__ ).'elements/';
			$this->shortcode_dir 	= plugin_dir_path( __FILE__ ).'shortcodes/';
			$this->includes_dir 	= plugin_dir_path( __FILE__ ).'includes/';
			*/
			
			// Adding WhoaThemes classes to standard VC shortcodes
			// --------------------------------------------
			add_filter('vc_shortcodes_css_class',       array($this, 'WT_VCSC_Extensions_Css_Classes'), 10, 2);			
			
			// Load External Files on Back-End
			// -------------------------------------------
			add_action('admin_enqueue_scripts', 		array($this, 	'WT_VCSC_Extensions_Admin'));
			
			// Load External Files on Front-End
			// --------------------------------------------
			add_action('wp_enqueue_scripts', 			array($this, 	'WT_VCSC_Extensions_Front'), 		999999999999999999999999999);
										
			// // Load Composer Elements ( Maps + Shortcodes Output )
			// --------------------------------------------
			foreach ($this->WT_VCSC_Elements as $ElementName => $element) {
				// if not WhoaThemes
				//if () {
					if ($element['active'] == "true") {
						if ($element['type'] == 'internal') {
							//require_once($this->shortcode_dir.'/wt_vcsc_' . $element['file'] . '.php');
							require_once(THEME_VC_EXTEND_ELEMENTS . '/wt_vcsc_' . $element['file'] . '.php');
						}
					}
				/*} else {
					$this->WT_VCSC_Elements[$ElementName]['active'] = "false";
				}*/			
			}
																			
			// Load Extended Composer Elements
			// --------------------------------------------
			add_action('init', 							array($this, 'WT_VCSC_RegisterWithComposer'), 999999999);
			
			// Load Helper Functions
			// --------------------------------------------
			add_action('init', 							array($this, 'WT_VCSC_HelperFunctions'));
			
		}
				
		// ! Generate param type "wt_seperator"
		// --------------------------------------------		
		function wt_separator_settings_field($settings, $value) {
			$dependency  = vc_generate_dependencies_attributes($settings);
			$param_name  = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type        = isset($settings['type']) ? $settings['type'].'_field' : '';	
			
			$output      = '';			
			$output .= '<div class="wpb_vc_param_value ' . $param_name . ' ' . $type . '" name="' . $param_name . '" style="border-bottom: 2px solid #DEDEDE; margin-bottom: 10px; margin-top: 10px; padding-bottom: 10px; font-size: 18px; color: #BEBEBE;" ' . $dependency . '>' . $value . '</div>';	
			return $output;
		}
				 
		// ! Generate param type "wt_multidropdown"
		// --------------------------------------------			 
		function wt_multidropdown_settings_field($settings, $value) {	
			$css_option = vc_get_dropdown_option($settings, $value);	
			$dependency = vc_generate_dependencies_attributes($settings);	
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type       = isset($settings['type']) ? $settings['type'].'_field' : '';
			
			$current_value = explode(",", $value);
			
			// if exists $settings['target'] then auto fill the options for multidropdown related to target value
			isset($settings['target']) ? $target = 'true' : $target = 'false';
			
			if ( $target == 'false' ) {
				$values = is_array($settings['value']) ? $settings['value'] : array();
			} else {
				$values = WT_VCSC_GetSelectTargetOptions($settings['target']);
			}
			
			$output = '';			
			$output .= '<select name="'.$settings['param_name'].'" class="wpb_vc_param_value wpb-input wpb-select ' . $param_name . ' ' . $type . '" data-option="'.$css_option.'"  multiple ' . $dependency . '>';
			
				foreach ( $values as $text_val => $val ) {
					
					if ( is_numeric($text_val) && (is_string($val) || is_numeric($val)) ) {
						$text_val = $val;
					}
					
					$text_val = htmlspecialchars( __($text_val, "wt_vcsc") );					
					$selected = in_array($val, $current_value) ? ' selected="selected"' : '';
					
					$output .= '<option value="'.$val.'"'.$selected.'>'.$text_val.'</option>';
				}
			
			$output .= '</select>';				
			return $output; 
		}
		
		// ! Generate param type "wt_range"
		// --------------------------------------------	
		function wt_range_settings_field($settings, $value) {
			$dependency  = vc_generate_dependencies_attributes($settings);
			$param_name  = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type        = isset($settings['type']) ? $settings['type'].'_field' : '';
			
			$min         = isset($settings['min']) ? $settings['min'] : '';
			$max         = isset($settings['max']) ? $settings['max'] : '';
			$step        = isset($settings['step']) ? $settings['step'] : '';
			$unit        = isset($settings['unit']) ? $settings['unit'] : '';
			
			$unit_margin = $unit != '' ? '20px' : '10px';
			
			$output      = '';			
			$output     .= '<div class="wt_range_block clearfix">';
			$output 	.= '<input style="width: 60px; float: left; margin-left: 0; margin-right: 5px; text-align: center; padding: 6px;" name="' . $param_name . '"  class="wt-range-serial nouislider-input-selector nouislider-input-composer wpb_vc_param_value ' . $param_name . ' ' . $type . '" type="text" value="' . $value . '"/>';
			$output     .= '<span style="float: left; margin-right: '.$unit_margin.'; margin-top: 8px;" class="unit">' . $unit . '</span>';
			$output 	.= '<div class="wt-range-input-element" data-value="' . $value . '" data-min="' . $min . '" data-max="' . $max . '" data-step="' . $step . '" style="width: 200px; float: left; margin-top: 8px;"></div>';
			$output 	.= '</div>';			
			return $output;
		}		
		
		// ! Generate param type "wt_loadfile"
		// --------------------------------------------	
		function wt_loadfile_settings_field($settings, $value){
			$dependency = vc_generate_dependencies_attributes($settings);
			$param_name = isset($settings['param_name']) ? $settings['param_name'] : '';
			$type       = isset($settings['type']) ? $settings['type'].'_field' : '';
			
			$file_path  = isset($settings['file_path']) ? $settings['file_path'] : '';
			//$url        = plugin_dir_url( __FILE__ );
			$url        = THEME_VC_ASSETS;
			
			$output     = '';
			if (!empty($file_path)) {
				$output .= '<script type="text/javascript" src="' . $url.$file_path . '"></script>';
			}
			return $output;
		}
				
		// ! Load Composer Elements + Add Custom Parameters
		// --------------------------------------------	
		function WT_VCSC_RegisterWithComposer() {
			if (function_exists('vc_is_inline')){
				if ((vc_is_inline()) || (is_admin())) {
					$this->WT_VCSC_AddParametersToComposer();
					$this->WT_VCSC_AddElementsToComposer();
				} else {
					$this->WT_VCSC_LoadClassElements();
				}
			} else if (is_admin()) {
				$this->WT_VCSC_AddParametersToComposer();
				$this->WT_VCSC_AddElementsToComposer();
			} else {
				$this->WT_VCSC_LoadClassElements();
			}
		}
		
		// ! Add Extended Custom Parameters
		// --------------------------------------------	
		function WT_VCSC_AddParametersToComposer() {
			if (function_exists('vc_add_shortcode_param')) {
									
				// Generate param type "wt_separator"
				vc_add_shortcode_param('wt_separator',        	array($this, 'wt_separator_settings_field'));
				
				// Generate param type "wt_multidropdown"
				vc_add_shortcode_param('wt_multidropdown',     array($this, 'wt_multidropdown_settings_field'));
				
				// Generate param type "wt_range"
				vc_add_shortcode_param('wt_range',             array($this, 'wt_range_settings_field'));
								
				// Generate param type "wt_loadfile"
				vc_add_shortcode_param('wt_loadfile',          array($this, 'wt_loadfile_settings_field'));
				
				// Generate param type "wt_markers"
				vc_add_shortcode_param('wt_gmap_markers',      array($this, 'wt_markers_settings_field'));
			}
		}
		
		
		// ! Generate param type "wt_markers"
		// --------------------------------------------	
		function wt_markers_settings_field($settings, $value) {
			$dependency = vc_generate_dependencies_attributes($settings);
			?>
			<script type="text/javascript">
				jQuery(document).ready(function( $ ) {
		
					// recreate from settings - textarea content
					var textarea_val = $('#vc_ui-panel-edit-element .marker_data').html();
					if(textarea_val != '') {
						// check if value is a valid json
						if (/^[\],:{}\s]*$/.test(textarea_val.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
							var obj = $.parseJSON(textarea_val);
							// loop trough the object
							Object.keys(obj).forEach(function(key) {
								var obj_data = {
									'm_address'  : obj[key].m_address,
									'm_title'    : obj[key].m_title,
									'm_desc'     : obj[key].m_desc,
									'm_icon_id'  : obj[key].m_icon_id,
									'm_icon_url' : obj[key].m_icon_url
								},
								marker = wt_marker_item_panel(obj_data);
								$('.wt_markers_wrapper').append(marker);
							});
						}
					}
				});
			</script>
			<?php
			return  '<div class="wt_markers_wrapper"></div>'
					.'<a href="#" class="new_marker">Add marker</a>';
		}
		
		// ! Load Extended Composer Elements
		// --------------------------------------------
		function WT_VCSC_AddElementsToComposer() {			
			
			$extend_WT_VCSC_Rows = true;
			$extend_WT_VCSC_Cols = true;
			
			// Load Extended Row Settings
			if ( $extend_WT_VCSC_Rows ) {
				//require_once($this->shortcode_dir . '/wt_vcsc_extend_row.php');
				require_once(THEME_VC_EXTEND_ELEMENTS . '/wt_vcsc_extend_row.php');
			}
			// Load Extended Column Settings
			if ( $extend_WT_VCSC_Cols ) {
				//require_once($this->shortcode_dir . '/wt_vcsc_extend_column.php');
				require_once(THEME_VC_EXTEND_ELEMENTS . '/wt_vcsc_extend_column.php');
			}
			// Load Extended Params for Specific VC Shortcode
			if ( $extend_WT_VCSC_Cols ) {
				//require_once($this->shortcode_dir . '/wt_vcsc_extend_params.php');
				require_once(THEME_VC_EXTEND_ELEMENTS . '/wt_vcsc_extend_params.php');
			}
		}
		
		function WT_VCSC_LoadClassElements() {			
			// Load Custom Post Types
		}
		
		// ! Load extended plugin Back-End css and javascript files when Editing
		// --------------------------------------------			
		function WT_VCSC_Extensions_Admin() {
			global $pagenow, $typenow;
			$screen = get_current_screen();
			if (empty($typenow) && !empty($_GET['post'])) {
				$post 		= get_post($_GET['post']);
				$typenow 	= $post->post_type;
			}
			$url = plugin_dir_url( __FILE__ );
			
			if (WT_VCSC_IsEditPagePost()) {
				//wp_enqueue_style( 'wt-visual-composer-extensions-admin', $url.'assets/wt-visual-composer-extensions-admin.css', null, false, 'all' );
				wp_enqueue_style( 'wt-visual-composer-extensions-admin', THEME_URI . '/framework/shortcodes/assets/wt-visual-composer-extensions-admin.css', null, false, 'all' );
			  
				// If you need any javascript files on back end, here is how you can load them.				
				
				//wp_enqueue_style( 'wt-extend-nouislider',                            $url . 'assets/lib/css/admin/jquery.nouislider.css', null, false, 'all' );
				wp_enqueue_style( 'wt-extend-nouislider',                            THEME_VC_CSS . '/admin/jquery.nouislider.css',                null, false, 'all' );
				//wp_register_script( 'wt-extend-nouislider',                          $url . 'assets/lib/js/admin/jquery.nouislider.min.js', array('jquery'), null, true );
				wp_enqueue_script( 'wt-extend-nouislider',                           THEME_VC_JS . '/admin/jquery.nouislider.min.js',             array('jquery'), null, true );	
				
				/* Google Map Admin Scripts
				------------------------------------------- */
				wp_enqueue_media();
				wp_enqueue_script('wt-extend-gmap-api',                              'https://maps.google.com/maps/api/js?sensor=false');
				
				//wp_register_script( 'wt-extend-gmap',                                $url . 'assets/lib/js/admin/wt-gmap-settings.js', array('jquery'), null, true );
				wp_enqueue_script( 'wt-extend-gmap',                                 THEME_VC_JS . '/admin/wt-gmap-settings.js',              array('jquery'), null, true );
				/* End Google Map Admin Scripts
				------------------------------------------- */
				
				// wp_enqueue_script( 'wt-visual-composer-extensions-admin', THEME_URI . '/framework/shortcodes/assets/wt-visual-composer-extensions-admin.js', array('jquery'), false, true );
			}
		}
		
		// ! Load extended plugin Front-End css and javascript files
		// --------------------------------------------		
		function WT_VCSC_Extensions_Front() {
			global $post;
			$url = plugin_dir_url( __FILE__ );
			
			if (!empty($post)){
				
				//wp_enqueue_style( 'wt-visual-composer-extensions-front',             $url . 'assets/wt-visual-composer-extensions-front.css', null, false, 'all' );
				
				//wp_register_script( 'wt-extend-waypoints',                           $url . 'assets/lib/js/jquery.waypoint.js',               array('jquery'), null, true );
				wp_register_script( 'wt-extend-waypoints',                           THEME_VC_JS . '/jquery.waypoint.js',                     array('jquery'), null, true );
				
				//wp_enqueue_style( 'wt-extend-bx-slider',                             $url . 'assets/lib/css/jquery.bxslider.css',             null, false, 'all' );
				wp_enqueue_style( 'wt-extend-bx-slider',                             THEME_VC_CSS . '/jquery.bxslider.css',                    null, false, 'all' );
				//wp_register_script( 'wt-extend-bx-slider',                           $url . 'assets/lib/js/jquery.bxslider.js',               array('jquery'), null, true );
				wp_register_script( 'wt-extend-bx-slider',                           THEME_VC_JS . '/jquery.bxslider.js',                    array('jquery'), null, true );
				
				//wp_register_script( 'wt-extend-youtube-player',                      $url . 'assets/lib/js/jquery.mb.YTPlayer.js',  array('jquery'), null, true );
				wp_register_script( 'wt-extend-youtube-player',                       THEME_VC_JS . '/jquery.mb.YTPlayer.js',               array('jquery'), null, true );		
				
				wp_register_script( 'wt-extend-gmap-api',                            'https://maps.google.com/maps/api/js?sensor=false',   false, false, false );
				
				//wp_register_script( 'wt-extend-gmap-markerclusterer',                $url . 'assets/lib/js/markerclusterer.js',       array('jquery'), null, true );
				wp_register_script( 'wt-extend-gmap-markerclusterer',                THEME_VC_JS . '/markerclusterer.js',                  array('jquery'), null, true );
				
				//wp_register_script( 'wt-extend-gmap',                                $url . 'assets/lib/js/wt-gmap.js',       array('jquery'), null, true );
				wp_register_script( 'wt-extend-gmap',                                THEME_VC_JS . '/wt-gmap.js',                          array('jquery'), null, true );	
				
				/*
				$handle = 'wt-validate';
				$list   = 'registered';				
				if (wp_script_is( $handle, $list )) { // check if script was previouselly registered in theme files
					return;
				} else {
					//wp_register_script( 'wt-validate',                   $url . 'assets/lib/js/jquery.validate.js', array('jquery'), null, true );				
					wp_register_script( 'wt-validate',                     THEME_VC_JS . '/jquery.validate.js', array('jquery'), null, true );
				}
				*/								
				
				//wp_enqueue_script( 'wt-visual-composer-extensions-front',            $url . 'assets/wt-visual-composer-extensions-front.js', array('jquery'), null, true );
					
			}
		}
		
		// ! Adding WhoaThemes classes to standard VC shortcodes
		// --------------------------------------------		
		function WT_VCSC_Extensions_Css_Classes($wt_class, $tag) {
			if ( in_array( $tag, array('vc_accordion', 'vc_toggle', 'vc_pie', 'TS-VCSC-Pricing-Table', 'TS-VCSC-Countdown') ) ) {
				$wt_class .= ' wt_vcsc_style';
			}
			return $wt_class;
		}		
				
		// ! Load Helper Functions
		// --------------------------------------------	
		function WT_VCSC_HelperFunctions() {			
			//require_once($this->includes_dir.'/wt_vcsc_functions.php');
			require_once(THEME_VC_EXTEND_INCLUDES . '/wt_vcsc_functions.php');
			
			//require_once($this->includes_dir.'/aq-resizer.php');
			require_once(THEME_VC_EXTEND_INCLUDES . '/aq-resizer.php');
		}
		
	}
}

if (class_exists('WT_VC_EXTENSIONS')) {
	$WT_VC_EXTENSIONS = new WT_VC_EXTENSIONS;
}

// ! Add WT_VCSC_SHORTCODE Class
// --------------------------------------------	
class WT_VCSC_SHORTCODE {
			
    public function getWTExtraId() {
		$output = '';
		$output = array(
			'type'        => 'textfield',
			'heading'     => __('Extra Unique ID name', 'wt_vcsc'),
			'param_name'  => 'el_id',
			'description' => __('If you wish to style particular content element differently, then use this field to add a UNIQUE ID name and then refer to it in your css file.', 'wt_vcsc'),
			'group'       => __('Extra settings', 'wt_vcsc')
		);		
		return $output;
	}	
	
    public function getWTExtraClass() {
		$output = '';
		$output = array(
			'type'        => 'textfield',
			'heading'     => __('Extra class name', 'wt_vcsc'),
			'param_name'  => 'el_class',
			'description' => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'wt_vcsc'),
			'group'       => __('Extra settings', 'wt_vcsc')
		);		
		return $output;
	}
	
    public function getWTAnimations() {
		$output = '';
		$output = array(
			"type"        => "dropdown",
			"heading"     => __("CSS WT Animation", "wt_vcsc"),
			"param_name"  => "css_animation",
			"value" => array(__("No", "wt_vcsc") => '', __("Hinge", "wt_vcsc") => "hinge", __("Flash", "wt_vcsc") => "flash", __("Shake", "wt_vcsc") => "shake", __("Bounce", "wt_vcsc") => "bounce", __("Tada", "wt_vcsc") => "tada", __("Swing", "wt_vcsc") => "swing", __("Wobble", "wt_vcsc") => "wobble", __("Pulse", "wt_vcsc") => "pulse", __("Flip", "wt_vcsc") => "flip", __("FlipInX", "wt_vcsc") => "flipInX", __("FlipOutX", "wt_vcsc") => "flipOutX", __("FlipInY", "wt_vcsc") => "flipInY", __("FlipOutY", "wt_vcsc") => "flipOutY", __("FadeIn", "wt_vcsc") => "fadeIn", __("FadeInUp", "wt_vcsc") => "fadeInUp", __("FadeInDown", "wt_vcsc") => "fadeInDown", __("FadeInLeft", "wt_vcsc") => "fadeInLeft", __("FadeInRight", "wt_vcsc") => "fadeInRight", __("FadeInUpBig", "wt_vcsc") => "fadeInUpBig", __("FadeInDownBig", "wt_vcsc") => "fadeInDownBig", __("FadeInLeftBig", "wt_vcsc") => "fadeInLeftBig", __("FadeInRightBig", "wt_vcsc") => "fadeInRightBig", __("FadeOut", "wt_vcsc") => "fadeOut", __("FadeOutUp", "wt_vcsc") => "fadeOutUp", __("FadeOutDown", "wt_vcsc") => "fadeOutDown", __("FadeOutLeft", "wt_vcsc") => "fadeOutLeft", __("FadeOutRight", "wt_vcsc") => "fadeOutRight", __("fadeOutUpBig", "wt_vcsc") => "fadeOutUpBig", __("FadeOutDownBig", "wt_vcsc") => "fadeOutDownBig", __("FadeOutLeftBig", "wt_vcsc") => "fadeOutLeftBig", __("FadeOutRightBig", "wt_vcsc") => "fadeOutRightBig", __("BounceIn", "wt_vcsc") => "bounceIn", __("BounceInUp", "wt_vcsc") => "bounceInUp", __("BounceInDown", "wt_vcsc") => "bounceInDown", __("BounceInLeft", "wt_vcsc") => "bounceInLeft", __("BounceInRight", "wt_vcsc") => "bounceInRight", __("BounceOut", "wt_vcsc") => "bounceOut", __("BounceOutUp", "wt_vcsc") => "bounceOutUp", __("BounceOutDown", "wt_vcsc") => "bounceOutDown", __("BounceOutLeft", "wt_vcsc") => "bounceOutLeft", __("BounceOutRight", "wt_vcsc") => "bounceOutRight", __("RotateIn", "wt_vcsc") => "rotateIn", __("RotateInUpLeft", "wt_vcsc") => "rotateInUpLeft", __("RotateInDownLeft", "wt_vcsc") => "rotateInDownLeft", __("RotateInUpRight", "wt_vcsc") => "rotateInUpRight", __("RotateInDownRight", "wt_vcsc") => "rotateInDownRight", __("RotateOut", "wt_vcsc") => "rotateOut", __("RotateOutUpLeft", "wt_vcsc") => "rotateOutUpLeft", __("RotateOutDownLeft", "wt_vcsc") => "rotateOutDownLeft", __("RotateOutUpRight", "wt_vcsc") => "rotateOutUpRight", __("RotateOutDownRight", "wt_vcsc") => "rotateOutDownRight", __("RollIn", "wt_vcsc") => "rollIn", __("RollOut", "wt_vcsc") => "rollOut", __("LightSpeedIn", "wt_vcsc") => "lightSpeedIn", __("LightSpeedOut", "wt_vcsc") => "lightSpeedOut" ),
			"description" => __("Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.", "wt_vcsc"),
			'group'       => __('Extra settings', 'wt_vcsc')
		);		
		return $output;
	}
	
    public function getWTAnimationsType() {
		$output = '';
		$output = array(
			"type"        => "dropdown",
			"heading"     => __("WT Animation Visible Type", "wt_vcsc"),
			"param_name"  => "anim_type",
			"value"       => array(__("Animate when element is visible", "wt_vcsc") => 'wt_animate_if_visible', __("Animate if element is almost visible", "wt_vcsc") => "wt_animate_if_almost_visible" ),
			"description" => __("Select when the type of animation should start for this element.", "wt_vcsc"),
			'group'       => __('Extra settings', 'wt_vcsc')
		);		
		return $output;
	}
	
    public function getWTAnimationsDelay() {
		$output = '';
		$output = array(
			"type"        => "textfield",
			"heading"     => __("WT Animation Delay", "wt_vcsc"),
			"param_name"  => "anim_delay",
			"description" => __("Here you can set a specific delay for the animation (miliseconds). Example: '100', '500', '1000'.", "wt_vcsc"),
			'group'       => __('Extra settings', 'wt_vcsc')
		);		
		return $output;
	}

	public function getWTCSSAnimationClass($css_animation,$anim_type) {
		$output = '';
		if ( $css_animation != '' ) {
			wp_enqueue_script( 'waypoints' ); // VC file
			//wp_enqueue_script( 'wt-extend-waypoints' );
			if ($anim_type == false) {
				$output = ' wt_animate' ;
			} else {
				$output = ' wt_animate ' . $anim_type ;
			}
		}
		return $output;
	}
	
	public function getWTCSSAnimationData($css_animation,$anim_delay) {
		$output = '';
		if ( $css_animation != '' ) {
			$output = ' data-animation="'.$css_animation.'"';
			if ( $anim_delay != '' ) {
				$output .= ' data-animation-delay="'.absint( $anim_delay ).'"';
			}
		}
		return $output;
	}
	
	public function getWTElementStyle($el_style) {
		$output = '';
		if ( trim($el_style) != '' ) {
			$output = ' style="'.$el_style.'"';
		}
		return $output;
	}
	
}

/*** WhoaThemes Visual Composer Content elements refresh ***/
class WT_VCSC_SharedLibrary {
	// Here we will store plugin wise (shared) settings. sizes, sizes etc...

	public static $sizes = array(
		'Mini'   => 'xs',
		'Small'  => 'sm',
		'Normal' => 'md',
		'Large'  => 'lg'
	);
	
	public static $text_align = array(
		'Left'    => 'left',
		'Right'   => 'right',
		'Center'  => 'center',
		'Justify' => 'justify'
	);
	
	public static $sep_styles = array(
		'Border' => '',
		'Dashed' => 'dashed',
		'Dotted' => 'dotted',
		'Double' => 'double'
	);
	
	public static $box_styles = array(
		'Default'                => '',
		'Rounded'                => 'vc_box_rounded',
		'Border'                 => 'vc_box_border',
		'Outline'                => 'vc_box_outline',
		'Shadow'                 => 'vc_box_shadow',
		'Bordered shadow'        => 'vc_box_shadow_border',
		'3D Shadow'              => 'vc_box_shadow_3d',
		'Circle'                 => 'vc_box_circle', //new
		'Circle Border'          => 'vc_box_border_circle', //new
		'Circle Outline'         => 'vc_box_outline_circle', //new
		'Circle Shadow'          => 'vc_box_shadow_circle', //new
		'Circle Border Shadow'   => 'vc_box_shadow_border_circle' //new
	);
	
	public static function getSizes() {
		return self::$sizes;
	}
	
	public static function getTextAlign() {
		return self::$text_align;
	}
	
	public static function getSeparatorStyles() {
		return self::$sep_styles;
	}

	public static function getBoxStyles() {
		return self::$box_styles;
	}
}

function WT_VCSC_getShared( $asset = '' ) {
	switch ( $asset ) {
		
		case 'sizes':
			return WT_VCSC_SharedLibrary::getSizes();
			break;
		
		case 'text align':
			return WT_VCSC_SharedLibrary::getTextAlign();
			break;
		
		case 'separator styles':
			return WT_VCSC_SharedLibrary::getSeparatorStyles();
			break;

		case 'single image styles':
			return WT_VCSC_SharedLibrary::getBoxStyles();
			break;

		default:
			# code...
			break;
			
	}
}

?>