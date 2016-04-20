/**
 * Created by EMF Brian on 12/21/2015.
 */
$(function() {
	
	$('#payment-page').submit(function(event) {
		event.preventDefault();
		var dataInput = $(this).serialize();
		$.ajax({
			url: 'https://network4rentals.com/network/local-partner/create-account/submit/',
			dataType: 'json',
			data: dataInput,
			type: 'POST',
			success: function(response) {
				if(typeof response.error == 'undefined') {
					alertify.set({ delay: 10000 });
					alertify.success(response.success);
					window.location.replace("https://network4rentals.com/network/local-partner/home/");
				} else {
					alertify.set({ delay: 10000 });
					alertify.error(response.error);
				}
			},
			error: function(XHR, textStatus, errorThrown) {
				console.log(XHR);
				console.log(textStatus);
				console.log(errorThrown);
				alertify.set({ delay: 10000 });
				alertify.error("There was an error processing your request, please try again. If the problem persist contact support.");
			},
			beforeSend: function() {
				$('#submit').html('<i class="fa fa-cog fa-spin"></i> Submitting').attr('disabled', true);	
			},
			complete: function() {
				$('#submit').html('Join Now').attr('disabled', false);
			},
		});
	});
	
	if( $('input[name=credit_card]').length>0 ) {
		$('input[name=credit_card]').mask("9999-9999-9999-9999");
	};
	
	if($('#keywords').length>0) {
		$('#keywords').tagsinput({
			maxTags: 10
		});
	}
	
	if($('#setKeywords').html() != '' && $('#setKeywords').length>0) {
		var keywords_array = $('#setKeywords').html().split(',');
		for(var i=0;i<keywords_array.length;i++) {
			$('#keywords').tagsinput('add', keywords_array[i]);
		}
	}
	
	$('.addPage').click(function() {
		var count = 0;
		$('#sortable li').each(function() {
			count++;
		});
		if(count<4) {
			$('.addNewPageForm').css({'display':'inline'});
			$('#addPage').modal('show');
		} else {
			$('.addNewPageForm').css({'display':'none'});
			$('.modal-body').html('You are only allowed to have 4 pages. You can edit or delete a page to make a new one.');
			$('#addPage').modal('show');
		}
	});	
	
	if($('#sortable').length>0) {
		$("#sortable").sortable({
			update: function( event, ui ) {
				
				var object = {};
				$('#sortable li').each(function(index) {
					var id = $(this).attr('data-stack').toString();
					object[id] = index;
				});
				var sendData = JSON.stringify(object);
				console.log(sendData);
				$.post('https://network4rentals.com/network/ajax_associations/update_page_stack', { jsonData: sendData}, function(response){
					
				});
			}
		});
	}
	
	$('.payment-plan').change(function () {
		$('.payment-plan').parent().parent().removeClass('selected');
		$(this).parent().parent().addClass('selected');
		var cost = $(this).val();
		$('#userTotal').html('<p>Total: $'+cost+' a Month</p>');
		var percent = $('#percent').val();
		var freq = $(this).data('freq');
		console.log(percent);
		if(typeof percent != 'undefined') {
			var total = $("input[type='radio']:checked").val();
			var newTotal = (total-(parseFloat(total)*(parseInt(percent)/100))).toFixed(2);
			$('#userTotal').html('<p>Total: $'+newTotal+' '+freq+'</p>');
			alertify.set({ delay: 10000 });
			alertify.success('Your total has changed');
		} else {
			$('#userTotal').html('<p>Total: $'+cost+' '+freq+'</p>');
		}
	});
	
    $(document).ready(stickyFooter);
    $(window).resize(stickyFooter);
	
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
	
	
	
	if( $('.phoneMask').length>0 ) {
		$('.phoneMask').mask('(999)-999-9999');
	}
		
	$(".public-background").change(function() {
		var e = $(this).val();
        changeImagesOut(e);
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
					console.log('error');
					$('.error-helper').html(request+' | '+errorType+' | '+errorMessage).fadeIn();
				}
			});
		} else {
			$('.error-helper').html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn();
		}
	});
		
	$('#deleteImage').click(function(event) {
		event.preventDefault();
		var id = $(this).data('imageid');
		$.ajax("//network4rentals.com/network/local-partner/my-website/delete_public_image/", {
			dataType: 'json',
			data: {'id':id},
			type: 'POST',
			success: function(r) {	
				if(typeof r.success !== 'undefined') {
					var imgValue = $('#deleteImage').data('removeimg');
					$(".public-background option[value='"+imgValue+"']").remove();
					$('#deleteImage').removeClass('in');
					$('.public-background').addClass('in');
					$('.default1 img').attr('src', 'https://network4rentals.com/network/public-images/default-1-small-choosing.jpg');
					changeImagesOut('1');
				}
			},
			timeout: 1500,
			error: function(e, t, n) {
				
			},
			beforeSend: function() {
				$(this).html('<i class="fa fa-image"></i> Deleting Image..').attr('disabled', true);
			},
			complete: function() {
				$(this).html('<i class="fa fa-image"></i> Change Image').attr('disabled', false);
			}
		});
	});
	
	var setColor = $('.colorPick').val();
	if(setColor == '') {
		color = 'E9DB7A';
	} else {
		color = setColor;
	}
	
	if($('.colorPicker').length>0) {
		$('.colorPicker').colorpicker({
			color: color,
			format: 'hex'
		});
	}
	
	if($('#advertiserList').length>0) {

		$("#advertiserList").on('click', '.deleteSelection', function() {
			$(this).parent().remove();
			var removeId = $(this).data('remove');
			$('#'+removeId).remove();
			resetPaymentOptions();
		});
		
		$('#addZip').click(function(event) {
			event.preventDefault();
			resetPaymentOptions();
			$('#remove').remove();
			var userType = $('#userType').val();
			var zipCode = $('#zipCode').val();
		
			if(userType != '' && zipCode != '') {
				$('#help-text').html('');
				addNewZipCode(zipCode, userType);
			} else {
				$('#help-text').html('<i class="fa fa-exclamation-triangle fa-lg"></i> <b>Must select a user type and zip code</b>');
			}
			
		});
		
		$('#paymentLength').change(function() {
			updatePaymentPlan();
		});
		
	}
	
	$('#ad-payment').submit(function(event) {
		
		event.preventDefault();
		var formData = $('#ad-payment').serialize();
		
		
		$.ajax("//network4rentals.com/network/local-partner/add-zip-codes/payment/", { 
			dataType: 'json',
			data: formData,
			type: 'POST',
			processData: false,
			success: function(response) {
				console.log(response);
				if(typeof response.error == 'undefined') {
					window.location.replace("https://network4rentals.com/network/local-partner/my-zips");
				} else {
					$('#paymentFeedback').html('<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:  </b> '+response.error+'</div>'); 
				}
			},
			error: function(e, t, n) {
				$('#paymentFeedback').html('<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:  </b>Something went wrong, try adding it again');
			},
			beforeSend: function() {
				$('#submitAdPayment').html('<i class="fa fa-cog fa-spin fa-lg"></i> Checking Payment').attr('disabled', true);
			},
			complete: function() {
				$('#submitAdPayment').html('Submit Payment').attr('disabled', false);
			}
		});
	});
	
	$('#extendAds').submit(function(event) {
		event.preventDefault();
		var formData = $('#extendAds').serialize();
		$.ajax("//network4rentals.com/network/local-partner/my-zips/payment/", { 
			dataType: 'json',
			data: formData,
			type: 'POST',
			processData: false,
			success: function(response) {
				console.log(response);
				if(typeof response.error == 'undefined') {
					window.location.replace("https://network4rentals.com/network/local-partner/my-zips");
				} else {
					$('#paymentFeedback').html('<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:  </b> '+response.error+'</div>'); 
				}
			},
			error: function(e, t, n) {
				$('#paymentFeedback').html('<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:  </b>Something went wrong, try adding it again');
			},
			beforeSend: function() {
				$('#submitAdPayment').html('<i class="fa fa-cog fa-spin fa-lg"></i> Checking Payment').attr('disabled', true);
			},
			complete: function() {
				$('#submitAdPayment').html('Submit Payment').attr('disabled', false);
			}
		});
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
	
	if($('#extend').length>0) {
		$('.renew').click(function() {
			var id = $(this).data('id');
			if($(this).is(':checked')) {
				addExtendZip(id);
			} else {
				removeExtendZip(id);
			}
		});
		
		$('#paymentLength').change(function() {
			if($(this).val()>0) {
				$('#step-3').slideDown();
				stickyFooter();
				calculateExtendTotal();
				$('#duration').html($(this).val()+' Months');
			} else {
				$('#step-3').slideUp();
			}
		});
	}
	
	$('#searchPromo').click(function() {
		var promo = $('#promocode').val();
		alertify.set({ delay: 10000 });
		$.ajax("//network4rentals.com/network/local-partner/create-account/checkpromo/", { 
			dataType: 'json',
			data: {'promo':promo},
			type: 'POST',
			success: function(response) {
				if(typeof response.error == 'undefined') {
					if(response.success>0) {
						$('#otherSettings').html('<input type="hidden" id="percent" value="'+response.success+'">');
						var total = $("input[type='radio']:checked").val();
						var freq = $("input[type='radio']:checked").data('freq');
						var newTotal = (total-(parseFloat(total)*(parseInt(response.success)/100))).toFixed(2);
						$('#userTotal').html('<p>Total: $'+newTotal+' '+freq+'</p>');
						alertify.success('Enjoy a discout of '+response.success+'% for the first year', 10000);
					} else {
						alertify.error('Promo code expired or invalid');
						$('#promocode').val('');
					}
				} else {
					alertify.error(response.error);
				}
			},
			error: function(e, t, n) {
				alertify.error('There was a problem processing the request, try again');
			},
			beforeSend: function() {
				$('#searchPromo').html('<i class="fa fa-cog fa-spin fa-lg"></i>').attr('disabled', true);
			},
			complete: function() {
				$('#searchPromo').html('<i class="fa fa-search"></i>').attr('disabled', false);
			}
		});
	});
	
});

function removeExtendZip(id) 
{
	$('#extend-'+id).remove();
	console.log('Total Selected: '+countExtendSelected());
	if(countExtendSelected()>0) {
		$('#step-2').slideDown();
		calculateExtendTotal();
		stickyFooter();
	} else {
		$('#step-2').slideUp();
		$('#step-3').slideUp();
	}
}

function addExtendZip(id) 
{
	$('#selections').append('<input type="hidden" id="extend-'+id+'" value="'+id+'" name="extend[]">');
	$('#step-2').slideDown();
	if($('#paymentLength').val()>0) {
		$('#step-3').slideDown();
		calculateExtendTotal();
		stickyFooter();
	}
}

function calculateExtendTotal()
{
	var totalSelected = parseInt(countExtendSelected());
	var monthly = parseFloat($('#charge').data('monthly'));
	var paymentLength = parseInt($('#paymentLength').val());
	
	var total = (totalSelected*monthly)*paymentLength;
	$('.shoppingTotal').html(total.toFixed(2));
	$('#totalCost').html('To extend '+totalSelected+' ad(s) for '+paymentLength+' months will only be $'+total.toFixed(2));
	$('.charge').val(total.toFixed(2));
}

function countExtendSelected() 
{
	var count = 0;
	$('.renew').each(function() {
		if($(this).is(':checked')) {
			count++;
		}
	});
	$('#finalAds').html(count);
	return count;
}

function checkForAvaliablity(type, zip)
{
	var isAvaliable = true;
	$.ajax("//network4rentals.com/network/local-partner/add-zip-codes/availability/", {
		dataType: 'json',
		data: {'type':type, 'zip':zip},
		type: 'POST',
		success: function(response) {
			if(typeof response.success !== 'undefined') {
				var color = {renters:'warning', landlords:'primary', contractors:'success', advertisers:'custom'};
				user = type.toLowerCase();
				
				var service = '';
				if(type=='Landlords') {
					service = 'l1'+zip;
				} else if(type=='Renters') {
					service = 'r2'+zip;
				} else if(type=='Contractors') {
					service = 'c3'+zip;
				} else if (type=='Advertisers') {
					service = 'a4'+zip;
				}
				
				$('#advertiserList ol').append('<li class="list-group-item" data-zip="'+zip+'" data-type="'+type+'">'+titleCase(type)+'<span class="deleteSelection" data-remove="'+service+'"><i class="fa fa-times-circle text-danger"></i></span> <span class="label label-'+color[user]+' pull-right">'+zip+'</span></li>');

				$('#selected').append('<input id="'+service+'" type="hidden" name="selections[]" value="'+zip+'|'+type+'">');
				
				$('#help-text').html('');
			} else {
				$('#help-text').html('<i class="fa fa-exclamation-triangle"></i> '+response.error);
				isAvaliable = false;
			}
		},
		error: function(e, t, n) {
			$('#help-text').html('Something went wrong, try adding it again');
			isAvaliable = false;
		},
		beforeSend: function() {
			$('#help-text').html('<i class="fa fa-cog fa-spin fa-lg"></i> Checking Avaliablity...');
			$('#addZip').attr('disabled', true);
		},
		complete: function() {
			$('#addZip').attr('disabled', false);
		}
	});
	return isAvaliable;
}

function resetPaymentOptions() 
{
	$('#totalCost').html('');
	$('#paymentLength').val('');
	$('#step-3').removeClass('in');
}

function updatePaymentPlan() 
{
	var paymentOption = $('#paymentLength').val();
	var costPerMonth = $('#adTotal').data('price');
	var totalAds = countTotalAds();
	if(paymentOption =='') {
		$('#payment-help').html('Please select a payment option');
		return false;
	}

	var expires = new Date();
	if(paymentOption==12) {
		expires.setYear(expires.getFullYear() + 1);
	} else {
		expires.setMonth(expires.getMonth()+parseInt(paymentOption));
	}
	
	if(paymentOption>0) {
		var finalTotal = (costPerMonth*totalAds)*paymentOption;
		$('#duration').html(paymentOption+' Months');
		$('#totalCost').html('<u>'+totalAds+'</u> ads for <u>'+paymentOption+'</u> months will only be <span class="text-success"><u>$'+finalTotal.toFixed(2)+'</u></span>');
		$('#step-3').addClass('in');
		$('#finalAds').html(totalAds);
		$('.shoppingTotal').html(finalTotal.toFixed(2));
		$('#expires').html((expires.getMonth()+1)+'/'+expires.getDate()+'/'+expires.getFullYear());
		showNextStep(3);
		$('#amountTotal').val(finalTotal.toFixed(2));
	} else {
		$('#totalCost').html('');
	}
}

function countTotalAds()
{
	var count = 0;
	$('#advertiserList li').each(function(index) {
		count++;
	});
	return count;
}

function addNewZipCode(zip, userType)
{
	if(validZipCode(zip) === false) {
		console.log(zip);
		$('#help-text').html('<i class="fa fa-exclamation-triangle fa-lg"></i> <b>Invalid Zip Code</b>');
		return false;
	}
	if(checkUserType(userType) === false) {
		$('#help-text').html('<i class="fa fa-exclamation-triangle fa-lg"></i> <b>Invalid User Selection</b>');
		return false;
	}
	
	var alreadyAdded = false;
	$('#advertiserList ol li').each(function() {
		var type = $(this).data('type');
		var zipCode = $(this).data('zip');
		if(zip == zipCode && type == userType) {
			$('#help-text').html('<i class="fa fa-exclamation-triangle fa-lg"></i> <b>Already added this user type and zip code</b>');
			alreadyAdded = true;
			return false;
		}
	});
	
	if(alreadyAdded !== true) {
		checkForAvaliablity(userType, zip);
	}
	
	if($('#step-2').hasClass('in')) {
		
	} else {
		showNextStep('2');
	}
}

function showNextStep(num) 
{
	$('#step-'+num).addClass('in');
}

function titleCase(string) { 
	return string.charAt(0).toUpperCase() + string.slice(1); 
}

function checkUserType(userType) {
	var types = ['landlords', 'renters', 'advertisers', 'contractors'];
	if(types.indexOf(userType.toLowerCase())>-1) {
		return true;
	}
	return false;
}

function convertToSlug(Text) {
    return Text
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '');
}

function validZipCode(zip) {
	var zipRegex = /^\d{5}$/;
    if (!zipRegex.test(zip)) {
		return false;	
    }
    return true;
}

function changeImagesOut(e)
{
	if (e == 1) {
		$(".default1").css({
			display: "block"
		});
		$(".default2").css({
			display: "none"
		});
		$(".default3").css({
			display: "none"
		});
		$(".default4").css({
			display: "none"
		})
	} else if (e == 2) {
		$(".default2").css({
			display: "block"
		});
		$(".default1").css({
			display: "none"
		});
		$(".default3").css({
			display: "none"
		});
		$(".default4").css({
			display: "none"
		})
	} else if (e == 3) {
		$(".default3").css({
			display: "block"
		});
		$(".default1").css({
			display: "none"
		});
		$(".default2").css({
			display: "none"
		});
		$(".default4").css({
			display: "none"
		})
	} else {
		$(".default4").css({
			display: "block"
		});
		$(".default1").css({
			display: "none"
		});
		$(".default2").css({
			display: "none"
		});
		$(".default3").css({
			display: "none"
		})
	}
}

function stickyFooter() {
	if($('#extend').length==0) {
		var bodyHeight = $("body").height();
		var vwptHeight = $(window).height();
		if (vwptHeight > bodyHeight) {
			$("footer").css("position","absolute").css({"bottom":"0", "width":"100%"});
		}

		var docHeight = $(window).height();
		var footerHeight = $('footer').height();
		var footerTop = $('footer').position().top + footerHeight;

		if (footerTop < docHeight) {
			$('footer').css('margin-top', 0+ (docHeight - footerTop) + 'px');
		}
	}
}