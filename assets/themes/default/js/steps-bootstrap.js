$(document).ready(function() {
	$('.stepProceed2').click(function() {
		var count = 0;
	});
	$('.stepProceed3').click(function() {
			$('#div2').removeClass('activestep');
			$('#div3').addClass('activestep');
	});
	$('.stepProceed4').click(function() {
	});	
});
function validateEmail(email) {