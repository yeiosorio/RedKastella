/**
 * Thanks to
 * @author	Aslam Doctor
 * @url		https://github.com/aslamdoctor/jQuery.mapmarker
 * --------------------------------------------------------
 * It's using Google Maps Javascript API V3
 * Useful links:
 *     https://developers.google.com/maps/documentation/javascript/reference
 *     https://developers.google.com/maps/documentation/javascript/controls
 *     https://developers.google.com/maps/documentation/javascript/examples/maptype-styled-simple
 *     https://developers.google.com/maps/tutorials/customizing/custom-markers
 * @custom pins tut: http://leafletjs.com/examples/custom-icons.html#markers-with-custom-icons
 * @default marker: http://www.evoluted.net/thinktank/web-development/google-maps-api-v3-custom-location-pins
 */

(function($){
	$.fn.mapmarker = function(options){
		var opts = $.extend({}, $.fn.mapmarker.defaults, options);

		return this.each(function() {
			// Apply plugin functionality to each element
			var map_element = this;
			// addMapMarker(map_element, opts.zoom, opts.center, opts.mapType, opts.controls, opts.scrollwheel, opts.draggable, opts.doubleclickzoom, opts.markers, opts.styling, opts.featureType, opts.visibility, opts.invert_lightness, opts.elementType, opts.color, opts.hue, opts.saturation, opts.lightness, opts.gamma);
			addMapMarker(map_element, opts);
			
		});
	};
	
	// Set up default values
	var defaultMarkers = {
		"markers": []
	};

	$.fn.mapmarker.defaults = {
		center		     : '0,0',
		zoom		     : 14,
		controls         : [],
		mapType		     : 'ROADMAP',
		typeControl      : 'HORIZONTAL_BAR',
		scrollwheel	     : 1,
		draggable	     : 1,
		doubleclickzoom  : 1,
		markers		     : defaultMarkers,
		customMarkers    : 0,
		styling		     : 0,
		featureType	     : "all",
		elementType	     : "all",
		visibility	     : "on",
		invert_lightness : 0,
		color		     : "",
		hue			     : "",
		saturation	     : 0,
		lightness	     : 0,
		gamma		     : 1
	}

	// Main function code here (ref:google map api v3)
	// function addMapMarker(map_element, zoom, center, mapType, controls, scrollwheel, draggable, doubleclickzoom, controls, markers, styling, featureType, visibility, invert_lightness, elementType, color, hue, saturation, lightness, gamma){
	function addMapMarker(map_element, opts){
		//console.log(opts)
		scrollwheel = (opts.scrollwheel) ? true : false;
		draggable = (opts.draggable) ? true : false;
		doubleclickzoom = (opts.doubleclickzoom) ? true : false;
		customMarkers = (opts.customMarkers) ? true : false;
		
		var MY_MAPTYPE_ID = 'custom_style';
		
		var maptype;
		switch(opts.mapType) {
			case"ROADMAP":
			default:
				maptype = google.maps.MapTypeId.ROADMAP;
			break;
			case"SATELLITE":
				maptype = google.maps.MapTypeId.SATELLITE;
			break;
			case"HYBRID":
				maptype = google.maps.MapTypeId.HYBRID;
			break;
			case"TERRAIN":
				maptype = google.maps.MapTypeId.TERRAIN;
			break;
		}
		
		switch(opts.typeControl) {
			case "DROPDOWN_MENU":
			default:
				mapTypeControl = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
			break;
			case "HORIZONTAL_BAR":
				mapTypeControl = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
			break;			
		}
		
		var coords = opts.center.split(","),
			centerPoint = new google.maps.LatLng(coords[0],coords[1]);			
				
		if(opts.styling) {
			var myOptions = {
				zoom: opts.zoom,
				center: centerPoint,
				mapTypeId: maptype,
				scrollwheel: scrollwheel,
				draggable: draggable,
				disableDoubleClickZoom: !doubleclickzoom,
				mapTypeControlOptions: {
					style: mapTypeControl,
					position: google.maps.ControlPosition.RIGHT_TOP,				
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID] 
				},
				mapTypeId: MY_MAPTYPE_ID
			}
		} else {
			var myOptions = {
				zoom: opts.zoom,
				center: centerPoint,
				mapTypeId: maptype,
				scrollwheel: scrollwheel,
				draggable: draggable,
				disableDoubleClickZoom: !doubleclickzoom,
				mapTypeControlOptions: {
					style: mapTypeControl,
					position: google.maps.ControlPosition.RIGHT_TOP 
				}
			}
		}
		var map = new google.maps.Map(map_element, myOptions);
		
		if(opts.styling) {
			var myMapStyles = [
				{ featureType: opts.featureType, elementType: opts.elementType, stylers: [ { visibility: opts.visibility } , { invert_lightness: opts.invert_lightness } , { color: opts.color } , { hue: opts.hue }, { saturation: opts.saturation },{ lightness: opts.lightness }, { gamma: opts.gamma } ] }
			];
			// In case you need to style different features and element types. Below lines are just an example. You need to edit theme after your needs.
			var advancedMapStyles = [
				{
				  stylers: [
					{ hue: '#890000' },
					{ visibility: 'simplified' },
					{ gamma: 0.5 },
					{ weight: 0.5 }
				  ]
				},
				{
				  elementType: 'labels',
				  stylers: [
					{ visibility: 'off' }
				  ]
				},
				{
				  featureType: 'water',
				  stylers: [
					{ color: '#890000' }
				  ]
				}
			];
			
			var styledMapOptions = {
				name: 'Custom Style'
		    };
			
			var MapStyles = new google.maps.StyledMapType(myMapStyles, styledMapOptions);
			// Advanced stylers
			// var MapStyles = new google.maps.StyledMapType(advancedMapStyles, styledMapOptions);
			
		    map.mapTypes.set(MY_MAPTYPE_ID, MapStyles);
		}
							
		// Check for map controls
		if(opts.controls === false){
			$.extend(myOptions, { disableDefaultUI: true });
		}else if (opts.controls.length != 0){
			$.extend(myOptions, opts.controls, { disableDefaultUI: true });
		}		
		map.setOptions(myOptions);		
		
		if(opts.styling) {
			map.mapTypes.set('custom_styled', MapStyles);
			map.setMapTypeId('custom_styled');
		}
		var infowindow = null;
		var baloon_text = "";
		
		google.maps.event.addListenerOnce(map, 'tilesloaded', function() {
			setMarkers(map);
    	});
		
		// run the marker JSON loop here			
		function setMarkers(map) {
			var iterator = 1;
			$.each(opts.markers.markers, function(i, the_marker){
				setTimeout(function () {
				
					latitude=the_marker.latitude;
					longitude=the_marker.longitude;
					
					// Check for map custom markers
					if(customMarkers){
						// In case you're using another bigger custom marker, the default marker properties below should be edited.
						icon = new google.maps.MarkerImage(
							the_marker.icon,
							// This marker is 63 pixels wide by 68 pixels tall.
							new google.maps.Size(63,68),
							// The origin for this image is 0,0.
							new google.maps.Point(0,0),
							// The anchor for this image is the base of the flagpole at 18,42.
							new google.maps.Point(18,42)
						);
					} else {
						icon = the_marker.markerDefaultIcon; // Displays the same google red marker for all the places.
					}	
								
					var baloon_text=the_marker.baloon_text;
					
					if(latitude!="" && longitude!=""){
						
						var marker = new google.maps.Marker({
							map: map, 
							position: new google.maps.LatLng(latitude,longitude),
							animation: google.maps.Animation.BOUNCE,
							icon: icon,
							draggable: false,
							raiseOnDrag: false
						});
						setTimeout(function(){marker.setAnimation(null);},400);
						
						// Set up markers with info windows 
						google.maps.event.addListener(marker, 'click', function() {
							// Close all open infowindows
							if (infowindow) {
								infowindow.close();
							}
							
							infowindow = new google.maps.InfoWindow({
								content: baloon_text
							});
							
							infowindow.open(map,marker);
						});
					}
				}, iterator * 500);
				iterator++;
				
			});
		} // end setMarkers		
		// end marker loop 
		
		// Custom navigation
		var $wt_map = $('.wt_gMap');
		if ( $wt_map.length ) {			
			function customNavigation( wt_maps ) {
				wt_maps.each(function() {
					var $this  = $(this);					
					$this.find('.wt_zoomIn').click(function(){
						map.setZoom(map.getZoom() + 1);
					});	
					$this.find('.wt_zoomOut').click(function(){
						map.setZoom(map.getZoom() - 1);
					});
					$this.find('.wt_resetMap').click(function(){
						map.setZoom(opts.zoom);
						map.setCenter(centerPoint);
					});			
				});
			}
			customNavigation( $wt_map );		
		}
		
		// Resize map in megamenu and tabs
		var $li   = $wt_map.parents( 'li.wt_megamenu' ),
			$rnav = $wt_map.parents( '#wt-responsive-nav' ),
			$tabs = $wt_map.parents('.wt_tabs_wrap .pane, .wt_accordion .wt_accPane, .wt_toggle_content'),
			mapHandler = function(){
				google.maps.event.trigger(map, "resize");
				map.setCenter(centerPoint);
				//Only resize the first time we open
				$rnav.off( 'wt_rnav_open', mapHandler );
				$li.off( 'wt_menuopen', mapHandler );
				$(this).off( 'wt_tabopen', mapHandler );
			};
			
		if ( $rnav.length )			
		$rnav.on( 'wt_rnav_open', mapHandler );
		
		if ( $li.length ) {			
			$li.on( 'wt_menuopen', mapHandler );					
		}			
		
		if ( $tabs.length ) {
			$tabs.each(function(index) {
				$(this).on( 'wt_tabopen', mapHandler );
			});
		}
	}

})(jQuery);