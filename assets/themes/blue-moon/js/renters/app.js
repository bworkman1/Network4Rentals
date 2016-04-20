$(function() {
	/*user settings*/
	$('#accept_sms').change(function() {
		if($(this).is(':checked') && $('#cellPhone').val() == '') {
			alertify.alert("You must add a cell phone number first");
			$(this).attr('checked', false);
		}
	});
	
	if($(".phone").length) {
		$('.phone').mask('(999) 999-9999',{autoclear: false});
	}
	

});