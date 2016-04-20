$(function() {
	
	var id = $('#service-request-details').attr('data-id');
	var data = contractor_suggestions(id);


	$('#suggested-contractors-scored').on('click', '.forward-sponsorship', function(event) {
		event.preventDefault();
		var email = $(this).data('email');
		$('.addedSuggestion').remove();
		$('.sponsorFeedback').remove();
		$('.forward-email-input').val(email);
		$('#suggested-contractors-scored li').removeClass('activated');
		$('#suggested-contractors-scored').after('<div class="addedSuggestion alert alert-success"><i class="fa fa-check pull-left fa-2x"></i> Email added below, you can add a note to the contractor if you like and when your done click the send button and the contractor will be notified.<br></div>').fadeIn;
		
		$(this).addClass('activated');
	});
	
	$('.forward-sponsorship').click(function(e) {
		e.preventDefault();
		var email = $(this).data('email');
		$('.addedSuggestion').remove();
		$('.sponsorFeedback').remove();
		$('.forward-email-input').val(email);
		$('#suggested-contractors-scored li').each(function() {
			$(this).removeClass('activated');
		});
		$('#forwardSponsorEmail').html('<div class="sponsorFeedback alert alert-success"><i class="fa fa-check pull-left fa-2x"></i> Email added below, you can add a note to the contractor if you like and when your done click the send button and the contractor will be notified.<br></div>').fadeIn;
	});
	
	$('#searchSponsored').keyup(function() {
		var search = $('#searchSponsored').val();
		if(search.length>2) {
			$.ajax('https://network4rentals.com/network/ajax/searchSponsoredContractors/'+search, {
				dataType: "json",
				success: function(response) {
					if(response.length>0) {
						$('#sponsored-results').html('<ul class="results"></ul>');
						for(var i=0;i<response.length;i++) {
							if(response[i]['email'] != 'brian@emf-websolutions.com') {
								if(response[i]['image'].length == 0) {
									response[i]['image'] = 'n4r_gif_ico.gif';
								}
								$('#sponsored-results .results').append('<li class="contractor-found" data-email="'+response[i]['email']+'"><img src="https://network4rentals.com/network/public-images/'+response[i]['image']+'" height="40px" width="40px">'+response[i]['bName']+' <span class="pull-right"><small>'+response[i]['city']+' '+response[i]['state']+'</small></span></li>');
							}
						}
					}
				},
				error: function(request, errorType, errorMessage) {
					$('.clickEvent').html('<div class="text-danger">Error Retrieving Info</div>').fadeIn();
				},
				timeout: 6000,
				beforeSend: function() {
					$('.clickEvent').html('<i class="fa fa-spinner fa-spin"></i> Retrieving Details... ').fadeIn();
				},
				complete: function() {
					$('.clickEvent').html('');
				}
			});
		}
	});
	

	
	$(document).on('click', '.contractor-found', function() {
		var email = $(this).data('email');
		$('#searchSponsored').val('');
		$('#sponsored-results').html('');
		$('.forward-email-input').val(email);
		$('.clickEvent').html('<div class="sponsorFeedback alert alert-success"><i class="fa fa-check pull-left fa-2x"></i> Email added below, you can add a note to the contractor if you like and when your done click the send button and the contractor will be notified.<br></div>').fadeIn;
	});
	
});


function contractor_suggestions(id) {
	$.ajax({
		url: 'https://network4rentals.com/network/ajax_landlords/suggested_contractors/'+id, 
		dataType: "json",
		cache: false,
		type: "post",
		data: {id:id},
		success: function(response) {
			var count = Object.keys(response).length;
			
			if(count>0) {
				var suggestions = '<li><legend><i class="fa fa-check text-primary"></i> Suggested Contractors Based On System Activity</legend></li>';
				for(var i=0;i<count;i++) {
					console.log(response[i].score);
					suggestions += '<li class="suggestion" data-email="'+response[i].email+'">';
					suggestions += '<div class="suggestionBtns pull-right"><button class="btn btn-primary forward-sponsorship" data-email="'+response[i].email+'">Select</button>';
					suggestions += '<a href="https://network4rentals.com/network/landlords/contractor-click/449/'+response[i].unique_name+'" class="btn btn-primary" target="_blank"><i class="fa fa-info-circle"></i> Learn More</a></div>';
					suggestions += response[i].bName+'<br><small> '+response[i].city+' '+response[i].state+' '+response[i].zip+'</small>';
					suggestions += '</li>';
					
					
				}
				$('#suggested-contractors-scored').html(suggestions);
			} else {
				
			}
		}, 
		error: function(request, errorType, errorMessage) {

		},
		timeout: 6000,
		beforeSend: function() {
			
		},
		complete: function() {
			
		}
	});
}