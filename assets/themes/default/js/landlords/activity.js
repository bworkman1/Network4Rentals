$(function() {
	
	$('#whatnow').modal('show');
	
	$('.assoc-invite').click(function() {
		var invite_id = $(this).data('inviteid');
		$.ajax({
			url: 'https://network4rentals.com/network/ajax_landlords/assoc_invite/',
			cache: false,
			type: "POST",
			dataType: "json",
			data: {id:invite_id},
			success: function(response) {
				if(response.accepted == 'y') {
					var data = formatInviteModal(response, invite_id, true);
				} else {
					var data = formatInviteModal(response, invite_id, false);
				}
				$('#inviteData').html(data);
				$('#acceptInvite .modal-title').html('Join '+response.title);
				$('#acceptInvite').modal('show');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				
			},
			beforeSend: function() {
				$(this).html('<i class="fa fa-spinner fa-spin"></i> Loading');
			},
			timeout: 6000,
			complete: function() {
				$(this).html('View Invite');
			}
		});
		
	});
	
	
	
});

function formatInviteModal(obj, id, accepted) {
	var modal = '';
	if(obj.logo != '') {
		modal += '<img src="https://network4rentals.com/network/public-images/'+obj.logo+'" class="img-responsive pull-left" height="100" width="100" alt="'+obj.title+'">';
	}
	modal += '<p><b>Contact:</b> '+obj.name+'</p>';
	modal += '<p><b>Address:</b> '+obj.address+' '+obj.city+', '+obj.state+' '+obj.zip+'</p>';
	formattedPhone = obj.phone.substr(0, 3) + '-' + obj.phone.substr(3, 3) + '-' + obj.phone.substr(6,4);
	modal += '<p><b>Phone:</b> '+formattedPhone+'</p>';
	modal += '<hr>';
	if(obj.link != '') {
		modal += '<a href="http://n4r.rentals/'+obj.link+'" target="_blank" class="btn btn-primary"><i class="fa fa-link"></i> Learn More</a>';
	}
	modal += '<hr>';
	if(accepted) {
		modal += '<div class="alert alert-success"><i class="fa fa-thumbs-up fa-fw fa-2x"></i> Invite already accepted</div>';
	} else {	
		modal += '<div class="row">';
			modal += '<div class="col-md-6">';
				modal += '<a href="https://network4rentals.com/network/landlords/assoc-invite/1/'+id+'" class="btn btn-danger btn-block">Decline</a>';
			modal += '</div>';
			modal += '<div class="col-md-6">';
				modal += '<a href="https://network4rentals.com/network/landlords/assoc-invite/2/'+id+'" class="btn btn-success btn-block">Accept</a>';
			modal += '</div>';
		modal += '</div>';
	}
	
	return modal;
}