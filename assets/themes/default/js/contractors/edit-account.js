$(function() {
	$('.phone').mask('(999) 999-9999');
	$('[data-toggle="tooltip"]').tooltip();
	$('#updatePayment').click(function(e) {
		e.preventDefault();
		var formData = $('#paymentForm').serialize();
		console.log(formData);
		$.ajax("//network4rentals.com/network/ajax-contractors/update-payment", {
			data: formData,
			dataType: "json",
			success: function(t) {
				
			},
			complete: function() {
				$(this).html('Update Credit Card').attr('disabled', false);
			},
			beforeSend: function() {
				$(this).html('<i class="fa fa-spin fa-spinner"></i> Updating Credit Card').attr('disabled', true);
			}, 
			error: function(e, t, n) {
				$(this).html('Update Credit Card').attr('disabled', false);
				if(t==="timeout") {
					alert('Updating your credit card info took longer then expected and timed out, please try again');
				}
			},
			timeout: 7000
		})
	});
	
	
});