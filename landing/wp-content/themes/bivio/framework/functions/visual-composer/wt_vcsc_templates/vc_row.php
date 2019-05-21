<?php
$output = $el_class = $css = '';
extract(shortcode_atts(array(
	'el_class'			 => '',
	'el_id'				 => '',
	'min_height'		 => '',
	'center_row'		 => '',
	'full_mobile_row'    => '',
	
	'default_bg'	 	 => '',
	'default_skin_bg'	 => '',
	'default_border'	 => '',
	'shadow'		     => '',
	'typography'         => 'dark',
	
	'bck_color'			 => '',
	
	'bg_type'			 => 'image',
	'bck_image'			 => '',
	'bg_size'			 => 'full',
	'bg_position'		 => 'top',
	'bg_size_standard'   => 'cover',
	'bg_repeat'	    	 => 'no-repeat',
	 
	'youtube_video_id'	 => '',
	
	'video_mp4'			 => '',
	'video_ogv'			 => '',
	'video_webm'		 => '',
	'video_image'		 => '',	
	
	'border_color'		 => '',
	'border_style'		 => 'solid',
	'border_width'		 => '',	
	
	'bg_color_overlay'	 => '',
	'bg_pattern_overlay' => '',
	
	'padding_top'		 => 0,
	'padding_bottom'	 => 0,
	'padding_left'		 => 0,
	'padding_right'	     => 0,
	'margin_top'		 => 0,
	'margin_bottom'		 => 0,
	
	'css_animation'		 => '',
	'anim_type'          => '',
	'anim_delay'         => '',		
), $atts));

wp_enqueue_script( 'wpb_composer_front_js' );

// WT ID
$id = mt_rand(9999, 99999);
if (trim($el_id) != false) {
	$el_id = esc_attr( trim($el_id) );
	$el_id = ' id="'.$el_id.'"';
} else {
	// $el_id = 'wt-row' . '-' . $id;
	$el_id = '';
}

// Main VC class
$el_class = $this->getExtraClass($el_class);

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'vc_row wpb_row '. ( $this->settings('base')==='vc_row_inner' ? 'vc_inner ' : '' ) . get_row_css_class() . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

// Animations
$this->wt_sc = new WT_VCSC_SHORTCODE;
$anim_class  = $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
$anim_data   = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);

// WT row container class
$row_class = '';
if ( $center_row == 'yes' ) {
	$row_class = ' wt-row-centered'; 
}
if ( $full_mobile_row == 'yes' ) {
	$row_class .= ' wt-row-full-mobile'; 
}
if ( $bg_type != '' ) {
	$row_class .= ' wt-background-'. $bg_type; 
} elseif ($bg_type == '' && $bck_color) {
	$row_class .= ' wt-background'; 
} else {
	$row_class .= '';
}

if ( $default_bg == 'yes' ) {
	$row_class .= ' wt_row_default_bg';
}

if ( $default_skin_bg == 'yes' ) {
	$row_class .= ' wt_skin_bg_color';
}

if ( $default_border == 'yes' ) {
	$row_class .= ' wt_row_default_border';
}

if ( $shadow == 'yes' ) {
	$row_class .= ' wt_row_shadow';
}

if ( $typography == 'light' ) {
	$row_class .= ' wt_skin_light';
} else {
	$row_class .= '';
}

$bg_size = esc_html($bg_size);

// BG image
if ( $bg_type != '' && $bg_type != 'youtube' ) {
	if ( $bck_image ) { // if background image is not empty
		//$bg_img_url = wp_get_attachment_url( $bck_image );
		$img_id = preg_replace('/[^\d]/', '', $bck_image);
		$img = wp_get_attachment_image_src( $img_id, $bg_size);
		$bg_img_url = $img[0];
	}
} else {
	$bg_img_url       = NULL;
	$bg_position      = NULL;
	$bg_size_standard = NULL;
	$bg_repeat        = NULL;
}

// Style tag for background, margin
$add_style = array();

	if ( $min_height ) {
		$add_style[] = 'min-height: '. $min_height .'px;';
	}
	
	if ( $bck_color ) {
		$add_style[] = 'background-color: '. $bck_color .';';
	}	

	if ( $bg_img_url ) {
		$add_style[] = 'background-image: url('. $bg_img_url .');';
	}
	
	if ( $bg_position ) {
		$add_style[] = 'background-position: '. $bg_position .' center;';
	}
	
	if ( $bg_size_standard ) {
		$add_style[] = '-webkit-background-size: '. $bg_size_standard .';-moz-background-size: '. $bg_size_standard .';-o-background-size: '. $bg_size_standard .';background-size: '. $bg_size_standard .';';
	}
	
	if ( $bg_repeat ) {
		$add_style[] = 'background-repeat: '. $bg_repeat .';';
	}
	
	if ( $border_color && $border_style && $border_width ) {
		$add_style[] = 'border-color: '. $border_color .';';
		$add_style[] = 'border-style: '. $border_style .';';
		$add_style[] = 'border-width: '. $border_width .';';
	}		
	
	if ( $margin_top ) {
		$add_style[] = 'margin-top: ' . intval($margin_top) . 'px;';
	}
	
	if ( $margin_bottom ) {
		$add_style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px;';
	}

// Style tag for paddings
$add_style_padd = array(); 

	if ( $padding_top ) {
		$add_style_padd[] = 'padding-top: ' . intval($padding_top) . 'px;';
	}
	
	if ( $padding_bottom ) {
		$add_style_padd[] = 'padding-bottom: ' . intval($padding_bottom) . 'px;';
	}
	
	if ( $padding_left ) {
		$add_style_padd[] = 'padding-left: ' . intval($padding_left) . 'px;';
	}
	
	if ( $padding_right ) {
		$add_style_padd[] = 'padding-right: ' . intval($padding_right) . 'px;';
	}

$add_style      = implode('', $add_style);
$add_style_padd = implode('', $add_style_padd);

if ( $add_style ) {
	$add_style = wp_kses( $add_style, array() );
	$add_style = ' style="' . esc_attr($add_style) . '"';
}

if ( $add_style_padd ) {
	$add_style_padd = wp_kses( $add_style_padd, array() );
	$add_style_padd = ' style="' . esc_attr($add_style_padd) . '"';
}

// Overlay
$overlay         = '';	
$overlay_color   = '';
$overlay_pattern = '';

if ( $bg_pattern_overlay != '' ) {
	$overlay_pattern = ' wt_row_pattern_'.$bg_pattern_overlay.'"';	
}

if ( $bg_color_overlay != '' ) {
	$overlay_color = ' style="background-color:'.$bg_color_overlay.'"';	
}

if ( $bg_color_overlay != '' || $bg_pattern_overlay != '' ) {
	$overlay = '<span class="wt_row_overlay'.$overlay_pattern.'"'.$overlay_color.'></span>';	
}

// Open wt row container
$output .= '<div'.$el_id.' class="wt-row-container'.$row_class.$el_class.$anim_class.'"'.$anim_data.$add_style .'>';

	if ( $bg_type == 'youtube' && $youtube_video_id != '' ) {
		wp_enqueue_script( 'wt-extend-youtube-player');
		$output .= '<div class="wt-youtube-bg-wrap wt-youtube-video-'. $id .'">';
		$output .= $overlay; // Overlay for videos
		$output .= '<a id="youtube-bg-video_'. $id .'" class="wt_youtube_player" data-property="{videoURL:\'http://www.youtube.com/watch?v='. $youtube_video_id .'\', autoPlay:true, containment:\'.wt-youtube-video-'. $id .'\', mute:true, startAt:0, opacity:1, ratio:\'4/3\', addRaster:true, showControls:false}"></a> <a class="wt-video-volume" onclick="jQuery(\'#youtube-bg-video_'. $id .'\').toggleVolume()"><i class="fa-volume-down"></i></a>';
		$output .= '</div>';
	}	
	
	if ( $bg_type != 'youtube' ) {
		$output .= $overlay; // Overlay for images
	} 

	// Open VC Row
	$output .= '<div class="'. $css_class .' '. '" '.$add_style_padd .'>';
	
		// Center the row
		if ( $center_row == 'yes' ) {
			$output .= '<div class="container">';
				$output .= '<div class="row">';
		}
			
			// The Inner Row
			$output .= wpb_js_remove_wpautop($content);
		
		// Center the row
		if ( $center_row == 'yes' ) {
				$output .= '</div>'; // End Row
			$output .= '</div>'; // End container
		}
	
	// Close VC Row
	$output .= '</div>'.$this->endBlockComment('row');		
	
// Close wt row container
$output .= '</div>';

echo $output;