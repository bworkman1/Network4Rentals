$(document).ready(function() {
	var timer;
	$('#fullname').focus();
	$('#landlord-search-step').keyup(function() {
		$('.thinking').html('<h4><i class="fa fa-spinner fa-spin"></i> Loading... </h4>').fadeIn();
		timer && clearTimeout(timer);
		var search = $('#landlord-search-step').val();
		$('.no-landlord').css({'display':'block'});
		if(search.length > 2) {
			setTimeout(function()  {
				$.ajax('//network4rentals.com/network/ajax/get_landlords_search_steps/'+search, {
					dataType: "json",
					success: function(response) {
						var response = $.parseJSON(response);
						console.log(response);
						if(response) {
							if(response.length>0) {
								var show_renters = '<hr><h3 class="found-title"><i class="fa fa-thumbs-o-up"></i> Landlords Found - Select One Below</h3>';
								show_renters += '<ul class="n-results">';
								for(var i=0;i<response.length;i++) {
									show_renters += '<li id="'+response[i]['link_id']+'" onclick="addloadLandlordInfo('+response[i]['link_id']+', '+response[i]['group_id']+', '+i+')" data-listitem="'+i+'"><i class="fa fa-minus"></i> ' +response[i]['name']+' -  '+response[i]['display_name']+' - '+response[i]['city']+' '+response[i]['zip']+'<span class="pull-right"><i class="fa fa-plus add-landlord"></i></span></li>';								
								}
							} else {
								var show_renters = '<ul class="n-results">';
								show_renters += '<li class="noneFound" style="cursor: initial !important;"><h3><i class="fa fa-frown-o text-danger"></i> No Landlords Found </h3>Try using a less descriptive search for example, search for "Netwrok" instead of the full "Network 4 Rentals"</li>';
								$('.thinking').html('<h4><i class="fa fa-spinner fa-spin"></i> Loading... </h4> ').fadeOut();
							}
						} else {
							var show_renters = '<ul class="n-results">';
							show_renters += '<li class="noneFound" style="cursor: initial !important;"><h3><i class="fa fa-frown-o text-danger"></i> No Landlords Found </h3>Try using a less descriptive search for example, search for "Network" instead of the full "Network 4 Rentals"</li>';
						}
						show_renters += '</ul>';
						$('#landlord-results').html(show_renters);
					},
					error: function(request, errorType, errorMessage) {
						$('.thinking').html('No Landlord Found').fadeIn();
					},
					timeout: 6000,
					beforeSend: function() {
						$('.thinking').html('<h4><i class="fa fa-spinner fa-spin"></i> Loading... </h4>').fadeIn();
					},
					complete: function() {
						$('.thinking').html('<h4><i class="fa fa-spinner fa-spin"></i> Loading... </h4>').fadeOut();
					}
				});
			}, 1000);
		}
		var show_renters = '<ul>';
		show_renters += '<li></li>';
		show_renters += '</ul>';
		$('#landlord-results').html(show_renters);
	});
	
	$('.gotoStep').click(function(event) {
		event.preventDefault();
		if($(this).hasClass('proceed')) {
			var arg1 = $(this).data('arg1');
			var arg2 = $(this).data('arg2');
			var arg3 = $(this).data('arg3');
			resetActives(arg1, arg2, arg3);
		} else {
			$.notify("Please finish the steps before this one.", "error",  { globalPosition: "top center" });
		}
	});
	
	// Realtime feedback
	$('#username').focusout(function() {
		check_username($(this).val());
	});
	
	$('#fullname').focusout(function() {
		if($(this).val().length<5) {
			$('#fullname').parent().addClass('has-error');
			$('#fullname').next().html('First and last name is required');
			
		} else {
			$('#fullname').parent().addClass('has-success').removeClass('has-error');
			$('#fullname').next().html('');
		}
	});
	
	$('#phone').focusout(function() {
		var phone = $(this).val()
		phone = phone.replace(/\D/g,'');
		if($(window).width<767) {
			$(this).val(phone);
		}
		if(phone.length != 10) {
			$('#phone').parent().addClass('has-error');
			$('#phone').next().html('A valid phone is required');
		} else {
			$('#phone').parent().addClass('has-success').removeClass('has-error');
			$('#phone').next().html('');
		}
	});
	
	$('#email').focusout(function() {
		email = $(this).val();
		email = email.replace(' ', '');
		$(this).val(email);
		if(IsEmail(email)) {
			check_email(email);
		} else {
			$('#email').parent().addClass('has-error');
			$('#email').next().html('A valid email is required');
		}
	});
	
	$('#hear').focusout(function() {
		if($(this).val() == '') {
			$('#hear').parent().addClass('has-error');
			$('#hear').next().html('You must select how you heard about us');
		} else {
			$('#hear').parent().addClass('has-success').removeClass('has-error');
			$('#hear').next().html('');
		}
	});
	
	$('#landlord_email').focusout(function() {
		if($(this).val().length>0) {
			if(IsEmail($(this).val())) {
				check_for_landlord_email($(this).val());
			} else {
				$('#landlord_email').parent().addClass('has-error');
				$('#landlord_email').next().html('A valid email or cell phone number is required');
			}
		}
	});
	
	$('#landlord_cell').focusout(function() {
		if($(this).val().length===14) {
			if($('#landlord-id').val()=='') {
				var check = checkPhoneNumber($(this).val());
				if(check !== 1) {
					checkIfCell($(this).val()); 
				}
			}
		} else {
			$('#landlord_cell').parent().removeClass('has-error');
			$('#landlord_cell').next().html('');
		}
	});
	
	$('#landlord_name').focusout(function() {
		if($(this).val().length=='') {
			$('#landlord_name').parent().addClass('has-error');
			$('#landlord_name').next().html('Landlords name is required');
		} else {
			$('#landlord_name').parent().addClass('has-success').removeClass('has-error');
			$('#landlord_name').next().html('');
		}
	});
	
	$('#landlord_city').focusout(function() {
		if($(this).val()=='') {
			$('#landlord_city').parent().addClass('has-error');
			$('#landlord_city').next().html('City is required');
		} else {
			$('#landlord_city').parent().addClass('has-success').removeClass('has-error');
			$('#landlord_city').next().html('');
		}
	});
	
	$('#landlord_zip').focusout(function() {
		if($(this).val()=='') {
			$('#landlord_zip').parent().addClass('has-error');
			$('#landlord_zip').next().html('Zip is required');
		} else {
			$('#landlord_zip').parent().addClass('has-success').removeClass('has-error');
			$('#landlord_zip').next().html('');
		}
	});
	
	$('#landlord_state').focusout(function() {
		if($(this).val()=='') {
			$('#landlord_state').parent().addClass('has-error');
			$('#landlord_state').next().html('State is required');
		} else {
			$('#landlord_state').parent().addClass('has-success').removeClass('has-error');
			$('#landlord_state').next().html('');
		}
	});
	
	$('#terms').click(function() {
		if($(this).val()!='') {
			$('#terms').next().html('');
		}
	});
	
	$('#terms').change(function() {
		$('#terms').next().html('');
	});
	
	$('#email-suggestions').on('click', '.addThisLandlord', function() {
		var id = $(this).data('landlordid');
		$('#landlord-id').val(id);
		addloadLandlordInfo(id, null);
		$('#suggestion-window').modal('hide');
	});
	
	$('#landlordProperties').on('change', 'select', function() {
		var propertyId = $(this).val();
		get_property_details(propertyId);
	});
	
	// Next Step Functions
	$('.stepProceed2').click(function(event) {
		event.preventDefault();
		var arg1 = $(this);
		var arg2 = $(this).data('arg2');
		var arg3 = $(this).data('arg3');
		 
		var continue_step = true;
		var names = {};
		
		
		
		$('#step-1 input, #step-1 select').each(function() {
			if($(this).parent().hasClass('has-error')) {
				continue_step = false;
			}
			var input_name = $(this).attr('name');
			names[input_name] = $(this).val();
		});
		
		if(names.fullname.length<5) {
			$('#fullname').parent().addClass('has-error');
			$('#fullname').next().html('First and last name is required');
			continue_step = false;
		} else {
			$('#fullname').parent().addClass('has-success').removeClass('has-error');
			$('#fullname').next().html('');
		}
		
		if(IsEmail(names.email) === false) {
			$('#email').parent().addClass('has-error');
			$('#email').next().html('A valid email is required');
			continue_step = false;
		} else {
			$('#email').parent().addClass('has-success').removeClass('has-error');
			$('#email').next().html('');
		}
		if(check_email(names.email)===false) {
			continue_step = false;
		}
		
		if($('#terms').prop('checked')) {
			$('#terms').parent().removeClass('has-error');
			$('#terms').next().html('');
		} else {
			$('#terms').parent().addClass('has-error');
			$('#terms').next().html('You must agree to the terms');
			continue_step = false;
		}
		
		names.phone = names.phone.replace(/\D/g,'');
		if(names.phone.length != 10) {
			$('#phone').parent().addClass('has-error');
			$('#phone').next().html('A valid phone is required');
			continue_step = false;
		}  else {
			$('#phone').parent().addClass('has-success').removeClass('has-error');
			$('#phone').next().html('');
		}
		
		if(names.hear.length==0) {
			$('#hear').parent().addClass('has-error');
			$('#hear').next().html('Select how you heard about us');
			continue_step = false;
		} 	else {
			$('#hear').parent().addClass('has-success').removeClass('has-error');
			$('#hear').next().html('');
		}
		
		if(names.username.length<5) {  
			$('#username').parent().addClass('has-error');
			$('#username').next().html('Name must be at least 6 characters long');
			continue_step = false;
		} else {
			$('#username').parent().addClass('has-success').removeClass('has-error');
			$('#username').next().html('');
		}
		
		if(check_username(names.username)===false) {
			continue_step = false;
		}
		
		if(names.password.length<6) {
			$('#password').parent().addClass('has-error');
			$('#password1').parent().addClass('has-error');
			$('#password').next().html('Password must be at least 6 characters long');
			continue_step = false;
		} else if(names.password != names.password1) {
			$('#password').parent().addClass('has-error');
			$('#password1').parent().addClass('has-error');
			$('#password').next().html('Passwords don\'t match');
			continue_step = false;
		} else {
			$('#password').parent().addClass('has-success').removeClass('has-error');
			$('#password1').parent().addClass('has-success').removeClass('has-error');
			$('#password').next().html('');
		}
		
		var text_messages = $('.textMessages').val();
		if(text_messages=='y') {
			var phone = $('.cellPhone').val();
			phone = phone.replace(/\D/g,'');
			if(phone.length==10) {
				$.ajax({
					url: '//network4rentals.com/network/ajax/check_if_number_can_accept_text/',
					cache: false,
					type: "POST",
					dataType: "json",
					data: {cell:phone},
					success: function(response) {
						console.log(response.Response.carrier_type);
						if(response.Response.carrier_type != 'mobile') {
							$('.cellPhone').addClass('input-error');
							continue_step = false;
						}
					},
					beforeSend: function() {
					
					},
					error: function (xhr, ajaxOptions, thrownError) {
						$.notify('Error looking up your cell phone, try again', 'error');
					},
					timeout: 6000,
					complete: function() {
						
					}
				});
			} else {
				$('.cellPhone').addClass('input-error');
				continue_step = false;
			}
		}
		
		//continue_step=true;
		if(continue_step) {
			if($('.landlordSelect ').val()=='y') {
				var list_items = '<li><b>Full Name:</b> '+names.fullname+'</li>';
				list_items += '<li><b>Email:</b> '+names.email+'</li>';
				list_items += '<li><b>Phone:</b> '+names.phone+'</li>';
				list_items += '<li><b>Hear About Us:</b> '+names.hear+'</li>';
				list_items += '<li><b>Username:</b> '+names.username+'</li>';
				list_items += '<li><b>Password:</b> '+names.password.replace(/\+/g, '*');+'</li>';
				list_items += '<li><b>Terms:</b> Agreed</li>';
				
				$('#user-details').html(list_items);
				$('.top-steps #div1').addClass('proceed');
				$('.top-steps #div2').addClass('proceed'); 
				$.notify("Next Step, Landlord Info", "success",  { globalPosition: "top center" });
				$('#landlord-search-step').focus();
				resetActives(arg1, arg2, arg3);
			}
		}
		
		
	});
	
	$('.stepProceed3').click(function(event) {
		event.preventDefault();
		var arg1 = $(this);
		var arg2 = $(this).data('arg2');
		var arg3 = $(this).data('arg3');
		
		var continue_step = false;
		if($('#squaredThree').is(':checked')) {
			if($('#hear').val() == 'Landlord/Mgr. Request') {
				continue_step = false;
				$('#info').modal('show');
			} else {
				continue_step = true;
			}
		} else {
			var landlord_id = $('#landlord-id').val();
			if(landlord_id) {
				continue_step = true;
			} else {
				$.notify("Find your landlord or check the checkbox below! ", "error",  { globalPosition: "top center" });
			}
		}
		
		var showError = false;
		$('#step-1 input').each(function() {
			if($(this).parent().hasClass('has-error')) {
				continue_step=false;
				showError = true;
			}
		}); 
		if(showError){
			$.notify("You have an error on the previous form, go back and fix it using the icon menu.", "error",  { globalPosition: "top center" });
		}
		
		if(continue_step) {
			$('.top-steps #div3').addClass('proceed');
			$.notify("Next Step, Landlord Info", "success",  { globalPosition: "top center" });
			resetActives(arg1, arg2, arg3);
		}
	});
	
	$('.stepProceed4').click(function(event) {
		event.preventDefault();
		var arg1 = $(this);
		var arg2 = $(this).data('arg2');
		var arg3 = $(this).data('arg3');
			
		var data = [];
		data['cell'] = $('input[name="landlord_cell"]').val();
		data['landlord_email'] = $('input[name="landlord_email"]').val();
		data['name'] = $('input[name="landlord_name"]').val();
		data['landlord_city'] = $('input[name="landlord_city"]').val();
		data['state'] = $('select[name="landlord_state"]').val();
		data['zip'] = $('input[name="landlord_zip"]').val();
		data['group_id'] = $('input[name="group_id"]').val();
		data['landlord_id'] = $('input[name="link_id"]').val();
		data['landlord_address'] = $('input[name="landlord_address"]').val();
		data['landlord_bname'] = $('input[name="bName"]').val();
		data['landlord_phone'] = $('input[name="landlord_phone"]').val();
		
		var dataset = 
		{
		  "landlord" : {
				  "cell": data['cell'],
				  "landlord_email": data['landlord_email'],
				  "name": data['name'] ,
				  "landlord_city": data['landlord_city'],
				  "landlord_state": data['state'],
				  "landlord_zip": data['zip'],
				  "landlord_address": data['landlord_address'],
				  "landlord_phone": data['landlord_phone'],
				  "bName": data['landlord_bname'],
				  "landlord_name": data['name'],
				}
		};
		
		var error = [];
		var valid_form = true;
		var valid_email = true;
		
		
	
		if(!IsEmail(data.landlord_email)) {
			valid_email = false;
			if(data.landlord_email !='') {
				error.push('Invalid Email');
				valid_form = false;
				$('input[name="landlord_email"]').addClass('input-error');
			} else {
				check_for_landlord_email($('#landlord_email').val());
			}
		} else {
			$('input[name="landlord_email"]').removeClass('input-error');
		}
		
		
		if(data.cell.length != 14 && valid_email === false) {
			$('input[name="landlord_email"]').addClass('input-error');
			$('input[name="landlord_cell"]').addClass('input-error');
			valid_form = false;
			$('#landlordContactInfo').modal('show');
			error.push('A valid email or cell is required to continue');
		} else {
			$('input[name="landlord_email"]').removeClass('input-error');
			$('input[name="landlord_cell"]').removeClass('input-error');		
		}
		
		if(data.landlord_city=='') {
			valid_form = false;
			$('input[name="landlord_city"]').addClass('input-error');	
			error.push('Landlord city is required');
		} else {
			$('input[name="landlord_city"]').removeClass('input-error');	
		}	
		
		if(data.state == '') {
			valid_form = false;
			$('select[name="landlord_state"]').addClass('input-error');	
			error.push('Landlord state is required');
		} else {
			$('select[name="landlord_state"]').removeClass('input-error');	
		}

		if(data.zip == '') {
			valid_form = false;
			$('input[name="landlord_zip"]').addClass('input-error');	
			error.push('Landlord state is required');
		} else {
			$('input[name="landlord_zip"]').removeClass('input-error');	
		}
		
		if(data.name=='') {
			valid_form = false;
			$('input[name="landlord_name"]').addClass('input-error');	
			error.push('Full contact/landlord name required');
		} else {
			$('input[name="landlord_name"]').removeClass('input-error');	
		}
	
		$('#step-3 input').each(function() {
			if($(this).parent().hasClass('has-error')) {
				valid_form=false;
				error.push('Landlord email is already a registered user');
			}
		});	
			
		if(valid_form) {
			add_to_final_step(dataset, 'landlord');
			$('.top-steps #div4').addClass('proceed');
			$.notify("Next Step, Rental Details", "success",  { globalPosition: "top center" });
			resetActives(arg1, arg2, arg3);
		} else {
			$.notify(error[0], "error");
		}
		
	});
	
	$('.stepProceed5').click(function(event) {
		event.preventDefault();
		var arg1 = $(this);
		var arg2 = $(this).data('arg2');
		var arg3 = $(this).data('arg3');	
		
		var error = [];
		var valid_form = true;
		var dataset = 
		{
			"property" : {
		  
			}
		  }
		
		
		
		$('#step-4 input').each(function() {
			var data_set_name = $(this).attr('name');
			dataset.property[data_set_name] = $(this).val();
			if($(this).attr('required')) {
				if($(this).val().length === 0) {
					$(this).addClass('input-error');
					valid_form = false;
				} else {
					$(this).removeClass('input-error');
				}
			}
			if($(this).parent().hasClass('has-error')) {
				valid_form = false;
			}
		});
		$('#step-4 select').each(function() {
			var data_set_name = $(this).attr('name');
			var selectVal = $(this).val();
			dataset.property[data_set_name] = selectVal;
			if($(this).attr('required')) {
				if($(this).val().length === 0) {
					$(this).parent().addClass('has-error');
					valid_form = false;
				} else {
					$(this).parent().removeClass('has-error');
					$(this).next().html('');
				}
			}
		}); 
		
		var prevStepErrors = false;
		$('#step-3 input').each(function() {
			if($(this).parent().hasClass('has-error')) {
				valid_form = false;
				prevStepErrors = true;
			}
		});
		if(prevStepErrors) {
			valid_form = false;
		}
	
		if(valid_form) {
			add_to_final_step(dataset, 'property');
			$('.top-steps #div5').addClass('proceed');
			$.notify("Next Step, Confirm Details", "success",  { globalPosition: "top center" });
			resetActives(arg1, arg2, arg3);
		} else {
			if(prevStepErrors) {
				$.notify("You have an error on the previous form, go back and fix it using the icon menu.", "error",  { globalPosition: "top center" });
			} else {
				error.push('All fields are required except lease upload');
				$.notify(error[0], "error");
			}
		}
		
	});
	
});
	
	function add_to_final_step(data, side) { 
		if(side == 'landlord') {		 
			$('#landlord-list').html('');
			$('#landlord-list').append('<li><h3>Landord Details</li>');
			$('#landlord-list').append('<li><b>Business Name:</b> '+data.landlord['bName']+'</li>');
			$('#landlord-list').append('<li><b>Landlord Name:</b> '+data.landlord['landlord_name']+'</li>');
			$('#landlord-list').append('<li><b>Landlord Email:</b> '+data.landlord['landlord_email']+'</li>');
			$('#landlord-list').append('<li><b>Phone:</b> '+data.landlord['landlord_phone']+'</li>');
			$('#landlord-list').append('<li><b>Cell Phone:</b> '+data.landlord['cell']+'</li>'); 
			$('#landlord-list').append('<li><b>Landlord Address:</b><br> '+data.landlord['landlord_address']+' '+data.landlord['landlord_city']+' '+data.landlord['landlord_state']+' '+data.landlord['landlord_zip']+'</li>');
		} else {	
			console.log(data);
			$('#rental-list').html('');
			$('#rental-list').append('<li><h3>Rental Details</h3></li>');
			$('#rental-list').append('<li><b>Rental Address:</b><br> '+data.property['rental_address']+' '+data.property['rental_city']+' '+data.property['rental_state']+' '+data.property['rental_zip']+'</li>');
			$('#rental-list').append('<li><b>Moved In:</b> '+data.property['move_in']+'</li>');
			$('#rental-list').append('<li><b>Rent Per Month: </b> $'+data.property['payments']+'</li>');
			$('#rental-list').append('<li><b>Deposit: </b> $'+data.property['deposit']+'</li>');
			$('#rental-list').append('<li><b>Lease: </b> '+data.property['lease']+'</li>');
		}
	}
	
	function addloadLandlordInfo(id, group_id, listItem) {
		
		if(id != '') {
			$.ajax('//network4rentals.com/network/ajax/get_landlords_info/'+id+'/'+group_id, {
				dataType: "json",
				success: function(response) {
					$('.no-landlord').css({'display':'none'});
					
			
					
					$('ul.n-results li').each(function() {
						console.log($(this).data('listitem')+' === '+listItem);
			
						if(listItem != $(this).data('listitem')) {
							$(this).css({'display':'none'});
						} else {
							$(this).addClass('active');
						}
					});
					addLandlordProperties(id, group_id);
					$('.found-title').html('<h3 class="found-title"><i class="fa fa-check text-success"></i>  Landlord Selected</h3>');
					$.notify("Landlord Selected, Continue To The Next Step", "success",  { globalPosition: "top center" });
	
					$('#bName').val(response['display_name']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#bName').next().html('');
					$('#landlord_name').val(response['name']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_name').next().html('');
					$('#landlord_email').val(response['email']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_email').next().html('');
					$('#landlord_phone').val(response['phone']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_phone').next().html('');
					$('#landlord_address').val(response['address']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_address').next().html('');
					$('#landlord_city').val(response['city']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_city').next().html('');
					
					$('#landlord_state option[value='+response['state']+']').attr('selected','selected').attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_state').next().html('');
				
					$('#landlord_zip').val(response['zip']).attr('readonly', true).parent().addClass('has-success').removeClass('has-error');
					$('#landlord_zip').next().html('');
					$('#landlord-id').val(response['link_id'])
					$('#group-id').val(response['group_id']);
					$('#landlord_cell').val(response['cell']).attr('readonly', true).attr('disabled', 'disabled').parent().addClass('has-success').removeClass('has-error');
					$('#landlord_cell').next().html('');		
					
					
					
				},
				error: function(request, errorType, errorMessage) {
					$('.thinking').html('No Landlord Found').fadeIn();
				},
				timeout: 6000,
				beforeSend: function() {
					$('.thinking').html('<h3><i class="fa fa-spinner fa-spin"></i> Loading... </h3>').fadeIn();
				},
				complete: function() {
					$('.thinking').html('');
				}
			});
			
			$('#landlord-search-step').val('');
		}	
	}
	
	function hideSteps() {
		$("div").each(function () {
			if ($(this).hasClass("activeStepInfo")) {
				$(this).removeClass("activeStepInfo");
				$(this).addClass("hiddenStepInfo");
			}
		});
	}

	function get_property_details(id) {
		$.ajax('//network4rentals.com/network/ajax/get_property_info/'+id, {
			dataType: "json",
			success: function(response) {
				$('#address').val(response['address']);
				$('#city').val(response['city']);
				
				$('#rental_state option[value="'+response['stateAbv']+'"]').attr('selected','selected');
				$('#zip').val(response['zipCode']);
				$('#deposit').val(response['deposit']);
				$('#rent').val(response['price']);
			},
			error: function(request, errorType, errorMessage) {
				
			},
			timeout: 6000,
			beforeSend: function() {
	
			},
			complete: function() {

			}
		});
	} 
	
	function addLandlordProperties(id, group_id) {
		$.ajax({
			url: '//network4rentals.com/network/ajax/find_landlord_properties/',
			cache: false,
			type: "POST",
			data: {id:id, group_id:group_id},
			success: function(response) {
				if(response.length>0) {
					var response = JSON.parse(response);
					var list = '<select class="form-control"><option value="">Select Your Address</option>';
					for(var i=0;i<response.length;i++) {
						list += '<option value="'+response[i]['id']+'">'+response[i]['address']+' '+response[i]['city']+' '+response[i]['stateAbv']+'</option>';
					}
					list += '</select>';
					$('#landlordProperties').html(list);
				}
			},
			timeout: 6000,
		});
	}
	
	function resetActives(event, percent, step) {
		$(".progress-bar").css("width", percent + "%").attr("aria-valuenow", percent);
		$(".progress-completed").text(percent + "%");

		$(".top-steps div").each(function () {
			if ($(this).hasClass("activestep")) {
				$(this).removeClass("activestep");
			}
		});
		
		$('.top-steps .col-md-2').each(function () {
			$(this).removeClass('activestep');
		});
		var step_num = step.substr(step.length - 1);
		$('#div'+step_num).addClass('activestep');
		
		hideSteps();
		showCurrentStepInfos(step);
	}
	
	function showCurrentStepInfos(step) {        
		var id = "#" + step;
		$(id).addClass("activeStepInfo");
	}
	
	function check_username(username) {
		if(username.length > 5) {
			$.ajax('//network4rentals.com/network/ajax/check_username_renter/'+username, {
				dataType: "json",
				success: function(response) {
					var response = $.parseJSON(response); 
					if(response == 1) {
						$('#username').parent().addClass('has-error').removeClass('has-success');
						$('#username').next().html('Username is already taken');
						return false;
					} else {
						$('#username').parent().addClass('has-success').removeClass('has-error');
						$('#username').next().html('');
						return true;
					}
				},
				timeout: 6000,
			});
		} else {
			$('#username').parent().addClass('has-error');
			$('#username').next().html('Name must be at least 6 characters long');
			return false;
		}
	}
	
	function checkIfCell(phone) {
		phone = phone.replace(/\D/g,'');
		if(phone.length===10) {
			$.ajax({
				url: '//network4rentals.com/network/ajax/check_if_phone_is_cell/',
				cache: false,
				type: "POST",
				data: {phone:phone},
				dataType: 'json',
				success: function(data) {
					for (var key in data) {
						var obj = data[key];
						for (var prop in obj) {
							if(prop == 'carrier_type') {
								if(obj[prop] != 'mobile') {
									$('#landlord_cell').parent().addClass('has-error').removeClass('has-success');
									$('#landlord_cell').next().html('This number is not a cell phone number');
									return false;
								} else {
									$('#landlord_cell').parent().addClass('has-success').removeClass('has-error');
									$('#landlord_cell').next().html('');
									return true;
								}
							}
						}
					}
				},
				timeout: 6000,
			});
		} else {
			$('#landlord_cell').parent().removeClass('has-error');
			$('#landlord_cell').next().html('');
			return false;
		}
	}	
	
	function check_email(email) {
		if(IsEmail(email)) {
			$('.error-email').html('');
			$.ajax({
				url: '//network4rentals.com/network/ajax/check_if_email_exists/',
				cache: false,
				type: "POST",
				data: {email:email},
				success: function(response) {	
					if(response == 1) {
						$('#email').parent().addClass('has-error').removeClass('has-success');
						$('#email').next().html('You already have an account, try signing in or use the forgot password link');
						return false;
					} else if(response == 3) {
						$('#email').parent().addClass('has-success').removeClass('has-error');
						$('#email').next().html('Please use a valid email');
						return false;
					} else {
						$('#email').parent().addClass('has-success').removeClass('has-error');
						$('#email').next().html('');
						return true;
					}
				},
				timeout: 6000,
				error: function(a,b,c) {
					alert(b+' ||| '+c);
				}
			});
		} else {
			$('#email').parent().addClass('has-error').removeClass('has-success');
			$('#email').next().html('Please use a valid email');
			return false;
		}
		
	}
	
	//Checks to see if a registered landlord is active in the system and alerts the user of the duplicate
	function check_for_landlord_email(email) {
		if($('#landlord-id').val().length>0){
			
		} else {
			if(IsEmail(email)) {
				$('.error-email').html('');
				$.ajax({ 
					url: '//network4rentals.com/network/ajax/search_landlandlord_email/',
					cache: false,
					type: "POST",
					data: {email:email},
					
					success: function(response) {
						if(response.length>0) {
							var response = JSON.parse(response);
							var list = '';
							for (var key in response) {
								if(key == 'bName') {
									list += '<li><b>Business Name: </b> '+response[key]+'</li>'; 
								} else if(key != 'id') {
									list += '<li><b>'+key.charAt(0).toUpperCase() + key.slice(1)+': </b> '+response[key]+'</li>'; 
								}
							}
							list += '<li><button class="addThisLandlord btn btn-warning" data-landlordid="'+response['id']+'">Add Landlord</button></li>';
							$('#email-suggestions').html(list); 
							$('#suggestion-window').modal('show');
							$('#landlord_email').parent().addClass('has-error');
							$('#landlord_email').next().html('Landlord already registered, please select you landlord from the list');
						} else {
							$('#landlord_email').parent().removeClass('has-error');
							$('#landlord_email').next().html('');
						}
					},
					timeout: 6000,
				});
			} else {
				
			}
		}
	}
	
	function checkPhoneNumber(phone) {
		$('.error-phone').html('');
		phone = phone.replace(/\D/g,'');
		$.ajax({ 
			url: '//network4rentals.com/network/ajax/search_landlandlord_phone/',
			cache: false,
			type: "POST",
			data: {phone:phone},
			success: function(response) {
				if(response.length>0) {
					var response = JSON.parse(response);
					var list = '';
					for (var key in response) {
						if(key == 'bName') {
							list += '<li><b>Business Name: </b> '+response[key]+'</li>'; 
						} else if(key != 'id') {
							list += '<li><b>'+key.charAt(0).toUpperCase() + key.slice(1)+': </b> '+response[key]+'</li>'; 
						}
					}
					list += '<li><button class="addThisLandlord btn btn-warning" data-landlordid="'+response['id']+'">Add Landlord</button></li>';
					$('#email-suggestions').html(list); 
					$('#suggestion-window').modal('show');
					return 1;
				}
			},
			timeout: 6000,
		});
	}
	
	function IsEmail(email) {
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		return regex.test(email);
	}
