$(document).ready(function() {

	

	$('.showTenantsInfo').click(function() {
		$('.landlordsInfoBox').addClass('hideInfo');
		$('.showTenantsZip').addClass('hideInfo');
		$('.showLandlordsZip').addClass('hideInfo');
		$('.showInactiveLandlords').addClass('hideInfo');
		$('.showInactiveTenants').addClass('hideInfo');
		if($('.tenantsInfoBox').hasClass('hideInfo')) {
			$('.tenantsInfoBox').removeClass('hideInfo');
			$('.tenantsInfoBox').addClass('showInfo');
		} else {
			$('.tenantsInfoBox').removeClass('showInfo');
			$('.tenantsInfoBox').addClass('hideInfo');
		}
	});
	
	$('.showLandlordsInfo').click(function() {
		$('.tenantsInfoBox').addClass('hideInfo');
		$('.showTenantsZip').addClass('hideInfo');
		$('.showLandlordsZip').addClass('hideInfo');
		$('.showInactiveLandlords').addClass('hideInfo');
		$('.showInactiveTenants').addClass('hideInfo');
		if($('.landlordsInfoBox').hasClass('hideInfo')) {
			$('.landlordsInfoBox').removeClass('hideInfo');
			$('.landlordsInfoBox').addClass('showInfo');
		} else {
			$('.landlordsInfoBox').removeClass('showInfo');
			$('.landlordsInfoBox').addClass('hideInfo');
		}
	});
	
	$('.showTenantsZipBtn').click(function() {
		$('.landlordsInfoBox').addClass('hideInfo');
		$('.tenantsInfoBox').addClass('hideInfo');
		$('.showLandlordsZip').addClass('hideInfo');
		$('.showInactiveLandlords').addClass('hideInfo');
		$('.showInactiveTenants').addClass('hideInfo');
		if($('.showTenantsZip').hasClass('hideInfo')) {
			$('.showTenantsZip').removeClass('hideInfo');
			$('.showTenantsZip').addClass('showInfo');
		} else {
			$('.showTenantsZip').removeClass('showInfo');
			$('.showTenantsZip').addClass('hideInfo');
		}
	});
	
	$('.showLandlordsZipBtn').click(function() {
		$('.landlordsInfoBox').addClass('hideInfo');
		$('.tenantsInfoBox').addClass('hideInfo');
		$('.showTenantsZip').addClass('hideInfo');
		$('.showInactiveLandlords').addClass('hideInfo');
		$('.showInactiveTenants').addClass('hideInfo');
		if($('.showLandlordsZip').hasClass('hideInfo')) {
			$('.showLandlordsZip').removeClass('hideInfo');
			$('.showLandlordsZip').addClass('showInfo');
		} else {
			$('.showLandlordsZip').removeClass('showInfo');
			$('.showLandlordsZip').addClass('hideInfo');
		}
	});	
	
	$('.rogue-landlords').click(function() {
		$('.landlordsInfoBox').addClass('hideInfo');
		$('.tenantsInfoBox').addClass('hideInfo');
		$('.showTenantsZip').addClass('hideInfo');
		$('.showInactiveTenants').addClass('hideInfo');
		$('.showLandlordsZip').addClass('hideInfo');
		if($('.showInactiveLandlords').hasClass('hideInfo')) {
			$('.showInactiveLandlords').removeClass('hideInfo');
			$('.showInactiveLandlords').addClass('showInfo');
		} else {
			$('.showInactiveLandlords').removeClass('showInfo');
			$('.showInactiveLandlords').addClass('hideInfo');
		}
	});
	
	$('.rogue-tenants').click(function() {
		$('.landlordsInfoBox').addClass('hideInfo');
		$('.tenantsInfoBox').addClass('hideInfo');
		$('.showTenantsZip').addClass('hideInfo');
		$('.showLandlordsZip').addClass('hideInfo');
		$('.showInactiveLandlords').addClass('hideInfo');
		if($('.showInactiveTenants').hasClass('hideInfo')) {
			$('.showInactiveTenants').removeClass('hideInfo');
			$('.showInactiveTenants').addClass('showInfo');
		} else {
			$('.showInactiveTenants').removeClass('showInfo');
			$('.showInactiveTenants').addClass('hideInfo');
		}
	});
	
	$('.infobox').click(function() {
		var type = $(this).data('type');
		var date = $(this).data('date');
		if(type=='tenants-date') {
			var sign_up_date = $(this).find('.sign-up-date').html();
			$('#myModal .modal-title').html('<i class="fa fa-calendar text-primary"></i> Tenants That Signed Up On - '+sign_up_date);
			ajax_call(type, date);	
		} 
		if(type=='landlords-date') {
			var sign_up_date = $(this).find('.sign-up-date').html();
			$('#myModal .modal-title').html('<i class="fa fa-calendar text-primary"></i> Landlords That Signed Up On - '+sign_up_date);
			ajax_call(type, date);
		}
	});
	
	$('.sendEmail').click(function() {
		var id = $(this).data('id');
		var name = $(this).data('name');
		var email = $(this).data('email');
		var type = $(this).data('type');
		$('.emailType').html(type);
		$('.emailName').html(name);
		$('.emailEmail').html(email);
		
		$('input[name="email_id"]').val(id);
		$('input[name="email_type"]').val(type);
		$('input[name="email_email"]').val(email);
	});
	
	$("#input").cleditor({
		width: 850, // width not including margins, borders or padding
		height: 250, // height not including margins, borders or padding
	});

});


function ajax_call(type, date) {
	if(type=='tenants-date') {
		var call_function = 'tenants_signed_up_on';
		var type = 'renters';
		var call = 'https://network4rentals.com/network/ajax/'+call_function+'/'+date+'/'+type;
	}
	if(type=='landlords-date') {
		var call_function = 'landlords_signed_up_on';
		var type = 'landlords';
		var call = 'https://network4rentals.com/network/ajax/'+call_function+'/'+date+'/'+type;
	}
	console.log(call);
	$.ajax(call, {
		dataType: "json",
		success: function(response) {
			var response = $.parseJSON(response);
			var data = '';
			if(response.length>0) {
				data += '<div class="row border-bottom">';
				data += '<div class="col-sm-1">';
				data += '<b>Id:</b>';
				data += '</div>';
				data += '<div class="col-sm-3">';
				data += '<b>Name:</b>'
				data += '</div>';
				data += '<div class="col-sm-4">';
				data += '<b>Email:</b>';
				data += '</div>';
				data += '<div class="col-sm-1">';
				data += '<b>Phone:</b>';
				data += '</div>';
				data += '<div class="col-sm-2 text-center">';
				data += '<b>Source:</b>';
				data += '</div>';
				data += '<div class="col-sm-1">';
				data += '<b>Browser:</b>';
				data += '</div>';
				data += '</div>';
				for(var i=0;i<response.length;i++) {
					data += '<div class="row border-bottom">';
					data += '<div class="col-sm-1">';
					data += response[i]['id'];
					data += '</div>';
					data += '<div class="col-sm-3">';
					data += response[i]['name'];
					data += '</div>';
					data += '<div class="col-sm-4">';
					data += response[i]['email'];
					data += '</div>';
					data += '<div class="col-sm-1">';
					data += response[i]['phone'];
					data += '</div>';
					data += '<div class="col-sm-2 text-center">';
					data += response[i]['hear'];
					data += '</div>';
					data += '<div class="col-sm-1 text-center">';
					data += '<button class="tips btn btn-xs btn-primary" title="'+response[i]['browser_info']+'"><i class="fa fa-question"></i></button>';
					data += '</div>';
					data += '</div>';
				}
			}
			$('#myModal .modal-body').html(data);
		},
		error: function(request, errorType, errorMessage) {
			$('.thinking').html('No Landlord Found').fadeIn();
		},
		timeout: 6000,
		beforeSend: function() {
			$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn();
		},
		complete: function() {
			$('.thinking').html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut();
		}
	}); //end ajax call
}