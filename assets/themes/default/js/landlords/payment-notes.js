$(function() {

	$('.viewNotes').click(function() {
		var payment_id = $(this).data('payment');
		var altId = $(this).data('altid');
		var elem = $(this);
		$(this).parent().parent().prev().html('<i class="fa fa-refresh fa-spin"></i> Loading');
		if(payment_id>0) {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/get_payment_notes/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:payment_id},
				success: function(response) {
					console.log(response);
					var notes = '';
					for(var i=0;i<response.length;i++) {
						
						if(response[i].sent_by == 'landlord') {
							notes += '<li><b class="text-primary">You:</b> <small>'+response[i].ts+'</small><br>';
								notes += response[i].note;
							notes += '</li>';
						} else {
							notes += '<li class="text-right"><b class="text-warning">Tenant:</b> <small>'+response[i].ts+'</small><br>';
								notes += response[i].note;
							notes += '</li>';
						}
					}
					if(notes.length==0) {
						notes = '<li id="noNotes"><p>No Notes Left On This Payment Yet</p></li>'
					}
					$('#payment_id').html('<input type="hidden" name="payment_id" id="paymentId" value="'+payment_id+'"><input type="hidden" name="altId" id="altId" value="'+altId+'">');
					
					$('#payment-notes-details').html(notes);
					$('#payment-notes').delay(300).modal('show');
					elem.parent().parent().prev().html('Options <span class="caret"></span>');
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error pulling the notes, try again", "error");
					elem.parent().parent().prev().html('Options <span class="caret"></span>');
				},
				timeout: 6000,
				beforeSend: function() {
					
				},
				complete: function() {
					$(this).parent().parent().prev().html('Options <span class="caret"></span>');
				}
			});
		} else {
			$.notify("Error pulling the notes, try again", "error");
		}
	});
	
	$('#addNewNote').click(function(e) {
		e.preventDefault();
		var note = $('#noteDetails').val();
		var id = $('#paymentId').val();
		var altId = $('#altId').val();
		var elem = $(this);
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Sending');
		
		if(id>0 && note !='') {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/add-new-payment-note/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:id, note:note,type:'landlord', altid:altId},
				success: function(response) {
					var notes = '';
					if(response.success !== undefined) {
						notes += '<li class="text-success"><b>You:</b><br>';
						notes += note;
						notes += '</li>';
						$('#noNotes').remove();
						$('#payment-notes-details').append(notes);
						$('#noteDetails').val('');
						$.notify("Note added successfully", "success");
						setTimeout(removeNoteSuccess, 1000);
					} else {
						$.notify(response.error, "error");
					}
					elem.html('Add Note');
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error pulling the notes, try again", "error");
					elem.html('Add Note');
				},
				timeout: 6000
			});
		} else {
			$.notify("Note field is required", "error");
			elem.html('Add Note');
		}
	});
	
	$('#addDispute').click(function(e) {
		e.preventDefault();
		var note = $('#disputeDetails').val();
		var id = $('#disputeId').val();
		var altId = $('#alt-Id').val();
		var elem = $(this);
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Disputing');
		if(id>0 && note !='') {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/dispute-payment/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:id, note:note,type:'landlord', altid:altId},
				success: function(response) {
					var notes = '';
					if(response.success !== undefined) {
						$('#disputeDetails').val('');
						$.notify("Dispute added successfully", "success");
						$('#disputePayment').modal('hide');
						$('.table #'+id).addClass('disputed');
						$('.table #'+id+' .dispute-row').html('<a href="#" class="settleDispute" data-altid="'+altId+'" data-payment="'+id+'"><i class="fa fa-flag-o"></i> Settle Dispute</a>');
					} else {
						$.notify(response.error, "error");
					}
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error adding dispute, try again "+errorMessage, "error");
				},
				timeout: 6000,
				beforeSend: function() {
					
				},
				complete: function() {
					elem.html('Add Dispute');
				}
			});
		} else {
			$.notify("Note field is required", "error");
		}
	});
	
	$('.dispute').click(function() {
		var payment_id = $(this).data('payment');
		var altId = $(this).data('altid');
		var elem = $(this);
		if(payment_id>0) {
			$('#dispute_id').html('<input type="hidden" name="payment_id" id="disputeId" value="'+payment_id+'"><input type="hidden" name="alt_Id" id="alt-Id" value="'+altId+'">');
			$('#disputePayment').modal('show');
		}
	});
	
	$('.settleDispute').click(function() {
		var payment_id = $(this).data('payment');
		var altId = $(this).data('altid');
		var elem = $(this);
		$('#dispute-data').html('<input type="hidden" name="payment_id" id="settlePaymentId" value="'+payment_id+'"><input type="hidden" name="altId" id="alternativeId" value="'+altId+'">');
		$('#settleDispute').modal('show');
	});
	
	$('#resolveDispute').click(function(e) {
		e.preventDefault();
		var payment_id = $('#settlePaymentId').val();
		var altId = $('#alternativeId').val();
		var elem = $(this);
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Resolving');
		if(payment_id>0) {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/resolve_payment_dispute/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:payment_id, alt_id:altId},
				success: function(response) {	
					if(response.success != 'undefined') {
						$.notify("Payment dispute marked as complete", "success");
						$('#settleDispute').modal('hide');
						$('#'+payment_id).removeClass('disputed');
						$('#'+payment_id).removeClass('disputed');
						$('#'+payment_id+' .dispute-row, #'+payment_id+' .divider').remove();
						
					} else {
						$.notify("Error resolving payment depute, try again ", "error");
					}
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error resolving payment depute, try again ", "error");
				},
				timeout: 6000,
				complete: function() {
					elem.html('Settle Dispute');
				}
			});
		} else {
			$.notify("Error settling payment depute, try again", "error");
		}
	});

	$('.editPayment').click(function(e) {
		e.preventDefault();
		$('#editPayment').modal('show');
	});
	

	
});


function removeNoteSuccess() {
	$('#payment-notes-details li').each(function() {
		$(this).removeClass('text-success');
	});
}