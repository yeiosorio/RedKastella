<?php defined( 'ABSPATH' ) or exit; ?>

<tr valign="top">
	<th scope="row"><label for="mc4wp_form_text_invalid_captcha"><?php _e( 'Invalid CAPTCHA', 'mailchimp-for-wp' ); ?></label></th>
	<td>
		<input type="text" class="widefat" id="mc4wp_form_text_invalid_captcha" name="mc4wp_form[messages][invalid_captcha]" value="<?php echo esc_attr( $message ); ?>" />
		<p class="help"><?php _e( 'The text that shows when the CAPTCHA was not completed (correctly).', 'mailchimp-for-wp' ); ?></p>
	</td>
</tr>