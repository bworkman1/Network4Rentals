$(function() {	
	$(".datepicker" ).datepicker();
	var baseAjax = 'http://localhost/n4r/renters/add-landlord/';
	
	$('#add-landlord-form').submit(function(event) {
		event.preventDefault();
		
		var formData = new FormData($(this)[0]);
		
		var fileSelect = document.getElementById('attachment');
		var files = fileSelect.files;
		var file = files[0];
		formData.append('file', file);
		
		$.ajax({
			url: baseAjax+"submitLandlord",
			data: formData,
			cache: false,
			dataType: 'json',
			contentType: false,
			processData: false,
			type: 'POST',
			timeout: 10000,
			success: function(results) {
				/* LEFT OFF HERE NOW I NEED TO SORT THE RESULTS AND IF ERROR - SUCCESS - FOUNT */
				console.log(results);
				console.log('made it');
			},
			error: function(error) { 
				console.log(error)
			},
			beforeSend: function() {
				$('#submit-landlord').html('<i class="fa fa-cog fa-spin"></i> Adding Landlord').attr('disabled', true);
			},
			complete: function() {
				$('#submit-landlord').html('Add Landlord').attr('disabled', false);
			}
		});
	});
	
	function daysInMonth(type) {
		if(type=='in') {
			var year = $('#movein_year').val();
			var month = $('#movein_month').val();
			var target = '#movein_day';
			var selected = $('#movein_day').val();
		} else {
			var year = $('#moveout_year').val();
			var month = $('#moveout_month').val();
			var target = '#moveout_day';
			var selected = $('#moveout_day').val();
		}
		var days = new Date(year, month, 0).getDate();
		var options = '<option>- Day -</option>';
		for(var i=0;i<days;i++) {
			if(selected == (i+1)) {
				options += '<option selected>'+(i+1)+'</option>';
			} else {
				options += '<option>'+(i+1)+'</option>';
			}
		}
		$(target).html(options);
	}
	
	$('.moveIn').change(function(){
		daysInMonth('in');
	});
	
	$('.moveOut').change(function(){
		daysInMonth('out');
	});
	
	$('#searchLandlord').click(function() {
		var name = $('#landlordSearchBy').val();
		$('#searchResults').html('');
		$('#groupData').html('');
		if(name.length<3) {
			$(this).parent().parent().addClass('has-error');
			$('#searhError').html('<div class="text-danger">Search must be atleast 5 characters long</div>');
		} else {
			$(this).parent().parent().removeClass('has-error');
			$('#searhError').html('');
			var earl = baseAjax+"ajax_landlord_search";

			$.ajax({
				url: earl,
				data: {'name':name},
                cache: false,
                dataType: 'json',
                type: 'POST',
                timeout: 10000,
                success: function(data) {
					if(data) {
						addSearchData(data);
					} else {
						$('#searhError').html('<div class="text-danger">No results found, try searhing by first name or just last.</div>');
					}
                },
                error: function() { 
					$('#searhError').html('<div class="text-danger">There was an error processing your request, try again.</div>');
				},
				beforesend: function() {
					$('#searchLandlord').html('<i class="fa fa-cog fa-spin"></i>');
				},
				complete: function() {
					$('#searchLandlord').html('<i class="fa fa-search"></i>');
				}
			});
		}
	});
	
	$(document).on('click', '.selectGroupMgr', function() {
		var id = $(this).data('id');
		var groupId = $(this).data('groupid');
		fillInLandlordDetails(id, groupId);
		$('#groupData').html('');
	});
	
	$(document).on('click', '.imp-notify li a', function() {
		var id = $(this).data('id');
		var name = $(this).data('name');
		
		$('#searchResults').html('');
		$.ajax({
			url: baseAjax+"ajaxGroupSearch",
			data: {'id':id},
			cache: false,
			dataType: 'json',
			type: 'POST',
			timeout: 10000,
			success: function(data) {
				if(data !='') {
					addGroupSelections(data, name);
					alertify.success("Select which manager you should connect to");
				} else {
					fillInLandlordDetails(id, '');
				}
			},
			error: function() { 
				$('#searhError').html('<div class="text-danger">There was an error processing your request, try again.</div>');
			},
		});
	});
	
	function addGroupSelections(data, name) {
		
		var out = '<div class="widget">';
			out += '<div class="widget-header">';
				out += '<div class="title">';
					out += name+'\'s Managers';
					out += '<span class="mini-title">Select One</span>';
				out += '</div>';
				out += '<span class="tools">';
					out += '<i class="fa fa-users"></i>';
				out += '</span>';
			out += '</div>';
			out += '<div class="widget-body">';
				out += '<p>'+name+' has '+(data.length-1)+' managers select the one that fits you.</p><hr>';
				out += '<div class="row">';
					$.each(data, function(key, data) {
						out += '<div class="col-md-6 col-sm-6 col-xs-6">';
							if(data.admin != 'admin') {
								out += '<div class="groupSelection">';
									out += '<img src="http://localhost/n4r/'+data.image+'" class="img-responsive pull-left img-icon" alt="'+data.name+'">';
									out += '<button  data-id="'+data.id+'" data-groupId="'+data.group_id+'" class="btn selectGroupMgr btn-warning pull-right"><i class="fa fa-plus"></i> Add</button>';
									out += '<p>'+data.bName+'</p>';
									out += '<p class="text-primary"><small>Manager: '+data.name+'</small></p>';
									
							} else {
								if(data.connect == 'y') {
									out += '<div class="groupSelectionInvalid">';
								
									out += '<img src="http://localhost/n4r/'+data.image+'" class="img-responsive pull-left img-icon" alt="'+data.name+'">';
									out += '<br><p class="text-primary">Landlord: '+data.name+'</small></p>';
									out += '<button data-id="'+data.id+'" data-groupId="'+data.group_id+'" class="btn btn-warning selectGroupMgr pull-right"><i class="fa fa-plus"></i> Add</button>';
								}
							}
							
							out += '</div>';
						out += '</div>';
					});
				out += '</div>';
			out += '</div>';
		out += '</div>';
		
		$('#groupData').html(out);
	}
	
	
	
	function fillInLandlordDetails(id, groupId) {
		
		$('.hidden').removeClass('hidden');
		$.ajax({
			url: baseAjax+"ajaxGetLandlordById",
			data: {'id':id, 'groupId':groupId},
			cache: false,
			dataType: 'json',
			type: 'POST',
			timeout: 10000,
			success: function(data) {
				$('#bName').val(data.bName).attr('readonly', true);
				$('#lName').val(data.name).attr('readonly', true);
				$('#city').val(data.city).attr('readonly', true);
				$('#state').val(data.state).attr('readonly', true);
				$('#eMail').val(data.email).attr('readonly', true);
				$('#address').attr('readonly', true);
				$('#phone').attr('readonly', true);
				$('#zip').val(data.zip).attr('readonly', true);
				$('#landlord-id').val(id);
				$('#group-id').val(groupId);
				alertify.success("Great, now check over the details and add your rental info");
			},
			error: function() { 
				
			},
			beforesend: function() {
				
			},
			complete: function() {
				
			}
		});
	}
	
	$('#noLandlord').click(function () {
		$('.hidden').removeClass('hidden');
		alertify.success("Enter your landlord details manually");
		$('html, body').animate({
			scrollTop: $("#landlordDetailsForm").offset().top
		}, 1000);
	});
	
	function addSearchData(data) {
		var out = '<ul class="imp-notify">';
		$.each(data, function(key, data) {
				out += '<li class="clearfix">';
					out += '<a href="#" data-id="'+data.id+'" data-name="'+data.name+'">';
						out += '<div class="icon">';
							out += '<img src="http://localhost/n4r/'+data.image+'" alt="'+data.name+'">';
						out += '</div>';
						out += '<div class="details">';
							out += '<strong class="text-primary">'+data.name+' <span>'+data.city+' '+data.state+'</span></strong>';
							out += '<p>'+data.bName+'</p>';
						out += '</div>';
					out += '</a>';
				out += '</li>';

		});
		out += '</ul>';
		
		$('#searchResults').html(out);
	}
	
});