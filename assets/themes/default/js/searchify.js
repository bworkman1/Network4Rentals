$( document ).ready(function() {

	$('.landlordDetails #bName').focusout(function() {
		var check = $('#landlord-id').val();
		if(check == '') {
			var search = $(this).val();
			if(search.length>6) {
				$.ajax('https://network4rentals.com/network/ajax/get-landlords-search/'+search, {
					dataType: "json",
					success: function(response) {
						
						var response = $.parseJSON(response);
						if(response[0]['id'].length>0) {
							
							var suggestions = '<ul>';
							for(var i=0;i<response.length;i++) {
								suggestions += '<li class="suggestion-selection" onclick="landlordSelect('+response[0]['id']+')">'+response[0]['bName']+'</li>';
							}
							suggestions += '</ul>';
							$('#suggestions').html(suggestions);
							$('#suggestion-window').modal('show');
						}				
					},
					error: function(request, errorType, errorMessage) {
						$('.thinking2').html('No Landlord Found').fadeIn();
					},
					timeout: 6000,
					beforeSend: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
					},
					complete: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
					}
				});
			}
		}
	});
	
	$('#email').focusout(function() {
		var check = $('#landlord-id').val();
		var search = $(this).val();
		if(search.length==0) {
			$('#phoneError').html('Either the cell phone or email address is required.');
		}
		if(check.length==0) {
			$('#phoneError').html('');
			
			search = search.replace("@", "%7c");
			if(search.length>6) {
				$.ajax('https://network4rentals.com/network/ajax/search-by-email/'+search, {
					dataType: "json",
					success: function(response) {
						
						var response = $.parseJSON(response);
						if(response['id'].length>0) {
							var suggestions = '<ul>';
							
							suggestions += '<li class="suggestion-selection" onclick="landlordSelect('+response['id']+')">'+response['name']+' | '+response['email']+'</li>';
							
							suggestions += '</ul>';
							$('#suggestions').html(suggestions);
							$('#suggestion-window').modal('show');
						}				
					},
					error: function(request, errorType, errorMessage) {
						$('.thinking2').html('No Landlord Found').fadeIn();
					},
					timeout: 6000,
					beforeSend: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
					},
					complete: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
					}
				});
			}
		}
	});	
	
	$('.money').blur(function() {
		$('.money').formatCurrency({
			symbol: ''
		});
	});
	
	$('.landlordDetails .phone').focusout(function() {
		var check = $('#landlord-id').val();
		if(check.length>8) {
			var search = $(this).val();
			search = search.replace("(", "");
			search = search.replace(")", "");
			search = search.replace("-", "");
			search = search.replace(" ", "");
			if(search.length==10) {
				$.ajax('https://network4rentals.com/network/ajax/search-by-phone/'+search, {
					dataType: "json",
					success: function(response) {
						var response = $.parseJSON(response);
						if(response['id'].length>0) {
							var suggestions = '<ul>';
							suggestions += '<li class="suggestion-selection" onclick="landlordSelect('+response['id']+')">'+response['name']+' | '+response['email']+' | '+response['bName']+'</li>';
							suggestions += '</ul>';
							$('#suggestions').html(suggestions);
							$('#suggestion-window').modal('show');
						}				
					},
					error: function(request, errorType, errorMessage) {
						$('.thinking2').html('No Landlord Found').fadeIn();
					},
					timeout: 6000,
					beforeSend: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
					},
					complete: function() {
						$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
					}
				});
			}
		}
	});	
	/*
	$('.current-residence').change(function(){
		//$('.saveLandlord').css({'display':'none'});
		var check = $(this).val();
		if(check == 'y') {
			$.ajax('https://network4rentals.com/network/ajax/check-current-residence/', {
				dataType: "json",
				success: function(response) {
					var response = $.parseJSON(response);
					if(response == 'y') {
						$('#info').modal('show');
						$('.saveLandlord').css({'display':'none'});
						$('.res-error').html('<i class="fa fa-exclamation-triangle"></i> You can not add a current residence because you have not marked one of your previous address with your move out date. Please fix this error then add your new address.');
					} else {
						$('.saveLandlord').css({'display':'block'});
						$('.res-error').html('');
					}
				},
				error: function(request, errorType, errorMessage) {
					$('.thinking2').html('No Landlord Found').fadeIn();
				},
				timeout: 6000,
				beforeSend: function() {
					$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
				},
				complete: function() {
					$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
				}
			});
		} else {
			$('.saveLandlord').css({'display':'block'});
			$('.res-error').html('');
		}
	}); */
	
	$('#cell-phone').focusout(function() {
		var email = $('#email').val();
		var cell = $(this).val();
		if(email.length==0) {
			cell = cell.replace(/\D/g,"");
			if(cell.length>9) {
				$.ajax('https://network4rentals.com/network/ajax/check-cell-phone/'+cell, {
					dataType: "json",
					success: function(response) {
						var response = $.parseJSON(response);
						if(response!=1) {
							var new_email = response['number']+response['domain'];
							$('#email').val(new_email);
							$('#phoneError').html('');
						} else {
							$('#phoneError').html('This phone number does not appear to be a cell phone. You must have a cell phone or an email address in order to add a landlord.');
						}
					},
				});
			}
		}
	});
	
	$.ajax('https://network4rentals.com/network/ajax/check-current-residence/', {
		dataType: "json",
		success: function(response) {
			var response = $.parseJSON(response);
			if(response=='y') {
				$('#info').modal('show');
			}
		}
	});
	
	
	
}); // Document Ready Ends	

function landlordSelect(id) {
	$.ajax('https://network4rentals.com/network/ajax/get-landlords-info/'+id, {
		dataType: "json",
		success: function(response) { 
			$('#bName').val(response['bName']).prop('readonly', true);
			$('#lName').val(response['name']).prop('readonly', true);
			$('#email').val(response['email']).prop('readonly', true);
			$('#phone').val(response['phone']).prop('readonly', true);
			$('#address').val(response['address']).prop('readonly', true);
			$('#city').val(response['city']).prop('readonly', true);
			$('#zip').val(response['zip']).prop('readonly', true);
			$('#state').val(response['state']).attr("disabled", true); 
			$('#landlord-id').val(response['id']).prop('readonly', true);
		},
		error: function(request, errorType, errorMessage) {
			$('.thinking2').html('No Landlord Found').fadeIn();
		},
		timeout: 6000,
		beforeSend: function() {
			$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
		},
		complete: function() {
			$('.thinking2').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
		}
	});
	$('#suggestion-window').modal('hide');
}