$(function() {
	
	if ($(window).width() < 700) {
		setTimeout(mobilizeCalendar, 500);
	}
	
	function mobilizeCalendar() {
		var calHead = $('.fc-header-title').html();
		$('#calendar table.fc-header tbody').prepend('<tr><td colspan="3" class="text-center">'+calHead+'</td></tr>');
		$('.fc-header-title').html('');
	}
	
	var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();
	
	$('.dateMask').mask('99/99/9999');
	$('.timeMask').mask('99:99');
	
	var currentStart;
	var currentEnd;
	
	var calendar = $('#calendar').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		selectable: true,
		selectHelper: true,
		select: function(start, end, allDay) {			
			var d = new Date(start);
			var startDate = ("0" + d.getDate()).slice(-2);			
			var startMonth = ("0" + (d.getMonth() + 1)).slice(-2);			
			var startYear = d.getFullYear();
		
			var startHour = ("0" + (d.getHours())).slice(-2);	
			var startMin = ("0" + (d.getMinutes())).slice(-2);
			if(d.getHours()>=12) {
				$('#startAm').val('pm');
				if(d.getHours()>12) {
					startHour -= 12;
					startHour = ("0" + startHour).slice(-2);
				}
			}
			/* --------------------------------------*/
			
			var d = new Date(end);
			var endDate = ("0" + d.getDate()).slice(-2);			
			var endMonth = ("0" + (d.getMonth() + 1)).slice(-2);			
			var endYear = d.getFullYear();
			
			var endHour = ("0" + (d.getHours())).slice(-2);	
			var endMin = ("0" + (d.getMinutes())).slice(-2);	
			
			if(d.getHours()>=12) {
				$('#endAm').val('pm');
				if(d.getHours()>12) {
					endHour -= 12;
				endHour = ("0" + endHour).slice(-2);
				}
			}
			
			$('#createStartTask').val(startMonth+'/'+startDate+'/'+startYear);
			$('#createEndTask').val(endMonth+'/'+endDate+'/'+endYear);
			
			$('#createTaskStartTime').val(startHour+':'+startMin);
			$('#createTaskEndTime').val(endHour+':'+endMin);
			
			var popup = $('#addEvent').modal('show');
			if(allDay) {
				$('#eventAllDay').attr('checked', true);
			} else {
				$('#eventAllDay').attr('checked', false);
			}
			calendar.fullCalendar('unselect');
		},
		editable: true,
		
		eventDrop: function(event, delta, revertFunc) {
			$.ajax({
				url: '//network4rentals.com/network/ajax_contractors/updateDroppedEvent',
				dataType: 'json',
				type: 'post',
				data: {'id':event.id, 'start':event.start, 'end':event.end, 'allDay': event.allDay},
				cache: true,
				error: function() {
					alertify.error('There was a problem updating this event, try refreshing your page and trying it again.');
				},
				dataType: 'json',
				success: function(data) {
					console.log(data);
					if(typeof data.success != 'undefined') {
						alertify.success('Event successfully moved');
					} else {
						alertify.error('There was a problem saving the event, try refreshing your page.');
					}
				},
			});
		},
		eventResize: function(event, delta, revertFunc) {
			$.ajax({
				url: '//network4rentals.com/network/ajax_contractors/updateDroppedEvent',
				dataType: 'json',
				type: 'post',
				cache: true,
				data: {'id':event.id, 'start':event.start, 'end':event.end, 'allDay': event.allDay},
				error: function() {
					alert('There was a problem updating this event, try refreshing your page and trying it again.');
				},
				dataType: 'json',
				success: function(data) {
					if(typeof data.success != 'undefined') {
						alertify.success('Event successfully moved');
					} else {
						alertify.error('There was a problem saving the event, try refreshing your page.');
					}
				},
			});
		},
		eventClick: function(calEvent, jsEvent, view) {
			$('#eventLink').remove();
			console.log(calEvent);
			$('#editEventTitle').val(calEvent.title);
			$('#deleteEvent').attr('data-id', calEvent.id);
			$('#editEvent').attr('data-id',calEvent.id);
			calEvent.end = calEvent.end || calEvent.start;
			
			var d = new Date(calEvent.start);
			var startDate = ("0" + d.getDate()).slice(-2);			
			var startMonth = ("0" + (d.getMonth() + 1)).slice(-2);			
			var startYear = d.getFullYear();
			
			var startHour = ("0" + (d.getHours())).slice(-2);	
			var startMin = ("0" + (d.getMinutes())).slice(-2);

			
			if(d.getHours()>=12) {
				$('#editStartAm').val('pm');
				if(d.getHours()>12) {
					startHour -= 12;
					startHour = ("0" + startHour).slice(-2);
				}
			}
		
	
			if(calEvent.end) {
				var d = new Date(calEvent.end);
				var endDate = ("0" + d.getDate()).slice(-2);			
				var endMonth = ("0" + (d.getMonth() + 1)).slice(-2);			
				var endYear = d.getFullYear();
				
				var endHour = ("0" + (d.getHours())).slice(-2);	
				var endMin = ("0" + (d.getMinutes())).slice(-2);	
				
				if(d.getHours()>=12) {
					$('#editEndAm').val('pm');
					if(d.getHours()>12) {
						endHour -= 12;
					endHour = ("0" + endHour).slice(-2);
					}
				}
				
				$('#editCreateEndTask').val(endMonth+'/'+endDate+'/'+endYear);
				$('#editCreateTaskEndTime').val(endHour+':'+endMin);
			}
			
			$('#editCreateStartTask').val(startMonth+'/'+startDate+'/'+startYear);
			$('#editCreateTaskStartTime').val(startHour+':'+startMin);
			
			if(calEvent.link) {
				$('#deleteEvent').after('<a id="eventLink" href="'+calEvent.link+'" class="btn btn-primary">View Request</a>');
			}
			
			$('#eventOptions').modal();
		},
		
		viewRender: function(view, elem) {
			currentStart = view.start;
			currentEnd = view.end;
		},
		events: {
			
			url: '//network4rentals.com/network/ajax_contractors/getCalendarEvents',
			dataType: 'json',
			type: 'post',
			data: {'start':currentStart, 'end':currentEnd},
			cache: true,
			error: function() {
				alertify.error('There was a problem loading your events, try refreshing your page.');
			},
			dataType: 'json',
			success: function(data) {
				console.log(data);
				return data;
			},
			
		}
		
	});
       
	$(document).on('mouseover', '.fc-event', function() {
		$(this).find('.fa-event-inner').append('<span class="fc-event-delete"><i class="fa fa-times"></i></span>');
	});
	
	$('#deleteEvent').click(function() {
		var id = $(this).attr('data-id');
		var elem = $(this);
		console.log(id);
		$.ajax({
			url: '//network4rentals.com/network/ajax_contractors/deleteCalendarEvent',
			dataType: 'json',
			type: 'post',
			data: {'id':id},
			dataType: 'json',
			error: function() {
				alertify.error('There was a problem deleting your events, try refreshing your page and trying again.');
			},
			success: function(data) {
				var allDay = (allDay === "true");					
				if(typeof data.error =='undefined') {
					$('#calendar').fullCalendar('removeEvents', id);
					$('#eventOptions').modal('hide');
					elem.attr('data-id', '');
					alertify.success('Event Has Been Deleted');
				} else {
					alertify.error(data.error);
				}
			},
			beforeSend: function() {
				elem.html('<i class="fa fa-cog fa-spin"></i> Deleting').attr('disabled', true);
			},
			complete: function() {
				elem.html('<i class="fa fa-times"></i> Delete').attr('disabled', false);
			}
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
		if(validDate(startDate+' '+startTime) && validDate(endDate+' '+endTime)) {
			if (title) {
				$.ajax({
					url: '//network4rentals.com/network/ajax_contractors/addCalendarEvent',
					dataType: 'json',
					type: 'post',
					data: {'title':title, 'startDate':startDate, 'startTime':startTime, 'endDate':endDate,'endTime':endTime, 'allDay':allDay, 'employee_id':employeeId,'startAm':startAm, 'endAm':endAm},
					dataType: 'json',
					error: function() {
						alertify.error('There was a problem loading your events, try refreshing your page.');
					},
					success: function(data) {
						if(allDay == 'true') {
							allDay = true;
						} else {
							allDay = false;
						}
						
						if(typeof data.error =='undefined') {
							calendar.fullCalendar( 'refetchEvents' );
							var popup = $('#addEvent').modal('hide');
							$('#eventTitle').val('');
							$('#eventStart').val('');
							$('#eventEnd').val('');
							$('#eventAllDay').val('');
							alertify.success('Event added to your calendar');
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
	
	$('#editEvent').click(function(event) {
		event.preventDefault();
		var elem = $(this);
		var id = $(this).attr('data-id');
		var title = $('#editEventTitle').val();
		var employeeId = $('#editEmployeeId').val();
		var editStartAm = $('#editStartAm').val();
		var editEndAm = $('#editEndAm').val();
		if($('#editEventAllDay').is(':checked')) {
			var allDay = 'true';
		} else {
			var allDay = 'false';
		}
		
		var startDate = $('#editCreateStartTask').val();
		var startTime = $('#editCreateTaskStartTime').val();
		
		var endDate = $('#editCreateEndTask').val();
		var endTime = $('#editCreateTaskEndTime').val();
		if (title) {
			$.ajax({
				url: '//network4rentals.com/network/ajax_contractors/updateCalendarEvent',
				dataType: 'json',
				type: 'post',
				data: {'id':id, 'title':title, 'startDate':startDate, 'startTime':startTime, 'endDate':endDate,'endTime':endTime, 'allDay':allDay, 'employee_id':employeeId, 'editStartAm':editStartAm, 'editEndAm':editEndAm},
				dataType: 'json',
				error: function() {
					alertify.error('There was a problem loading your events, try refreshing your page.');
				},
				success: function(data) {
					console.log(data);
					var allDay = (allDay === "true");					
					if(typeof data.error =='undefined') {
								
						
						var popup = $('#addEvent').modal('hide');
						$('#eventTitle').val('');
						$('#eventStart').val('');
						$('#eventEnd').val('');
						$('#eventAllDay').val('');
						alertify.success("Event Has Been Changed");
						$('#eventOptions').modal('hide');
						calendar.fullCalendar( 'refetchEvents' );
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