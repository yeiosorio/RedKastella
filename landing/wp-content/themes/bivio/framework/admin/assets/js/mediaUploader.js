var mediaUploader = {
	OptionUploaderUseThisImage : function(id,target){
		var win = window.dialogArguments || opener || parent || top;

		win.theme.themeOptionGetImage(id,target);
		win.tb_remove();
	}
}

jQuery(document).ready( function($) {
	if(location.search.indexOf('option_image_upload') != -1){
		jQuery('#media-upload #filter').append('<input type="hidden" value="1" name="option_image_upload">');
		jQuery('#media-upload #gallery-settings').remove();
	}
});

function optionImagePrepareMediaItem(fileObj, serverData) {
	var f = ( typeof shortform == 'undefined' ) ? 1 : 2, item = jQuery('#media-item-' + fileObj.id);
	// Move the progress bar to 100%
	jQuery('.bar', item).remove();
	jQuery('.progress', item).hide();

	try {
		if ( typeof topWin.tb_remove != 'undefined' )
			topWin.jQuery('#TB_overlay').click(topWin.tb_remove);
	} catch(e){}

	// Old style: Append the HTML returned by the server -- thumbnail and form inputs
	if ( isNaN(serverData) || !serverData ) {
		item.append(serverData);
		prepareMediaItemInit(fileObj);
	}
	// New style: server data is just the attachment ID, fetch the thumbnail and form html from the server
	else {
		if(typeof swfu.settings.post_params.target !== 'undefined'){
			item.load('async-upload.php', {attachment_id:serverData, fetch:f,option_image_upload:1,target:swfu.settings.post_params.target}, function(){prepareMediaItemInit(fileObj);updateMediaForm()});
		}else{
			item.load('async-upload.php', {attachment_id:serverData, fetch:f,option_image_upload:1}, function(){prepareMediaItemInit(fileObj);updateMediaForm()});
		}
	}
}