$(function() {

$('#invoice-search').keyup(function() {
		var search = $(this).val();
		
		
		if(search.length>2) {
			$('.init').addClass('hide');
		
			$.ajax({
				type: 'POST',
				url: '//network4rentals.com/network/ajax/invoicesearch/',
				data: {'search':search},
				success: function(data){
					if(typeof data.error == 'undefined') {
						if(data.length>4) {
							$('.searchdata').remove();
							$('#payments tbody').append(data);
						} else {
							$('.searchdata').remove();
							if(!$('#payments tbody').find('tr').hasClass('noResults')) {
								$('#payments tbody').append('<tr class="searchdata noResults"><td  colspan="7" class="text-center"><br><i class="fa fa-times-circle text-danger"></i> No Results Found<br><br></td></tr>');
							}
						}
					} else {
						
						if(!$('#payments tbody').find('tr').hasClass('noResults')) {
							$('#payments tbody').append('<tr class="searchdata noResults"><td  colspan="7" class="text-center"><br><i class="fa fa-times-circle text-danger"></i> No Results Found<br><br></td></tr>');
						}
					}
					
				},
				error: function(xhr, type, exception) { 
					$('#payments tbody').append('<tr class="searchdata errorSearch"><td colspan="7"><br><i class="fa fa-times-circle text-danger"></i>Error Searching For Results<br><br></td></tr>');
				},
				beforeSend: function() {
					if(!$('#payments tbody').find('tr').hasClass('searching')) {
						$('#payments tbody').append('<tr class="searchdata searching"><td  colspan="7" class="text-center"><br><i class="fa fa-spinner fa-spin"></i> Searching...<br><br></td></tr>');
					}
					$('.noResults').remove();
				},
				complete: function() {
					$('.searching').remove();
					$('.errorSearch').remove();
				}
			});
		} else {
			$('.searchdata').remove();
			$('.init').removeClass('hide');
		}
	});

	$('#payment-search').keyup(function() {
		var search = $(this).val();
		
		
		if(search.length>2) {
			$('.init').addClass('hide');
		
			$.ajax({
				type: 'POST',
				url: '//network4rentals.com/network/ajax/paymentsearch/',
				data: {'search':search},
				success: function(data){
					if(typeof data.error == 'undefined') {
						if(data.length>4) {
							$('.searchdata').remove();
							$('#payments tbody').append(data);
						} else {
							$('.searchdata').remove();
							if(!$('#payments tbody').find('tr').hasClass('noResults')) {
								$('#payments tbody').append('<tr class="searchdata noResults"><td  colspan="7" class="text-center"><br><i class="fa fa-times-circle text-danger"></i> No Results Found<br><br></td></tr>');
							}
						}
					} else {
						
						if(!$('#payments tbody').find('tr').hasClass('noResults')) {
							$('#payments tbody').append('<tr class="searchdata noResults"><td  colspan="7" class="text-center"><br><i class="fa fa-times-circle text-danger"></i> No Results Found<br><br></td></tr>');
						}
					}
					
				},
				error: function(xhr, type, exception) { 
					$('#payments tbody').append('<tr class="searchdata errorSearch"><td colspan="7"><br><i class="fa fa-times-circle text-danger"></i>Error Searching For Results<br><br></td></tr>');
				},
				beforeSend: function() {
					if(!$('#payments tbody').find('tr').hasClass('searching')) {
						$('#payments tbody').append('<tr class="searchdata searching"><td  colspan="7" class="text-center"><br><i class="fa fa-spinner fa-spin"></i> Searching...<br><br></td></tr>');
					}
					$('.noResults').remove();
				},
				complete: function() {
					$('.searching').remove();
					$('.errorSearch').remove();
				}
			});
		} else {
			$('.searchdata').remove();
			$('.init').removeClass('hide');
		}
	});
	
	$('#create-invoice').submit(function(event) {
		event.preventDefault();
		var form = document.getElementById('create-invoice');
		var formData = new FormData(form);
		formData.append('type', $('#create-invoice').data('type'));
		$.ajax({
			url: '//network4rentals.com/network/ajax/create-invoice/',
			type: 'POST',
			success: function(data) {
				console.log(data);
				if(typeof data.success != 'undefined') {
					window.location.href = $('#create-invoice').data('url');
				} else {
					$(".progress-bar").width("5%").addClass('progress-bar-danger').html('5%');
					$('#feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+data.error+'</div>');
				}
			},
			error: function(xhr, type, exception) {
				console.log('error');
				console.log(xhr);
				console.log(type);
				console.log(exception);
				$(".progress-bar").width("0%").addClass('progress-bar-danger');
				$("#feedback").html('<div class="alert alert-danger">' +
					'<i class="fa fa-exclamation-triangle"></i> Invoice Failed, try refreshing the page and trying again!</div>');
			},
			beforeSend: function() {
				$("#feedback").html('');
				$(".progress-bar").width("0%").removeClass('progress-bar-danger');
				$('#submit').html('<i class="fa fa-spinner fa-spin"></i> Creating Invoice').attr('disabled', true);
				$('input[name="file"]').attr('disabled', true);
				$('.progress').removeClass('hide');
			},
			complete: function() {
				//$('.progress').addClass('hide');
				$('#submit').html('Create Invoice').attr('disabled', false);
				$('input[name="file"]').attr('disabled', false);
				$('.alert-warning').remove();

			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
		}, 'json');
	});
	
	
	$('#addPaymentBtn').click(function() {
		
		var form = document.getElementById('addPayment');
		var formData = new FormData(form);
		$.ajax({
			url: '//network4rentals.com/network/ajax/addOfflinePayment/',
			type: 'POST',
			success: function(data) {
				if(typeof data.success != 'undefined') {
					window.location.href = $('#addPaymentBtn').data('url');
				} else {
					$('#feedback').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '+data.error+'</div>');
				}
			},
			error: function(xhr, type, exception) {
				console.log('error');
				$("#feedback").html('<div class="alert alert-danger">' +
					'<i class="fa fa-exclamation-triangle"></i> Invoice Failed, try refreshing the page and trying again!</div>');
			},
			beforeSend: function() {
				$("#feedback").html('');
				$('#addPaymentBtn').html('<i class="fa fa-spinner fa-spin"></i> Saving Payment...').attr('disabled', true);
			},
			complete: function() {
				$('#addPaymentBtn').html('Save Payment').attr('disabled', false);
				$('.alert-warning').remove();

			},
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
		}, 'json');
	});
	
	
});