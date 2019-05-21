jQuery(document).ready(function( $ ) {

    var wt_marker = false,
        custom_uploader;

	/* ----- Add New Marker Setting Panel ----- */
	
    $(document).on('click', '.new_marker', function(e) {
        e.preventDefault();
        var marker_data = {
                'm_address'  : '',
                'm_lat_lng'  : '',
                'm_title'    : '',
                'm_desc'     : '',
                'm_icon_id'  : '',
                'm_icon_url' : ''
            },
            marker = wt_marker_item_panel(marker_data);
        $('.wt_markers_wrapper').append(marker);
    });

	/* ----- Show / Hide Marker Settings Panel ----- */
	
    $(document).on('click', '.wt_marker_settings', function(e) {
        e.preventDefault();
        var marker_settings = $(this).parent().next();
        $('.wt_marker_info').not(marker_settings).hide();
		
		if(!marker_settings.is(':visible')) {
			marker_settings.parent().addClass("wt_marker_info_opened");
		} else {
			marker_settings.parent().removeClass("wt_marker_info_opened");
		}
		
        marker_settings.slideToggle(200);
    });
	
	/* ----- Save Marker Settings Fields on Blur ----- */

    $(document).on('blur', '.m_title, .m_address, .m_desc', function() {
        var $this = $(this);

        // Get lat & lng of marker
        if($this.hasClass('m_address')) {
            var geocoder = new google.maps.Geocoder();
            // Add loader while coordinates are retriving
            $this.parent().parent().append('<div class="get_coordinates">Get marker latitude & longitude</div>');
            geocoder.geocode({ 'address': $this.val() }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    // Save coords in data-lat_lng attr
                    $this.attr('data-lat_lng', results[0].geometry.location);
                }
                $this.parent().parent().find('.get_coordinates').remove();
                // Update textarea with new json markers list
                wt_save_markers_data();
            });
        } else {
            // Update textarea with new json markers list
            wt_save_markers_data();
        }
		
		// Adding title val() to ".marker_title" span. This helps to easily recognize the marker when its info block is :hidden
        if($this.hasClass('m_title')) $this.parent().parent().parent().find('.marker_title').text($this.val());
    });	
	
	/* ----- Remove Marker Settings Panel ----- */
	
    $(document).on('click', '.remove_marker', function(e) {
        e.preventDefault();
        $(this).parent().parent().remove();
		
		// Update textarea with new json markers list
        wt_save_markers_data();
    });	
	
	/* ----- WP Media Library - Upload Custom Markers ----- */
		   
    $(document).on('click', '.wt_marker_img', function(e) {
        e.preventDefault();
        var $this = $(this);
        wt_marker = $this.parent().parent().parent();

        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Select marker',
            button: {
                text: 'Select'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            // console.log(attachment);

            // Add marker icon preview
            wt_marker.find('.m_icon_prev').remove();
            wt_marker.find('.remove_marker_image').remove();
            wt_marker.find('.wt_marker_img').after('<img class="m_icon_prev" data-icon_id="'+attachment.id+'" src="'+attachment.url+'">');
        	wt_marker.find('.m_icon_prev').after('<a href="#" class="remove_marker_image">Remove</a>');

            // Update textarea with new json markers list
            wt_save_markers_data();

            wt_marker = false;
        });
        custom_uploader.open();
    });
	
	/* ----- Remove Marker Image ----- */
		   
    $(document).on('click', '.remove_marker_image', function(e) {
        e.preventDefault();
        var $this = $(this);
        wt_marker = $this.parent().parent().parent();
		
		// Remove marker icon preview
		wt_marker.find('.m_icon_prev').remove();
        wt_marker.find('.wt_marker_img').after('<img class="m_icon_prev" data-icon_id="" src="">');
		$this.remove();

		// Update textarea with new json markers list
		wt_save_markers_data();
    });

});

/* Saving Markers Data And Raturn Data As Json
   --------------------------------------------------------- */

function wt_save_markers_data() {
    jQuery(document).ready(function($) {
        var marker_data_textarea = $('#vc_ui-panel-edit-element .marker_data');
        if($('#vc_ui-panel-edit-element .wt_marker_item').length > 0) {
            var data = {},
                i = 0;
            $('#vc_ui-panel-edit-element .wt_marker_item').each(function() {
                var $this = $(this),
                    row = {},
					
					// Get marker latitude & longitude values - on addresss field blur event
                    m_lat_lng = $this.find('.m_address').attr('data-lat_lng');

                if(m_lat_lng == undefined) m_lat_lng = '';

                row['m_address']  = $this.find('.m_address').val();
                row['m_lat_lng']  = m_lat_lng;
                row['m_title']    = $this.find('.m_title').val();
                row['m_desc']     = $this.find('.m_desc').val();
                row['m_icon_id']  = $this.find('.m_icon_prev').attr('data-icon_id');
                row['m_icon_url'] = $this.find('.m_icon_prev').attr('src');
				
                data[i] = row;
                i++;
            });
            marker_data_textarea.html(JSON.stringify(data));
            return true;
        }
        marker_data_textarea.html('');
        return false;
    });
}

/* Marker Item Panel
   --------------------------------------------------------- */
   	
function wt_marker_item_panel(marker_data) {
    // Build marker icon preview
    var wt_marker_icon = '';
    if(marker_data.m_icon_id != undefined) {
        wt_marker_icon = '<img class="m_icon_prev" data-icon_id="" src="">';
    }
    if(marker_data.m_icon_id != '') {
        wt_marker_icon = '<img class="m_icon_prev" data-icon_id="'+marker_data.m_icon_id+'" src="'+marker_data.m_icon_url+'">';
        wt_marker_icon += '<a href="#" class="remove_marker_image">Remove</a>';
    }
    
    var m_lat_lng = (marker_data.m_lat_lng == undefined)? '' : marker_data.m_lat_lng;

    return '<div class="wt_marker_item">'+
                '<div class="wt_marker_events">'+
                    '<span class="marker_icon dashicons dashicons-location"></span>'+
                    '<a href="#" class="wt_marker_settings">Marker settings</a>'+
                    '<span class="marker_title">'+marker_data.m_title+'</span>'+
                    '<a href="#" class="remove_marker">x</a>'+
                '</div>'+
                '<div class="wt_marker_info">'+
                    '<div class="mi_section">'+
                        '<span>Marker address:</span>'+
                        '<input class="m_address" type="text" value="'+marker_data.m_address+'" data-lat_lng="'+m_lat_lng+'">'+
                    '</div>'+
                    '<div class="mi_section">'+
                        '<span>InfoWindow title:</span>'+
                        '<input class="m_title" type="text" value="'+marker_data.m_title+'">'+
                    '</div>'+
                    '<div class="mi_section">'+
                        '<span>InfoWindow content:</span>'+
                        '<textarea class="m_desc">'+marker_data.m_desc+'</textarea>'+
                    '</div>'+
                    '<div class="mi_section mi_last">'+
                        '<a href="#" class="wt_marker_img">Marker image</a>'+
                        wt_marker_icon+
                    '</div>'+
                '</div>'+
            '</div>';
}