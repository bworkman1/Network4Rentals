$(function() {
	var $summernote = $('.summernote');
	$summernote.summernote({
		height: 400,
		onImageUpload: function(files, editor, welEditable) {
			if(files[0].size>2000000) {
				$.notify('Image is too large, you need to shrink the image', "error");
			} else {
				sendFile(files[0], editor, welEditable);
			}
		}
	});

	$('#keyword-area input').tagsinput({
		maxTags: 10
	});
	
	if($('#setKeywords').html() != '') {
		var keywords_array = $('#setKeywords').html().split(',');
		for(var i=0;i<keywords_array.length;i++) {
			$('#keywords').tagsinput('add', keywords_array[i]);
		}
	}
	
	function sendFile(file, editor, welEditable) {
		data = new FormData();
		data.append("file", file);
		$('#pageFeedback').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Image');
		$.ajax({
			data: data,
			type: "POST",
			dataType: "json",
			url: "//network4rentals.com/network/ajax_contractors/summernote_image_uploader/",
			cache: false,
			xhr: function() {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
				return myXhr;
			},
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if(typeof data.success == "undefined") {
					$.notify(data.error, "error");
					$('#pageFeedback').html('');
				} else {
					$('.summernote').summernote('editor.insertImage', data.success);
				}
			}
		});
	}
	

	
});