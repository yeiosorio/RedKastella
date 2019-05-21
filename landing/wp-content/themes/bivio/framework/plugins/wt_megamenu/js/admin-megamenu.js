jQuery(document).ready(function($){

	var wt_menu = {
		reTimeout: false,

		recalc : function() {
			$menuItems = jQuery('.menu-item', '#menu-to-edit');

			$menuItems.each( function(i) {
				var $item = jQuery(this),
					$checkbox = jQuery('.menu-item-wt-enable-megamenu', this);

				if ( !$item.is('.menu-item-depth-0') ) {

					var checkItem = $menuItems.filter(':eq('+(i-1)+')');
					if ( checkItem.is('.field-wt-megamenu-enabled') ) {

						$item.addClass('field-wt-megamenu-enabled');
						$checkbox.attr('checked','checked');
					} else {

						$item.removeClass('field-wt-megamenu-enabled');
						$checkbox.attr('checked','');
					}
				}

			});

		},

		binds: function() {

			jQuery('#menu-to-edit').on('click', '.menu-item-wt-enable-megamenu', function(event) {
				var $checkbox = jQuery(this),
					$container = $checkbox.parents('.menu-item:eq(0)');

				if ( $checkbox.is(':checked') ) {
					$container.addClass('field-wt-megamenu-enabled');
				} else {
					$container.removeClass('field-wt-megamenu-enabled');
				}

				wt_menu.recalc();

				return true;
			});

			jQuery('#menu-to-edit').on('change', '.field-wt-icon input[type="radio"]', function(event){
				var $this = jQuery(this),
					$parentContainer = $this.parents('.wt-megamenu-fields');

				switch( $this.val() ) {
					case 'image': $parentContainer.addClass('field-wt-megamenu-image-icon').removeClass('field-wt-megamenu-iconfont-icon'); break;
					case 'iconfont': $parentContainer.addClass('field-wt-megamenu-iconfont-icon').removeClass('field-wt-megamenu-image-icon'); break;
					default: $parentContainer.removeClass('field-wt-megamenu-iconfont-icon field-wt-megamenu-image-icon');
				}

				return true;
			});

			jQuery('#menu-to-edit').on('click', '.uploader-button', function(event){
				var frame,
					$el = jQuery(this),
					selector = $el.parents('.field-wt-image.controls');

				event.preventDefault();

				if ( $el.hasClass('upload-button') ) {

					// If the media frame already exists, reopen it.
					if ( frame ) {
						frame.open();
						return;
					}

					// Create the media frame.
					frame = wp.media({
						// Set the title of the modal.
						title: $el.data('choose'),
						library: { type: 'image' },
						// Customize the submit button.
						button: {
							// Set the text of the button.
							text: $el.data('update'),
							// Tell the button not to close the modal, since we're
							// going to refresh the page when the image is selected.
							close: false
						}
					});

					// When an image is selected, run a callback.
					frame.on( 'select', function() {

						// Grab the selected attachment.
						var attachment = frame.state().get('selection').first();

						frame.close();

						selector.find('.upload').val(attachment.attributes.url);
						selector.find('.upload-id').val(attachment.attributes.id);
						if ( attachment.attributes.type == 'image' ) {
							selector.find('.screenshot').empty().hide().append('<img src="' + attachment.attributes.url + '"><a class="remove-image">Remove</a>').slideDown('fast');
						}
						$el.addClass('remove-file').removeClass('upload-button').val(optionsframework_l10n.remove);
						selector.find('.of-background-properties').slideDown();
					});

					// Finally, open the modal.
					frame.open();
				} else {
					selector.find('.remove-image').hide();
					selector.find('.upload').val('');
					selector.find('.of-background-properties').hide();
					selector.find('.screenshot').slideUp();
					$el.addClass('upload-button').removeClass('remove-file').val(optionsframework_l10n.upload);
					selector.find('.upload-id').val(0);
				}
			});

		},

		init: function() {
			wt_menu.binds();
			wt_menu.recalc();

			jQuery( ".menu-item-bar" ).live( "mouseup", function(event, ui) {
				if ( !jQuery(event.target).is('a') ) {
					clearTimeout(wt_menu.reTimeout);
					wt_menu.reTimeout = setTimeout(wt_menu.recalc, 700);
				}
			});
		},


	}

	wt_menu.init();
});