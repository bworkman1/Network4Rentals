$(function(){
	$('#login-btn').click(function(e) {
		e.preventDefault();
		var user = $('#user').val();
		var pass = $('#pass').val();
		
		$.ajax({
			url: 'https://network4rentals.com/network/ajax/login_association/',
			cache: false,
			type: "POST",
			dataType: 'json',
			data: {user:user, pass:pass},
			success: function(response) {
				if(typeof response.error == 'undefined') {
					$('#pass-error').html('').css({'position':'relative'});
					window.location.replace("https://network4rentals.com/network/landlord-associations/home/");
					$('#login-btn').html('Forwarding To Login').removeClass('disabled');
				} else {
					$('#pass').val('');
					$('#pass-error').html('Invalid Username &amp; Password').css({'position':'relative'});
				}
			},
			error: function(error, xhrRequest) {
				console.log(error);
				console.log(xhrRequest);
			},
			beforeSend: function() {
				$('#login-btn').html('<i class="fa fa-spinner fa-spin"></i> Logging In').addClass('disabled');
			},
			timeout: 6000,
			complete: function() {
				$('#login-btn').html('Login').removeClass('disabled');
			}
		}); 
	});	
	
	$('#forgot-btn').click(function(e) {
		e.preventDefault();		
		$('#forgot-error').html('');
		$('#forgot-success').html('');
		var formData = $('#forgotPasswordForm').serialize();
		console.log(formData);
		$.ajax({
			url: 'https://network4rentals.com/network/ajax_associations/forgotpass_assocation/',
			cache: false,
			type: "POST",
			dataType: "json",
			data: formData,
			success: function(response) {
				console.log(response);
				if(typeof response.success != 'undefined') {
					processSuccess('#forgot-success', response.success);
				} else {
					processError('#forgot-error', response.error);
				}
			},
			beforeSend: function() {
				$('#forgot-btn').html('<i class="fa fa-spinner fa-spin"></i> Resetting...').addClass('disabled');
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					processError('#forgot-error', 'Reset password timed out, try again');
				} else {
					$('#forgot-error').html('Error processing password reset contact us');
				}
			},
			timeout: 32000,
			complete: function() {
				$('#forgot-btn').html('Reset Password').removeClass('disabled');
			}
		}); 
	});
	
	function processError(elem, values) {
		$(elem).html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i> '+values+'<div class="clearfix"></div></div>');
	}
	function processSuccess(elem, values) {
		$(elem).html('<div class="alert alert-success"><i class="fa fa-check fa-2x pull-left"></i> '+values+'<div class="clearfix"></div></div>');
	}
	
});