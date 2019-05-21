<?php

function wt_option_image_upload_tabs($tabs) {
	unset($tabs['type_url']);
    return $tabs;
}

function wt_option_image_form_url($form_action_url, $type){
	$form_action_url = $form_action_url.'&option_image_upload=1&target='.$_GET['target'];
	return $form_action_url;
}

function wt_disable_option_flash_uploader($flash){
	return false;
}

function wt_option_image_attachment_fields_to_edit($form_fields, $post){

	unset($form_fields['align']);
	unset($form_fields['image-size']);
	$filename = basename( $post->guid );
	$attachment_id = $post->ID;
	if ( current_user_can( 'delete_post', $attachment_id ) ) {
		if ( !EMPTY_TRASH_DAYS ) {
			$delete = "<a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Delete Permanently' , 'wt_admin' ) . '</a>';
		} elseif ( !MEDIA_TRASH ) {
			$delete = "<a href='#' class='del-link' onclick=\"document.getElementById('del_attachment_$attachment_id').style.display='block';return false;\">" . __( 'Delete' , 'wt_admin' ) . "</a>
			 <div id='del_attachment_$attachment_id' class='del-attachment' style='display:none;'>" . sprintf( __( 'You are about to delete <strong>%s</strong>.' , 'wt_admin' ), $filename ) . "
			 <a href='" . wp_nonce_url( "post.php?action=delete&amp;post=$attachment_id", 'delete-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='button'>" . __( 'Continue' , 'wt_admin' ) . "</a>
			 <a href='#' class='button' onclick=\"this.parentNode.style.display='none';return false;\">" . __( 'Cancel' , 'wt_admin' ) . "</a>
			 </div>";
		} else {
			$delete = "<a href='" . wp_nonce_url( "post.php?action=trash&amp;post=$attachment_id", 'trash-attachment_' . $attachment_id ) . "' id='del[$attachment_id]' class='delete'>" . __( 'Move to Trash' , 'wt_admin' ) . "</a>
			<a href='" . wp_nonce_url( "post.php?action=untrash&amp;post=$attachment_id", 'untrash-attachment_' . $attachment_id ) . "' id='undo[$attachment_id]' class='undo hidden'>" . __( 'Undo' , 'wt_admin' ) . "</a>";
		}
	} else {
		$delete = '';
	}
	$form_fields['buttons'] = array( 
		'tr' => "\t\t<tr><td></td><td><input type='button' class='button' onclick='mediaUploader.OptionUploaderUseThisImage(".$post->ID.",\"". $_REQUEST['target']."\")' value='" . __( 'Use this' , 'wt_admin' ) . "' /> $delete</td></tr>\n"
	);
	return $form_fields;
}
function wt_option_image_swfupload_post_params($params){
	$params['option_image_upload']=1;
	$params['target']=$_REQUEST['target'];
	return $params;
}
function wt_option_image_upload_post_params($params){
	$params['option_image_upload']=1;
	$params['target']=$_REQUEST['target'];
	unset($params['short']);
	return $params;
}

function wt_option_image_upload_init(){
	add_filter('flash_uploader', 'wt_disable_option_flash_uploader');
	add_filter('media_upload_tabs', 'wt_option_image_upload_tabs');
	add_filter('attachment_fields_to_edit', 'wt_option_image_attachment_fields_to_edit', 10, 2);
	add_filter('media_upload_form_url', 'wt_option_image_form_url', 10, 2);
	wp_enqueue_script('theme-mediaUploader', THEME_ADMIN_ASSETS_URI . '/js/mediaUploader.js');
	add_filter('upload_post_params', 'wt_option_image_upload_post_params');
	add_filter('swfupload_post_params', 'wt_option_image_swfupload_post_params');
	//add_filter('swfupload_success_handler','option_image_swfupload_success_handler');
}

if (isset($_GET['option_image_upload']) || isset($_POST['option_image_upload'])) {
	add_action('admin_init', 'wt_option_image_upload_init');
}

//option insert image ajax action callback
function option_get_image_action_callback() {
	$original = wp_get_attachment_image_src($_POST['id'],'full');
	if (! empty($original)) {
		echo $original[0];
	} else {
		die(0);
	}
	die();
}
add_action('wp_ajax_theme-option-get-image', 'option_get_image_action_callback');
