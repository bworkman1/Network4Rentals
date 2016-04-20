$(function() {
	alertify.set({ delay: 15000 });
	
	$('#payment-page').submit(function(event) {
		event.preventDefault();
		var dataInput = $(this).serialize();
		$.ajax({
			url: 'https://network4rentals.com/network/ajax-contractors/submit-new-account/',
			dataType: 'json',
			data: dataInput,
			type: 'POST',
			success: function(response) {
				console.log(response);
				if(typeof response.error == 'undefined') {
					alertify.success(response.success);
					window.location.replace("https://network4rentals.com/network/contractor/manage-zips");
				} else {
					alertify.error(response.error);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				alertify.error("There was an error processing your request, please try again. If the problem persist contact support.");
			},
			beforeSend: function() {
				$('#submit').html('<i class="fa fa-cog fa-spin"></i> Submitting').attr('disabled', true);	
			},
			complete: function() {
				$('#submit').html('Join Now').attr('disabled', false);
			},
		});
	});
	
	$('.payment-plan').change(function () {
		$('.payment-plan').parent().parent().removeClass('selected');
		$(this).parent().parent().addClass('selected');
		var cost = $(this).val();
		$('#userTotal').html('<p>Total: $'+cost+' a Month</p>');
		var percent = $('#percent').val();
		var freq = $(this).data('freq');
		if(typeof percent != 'undefined') {
			var total = $("input[type='radio']:checked").val();
			var newTotal = (total-(parseFloat(total)*(parseInt(percent)/100))).toFixed(2);
			$('#userTotal').html('<p>Total: $'+newTotal+' '+freq+'</p>');
			alertify.success('Your total has changed');
		} else {
			$('#userTotal').html('<p>Total: $'+cost+' '+freq+'</p>');
		}
	});
	
	if( $('input[name=credit_card]').length>0 ) {
		$('input[name=credit_card]').mask("9999-9999-9999-9999");
	};
	
	$('#searchPromo').click(function() {
		var promo = $('#promocode').val();
		$.ajax("//network4rentals.com/network/local-partner/create-account/checkpromo/", { 
			dataType: 'json',
			data: {'promo':promo},
			type: 'POST',
			success: function(response) {
				console.log(response);
				if(typeof response.error == 'undefined') {
					if(response.success>0) {
						$('#otherSettings').html('<input type="hidden" id="percent" value="'+response.success+'">');
						var total = $("input[type='radio']:checked").val();
						var freq = $("input[type='radio']:checked").data('freq');
						var newTotal = (total-(parseFloat(total)*(parseInt(response.success)/100))).toFixed(2);
						$('#userTotal').html('<p>Total: $'+newTotal+' '+freq+'</p>');
						alertify.success('Enjoy a discout of '+response.success+'% for the first year');
					} else {
						alertify.error('Promo code expired or invalid');
						$('#promocode').val('');
					}
				} else {
					alertify.error(response.error);
				}
			},
			error: function(e, t, n) {
				alertify.error('There was a problem processing the request, try again');
			},
			beforeSend: function() {
				$('#searchPromo').html('<i class="fa fa-cog fa-spin fa-lg"></i>').attr('disabled', true);
			},
			complete: function() {
				$('#searchPromo').html('<i class="fa fa-search"></i>').attr('disabled', false);
			}
		});
	});
});