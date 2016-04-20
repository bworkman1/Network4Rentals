$(function() {
	$('.sendBy').change(function() {
		var sendBy = $(this).val();
		if(sendBy == 'text') { //send by phone
			addEmailInputs('text');
		} else { //send by email
			addEmailInputs('email');
		}
	});
	
	$( "#inviteTenant" ).on( "submit", function( event ) {
		event.preventDefault();
		var formData = $('#inviteTenant').serialize();
		sendInviteData(formData);
	});
	
});

function sendInviteData(formData) {
	$.ajax({
		type: 'post',
		dataType: 'json',
		url: '//network4rentals.com/network/ajax-landlords/invite-tenant',
		data: formData,
		success: function(response){
			console.log(response);
			if(typeof response.error != 'undefined') {
				var feedback = '<div class="alert alert-danger"><b><i class="fa fa-times"></i></b> '+response.error+'</div>';
				$('#inviteError').html(feedback);
			} else {
				var feedback = '<div class="alert alert-success"><b><i class="fa fa-thumbs-o-up"></i></b> '+response.success+'</div>';
				eraseFormData();
				$('#inviteFeedback').html(feedback);
			}
		},
		error: function(xhr, type, exception) { 
			// if ajax fails display error alert
			var feedback = '<div class="alert alert-danger"><b><i class="fa fa-times"></i></b> There was an error sending your request, try again</div>';
			$('#inviteError').html(feedback);
		},
		timeout: 7000,
		beforeSend: function() {
			$('.sendIt').html('<i class="fa fa-spinner fa-spin"></i> Sending').attr('disabled', true);
		}, 
		complete: function() {
			$('.sendIt').html('<i class="fa fa-envelope"></i> Send Invite').attr('disabled', false);
		}
	});
}

function eraseFormData() {
	$('#selectionInputs').html('');
	$('#inviteTenant input, #inviteTenant select').each(function() {
		$(this).val('');
	});
	$('#inviteTenants').modal('hide');
	$("html, body").animate({ scrollTop: 0 }, "slow");	
}

function addEmailInputs(type) {
	if(type=='email') {
		var inputs = '<div class="row"><div class="col-sm-6"><label><span class="text-danger">*</span> Email:</label><input type="email" name="email" class="form-control" required="required" maxlength="80"></div><div class="col-sm-6"><label><span class="text-danger">*</span> Confirm Email:</label><input type="email" name="email2" class="form-control" required="required" maxlength="80"></div></div>';
	} else {
		var inputs = '<div class="row"><div class="col-sm-6"><label><span class="text-danger">*</span> Cell Phone:</label><input type="text" name="cell" class="form-control phone" required="required" maxlength="16">	</div></div>';
	}
	$('#selectionInputs').html(inputs);
	$('.phone').mask('(999) 999-9999');
	
}