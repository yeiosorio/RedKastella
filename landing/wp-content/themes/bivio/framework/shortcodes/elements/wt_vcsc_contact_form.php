<?php

// File Security Check
if (!defined('ABSPATH')) die('-1');

/*
Register WhoaThemes shortcode.
*/

class WPBakeryShortCode_WT_contact_form extends WPBakeryShortCode {
	
	private $wt_sc;
	
	public function __construct($settings) {
        parent::__construct($settings);
		$this->wt_sc = new WT_VCSC_SHORTCODE;
	}
			
	protected function content($atts, $content = null) {
		
		extract( shortcode_atts( array(
			'fields'        => 'name,email,message',
			'required'      => 'name,email,message',
			'button_text'   => '',
			'email'         => get_bloginfo('admin_email'),
			'success'       => '',
			
			'el_class'      => '',
    		'css_animation' => '',
    		'anim_type'     => '',
    		'anim_delay'    => '',			
			'css'           => ''		
		), $atts ) );
		
		wp_enqueue_script('wt-validate');
		wp_enqueue_script('wt-validate-translation');
		
		$sc_class = 'wt_contact_form_sc';
		
		$id = mt_rand(9999, 99999);
		
        $fields      = array_map( 'trim', explode( ',', $fields ) );
        $required    = array_map( 'trim', explode( ',', $required ) );	
        $button_text = $button_text ? esc_html( $button_text ) : __("Submit", "wt_front");
		
		!empty($email) ? $email = esc_html( $email ) : '';		
		$email = str_replace('@','(at)',$email);
		$sitename = get_bloginfo('name');
		$siteurl =  home_url();
		
		if(!empty($success)){			
			$success = trim($success);
			$success = do_shortcode( $success );
			// $success = esc_textarea( $success );
		} else {
			$success = __('We received your message and we will get back to you as soon as possible. <br /> <strong>Thank You!</strong>','wt_front');
		}
		
		$el_class = $this->getExtraClass($el_class);		
		$css_class = apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $sc_class.$el_class.vc_shortcode_custom_css_class($css, ' '), $this->settings['base']);		
		$css_class .= $this->wt_sc->getWTCSSAnimationClass($css_animation,$anim_type);
		$anim_data = $this->wt_sc->getWTCSSAnimationData($css_animation,$anim_delay);
		
		$include_path = THEME_INCLUDES;
		$output = '';
		
		$output .= '<div class="wt_contact_form_wrap">';
		$output .= '<div class="success alert alert-success alert-dismissable" style="display:none;"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'.$success.'</div>';		
		$output .= '<form id="contact_form_'.$id.'" class="'.$css_class.'" action="'.$include_path.'/sendmail.php" method="post" role="form"'.$anim_data.'>';
		$output .= '<div class="row"><div class="fieldset">';
		$output .= '<div class="col-lg-4 col-md-4 col-sm-4">';
		
			foreach ( $fields as $index=>$field ) {
				if ( in_array( $field, $required ) ) {
					$req = ' *';
					$req_attr = ' required';
				} else {
					$req = '';
					$req_attr = '';
				};
				
				switch( $field ) {
					case 'name'    : $placeholder = __('Name','wt_front');
									 $output .= '<div class="form-name">';
									 $output .= '<label for="name" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="text" id="contact_name_'.$id.'" name="contact_name_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="3" tabindex="1" '.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'email'   : $placeholder = __('E-mail','wt_front');
									 $output .= '<div class="form-email">';
									 $output .= '<label for="email" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="email" id="contact_email_'.$id.'" name="contact_email_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" tabindex="2"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'subject' : $placeholder = __('Subject','wt_front');
									 $output .= '<div class="form-subject">';
									 $output .= '<label for="subject" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="text" id="contact_subject_'.$id.'" name="contact_subject_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="5" tabindex="3"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'phone'   : $placeholder = __('Phone','wt_front');
									 $output .= '<div class="form-phone">';
									 $output .= '<label for="phone" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="tel" id="contact_phone_'.$id.'" name="contact_phone_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="3" tabindex="4"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'website' : $placeholder = __('Website','wt_front');
									 $output .= '<div class="form-website">';
									 $output .= '<label for="website" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="url" id="contact_website_'.$id.'" name="contact_website_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="7" tabindex="5"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'country' : $placeholder = __('Country','wt_front');
									 $output .= '<div class="form-country">';
									 $output .= '<label for="country" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="text" id="contact_country_'.$id.'" name="contact_country_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="3" tabindex="6"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'city'    : $placeholder = __('City','wt_front');
									 $output .= '<div class="form-city">';
									 $output .= '<label for="city" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="text" id="contact_city_'.$id.'" name="contact_city_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="3" tabindex="7"'.$req_attr.'>';
									 $output .= '</div>'; break;
					case 'company' : $placeholder = __('Company','wt_front');
									 $output .= '<div class="form-company">';
									 $output .= '<label for="company" class="assistive-text">'.$placeholder.$req.'</label>';
									 $output .= '<input type="text" id="contact_company_'.$id.'" name="contact_company_'.$id.'" placeholder="'.$placeholder.$req.'" class="left-input text_input" value="" minlength="3" tabindex="8"'.$req_attr.'>';
									 $output .= '</div>'; break;
				}
				
			}
		
		$output .= '</div>';
		
		if ( in_array('message', $fields) ) {
			if ( in_array( 'message', $required ) ) {
				$req = ' *';
				$req_attr = ' required';
			} else {
					$req = '';
					$req_attr = '';
			};
			$placeholder = __('Message...','wt_front');
			$output .= '<div class="col-lg-6 col-md-6 col-sm-6">';
			$output .= '<div class="form-message">';
			$output .= '<label for="message" class="assistive-text">'.$placeholder.$req.'</label>';
			$output .= '<textarea name="contact_content_'.$id.'" class="text_area" placeholder="'.$placeholder.$req.'" minlength="5"'.$req_attr.'></textarea>';
			$output .= '</div>';
			$output .= '</div>';
		}
		
		$output .= '<div class="col-lg-2 col-md-2 col-sm-2">';
			$output .= '<a href="#" onclick="jQuery(\'#contact_form_'.$id.'\').submit();return false;" class="contact_button"><span>'.$button_text.'</span></a>';
			$output .= '<!--a href="#" class="reset-form">clear</a-->';
			$output .= '<div><input type="hidden" value="'.$id.'" name="contact_widget_id"/>';
			$output .= '<input type="hidden" value="'.$email.'" name="contact_to_'.$id.'"/>';
			$output .= '<input type="hidden" value="'.$sitename.'" name="contact_sitename_'.$id.'"/>';
			$output .= '<input type="hidden" value="'.$siteurl.'" name="contact_siteurl_'.$id.'"/></div>';
		$output .= '</div>';
		
		$output .= '</div></div> <!-- End fieldset -->';
		$output .= '</form>';
		$output .= '</div>';
		
        return $output; 
    }
	
}
	
/*
Register WhoaThemes shortcode within Visual Composer interface.
*/
	
if (function_exists('vc_map')) {

	$add_wt_sc_func             = new WT_VCSC_SHORTCODE;
	$add_wt_extra_class         = $add_wt_sc_func->getWTExtraClass();
	$add_wt_css_animation       = $add_wt_sc_func->getWTAnimations();
	$add_wt_css_animation_type  = $add_wt_sc_func->getWTAnimationsType();
	$add_wt_css_animation_delay = $add_wt_sc_func->getWTAnimationsDelay();
	
	vc_map( array(
		'name'          => __('WT Contact Form', 'wt_vcsc'),
		'base'          => 'wt_contact_form',
		'icon'          => 'wt_vc_ico_cform',
		'class'         => 'wt_vc_sc_cform',
		'category'      => __('by WhoaThemes', 'wt_vcsc'),
		'description'   => __('HTML5 contact form with validation', 'wt_vcsc'),
		'params'        => array(
			array(
				'type'          => 'checkbox',
				'heading'       => __('Form fields', 'wt_vcsc'),
				'admin_label'   => true,
				'param_name'    => 'fields',
				'value'         => array(
					__('Name', 'wt_vcsc')    => 'name',
					__('E-mail', 'wt_vcsc')  => 'email',
					__('Subject', 'wt_vcsc') => 'subject',
					__('Phone', 'wt_vcsc')   => 'phone',
					__('Website', 'wt_vcsc') => 'website',
					__('Country', 'wt_vcsc') => 'country',
					__('City', 'wt_vcsc')    => 'city',
					__('Company', 'wt_vcsc') => 'company',
					__('Message', 'wt_vcsc') => 'message'
				),
				'description'   => __('Select form fields for your contact form.', 'wt_vcsc')
			),
			array(
				'type'          => 'checkbox',
				'heading'       => __('Required fields', 'wt_vcsc'),
				'param_name'    => 'required',
				'value'         => array(
					__('Name', 'wt_vcsc')    => 'name',
					__('E-mail', 'wt_vcsc')  => 'email',
					__('Subject', 'wt_vcsc') => 'subject',
					__('Phone', 'wt_vcsc')   => 'phone',
					__('Website', 'wt_vcsc') => 'website',
					__('Country', 'wt_vcsc') => 'country',
					__('City', 'wt_vcsc')    => 'city',
					__('Company', 'wt_vcsc') => 'company',
					__('Message', 'wt_vcsc') => 'message'
				),
				'description'   => __('Select required fields for your contact form.', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Submit button text', 'wt_vcsc'),
				'param_name'    => 'button_text',
				'value'         => __('Submit', 'wt_vcsc'),
				'description'   => __('Select button text to display. Default: \'Submit\'', 'wt_vcsc')
			),
			array(
				'type'          => 'textfield',
				'heading'       => __('Email', 'wt_vcsc'),
				'param_name'    => 'email',
				'description'   => __('Here you can set the email where you want to receive the messages. Default email is the admin one.','wt_vcsc')
			),
			array(
				'type'          => 'textarea',
				'heading'       => __('Success Text', 'wt_vcsc'),
				'param_name'    => 'success',
				'description'   => __('This is the \'success message\' to be displayed after the messages are sent.', 'wt_vcsc')
			),
			
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