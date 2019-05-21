<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_gmap extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'address'               => '',
			'height'                => 100,
			'zoom'              	=> 14,
			'fit_bounds'            => false, 
			'center_on_markerclick' => false,
			'clusters'              => false,
			'scrollwheel'           => false,
			'theme'                 => '',
			'marker_data'           => '',
			 			
			'el_id'                 => '',
			'el_class'              => '',
    		'css_animation'         => '',
    		'anim_type'             => '',
    		'anim_delay'            => ''
		), $atts ) );				
		
		wp_enqueue_script('wt-extend-gmap-api');		
		
		if ($clusters==true) {
			wp_enqueue_script('wt-extend-gmap-markerclusterer');
		}
		
		wp_enqueue_script('wt-extend-gmap');
		
		$sc_class = 'wt_gmap_sc';
		
		$address  = WT_VCSC_ValidateMarkerText($address);
		$height   = (int)$height;
		$zoom     = (int)$zoom;
		$fit_bounds            = $fit_bounds            ? 1 : 0;
		$center_on_markerclick = $center_on_markerclick ? 1 : 0;
		$clusters              = $clusters              ? 1 : 0;
		$scrollwheel           = $scrollwheel           ? 'true' : 'false';
		
		if ($theme != '') {
			$theme = rawurldecode(base64_decode(strip_tags($theme)));
		} else {
			$theme = '[]';
		}	
		
		if ($marker_data != '') {
			$marker_data = rawurldecode(base64_decode(strip_tags($marker_data)));
        	$markers     = json_decode( str_replace('``', '"', $marker_data), true);
		} else {
        	$markers = null;
		}		
				
		$id = mt_rand(9999, 99999);
		if (trim($el_id) != false) {
			$el_id = ' id="' . esc_attr( trim($el_id) ) . '"';
		} else {
			$el_id = $sc_class . '-' . $id;
		}		
		
		$markers_out = '';
		
		if(!empty($markers)):		
			$countMarkers = count($markers);
			$i = 0;
						
			foreach ($markers as $marker):
			
				$marker_address  = WT_VCSC_ValidateMarkerText($marker['m_address']);
				// since 1.3 version, marker lat/lng is saved on marker address input which means we can use it directly
				$marker_lat_lng  = (isset($marker['m_lat_lng']))  ? $marker['m_lat_lng'] : '';
				$marker_icon_url = (isset($marker['m_icon_url']) && !empty($marker['m_icon_url'])) ? $marker['m_icon_url'] : THEME_VC_IMG . '/marker.png';
				$marker_title    = WT_VCSC_ValidateMarkerText($marker['m_title']);
				$marker_desc     = WT_VCSC_ValidateMarkerText($marker['m_desc']);
				
				$m_address_out = '"address": "' . $marker_address.'", ';
				$m_lat_lng_out = '"lat_lng": "' . $marker_lat_lng.'", ';
				$m_icon_out    = '"icon": "' . $marker_icon_url.'", ';
				$m_title_out   = '"title": "' . $marker_title.'", ';
				$m_desc_out    = '"description": "' . $marker_desc.'"';
				
				if(++$i === $countMarkers) {
					// last marker
					$markers_out .= '{' . $m_address_out . $m_lat_lng_out . $m_icon_out . $m_title_out . $m_desc_out . '}';
				} else {
					$markers_out .= '{' . $m_address_out . $m_lat_lng_out . $m_icon_out . $m_title_out . $m_desc_out . '},';
				}
			
			endforeach; 
		endif;
						
		$el_class  = esc_attr( $this->getExtraClass($el_class) );		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class, $this->settings['base']);
		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$output = '';		
			
		$output .= '<div id="'.$el_id.'" class="'.$css_class.'"'.$anim_data.' style="height: '.$height.'px">';		
		if(empty($address)) {
			$output .= '<p>Please fill the address field within Visual Composer interface to get a working google map.</p>';
		}		
		$output .= '</div>';
		
		if(!empty($address)) {
		$output .= "
<script type=\"text/javascript\">
	jQuery(document).ready(function($) {
		var map_options = {
			map_id  : '$el_id',
			address : '$address',
			zoom    : $zoom,
			theme   : $theme,
			markers : [
				$markers_out
			],
			fit_to_markers        : $fit_bounds,
			center_on_markerclick : $center_on_markerclick,
			clusters              : $clusters,
			scrollwheel           : $scrollwheel
		};
		console.log(map_options);
		wt_vcsc_extend_google_map(map_options);	
	});
</script>";
}
		
        return $output;
    }
	
}

/*
Register WhoaThemes shortcode within Visual Composer interface.
*/

if (function_exists('wpb_map')) {
		
	$add_wt_sc_func             = new WT_VCSC_SHORTCODE;
	$add_wt_extra_id            = $add_wt_sc_func->getWTExtraId();
	$add_wt_extra_class         = $add_wt_sc_func->getWTExtraClass();
	$add_wt_css_animation       = $add_wt_sc_func->getWTAnimations();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	wpb_map( array(
		'name'          => esc_html(__('WT Google Map', 'wt_vcsc')),
		'base'          => 'wt_gmap',
		'icon'          => 'wt_vc_ico_gmap',
		'class'         => 'wt_vc_sc_gmap',
		'category'      => esc_html(__('by WhoaThemes', 'wt_vcsc')),
		'description'   => esc_html(__('Google map with custom markers', 'wt_vcsc')),
		'params'        => array(
			array(
				'type'          => 'textfield',
				'heading'       => esc_html(__('Map address', 'wt_vcsc')),
				'holder'        => 'div',
				'param_name'    => 'address',
				'description'   => esc_html(__('Address where the map should point (ex. "London, UK").', 'wt_vcsc'))
			),
			array(
				'type'          => 'wt_gmap_markers',
				'heading'       => 'Map markers',
				'holder'        => 'div',
				'param_name'    => 'map_markers',
				'value'         => ''
			),
			array(
				'type'          => 'wt_range',
				'heading'       => esc_html(__( 'Map height', 'wt_vcsc') ),
				'param_name'    => 'height',
				'value'         => '100',
				'min'           => '100',
				'max'           => '1000',
				'step'          => '1',
				'unit'          => 'px',
				'description'   => esc_html(__( 'Height of the map in px.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			
			array(
				'type'          => 'wt_range',
				'heading'       => esc_html(__( 'Map zoom', 'wt_vcsc') ),
				'param_name'    => 'zoom',
				'value'         => '14',
				'min'           => '0',
				'max'           => '19',
				'step'          => '1',
				//'unit'          => 'px',
				'description'   => esc_html(__( 'Map zoom level from 0 to 19.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'type'			=> 'checkbox',
				'heading'		=> esc_html(__('Fit markers?','wt_vcsc')),
				'param_name'	=> 'fit_bounds',
				'value'			=> Array( esc_html(__('Yes please.', 'wt_vcsc')) => true),
				'description'   => esc_html(__( 'Set map zoom to cover all visible markers.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'type'			=> 'checkbox',
				'heading'		=> esc_html(__('Center & zoom on marker click?','wt_vcsc')),
				'param_name'	=> 'center_on_markerclick',
				'value'			=> Array( esc_html(__('Yes please.', 'wt_vcsc')) => true),
				'description'   => esc_html(__( 'Auto center & zoom the map on marker click event.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'type'			=> 'checkbox',
				'heading'		=> esc_html(__('Group / cluster markers?','wt_vcsc')),
				'param_name'	=> 'clusters',
				'value'			=> Array( esc_html(__('Yes please.', 'wt_vcsc')) => true),
				'description'   => esc_html(__( 'Group markers that are relatively close together into marker areas.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'type'			=> 'checkbox',
				'heading'		=> esc_html(__('Scroll wheel?','wt_vcsc')),
				'param_name'	=> 'scrollwheel',
				'value'			=> Array( esc_html(__('Yes please.', 'wt_vcsc')) => true),
				'description'   => esc_html(__( 'If false, disables scrollwheel zooming in Street View.', 'wt_vcsc') ),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'type'          => 'textarea_raw_html',
				'heading'       => esc_html(__('Map theme', 'wt_vcsc')),
				//'holder'        => 'div',
				'param_name'    => 'theme',
				'value'         => '',
				'description'   => esc_html(__('JavaScript Style Array. Set your own style or see ', 'wt_vcsc')) . '<a href="http://snazzymaps.com/" target="_blank">http://snazzymaps.com</a>' . esc_html(__(' for more free themes. Also you can use ', 'wt_vcsc')) . '<a href="http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html" target="_blank">this online editor</a>' . esc_html(__(' to create a personalized google map. Copy / paste here the JSON array.', 'wt_vcsc')),
				'group'         => esc_html(__('Advanced map settings', 'wt_vcsc'))
			),
			array(
				'heading'       => '',
				'type'          => 'textarea_raw_html',
				//'holder'        => 'div',
				'param_name'    => 'marker_data',
				'value'         => '',
			),				
			array(
				'type'                => 'wt_loadfile',
				'heading'             => '',
				'param_name'          => 'el_file',
				'value'               => '',
				'file_type'           => 'js',
				'file_path'           => 'wt-visual-composer-extend-element.min.js',
				'param_holder_class'  => 'wt_loadfile_field',
				'description'         => ''
			),
			
			$add_wt_extra_id,
			$add_wt_extra_class,
			$add_wt_css_animation,
			$add_wt_css_animation_type,
			$add_wt_css_animation_delay
						
		)
	));
	
}