$(function() {
	var base_url = 'https://network4rentals.com/network/';
	
	$('.topBarBackground').on('change', function() {
		var color = $(this).val();
		$('#top_bar').css({'background':'#'+color});
	});
	$('.topBarTextColor').on('change', function() {
		var color = $(this).val();
		$('#top_bar').css({'color':'#'+color});
	});
	$('.topBarSlogan').keyup(function() {
		var slogan = $(this).val();
		$('#top_bar .container .col-md-6:first-child').html(slogan);
	});
	
	$('.addPage').click(function() {
		var count = 0;
		$('#sortable li').each(function() {
			count++;
		});
		if(count<4) {
			$('.addNewPageForm').css({'display':'inline'});
			$('#addPage').modal('show');
		} else {
			$('.addNewPageForm').css({'display':'none'});
			$('.modal-body').html('You are only allowed to have 4 pages. You can edit or delete a page to make a new one.');
			$('#addPage').modal('show');
		}
	});
	
	$('.unique-page-name-check').focusout(function() {
		var unique_name = $(this).val();
		unique_name = convertToSlug(unique_name);
		$(this).val(unique_name);
		if(unique_name.length > 3) {
			$('.error-helper').html('');
			$.ajax('https://network4rentals.com/network/ajax/check_unique_url/'+unique_name, {
				dataType: "json",
				success: function(response) {
					var response = $.parseJSON(response);
					if(response == 1) {
						$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Is Already Taken, Try A Different One').fadeIn();
					}
				},
				timeout: 6000,
			});
		} else {
			$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn();
		}
	});
	
	$('.addNewPageForm').click(function() {
		var name = $('#pageName').val();
		if(name.length>2) {
			$('#addPage').modal('hide');
			$('#pageName').val('');
			$('.pageNameError').html('');
			$.ajax({
				url: "https://network4rentals.com/network/ajax_associations/add_page_website/", // Url to which the request is send
				type: "POST",
				data: {'name':name},
				success: function(response)
				{	
					if(response>5) {
						$('#sortable').append('<li><i class="fa fa-arrows-v toolTips" title="Reorder Me"></i> '+name+' <a href="'+base_url+'landlord-associations/edit-page/'+response+'"><i class="fa fa-gears pull-right toolTips" title="Edit Details"></i></a></li>');
						$.notify('Page created successfully', "success");
					} else {
						$.notify(response, "error");
					}
				},
				error: function (x, e) {
					$.notify(e, "error");
				},
				complete: function(data) {
					
				}
			});
		} else {
			$('.pageNameError').html('Name must be at least 3 characters long');
		}
	});
	 
	$("#sortable").sortable({
		update: function( event, ui ) {
			
			var object = {};
			$('#sortable li').each(function(index) {
				var id = $(this).attr('data-stack').toString();
				object[id] = index;
			});
			var sendData = JSON.stringify(object);
			console.log(sendData);
			$.post('https://network4rentals.com/network/ajax_associations/update_page_stack', { jsonData: sendData}, function(response){
				
			});
		}
	});
	
	var pageCount = 0;
	$('#sortable li').each(function() {
		pageCount++;
	});
	if(pageCount>4) {
		$('.addPage').remove();
	}
	
    $( "#sortable" ).disableSelection();
	
	
	
	$('#ImageBrowse').on('change',(function(e) {
        e.preventDefault();
		$('.progress').addClass('in');
		$('.status').addClass('in');
        var formData = new FormData('#imageUploadForm');
		$('.uploaderProgress').css({'display':'block'});
		var file = this.files[0];
		var name = file.name;
		var size = file.size;
		var type = file.type;
		
		
		$.ajax({
			url: "https://network4rentals.com/network/ajax/landlord_assocation_page_upload/", // Url to which the request is send
			type: "POST",             // Type of request to be send, called as method
			xhr: function () {
			myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
				}
				return myXhr;
			},
			data: {img:file}, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
			contentType: false,       // The content type used when sending data to the server.
			cache: false,             // To unable request pages to be cached
			processData:false,        // To send DOMDocument or non processed data file it is set to false
			success: function(response)   // A function to be called if request succeeds
			{	
				console.log(response);
				$('#loading').hide();
				$("#message").html(response);
			},
            error: function (x, e) {
                if (x.status === 0) {
                    console.log('You are offline!!\n Please Check Your Network. ' + x.reponseText);
                }
                else if (x.status == 404) {
                    console.log('Requested URL not found.');
                } else if (x.status == 500) {
                    console.log('Internel Server Error.');
                } else if (e == 'parsererror') {
                    console.log('Error.\nParsing JSON Request failed.');
                } else if (e == 'timeout') {
                    console.log('Request Time out.');
                } else {
                    console.log('Unknow Error.\n' + x.responseText);
                }
            },
			complete: function(data) {
				//$('.progress').removeClass('in');
				//$('.status').removeClass('in');
			}
		});
		
		/*
        $.ajax({
            type:'POST',
			xhr: function () {
			myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', progressHandlingFunction, false);
				}
				return myXhr;
			},
            url: 'https://network4rentals.com/network/ajax/landlord_assocation_page_upload/',
            data:formData,
            cache:false,
			dataType: 'json',
            contentType: false,
            processData: false,
            success:function(response){
				console.log(response);
				$('.uploaderProgress').css({'display':'none'});
				$('.progress-bar').css({'width':'0%'});
                if(response[0] == 'success') {
					var full_img_link = 'http://monogramhomes.net/management/uploads/'+response[1];
					$('.completed-upload').html('<div class="alert alert-success"><b><i class="glyphicon glyphicon-thumbs-up"></i> Success:</b> '+response[1]+'</div>');
					$('.upload-preview').html('<img src="'+full_img_link+'" class="img-responsive" />');
					$('iframe').each(function() {
						$('#editor', this.contentWindow.document||this.contentDocument).append('<img src="'+full_img_link+'" class="img-responsive" />');
					});
				} else {
					$('.completed-upload').html('<div class="alert alert-danger"><b><i class="glyphicon glyphicon-thumbs-down"></i> Error:</b> '+response[1]+'</div>');
				}
				$('#ImageBrowse').val('');
            },
            error: function (x, e) {
                if (x.status === 0) {
                    console.log('You are offline!!\n Please Check Your Network. ' + x.reponseText);
                }
                else if (x.status == 404) {
                    console.log('Requested URL not found.');
                } else if (x.status == 500) {
                    console.log('Internel Server Error.');
                } else if (e == 'parsererror') {
                    console.log('Error.\nParsing JSON Request failed.');
                } else if (e == 'timeout') {
                    console.log('Request Time out.');
                } else {
                    console.log('Unknow Error.\n' + x.responseText);
                }
            },
			complete: function(data) {
				//$('.progress').removeClass('in');
				//$('.status').removeClass('in');
			}
        });
		*/

    }));
	
	$('.attachment-img').bind('change', function() {
		var error = false;
		var size = this.files[0].size / 1048576;
		var ftype = this.files[0].type;
		var fName = this.files[0].name;
		
		
		if(size === 'undefined') {
			var error = false;
		}
		
		if(size > 5) {
			alert('The file you are trying to upload is too large, try shrinking the files size and try again.');
			$('.sendMsg').css({'display':'none'});	
			error = true;
		}
		switch(ftype) {
			case 'image/png':
			case 'image/gif':
			case 'image/jpeg':
			case 'image/pjpeg':
				break;
			default:
				alert('Invalid File Type, Please Upload A Valid File. ');
				error = true;
		}
		
		if(error == false) {
			
		} else {
			$(this).val('');	
		}

	});	
	
	$(".attachment-img").change(function(){
		readURL(this);
	});
	
	$('.deleteImageBkg').click(function() {
		var selections = '<select class="form-control public-background" name="background_select">';
		selections += '<option value="1">Default 1</option>';
		selections += '<option value="2">Default 2</option>';
		selections += '<option value="3">Default 3</option>';
		selections += '<option value="na">Upload Your Own</option>';
		selections += '</select>';
		$('.deleteImgPlace').html(selections);
	});
	
	$(document.body).on('change', '.public-background', function() {
		var background = $(this).val();
		if(background==1) {
			$('.default1').css({'display':'block'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==2) {
			$('.default2').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==3) {
			$('.default3').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else {
			$('.default4').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
		}
		$('.selectedBkg').remove();
	});
	
});

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('.thumbPreview').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	}
}

function convertToSlug(Text)
{
	return Text
		.toLowerCase()
		.replace(/ /g,'-')
		.replace(/[^\w-]+/g,'')
		;
}

function progressHandlingFunction(e) {
	if (e.lengthComputable) {
		var s = parseInt((e.loaded / e.total) * 100);
		$(".progress-bar").width(s + "%");
		$(".status").text(s + "% Complete");
		if (s == 100) {
			
		}
	}
}