$(function() {
	$('.partial-payments button').click(function() {
		var selection = $(this).data('option');
		var type = $(this).data('type');
		var id = $(this).data('id');
		if(selection==='y') {
			$('.partial-payments .no').removeClass('btn-danger').removeClass('btn-default');;
			$('.partial-payments .yes').addClass('btn-success').addClass('btn-default');
			$('.minPayment-input').addClass('in');
		} else {
			$('.partial-payments .no').addClass('btn-danger').addClass('btn-default');;
			$('.partial-payments .yes').removeClass('btn-success').removeClass('btn-default');
			$('.minPayment-input').removeClass('in');
		}
	});
	
	$('.payment-settings button').click(function() {
		var selection = $(this).data('options');
		var type = $(this).data('type');
		var id = $(this).data('id');
		if(selection==='y') {
			$('.payment-settings .no').removeClass('btn-danger').addClass('btn-default');
			$('.payment-settings .yes').addClass('btn-success').removeClass('btn-default');
		} else {
			$('.payment-settings .no').addClass('btn-danger').removeClass('btn-default');
			$('.payment-settings .yes').removeClass('btn-success').addClass('btn-default');
		}
	});

	$('.minPayment').focusout(function() {
		var amount = $(this).val();
		var type = 'amount';
		var id = $(this).data('id');
		if(amount != '') {
			$(this).removeClass('input-error');
		} else {
			$(this).addClass('input-error');
		}
	});
	
	$('.discount').focusout(function() {
		var amount = $(this).val();
		var type = 'discount';
		var id = $(this).data('id');
		if(amount != '') {
			$(this).removeClass('input-error');
		} else {
			$(this).addClass('input-error');
		}
	});
	
	$('.money').blur(function() {
		$('.money').formatCurrency({
			symbol: ''
		});
	});
	
	$('.discountPayment').focusout(function() {
		var amount = $(this).val();
		var type = 'discount_payment';
		var id = $(this).data('id');
		if(amount != '') {
			$(this).removeClass('input-error');
		} else {
			$(this).addClass('input-error');
		}
	});
	
	$('.addNoteToPayment').click(function() {
		var id = $(this).data('paymentid');
		var elem = $(this);
		$('#payment-notes form').get(0).setAttribute('action', 'https://network4rentals.com/network/landlords/add-note-payment/'+id);
		$(this).html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading');
		$.ajax({
			url: 'https://network4rentals.com/network/ajax/get_payment_notes/', 
			dataType: "json",
			cache: false,
			type: "post",
			data: {id:id},
			success: function(response) {
				$('#payment-notes').modal('show');
				elem.html('<i class="fa fa-file-o"></i> Notes');
				var notes = '';
				for(var i=0;i<response.length;i++) {
					if(response[i].sent_by == 'landlord') {
						notes += '<li><b class="text-primary">You:</b><br>';
							notes += response[i].note;
						notes += '</li>';
					} else {
						notes += '<li class="text-right"><b class="text-warning">Tenant:</b><br>';
							notes += response[i].note;
						notes += '</li>';
					}
				}
				if(notes.length==0) {
					notes = '<li><h3>No Notes Left On This Payment Yet</h3></li>'
				}
				$('#payment-notes-details').html(notes);
			},
			error: function(request, errorType, errorMessage) {
				$('#payment-notes').html('<div class="text-danger">Error Retrieving Info</div>').fadeIn();
			},
			timeout: 6000,
			beforeSend: function() {
				elem.html('<i class="fa fa-file-o"></i> Notes');
			},
			complete: function() {
				elem.html('<i class="fa fa-file-o"></i> Notes');
			}
		}); 
	});	
	
	$('.viewNotes').click(function() {
		var payment_id = $(this).data('payment');
		var altId = $(this).data('altid');
		var elem = $(this);
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Loading');
		if(payment_id>0) {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax/get_payment_notes/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:payment_id},
				success: function(response) {
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
					$('#payment_id').html('');
					$('#payment_id').html('<input type="hidden" name="payment_id" id="paymentId" value="'+payment_id+'"><input type="hidden" name="altid" id="altid" value="'+altId+'">');
					
					$('#payment-notes-details').html(notes);
					$('#payment-notes').delay(300).modal('show');
					elem.html('<i class="fa fa-file"></i> Notes');
					$('#fileNote').val();
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error pulling the notes, try again", "error");
					elem.html('<i class="fa fa-file"></i> Notes');
				},
				timeout: 6000,
				beforeSend: function() {
					
				},
				complete: function() {
					elem.html('<i class="fa fa-file"></i> Notes');
				}
			});
		} else {
			$.notify("Error pulling the notes, try again", "error");
			elem.html('<i class="fa fa-file"></i> Notes');
		}
	});
	
	$('#addNewNotse').click(function(e) {
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
					console.log(response);
					var notes = '';
					if(response.success !== undefined) {
						notes += '<li class="text-success"><b>You:</b><br>';
						notes += note;
						notes += '</li>';
						$('#noNotes').remove();
						$('#payment-notes-details').append(notes);
						$('#noteDetails').val('');
						$.notify("Note added successfully", "success");
						setTimeout(removeNoteSuccess, 2000);
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
	
	$('#noteForm').on('submit', function(e) {
        e.preventDefault();
		$('.progress').slideDown();
        var formData = new FormData(this);
		formData.append("type", 'landlord');
		$('.uploaderProgress').css({'display':'block'});
        $.ajax({
            type:'POST',
			xhr: function () {
			myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
				}
				return myXhr;
			},
            url: 'https://network4rentals.com/network/ajax/add_new_payment_note/landlords/',
            data:formData,
            cache:false,
			dataType: 'json',
            contentType: false,
            processData: false,
            success:function(response){				
				var notes = '';
				if(response.success !== undefined) {
					notes += '<li class="text-success"><b class="text-primary">You:</b> <small>'+response.time+'</small><br>';
					notes += response.note;
					if(response.file !== '') {
						notes += '<br><a href="https://network4rentals.com/network/message-uploads/p_notes/'+response.file+'" target="_blank"><i class="fa fa-paper-clip"></i> '+response.file+'</a>';
					}
					notes += '<hr></li>';
					$('#noNotes').remove();
					$('#payment-notes-details').append(notes);
					$('#noteDetails').val('');
					$.notify("Note added successfully", "success");
					setTimeout(removeNoteSuccess, 1000);
				} else {
					$.notify(response.error, "error");
				}
				$('#addNewNote').html('Add Note').attr('disabled', false);
				$('#fileNote').val('');
            },
            error: function(data){
                
            }, 
			complete: function(data) {
				$('.progress').slideUp();
				$('.progress-bar').css({'width':'0'})
			}
        });
		function progressHandlingFunction(e) {
			if (e.lengthComputable) {
				var s = parseInt((e.loaded / e.total) * 100);
				$(".progress-bar").width(s + "%");
				$(".status").text(s + "% Complete");
				if (s == 100) {
					
				}
			}
		}
    });	
	
	$('#fileNote').change(function () {
		var ext = this.value.match(/\.(.+)$/)[1];
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'doc':
			case 'docx':
			case 'pdf':
				break;
			default:
				alert('This is not an allowed file type.');
				this.value = '';
		}
	});
	
	$('.dateField').focusout(function(){
		var txtVal =  $(this).val();
		if(textVal.length>0) {
			if(isDate(txtVal)) {
				$(this).removeClass('input-error');
				$('.submitTenatDetails').attr('disabled', false);
			} else {
				$(this).addClass('input-error');
				$('.submitTenatDetails').attr('disabled', true);
				alert('Invalid Date');
			}
		}
	});

	$('.saveButton').click(function() {
		
		var accept = $('.payment-settings .btn-default').data('options');
		var partial = $('.partial-payments .btn-default').data('option');		
		if(accept == 'y') {
			accept = 'n';
		} else {
			accept = 'y';
		}
		
		var data = {
			'discount_payment': $('.discountPayment').val(),
			'id':$('.btn.payments').data('id'),
			'min_payment':$('.minPayment').val(),
			'auto_pay_discount':$('.discount').val(),
			'payments_allowed': accept,
			'partial_payments': partial,
		};
		savePaymentSettings(data);
	});
});
function isDate(txtDate) {
  var currVal = txtDate;
  if(currVal == '')
    return false;
  var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/;
  var dtArray = currVal.match(rxDatePattern); // is format OK?
  if (dtArray == null)
     return false;
	dtMonth = dtArray[1];
    dtDay= dtArray[3];
    dtYear = dtArray[5]; 
  if (dtMonth < 1 || dtMonth > 12)
      return false;
  else if (dtDay < 1 || dtDay> 31)
      return false;
  else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
      return false;
  else if (dtMonth == 2)  {
     var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
     if (dtDay> 29 || (dtDay ==29 && !isleap))
          return false;
  }
  return true;
}

function savePaymentSettings(data) {
	$.ajax({
		url: 'https://network4rentals.com/network/ajax/toggle_tenant_payment_settings/',
		cache: false,
		type: "POST",
		dataType: "json",
		data: data,
		success: function(response) {
			if(typeof response.success != 'undefined') {
				$('#saveFeedback').html('<div class="alert alert-success removeBox"><b><i class="fa fa-check-circle fa-lg fa-fw"></i> '+response.success+'</b></div>');
			} else {
				$('#saveFeedback').html('<div class="alert alert-danger removeBox"><b><i class="fa fa-check-circle fa-lg fa-fw"></i> '+response.error+'</b></div>');
			}
		},
		beforeSend: function() {
			$('.saveButton').html('<i class="fa fa-spinner fa-spin"></i> Saving');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			console.log(thrownError);
			$('#saveFeedback').html('<div class="alert alert-danger removeBox"><b><i class="fa fa-times-circle fa-lg fa-fw"></i> Something went wrong, try again!</b></div>');
		},
		complete: function() {
			$('.saveButton').html('<i class="fa fa-save"></i> Save');
			setTimeout(removeSaveFeedback, 5000);
		}
	});
}


function removeSaveFeedback() {
  $('#saveFeedback').html('');
}

function change_payment_settings(status, column, id) {
	$('.saveButton').html('<i class="fa fa-spinner fa-spin"></i> Saving');
	$.ajax({
		url: 'https://network4rentals.com/network/ajax/toggle_tenant_payment_settings/',
		cache: false,
		type: "POST",
		dataType: "json",
		data: {status:status, column:column, id:id},
		success: function(response) {
			console.log(response);
			if(response == '1') {
				$.notify("Setting saved successfully", "success");
			} else {
				$.notify("Setting not saved, try again", "error");
			}
			$('.saveButton').html('<i class="fa fa-save"></i> Save');
		},
		beforeSend: function() {
			
		},
		error: function (xhr, ajaxOptions, thrownError) {
			$('.saveButton').html('<i class="fa fa-save"></i> Save');
			$.notify('Setting not saved, try again', "error");
		},
		timeout: 6000,
		complete: function() {
			
		}
	});
}

function removeNoteSuccess() {
	$('#payment-notes-details li').each(function() {
		$(this).removeClass('text-success');
	});
}