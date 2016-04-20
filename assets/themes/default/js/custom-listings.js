$( document ).ready(function() {
	var docHeight = $(window).height();
	var footerHeight = $('footer').height();
	var footerTop = $('footer').position().top + footerHeight;
   
	if (footerTop < docHeight) {
		$('footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
	} 
	
	if($('.googleMapOk').length>0) {
		var address = $('#address').html();
		var city = $('#city').html();
		var state = $('#state').html();
		var full_address = address+' '+city+' '+state;
		load_map_and_street_view_from_address(full_address);
	}
	
	
	new WOW().init();
	




	function load_map_and_street_view_from_address(address) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var gps = results[0].geometry.location;
			create_map_and_streetview(gps.lat(), gps.lng(), 'map_canvas', 'pano');
			}
		});
	}
 
    var map;
    var myPano;
    var panorama;
    var houseMarker;
    var addLatLng;
    var panoOptions;
    function create_map_and_streetview(lat, lng, map_id, street_view_id) {
    var googlePos = new google.maps.LatLng(lat,lng);
 
    panorama = new google.maps.StreetViewPanorama(document.getElementById("pano"));
    addLatLng = new google.maps.LatLng(lat,lng);
    var service = new google.maps.StreetViewService();
    service.getPanoramaByLocation(addLatLng, 50, showPanoData);
 
    var myOptions = {
    zoom: 14,
    center: addLatLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    backgroundColor: 'transparent',
    streetViewControl: false,
    keyboardShortcuts: false,
 
    }
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		var marker = new google.maps.Marker({
            map: map,
            position: addLatLng
        });
    }
 
    function showPanoData(panoData, status) {
		if (status != google.maps.StreetViewStatus.OK) {
			$('#pano').html('No StreetView Picture Available').attr('style', 'text-align:center;font-weight:bold').show();
			return;
		}
		$('#pano').show();
		  var angle = computeAngle(addLatLng, panoData.location.latLng);
	 
		  var panoOptions = {
			position: addLatLng,
			addressControl: false,
			linksControl: false,
			panControl: false,
			zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
			},
			pov: {
			heading: angle,
			pitch: 10,
			zoom: 1
			},
			enableCloseButton: false,
			visible:true
		};
		panorama.setOptions(panoOptions);
    }
 
	function computeAngle(endLatLng, startLatLng) {
		var DEGREE_PER_RADIAN = 57.2957795;
		var RADIAN_PER_DEGREE = 0.017453;

		var dlat = endLatLng.lat() - startLatLng.lat();
		var dlng = endLatLng.lng() - startLatLng.lng();
		// We multiply dlng with cos(endLat), since the two points are very closeby,
		// so we assume their cos values are approximately equal.
		var yaw = Math.atan2(dlng * Math.cos(endLatLng.lat() * RADIAN_PER_DEGREE), dlat)
		 * DEGREE_PER_RADIAN;
		return wrapAngle(yaw);
   }
 
	function wrapAngle(angle) {
		if (angle >= 360) {
			angle -= 360;
		} else if (angle < 0) {
			angle += 360;
		}
		return angle;
    };
	
});