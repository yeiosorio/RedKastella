jQuery.noConflict();


function copy_slider_row() {
	var $multitable_wrap = jQuery('.multitables');
	$multitable_wrap.each(function() {
		var $add_next = jQuery(this).find('.add_row');
		var $del_this = jQuery(this).find('.del_row');
		var $current_table = jQuery(this);
		
		var $slideshow_type = jQuery('#slideshow_type');
		switch($slideshow_type.val()){
			case 'flex':
				var $flex_count = jQuery(this).find('#flex_custom_slider_count');
				
				$add_next.unbind('click').bind('click',function() {
					$flex_count.val(parseInt($flex_count.val())+1);
					$current_number = $flex_count.val();
					$newclone = jQuery('.flex .clone_row').clone().insertBefore(jQuery('.flex .clone_row'));
					$newclone.removeClass('hidden').removeClass('clone_row');
					
					correct_numbers($current_table)
					copy_slider_row();
					table_sort_test();
					
					return false;
					});
			
				$del_this.bind('click',function() {
					$flex_count.val(parseInt($flex_count.val())-1);
					jQuery(this).parents('.multitable').remove();
					correct_numbers($current_table);
					return false;
					});
					
				break;
			case 'nivo':
				var $nivo_count = jQuery(this).find('#nivo_custom_slider_count');
				
				$add_next.unbind('click').bind('click',function() {
					$nivo_count.val(parseInt($nivo_count.val())+1);
					$current_number = $nivo_count.val();
					$newclone = jQuery('.nivo .clone_row').clone().insertBefore(jQuery('.nivo .clone_row'));
					$newclone.removeClass('hidden').removeClass('clone_row');
					
					correct_numbers($current_table)
					copy_slider_row();
					table_sort_test();
					
					return false;
					});
			
				$del_this.bind('click',function() {
					$nivo_count.val(parseInt($nivo_count.val())-1);
					jQuery(this).parents('.multitable').remove();
					correct_numbers($current_table);
					return false;
					});
					
				break;
			case 'anything':
				var $anything_count = jQuery(this).find('#anything_custom_slider_count');
				
				$add_next.unbind('click').bind('click',function() {
					$anything_count.val(parseInt($anything_count.val())+1);
					$current_number = $anything_count.val();
					$newclone = jQuery('.anything .clone_row').clone().insertBefore(jQuery('.anything .clone_row'));
					$newclone.removeClass('hidden').removeClass('clone_row');
					
					correct_numbers($current_table)
					copy_slider_row();
					table_sort_test();
					
					return false;
					});
			
				$del_this.bind('click',function() {
					$anything_count.val(parseInt($anything_count.val())-1);
					jQuery(this).parents('.multitable').remove();
					correct_numbers($current_table);
					return false;
					});
					
				break;
			case 'cycle':
				var $cycle_count = jQuery(this).find('#cycle_custom_slider_count');
				
				$add_next.unbind('click').bind('click',function() {
					$cycle_count.val(parseInt($cycle_count.val())+1);
					$current_number = $cycle_count.val();
					$newclone = jQuery('.cycle .clone_row').clone().insertBefore(jQuery('.cycle .clone_row'));
					$newclone.removeClass('hidden').removeClass('clone_row');
					
					correct_numbers($current_table)
					copy_slider_row();
					table_sort_test();
					
					return false;
					});
			
				$del_this.bind('click',function() {
					$cycle_count.val(parseInt($cycle_count.val())-1);
					jQuery(this).parents('.multitable').remove();
					correct_numbers($current_table);
					return false;
					});
					
				break;
		}		
	});
	
}

function correct_numbers($current_table) {
	
	
	$current_table.find('.multitable').each(function(i){
		var $current_sub_table = jQuery(this);
		$current_sub_table.find('.changenumber').html(i+1);
		
		$current_sub_table.find('.correct_num').each(function(){
				var $multiply_me = '';
				var $newname = jQuery(this).attr('name').replace(/\d+/,i);
				if (jQuery(this).hasClass('multiply_me')) $multiply_me = 'multiply_me';
				jQuery(this).attr({'name': $newname,'id': $newname});
			});
		

		$current_sub_table.find('.upload_number').each(function(){			
				var $multiply_me = '';
				var $uploadname = jQuery(this).attr('id').replace(/\d+/,i);
				if (jQuery(this).hasClass('multiply_me')) $multiply_me = 'multiply_me';
				var version = jQuery('.theme-options-page').data('version');            
				var $slideshow_type = jQuery('#slideshow_type');
				switch($slideshow_type.val()){
					
					case 'flex':
						if (version == "gt3_5") {
							jQuery(this).attr({'id': $uploadname, 'data-target': "flex_custom_slider_url_" + i +""}); }
						else {
							jQuery(this).attr({'id': $uploadname, 'href': "media-upload.php?&target=flex_custom_slider_url_" + i + "&option_image_upload=1&type=image&TB_iframe=1&width=640&height=544"});	}
						break;
					case 'nivo':
						if (version == "gt3_5") {
							jQuery(this).attr({'id': $uploadname, 'data-target': "nivo_custom_slider_url_" + i +""}); }
						else {
							jQuery(this).attr({'id': $uploadname, 'href': "media-upload.php?&target=nivo_custom_slider_url_" + i + "&option_image_upload=1&type=image&TB_iframe=1&width=640&height=544"});	}
						break;
					case 'anything':
						if (version == "gt3_5") {
							jQuery(this).attr({'id': $uploadname, 'data-target': "anything_custom_slider_url_" + i +""}); }		
						else {
							jQuery(this).attr({'id': $uploadname, 'href': "media-upload.php?&target=anything_custom_slider_url_" + i + "&option_image_upload=1&type=image&TB_iframe=1&width=640&height=544"});	}
						break;
					case 'cycle':
						if (version == "gt3_5") {
							jQuery(this).attr({'id': $uploadname, 'data-target': "cycle_custom_slider_url_" + i +""}); }	
						else {
							jQuery(this).attr({'id': $uploadname, 'href': "media-upload.php?&target=cycle_custom_slider_url_" + i + "&option_image_upload=1&type=image&TB_iframe=1&width=640&height=544"});	}			
						break;
				}	
			});
		
		$current_sub_table.find('.theme-option-image-preview').each(function(){
			var $multiply_me = '';
			var $previewName = jQuery(this).attr('id').replace(/\d+/,i);
			if (jQuery(this).hasClass('multiply_me')) $multiply_me = 'multiply_me';
			jQuery(this).attr({'id': $previewName});				
		});					
	});
}	

function table_sort_test() {
		jQuery(".table_sort").tableDnD({
		    onDragClass: "myDragClass",
		    onDrop: function(table, row) {
				
				var $slideshow_type = jQuery('#slideshow_type');
				
				switch($slideshow_type.val()){
					case 'flex':
						var $multitable_wrap = jQuery('.flex .multitable');
						break;
					case 'nivo':
						var $multitable_wrap = jQuery('.nivo .multitable');
						break;
					case 'anything':
						var $multitable_wrap = jQuery('.anything .multitable');		
						break;
					case 'cycle':
						var $multitable_wrap = jQuery('.cycle .multitable');		
						break;
				}
					
				$multitable_wrap.each(function(i) {
					var $current_sub_table = jQuery(this);
					$current_sub_table.find('.correct_num').each(function(){
							var $newname = jQuery(this).attr('name').replace(/\d+/,i);
							jQuery(this).attr({'name': $newname,'id': $newname, 'class': $newname + " correct_num"});
						});
					});
		    },
			onDragStart: function(table, row) {	
			}
		});
}

jQuery(document).ready(function(){
table_sort_test();
copy_slider_row();
});

var theme = {
	optionsMultidropdown : function() {
		var wrap = jQuery(".multidropdown-wrap");

		wrap.each(function() {
			var selects = jQuery(this).children('select');
			var field = jQuery(this).siblings('input:hidden');
			field.val("");
			var name = field.attr("name");
			selects.each(function(i) {
				if (jQuery(this).val()) {
					if (field.val()) {
						field.val(field.val() + ',' + jQuery(this).val());
					} else {
						field.val(jQuery(this).val());
					}
				}
				jQuery(this).attr('id', name + '_' + i);
				jQuery(this).attr('name', name + '_' + i);

				jQuery(this).unbind('change').bind('change',function() {
					if (jQuery(this).val() && selects.length == i + 1) {
						jQuery(this).clone().val("").appendTo(wrap);
					} else if (!(jQuery(this).val())
							&& !(selects.length == i + 1)) {
						jQuery(this).remove();
					}
					theme.optionsMultidropdown();
				});
			})
		})
	},

	optionSuperlink : function() {
		var wrap = jQuery(".superlink-wrap");
		wrap.each(function(){
			var field = jQuery(this).siblings('input:hidden');
			var selector = jQuery(this).siblings('select');
			var name = field.attr('name');
			var items = jQuery(this).children();
			selector.change(function(){
				items.hide();
				jQuery("#"+name+"_"+jQuery(this).val()).show();
				field.val('');
			});
			items.change(function(){
				field.val(selector.val()+'||'+jQuery(this).val());
			})
		})
		
		
	},
	uploaderInit : function(){
		jQuery('.theme-upload-button').each(function(){
			
		});	
	},
	
	themeOptionGetImage : function(attachment_id,target){
		
		jQuery.post(ajaxurl, {
			action:'theme-option-get-image',
			id: attachment_id, 
			cookie: encodeURIComponent(document.cookie)
		}, function(src){
			if ( src == '0' ) {
				alert( 'Could not use this image. Try a different attachment.' );
			} else {
				jQuery("#"+target).val(src);
				var imgContainer = 	"#"+target+"_preview";					
								
				// if ( imgContainer.indexOf("custom_slider_url_") != -1 || imgContainer.indexOf("sc_") != -1 ) {
					var image = src;
					var arr = image.split('.');
										
					var imageName = '';    // name
					jQuery.each(arr, function(i, val) {
						if (i != arr.length-1) {
							imageName += val;							
							if (i != arr.length-2) {
								imageName += '.';	
							}
						}
					});
					
					var imageExt = arr[arr.length-1];    // extension
					var imageWidth = 150;
					var imageHeight = 150;
					// Create thumbnail
					var newSrc = imageName + '-' + imageWidth + 'x' + imageHeight + '.' + imageExt;
					if ( jQuery("#"+target+"_preview").hasClass("no_crop") ) {
						jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+src+'"><img src="'+image+'"/></a>');
					} else {
						jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+src+'"><img src="'+newSrc+'"/></a>');
					}
					
					if (imgContainer.indexOf("sc_") != -1) {
						jQuery("#"+target+"_preview").html('<img src="'+newSrc+'"/>');	
					}					
				/*
				}				
				else {
					jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+src+'"><img src="'+src+'"/></a>');
					
				}
				*/											
			}
		});
	}
}
jQuery.fn.exists = function(){return jQuery(this).length>0;}

function thumbnails(img) {	
	jQuery(img).each(function() {			
		var image = jQuery(this).find('img');
		
		if (jQuery(image).exists()) {
			
			var src = image.attr('src');
			var arr = src.split('.');
			
			var imageName = '';    // name
			jQuery.each(arr, function(i, val) {
				if (i != arr.length-1) {
					imageName += val;							
					if (i != arr.length-2) {
						imageName += '.';	
					}
				}
			});
			
			var imageExt = arr[arr.length-1];    // extension
			
			var imageWidth = 150;
			var imageHeight = 150;
			// Create thumbnail
			var newSrc = imageName + '-' + imageWidth + 'x' + imageHeight + '.' + imageExt;
					
			jQuery(image).attr({'src': newSrc});
		}					
	});	
}

jQuery(document).ready( function($) {
	// thumbnails('.theme-option-image-preview, .image-preview');
	
	$("#wpwrap").fitVids();	
	
	$('.option_help a').tooltip({
		position: "top left",
		effect: 'fade',
		offset: [-15, 0],
		relative: true, 
		opacity: 0.8, 
		tipClass: 'tooltip'
    });	
	
	// showing the selected value   
    $('.wt_select_wrapp select').live('change', function() {
    	var el = $(this);
    	el.next('.wt_option_selected').text(el.find('option:selected').text());
    });
		
	$('.meta-box-item a.switch').click(function(event){
		jQuery(this).parent().siblings('.description').toggle();
		event.preventDefault();
	});
	theme.optionsMultidropdown();
	theme.uploaderInit();
	theme.optionSuperlink();
	
	$(".range-input-wrap :range").rangeinput(); //enable range input
		
	$.tools.validator.addEffect("option", function(errors, event) {
		// add new ones
		$.each(errors, function(index, error) {
			var input = error.input;
			input.addClass("invalid");
			var msg = input.next('.validator-error').empty();
			$.each(error.messages, function(i, m) {
				$("<span/>").html(m).appendTo(msg);			
			});
		});
		
	// the effect does nothing when all inputs are valid	
	}, function(inputs)  {
		inputs.removeClass("invalid").each(function() {
			$(this).next('.validator-error').empty();
		});
	});
	$(".validator-wrap :input").validator({effect:'option'});
	/*
	//mColorPicker setting
	$.fn.mColorPicker.init.showLogo = false;
	$.fn.mColorPicker.defaults.imageFolder = theme_admin_assets_uri + "/images/mColorPicker/";
	*/
	
	$('.color-input-wrap input[type="text"]').colorInput({format:'rgba'});
	
	$('.toggle-button:checkbox').each(function(){
		if(!$(this).parents().is('.shortcode_wrap')){
			if($(this).parents('.postbox').is('.closed')){
				var button = $(this);
				
				$(this).parents('.postbox').children('.hndle,.handlediv').bind('clickoutside',function(e){
					button.iphoneStyle();
				});
			}else{
				$(this).iphoneStyle();
			}
		}
	});
	$('select.tri-toggle-button').each(function(){
		if(!$(this).parents().is('.shortcode_wrap')){
			if($(this).parents('.postbox').is('.closed')){
				var button = $(this);
				
				$(this).parents('.postbox').children('.hndle,.handlediv').bind('clickoutside',function(e){
					button.iphoneStyleTriToggle();
				});
			}else{
				$(this).iphoneStyleTriToggle();
			}
		}
	});	
	
});

/* intro type */
jQuery(document).ready( function($) {
	jQuery('.meta-box-item select[name="_intro_type"]').change(function(event){
		var groups = jQuery(this).parents('.meta-box-item').siblings('.intro_type').hide();
		
		switch(jQuery(this).val()){
			case 'default':
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'title':
				groups.filter("#intro_title").show();
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'custom':
				groups.filter("#intro_text").show();
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'title_custom':
				groups.filter("#intro_title").show();
				groups.filter("#intro_text").show();
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'slideshow':
				groups.filter("#intro_slideshow").show();
				break;
			case 'static_image':
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'static_video':
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
			case 'disable':
				jQuery("#slideshow_layerS").hide();
				jQuery("#slideshow_rev").hide();
				break;
		}
	}).trigger('change');
});

/* background type */
jQuery(document).ready( function($) {
	jQuery('.meta-box-item select[name="_bg_type"]').change(function(event){
		var groups = jQuery(this).parents('.meta-box-item').siblings('._bg_type').hide();
		
		switch(jQuery(this).val()){
			case 'pattern':
				jQuery("#pattern").show();
				jQuery("#parallax").hide();
				jQuery("#cover").hide();
				jQuery("#video").hide();
				jQuery("#color").hide();
				break;
			case 'parallax':
				jQuery("#parallax").show();
				jQuery("#pattern").hide();
				jQuery("#cover").hide();
				jQuery("#video").hide();
				jQuery("#color").hide();
				break;
			case 'cover':
				jQuery("#cover").show();
				jQuery("#parallax").hide();
				jQuery("#pattern").hide();
				jQuery("#video").hide();
				jQuery("#color").hide();
				break;
			case 'video':
				jQuery("#video").show();
				jQuery("#pattern").hide();
				jQuery("#parallax").hide();
				jQuery("#cover").hide();
				jQuery("#color").hide();
				break;
			case 'color':
				jQuery("#color").show();
				jQuery("#pattern").hide();
				jQuery("#parallax").hide();
				jQuery("#cover").hide();
				jQuery("#video").hide();
				break;
		}
	}).trigger('change');
});


jQuery(document).ready( function($) {
	jQuery('[name="_enable_fullcontact"]').change(function(){
		var fullcontact = jQuery(this.checked);
		jQuery('#fullcontact_gmap').each(function(i){
			if (jQuery(this.checked)) {
				jQuery(this).show();
			}
			else {
				jQuery(this).hide();
			}				
		});
	});	
});
/* thumbnail metabox */
jQuery(document).ready( function($) {
	jQuery('.meta-box-item select[name="_thumbnail_type"]').change(function(event){
		var groups = jQuery(this).parents('.meta-box-item').siblings('.featured_type').hide();
	
		switch(jQuery(this).val()){
			case 'tplayer':
				groups.filter("#thumbnail_player").show();
				break;
			case 'tslide':
				groups.filter("#thumbnail_slide").show();
				break;
		}
	}).trigger('change');
});

/* portfolio metabox */
jQuery(document).ready( function($) {
	jQuery('.meta-box-item select[name="_portfolio_type"]').change(function(event){
		var groups = jQuery(this).parents('.meta-box-item').siblings('.portfolio_type').hide();
		
		switch(jQuery(this).val()){
			case 'image':
				groups.filter("#portfolio_image").show();
				break;
			case 'video':
				groups.filter("#portfolio_video").show();
				break;
			case 'doc':
				groups.filter("#portfolio_document").show();
				break;
			case 'link':
				groups.filter("#portfolio_link").show();
				break;
		}
	}).trigger('change');
});

/* rev slider & layer slider metabox */
jQuery(document).ready( function($) {
	jQuery('.meta-box-group select[name="_slideshow_type"]').change(function(event){
		var groups = jQuery(this).parents('.meta-box-group').siblings('.slideshow_type').hide();
		
		switch(jQuery(this).val()){
			case 'rev':
				groups.filter("#slideshow_rev").show();
				break;
			case 'layerS':
				groups.filter("#slideshow_layerS").show();
				break;
		}
	}).trigger('change');
})

/* slideshow type */
jQuery(document).ready( function($) {
	jQuery('#slideshow_type').change(function(event){
		switch(jQuery(this).val()){
			case 'flex': 
				jQuery(".flexSwitch").show();
				jQuery(".nivoSwitch").hide();
				jQuery(".anythingSwitch").hide();
				jQuery(".cycleSwitch").hide();
				jQuery(".revSwitch").hide();
				jQuery(".layerSSwitch").hide();
				break;
			case 'cycle':
				jQuery(".cycleSwitch").show();
				jQuery(".flexSwitch").hide();
				jQuery(".nivoSwitch").hide();
				jQuery(".anythingSwitch").hide();
				jQuery(".revSwitch").hide();
				jQuery(".layerSSwitch").hide();
				break;
			case 'nivo':
				jQuery(".nivoSwitch").show();
				jQuery(".flexSwitch").hide();
				jQuery(".anythingSwitch").hide();
				jQuery(".cycleSwitch").hide();
				jQuery(".revSwitch").hide();
				jQuery(".layerSSwitch").hide();
				break;
			case 'anything':
				jQuery(".anythingSwitch").show();
				jQuery(".flexSwitch").hide();
				jQuery(".nivoSwitch").hide();
				jQuery(".cycleSwitch").hide();
				jQuery(".revSwitch").hide();
				jQuery(".layerSSwitch").hide();
				break;
			case 'rev':
				jQuery(".revSwitch").show();
				jQuery(".layerSSwitch").hide();
				jQuery(".cycleSwitch").hide();
				jQuery(".flexSwitch").hide();
				jQuery(".nivoSwitch").hide();
				jQuery(".anythingSwitch").hide();
				break;
			case 'layerS':
				jQuery(".layerSSwitch").show();
				jQuery(".revSwitch").hide();
				jQuery(".cycleSwitch").hide();
				jQuery(".flexSwitch").hide();
				jQuery(".nivoSwitch").hide();
				jQuery(".anythingSwitch").hide();
				break;
		}
	}).trigger('change');
});

/* backgroud type */
jQuery(document).ready( function($) {
	jQuery('#background_type').change(function(event){
		switch(jQuery(this).val()){
			case 'pattern': 
				jQuery(".patternSwitch").show();
				jQuery(".imageSwitch").hide();
				jQuery(".slideshowSwitch").hide();
				jQuery(".videoSwitch").hide();
				break;
			case 'image_bg':
				jQuery(".imageSwitch").show();
				jQuery(".patternSwitch").hide();
				jQuery(".slideshowSwitch").hide();
				jQuery(".videoSwitch").hide();
				break;
			case 'slideshow':
				jQuery(".slideshowSwitch").show();
				jQuery(".imageSwitch").hide();
				jQuery(".patternSwitch").hide();
				jQuery(".videoSwitch").hide();
				break;
			case 'video':
				jQuery(".videoSwitch").show();
				jQuery(".slideshowSwitch").hide();
				jQuery(".imageSwitch").hide();
				jQuery(".patternSwitch").hide();
				break;
		}
	}).trigger('change');
});

/* media type */
jQuery(document).ready( function($) {
	jQuery('#media_type').change(function(event){
		switch(jQuery(this).val()){
			case 'videos': 
				jQuery(".videosSwitch").show();
				jQuery(".audiosSwitch").hide();
				break;
			case 'audios':
				jQuery(".audiosSwitch").show();
				jQuery(".videosSwitch").hide();
				break;
		}
	}).trigger('change');
});


/* ----- Tabs ----- */

jQuery(document).ready( function($) {

	var $navigation = $('.whoathemes_options_tabs');

	if( $navigation.length ) {
		function navFunct( navTabs ) {
			navTabs.each(function() {
				var $this  = $(this);			
				$this.nextAll().hide();	
				
				var activetab = '';
				if (typeof(localStorage) != 'undefined' ) {
					activetab = localStorage.getItem("activetab");
				}
								
				if (activetab != '' && $(activetab + '-tab').length ) {
					$(activetab + '-tab').addClass('nav-tab-active');
					$(activetab).addClass('wt-active-group');
				} else {
					$this.find('.nav-tab-wrapper a:first').addClass('nav-tab-active');
				} 				
				if (navTabs.length > 1 && !$this.find('.nav-tab-active').length) {
					$this.find('.nav-tab-wrapper a:first').addClass('nav-tab-active');
				}									
								
				if (activetab != '' && $(activetab).length) {
					$(activetab).fadeIn();
				} else {
					$this.next().fadeIn();
				} 				
				if (navTabs.length > 1 && !$this.siblings('.wt-active-group').length) {
					$this.next().fadeIn();
				}
				
				$this.find('.nav-tab-wrapper a').click(function(evt) {
					$this.find('.nav-tab-wrapper a').removeClass('nav-tab-active');
					$(this).addClass('nav-tab-active').blur();
					
					var clicked_group = $(this).attr('href');
					if (typeof(localStorage) != 'undefined' ) {
						localStorage.setItem("activetab", $(this).attr('href'));
					}
					
					$this.nextAll().hide();
					$(clicked_group).fadeIn();					
					$(clicked_group).find('.elastic').elastic();
					evt.preventDefault();					
				}); 
				
			});
		}
		navFunct( $navigation );		
	}		
}); 


/* more info link */
jQuery(document).ready( function($) {
	jQuery('a.more_info').click(function(event){
		var $link = jQuery(this);
		var $moreInfo = $link.next();
		
		$moreInfo.slideToggle("fast");
		if ($link.text() == "[+] more info") {
			$link.text("[-] less info");
		} else {
			$link.text("[+] more info");
		}
		return false;
	});
}); 

/* chosen select/multiselect */
jQuery(document).ready( function($) {
	$(".chzn-select").chosen();
	$('textarea.elastic').elastic();
});
/* upload */
	
jQuery(document).ready(function($){
    $('.theme-upload-button').live('click', function(e) {
        e.preventDefault();
        upload_image($(this));
        return false; 
    });
});
function upload_image(el){
	var version = jQuery('.theme-options-page').data('version'); 
	var versionMeta = jQuery('.meta-box-group').data('version');             
	if (version == "gt3_5" || versionMeta == "gt3_5") {
    var $ = jQuery;
    var file_frame;
    var button = $(el);
	var target = button.data('target');
    if (file_frame) {
        file_frame.open();
        return;
    }

    //Extend the wp.media object
    file_frame = wp.media.frames.file_frame = wp.media({
        
        multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    file_frame.on('select', function() {
        attachment = file_frame.state().get('selection').first().toJSON();
			$("#"+target).val(''+attachment.url+'');
				var imgContainer = 	"#"+target+"_preview";					
				var image = attachment.url;
				var arr = image.split('.');
									
				var imageName = '';    // name
				jQuery.each(arr, function(i, val) {
					if (i != arr.length-1) {
						imageName += val;							
						if (i != arr.length-2) {
							imageName += '.';	
						}
					}
				});
				
				var imageExt = arr[arr.length-1];    // extension
				var imageWidth = 150;
				var imageHeight = 150;
				
				// Create thumbnail
				var newSrc = imageName + '-' + imageWidth + 'x' + imageHeight + '.' + imageExt;	
				if ( jQuery("#"+target+"_preview").hasClass("no_crop") ) {
					jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+attachment.url+'"><img src="'+image+'"/></a>');
				} else {
					jQuery("#"+target+"_preview").html('<a class="thickbox" href="'+attachment.url+'"><img src="'+newSrc+'"/></a>');
				}
				
				if (imgContainer.indexOf("sc_") != -1) {
					jQuery("#"+target+"_preview").html('<img src="'+newSrc+'"/>');	
				}	
    });
    //Open the uploader dialog
    file_frame.open();		
	}
};
jQuery(document).ready(function($){
	$('.theme-upload-buttons').live('click', function(){
		$content = $(this).parent().parent();
		$content.find('.upload-value').val('');
		$content.find('.image-preview').html('');
		
		$multitable = $(this).closest('.multitable');
		$multitable.find('.theme-option-image-preview').html('');
        return false; 
	});
});
	
/*
 * jQuery outside events - v1.1 - 3/16/2010
 * http://benalman.com/projects/jquery-outside-events-plugin/
 * 
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
// (function($,c,b){$.map("click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup".split(" "),function(d){a(d)});a("focusin","focus"+b);a("focusout","blur"+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+"."+e+"-special-event";$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})(jQuery,document,"outside");