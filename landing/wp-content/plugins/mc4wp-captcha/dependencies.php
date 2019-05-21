<?php

// is MailChimp for WordPress 3.0 installed?
if( ! defined( 'MC4WP_VERSION' ) || version_compare( MC4WP_VERSION, '3.0', '<' ) ) {
	return false;
}

// is Captcha by BestWebSoft installed?
// https://wordpress.org/plugins/captcha/
if( ! function_exists( 'cptch_display_captcha_custom' ) ) {
	return false;
}

// finally, return true
return true;
