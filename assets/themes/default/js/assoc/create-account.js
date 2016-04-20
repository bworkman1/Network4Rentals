$(function(){

	$('#service_zips').tagsInput({
		'defaultText':'Add zips   ',
		'onAddTag':check_if_zip,
		'onRemoveTag':check_if_zip
	});
	
	$('.tagsinput').addClass('form-control');
	
	$(".phone").mask("(999) 999-9999");
	$(".c_card").mask("9999-9999-9999-9999");
	
	$(".numbersOnly, .tagsinput").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });	
	
	//check username for valid username
	$('#username').focusout(function() {
		var username = $(this).val();
		var error = false;
		if(username.length<6) {
			error = true;
			add_error_feedback('username', 'Invalid username, must be at least 6 characters long');
		} else {
			var pattern = new RegExp(/^[a-z][\w.-]{4,40}$/i);
			if(!pattern.test(username)) {
				error = true;
				add_error_feedback('username', 'Invalid username, can only be letters and numbers and no longer then 20 characters');
			} else {
				// ajax call to check if user name is already in use
				$.ajax({
					url: 'https://network4rentals.com/network/ajax/check_unique_landlord_association/',
					cache: false,
					type: "POST",
					data: {username:username},
					success: function(response) {
						if(response>0) {
							error = true;
							add_error_feedback('username', 'This username is already being used, try adding a number to the end to make it unique');
						}
					},
					timeout: 6000,
				});
				
			}
		}
		
		if(!error) {
			add_success_feedback('username');
		}
	});

	
	$('#coupon').keyup(function() {
		var coupon = $(this).val();
		checkCouponCode(coupon);
	});
	

	if($('#coupon').val()) {
		checkCouponCode($('#coupon').val());
	}
	
	// check if valid email
	$('#email').focusout(function() {
		var email = $(this).val();
		var error = false;
		if( !isValidEmailAddress( email ) ) {
			error = true;
			add_error_feedback('email', 'Invalid email, you must use a valid email');
		} else {
			// ajax call to check if email is already in use
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/check_unique_email_landlord_association/',
				cache: false,
				type: "POST",
				data: {email:email},
				success: function(response) {
					if(response>0) {
						error = true;
						add_error_feedback('email', 'This email is already registered, if you forgot your password try using the forgot password tool by clicking login and selecting forgot password.');
					}
				},
				timeout: 6000,
			});
		}
		if(!error) {
			add_success_feedback('email');
		}
	});
	
	//check if password is good
	$('#password1').focusout(function() {
		var password = $(this).val();
		var error = false;
		if(password.length<6) {
			error = true;
			add_error_feedback('password1', 'Password must be at least 6 characters long');
		}
		if(!error) {
			add_success_feedback('password1');
		}
	});
	$('#password2').focusout(function() {
		var password = $(this).val();
		var password2 = $('#password1').val();
		var error = false;
		if(password.length<6) {
			error = true;
			add_error_feedback('password2', 'Password must be at least 6 characters long');
		} else {
			if(password !== password2) {
				error = true;
				add_error_feedback('password1', 'Passwords do not match');
				add_error_feedback('password2', 'Passwords do not match');
			} else {
				if(password.length>5) {
					add_success_feedback('password1');
				}
			}
		}
		if(!error) {
			add_success_feedback('password2');
		}
	});
	
	//check if title is long enough
	$('#title').focusout(function() {
		var title = $(this).val();
		var error = false;
		if(title.length<6) {
			error = true;
			add_error_feedback('title', 'Title should be at least 6 characters long');
		}
		if(!error) {
			add_success_feedback('title');
		}
	});	
	
	//check if title is long enough
	$('#referrer').focusout(function() {
		var referrer = $(this).val();
		var error = false;
		if(referrer.length<6) {
			error = true;
			add_error_feedback('referrer', 'You must select how you hard about us');
		}
		if(!error) {
			add_success_feedback('referrer');
		}
	});
	
	//name if title is long enough
	$('.payment_name').focusout(function() {
		var name = $(this).val();
		var error = false;
		var names = name.split(' ');
		if(names.length<2) {
			error = true;
			add_error_feedback('name', 'First and last name is required');			
		} else {
			var long_enough = true;
			for(var i=0;i<names.length;i++) { //check to see if name array values are longer then 2
				if(names[i].length<2) {
					long_enough = false;
				}
			}
			if(long_enough === false) {
				error = true;
				add_error_feedback('name', 'First and last name must be 2 characters or more');
			}
		}
		if(!error) {
			add_success_feedback('name');
		}
	});
	
	$('#phone').focusout(function() {
		var phone = $(this).val();
		phone =  phone.replace(/[^0-9]/g, '');
		var error = false;
		
		if(phone.length!==10) {
			error = true;
			add_error_feedback('phone', 'Phone number is required');
		}
		if(!error) {
			add_success_feedback('phone');
		}
	});
	
	$('#address').focusout(function() {
		var address = $(this).val();
		var error = false;
		
		if(address.length<5) {
			error = true;
			add_error_feedback('address', 'Address is required');
		} else {
			var pattern = new RegExp(/^[a-zA-Z0-9-\/] ?([a-zA-Z0-9-\/]|[a-zA-Z0-9-\/] )*[a-zA-Z0-9-\/]$/);
			if(!pattern.test(address)) { 
				error = true;
				add_error_feedback('address', 'A valid address is required');
			}
		}
		if(!error) {
			add_success_feedback('address');
		}
	});
	
	$('#city').focusout(function() {
		var city = $(this).val();
		var error = false;
		
		if(city.length<5) {
			error = true;
			add_error_feedback('city', 'City is required');
		} else {
			var pattern = new RegExp(/^[a-zA-z] ?([a-zA-z]|[a-zA-z] )*[a-zA-z]$/);
			if(!pattern.test(city)) { 
				error = true;
				add_error_feedback('city', 'A valid city is required');
			}
		}
		if(!error) {
			add_success_feedback('city');
		}
	});	
	
	$('#state').focusout(function() {
		var state = $(this).val();
		var error = false;
		
		if(state.length<2) {
			error = true;
			add_error_feedback('state', 'State is required');
		}
		if(!error) {
			add_success_feedback('state');
		}
	});		

	$('#zip').focusout(function() {
		var zip = $(this).val();
		var error = false;
		
		if(zip.length!==5) {
			error = true;
			add_error_feedback('zip', 'Zip is required');
		}
		if(!error) {
			add_success_feedback('zip');
		}
	});			
	
	$('#terms').focusout(function() {
		var terms = $(this).val();
		var error = false;
		
		if(terms.length!==1) {
			error = true;
			add_error_feedback('terms', 'Terms is required');
		}
		if(!error) {
			add_success_feedback('terms');
		}
	});			
	
	$('#submit-account').click(function(event) {
		event.preventDefault();
		var form_errors = false;
		$('#createAccount input, #createAccount select').each(function() {
			if($(this).val().length===0) {
				if($(this).is('[disabled=disabled]') || $(this).attr('id')=='coupon') {
					
				} else {
					form_errors = true;
					$(this).focus();
				}
			}
			if($(this).parent().parent().hasClass('has-error')) {
				form_errors = true;
				$(this).focus();
				var id = $(this).attr('id');
				add_error_feedback(id, '');
			}
		});
		if( $('#terms').val() =='') {
			form_errors = true;
			add_error_feedback('terms', 'You must agree to the terms of service');
		} else {
			add_error_feedback('terms', '');
		}
		if(form_errors === false) {
			$('#createAccount').submit();
		}
	});
	
});

function checkCouponCode(coupon) {
	if(coupon.length>3) {
		$.ajax({
			url: 'https://network4rentals.com/network/ajax_associations/check_coupon_code/',
			cache: false,
			type: "POST",
			data: {coupon:coupon},
			success: function(response) {
				console.log(response);
				if(response>0) {
					$('#coupon-error').html('<i class="fa fa-check-circle-o fa-fw fa-2x toolTip text-success" title="Invalid Coupon Code"></i>');
					$('.removeRequired').each(function() {
						$(this).removeAttr('required');
						$(this).attr('disabled', true);
					});
					$('#coupon').parent().addClass('has-successs').removeClass('has-error');
				} else {
					$('.removeRequired').each(function() {
						$(this).removeAttr('disabled', true);
						$(this).attr('required', true);
					});
					$('#coupon').parent().addClass('has-error');
					$('#coupon-error').html('<i class="fa fa-times-circle-o fa-fw fa-2x text-danger toolTip" data-toggle="tooltip" data-placement="top" title="Invalid Coupon Code"></i>');
				}
			},
			timeout: 6000,
			beforeSend: function() {
				$('#coupon-error').html('<i class="fa fa-circle-o-notch fa-spin fa-fw fa-2x text-info"></i>');
			},
			error: function() {
				$('#coupon-error').html('<i class="fa fa-exclamation-triangle fa-fw fa-2x text-danger"></i>');
			}
		});
	} else {
		if(coupon.length>0) {
			$('#coupon-error').html('<i class="fa fa-times-circle-o fa-fw fa-2x text-danger toolTip" data-toggle="tooltip" data-placement="top" title="Invalid Coupon Code"></i>');
		} else {
			$('#coupon-error').html('');
		}	
		$('#coupon').parent().removeClass('has-successs').removeClass('has-error');
	}
}

function check_if_zip() {
	var zips = $('#service_zips').val();
	var error = false;
	var bad_zips = '';
	zips = zips.split(',');
	for(var i=0;i<zips.length;i++) {
		if(zips[i].length!==5) {
			error = true;
			bad_zips += ' <u>'+zips[i]+'</u>';
		}
	}
	if(error) {
		$('#service_zips_error').parent().parent().addClass('has-error').removeClass('has-success');
		$('.service-zip-feedback').removeClass('fa-asterisk').addClass('fa-times');
		$('#service_zips_error').html('One or more of your zips is invalid, 5 diget zip codes only '+bad_zips).css({'position':'relative'});
	} else {
		$('#service_zips_error').parent().parent().addClass('has-success').removeClass('has-error');
		$('.service-zip-feedback').addClass('fa-check').removeClass('fa-times');
		$('#service_zips_error').html('').css({'position':'absolute'});
	}
	$('#service_zips_tag').focus();
}


function add_error_feedback(ids, msg) {
	$('#'+ids).parent().parent().addClass('has-error').removeClass('has-success');
	$('.'+ids).removeClass('fa-asterisk').addClass('fa-times');
	$('#'+ids+'-error').html(msg).css({'position':'relative'});
}
function add_success_feedback(ids) {
	$('#'+ids).parent().parent().addClass('has-success').removeClass('has-error');
	$('.'+ids).addClass('fa-check').removeClass('fa-times').removeClass('fa-asterisk');
	$('#'+ids+'-error').html('').css({'position':'absolute'});
}

function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    return pattern.test(emailAddress);
};
