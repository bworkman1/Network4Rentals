$(function() {
	var baseUrl = '//network4rentals.com/network/ajax_landlords/'
	
	function setImageValues(imgName) {
		var counter = 0;
		$('.imgInputName').each(function(count) {
			counter = count;
			if($(this).val() == '') {
				$(this).val(imgName);
				return false;
			}
		});
		counter = counter+1;
		$('#imgPreview'+counter).html('<img src="../'+imgName+'" class="img-responsive" alt="Added Image"><div class="checkbox"><label><input type="radio" name="featured_image" value="'+counter+'"> Featured?</label></div>');

		if(counter == 5) {
			$('#imageBtn').addClass('fade');
		}
	}
	
	function removeImage() {
		var counter = 0;
		$('.imgInputName').each(function(count) {
			counter = count;
			if($(this).val() == '') {
				return false;
			}
		});
		
		counter = counter-1;
		$('#imgPreview'+counter).html('');
		$('.imgInputName:nth-child('+counter).val('');
		
		
	}
	
	$(".preview").mouseenter(function() {
		$(this).prepend('<span class="removeImage"><i class="fa fa-times"></i></span>');
	});
	$(".preview").mouseleave(function() {
		$(this).find('.removeImage').remove();
	});
	
	$('.preview').on('click', '.removeImage', function() {
		var id = $(this).parent().attr('id');
		var lastChar = id.substr(id.length - 1); 
		$('input[name=image' + lastChar + ']').val('');
		$('#imgPreview'+lastChar).html('');
		$('#imageBtn').addClass('in');
	});
	
	$('#imageBtn').click(function() {
		 $('input[type=file]').click();
	});
	
	$('#image').change(function() {
		var defaultBtn = $('#imageBtn').html();
		var image = $('#image')[0].files[0];
		var data = new FormData();
		data.append( 'image', image );
		$.ajax(baseUrl+'image-uploader/edit', {
			dataType: "json",
			data: data,
			type: 'POST',
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if(data.status === 'success') {
					setImageValues(data.url);
				} else {
					alert(data.message);
				}
			},
			error: function(xhr) { // if error occured
				alert("Error occured.please try again");
				$('#imageBtn').html(defaultBtn).attr('disabled', false);
			},
			beforeSend: function() {
				$('#imageBtn').html('<br><div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div><br><br>').attr('disabled', true);
			},
			complete: function() {
				$('#imageBtn').html(defaultBtn).attr('disabled', false);
				$('#image').val('');
			},
		});
	});
	
});