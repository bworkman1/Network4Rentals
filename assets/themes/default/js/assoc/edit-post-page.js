$(function() {
	var $summernote = $('.summernote');
	$summernote.summernote({
            height: 536,
            onImageUpload: function(files, editor, welEditable) {
				if(files[0].size>2000000) {
					$.notify('Image is too large, you need to shrink the image', "error");
				} else {
					sendFile(files[0], editor, welEditable);
				}
            }
        });
		
        function sendFile(file, editor, welEditable) {
            data = new FormData();
            data.append("file", file);
			$('#pageFeedback').html('<i class="fa fa-circle-o-notch fa-spin"></i> Loading Image');
            $.ajax({
                data: data,
                type: "POST",
				dataType: "json",
                url: "//network4rentals.com/network/ajax_associations/summernote_image_uploader/",
                cache: false,
				xhr: function() {
					var myXhr = $.ajaxSettings.xhr();
					if (myXhr.upload) myXhr.upload.addEventListener('progress',progressHandlingFunction, false);
					return myXhr;
				},
                contentType: false,
                processData: false,
                success: function(data) {
					if(typeof data.success == "undefined") {
						$.notify(data.error, "error");
						$('#pageFeedback').html('');
					} else {
						$('.summernote').summernote('editor.insertImage', data.success);
					}
                }
            });
        }
		
		function progressHandlingFunction(e){
			if(e.lengthComputable){
				var prog = Math.round((e.loaded/e.total)*100);
				$('.progress-bar').css({'width':prog+'%', 'display':'block'}).attr('aria-valuenow', prog).html(prog+'%'); 
				if (e.loaded == e.total) {
					$('.progress-bar').css({'width':'0%', 'display':'hidden'}).attr('aria-valuenow', 0).html('0%'); 
					$('#pageFeedback').html('');
				}
			}
		}
	
});