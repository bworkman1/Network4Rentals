$(function() {
	$('.price').maskMoney();
	$('.date').mask('99/99/9999');
	
	$('#expense_file').change(function() {
		var file = $(this).val();
		var ext = file.split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg,pdf']) == -1) {
			$(this).addClass('borderDanger');
			$(this).parent().effect('shake', 'slow', '2', '2');
			$(this).val('');
			$('#fileUploadError').addClass('text-danger').html('Invalid file type, file must be a jpg, png, gif, pdf');
		} else {
			$('#fileUploadError').removeClass('text-danger').html('Upload a copy of the reciet or picture of the expense. <small>jpg, png, gif, pdf</small>');
			$(this).removeClass('borderDanger');
		}
	});
	
	$('#searchProperties').keyup(function() { 
		if($(this).val() != '') {
			var search = $(this).val();
			if(search.length>2) {
				$.ajax({
					url: '//network4rentals.com/network/ajax_landlords/search_listings/', 
					data: {'search':search},
					type: 'post',
					dataType: 'json',
					success: function(response) {
						if(typeof response.error == 'undefined') {
							addSeachResults(response);
						} else {
							
						}
					},
					error: function(request, errorType, errorMessage) {
						console.log(request);
					},
					timeout: 6000,
					beforeSend: function() {
						$('#currentData').fadeOut();
						$('#searchedData').html('<div id="loader"><i class="fa fa-gear fa-fw fa-4x fa-spin text-primary"></i><h3 class="text-primary">Loading Results</h3></div>');
					},
					complete: function() {
					
					}
				});
			}
		} else {
			$('#pagination').addClass('in');
			$('#currentData').fadeIn();
			$('#searchedData').html('');
		}
	});
	
	
	$(document).on('click', '.toogleAd', function() {
		var el = $(this);
		var id = $(el).data('id');
		var state = $(el).data('state');
		$.ajax('//network4rentals.com/network/ajax/toogle_rental_listing/'+id+'/'+state, {
			
			success: function(response) {
				if(response == '1') {
					$(el).removeClass('btn-default').addClass('btn-success');
					$(el).html('Listing Showing');
					$(el).data( "state", '2');
				} else if(response == '2') {
					$(el).removeClass('btn-success').addClass('btn-default');
					$(el).html('Not Showing');
					$(el).data( "state", '1');
				} else {
					alert("Something Went Wrong, Try Again");
				}
			},
			error: function(request, errorType, errorMessage) {
				alert("Something Went Wrong, Try Again");
				alert(errorType); 
			},
			timeout: 6000,
			beforeSend: function() {
				$(el).html('<i class="fa fa-spinner fa-spin"></i> Updating...').fadeIn();
			},
			complete: function() {
				
			}
		});
		
		var show_renters = '<ul>';
		show_renters += '<li>4 Characters Min</li>';
		show_renters += '</ul>';
		$('#results').html(show_renters);
	});	
	
	$('#addExpense').on('hidden.bs.modal', function () {
		$('#addExpenseItem input, #addExpenseItem select').each(function() {
			$(this).val('');
			$(this).removeClass('borderDanger');
		});
	});
	
	$('.addNewExpense').click(function() {
		var id = $(this).data('id');
		
		$('#property_id').val(id);
	});
	
	$('#expenseType').focusout(function() {
		var val = $(this).val();
		if(val<1) {
			$(this).addClass('borderDanger');
			$(this).parent().effect('shake', 'slow');
		} else {
			$(this).removeClass('borderDanger');
		}
	});
	
	$('#expense_cost').focusout(function() {
		var val = $(this).val();
		if(val<1) {
			$(this).addClass('borderDanger');
			$(this).parent().effect('shake', 'slow');
		} else {
			$(this).removeClass('borderDanger');
		}
	});

	$('#expense_date').focusout(function() {
		var val = $(this).val();
		if(isDate(val)) {
			$(this).removeClass('borderDanger');
		} else {
			$(this).parent().effect('shake', 'slow');
			$(this).addClass('borderDanger');
		}
		
	});

	$('#submitExpense').click(function(event) {
		event.preventDefault();
		console.log('clicked');
		var error = false;
		$('#addExpenseItem input, #addExpenseItem select').each(function(index) {
			var id = $(this).attr('id');
			var input = false;
			if(id != 'expense_file') {
				if($(this).val() == '') {
					input = true;
				}
				
				if($(this).hasClass('borderDanger')) {
					input = true;
				}
				
				if(id == 'expenseType') {
					if($(this).val()==0) {
						input = true;
					}
				}
			} 
			
			if(input) {
				$(this).addClass('borderDanger');
				$(this).parent().effect('shake', 'slow');
				error = true;
			}		
		});
		if(error === false) {
			$('#addExpenseItem').submit();
		}
		
	});
	
	
	$(document).on('click', '.viewTheseRequest', function() {
		var property_id = $(this).data('id');
		$('body').append($('<form/>', {
			id: 'form',
			method: 'POST',
			action: 'https://network4rentals.com/network/landlords/search-requests'
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'address',
			value: property_id
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'start_date',
			value: ''
	   }));
	   	$('#form').append($('<input/>', {
			type: 'hidden',
			name: 'end_date',
			value: ''
	   }));
	   $('#form').append($('<input/>', {
			type: 'hidden',
			name: 'serviceType',
			value: ''
	   }));

	   $('#form').submit();

	   return false;
	});
	
});

function addSeachResults(data) {
	if(data.length>0) {
		$('#pagination').removeClass('in');
		$('#searchedData').html('');
		var item = '<div class="row">';
		for(var i=0;i<data.length;i++) {
			item += '<div class="col-md-4 col-sm-6">';
				item += '<div class="property-item text-center">';
					if(data[i].img_show != '') {
						item += '<div class="listing-image" style="background-image:url(https://network4rentals.com/network/'+data[i].img_show+')"></div>';
					} else {
						item += '<div class="listing-image" style="background-image:url(https://network4rentals.com/network/listing-images/comingSoon.jpg)"></div>';
					}
					item += '<h3>'+data[i].title+'</h3>';
					item += '<p>'+data[i].address+', '+data[i].city+' '+data[i].stateAbv+'</p>';
						item += '<div class="row">';
							item += '<div class="col-xs-6">';
								item += '<p>Beds: '+data[i].bedrooms+'</p>';
							item += '</div>';
							item += '<div class="col-xs-6">';
								item += '<p>Baths: '+data[i].bathrooms+'</p>';
							item += '</div>';
						item += '</div>';
						
						item += '<div class="row">';
							item += '<div class="col-xs-6">';
								item += '<button type="button" class="btn btn-primary dropdown-toggle btn-block" data-toggle="dropdown">';
										item += 'Options <span class="caret"></span>';
								item += '</button>';
								item += '<ul class="dropdown-menu" role="menu">';
									item += '<li>';
										item += '<a href="https://network4rentals.com/network/landlords/edit-listing/'+data[i].id+'"><i class="fa fa-edit"> Edit Listing</i></a>';
									item += '</li>';
									item += '<li>';
										item += '<a href="https://network4rentals.com/network/landlords/add-service-request"><i class="fa fa-plus-square-o"></i> Add Service Request</a>';
									item += '</li>';
									item += '<li>';
										item += '<a href="" class="viewTheseRequest" data-id="'+data[i].id+'"><i class="fa fa-wrench"></i> View Service Requests</a>';
									item += '</li>';
									item += '<li>';
										item += '<a href="#" data-toggle="modal" onclick="getPropertyItems('+data[i].id+');" class="addItemProperty" data-target="#addItems" data-id="'+data[i].id+'"><i class="fa fa-plus"></i> Add Item</a>';
									item += '</li>';
									if(data[i].active == 'y') {
										item += '<li><a href="https://network4rentals.com/network/listings/view-listing/'+data[i].id+'" target="_blank"><i class="fa fa-link"></i> View Listing</a></li>';
									}
									item += '<li class="divider"></li>';
									item += '<li>';
										item += '<a href="" data-toggle="modal" data-target="#deleteListing" class="deleteListing" data-listingid="'+data[i].id+'"><i class="fa fa-times"></i> Delete Listing</a>';
									item += '</li>';
								item += '</ul>';
							item += '</div>';
							item += '<div class="col-xs-6">';
							
							if(data[i].active != 'y') {
								item += '<button class="btn btn-default btn-block toogleAd" data-state="1" data-id="'+data[i].id+'">Inactive</button>';
							} else {
								item += '<button class="btn btn-success btn-block toogleAd" data-state="2" data-id="'+data[i].id+'">Active</button>';
							}
								
							item += '</div>';
						item += '</div>';
					item += '</div>';
				item += '</div>';	
				
				if ((i+1)%3 == 0) {
					item += '</div><div class="row">';
				}
			
		}	
		item += '</div>';
		$('#searchedData').append(item);
	} else {
		$('#searchedData').html('<div class="alert alert-danger text-left"><i class="fa fa-times"></i> No Results Found</div>');
	}
}

function isDate(txtDate) {
	var currVal = txtDate;
	if(currVal == '')
		return false;
  
	//Declare Regex  
	var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; 
	var dtArray = currVal.match(rxDatePattern); // is format OK?

	if (dtArray == null)
		return false;
 
	//Checks for mm/dd/yyyy format.
	dtMonth = dtArray[1];
	dtDay= dtArray[3];
	dtYear = dtArray[5];
	if (dtMonth < 1 || dtMonth > 12)
		return false;
	else if (dtDay < 1 || dtDay> 31)
		return false;
	else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
		return false;
	else if (dtMonth == 2) {
		var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
		if (dtDay> 29 || (dtDay ==29 && !isleap))
			return false;
		}
	return true;
}