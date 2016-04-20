$(function() {
	$('#more-filters').click(function() {
		if($('#filtered-options').is(':visible')) {
			$('#filtered-options').slideUp();
			$('#more-filters i').removeClass('fa-rotate-180');
		} else {
			$('#filtered-options').slideDown();
			$('#more-filters i').addClass('fa-rotate-180');
		}
	});
	
	
	
	var filteredCount = 0;
	$('input:checked').each(function () {
		filteredCount++;
	});
	if(filteredCount>0) {
		$('#more-filters .label').html(filteredCount);
	}
	
	$('input[type=checkbox]').click(function(){
		if($(this).is(':checked')) { 
			filteredCount++;
		} else {
			filteredCount--;
		}
		if(filteredCount>0) {
			$('#more-filters .label').html(filteredCount);
			$('#more-filters .label').css({'display':'inline'});
		} else {
			$('#more-filters .label').css({'display':'none'}).html('');
		}
	});
	
	$('#clearFilters').click(function(event) {
		event.preventDefault();
		$('input:checked').each(function () {
			$(this).attr('checked', false);
		});
		filteredCount=0;
		$('#more-filters .label').css({'display':'none'}).html('');
	});
	
	
	$('#contact-landlord').submit(function(event) {
		event.preventDefault();
		var data = $(this).serialize();
		$('#form-errors').html('');
		$.ajax({
			url: '//network4rentals.com/network/ajax-listings/contact-landlord/',
			type: 'post',
			dataType: 'json',
			data: data,
			timeout: 15000,
			success: function(response) {
				console.log(response);
				Recaptcha.reload();
				if(typeof response.errors == 'undefined') {
					location.reload();
				} else {
					$('#form-errors').html('<div class="alert alert-danger"><b>Error:</b> '+response.errors+'</div>');
				}
			},
			error: function(x, t, m) {
				if(t==="timeout") {
					$('#form-errors').html('<div class="alert alert-danger"><b>Error:</b> Form timed out, try again</div>');
				} else {
					$('#form-errors').html('<div class="alert alert-danger"><b>Error:</b> Something went wrong, refresh your page and try again</div>');
				}
				$('#sendit').html('<i class="fa fa-envelope"></i> Send').attr('disabled', false);
				Recaptcha.reload();
			},
			complete: function() {
				$('#sendit').html('<i class="fa fa-envelope"></i> Send').attr('disabled', false);
			},
			beforeSend: function() {
				$('#sendit').html('<i class="fa fa-circle-o-notch fa-spin"></i> Sending</i>').attr('disabled', true);
			}
		});
	});
});