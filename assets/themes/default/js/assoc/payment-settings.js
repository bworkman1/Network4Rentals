$(function() {
	
	// validate that there is a first and last name
	$('#card-holder-name').focusout(function() {
		var name = $(this).val();
		var name_array = name.split(' ');
		if(name_array.length<2) {
			$(this).tooltip('show');
			$(this).parent().parent().addClass('has-error').removeClass('has-success');
			$(this).addClass('isError');
		} else {
			$(this).parent().parent().removeClass('has-error').addClass('has-success');
			$(this).tooltip('destroy');
			$(this).removeClass('isError');
		}
	});	
	
	// validate credit card
	$('#card-number').focusout(function() {
		var cc = $(this).val();

		if(valid_credit_card(cc)) {
			$(this).parent().parent().removeClass('has-error').addClass('has-success');
			$(this).tooltip('destroy');
			$(this).removeClass('isError');
		} else {
			$(this).addClass('isError');
			$(this).parent().parent().addClass('has-error').removeClass('has-success');
			$(this).tooltip('show');
		}
	});	 
	
	
	$('#expiry-month').focusout(function() {
		var month = $(this).val();
		if(month.length>0) {
			$(this).removeClass('isError');
			$(this).css({'border-color':'#3c763d !important;'});
		} else {
			$(this).addClass('isError');
			$(this).css({'border-color':'#a94442 !important;'});
		}
	});

	$('#expiry-year').focusout(function() {
		var year = $(this).val();
		if(year.length>0) {
			$(this).css({'border-color':'#3c763d;'});
			$(this).removeClass('isError');
		} else {
			$(this).addClass('isError');
			$(this).css({'border-color':'#a94442;'});
		}
	});	
	
	$('#cvv').focusout(function() {
		var cvv = $(this).val();
		if(cvv.length>0) {
			$(this).removeClass('isError');
			$(this).parent().parent().removeClass('has-error').addClass('has-success');
		} else {
			$(this).addClass('isError');
			$(this).parent().parent().addClass('has-error').removeClass('has-success');
		}
	});
	
	$('#card-number').mask('9999-9999-9999-9999');
	
	$('#payment').submit(function(e) {
		console.log('test');
		e.preventDefault();
		var formSubmit = true;
		
		
		if(formSubmit) {
			$(this).submit();
			$('.submitError').html('');
		} else {
			$('.submitError').html('<div class="text-danger">Fix the errors before submitting your payment</div>');
		}
		
	});

});

function valid_credit_card(value) {
  // accept only digits, dashes or spaces
	if (/[^0-9-\s]+/.test(value)) return false;

	// The Luhn Algorithm. It's so pretty.
	var nCheck = 0, nDigit = 0, bEven = false;
	value = value.replace(/\D/g, "");

	for (var n = value.length - 1; n >= 0; n--) {
		var cDigit = value.charAt(n),
			  nDigit = parseInt(cDigit, 10);

		if (bEven) {
			if ((nDigit *= 2) > 9) nDigit -= 9;
		}

		nCheck += nDigit;
		bEven = !bEven;
	}

	return (nCheck % 10) == 0;
}