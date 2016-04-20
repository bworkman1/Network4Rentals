$(function() {
	
	/* WHEN A USERS SEARCHES FOR A LANDLORD THIS FIRES OFF AND QUIRES THE DATABASE GENERATING THE LIST OF FOUND USERS */
	$('#searchLandlords').click(function(event) {
		event.preventDefault();
		var data = $('#searchMemberForm').serialize();
		var url = '';
		$('#searchError').html();
		$('#searchLandlordResults').html('');
		$.ajax({
			url: '//network4rentals.com/network/ajax-associations/search-for-landlord-assoc/',
			type: 'post',
			dataType: 'json',
			data: data,
			timeout: 1500,
			success: function(response) {
				if(typeof response.none == 'undefined') {
					var user_data = '';
					for(var i=0;i<response.length;i++) {
						if(response[i]['image'] == '') {
							img = 'https://network4rentals.com/network/assets/themes/default/images/ajax-associations-no-user-image.jpg';
						} else {
							img = 'https://network4rentals.com/network/public-images/'+response[i]['image'];
						}
						
						user_data += '<li data-landlordid="'+response[i]['id']+'">';
							user_data += '<img src="'+img+'" alt="" height="50" width="50" class="pull-left" style="padding-right: 10px">';
							user_data += '<h4><b>'+response[i]['name']+'</b></h4>';
							user_data += response[i]['city']+', '+response[i]['city'];
							if(response[i]['bName'] != '') {
								user_data += ' | '+response[i]['bName'];
							}
							user_data += '<div class="clearfix"></div>';
						user_data += '</li>';
					}
					$('#searchError').html('<h3>Select a Result Below to add</h3>');
					$('#searchLandlordResults').html(user_data);
				} else {
					$('#searchError').html('<br><div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> No results found</div>');
				}
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#searchError').html('<br><div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Search timed out, try agin</div>');
				} else {
					$('#searchError').html('<br><div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error searching for results, try agin</div>');
				}
			},
			complete: function() {
				$('#searchLandlords').attr('disabled', false);
				$('#searchLandlords').html('<i class="fa fa-search"></i>');
			},
			beforeSend: function() {
				$('#searchLandlords').attr('disabled', true);
				$('#searchLandlords').html('<i class="fa fa-cog fa-spin"></i>');
			}
		});
	});

	$('ul#searchLandlordResults').on('click', 'li', function() {
		var user_id = $(this).data('landlordid');
		$.ajax({
			url: '//network4rentals.com/network/ajax-associations/grab-landlord-details',
			type: 'post',
			dataType: 'json',
			data: {'id':user_id},
			timeout: 15000,
			success: function(response) {
				$('#addMemeberForm').find('#name').val(response.name).attr('readonly', true);
				$('#addMemeberForm').find('#email').val(response.email).attr('readonly', true);
				$('#addMemeberForm').find('#city').val(response.city).attr('readonly', true);
				$('#addMemeberForm').find('#state').val(response.state).attr('readonly', true);
				$('#addMemeberForm').find('#zip').val(response.zip).attr('readonly', true);
				$('#addMemeberForm').find('#landlord_id').val(user_id);
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$.notify("Request timed out, try again", "error");
				} else {
					$.notify("Something went wrong, try again", "error");
				}
			},
			complete: function() {
				
			},
			beforeSend: function() {
				
			}
		});	
		
		openMemberDetails();
	});

	$('#cantFind').click(function(event) {
		event.preventDefault();
		openMemberDetails();
	});
	
	$('#addMemeber').on('hidden.bs.modal', function () {
		resetAddMemberFields();
	});
	
	$('#addNewCategoryLink').click(function() {
		$(this).slideUp();
		$('#catFeedback').html('<div class="form-group has-success"><label class="col-lg-6 control-label text-right" for="addNewCat">Category Name:</label><div class="col-lg-6"><div class="input-group"><input type="text" id="addNewCat" maxlength="20" class="form-control"> <span class="input-group-addon addNewCategoryButton"><i class="fa fa-plus"></i></span></div></div>').slideDown();
	});
	
	$('#catFeedback').on('click', '.addNewCategoryButton', function() {
		var cat = $('#catFeedback #addNewCat').val();
		if(cat != '') {
			if(cat.length>1) {
				$('#add_member_type').append('<option selected>'+cat+'</option>').parent().parent().addClass('has-success');
				$('#catFeedback').slideUp().html('');
				$('#addNewCategoryLink').slideDown();
			} else {
				$('#catFeedback #addNewCat').addClass('has-error');
			}
		} else {
			$('#catFeedback #addNewCat').addClass('has-error');
		}
	});

	$('#member-settings').on('click', '#addMemberCategoryBtnEdit', function() {
		$(this).after('<div id="addCategoryInputBox" class="form-group has-feedback"><div class="col-sm-8 col-sm-offset-4"><div class="input-group"><input type="text" class="form-control" id="newCategoryInput" placeholder="Category Name" /><span id="addCategorybtn" class="input-group-addon"><i class="fa fa-plus"></i> Add</span></div></div></div>');
		$('#member-settings').find('#addMemberCategoryBtnEdit').slideUp();
	});
	
	$('#member-settings').on('click', '#addCategorybtn', function() {
		var cat = $('#member-settings').find('#newCategoryInput').val();
		if(cat != '') {
			$('#member-settings').find('#member_type').append('<option selected>'+cat+'</option>');
			$('#member-settings').find('#newCategoryInput').val('');
			$('#member-settings').find('#addCategoryInputBox').remove();
			$('#member-settings').find('#addMemberCategoryBtnEdit').slideDown();
		} else {
			$('#member-settings').find('#addCategoryInputBox').addClass('has-error');
		}
	});
	
	$('.memberSettings').click(function() {
		$('#member-settings').slideUp();
		$('.deleteMember').slideUp();
		var memberId = $(this).data('id');
		var elem = $(this);
		if(memberId>0) {
			$.ajax({
				url: '//network4rentals.com/network/ajax-associations/landlord-assocations-get-memeber-details',
				type: 'post',
				dataType: 'json',
				data: {'id':memberId},
				timeout: 15000,
				success: function(response) {
					var lockDetails = true;
					if(response.registered_landlord_id>0) {
						$('.registeredMember').css({'display':'block'});
						
					} else {
						$('.registeredMember').css({'display':'none'});
						var lockDetails = false;
					}
					if(response.custom_values == 'y' || response.registered_landlord_id == 0) {
						
						$('#showName').val(response.name);
						$('#showEmail').val(response.email);
						$('#showPhone').val(response.phone).mask('(999) 999-9999');
						$('#showAddress').val(response.address);
						$('#showCity').val(response.city);
						$('#showState').val(response.state);
						$('#showZip').val(response.zip);
						
					} else {
						if(response.accepted == 'y') {
							$('#showEmail').val(response.email).attr('readonly', lockDetails);
							$('#showPhone').val(response.phone).attr('readonly', lockDetails).mask('(999) 999-9999');
							$('#showAddress').val(response.address).attr('readonly', lockDetails);
						} else {
							$('#showEmail').val('').attr('readonly', lockDetails);
							$('#showPhone').val('').attr('readonly', lockDetails).mask('(999) 999-9999');
							$('#showAddress').val('').attr('readonly', lockDetails);
						}
						
						$('#showName').val(response.name).attr('readonly', true);
						$('#showCity').val(response.city).attr('readonly', true);
						$("#showState").val(response.state).attr('disabled', true);
						$('#showZip').val(response.zip).attr('readonly', true);
					}
					
					$('#showPosition').val(response.position);
					$('#showId').val(response.id);
					$('#showPosition').val(response.position);
					$('#showActive').val(response.active);
					$('#showBadge').val(response.show_badge);
					$('#showDate').val(response.due_date);
					$('#member_type').val(response.member_type);
					$('#payment-amount').val(response.payment_amount);
					$("#custom_values").val(response.custom_values);
					
					$('.deleteMember').attr('href', 'https://network4rentals.com/network/landlord-associations/delete-member/'+response.id+'/').slideDown();
					
					$('#member-settings').slideDown();
				},
				error: function(x, t, m) {
					if(t==="timeout") {
						$.notify("Request timed out, try again", "error");
					} else {
						$.notify("Something went wrong, try again", "error");
					}
				},
				complete: function() {
					elem.find('i').removeClass('fa-spin');
				},
				beforeSend: function() {
					elem.find('i').addClass('fa-spin');
				}
			});
		} else {
			
		}
	});
	
	$('#custom_values').change(function() {
		console.log($(this).val());
		if($(this).val()=='n') {
			$('#showName').attr('readonly', true);
			$('#showEmail').attr('readonly', true);
			$('#showPhone').attr('readonly', true).mask('(999) 999-9999');
			$('#showAddress').attr('readonly', true);
			$('#showCity').attr('readonly', true);
			$("#showState").attr('disabled', true);
			$('#showZip').attr('readonly', true);
		} else {
			$('#showName').attr('readonly', false);
			$('#showEmail').attr('readonly', false);
			$('#showPhone').attr('readonly', false);
			$('#showAddress').attr('readonly', false);	
			$('#showCity').attr('readonly', false);	
			$('#showState').attr('disabled', false);
			$('#showZip').attr('readonly', false);				
		}
	});
	
});


function openMemberDetails() {
	$('#searchError').html('');
	$('#searchLandlordResults').html('');
	$('#searchFor').val('');
	$('.results').slideUp();
	$('#addMemeberForm').slideDown();
}

function resetAddMemberFields() {
	$('.results').slideDown();
	$('#addMemeberForm').slideUp();
	$('#searchError').html('');
	$('#searchLandlordResults').html('');
	$('#searchFor').val('');
}