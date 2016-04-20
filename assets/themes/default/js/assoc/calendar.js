$(function() {
		
	var d = new Date();
	var yearToday = d.getFullYear();
	var dayToday = d.getDate();	

	var url = window.location.href;
	var res = url.split("/");
	
	var linkDay = res[res.length-1];
	var linkYear = res[res.length-2];
	var yearSet = false;
	if(linkYear.length === 4) {
		yearSet = true;
	}
		
	$('.date').datetimepicker({
		language: 'en',
		pick12HourFormat: true,
		pickSeconds: true
    });
	
	$('.cal-addresses').focusout(function() {
		var address = $('.cal-addresses').val();
		if(address.length) {
			load_map_and_street_view_from_address(address, 'map_canvas');
		}
	});
	
	$('.showCalAddress').click(function() { // Add Address To Calendar Event
		 if (this.checked) {
			$('.cal-address').addClass('in').css({'display':'block'});
		} else {
			$('.cal-address').removeClass('in').css({'display':'none'});
		}
	});
	
	$('.addEvent').click(function(event) { // Add event to calendar
		event.preventDefault();
		
		var input = $('#addEvent').serializeArray();
		
		var yearStart = input[0].value.substring(0, 4);
		var monthStart = input[0].value.substring(5, 7);
		var dayStart = input[0].value.substring(8, 10);
		
		var addToCurrentCalendar = false;
		if(yearSet) {
			if(yearStart == linkYear || monthStart == linkDay) {
				addToCurrentCalendar = true;
			}
		} else {
			if(yearStart == yearToday || monthStart == dayToday) {
				addToCurrentCalendar = true;
			}
		}

		$.ajax({
			url: '//network4rentals.com/network/ajax_associations/add_new_landlord_assoc_event/',
			cache: false,
			type: "POST",
			data: input,
			success: function(response) {
				console.log(response);
				if(response>0) {
					$.notify("Event successfully added, refresh page to see event", "success");
					if(addToCurrentCalendar) {
						$('table tr td .day_listing').each(function() {
							var day = $(this).html();
							if(day == dayStart) {
							
								if($(this).parent().hasClass('cal-event-list')) {
									$('.cal-event-list').append('<li class="addedItem" id="'+response+'" data-id="'+response+'">'+input[2].value+'</li>');
								} else {
									$(this).parent().append('<ul class="cal-event-list"><li class="addedItem" id="'+response+'" data-id="'+response+'">'+input[2].value+'</li></ul>');
								}
							}
						});
					}
					
					location.reload();
				} else {
					$.notify("Add event failed, try again", "error");
				}
				$('.cal-address').removeClass('in').css({'display':'none'});
				$('input').each(function() {
					
					if($(this).attr('type')=='checkbox') {
			
					} else {
						$(this).val('');
					}
				});
				$('#map_canvas').css({'height':'0'});
			},
			beforeSend: function() {
				$('.addEvent').html('<i class="fa fa-spinner fa-spin"></i> Adding Event').addClass('disabled');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr+' - '+ajaxOptions+' - '+thrownError);
				$.notify(thrownError, "error");
			},
			timeout: 15000,
			complete: function() {
				$('.addEvent').html('Add Event').removeClass('disabled');
			}
		});
	});

	$('.cal-event-list li').click(function() { // Show Calendar Event
		$('.loadingIcon').remove();
		$(this).append('<span class="loadingIcon pull-right"><i class="fa fa-refresh fa-spin"></i></span>');
		var cal_id = $(this).data('id');
		$('#show_map').removeClass('in').css({'display':'none'});
		$.ajax({
			url: '//network4rentals.com/network/ajax_associations/fetch_landlord_assoc_event/',
			cache: false,
			type: "POST",
			data: {id:cal_id},
			dataType: 'json',
			success: function(response) {
				if(response.id>0) {
					console.log(response);
					/* View Event Values */
					$('.modal-title, #editEventTitle').html(response.what);
					$('#start').html(response.start);
					$('#end').html(response.end);
					$('#where').html(response.where);
					$('#address').html(response.address);
					$('.deleteBtn').data('deleteid', response.id);
					
					/* Edit Event Values */
					$('#edit_what').val(response.what);
					$('#edit_start').val(response.start);
					$('#edit_ends').val(response.end);
					$('#edit_where').val(response.where);
					$('#edit_address').val(response.address);
					$('#edit_id').val(response.id);
					$('.editEventBtn').attr('href', 'https://network4rentals.com/network/landlord-associations/edit-event/'+response.id+'/');
					$('.eventDetailsBtn').attr('href', 'https://network4rentals.com/network/landlord-associations/event-details/'+response.id+'/');
					if(response.public ==='n') {
						$('#public').html('Private');
					} else {
						$('#public').html('Public');
						$('#edit_public').prop('checked', true); 
					}
					if(response.map === 'y') {
						$('#show_map').addClass('in').css({'display':'block'});
						$('#edit_map').prop('checked', true);
						setTimeout( function(){
							load_map_and_street_view_from_address(response.address, 'event_map');
						},700);
						
					} else {
						$('#show_map').removeClass('in').css({'display':'none'});
					}
					setTimeout( function(){
						$('#eventDetails').modal('show'); //show modal with new details
					}, 300);
				} else {
					console.log('No Data');
				}
				
			}, 
			complete: function() {
				$('.loadingIcon').remove();
			}
		});
	});
	

	if($('input[name=map]').is(':checked')) {
		var address = $('.cal-addresses').val();
		load_map_and_street_view_from_address(address, 'map_canvas');
	}
	
	$('.deleteBtn').click(function() { // Delete Calendar Event
		var cal_id = $(this).data('deleteid');
		$.ajax({
			url: '//network4rentals.com/network/ajax/delete_landlord_assoc_event/',
			cache: false,
			type: "POST",
			data: {id:cal_id},
			dataType: 'json',
			success: function(response) {
				$('#eventDetails').modal('hide');
				$('#'+cal_id).fadeOut();
				$.notify("Event successful deleted", "success");
			}
		});
		
	});
	
});

	function load_map_and_street_view_from_address(address, element_id) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var gps = results[0].geometry.location;
				create_map_and_streetview(gps.lat(), gps.lng(), element_id, 'pano');
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
		// service.getPanoramaByLocation(addLatLng, 50, showPanoData);
	 
		var myOptions = {
			zoom: 14,
			center: addLatLng,
			mapTypeId: google.maps.MapTypeId.ROADMAP,
			backgroundColor: 'transparent',
			streetViewControl: false,
			keyboardShortcuts: false,
		}
		var pageMap = document.getElementById(map_id);
		pageMap.style.height = "200px";
		pageMap.style.width = "100%";
		var map = new google.maps.Map(document.getElementById(map_id), myOptions);
		var marker = new google.maps.Marker({
			map: map,
			position: addLatLng
		});
		google.maps.event.trigger(map, 'resize');
    }
 
    function showPanoData(panoData, status) {
		if (status != google.maps.StreetViewStatus.OK) {
			$('#pano').html('No StreetView Picture Available').attr('style', 'text-align:center;font-weight:bold').show();
			return;
		} else {
			$('#pano').css({'height':'200px'});
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
