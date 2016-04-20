$(function() {	
	$('.dateMask').mask('99/99/9999');
	$('.timeMask').mask('99:99');
	
	$('#fileNote').change(function () {
		var ext = this.value.match(/\.(.+)$/)[1];
		switch (ext) {
			case 'jpg':
			case 'jpeg':
			case 'png':
			case 'gif':
			case 'doc':
			case 'docx':
			case 'pdf':
				break;
			default:
				alert('This is not an allowed file type.');
				this.value = '';
		}
	});
	
	$('.money').blur(function() {	
		$('.money').toNumber();
		$('.money').formatCurrency({
			symbol: ''
		});
	});
	
	$('#saveEvent').click(function(event) {
		event.preventDefault();
		var elem = $(this);

		var title = $('#eventTitle').val();
		var employeeId = $('#employeeId').val();

		if($('#eventAllDay').is(':checked')) {
			var allDay = 'true';
		} else {
			var allDay = 'false';
		}
		var startDate = $('#createStartTask').val();
		var startTime = $('#createTaskStartTime').val();
		
		var endDate = $('#createEndTask').val();
		var endTime = $('#createTaskEndTime').val();
		
		var startAm = $('#startAm').val();
		var endAm = $('#endAm').val();
		var link = $('#link').val();
		var serviceId = $('#requestID').val();
		
		if(validDate(startDate+' '+startTime) && validDate(endDate+' '+endTime)) {
			if (title) {
				$.ajax({
					url: '//network4rentals.com/network/ajax_contractors/addCalendarEvent',
					dataType: 'json',
					type: 'post',
					data: {'title':title, 'startDate':startDate, 'startTime':startTime, 'endDate':endDate,'endTime':endTime, 'allDay':allDay, 'employee_id':employeeId,'startAm':startAm, 'endAm':endAm, 'link':link, 'service_id':serviceId},
					dataType: 'json',
					error: function() {
						alertify.error('There was a problem loading your events, try refreshing your page.');
					},
					success: function(data) {
						console.log(data);
						if(allDay == 'true') {
							allDay = true;
						} else {
							allDay = false;
						}
						
						if(typeof data.error =='undefined') {
							var popup = $('#addEvent').modal('hide');
							$('#eventTitle').val('');
							$('#eventStart').val('');
							$('#eventEnd').val('');
							$('#eventAllDay').val('');
							alertify.success('Event added to your calendar');
							window.location.href = 'http://network4rentals.com/network/contractor/my-calendar/';
						} else {
							alertify.error(data.error);
						}
					},
					beforeSend: function() {
						elem.html('<i class="fa fa-cog fa-spin"></i> Adding').attr('disabled', true);
					},
					complete: function() {
						elem.html('<i class="fa fa-save"></i> Save').attr('disabled', false);
					}
					
				});
					
				
			} else {
				alertify.error('Title is required');
			}
		} else {
			alertify.error('Invalid date');
		}
	});
	
	function validDate(text) {

		var date = Date.parse(text);

		if (isNaN(date)) {
			return false;
		}

		var comp = text.split('/');

		if (comp.length !== 3) {
			return false;
		}

		var m = parseInt(comp[0], 10);
		var d = parseInt(comp[1], 10);
		var y = parseInt(comp[2], 10);
		var date = new Date(y, m - 1, d);
		return (date.getFullYear() == y && date.getMonth() + 1 == m && date.getDate() == d);
	}
	
});

	



