$(document).ready(function() {
	var docHeight = $(window).height();
	var footerHeight = $('footer').height();
	var footerTop = $('footer').position().top + footerHeight;

	if (footerTop < docHeight) {
		$('footer').css('margin-top', 0+ (docHeight - footerTop) + 'px');
	}
	
	$('.toolTips').tooltip('toggle');
	$('.toolTips').tooltip('hide');
	//public page background changer
	$('.public-background').change(function() {
		var background = $(this).val();
		if(background==1) {
			$('.default1').css({'display':'block'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==2) {
			$('.default2').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default3').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else if(background==3) {
			$('.default3').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default4').css({'display':'none'});
		} else {
			$('.default4').css({'display':'block'});
			$('.default1').css({'display':'none'});
			$('.default2').css({'display':'none'});
			$('.default3').css({'display':'none'});
		}
	});
	

	
	$('#support-help').hover(function() {
		$(this).stop().animate({
			right: "0"
		}, 350);
	}).mouseleave(function() {
		$(this).stop().animate({
			right: "-158"
		}, 350);
	});
	
	$('.changePass').click(function(event) {
		event.preventDefault();
		var form_error = false;
		var pwd = $('#pwd').val();
		var pwd2 = $('#pwd2').val();
		if(pwd.length<6) {
			form_error = true;
			$('#pwd').addClass('error_input');
			$('#pwd2').addClass('error_input');
			$('.password-error-text').html('<span class="text-danger">Password Must Be At Least 7 Characters Long</span>');
		}
		if(pwd!=pwd2) {
			form_error = true;
			$('#pwd').addClass('error_input');
			$('#pwd2').addClass('error_input');
			$('.password-error-text').html('<span class="text-danger">Passwords Do Not Match</span>');
		}
		if(form_error) {
			$("#password").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50); 
		} else {
			$('#pwd').removeClass('error_input');
			$('#pwd2').removeClass('error_input');
			$('.password-error-text').html('');
			$(this).closest('form').submit();
		}
		
	});
	
	// unique page name checker
	$('.unique-page-name-check').focusout(function() {
		var unique_name = $(this).val();
		unique_name = convertToSlug(unique_name);
		if(unique_name.length > 3) {
			$('.error-helper').html('');
			$.ajax('https://network4rentals.com/network/ajax/check_unique_url/'+unique_name, {
				success: function(response) {
					if(response == 1) {
						$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Is Already Taken, Try A Different One').fadeIn();
					}
				},
				timeout: 6000,
				error: function(request, errorType, errorMessage) {
					$('.error-helper').html(request+' | '+errorType+' | '+errorMessage).fadeIn();
				}
			});
		} else {
			$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn();
		}
	});
	
	$('.titleAd').keyup(function() {
		var str = $(this).val();
		$('#ad-preview .title').html('<h4><b>'+str+'</b></h4>');
	});	
	
	$('.ad-desc').keyup(function() {
		$('.other-details').removeClass('hide');
		var str_count = $(this).val().length;
		var str = $(this).val();
		var allowed = 145;
		var allowed_left = allowed-str_count;
		$('.text-counter').html('<small><b>'+allowed_left+'</b> Characters Left</small>');
		$('#ad-preview .description').html(str);
	});
});

function convertToSlug(Text) {
    return Text
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
}