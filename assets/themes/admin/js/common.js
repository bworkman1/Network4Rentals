$(function() {
	$('.toolTips').tooltip();
	
	$('.viewAllWIthout').click(function() {
		if($('.hideRest').hasClass('showingRest')) {
			$('.hideRest').removeClass('showingRest').slideUp();
			$(this).html('Show All');
		} else { 
			$('.hideRest').addClass('showingRest').slideDown();
			$(this).html('Hide Most');
		}

	});
	
	$('#affiliate').click(function() {
		if($(this).is(':checked')) {
			$('#affiliate-account').css({'display':'block'}).addClass('in');
			$('#unique').attr('disabled', false);
			$('#background').attr('disabled', false);
		} else {
			$('#affiliate-account').css({'display':'none'}).removeClass('in');
			$('#unique').attr('disabled', true);
			$('#background').attr('disabled', true);
		}
	});
	

	/*ADD SUPPLY HOUSES*/
	$('#addSupplyHouse').submit(function(event) {
		event.preventDefault();
		
		var fd = new FormData();	
		var file_data = '';
		
		file_data = $('#logo')[0].files;
		fd.append("file_0", file_data[0]);
		
		file_data = $('#background')[0].files;
		fd.append("file_1", file_data[0]);
		
		var other_data = $('#addSupplyHouse').serializeArray();
		$.each(other_data,function(key,input){
			fd.append(input.name,input.value);
		});

		fd.append('resource_types', $('#resource_types').val());
		fd.append('ad_services', $('#ad_services').val());
		
		$.ajax({
			url: '//network4rentals.com/network/ajax-admins/add-supply-house/',
			data: fd,
			contentType: false,
			processData: false,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				console.log(data);
				if(typeof data.error == 'undefined') {
					alertify.success('Supply House created successfully');
					window.location.href = "https://network4rentals.com/network/n4radmin/supply-houses";
				} else {
					alertify.error(data.error);
				}
			},
			error: function(xhR) {
				console.log(xhR);
			},
			beforeSend: function() {
				$('#submit').html('<i class="fa fa-cog fa-spin"></i> Saving Supply House').attr('disabled', true).removeClass('btn-primary').addClass('btn-default');
			},
			complete: function() {
				$('#submit').html('Save Supply House').attr('disabled', false).addClass('btn-primary').removeClass('btn-default');
			}
		});
	
    });
	
		/*ADD SUPPLY HOUSES*/
	$('#editSupplyHouse').submit(function(event) {
		event.preventDefault();
		
		var fd = new FormData();	
		var file_data = '';
		
		file_data = $('#logo')[0].files;
		fd.append("file_0", file_data[0]);
		
		file_data = $('#background')[0].files;
		fd.append("file_1", file_data[0]);
		
		var other_data = $('#editSupplyHouse').serializeArray();
		$.each(other_data,function(key,input){
			fd.append(input.name,input.value);
		});

		fd.append('resource_types', $('#resource_types').val());
		fd.append('ad_services', $('#ad_services').val());
		
		$.ajax({
			url: '//network4rentals.com/network/ajax-admins/edit-supply-house/',
			data: fd,
			contentType: false,
			processData: false,
			type: 'POST',
			dataType: 'json',
			success: function(data){
				console.log(data);
				if(typeof data.error == 'undefined') {
					if(data.success != null) {
						$('#supplyHouseLogo').attr('src', 'https://network4rentals.com/network/'+data.success);
					}
					alertify.success('Supply House saved successfully');
				} else {
					alertify.error(data.error);
				}
			},
			error: function(xhR) {
				alertify.error('Something went wrong, try again. If you adding an affiliate account you must select a background iamge, this might be the issue.');
				console.log(xhR);
			},
			beforeSend: function() {
				$('#submit').html('<i class="fa fa-cog fa-spin"></i> Saving Supply House').attr('disabled', true).removeClass('btn-primary').addClass('btn-default');
			},
			complete: function() {
				$('#submit').html('Save Supply House').attr('disabled', false).addClass('btn-primary').removeClass('btn-default');
			}
		});
	
    });
	
	
});