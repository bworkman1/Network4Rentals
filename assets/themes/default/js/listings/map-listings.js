$(function() {
	// Define your locations: HTML content for the info window, latitude, longitude
	var locations = [];
	$('.items').each(function() {
		var address = $(this).data('address');
		var title = $(this).data('title');
		var id = $(this).data('id');
		var lat = $(this).data('lat');
		var lon = $(this).data('long');
	
		locations[] = ['<h4>'+title+'</h4><p>'+address+'</p><a href="https://network4rentals.com/network/listings/view-listing/'+id+'">View Listing</a>', lat, lon]
	});
	
	
	console.log(locations[0][1]);
   
    
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

    var map = new google.maps.Map(document.getElementById('listing-map'), {
		zoom: 50,
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
		maxWidth: 160
    });

    var marker;
    var markers = new Array();
    
    var iconCounter = 0;
    
    // Add the markers and infowindows to the map
    for (var i = 0; i < locations.length; i++) {  
		marker = new google.maps.Marker({
			//console.log(locations[i][1][2]+' = '+locations[i][2]);
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

    function AutoCenter() {
		//  Create a new viewpoint bound
		var bounds = new google.maps.LatLngBounds();
		//  Go through each...
		jQuery.each(markers, function (index, marker) {
			bounds.extend(marker.position);
		});
		//  Fit these bounds to the map
		map.fitBounds(bounds);
    }
    AutoCenter();
});