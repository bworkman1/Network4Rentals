$(function() {
	$('table').each(function() {
		$(this).addClass('table table-striped');
	});
	
	$('.toolTips').tooltip();
	
	$('.forgotPassword').click(function() {
		$('#modal-1').removeClass('md-show');
	});	
	$('.loginForgotPass').click(function() {
		$('#modal-2').removeClass('md-show');
	});
	
	
	

	
	
	
	



});

