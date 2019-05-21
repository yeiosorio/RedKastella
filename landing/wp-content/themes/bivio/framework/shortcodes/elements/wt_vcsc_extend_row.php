<?php
	if (function_exists('vc_add_param')) {
		
		// Row WT_VC Extensions
		vc_add_param("vc_row", array(
			'type'              			=> 'wt_separator',
			'heading'           			=> __( '', 'wt_vcsc' ),
			'param_name'        			=> 'separator',
			'value'             			=> 'Background Extended Settings',
			'description'       			=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                          => 'textfield',
			'heading'                       => __('Extra Unique ID name', 'wt_vcsc'),
			'param_name'                    => 'el_id',
			'description'                   => __('If you wish to style particular content element differently, then use this field to add a UNIQUE ID name and then refer to it in your css file.', 'wt_vcsc')
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Minimum Height', 'wt_vcsc' ),
			'param_name'            		=> 'min_height',
			'value'                 		=> '0',
			'min'                   		=> '0',
			'max'                   		=> '2048',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( 'Define the minimum height for this row.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'class'							=> '',
			'heading'						=> __('Center Row Content?','wt_vcsc'),
			'param_name'					=> 'center_row',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Use this option to horizontally center the inner content of this row. <strong>Useful when using pages based on \'Full Screen\' templates.</strong>', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'class'							=> '',
			'heading'						=> __('Full Screen Width on Mobiles?','wt_vcsc'),
			'param_name'					=> 'full_mobile_row',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Use this option to keep full width content of this row on mobiles. <strong>Useful when using pages based on \'Full Screen\' templates.</strong>', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'heading'						=> __('Default Background?','wt_vcsc'),
			'param_name'					=> 'default_bg',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Check this option to add a default background color.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));	
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'heading'						=> __('Default Skin Background?','wt_vcsc'),
			'param_name'					=> 'default_skin_bg',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Check this option to add a default skin background color.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));	
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'heading'						=> __('Default Border?','wt_vcsc'),
			'param_name'					=> 'default_border',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Check this option to add a default border.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		vc_add_param( "vc_row", array(
			'type'							=> 'checkbox',
			'heading'						=> __('Drop Shadow?','wt_vcsc'),
			'param_name'					=> 'shadow',
			'value'							=> Array(__('Yes please.', 'wt_vcsc') => 'yes'),
			'description'           		=> __( 'Check this option to add a default shadow to this row.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		vc_add_param("vc_row", array(
			'type' 							=> 'dropdown',
			'heading' 						=> __( 'Typography Style', 'wt_vcsc'),
			'param_name' 					=> 'typography',
			'value' 						=> array(
				__( 'Dark Text', 'wt_vcsc')		=> 'dark',
				__( 'White Text', 'wt_vcsc')	=> 'light'
			),
			'description' 					=> __('Select typography style.', 'wt_vcsc'),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                          => 'colorpicker',
			'heading'                       => __('Background Color', 'wt_vcsc'),
			'param_name'                    => 'bck_color',
			'description'                   => __( 'Select background color for this row.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));	
		vc_add_param("vc_row", array(
			'type' 							=> 'dropdown',
			'heading' 						=> __( 'Background Type', 'wt_vcsc'),
			'param_name' 					=> 'bg_type',
			'value' 						=> array(
				__( 'None', 'wt_vcsc')					=> '',
				__( 'Simple Image', 'wt_vcsc')			=> 'image',
				__( 'Fixed Image', 'wt_vcsc')			=> 'fixed',
				__( 'Parallax Image', 'wt_vcsc')		=> 'parallax',
				__( 'YouTube Video', 'wt_vcsc')			=> 'youtube',
				//__( 'Self Hosted Video', 'wt_vcsc')		=> 'video',
			),
			'admin_label' 					=> true,
			'description' 					=> __('Select background type for this row.', 'wt_vcsc'),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'							=> 'attach_image',
			'heading'						=> __( 'Background Image', 'wt_vcsc' ),
			'param_name'					=> 'bck_image',
			'value'							=> '',
			'description'					=> __( 'Select the background image for your row.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> array('image', 'fixed', 'parallax')
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'dropdown',
			'heading'               		=> __( 'Background Image Size', 'wt_vcsc' ),
			'param_name'            		=> 'bg_size',
			'value'                 		=> array(
				__( 'Full Size Image', 'wt_vcsc' )			=> 'full',
				__( 'Large Size Image', 'wt_vcsc' )			=> 'large',
				__( 'Medium Size Image', 'wt_vcsc' )		=> 'medium',
				__( 'Thumbnail Size Image', 'wt_vcsc' )		=> 'thumbnail',
			),
			'description'           		=> __( 'Select which image size based on WordPress settings should be used.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> array('image', 'fixed', 'parallax')
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type' 							=> 'dropdown',
			'heading' 						=> __( 'Background Position', 'wt_vcsc' ),
			'param_name' 					=> 'bg_position',
			'value' 						=> array(
				__( 'Top', 'wt_vcsc' )			=> 'top',
				__( 'Middle', 'wt_vcsc' ) 		=> 'center',
				__( 'Bottom', 'wt_vcsc' ) 		=> 'bottom'
			),
			'description' 					=> __(''),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> array('image', 'fixed', 'parallax')
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc' ),
		));
		vc_add_param("vc_row", array(
			'type' 							=> 'dropdown',
			'heading' 						=> __( 'Background Size', 'wt_vcsc' ),
			'param_name' 					=> 'bg_size_standard',
			'value' 						=> array(
				__( 'Cover', 'wt_vcsc' ) 		=> 'cover',
				__( 'Contain', 'wt_vcsc' ) 		=> 'contain',
				__( 'Initial', 'wt_vcsc' ) 		=> 'initial',
				__( 'Auto', 'wt_vcsc' ) 		=> 'auto',
			),
			'description' 					=> __(''),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> array('image', 'fixed', 'parallax')
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type' 							=> 'dropdown',
			'heading' 						=> __( 'Background Repeat', 'wt_vcsc' ),
			'param_name' 					=> 'bg_repeat',
			'value' 						=> array(
				__( 'No Repeat', 'wt_vcsc' )	=> 'no-repeat',
				__( 'Repeat X + Y', 'wt_vcsc' )	=> 'repeat',
				__( 'Repeat X', 'wt_vcsc' )		=> 'repeat-x',
				__( 'Repeat Y', 'wt_vcsc' )		=> 'repeat-y'
			),
			'description' 					=> __(''),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> array('image', 'fixed', 'parallax')
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		
		// YouTube Video Background
		vc_add_param("vc_row", array(
			'type'              			=> 'textfield',
			'heading'           			=> __( 'YouTube Video ID', 'wt_vcsc' ),
			'param_name'        			=> 'youtube_video_id',
			'value'             			=> '',
			'description'       			=> __( 'Enter the YouTube video ID.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> 'youtube'
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		
		// Self Hosted Video Background
		/*
		vc_add_param("vc_row", array(
			'type'              			=> 'textfield',
			'heading'           			=> __( 'MP4 Video Path', 'wt_vcsc' ),
			'param_name'        			=> 'video_mp4',
			'value'             			=> '',
			'description'       			=> __( 'Enter the path to the MP4 video version.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> 'video'
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'              			=> 'textfield',
			'heading'           			=> __( 'OGV Video Path', 'wt_vcsc' ),
			'param_name'        			=> 'video_ogv',
			'value'             			=> '',
			'description'       			=> __( 'Enter the path to the OGV video version.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> 'video'
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'              			=> 'textfield',
			'heading'           			=> __( 'WEBM Video Path', 'wt_vcsc' ),
			'param_name'        			=> 'video_webm',
			'value'             			=> '',
			'description'       			=> __( 'Enter the path to the WEBM video version.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> 'video'
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'							=> 'attach_image',
			'heading'						=> __( 'Video Screenshot Image', 'wt_vcsc' ),
			'param_name'					=> 'video_image',
			'value'							=> '',
			'description'					=> __( 'Select the a screenshot image for the video.', 'wt_vcsc' ),
			'dependency' 					=> array(
				'element' 	=> 'bg_type',
				'value' 	=> 'video'
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		*/		
		vc_add_param("vc_row", array(
			'type'                          => 'colorpicker',
			'heading'                       => __('Background Overlay Color', 'wt_vcsc'),
			'param_name'                    => 'bg_color_overlay',
			'description'                   => __( 'Select overlay color for this element.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));	
		vc_add_param("vc_row", array(
			'type'                  		=> 'dropdown',
			'heading'               		=> __( 'Background Overlay Pattern', 'wt_vcsc' ),
			'param_name'            		=> 'bg_pattern_overlay',
			'value'                 		=> array(
				__( 'None', 'wt_vcsc' )			=> '',
				__( 'Dotted', 'wt_vcsc' )		=> 'dotted',
				__( 'Dashed', 'wt_vcsc' )		=> 'dashed',
			),
			'description'           		=> __( 'Select overlay pattern type for this element.', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'							=> 'colorpicker',
			'class'							=> '',
			'heading'						=> __('Border Color','wt_vcsc'),
			'param_name'					=> 'border_color',
			'value' 						=> '',
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		
		vc_add_param("vc_row", array(
			'type'							=> 'dropdown',
			'class'							=> '',
			'heading'						=> __('Border Style','wt_vcsc'),
			'param_name'					=> 'border_style',
			'value'							=> array(
				__('Solid', 'wt_vcsc')	=> 'solid',
				__('Dotted', 'wt_vcsc')	=> 'dotted',
				__('Dashed', 'wt_vcsc')	=> 'dashed',
			),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));
		
		vc_add_param("vc_row", array(
			'type'							=> 'textfield',
			'class'							=> '',
			'heading'						=> __('Border Width','wt_vcsc'),
			'param_name'					=> 'border_width',
			'value'							=> '0px 0px 0px 0px',
			'description'					=> __('Your border width in pixels. Example: <strong>1px 1px 1px 1px</strong> (top, right, bottom, left).', 'wt_vcsc'),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc')
		));	
		
		// Paddings & Margins
		vc_add_param("vc_row", array(
			'type'              			=> 'wt_separator',
			'heading'           			=> __( '', 'wt_vcsc' ),
			'param_name'        			=> 'separator_2',
			'value'             			=> 'Paddings and Margins',
			'description'       			=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Padding: Top', 'wt_vcsc' ),
			'param_name'            		=> 'padding_top',
			'value'                 		=> '0',
			'min'                   		=> '0',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Padding: Bottom', 'wt_vcsc' ),
			'param_name'            		=> 'padding_bottom',
			'value'                 		=> '0',
			'min'                   		=> '0',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Padding: Left', 'wt_vcsc' ),
			'param_name'            		=> 'padding_left',
			'value'                 		=> '0',
			'min'                   		=> '0',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Padding: Right', 'wt_vcsc' ),
			'param_name'            		=> 'padding_right',
			'value'                 		=> '0',
			'min'                   		=> '0',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Margin: Top', 'wt_vcsc' ),
			'param_name'            		=> 'margin_top',
			'value'                 		=> '0',
			'min'                   		=> '-250',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_range',
			'heading'               		=> __( 'Margin: Bottom', 'wt_vcsc' ),
			'param_name'            		=> 'margin_bottom',
			'value'                 		=> '0',
			'min'                   		=> '-250',
			'max'                   		=> '250',
			'step'                  		=> '1',
			'unit'                  		=> 'px',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		
		// Animations	
		vc_add_param("vc_row", array(
			'type'              			=> 'wt_separator',
			'heading'           			=> __( '', 'wt_vcsc' ),
			'param_name'        			=> 'separator_3',
			'value'             			=> 'Animations',
			'description'       			=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));	
		vc_add_param("vc_row", array(
			"type"                          => "dropdown",
			"heading"                       => __("CSS WT Animation", "wt_vcsc"),
			"param_name"                    => "css_animation",
			"value" => array(__("No", "wt_vcsc") => '', __("Hinge", "wt_vcsc") => "hinge", __("Flash", "wt_vcsc") => "flash", __("Shake", "wt_vcsc") => "shake", __("Bounce", "wt_vcsc") => "bounce", __("Tada", "wt_vcsc") => "tada", __("Swing", "wt_vcsc") => "swing", __("Wobble", "wt_vcsc") => "wobble", __("Pulse", "wt_vcsc") => "pulse", __("Flip", "wt_vcsc") => "flip", __("FlipInX", "wt_vcsc") => "flipInX", __("FlipOutX", "wt_vcsc") => "flipOutX", __("FlipInY", "wt_vcsc") => "flipInY", __("FlipOutY", "wt_vcsc") => "flipOutY", __("FadeIn", "wt_vcsc") => "fadeIn", __("FadeInUp", "wt_vcsc") => "fadeInUp", __("FadeInDown", "wt_vcsc") => "fadeInDown", __("FadeInLeft", "wt_vcsc") => "fadeInLeft", __("FadeInRight", "wt_vcsc") => "fadeInRight", __("FadeInUpBig", "wt_vcsc") => "fadeInUpBig", __("FadeInDownBig", "wt_vcsc") => "fadeInDownBig", __("FadeInLeftBig", "wt_vcsc") => "fadeInLeftBig", __("FadeInRightBig", "wt_vcsc") => "fadeInRightBig", __("FadeOut", "wt_vcsc") => "fadeOut", __("FadeOutUp", "wt_vcsc") => "fadeOutUp", __("FadeOutDown", "wt_vcsc") => "fadeOutDown", __("FadeOutLeft", "wt_vcsc") => "fadeOutLeft", __("FadeOutRight", "wt_vcsc") => "fadeOutRight", __("fadeOutUpBig", "wt_vcsc") => "fadeOutUpBig", __("FadeOutDownBig", "wt_vcsc") => "fadeOutDownBig", __("FadeOutLeftBig", "wt_vcsc") => "fadeOutLeftBig", __("FadeOutRightBig", "wt_vcsc") => "fadeOutRightBig", __("BounceIn", "wt_vcsc") => "bounceIn", __("BounceInUp", "wt_vcsc") => "bounceInUp", __("BounceInDown", "wt_vcsc") => "bounceInDown", __("BounceInLeft", "wt_vcsc") => "bounceInLeft", __("BounceInRight", "wt_vcsc") => "bounceInRight", __("BounceOut", "wt_vcsc") => "bounceOut", __("BounceOutUp", "wt_vcsc") => "bounceOutUp", __("BounceOutDown", "wt_vcsc") => "bounceOutDown", __("BounceOutLeft", "wt_vcsc") => "bounceOutLeft", __("BounceOutRight", "wt_vcsc") => "bounceOutRight", __("RotateIn", "wt_vcsc") => "rotateIn", __("RotateInUpLeft", "wt_vcsc") => "rotateInUpLeft", __("RotateInDownLeft", "wt_vcsc") => "rotateInDownLeft", __("RotateInUpRight", "wt_vcsc") => "rotateInUpRight", __("RotateInDownRight", "wt_vcsc") => "rotateInDownRight", __("RotateOut", "wt_vcsc") => "rotateOut", __("RotateOutUpLeft", "wt_vcsc") => "rotateOutUpLeft", __("RotateOutDownLeft", "wt_vcsc") => "rotateOutDownLeft", __("RotateOutUpRight", "wt_vcsc") => "rotateOutUpRight", __("RotateOutDownRight", "wt_vcsc") => "rotateOutDownRight", __("RollIn", "wt_vcsc") => "rollIn", __("RollOut", "wt_vcsc") => "rollOut", __("LightSpeedIn", "wt_vcsc") => "lightSpeedIn", __("LightSpeedOut", "wt_vcsc") => "lightSpeedOut" ),
			'description' => __('Select type of animation if you want this element to be animated when it enters into the browsers viewport. Note: Works only in modern browsers.', 'wt_vcsc'),
			'group' 	                    => __( 'WT_VC Extensions', 'wt_vcsc'),
		));
		vc_add_param("vc_row", array(
			"type"                          => "dropdown",
			"heading"                       => __("WT Animation Visible Type", "wt_vcsc"),
			"param_name"                    => "anim_type",
			"value"                         => array(__("Animate when element is visible", "wt_vcsc") => 'wt_animate_if_visible', __("Animate if element is almost visible", "wt_vcsc") => "wt_animate_if_almost_visible" ),
			"description"                   => __("Select when the type of animation should start for this element.", "wt_vcsc"),
			'group'                         => __('WT_VC Extensions', 'wt_vcsc')
		));		
		vc_add_param("vc_row", array(
			"type"                          => "textfield",
			"heading"                       => __("WT Animation Delay", "wt_vcsc"),
			"param_name"                    => "anim_delay",
			"description"                   => __("Here you can set a specific delay for the animation (miliseconds). Example: '100', '500', '1000'.", "wt_vcsc"),
			'group'                         => __('WT_VC Extensions', 'wt_vcsc')
		));	
		
		vc_add_param("vc_row", array(
			'type'                  		=> 'wt_loadfile',
			'heading'               		=> __( '', 'wt_vcsc' ),
			'param_name'            		=> 'el_file',
			'value'                 		=> '',
			'file_type'             		=> 'js',
			'file_path'             		=> 'wt-visual-composer-extend-element.min.js',
			'param_holder_class'            => 'wt_loadfile_field',
			'description'           		=> __( '', 'wt_vcsc' ),
			'group' 						=> __( 'WT_VC Extensions', 'wt_vcsc'),
		));
	}
?>