var refresh = null;
$(document).ready(function() {
	//checkForCompletion();
	refresh = setInterval(checkForCompletion, 3000);
	
	$('#resendEmail').click(function() {
		resendEmailClicked();
	});
	
	$('#sendAndChange').click(function() {
		var email = $('#changeEmail .email').val();
		if ($('.update').is(':checked')) {
			var update = $('#changeEmail .update').val();
		} else {
			var update = null;
		}
		resendNewEmail(email, update);
		$('#changeEmail .email').val('');
	});
	
});

function checkForCompletion() {
	$.ajax('https://network4rentals.com/network/ajax/checkForTerms', {
		success: function(response) {
			console.log(response);
			if(response.indexOf('1') > -1) {
				$('.fadeout').hide();
				$('.removeOnSuccess').remove();
				$('.waiting').html('<i class="fa fa-exclamation text-success"></i> Finished');
				$('.agreed').html('Yes').removeClass('label-danger').addClass('label-success');
				$('.makeFull').removeClass('col-sm-6').addClass('col-sm-12');
				$('.changeOnSuccess').removeClass('panel-danger').addClass('panel-success').prev().removeClass('col-sm-6').addClass('col-sm-12');
				$('.makeFull');
				$('.agreedAlert').removeClass('alert-danger').addClass('alert-success');
				clearInterval(refresh); 
				$('.fadeout').fadeIn();
				$.notify("User Agreed: Great Job On The Sale", "success");
			}
		},
		error: function(request, errorType, errorMessage) {
			console.log(errorMessage);
		},
		timeout: 6000
	});
}

function resendEmailClicked() {
	$.ajax('https://network4rentals.com/network/contractors/resend-email', {
		success: function(response) {
			$.notify("Email Sent Successfully", "success");
		},
		error: function(request, errorType, errorMessage) {
			$.notify(errorMessage, "error");
		},
		beforeSend: function() {
			$('#resendEmail').html('<i class="fa fa-spinner fa-spin"></i> Sending').fadeIn();
		},
		complete: function() {
			$('#resendEmail').html('Resend Email').fadeIn();
		}
	});
}

function resendNewEmail(email, update) {
	$.ajax('https://network4rentals.com/network/contractors/resend-email', {
		type: 'POST',
		data: {email: email, update: update},
		success: function(response) {
			if(update=='y') {
				$.notify("Email Sent Successfully And User Account Has Been Updated", "success");
			} else {
				$.notify("Email Sent Successfully", "success");
			}
		},
		error: function(request, errorType, errorMessage) {
			$.notify(errorMessage, "error");
		},
		beforeSend: function() {
			$('#changeThatEmail').html('<i class="fa fa-spinner fa-spin"></i> Sending').fadeIn();
		},
		complete: function() {
			$('#changeThatEmail').html('Change Email Address').fadeIn();
		}
	});
}