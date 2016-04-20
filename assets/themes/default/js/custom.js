$(document).ready(function() {
	$('.notification-btn').click(function() {
		$('#notifications-box').delay(100).stop().slideToggle('fast');
	});
	
	$('.money').blur(function() {
		$('.money').formatCurrency({
			symbol: ''
		});
	});
	
	
    $('.slideout-menu-toggle').on('click', function(event){
    	event.preventDefault();
    	// create menu variables
    	var slideoutMenu = $('.slideout-menu');
    	var slideoutMenuWidth = $('.slideout-menu').width();
    	
    	// toggle open class
    	slideoutMenu.toggleClass("open");
    	
    	// slide menu
    	if (slideoutMenu.hasClass("open")) {
	    	slideoutMenu.animate({
		    	left: "0px"
	    	});	
    	} else {
	    	slideoutMenu.animate({
		    	left: -slideoutMenuWidth
	    	}, 250);	
    	}
    });
	
	$('.occuranceType').click(function() {
		var occurance = $(this).val();
		if(occurance=='y') {
			$('#reoccurringDate').html('<label><i class="fa fa-asterisk text-danger"></i> Start Date</label><div class="input-group"><input type="text" class="form-control datepicker" name="reoccurring_date" id="pm-date" placeholder="MM/DD/YYYY"><div class="input-group-addon"><i class="fa fa-calendar"></i></div></div>');
			
			var list = '<label><i class="fa fa-asterisk text-danger"></i> How Often</label><div class="form-group"><select name="interval" class="form-control" required">';
			list += '<option value="1">Monthly</option><option value="3">Quarterly</option><option value="6">Bi-Yearly</option><option value="12">Yearly</option>';
			list += '</select></div>';
			$('#interval').html(list);
		} else {
			$('#reoccurringDate').html('');
			$('#interval').html('');
		}
		$('#pm-date').datepicker();
	});
	
	 $('#newUserModal').modal('show');
	
	$('.listingImageUpload').bind('change', function() {
		var imageSize = 0;
		$('.listingImageUpload').each(function() {
			if($(this).val()!='') {
				var currentFile = this.files[0].size;
				imageSize = currentFile+imageSize;
			}
		});
		var sizeInMB = (imageSize / (1024*1024)).toFixed(2);
		if(sizeInMB>6) {
			$('#imageWarning').html('<div class="alert alert-danger">Your images are too large, please shrink them with one of the options under the help button above.</div>');
		} else {
			$('#imageWarning').html('');
		}
		
	});
	
	$('.panel-body').slideDown();
	
	$(".youtube_video").fitVids();
	var docHeight = $(window).height();
	var footerHeight = $('footer').height();
	var footerTop = $('footer').position().top + footerHeight;
   
	if (footerTop < docHeight) {
		$('footer').css('margin-top', 0 + (docHeight - footerTop) + 'px');
	} 
	$("#landlord-logo").mouseover(function() {
		$('#editAccountLink ').animate({ 
			backgroundColor: "black" }, 1000).css({'display':'block'}); 
		})
		.mouseout(function() { 
			$('#editAccountLink').animate({ backgroundColor: "#68BFEF" }, 'slow').css({'display':'none'});  
	});
	
	$('form').submit(function(){
		$(this).find('button[type=submit]').attr('disabled', 'disabled').html('<i class="fa fa-clock-o"></i> Please Wait');
	});
	
	$('.dropdown-menu li').each(function() {
		if($(this).hasClass('active')) {
			$(this).parent().css({'display':'block'});
		}
	});
	$('.menu-column .dropdown').on('show.bs.dropdown', function(e) {     $(this).find('.dropdown-menu').first().stop(true, true).slideDown(); }); 
	$('.menu-column .dropdown').on('hide.bs.dropdown', function(e) { $(this).find('.dropdown-menu').first().stop(true, true).slideUp(); });
	
	$('.finalizePayment').click(function(event ) {
		event.preventDefault();
		var form_error = false;
		var name = $('.checkName').val();
		var route = $('.routing-number').val();
		var account = $('.account-number').val();
		var check = $('.checkNum').val();
		var amount = $('.checkAmounts').val();
		var bank_name = $('.bankNames').val();
		var min_amount = $('.checkAmounts').data('min');
		
		if(name.length==0) {
			form_error = true;
			$('.check-name-error').addClass('text-danger').html('Name Of Account Holder Required');
			$('.checkNames').addClass('has-error');
		} else {
			$('.checkNames').removeClass('has-error');
			$('.check-name-error').html('');
		}
		if(route.length==0) {
			form_error = true;
			$('.routing-error').addClass('text-danger').html('Routing Number Is Required');
			$('.routing').addClass('has-error');
		} else {
			$('.routing').removeClass('has-error');
			$('.routing-error').html('');
		}
		if(account.length==0) {
			form_error = true;
			$('.account-error').addClass('text-danger').html('Account Number Is Required');
			$('.checkRoutingNum').addClass('has-error');
		} else {
			$('.account-error').html('');
			$('.checkRoutingNum').removeClass('has-error');
		}
		if(bank_name.length==0) {
			form_error = true;
			$('.bank-name-error').addClass('text-danger').html('The Name Of Your Bank Is Required');
			$('.bankName').addClass('has-error');
		} else {
			$('.bank-name-error').html('');
			$('.bankName').removeClass('has-error');
		}
	
		if(amount.length == 0) {
			form_error = true;
			$('.amount-error').addClass('text-danger').html('Amount Is Required');
			$('.checkAmount').addClass('has-error');
		} else {
			
			$('.amount-error').html('');
			$('.checkAmount').removeClass('has-error');
		}
		

		if(min_amount>0) {
		
			if(min_amount>amount) {
				form_error = true;
				$('.checkAmount').addClass('has-error');
				$('.amount-error').addClass('text-danger').html('Min Amount Has Not Been Met');
			}
		}
		
		if(form_error == false) {
			$('.finalizePayment').remove();
			$('.paymentInfoConfirm').css({'display':'block'});
			var total = amount*.01;
			$('#paymentInfoHide input').each(function() {
				$(this).attr('readonly', 'readonly');
			});
			$('.adminFee').html('');
			
			var fees = '<div class="row">';
			fees += '<div class="col-md-4"> <label><b>Sub-Total:</b></label> <input type="text" class="feeShown form-control" disabled value="'+amount+'" /> </div>';
			fees += '<div class="col-md-4"> <label><b>Fee:</b></label><input type="text" class="feeTotal form-control" disabled value="'+total+'" /> </div>';
			fees += '<div class="col-md-4"> <label><b>Total:</b></label><input type="text" class="feeRentTotal form-control" disabled value="'+(parseFloat(total)+parseFloat(amount))+'" /> </div>';
			fees += '</div><br>';
			
			$('.adminFeeBox').html(fees);
			$(this).removeClass('in');
			$('.submitCheckPayment').addClass('in');
			$('.alertHere').html('<div class="alert alert-warning">Please Look Over Your Payment Info To Make Sure Everything Is Correct. Once Satisfied Click Submit Payment Below.</div>');
		}
	});
	
	$('.sortActivity').change(function() {
		$('.sorting').submit();
	});
	
	$('.checkUsername').focusout(function() {
		var username = $(this).val();
		if(username.length > 5) {
			$('.error-username').html('');
			$.ajax('https://network4rentals.com/network/ajax/check_if_user_exists/'+username, {
				dataType: "json",
				success: function(response) {
					var response = $.parseJSON(response);
					if(response == 1) {
						$('.error-username').html('<i class="fa fa-exclamation-triangle"></i> Username Is Already Taken, Try A Different One');
						$('.checkUsername').addClass('input-error');
					} else {
						$('.error-username').html('');
						$('.checkUsername').removeClass('input-error');
					}
				},
				timeout: 6000,
			});
		} else {
			$('.checkUsername').addClass('input-error');
			$('.error-username').html('<i class="fa fa-exclamation-triangle"></i> Username Must Be 6 Characters Or More').fadeIn();
		}
	});
	
	$('.checkLandlordEmail').focusout(function() {
		var email = $(this).val();
		email = email.replace(' ', '');
		$(this).val(email);
		if(email.length > 5) {
			$('.error-email').html('');
			$.ajax({
				url: 'https://network4rentals.com/network/ajax_landlords/check_if_email_exists/',
				cache: false,
				type: "POST",
				data: {email:email},
				success: function(response) {
					if(response == 1) {
						$('.error-email').html('<i class="fa fa-exclamation-triangle"></i> Email Is Already Taken, Try A Different One Or Use The Forgot Password Feature');
						$('.checkLandlordEmail').addClass('input-error');
					} else if(response == 3) {
						$('.error-email').html('<i class="fa fa-exclamation-triangle"></i> Invalid Email, Please Use A Real Email Address');
						$('.checkLandlordEmail').addClass('input-error');
					} else {
						$('.error-email').html('');
						$('.checkLandlordEmail').removeClass('input-error');
					}
				},
				timeout: 6000,
			});
		} else {
			$('.error-email').html('<i class="fa fa-exclamation-triangle"></i> Invalid Email').fadeIn();
		}
	});
	
	$('.toolTips').tooltip({
		html: 'TRUE',
   });
   
	$('.mail li').click(function(e) {
		e.preventDefault();
		$(this).css({'background':'#F3F3F3'});
	});
   
	$('.public-background').change(function() {
		var background = $(this).val();
		if(background==1) {
			$('.default1').css({'display':'block'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==2) {
			$('.default2').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==3) {
			$('.default3').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else {
			$('.default4').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
		}
	});
   
	$('.attachment-img').bind('change', function() {
		var error = false;
		var size = this.files[0].size / 1048576;
		var ftype = this.files[0].type;
		var fName = this.files[0].name;
		
		
		if(size === 'undefined') {
			var error = false;
		}
		
		if(size > 5) {
			alert('The file you are trying to upload is too large, try shrinking the files size and try again.');
			$('.sendMsg').css({'display':'none'});	
			error = true;
		}
		switch(ftype) {
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
			case 'image/pjpeg':
				break;
			default:
				alert('Invalid File Type, Please Upload A Valid File. ');
				error = true;
		}
		
		if(error == false) {
			
		} else {
			$(this).val('');	
		}

	});
	
	$('.viewTheseRequest').click(function() {
		var property_id = $(this).data('id');
		$('body').append($('<form/>', {
			id: 'form',
			method: 'POST',
			action: 'https://network4rentals.com/network/landlords/search-requests'
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'address',
			value: property_id
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'start_date',
			value: ''
	   }));
	   	$('#form').append($('<input/>', {
			type: 'hidden',
			name: 'end_date',
			value: ''
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'serviceType',
			value: ''
	   }));

	   $('#form').submit();

	   return false;
	});
	
	$('.unique-page-name-check').focusout(function() {
		var unique_name = $(this).val();
		unique_name = convertToSlug(unique_name);
		$(this).val(unique_name);
		if(unique_name.length > 3) {
			$('.error-helper').html('');
			$.ajax('https://network4rentals.com/network/ajax/check_unique_url/'+unique_name, {
				dataType: "json",
				success: function(response) {
					var response = $.parseJSON(response);
					if(response == 1) {
						$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Is Already Taken, Try A Different One').fadeIn();
					}
				},
				timeout: 6000,
			});
		} else {
			$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn();
		}
	});
	
	$('#landlord-search').keyup(function() {
		var search = $('#landlord-search').val();
		if(search.length > 2) {
			$.ajax('https://network4rentals.com/network/ajax/get_landlords_search/'+search, {
				dataType: "json",
				success: function(response) {					
					var show_renters = '<ul class="s-results">';
					if(response != 'undefined') {
						for(var i=0;i<response.length;i++) {
							if(response[i]['main_admin_id'] > 0) {
								var subId = response[i]['id'];
								show_renters += '<li id="'+response[i]['id']+'" onclick="loadLandlordInfo('+response[i]['sub_admins']+', '+subId+', \''+response[i]['sub_b_name']+'\')">'+response[i]['sub_b_name']+' - '+response[i]['city']+' '+response[i]['zip']+' | '+response[i]['name']+'</li>';								
							} else {
								if(response[i]['bName'] != '') {
									show_renters += '<li id="'+response[i]['id']+'" data-info="'+response[i]['id']+'" onclick="loadLandlordInfo(this.id)">'+response[i]['bName']+' - '+response[i]['city']+' '+response[i]['zip']+' | '+response[i]['name']+'</li>';
								} else {
									show_renters += '<li id="'+response[i]['id']+'" data-info="'+response[i]['id']+'" onclick="loadLandlordInfo(this.id)">'+response[i]['name']+' - '+response[i]['city']+' '+response[i]['zip']+' | '+response[i]['name']+'</li>';
								}				
							}
						}
					} else {
						show_renters += '<li>Sorry No Results Found</li>';
					}
					show_renters += '</ul>';
					$('#results').html(show_renters);
				},
				error: function(request, errorType, errorMessage) {
					$('.thinking').html('No Landlord Found').fadeIn();
				},
				timeout: 6000,
				beforeSend: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
				},
				complete: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
				}
			});
		}
		var show_renters = '<ul>';
		show_renters += '<li>3 Characters Min</li>';
		show_renters += '</ul>';
		$('#results').html(show_renters);
	});
		
	$('.row').click(function() {
		$('#results').html('');
	});
  
	$('.show-message').click(function(e) { 
		var row_id = $(this).data('id');
		$('.collapse-message-'+row_id).slideToggle();
		$.ajax('https://network4rentals.com/network/ajax/update_time/'+row_id, {
			timeout: 6000,
			success: function(response) {
				$('#label-'+row_id).fadeOut(); 
			}
		});
	});
	
	$('.reply-message-btn').click(function(){
		var id = $(this).data('reply-id');
		$('.parent-id').val(id);
	});
	
	$('.message').keyup(function() { //counts the textarea input in models
		var strings = $(this).val().length;
		if(strings > 1250) {
			$(this).next().html('<div class="text-danger">The Message Cannot Exceed 1250 Characters ('+strings+')</div>');
		} else {
			$(this).next().html('<p><em>Grab This And Pull Down To Resize Box <i class="fa fa-level-up"></i></em></p>');
		}
	});
	
	$('.attachment').bind('change', function() {
		var error = false;
		var size = this.files[0].size / 1048576;
		var ftype = this.files[0].type;
		var fName = this.files[0].name;
		
		if(size === 'undefined') {
			var error = false;
		}

		if(size > 5) {
			alert('The file you are trying to upload is too large, try shrinking the files size and try again.');
			$('.sendMsg').css({'display':'none'});	
			error = true;
		}
		switch(ftype) {
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
			case 'image/pjpeg':
			case 'application/pdf':
			case 'application/octet-stream':
			case 'application/vnd.oasis.opendocument.text':
			case 'application/msword':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			case 'application/vnd.openxmlformats-officedocument.wordprocessingml.template':
			case 'application/vnd.oasis.opendocument.text':
				break;
			default:
				alert('Invalid File Type, Please Upload A Valid Files. '+ftype);
				error = true;
		}
		
		if(error == false) {
			
		} else {
			$(this).val('');	
		}

	});
	
	$('.datepicker').datepicker();
	if($(window).width()>767) {
		$(".phone").mask("(999) 999-9999");
	}
	
	$('.resize_help ').click(function(e) {
		e.preventDefault();
		if($('.help').hasClass('showing-help')) {
			$('.help').removeClass('showing-help').slideToggle();
		} else {
			$('.help').addClass('showing-help').slideToggle();
		}
	});
	

	
	$('.deleteListing').click(function() {
		var id = $(this).data('listingid');
		$('.hiddenId').val(id);
	});
	
	$('.listing-state').focusout(function() {
		var address = $('.listing-address').val();
		var city = $('.listing-city').val();
		var state = $('.listing-state').val();
		if(address != '' && city != '' && state != '') {
			var view = load_map_and_street_view_from_address(address+' '+city+' '+state);
			if(!view) {
				$('.show-map').fadeIn();
			} else {
				$('.show-map').fadeOut();
			}
		}
	});
	
	if($('.tenant-address-service-requests').length>0) {
		var address = $('.tenant-address-service-requests').text();
		load_map_and_street_view_from_address(address);
	}
	
	if ($('#showing-maps').length){
		var address = $('.listing-address').val();
		var city = $('.listing-city').val();
		var state = $('.listing-state').val();
		if(address != '' && city != '' && state != '') {
			load_map_and_street_view_from_address(address+' '+city+' '+state);
		}
	}
	
	$('.pwd').focusout(function() {
		var inputs = $(this).val();
		if(inputs.length < 6 && inputs != '') {
			$(this).css({'background':'rgba(250, 0, 0, .4)'});
			$(this).next().html('Password Must Be 6 Characters Long');
		} else {
			$(this).next().html('');
		}
	});
	
	$('.pwd-check').keyup(function() {
		var checkpwd = $(this).val().length;
		var pwd1 = $('.pwd1').val();
		var pwd2 = $(this).val();
		if(checkpwd > 3) {
			if(pwd1 == pwd2) {
				$('.error-info').html('');
				$('.pwd').css({'background':'rgba(250, 250, 250, 1)'});
				$(this).css({'background':'rgba(250, 250, 250, 1)'});
			} else {
				$(this).next().html('Passwords Do Not Match');
				$('.pwd').css({'background':'rgba(250, 0, 0, .4)'});
				$(this).css({'background':'rgba(250, 0, 0, .4)'});
			}
		}
	});
	
	$('.checkPass2').focusout(function() {
		var pwd1 = $('.checkPass').val();
		var pwd2 = $('.checkPass2').val();
		if(pwd1.length>6 || pwd2.length>6) {
			if(pwd1 != pwd2) {
				$('.checkPass').addClass('input-error');
				$('.checkPass2').addClass('input-error');
				$('.pwd-error').html('<i class="fa fa-exclamation-triangle">Passwords Do Not Match Try Again');
			} else {
				$('.checkPass').removeClass('input-error');
				$('.checkPass2').removeClass('input-error');
				$('.pwd-error').html('');
			}
		} else {
			$('.checkPass').addClass('input-error');
			$('.checkPass2').addClass('input-error');
			$('.pwd-error').html('<i class="fa fa-exclamation-triangle">Password Must Be At Least 6 Charaters Long');
		}
		
	});
	
	$(".img-preview").change(function(){
		readURL(this);
	});
	
	$('.forward-sponsorship').click(function(e) {
		e.preventDefault();
		var email = $(this).data('con-email');
		$('.forward-email-input').val(email);
	});
	
	$('.existing_properties').change(function() {
		var existing = $(this).val();
		if(existing.length>0) {
			$('.add-to-properties').css({'display':'none'});
			$('.listing-baths').removeAttr('required');
			$('.listing-beds').removeAttr('required');
			$('.listing-title').removeAttr('required');
			$('.listing-sqfeet').removeAttr('required');
			$('.listing-deposit').removeAttr('required');
			$('.listing-desc').removeAttr('required');
		} else {
			$('.add-to-properties').css({'display':'block'});
			$('.listing-baths').prop('required',true);
			$('.listing-desc').prop('required',true);
			$('.listing-beds').prop('required',true);
			$('.listing-title').prop('required',true);
			$('.listing-sqfeet').prop('required',true);
			$('.listing-deposit').prop('required',true);
		}
	});
	
	$('.propertySelect').change(function() {
		var check = $(this).val();
		get_property_details(check);
		
		if(check.length>0) {
			$.ajax('https://network4rentals.com/network/ajax/get_property_details/'+check, {
				dataType: "json",
				success: function(response) {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Retrieving Details... ').fadeOut();
					var response = $.parseJSON(response);
					$('.hide-form').addClass('in');
					if(typeof response != 'undefined') {
						$('.address').val(response['address']);
						$('.city').val(response['city']);
						$('.state').val(response['stateAbv']);
						$('.zip').val(response['zipCode']);
						$('.rental_id').val(check);
						var group = $('.groupPicker').val();
						if(group != '') {
							$('.group_id').val(group);
						}
					}
				},
				error: function(request, errorType, errorMessage) {
					$('.thinking').html('<div class="text-danger">Error Retrieving Info, try again</div>').fadeIn();
				},
				timeout: 15000,
				beforeSend: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Retrieving Details... ').fadeIn();
				},
				complete: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Retrieving Details... ').fadeOut();
				}
			});
		} else {
			$('.hide-form').removeClass('in');
		}
		
	});

	$('.reply-message').click(function() {
		var id = $(this).data('id');
		$('#hidden-id').val(id);
	});

	$("#selectTenants").select2({
		allowClear: true
	});
		
	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^-\d]/g, "");
	});
	
	$('.removeAdmin').click(function() {
		var id = $(this).data('adminid');
		$('.adminremovalselection').find('[value="'+id+'"]').hide();
		$('.hidden-admin-id').val(id);
	});
	
	$('.addItemProperty').click(function() {
		var id = $(this).data('id');
		$('#addItems form').get(0).setAttribute('action', 'https://network4rentals.com/network/landlords/add-rental-item/'+id+'/9'); 
	});


		
	
	$('.isoSelections input[type="checkbox"]').click(function() {
		var check = $(this).attr('class');
		console.log(check);
		$('.'+check).not(this).attr('checked', false);  
		
	});
	
	
	$('.editAdmin').click(function() {
		var id = $(this).data('changeid');
		var bname = $(this).data('subbname');
		$('.subGroupId').val(id);
		$('.subBName').val(bname);
		
	});
	
	$('.textMessages').change(function() {
		if($(this).val() == 'y') {
			$('.textMessagePhoneNumber').addClass('fade in');
		} else {
			$('.textMessagePhoneNumber').removeClass('in');
		}
	});
	
	$('.addTextMessages').click(function(event) {
		event.preventDefault();
		var text_messages = $('.textMessages').val();
		var elem = $(this);
		if(text_messages=='y') {
			$('.textMessages').removeClass('input-error');
			var phone = $('.cellPhone').val();
			phone = phone.replace(/\D/g,'');
			if(phone.length==10) {
				$.ajax({
					url: 'https://network4rentals.com/network/ajax/check_if_number_can_accept_text/',
					cache: false,
					type: "POST",
					dataType: "json",
					data: {cell:phone},
					success: function(response) {
						if(response.Response.carrier_type != 'mobile') {
							showErrorAccountCreation();
						} else {
							submitCreateAccountForm();
						}
					},
					beforeSend: function() {
						elem.html('<i class="fa fa-clock-o"></i> Please Wait').attr('disabled', true);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						$.notify('Error looking up your cell phone, try again', 'error');
					},
					timeout: 6000,
					complete: function() {
						elem.html('Send Text').attr('disabled', false);
					}
				});
			} else {
				showErrorAccountCreation()
			}
		} else {
			$('.textMessages').addClass('input-error');
			
		}
	});
	
	$('.createLandlordAccount').click(function(event) {
		event.preventDefault();
		var text_messages = $('.textMessages').val();
		if(text_messages=='y') {
			var phone = $('.cellPhone').val();
			phone = phone.replace(/\D/g,'');
			if(phone.length==10) {
				$.ajax({
					url: 'https://network4rentals.com/network/ajax/check_if_number_can_accept_text/',
					cache: false,
					type: "POST",
					dataType: "json",
					data: {cell:phone},
					success: function(response) {
						if(response.Response.carrier_type != 'mobile') {
							showErrorAccountCreation();
						} else {
							submitCreateAccountForm();
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
				showErrorAccountCreation()
			}
		} else {
			submitCreateAccountForm();
		}
	});
	
	
	/* Renter Create Account */
	$('.landlordSelect').change(function () {
		if($(this).val() == 'y') {
			$('.top-steps').css({'display':'block'});
			$('#progress1').css({'display':'block'});
			$('.stepProceed2').html('Next Step').removeClass('noLandlordSubmit');
			$('#createAccount').attr('action', 'https://network4rentals.com/network/renters/create-account');
		} else {
			//$('#createAccount').attr('action', 'https://network4rentals.com/network/renters/create-account-no-landlord');
			$('.top-steps').css({'display':'none'});
			$('#progress1').css({'display':'none'});
			$('.stepProceed2').html('Submit').addClass('noLandlordSubmit');
		}
	});
	
	$('.stepProceed2').click(function(e) {
		
		if($('.landlordSelect').val() == 'n') {
			$("#ajaxError").html('');
			var elem = $(this);
			var fullname = $("input[name=fullname]").val();
			var email = $("input[name=email]").val();
			var phone = $("input[name=phone]").val();
			var hear = $("select[name=hear]").val();
			var username = $("input[name=username]").val();
			var password = $("input[name=password]").val();
			var password1 = $("input[name=password1]").val();
			var terms = $("input[name=terms]").val();
			var sms_msgs = $("select[name=sms_msgs]").val();
			var cell_phone = $("input[name=cell_phone]").val();
			$(this).html('<i class="fa fa-spinner fa-pulse"></i> Creating Account').attr('disabled', true);
			
			var data = {
					'fullname':fullname,
					'email':email,
					'phone':phone,
					'hear':hear,
					'username':username,
					'password':password,
					'password1':password1,
					'terms':terms,
					'sms_msgs':sms_msgs,
					'cell_phone':cell_phone
				};
			
	
			
			$.ajax({
				type:     "POST",
				url: '//network4rentals.com/network/ajax_renters/create-account-no-landlords',
				dataType: 'json',
				data: data,
				success: function(results){  
					console.log(results);
					if(typeof results.success != 'undefined') {
						window.location.replace('https://network4rentals.com/network/renters/account-created');
					} else {
						$('#ajaxError').html('<div class="alert alert-danger">'+results.error+'</div>');
					}
				},
				error: function(a,b,c){
						// What to do when the ajax fails. 
						console.log(b+c);
						$("#ajaxError").html('<div class="alert alert-danger">Something Went Wrong, Try Submitting Again</div>');
						$('.stepProceed2').html('Submit').attr('disabled', false);
				}, 
				complete: function() {
					$('.stepProceed2').html('Submit').attr('disabled', false);
				}
			});
		
			
		}
		
	});
	
	
	$('.editRentalItem').click(function() {
		var id = $(this).data('id');
		var elem = $(this);
		$.ajax({
			type:     "POST",
			url: '//network4rentals.com/network/ajax_landlords/get_single_property_item',
			dataType: 'json',
			data: {'id':id},
			success: function(results){  
				console.log(results);
				$('#editItem').modal('show');
				$('[name="desc"]').val(results.desc);
				$('[name="brand"]').val(results.brand);
				$('[name="modal_num"]').val(results.modal_num);
				$('[name="serial"]').val(results.serial);
				$('[name="service_type"]').val(results.service_type);
				$('[name="id"]').val(results.id);
			},
			error: function(a,b,c){
				alertify.error("Something went wrong pulling the item details, try again");
			}, 
			complete: function() {
				elem.html('<i class="fa fa-pencil"></i>');
			},
			beforeSend: function() {
				elem.html('<i class="fa fa-spin fa-cog"></i>');
			}
		});
	});
	
	
	
});
	function showErrorAccountCreation() {
		$.notify('The number you entered doesn\'t appear to be a cell phone number', 'error');
		$('.cellPhone').addClass('input-error');
	}
	
	function submitCreateAccountForm() {	
		$('#createNewAccount').submit();
	}
	
	function addOverlay() {

		var docHeight = $(document).height();
		$("body").append('<i class="fa-li fa fa-spinner fa-spin fa-3x"></i>');
		$("#overlay")
		  .height(docHeight)
		  .css({
			 'opacity' : 0.4,
			 'position': 'absolute',
			 'top': 0,
			 'left': 0,
			 'background-color': 'black',
			 'width': '100%',
			 'z-index': 5000
		  });

	};
	
	function getPropertyItems(id) {
		$('#listing_items').html('');
		var property_id = id;
		$.ajax('https://network4rentals.com/network/ajax/show_listing_items/'+property_id, {
			dataType: "json",
			success: function(response) {
				var services_array = ['', 'Appliance Repair', 'Carpentry', 'Concrete', 'Drain Cleaning (Clogged Drain)', 'Doors And Windows', 'Electrical', 'Heating And Cooling', 'Lawn And Landscape', 'Mold Removal', 'Plumbing', 'Painting', 'Roofing', 'Siding'];
				var response = $.parseJSON(response);
				if(response != false) {
					var list = '<li><div class="row"><div class="col-sm-2">Image</div><div class="col-sm-2"><b>Item</b></div><div class="col-sm-2"><b>Model#:</b></div><div class="col-sm-2"><b>Brand:</b></div><div class="col-sm-2"><b>Serial#:</b></div><div class="col-sm-2"><b>Service Type:</b></b></div></div></li>';
					for(i=0;i<response.length;i++) {
						if(response[i]['image'] !== null) {
							var image = '<img src="https://network4rentals.com/network/'+response[i]['image']+'" width="40" height="40" class="img-responsive">';
						} else {
							var image = '';
						}
						list += '<li><div class="row"><div class="col-sm-2">'+image+'</div><div class="col-sm-2">'+response[i]['desc']+'</div><div class="col-sm-2">'+response[i]['modal_num']+'</div><div class="col-sm-2">'+response[i]['brand']+'</div><div class="col-sm-2">'+response[i]['serial']+'</div><div class="col-sm-2">'+services_array[response[i]['service_type']]+'</div></div></li>';
					}
				} else {
					$('#listing_items').html('<li>No Items Have Been Added</li>');
				}
				$('#listing_items').html(list);
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
	
	function removeOverlay() {
		$('#overlay').remove();
	}

	function loadLandlordInfo(id, main_id, bName) {
		$('#results').html('');
		if(id != '') {
			$.ajax('https://network4rentals.com/network/ajax/get_landlords_info/'+id, {
				dataType: "json",
				success: function(response) {
					if(main_id>0) {
						$('#bName').val(bName).attr('readonly', true);
					} else {
						$('#bName').val(response['bName']).attr('readonly', true);
					}
					$('#lName').val(response['name']).attr('readonly', true);
					$('#email').val(response['email']).attr('readonly', true);
					$('#phone').val(response['phone']).attr('readonly', true);
					$('#address').val(response['address']).attr('readonly', true);
					$('#city').val(response['city']).attr('readonly', true);
					$('#state option[value='+response['state']+']').attr('selected','selected');
					$('#state').attr("disabled", true); 
					$('#zip').val(response['zip']).attr('readonly', true);
					$('#landlord-id').val(response['id']);
					$('#cell-phone').val(response['cell']).attr('readonly', true);;
					$('.thinking').html('<i class="fa fa-check-circle text-success"></i>');
					$('#group_id').val(main_id);
				},
				error: function(request, errorType, errorMessage) {
					$('.thinking').html('No Landlord Found').fadeIn();
				},
				timeout: 6000,
				beforeSend: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
				},
				complete: function() {
					$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
				}
			});
			$('#landlord-search').val('');
		}	
	}

	function load_map_and_street_view_from_address(address) {
		var geocoder = new google.maps.Geocoder();
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var gps = results[0].geometry.location;
			create_map_and_streetview(gps.lat(), gps.lng(), 'map_canvas', 'pano');
			}
		});
	}
 
    var map;
    var myPano;
    var panorama;
    var houseMarker;
    var addLatLng;
    var panoOptions;
    function create_map_and_streetview(lat, lng, map_id, street_view_id) {
    var googlePos = new google.maps.LatLng(lat,lng);
 
    panorama = new google.maps.StreetViewPanorama(document.getElementById("pano"));
    addLatLng = new google.maps.LatLng(lat,lng);
    var service = new google.maps.StreetViewService();
    service.getPanoramaByLocation(addLatLng, 50, showPanoData);
 
    var myOptions = {
    zoom: 14,
    center: addLatLng,
    mapTypeId: google.maps.MapTypeId.ROADMAP,
    backgroundColor: 'transparent',
    streetViewControl: false,
    keyboardShortcuts: false,
 
    }
		var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		var marker = new google.maps.Marker({
            map: map,
            position: addLatLng
        });
    }
 
    function showPanoData(panoData, status) {
		if (status != google.maps.StreetViewStatus.OK) {
			$('#pano').html('No StreetView Picture Available').attr('style', 'text-align:center;font-weight:bold').show();
			return;
		}
		$('#pano').show();
		  var angle = computeAngle(addLatLng, panoData.location.latLng);
	 
		  var panoOptions = {
			position: addLatLng,
			addressControl: false,
			linksControl: false,
			panControl: false,
			zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL
			},
			pov: {
			heading: angle,
			pitch: 10,
			zoom: 1
			},
			enableCloseButton: false,
			visible:true
		};
		panorama.setOptions(panoOptions);
    }
 
	function computeAngle(endLatLng, startLatLng) {
		var DEGREE_PER_RADIAN = 57.2957795;
		var RADIAN_PER_DEGREE = 0.017453;

		var dlat = endLatLng.lat() - startLatLng.lat();
		var dlng = endLatLng.lng() - startLatLng.lng();
		// We multiply dlng with cos(endLat), since the two points are very closeby,
		// so we assume their cos values are approximately equal.
		var yaw = Math.atan2(dlng * Math.cos(endLatLng.lat() * RADIAN_PER_DEGREE), dlat)
		 * DEGREE_PER_RADIAN;
		return wrapAngle(yaw);
   }
 
	function wrapAngle(angle) {
		if (angle >= 360) {
			angle -= 360;
		} else if (angle < 0) {
			angle += 360;
		}
		return angle;
    };

	function readURL(input) {

		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('.thumbPreview').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	
	function convertToSlug(Text)
	{
		return Text
			.toLowerCase()
			.replace(/ /g,'-')
			.replace(/[^\w-]+/g,'')
			;
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