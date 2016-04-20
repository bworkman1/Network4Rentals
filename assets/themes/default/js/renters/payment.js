$(function() {
	
	$('.setAutopay').change(function() {
		if ($('.autopay').is(':checked')) {
			$(".understandAutoPay").slideDown();
			$('.understandAutoPayBox').attr('required', true);
			$('#reoccuringPayments').modal('show');
			var rent = parseFloat($('.pay-amount').data('rent'));
			var discount = parseFloat($('.pay-amount').data('autodiscount'));
				
			var total = rent-discount;
				
			var sub_fee = 5;
			var fee = 5;
			
			$('.fee').val(fee.toFixed(2));
			var new_total = fee+total;
			$('.pay-amount').val(total.toFixed(2)).attr('disabled', true);
			$('#addAmount').html('<input type="hidden" name="amount" value="'+total.toFixed(2)+'" required="">');
			var today = new Date();
			$('#insertStartDate').html('<input type="text" class="form-control dateStart" name="start_date" id="start-date" aria-describedby="start-date" placeholder="'+(today.getMonth()+1)+'/'+today.getDate()+'/'+today.getFullYear()+'" value="today">');
			$('.dateStart').mask('99/99/9999');
			$('#startDate').slideDown();
			if(discount>0) {
				$('#discountNote').html('<div class="breadcrumb"><h4 style="margin: 0">Breakdown: $'+rent+'(Rent) - $'+discount+'(Auto Pay Discount) = $'+total+'</h4><small><span class="text-danger">*</span> Not including fee</small></div>');
			}
		} else {
			$(".understandAutoPay").slideUp();
			$('.understandAutoPayBox').removeAttr('required');
			$('.understandAutoPayBox').attr('checked', false);
			$('.pay-amount').val('').attr('disabled', false);
			$('#addAmount').html('');
			$('#insertStartDate').html('');
			$('#startDate').slideUp();
			setInitAmount();
		}
	});
	
	$('#startDate').css({'display':'none'});
	
	$('.dateStart').mask('99/99/9999');
	
	$('.dateStart').focusout(function() {
		if(isDate($(this).val())) {			
			var date = $(this).val();
			var parts = date.split('/');
			var date = new Date(parseInt(parts[2], 10),     // year
								parseInt(parts[0], 10) - 1, // month, starts with 0
								parseInt(parts[1], 10));    // day
			if (date < new Date()) {
				add_error_feedback('start-date', 'Start date must be in the future');
			} else {
				add_success_feedback('start-date');
			}
		} else {
			add_error_feedback('start-date', 'Invalid start date');
		}
	});
	
	$('.money').blur(function() {
		$('.money').formatCurrency({
			symbol: ''
		});
	});
	
	$(".numbersOnly").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });	
	
	$('.pay-amount').change(function() {
		var amount = parseInt($(this).val());
		var rent = $('.pay-amount').data('rent');
		var discount = $('.pay-amount').data('discount');
		if ($('.autopay').is(':checked')) { 
			amout = amount-discount;
		}
		if(rent!=amount) {
			if($('.autopay').is(':checked')) {
				$('#autoPayFail').modal('show');
				$(".understandAutoPay").slideUp();
				$('.understandAutoPayBox').removeAttr('required');
				$('.autopay').attr('checked', false);
			}
		}
		
		var sub_fee = 5;
		var fee_amount = 5;
		
		$('.fee').val(fee_amount.toFixed(2));

	});
	
	$('#name').focusout(function() {
		var name = $(this).val();
		var error = false;
		var names = name.split(' ');
		if(names.length<2) {
			error = true;
			add_error_feedback('name', 'First and last name is required');			
		} else {
			var long_enough = true;
			for(var i=0;i<names.length;i++) { //check to see if name array values are longer then 2
				if(names[i].length<2) {
					long_enough = false;
				}
			}
			if(long_enough === false) {
				error = true;
				add_error_feedback('name', 'First and last name must be 2 characters or more');
			}
		}
		if(!error) {
			add_success_feedback('name');
		}
	});
	
	$('#bank').focusout(function() {
		var name = $(this).val();
		if(name.length<2) {
			add_error_feedback('bank', 'Bank name must be 2 characters or more');
		} else {
			add_success_feedback('bank');
		}
	});
	
	$('.routing-number').focusout(function() {
		var test_aba = $(this).val();
		if(test_aba.length===9) {
			add_success_feedback('routing-number');
		} else {
			add_error_feedback('routing-number', 'Invalid routing number');
		}
    });
	
	$('#bank-number').focusout(function() {
		var test_aba = $(this).val();
		if(test_aba.length>4) {
			add_success_feedback('bank-number');
		} else {
			add_error_feedback('bank-number', 'Invalid bank account number');
		}
    });	
	 	
	$('.submitPayment').click(function(e) {
		e.preventDefault();
		var submit_form = true;
		if ($('.autopay').is(':checked')) {
			if ($('.understandAutoPayBox').is(':checked')) {
				$('.errorUnderstand').html('');
			} else {
				$('.errorUnderstand').remove();
				$('.understandAutoPay').append('<div class="text-danger errorUnderstand">You must select the box above</div>');
				submit_form = false;
				$(this).focus();
			}
		}
		
		var minAmount = $('.pay-amount').data('minamount');
		var total = parseInt($(".pay-amount").val());
		
		total = (total+5);
		if ($('.autopay').is(':checked')) { 
		
		} else {
			if(minAmount>total) {
				submit_form = false;
				$('.total-error').html('<div class="text-danger errorUnderstand">Amount must be high then minimum payment allowed</div>');
				$('.pay-amount').focus().addClass('has-error');
			} else {
				$('.total-error').html('');
			}
		}
		var autoPayChecked = $("input:checkbox[name=autopay]:checked").val();
		$('#payment-form input').each(function() {
			var parent_container = $(this).parent().parent();
			if($(this).val()=='') {
				if(parent_container.hasClass('has-error')) {
					submit_form = false;
					$(this).focus();
					return false;
				}
			}
			
			if($(this).val()=='') {
				var input_id = $(this).attr('id');
				add_error_feedback(input_id, 'Required Field');
				submit_form = false;
				$(this).focus();
				return false;
			}
			
		});
		
		if(submit_form) {
			var name = $('#name').val();
			var bank = $('#bank').val();
			var payment_type = $("input:radio[name=payment_type]:checked").val();
			var routing_number = $("#routing-number").val();
			var bank_number = $("#bank-number").val();
			var autopay = $("input:checkbox[name=autopay]:checked").val();
			
			var startDate = $('#start-date').val();
			var landlordDetails = $('#landlord-details').html();
			if(autopay === 'y') {
				var today = new Date();
				var dd = today.getDate();
				var mm = today.getMonth()+2; //January is 0!

				if(dd<10) {
					dd='0'+dd
				} 

				if(mm<10) {
					mm='0'+mm
				} 

				var nextPayment = mm+'/'+dd;
				autopay = 'Enabled';
			} else {
				autopay = 'One Time Payment';
			}
			if(payment_type != '1') {
				payment_type = 'Savings';
			} else {
				payment_type = 'Checking';
			}
			var bankCount = bank_number.length;
			var last4 = bank_number.substr(bank_number.length-4);
		
			var maskedBank = '';
			for(var i=0;i<bankCount;i++) {
				maskedBank += '*';
			}
			maskedBank = maskedBank+last4;
			
			var modal_data = '';
		
			modal_data += '<div class="row">';
			modal_data += '<div class="col-lg-6">';
			modal_data += '<b>Name On Account:</b><br>'+name+'<br>';
			modal_data += '<b>Bank:</b><br>'+bank+'<br>';
			modal_data += '<b>Payment Type:</b><br>'+payment_type+'<br>';
			modal_data += '<b>Rounting Number:</b><br>'+routing_number+'<br>';
			modal_data += '</div>';
			modal_data += '<div class="col-lg-6">';
			modal_data += '<b>Bank Number:</b><br>'+maskedBank+'<br>';
			modal_data += '<b>Auto Pay:</b><br>'+autopay+'<br>';
			if(autopay === 'Enabled') {
				modal_data += '<b>Starting On:</b><br>'+startDate+'<br>';
			}
			modal_data += '<b>Total:</b><br>$'+total+'<br>';
			modal_data += '</div>';
			modal_data += '</div>';
		
			modal_data += '<hr><h4><i class="fa fa-check text-success"></i> Check Your Landlord Details</h4>';
			modal_data += landlordDetails;
			
			
			
			$('#confirmData').html(modal_data);
			
			$('#confirm-payment').modal('show');
		} 
		
	});
	
	$('#confirm-btn').click(function() {
		$(this).html('<i class="fa fa-spinner fa-pulse"></i> Verifying Payment').attr('disabled');
		$('#payment-form').submit();
	});
	
	setInitAmount();
	
});

function setInitAmount()
{
	var elem = $('.pay-amount');
	var discount = parseFloat(elem.data('discount'));
	var rent = parseFloat(elem.data('rent'));
	var total = rent-discount;
	var partial = elem.data('partial');
	if(discount>0) {
		$('#discountNote').html('<div class="breadcrumb"><h4 style="margin: 0">Breakdown: $'+rent+'(Rent) - $'+discount+'(Discount) = $'+total+'</h4><small><span class="text-danger">*</span> Not including fee</small>');
	} else {
		$('#discountNote').html('');
	}
	
	if(partial == 'n') {
		elem.attr('disabled', true);
	}
	
	elem.val(total);
}

function isDate(txtDate) {
	var currVal = txtDate;
	if(currVal == '')
    return false;
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
	else if (dtMonth == 2){
	var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
	if (dtDay> 29 || (dtDay ==29 && !isleap))
		return false;
	}
	return true;
}

function add_error_feedback(ids, msg) {
	$('#'+ids).parent().parent().addClass('has-error').removeClass('has-success');
	$('.'+ids).removeClass('glyphicon-ok').addClass('glyphicon-remove');
	$('#'+ids+'-error').html(msg).css({'position':'relative'});
}

function add_success_feedback(ids) {
	$('#'+ids).parent().parent().addClass('has-success').removeClass('has-error');
	$('.'+ids).addClass('glyphicon-ok').removeClass('glyphicon-remove');
	$('#'+ids+'-error').html('').css({'position':'absolute'});
}

