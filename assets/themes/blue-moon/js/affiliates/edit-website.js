$(function() {
	$('#website-media a').mouseover(function() {
		$(this).find('.deleteImage').css({'display':'block'});
	}).mouseout(function() { 
		$(this).find('.deleteImage').css({'display':'none'});
	});
	
	$('.deleteImage').click(function(event) {
		event.preventDefault();
		var elem = $(this);
		var type = $(this).data('type');
		$.ajax({
			url: 'https://network4rentals.com/network/affiliates/my-website/ajaxDeletImage/'+type,
			dataType: "json",
			success: function(data) {
				elem.parent().remove();
			},
			error: function(error) {
				console.log(error);
			},
			beforeSend: function() {
				elem.addClass('thinking').html('<i class="fa fa-cog fa-2x fa-spin"></i>');
			},
			complete: function() {
				elem.removeClass('thinking').html('<i class="fa fa-times fa-2x"></i>');
			}
		});
	});
	
	
});