$(function() {
	$('.deleteUserBtn').click(function() {
		$('#deleteUserError').html('');
		var id = $(this).data('id');
		var name = $(this).data('name');
		var type = $(this).data('type');
		
		if(id!=''&&name!=''&&type!='') {
			$('.modal-title span').html('<b>'+name+'</b>');
			$('#user_id_delete').attr('value', id);
			$('#user_type_delete').attr('value', type);
		} else {
			$('#deleteUserError').html('Some of the data didn\'t transfer to this model, try again. If the problem presist contact support and they can delete it for you.');
		}
	});
});