$(function() {
	$("#datepicker" ).datepicker({
		onSelect: function(selected,evnt) {
			updateAppointmentSection(selected);
		}
	});
  
	// Appointments
	$( "ul.appointments li" ).click(function() {
		var evenetId = $(this).data('id');
		var complete;
		if($(this).hasClass('complete')) {
			complete = 'n';
			$(this).removeClass('complete');
		} else {
			complete = 'y';
			$(this).addClass('complete');
		}
		setAppointmentStatus(complete);
	});
	
});

function updateAppointmentSection(selected){
	console.log(selected);
}

function setAppointmentStatus(complete) {
	console.log(complete);
}