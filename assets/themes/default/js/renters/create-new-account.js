$(function() {
	var baseUrl = 'https://network4rentals.com/network/assets/themes/default/page-loads/'
	$('#textMsg').change(function () {
		if ($('#textMsg').is(':checked')) {
			$('#phone').html('<div class="form-group"><input class="form-control phone" name="cell" placeholder="Cell Phone" type="text" required/></div>').slideDown();
			$('#dynamicContent').find('.phone').mask('(999) 999-9999');
		} else {
			$('#phone').slideUp().delay(200).html('');
		}
	});
	
	$('#dynamicContent').on('submit', '#confirmCode', function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			url: '//network4rentals.com/network/ajax_renters/confirm-new-account/',
			cache: false,
			type: "POST",
			dataType: "json",
			data: data,
			success: function(response) {
				if(typeof response.success != 'undefined') {
					$('#dynamicContent').find('#form-feedback').html('');
					window.location.href = "https://network4rentals.com/network/renters/activity";
				} else {
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+response.error+'</div>');
				}
			},
			timeout: 15000,
			beforeSend: function() {
				$('#dynamicContent').find('#confirmCodeBtn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Checking').attr('disabled', true);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Your request timed out, try again</div>');
				} else {
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Something went wrong, try again</div>');
				}
			},
			complete: function() {
				$('#dynamicContent').find('#confirmCodeBtn').html('Confirm').attr('disabled', false);
			}
		});
	});
	
	$('#signUp').submit(function(event) {
		event.preventDefault();
		var data = $(this).serialize();

		$.ajax({
			url: '//network4rentals.com/network/ajax-renters/create-new-account/',
			cache: false,
			type: "POST",
			dataType: "json",
			data: data,
			success: function(response) {
				console.log(response);
				if(typeof response.success != 'undefined') {
					console.log('ERROR GOES HERE');
				} else if(typeof response.registered != 'undefined') {
					console.log('Registered');
				} else {
					console.log('Success Goes Here');
				}
				
				if(typeof response.success != 'undefined') {
					
					$('#dynamicContent').load(baseUrl+'confirm-account.html');
					if(response.success == 'cell') {
						setInterval(function(){ $('#dynamicContent').find('#confirmByPhone').remove(); }, 500);
					}
					$('#dynamicContent').find('#form-feedback').html('');
					
				} else if(typeof response.registered != 'undefined') {
					
					$('#dynamicContent').load(baseUrl+'confirm-account.html');
					if(response.success == 'cell') {
						setInterval(function(){ $('#dynamicContent').find('#confirmByPhone').remove(); }, 500);
					}
					$('#dynamicContent').find('#form-feedback').html('');
					
				} else {
					
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+response.error+'</div>');
					
				}
			},
			beforeSend: function() {
				$('#createAccountBtn').html('<i class="fa fa-circle-o-notch fa-spin"></i> Creating Account').attr('disabled', true);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Your request timed out, try again</div>');
				} else {
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Something went wrong, try again</div>');
				}
			},
			complete: function() {
				$('#createAccountBtn').html('Create Account').attr('disabled', false);
			}
		});		
	});
	
	if($('#dynamicContent').data('created')==true) {
		$('#dynamicContent').load(baseUrl+'confirm-account.html');
		if($('#dynamicContent').data('cell')==true) {
			setInterval(function(){ $('#dynamicContent').find('#confirmByPhone').remove(); }, 500);
		}
	}
	
	$('#dynamicContent').on('click', '#confirmByPhone', function(event) {
		event.preventDefault();
		$(this).slideUp();
		$('#switchToSMS').load(baseUrl+'/switch-to-sms.html');
		$('#dynamicContent').children('#confirmCode').attr('placeholder', 'Code from SMS');
	});
	
	$('#dynamicContent').on('focus', '.phone', function() {
		$('#dynamicContent').find('.phone').mask('(999) 999-9999');
	});
	
	$('#dynamicContent').on('submit', '#switchConfirmSms', function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		$.ajax({
			url: '//network4rentals.com/network/ajax-renters/switch-to-sms/',
			cache: false,
			type: "POST",
			dataType: "json",
			data: data,
			success: function(response) {
				if(typeof response.success != 'undefined') {
					$('#dynamicContent').find('#form-feedback').slideUp();
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Text message sent</div>').slideDown();
					$('#switchToSMS').slideUp();
				} else {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+response.error+'</div>').slideDown();
				}
			},
			timeout: 15000,
			beforeSend: function() {
				$('#dynamicContent').find('#addCellPhone').html('<i class="fa fa-circle-o-notch fa-spin"></i> Sending').attr('disabled', true);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Your request timed out, try again</div>').slideDown();
				} else {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Something went wrong, try again</div>').slideDown();
				}
			},
			complete: function() {
				$('#dynamicContent').find('#addCellPhone').html('Submit').attr('disabled', false);
			}
		});		
		$(this).remove();
	});
	
	$('#dynamicContent').on('click', '#resend', function(event) {
		event.preventDefault();
		$.ajax({
			url: '//network4rentals.com/network/ajax-renters/resend-confirm-code/',
			cache: false,
			type: "POST",
			dataType: "json",
			success: function(response) {
				if(typeof response.success != 'undefined') {
					$('#dynamicContent').find('#form-feedback').slideUp();
					$('#dynamicContent').find('#form-feedback').html('<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> Code sent again</div>').slideDown();
					$('#switchToSMS').slideUp();
				} else {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+response.error+'</div>').slideDown();
				}
			},
			timeout: 15000,
			beforeSend: function() {
				$('#dynamicContent').find('#addCellPhone').html('<i class="fa fa-circle-o-notch fa-spin"></i> Sending').attr('disabled', true);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Your request timed out, try again</div>').slideDown();
				} else {
					$('#dynamicContent').find('#form-feedback').slideUp().html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Something went wrong, try again</div>').slideDown();
				}
			},
			complete: function() {
				$('#dynamicContent').find('#addCellPhone').html('Submit').attr('disabled', false);
			}
		});			
	});
	
	
	
});