$(document).ready(function() {
	$('.stepProceed2').click(function() {		
		var counter = 0;		$(".selectedZips").each(function(index) {			counter++;		});		if(counter > 0) {			$('#div1').removeClass('activestep');			$('#div2').addClass('activestep');		} else {			bootbox.dialog({				message: 'In order to go to the next step you will need to add some zip codes to your shopping cart by using the search zip code feature.',				title: "First Add Some Zip Codes:",				buttons: {					success: {						label: "Close",						className: "btn-success btn-sm"					}				}			});			resetActive(event, 0, 'step-1');			$(this).parent().removeClass('activestep');			$('#div1').addClass('activestep');		}
	});
	$('.stepProceed3').click(function() {			var form_error = false;		$("#step-2 input").each(function(index ) {			var value = $(this).val();			if($(this).attr("required")) {				if(value.length==0) {					$(this).addClass('error_input');					form_error = true;				} else {					$(this).removeClass('error_input');				}			} else {				$(this).removeClass('error_input');			}		});		$("#step-2 select").each(function(index ) {			var value = $(this).val();			if($(this).attr("required")) {				if(value.length==0) {					$(this).addClass('error_input');					form_error = true;				} else {					$(this).removeClass('error_input');				}			}		});		if(form_error) {			var l = 15;  			for(var i=0;i<10;i++)  {				$("#step-2").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  			}			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();			resetActive(event, 25, 'step-2');			$(this).parent().removeClass('activestep');			$('#div2').addClass('activestep');		} else {			var frequncy = $('#frequency').val();			if(frequncy == 1) {				$('.cc-frequncy').html('<b>Billing Cycle:</b> Monthly');				$('.freq').html('Per Month');			} else if (frequncy == 3) {				$('.freq').html('Per Quarter');				$('.cc-frequncy').html('<b>Billing Cycle:</b> Every '+frequncy+' Months');			} else if (frequncy == 6) {				$('.freq').html('Bi-Yearly');				$('.cc-frequncy').html('<b>Billing Cycle:</b> Every '+frequncy+' Months');			} else if (frequncy == 12) {				$('.freq').html('Yearly');				$('.cc-frequncy').html('<b>Billing Cycle:</b> Every '+frequncy+' Months');			}					$('#showError').fadeOut();			resetActive(event, 50, 'step-3');
			$('#div2').removeClass('activestep');
			$('#div3').addClass('activestep');		}
	});
	$('.stepProceed4').click(function() {		var form_error = false;		var name_on_card = $('#name_on_card').val;				if($('#checkout_card_number').hasClass('error_input_cc')) {			$('.cc_helper').html('<span class="text-danger">* Invalid Credit Card</span>');			form_error = true;		} else {			$('.cc_helper').html('');		}				$("#step-3 input").each(function(index ) { //checks inputs for values			var value = $(this).val();			if($(this).attr("required")) {				if(value.length==0) {					$(this).addClass('error_input');					form_error = true;				} else {					$(this).removeClass('error_input');				}			} else {				$(this).removeClass('error_input');			}			if($(this).hasClass('error_input')) {				form_error = true;			}		});				$("#step-3 select").each(function(index ) { //checks select boxes for values			var value = $(this).val();			if($(this).attr("required")) {				if(value.length==0) {					$(this).addClass('error_input');					form_error = true;				} else {					$(this).removeClass('error_input');				}			}		});								if(form_error) {			var l = 15;  			for(var i=0;i<10;i++)  {				$("#step-3").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  			}			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();			resetActive(event, 25, 'step-3');			$(this).parent().removeClass('activestep');			$('#div3').addClass('activestep');		} else {			$('#showError').fadeOut();			resetActive(event, 75, 'step-4');			$('#div3').removeClass('activestep');			$('#div4').addClass('activestep');		}
	});			$('.stepProceed5').click(function() {		form_error = false;						$("#step-4 input").each(function(index ) { //checks inputs for values			var value = $(this).val();			if($(this).attr("required")) {				if(value.length==0) {					$(this).addClass('error_input');					form_error = true;				} else {					$(this).removeClass('error_input');				}			} else {				$(this).removeClass('error_input');			}			if($(this).hasClass('error_input')) {				form_error = true;			}		});					var username = $('#user').val();		if(username.length<7) {			$('.user-error-text').html('<span class="text-danger">Username Must Be 7 Characters Long Or More</span>');			$('#user').addClass('error_input');			form_error = true;		} else {			if($('#user').hasClass('error')) {				form_error = true;			}		}						var userEmail = $('#email').val();		if(userEmail.length<7) {			form_error = true;			$('#email').addClass('error_input');			$('.email-error-text').html('<span class="text-danger">Must use a valid email address</span>');		} else {			//if(validateEmail(userEmail)) {			if(userEmail.length>0) {				$('.email-error-text').html('');			} else {				$('#email').addClass('error_input');				$('.email-error-text').html('<span class="text-danger">Must use a valid email address</span>');				form_error = true;			}		}					if($('#email').hasClass('error')) {			form_error = true;		}					var pwd = $('#password').val();		var pwd2 = $('#password2').val();		if(pwd.length<7) {			form_error = true;			$('.password-error-text').html('<span class="text-danger">Password Must Be 7 Characters Long Or More</span>');		} else {			$('.password-error-text').html('');			if(pwd != pwd2) {				form_error = true;					$('.password-error-text').html('<span class="text-danger">Passwords Do Not Match, Try Again</span>');			} else {				$('.password-error-text').html('');			}		}						if($("#termsService").is(':checked')) {			$('.terms-error-text').html('');		} else {			form_error = true;			$('.terms-error-text').html('<span class="text-danger">You Must Agree To The Terms Of Service</span>');		}						if(form_error == true) {			var l = 15;  			for(var i=0;i<10;i++)  {				$("#step-4").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  			}			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();			resetActive(event, 75, 'step-4');			$(this).parent().removeClass('activestep');			$('#div4').addClass('activestep');		} else {			var blankPassword = '***********';						$('.step4details .fill-out').html('<b>User Name:</b> '+username+'<br><b>Email:</b>  '+userEmail+'<br><b>Password:</b> '+blankPassword);			$('#showError').fadeOut();			resetActive(event, 100, 'step-5');			$('#div4').removeClass('activestep');			$('#div5').addClass('activestep');		}			});			
});
function validateEmail(email) {	var filter = /^[w-.+]+@[a-zA-Z0-9.-]+.[a-zA-z0-9]{2,4}$/;	if (filter.test(email)) {		return true;	} else {		return false;	}}
function resetActive(event, percent, step) {
	$(".progress-bar").css("width", percent + "%").attr("aria-valuenow", percent);
	$(".progress-completed").text(percent + "%");

	$("div").each(function () {
		if ($(this).hasClass("activestep")) {
			$(this).removeClass("activestep");
		}
	});

	if (event.target.className == "col-md-2") {
		$(event.target).addClass("activestep");
	}
	else {
		$(event.target.parentNode).addClass("activestep");
	}

	hideSteps();
	showCurrentStepInfo(step);
}
function hideSteps() {
	$("div").each(function () {
		if ($(this).hasClass("activeStepInfo")) {
			$(this).removeClass("activeStepInfo");
			$(this).addClass("hiddenStepInfo");
		}
	});
}
function showCurrentStepInfo(step) {        
	var id = "#" + step;
	$(id).addClass("activeStepInfo");
}