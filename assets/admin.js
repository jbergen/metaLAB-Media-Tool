jQuery(document).ready(function(){	
	
	
	//console.log('loaded');
	jQuery( "#date" ).datepicker({
		changeMonth: true,
		changeYear: true,
		yearRange: "1800:2020",
		dateFormat: 'yy-mm-dd'
		
	});
	
	jQuery("#clear-map").change(function(){
		if( jQuery('#clear-map').attr('checked') ){
			jQuery("#lat").val( "");
			jQuery("#lng").val( "");
			jQuery("#location").val( "");
		}
	});
	    
	
//movable markers map

var map;
var marker;

jQuery("#location").focusout(function(){
	codeAddress();
});
	
			var geocoder = new google.maps.Geocoder();

	function geocodePosition(pos) {
	  geocoder.geocode({
	    latLng: pos
	  }, function(responses) {
	    if (responses && responses.length > 0) {
		
			//buckets to determine how precise you want to look up the place
			//responses[n] n=1-7 specific-general
			//map.getZoom() 18-0 specific-general
			var zoom = map.getZoom();
			//console.log(zoom);
			var r = 1;
			if(zoom < 16 && zoom >= 14) r = 2;
			else if(zoom <= 13 && zoom >= 11) r = 3;
			else if(zoom <= 10) r = 4;
			
	      updateMarkerAddress(responses[r].formatted_address);
	    } else {
	      updateMarkerAddress('Cannot determine address at this location.');
	    }
	  });
	}


	function updateMarkerPosition(latLng) {
		jQuery("#lat").val( [ Math.round(latLng.lat()*10000)/10000 ]);
		jQuery("#lng").val( [ Math.round(latLng.lng()*10000)/10000 ]);
	
		jQuery('#clear-map').attr('checked', false)	
	}

	function updateMarkerAddress(str) {
	  	jQuery("#location").val( str );
	}

	function initialize() {
		
		//var existingLocation = jQuery("#location").val();
		var lat = jQuery("#lat").val();
		var lng = jQuery("#lng").val();
		var zoomWindow = 12;
	
		var temp = parseInt( jQuery("#zoom").attr("value")) ;
		if(temp > 0) zoomWindow = temp;
	
		//console.log(zoomWindow);
		if(lat == "" || lng == ""){
			lat = 42.376;
			lng = -71.1135;
		}
	
		var latLng = new google.maps.LatLng(lat, lng);
	  
		//var map = new google.maps.Map(document.getElementById('map_canvas'), {
		map = new google.maps.Map(document.getElementById('map_canvas'), {
		    zoom: zoomWindow,
		    center: latLng,
		    mapTypeId: google.maps.MapTypeId.ROADMAP
		  });
		  marker = new google.maps.Marker({
		    position: latLng,
		    title: 'your point',
		    map: map,
		    draggable: true
		  });

		google.maps.event.addListener(map, 'zoom_changed', function() {
		    jQuery("#zoom").attr("value",map.getZoom());
		    geocodePosition(marker.getPosition());
			updateMarkerPosition(marker.getPosition());
		  });
	

		google.maps.event.addListener(marker, 'dragend', function() {
		    geocodePosition(marker.getPosition());
			updateMarkerPosition(marker.getPosition());
		  });
		}
	
		function codeAddress() {
		    var address = jQuery("#location").val();

		    geocoder.geocode( { 'address': address}, function(results, status) {
		      if (status == google.maps.GeocoderStatus.OK) {
		        map.setCenter(results[0].geometry.location);
		        
				marker.position = results[0].geometry.location;
				
				updateMarkerPosition(results[0].geometry.location);
				geocodePosition(results[0].geometry.location);


				geocodePosition(results[0].geometry.location);
				updateMarkerPosition(results[0].geometry.location);

		      } else {
		        alert("Geocode was not successful for the following reason: " + status);
		      }
		 });
		}
		
		//don't load the map if there's nowhere to put it!
		if( jQuery('#map_canvas').length > 0) initialize();
	

});