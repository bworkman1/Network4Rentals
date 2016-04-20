$(function() {
	$('#contact-phone').mask('(999) 999-9999', {autoclear: true});
	$('#createTaskStartTime').mask('99:99', {autoclear: true});
	$('#createTaskEndTime').mask('99:99', {autoclear: true});
	$('#createStartTask').mask('99/99/9999',{autoclear: true});
	$('#createEndTask').mask('99/99/9999',{autoclear: true});
	
	$('#schedule').click(function() {
		if($(this).is(':checked')) {
			$('.scheduleIn').removeClass('displayNone');
		} else {
			$('.scheduleIn').addClass('displayNone');
		}
	});
	
	$('#addRequest').submit(function(event) {
		event.preventDefault();
		
		var formData = new FormData();
		formData.append('serviceAddress', $('#service-address').val());
		formData.append('serviceCity', $('#service-city').val());
		formData.append('serviceState', $('#service-state').val());
		formData.append('serviceZip', $('#service-zip').val());
		formData.append('serviceType', $('#serviceType').val());
		formData.append('description', $('#description').val());
		formData.append('contactName', $('#contact-name').val());
		formData.append('contactPhone', $('#contact-phone').val());
		formData.append('contactEmail', $('#contact-email').val());
		formData.append('createStartTask', $('#createStartTask').val());
		formData.append('createTaskStartTime', $('#createTaskStartTime').val());
		formData.append('createEndTask', $('#createEndTask').val());
		formData.append('createTaskEndTime', $('#createTaskEndTime').val());
		formData.append('startAm', $('#startAm').val());
		formData.append('endAm', $('#endAm').val());
		
		if($('#schedule').is(':checked')) {
			formData.append('schedule', 'y');
		}
		
		
		var fileSelect = document.getElementById('attachment');
		var files = fileSelect.files;
		var file = files[0];

		if(file) {
			formData.append('file', file);
		}
		
		$.ajax({
			url: '//network4rentals.com/network/ajax_contractors/add_service_request/',
			data: formData,
			cache: false,
			dataType: 'json',
			contentType: false,
			processData: false,
			type: 'POST',
			timeout: 10000,
			success: function(response) {
				if(typeof response.error == 'undefined') {
					alertify.success('Service request added successfully');
					if($('#schedule').is(':checked')) {
						window.location.href = "https://network4rentals.com/network/contractor/my-calendar";
					} else {
						window.location.href = "https://network4rentals.com/network/contractor/service-requests";
					}
				} else {
					alertify.error(response.error);
				}
			
			}, 
			error: function(jqXHR, textStatus, errorThrown) {	
				if(file.name != '') {
					if(textStatus==="timeout") {
						alertify.error('Your request timed out, perhaps the image your uploading is too large, try a smaller image or no image');
					}
				} else {
					alertify.error('There was an error processing your request, try again');
				}
			},
			beforeSend: function() {
				$('.submit').removeClass('btn-primary').addClass('btn-info').html('<i class="fa fa-cog fa-spin"></i> Adding Request').attr('disabled', true);
			},
			complete: function() {
				$('.submit').removeClass('btn-info').addClass('btn-primary').html('<i class="fa fa-share"></i> Add Request').attr('disabled', false);
			}
		});
		
	});
	

});