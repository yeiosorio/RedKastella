/**
 * WhoaThemes Extend Visual Composer Google Map
 *
 * Google Map Options with markerclusterer enabled
 */
 
function wt_vcsc_extend_google_map(map_options) {	
    // console.log(map_options);	   
    var options = {
            mapTypeId      : google.maps.MapTypeId.ROADMAP,
            panControl     : false,
            mapTypeControl : true,
            zoomControl    : true,
            rich_marker    : true,
            zoomControlOptions : {
                style    : google.maps.ZoomControlStyle.SMALL,
                position : google.maps.ControlPosition.TOP_LEFT
            },
            streetViewControl : false,
            zoom              : map_options.zoom,
            // draggable         : map_options.draggable,
            scrollwheel       : map_options.scrollwheel,
            styles            : map_options.theme
        },
        element     = document.getElementById(map_options.map_id),
        marker      = null,
        infowindow  = new google.maps.InfoWindow({
            content : "..."
        }),
        markers         = [],
        map             = new google.maps.Map(element, options),
        bounds          = new google.maps.LatLngBounds(),
        markerclusterer = map_options.clusters ? new MarkerClusterer(map, []) : null,
        geocoder        = new google.maps.Geocoder(),
        map_center      = null;

    // set map center based on main address using client side geocoding
    geocoder.geocode({ 'address': map_options.address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map_center = results[0].geometry.location;
            map.setCenter(map_center);
        }
    });

	/* Setting Map Markers
	   --------------------------------------------------------- */
	   
    map_options.markers.forEach(function(mk) {
        // if marker has saved lat/lng values simply set the marker up
        if(mk.lat_lng != '' && mk.lat_lng != undefined) {
            mk.lat_lng = mk.lat_lng.replace(/[()\s]/g,'');
            mk.lat_lng = mk.lat_lng.split(',');			
            // console.log(mk.lat_lng);
            marker = new google.maps.Marker({
                position : {
                    lat : parseFloat(mk.lat_lng[0]),
                    lng : parseFloat(mk.lat_lng[1])
                },
                map   : map,
                icon  : mk.icon,
                title : mk.title
            });
			
            if(map_options.clusters) markerclusterer.addMarker(marker);
            google.maps.event.addListener(marker, 'click', function () {
                if(map_options.center_on_markerclick) map.setCenter(this.getPosition());
                infowindow.setContent('<div class="wt_marker_content">'+
                                        '<strong>'+mk.title+'</strong>'+
                                        '<p>'+mk.description+'</p>'+
                                        '</div>'
                                        );
                infowindow.open(map, this);
            });
            bounds.extend(marker.position);
            markers.push(marker);
            // Fit markers
            if(map_options.fit_to_markers) map.fitBounds(bounds);
        } else {
            // console.log('geting new');
            // get the marker lat / lng values and then set up the marker
            geocoder.geocode({ 'address': mk.address }, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    marker = new google.maps.Marker({
                        position : results[0].geometry.location,
                        map      : map,
                        icon     : mk.icon,
                        title    : mk.title
                    });
					
                    if(map_options.clusters) markerclusterer.addMarker(marker);
                    google.maps.event.addListener(marker, 'click', function () {
                        if(map_options.center_on_markerclick) map.setCenter(this.getPosition());
                        infowindow.setContent('<div class="wt_marker_content">'+
                                                '<strong>'+mk.title+'</strong>'+
                                                '<p>'+mk.description+'</p>'+
                                                '</div>'
                                                );
                        infowindow.open(map, this);
                    });
                    bounds.extend(marker.position);
                    markers.push(marker);
                    // Fit markers
                    if(map_options.fit_to_markers) map.fitBounds(bounds);
                }
            });
        }
    });

}