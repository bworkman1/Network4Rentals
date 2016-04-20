$(function(){
	$('#createAccount').submit(function(event) {
		event.preventDefault();
	});
	
	$('#coupon').keyup(function() {
		var coupon = $(this).val();
		checkCouponCode(coupon);
	});
	$('#coupon').focusout(function() {
		var coupon = $(this).val();
		checkCouponCode(coupon);
	});	
	
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

	$('#emails').focusout(function() {
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
	
});

function checkCouponCode(coupon) {
	if($('#couponFeedback').html() =='') { 
		if(coupon.length>3) {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax_associations/check_coupon_code/',
				cache: false,
				type: "POST",
				dataType: 'json',
				data: {coupon:coupon},
				success: function(response) {
					console.log('Response: '+response);
					if(typeof response.success !== 'undefined') {
						$('#coupon-error').html('<i class="fa fa-check-circle-o fa-fw fa-2x toolTip text-success" title="Invalid Coupon Code"></i>');
						$('#coupon').parent().addClass('has-successs').removeClass('has-error');
						$('#couponFeedback').html('<div class="text-center alert alert-success">'+response.success+'</div>');
						$('#coupon').attr('readonly', true);
					} else {
						$('.removeRequired').each(function() {
							$(this).removeAttr('disabled', true);
							$(this).attr('required', true);
						});
						$('#coupon').parent().addClass('has-error');
						$('#coupon-error').html('<i class="fa fa-times-circle-o fa-fw fa-2x text-danger toolTip" data-toggle="tooltip" data-placement="top" title="Invalid Coupon Code"></i>');
						$('#couponFeedback').html('');
					}
				},
				timeout: 6000,
				beforeSend: function() {
					$('#coupon-error').html('<i class="fa fa-circle-o-notch fa-spin fa-fw fa-2x text-info"></i>');
				},
				error: function(a, b, c) {
					console.log(b);
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
