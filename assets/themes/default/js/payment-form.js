$(document).ready(function() {
	
	$.validator.setDefaults({
	  debug: false,
	  success: "valid"
	});
	$( "#cc_form" ).validate({
	  rules: {
		cc_number: {
		  required: true,
		  creditcard: true
		}
	  }
	});


	// Shows the form the user selects 
	$('#payment-type').change(function() {
		var payment_type = $(this).val();
		if(payment_type == 1) {
			$('.payment-card').css({'display':'block'}).addClass('showing');
		} else if(payment_type == 2) {
			$('.payment-card').css({'display':'block'}).addClass('showing');
		} else if(payment_type == 3) {
			$('.payment-check').css({'display':'block'}).addClass('showing');
		} else if(payment_type == 4) {
			$('.payment-offline').css({'display':'block'}).addClass('showing');
		} else {
			$('.payment-check').css({'display':'none'});
			$('.payment-card').css({'display':'none'});
			$('.payment-offline').css({'display':'none'});
		}
	
		$('.payment-forms').each(function () {
			if($(this).hasClass('showing')) {
				$(this).removeClass('showing');
			} else {
				$(this).css({'display':'none'});
			}
		});
	}); 
	
    $('.routing-number').change(function() {
		

	
		
    });
	
	
});

