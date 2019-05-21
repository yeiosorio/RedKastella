<?php
/*
Plugin Name: MailChimp for WordPress - Captcha
Plugin URI: https://mc4wp.com/#utm_source=wp-plugin&utm_medium=mailchimp-top-bar&utm_campaign=plugins-page
Description: Adds a captcha field to MailChimp for WordPress sign-up forms.
Version: 1.0.2
Author: ibericode
Author URI: https://ibericode.com/
Text Domain: mc4wp-captcha
Domain Path: /languages
License: GPL v3

MailChimp for WordPress - Captcha
Copyright (C) 2015, Danny van Kooten, danny@ibericode.com

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * Load the plugin files
 *
 * @return bool
 */
function __bootstrap_mc4wp_captcha() {

	// check if dependencies are met
	$dependencies_met = include dirname( __FILE__ ) . '/dependencies.php';
	if( ! $dependencies_met ) {
		return false;
	}

	require_once dirname( __FILE__ ) . '/functions.php';

	add_filter( 'mc4wp_form_errors', 'mc4wp_captcha_form_errors', 10, 2 );
	add_filter( 'mc4wp_form_messages', 'mc4wp_captcha_register_form_message' );
	add_filter( 'mc4wp_dynamic_content_tags_form', 'mc4wp_captcha_add_dynamic_content_tags' );
	add_filter( 'mc4wp_form_ignored_field_names', 'mc4wp_captcha_ignored_fields' );
	add_action( 'mc4wp_admin_form_after_messages_settings_rows', 'mc4wp_captcha_add_form_message_settings_row', 10, 2 );

    return true;
}

add_action( 'plugins_loaded', '__bootstrap_mc4wp_captcha', 30 );

