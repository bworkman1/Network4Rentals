$(function() {
	$(".supplyHouseResult").click(function() {
		var id = $(this).data('id');
		var lat = $(this).data('lat');
		var lon = $(this).data('long');
		google.maps.event.trigger(markers[id], "click");
		var laLatLng = new google.maps.LatLng(lat, lon);
		map.panTo(laLatLng);
		map.setZoom(15);
	});
	
	var locations = [];
	var directions = 'https://www.google.com/maps/place/';
	$('.supplyHouseResult').each(function() {
		var address = $(this).data('address');
		var title = $(this).data('title');
		var lat = $(this).data('lat');
		var lon = $(this).data('long');
		var url = $(this).data('url');
		var phone = $(this).data('phone');
		var dir = '<a href="'+directions+address.replace(' ', '+')+'" target="_blank">Get Directions</a>';
		if(url != '') {
			url = '<a href="'+url+'" target="_blank">Visit Website</a>';
		}
		var data = ['<h4>'+title+'</h4><p>'+address+'</p><p>'+phone+'</p>'+dir+'<br>'+url, lat, lon];

		locations.push(data);
	});
	
       
    // Setup the different icons and shadows
    var iconURLPrefix = 'https://maps.google.com/mapfiles/ms/icons/';
    
    var icons = [
		iconURLPrefix + 'red-dot.png',
		iconURLPrefix + 'green-dot.png',
		iconURLPrefix + 'blue-dot.png',
		iconURLPrefix + 'orange-dot.png',
		iconURLPrefix + 'purple-dot.png',
		iconURLPrefix + 'pink-dot.png',      
		iconURLPrefix + 'yellow-dot.png'
    ]
    var icons_length = icons.length;
    
    
    var shadow = {
		anchor: new google.maps.Point(15,33),
		url: iconURLPrefix + 'msmarker.shadow.png'
    };

    var map = new google.maps.Map(document.getElementById('map'), {
		zoom: 15,
		center: new google.maps.LatLng(40.232046, -82.452802),
		mapTypeId: google.maps.MapTypeId.ROADMAP,
		mapTypeControl: false,
		streetViewControl: false,
		panControl: false,
		zoomControlOptions: {
			position: google.maps.ControlPosition.LEFT_BOTTOM
		}
    });

    var infowindow = new google.maps.InfoWindow({
		maxWidth: 200
    });

    var marker;
    var markers = new Array();
    
    var iconCounter = 0;
    
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(locations[i][1], locations[i][2]),
			map: map,
			icon : icons[iconCounter],
			shadow: shadow
		});

		markers.push(marker);

		google.maps.event.addListener(marker, 'click', (function(marker, i) {
			return function() {
				infowindow.setContent(locations[i][0]);
				infowindow.open(map, marker);
			}
		})(marker, i));

		iconCounter++;
		// We only have a limited number of possible icon colors, so we may have to restart the counter
		if(iconCounter >= icons_length){
			iconCounter = 0;
		}
    }
	
	var listener = google.maps.event.addListener(map, "idle", function() { 
		if (map.getZoom() > 16) map.setZoom(16); 
		google.maps.event.removeListener(listener); 
	});

    function AutoCenter() {
		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds();
		//  Go through each...
		jQuery.each(markers, function (index, marker) {
			bounds.extend(marker.position);
		});
		//  Fit these bounds to the map
		map.fitBounds(bounds);
		map.zoom(15);
    }
    AutoCenter();
});