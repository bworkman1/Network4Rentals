$(function() {
	
	$('.viewNotes').click(function(e) {
		e.preventDefault();
		var payment_id = $(this).data('payment');
		var altid = $('#landlordName').data('altid');
		var elem = $(this);
		var landlordName = $('#landlordName').data('landlordname');
		$(this).html('<i class="fa fa-refresh fa-spin"></i> Loading');
		if(payment_id>0) {
			$.ajax({
				url: 'https://network4rentals.com/network/ajax_renters/get_payment_notes/', 
				dataType: "json",
				cache: false,
				type: "post",
				data: {id:payment_id},
				success: function(response) {
					var notes = '';
					for(var i=0;i<response.length;i++) {
						
						if(response[i].sent_by != 'landlord') {
							notes += '<li><u><b class="text-warning">Me:</b> <small>'+response[i].ts+'</small></u><br>';
								notes += response[i].note;
								if(response[i].attachment !== '') {
									notes += '<br><a href="https://network4rentals.com/network/message-uploads/test/'+response[i].attachment+'" target="_blank"><i class="fa fa-paper-clip"></i> '+response[i].attachment+'</a>';
								}
							notes += '<hr></li>';
						} else {
							notes += '<li class="text-right"><b class="text-primary"><u>'+landlordName+':</b> <small>'+response[i].ts+'</small></u><br>';
								notes += response[i].note;
								if(response[i].attachment !== '') {
									notes += '<br><a href="https://network4rentals.com/network/message-uploads/test/'+response[i].attachment+'" target="_blank"><i class="fa fa-paper-clip"></i> '+response[i].attachment+'</a>';
								}
							notes += '<hr></li>';
						}
					}
					if(notes.length==0) {
						notes = '<li id="noNotes"><p>No Notes Left On This Payment Yet</p></li>'
					}
					$('#payment_id').html('<input type="hidden" name="payment_id" id="paymentId" value="'+payment_id+'"><input type="hidden" name="altid" id="altid" value="'+altid+'">');
					
					$('#payment-notes-details').html(notes);
					$('#payment-notes').delay(300).modal('show');
					elem.html('<i class="fa fa-file"></i> View Notes');
				}, 
				error: function(request, errorType, errorMessage) {
					$.notify("Error pulling the notes, try again", "error");
					elem.html('<i class="fa fa-file"></i> View Notes');
				},
				timeout: 6000,
				beforeSend: function() {
					
				},
				complete: function() {
					$(this).html('<i class="fa fa-file"></i> View Notes');
				}
			});
		} else {
			$.notify("Error pulling the notes, try again", "error");
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
	
	//-------------------
	
	$('form').on('submit', function(e) {
        e.preventDefault();
		$('.progress').slideDown();
        var formData = new FormData(this);
		formData.append("type", 'renter');
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
            url: 'https://network4rentals.com/network/ajax-renters/add_new_payment_note/renters/',
            data:formData,
            cache:false,
			dataType: 'json',
            contentType: false,
            processData: false,
            success:function(response){
				console.log(response);
				
				var notes = '';
				if(response.success !== undefined) {
					notes += '<li class="text-success"><b class="text-warning">You:</b> <small>'+response.time+'</small><br>';
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
	
	
	
	//-------------------
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

function removeNoteSuccess() {
	$('#payment-notes-details li').each(function() {
		$(this).removeClass('text-success');
	});
}


