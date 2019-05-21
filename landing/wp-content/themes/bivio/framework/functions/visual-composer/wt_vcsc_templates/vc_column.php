<?php
$output = $el_class = $width = $offset = $css = '';
extract(shortcode_atts(array(
	'el_class'			 => '',
	'el_id'				 => '',
	'width'				 => '1/1',
	'offset'             => '',
	
	'style'				 => '',
	'shadow'		     => '',
	'typography'         => 'dark',
	
	'bck_color'			 => '',	
	'bg_type'			 => 'image',
	'bck_image'			 => '',
	'bg_size'			 => 'full',
	'bg_position'		 => 'top',
	'bg_size_standard'   => 'cover',
	'bg_repeat'	    	 => 'no-repeat',
	
	'border_color'		 => '',
	'border_style'		 => 'solid',
	'border_width'		 => '',
	
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

// WT ID
$id = mt_rand(9999, 99999);
if (trim($el_id) != false) {
	$el_id = esc_attr( trim($el_id) );
	$el_id = ' id="'.$el_id.'"';
} else {
	// $el_id = 'wt-row' . '-' . $id;
	$el_id = '';
}

$el_class  = $this->getExtraClass($el_class);
$width     = wpb_translateColumnWidthToSpan($width);
$width     = vc_column_offset_class_merge($offset, $width);

// Animations
$this->wt_sc = new WT_VCSC_SHORTCODE;
$anim_class  = $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
$anim_data   = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);

$el_class .= ' wpb_column vc_column_container'.$anim_class;

$bg_size = esc_html($bg_size);

// BG image
if ( $bg_type != '' ) {
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

// Wrapper Classes

$col_wrapper_classes = '';

if ( $bg_type != '' ) {
	$col_wrapper_classes = ' wt-background-'. $bg_type; 
} elseif ($bg_type == '' && $bck_color) {
	$col_wrapper_classes = ' wt-background'; 
} else {
	$col_wrapper_classes = '';
}

if ( $style != '' ) {
	$col_wrapper_classes .= ' wt_column_' . $style;
}

if ( $shadow == 'yes' ) {
	$col_wrapper_classes .= ' wt_column_shadow';
}

if ( $typography == 'light' ) {
	$col_wrapper_classes .= ' wt_skin_light';
} else {
	$col_wrapper_classes .= '';
}

// Outer Style
$add_style = array();
	
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

	if ( $padding_top ) {
		$add_style[] = 'padding-top: ' . intval($padding_top) . 'px;';
	}
	
	if ( $padding_bottom ) {
		$add_style[] = 'padding-bottom: ' . intval($padding_bottom) . 'px;';
	}
	
	if ( $padding_left ) {
		$add_style[] = 'padding-left: ' . intval($padding_left) . 'px;';
	}
	
	if ( $padding_right ) {
		$add_style[] = 'padding-right: ' . intval($padding_right) . 'px;';
	}
	
	if ( $margin_top ) {
		$add_style[] = 'margin-top: ' . intval($margin_top) . 'px;';
	}
	
	if ( $margin_bottom ) {
		$add_style[] = 'margin-bottom: ' . intval($margin_bottom) . 'px;';
	}

$add_style = implode('', $add_style);

if ( $add_style ) {
	$add_style = wp_kses( $add_style, array() );
	$add_style = ' style="' . esc_attr($add_style) . '"';
}

// Output

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $width . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$output .= "\n\t".'<div'.$el_id.' class="'. $css_class .'"'. $anim_data .'>';
	$output .= "\n\t\t".'<div class="wt_wpb_wrapper'. $col_wrapper_classes .' clearfix" '. $add_style .'>';
		//$output .= "\n\t\t".'<div class="wpb_wrapper">';
		$output .= "\n\t\t\t".wpb_js_remove_wpautop($content);
		//$output .= "\n\t\t".'</div> '.$this->endBlockComment('.wpb_wrapper');
	$output .= "\n\t\t".'</div>';
$output .= "\n\t".'</div> '.$this->endBlockComment($el_class) . "\n";

echo $output;