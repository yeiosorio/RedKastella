<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_social_networks extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
				
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'icon_type' 	    => 'wt_icon_type_1',
			'icon_style' 	    => 'simple',
			'icon_background'   => '',
			'icon_color'        => '',
    		'icon_align'        => 'left',
			'icon_margin' 	    => '5',
			'icon_size'         => 32,
			'tooltip'	        => false,
			'tooltip_placement' => '',		
			'social_networks'   => '',
			
			'website_link'     => '',
			'email_link'       => '',
			'facebook_link'    => '',
			'twitter_link'     => '',
			'pinterest_link'   => '',
			'linkedin_link'    => '',
			'google_link'      => '',
			'dribbble_link'    => '',
			'youtube_link'     => '',
			'vimeo_link'       => '',
			'rss_link'         => '',
			'github_link'      => '',
			'delicious_link'   => '',
			'flickr_link'      => '',
			//'forrst_link'      => '',
			'lastfm_link'      => '',
			'tumblr_link'      => '',
			'deviantart_link'  => '',
			'skype_link'       => '',
			'instagram_link'   => '',
			'stumbleupon_link' => '',
			'behance_link'     => '',
			'soundcloud_link'  => '',
			
			'el_id'            => '',
			'el_class'         => '',
    		'css_animation'    => '',
    		'anim_type'        => '',
    		'anim_delay'       => '',			
			'css'              => ''		
		), $atts ) );
		
		$sc_class = 'wt_social_networks_sc';	
					
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = esc_attr( trim($el_id) );
		} else {
			$el_id = $sc_class . '-' . $id;
		}		
		
		$icon_margin = (int)$icon_margin;	
				
		if ( $icon_margin != '' ) {
			$icon_margin = ' style="margin: ' . $icon_margin . 'px;"';
		}
		
		$el_style = '';	
		
		if ( $icon_color != '' ) {
			$icon_color = 'color: ' . $icon_color . ';';
		}
						
		if ((empty($icon_background)) || ($icon_style == 'simple')) {
			$icon_background = '';
		} else {
			$icon_background = 'background: ' . $icon_background . ';';
		}
			
		if ( $icon_background != '' || $icon_color != '' ) {
			$el_style = ' style="'. $icon_color . $icon_background .'"';
		}
				
		$el_class = esc_attr( $this->getExtraClass($el_class) );
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);
		$css_class .= ' wt_align_'.$icon_align;		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
								
		if($social_networks != ''){
			 
			$soc_output = '';	
			$icon_name  = '';
			$social_networks = array_map( 'trim', explode( ',', $social_networks ) );	
					
			if(is_array($social_networks) && !empty($social_networks)){
				$soc_output .= "\n\t\t\t" . '<ul class="wt_icon_'.$icon_size.' ' . $icon_type . ' ' . $icon_style . '">'; 
				
					foreach ( $social_networks as $index=>$icon ) {
						$icon_link = $icon.'_link';
						$icon_name = $icon;
						
						switch( $icon ) {
							case 'website'     : $icon_output = '<i class="entypo-link"></i>';      break;
							case 'email'       : $icon_output = '<i class="fa-envelope"></i>';      break;
							case 'facebook'    : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'twitter'     : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'pinterest'   : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'linkedin'    : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'google'      : $icon_output = '<i class="entypo-gplus"></i>';     break;
							case 'dribbble'    : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'youtube'     : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'vimeo'       : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'rss'         : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'github'      : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'delicious'   : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'flickr'      : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							//case 'forrst'      : $icon_output = '<i class="fa-'.$icon.'"></i>'; break;
							case 'lastfm'      : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'tumblr'      : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'deviantart'  : $icon_output = '<i class="fa-'.$icon.'"></i>';     break;
							case 'skype'       : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'instagram'   : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'stumbleupon' : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'behance'     : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
							case 'soundcloud'  : $icon_output = '<i class="entypo-'.$icon.'"></i>'; break;
						}
						
						if ($tooltip == false) {

							$tooltip = '';
						
						} else {
						
							$tooltip_placement == '' ? $tooltip_placement = 'top' : '';
							$tooltip = ' data-toggle="tooltip" data-placement="'.$tooltip_placement.'"';
						
						}
						
						$soc_output .= "\n\t\t\t\t" . '<li' . $icon_margin . '>';
							

							if ($$icon_link == false) { // if there is not set the social link, put '#' as a placeholder

								if($icon == 'facebook'){

									$soc_output .= "\n\t\t\t\t\t" . '<a'.$el_style.' href="http://www.facebook.com/KastellaContratos" class="'.$icon_name.'" title="'.$icon.'" rel="nofollow" target="_blank"'.$tooltip.'>'.$icon_output.'</a>'; 				

								}else if($icon == 'twitter'){

									$soc_output .= "\n\t\t\t\t\t" . '<a'.$el_style.' href="http://www.twitter.com/KastellaApp" class="'.$icon_name.'" title="'.$icon.'" rel="nofollow" target="_blank"'.$tooltip.'>'.$icon_output.'</a>'; 
								}else{

									$soc_output .= "\n\t\t\t\t\t" . '<a'.$el_style.' href="#" class="'.$icon_name.'" title="'.$icon.'" rel="nofollow" target="_blank"'.$tooltip.'>'.$icon_output.'</a>';

								}

							} else {

									$soc_output .= "\n\t\t\t\t\t" . '<a'.$el_style.' href="'.esc_url( $$icon_link ).'" class="'.$icon_name.'" title="'.$icon.'" rel="nofollow" target="_blank"'.$tooltip.'>'.$icon_output.'</a>';
									 
							}
						$soc_output .= "\n\t\t\t\t" . '</li>';												
					}
				
				$soc_output .= "\n\t\t\t" . '</ul>'; 
			}
		}
		
		$output = '<div id="'.$el_id.'" class="'.$css_class.'"'.$anim_data.'>';
	        $output .= "\n\t" . $soc_output;
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
		'name'          => __('WT Social Networks', 'wt_vcsc'),
		'base'          => 'wt_social_networks',
		'icon'          => 'wt_vc_ico_social_networks',
		'class'         => 'wt_vc_sc_social_networks',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('Place social networks links', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'dropdown',
				'heading'       => __('Icon type', 'wt_vcsc'),
				'param_name'    => 'icon_type',
				'value' => array( 
					__('Type #1', 'wt_vcsc')   => 'wt_icon_type_1',
					__('Type #2', 'wt_vcsc')   => 'wt_icon_type_2', 
					__('Type #3', 'wt_vcsc')   => 'wt_icon_type_3',
					__('Type #4', 'wt_vcsc')   => 'wt_icon_type_4',
				),
				'description'   => __('Select social networks type.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Icon style', 'wt_vcsc'),
				'param_name'    => 'icon_style',
				'value' => array( 
					__('Simple', 'wt_vcsc')    => 'wt_simple',
					__('Square', 'wt_vcsc')    => 'wt_square', 
					__('Rounded', 'wt_vcsc')   => 'wt_rounded',
					__('Circle', 'wt_vcsc')    => 'wt_circle',
				),
				'description'   => __('Select social networks style.', 'wt_vcsc')
			),
			array(
				'type'          => 'colorpicker',
				'heading'       => __('Icon background', 'wt_vcsc'),
				'param_name'    => 'icon_background',
				'description'   => __( 'Select social networks background.', 'wt_vcsc' )
			),
			array(
				'type'          => 'colorpicker',
				'heading'       => __('Icon color', 'wt_vcsc'),
				'param_name'    => 'icon_color',
				'description'   => __( 'Select social networks text color.', 'wt_vcsc' )
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Icons alignment', 'wt_vcsc'),
				'param_name'    => 'icon_align',
				'value'         => array(__('Align left', 'wt_vcsc') => 'left', __('Align right', 'wt_vcsc') => 'right', __('Align center', 'wt_vcsc') => 'center'),
				'std'           => 'center',
				'description'   => __('Select icons alignment.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Icon margin', 'wt_vcsc'),
				'param_name'    => 'icon_margin',
				'std'           => '5',
				'description'   => __('Select icons margin. (in pixels)', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Icon size', 'wt_vcsc'),
				'param_name'    => 'icon_size',
				'value' => array( 
					'26' => '26',
					'32' => '32', 
					'38' => '38',
					'40' => '40',
					'42' => '42',
					'44' => '44',
					'50' => '50',
				),
				'std'           => '32',
				'description'   => __('Select social networks size.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Show tooltip title?', 'wt_vcsc'),
				'param_name'    => 'tooltip',
				'value'         => array( __( 'Yes, please', 'wt_vcsc' ) => 'true' ),
				'description'   => __('If YES, it shows a tooltip with the social link information.', 'wt_vcsc')
			),
			array(
				'type'          => 'dropdown',
				'heading'       => __('Tooltip placement', 'wt_vcsc'),
				'param_name'    => 'tooltip_placement',					
				'value'         => array( __('Top', 'wt_vcsc') => '', __('Bottom', 'wt_vcsc') => 'bottom', __('Left', 'wt_vcsc') => 'left', __('Right', 'wt_vcsc') => 'right'),
				'std'           => 'top',
				'dependency'    => array(
					'element'   => 'tooltip',
					'not_empty'  => true,
				),
				'description'   => __('Select tooltip placement.', 'wt_vcsc')
			),
			array(
				'type'          => 'wt_multidropdown',
				'heading'       => __('Social networks', 'wt_vcsc'),
				'admin_label'   => true,
				'param_name'    => 'social_networks',
				'value' => array( 
					__("No", "wt_vcsc")          => '',
					__('Website', 'wt_vcsc')     => 'website',
					__('Email', 'wt_vcsc')       => 'email', 
					__('Facebook', 'wt_vcsc')    => 'facebook', 
					__('Twitter', 'wt_vcsc')     => 'twitter',
					__('Pinterest', 'wt_vcsc')   => 'pinterest', 
					__('LinkedIn', 'wt_vcsc')    => 'linkedin', 
					__('Google +', 'wt_vcsc')    => 'google',  
					__('Dribbble', 'wt_vcsc')    => 'dribbble',   
					__('YouTube', 'wt_vcsc')     => 'youtube',   
					__('Vimeo', 'wt_vcsc')       => 'vimeo',   
					__('Rss', 'wt_vcsc')         => 'rss', 
					__('Github', 'wt_vcsc')      => 'github',
					__('Delicious', 'wt_vcsc')   => 'delicious',
					__('Flickr', 'wt_vcsc')      => 'flickr',
					//__('Forrst', 'wt_vcsc')      => 'forrst',
					__('Lastfm', 'wt_vcsc')      => 'lastfm',
					__('Tumblr', 'wt_vcsc')      => 'tumblr',
					__('Deviantart', 'wt_vcsc')  => 'deviantart',
					__('Skype', 'wt_vcsc')       => 'skype',
					__('Instagram', 'wt_vcsc')   => 'instagram',
					__('StumbleUpon', 'wt_vcsc') => 'stumbleupon',
					__('Behance', 'wt_vcsc')     => 'behance',
					__('SoundCloud', 'wt_vcsc')  => 'soundcloud',
					//__('Yelp', 'wt_vcsc')        => 'yelp',
					//__('Yahoo', 'wt_vcsc')       => 'yahoo',
					//__('WordPress', 'wt_vcsc')   => 'wordpress',
					//__('Technorati', 'wt_vcsc')  => 'technorati',
					//__('Picasa', 'wt_vcsc')      => 'picasa',
					//__('Paypal', 'wt_vcsc')      => 'paypal',
					//__('Netvibes', 'wt_vcsc')    => 'netvibes',
					//__('Metacafe', 'wt_vcsc')    => 'metacafe',
					//__('Html5', 'wt_vcsc')       => 'html5',
					//__('Ember', 'wt_vcsc')       => 'ember',
					//__('Dropbox', 'wt_vcsc')     => 'dropbox',
					//__('Digg', 'wt_vcsc')        => 'digg',
					//__('Blogger', 'wt_vcsc')     => 'blogger',
					//__('Apple', 'wt_vcsc')       => 'apple',
					//__('Aim', 'wt_vcsc')         => 'aim'
				),
				'description'   => __('Select custom social media links. <b>Hold the \'Ctrl\' or \'Shift\' keys while clicking to select multiple items</b>. <br>Don\'t include \'No\' option in your selection.', 'wt_vcsc')
			),		
				array(
					'type'               => 'textfield',
					'heading'            => __('Website Link', 'wt_vcsc'),
					'param_name'         => 'website_link',
					'description'        => __('Set website link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks',
						'value'   => array( 'website' )
					)
				),		
				array(
					'type'               => 'textfield',
					'heading'            => __('Email Link', 'wt_vcsc'),
					'param_name'         => 'email_link',
					'description'        => __('Set email link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => 'email'
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => __('Facebook Link', 'wt_vcsc'),
					'param_name'         => 'facebook_link',
					'description'        => __('Set facebook link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'facebook' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Twitter Link', 'wt_vcsc'),
					'param_name'         => 'twitter_link',
					'description'        => __('Set twitter link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'twitter' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Pinterest Link', 'wt_vcsc'),
					'param_name'         => 'pinterest_link',
					'description'        => __('Set pinterest link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'pinterest' )
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => __('LinkedIn Link', 'wt_vcsc'),
					'param_name'         => 'linkedin_link',
					'description'        => __('Set linkedin link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'linkedin' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Google + Link', 'wt_vcsc'),
					'param_name'         => 'google_link',
					'description'        => __('Set google + link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'google' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Dribbble Link', 'wt_vcsc'),
					'param_name'         => 'dribbble_link',
					'description'        => __('Set dribbble link.', 'wt_vcsc'),
					'param_holder_class' => ' border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'dribbble' ) 
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('YouTube Link', 'wt_vcsc'),
					'param_name'         => 'youtube_link',
					'description'        => __('Set youtube link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'youtube' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Vimeo Link', 'wt_vcsc'),
					'param_name'         => 'vimeo_link',
					'description'        => __('Set vimeo link.', 'wt_vcsc'),
					'param_holder_class' => ' border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'vimeo' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Rss Link', 'wt_vcsc'),
					'param_name'         => 'rss_link',
					'description'        => __('Set rss link.', 'wt_vcsc'),
					'param_holder_class' => 'hidden_el border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'rss' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Github Link', 'wt_vcsc'),
					'param_name'         => 'github_link',
					'description'        => __('Set github link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'github' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Delicious Link', 'wt_vcsc'),
					'param_name'         => 'delicious_link',
					'description'        => __('Set delicious link.', 'wt_vcsc'),
					'param_holder_class' => 'hidden_el border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'delicious' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Flickr Link', 'wt_vcsc'),
					'param_name'         => 'flickr_link',
					'description'        => __('Set flickr link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'flickr' )
					)
				), /*	
				array(
					'type'               => 'textfield',
					'heading'            => __('Forrst Link', 'wt_vcsc'),
					'param_name'         => 'forrst_link',
					'description'        => __('Set forrst link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'forrst' )
					)
				), */	
				array(
					'type'               => 'textfield',
					'heading'            => __('Lastfm Link', 'wt_vcsc'),
					'param_name'         => 'lastfm_link',
					'description'        => __('Set lastfm link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'lastfm' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Tumblr Link', 'wt_vcsc'),
					'param_name'         => 'tumblr_link',
					'description'        => __('Set tumblr link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'tumblr' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Deviantart Link', 'wt_vcsc'),
					'param_name'         => 'deviantart_link',
					'description'        => __('Set deviantart link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'deviantart' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Skype Link', 'wt_vcsc'),
					'param_name'         => 'skype_link',
					'description'        => __('Set skype link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'skype' )
					)
				),
				array(
					'type'               => 'textfield',
					'heading'            => __('Instagram Link', 'wt_vcsc'),
					'param_name'         => 'instagram_link',
					'description'        => __('Set instagram link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'instagram' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('StumbleUpon Link', 'wt_vcsc'),
					'param_name'         => 'stumbleupon_link',
					'description'        => __('Set stumbleupon link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array( 
						'element' => 'social_networks', 
						'value'   => array( 'stumbleupon' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('Behance Link', 'wt_vcsc'),
					'param_name'         => 'behance_link',
					'description'        => __('Set behance link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'behance' )
					)
				),	
				array(
					'type'               => 'textfield',
					'heading'            => __('SoundCloud Link', 'wt_vcsc'),
					'param_name'         => 'soundcloud_link',
					'description'        => __('Set soundcloud link.', 'wt_vcsc'),
					'param_holder_class' => 'border_box wt_dependency',
					'dependency'         => array(
						'element' => 'social_networks', 
						'value'   => array( 'soundcloud' )
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