
var base_url = "//network4rentals.com/network/";
$(document).ready(function() {

	$('#submitAccount').click(function(e) {
		e.preventDefault();
		var body = $('body');
		var formData = $('#createAccountForm').serialize();
		 $.ajax('//network4rentals.com/network/ajax_contractors/create_account/', {
			dataType: "json",
			data: formData,
			type: 'POST',
			success: function(response) {
				if(typeof response.error != "undefined") {
					$('#errorModal .crateUserErrors').html(response.error);
					$('#errorModal').modal('show');
				} else {
					//SUCCESS
					window.location.replace("https://network4rentals.com/network/contractor/public-page");
				}
			},
			error: function(e, t, n) {
				console.log(e+' = '+t+' = '+n);
				$('#errorModal .crateUserErrors').html(n);
				$('#errorModal').modal('show');
			},
			beforeSend: function() {
				body.addClass("loading");
			},
			complete: function() {
				body.removeClass("loading"); 
			}
		});
	});
	
	$('[data-toggle="tooltip"]').tooltip();
	
    $(".searchZips").click(function(e) {
        e.preventDefault();
        var t = $(".zipSearch").val();
        var n = $("#serviceType").val();
		if(t != '') {
			t = t.replace(/\D/g, "");
		}
        if ($(".zips_purchased").hasClass("added")) {} else {}
        if (t.length == 5) {
            if (n.length > 0) {
                get_zips_contractors(t, n)
            } else {
                $(".loadingZips").html('<i class="fa fa-warning text-danger"></i> You Must Select A Service Type').fadeIn();
            }
        } else {
            $(".loadingZips").html('<i class="fa fa-warning text-danger"></i> Zip Code Must Be 5 Digits Long').fadeIn();
        }
    });
	
	$('#promo-btn').click(function(event) {
		event.preventDefault();
		var promo = $('#promo').val();
		if(promo.length>2) {
			$('#promo-error').html('');
			$.ajax("//network4rentals.com/network/ajax-contractors/add_zip_create_account/", {
				dataType: "json",
				data: {'promo':promo},
				success: function(response) {
					
				},
				complete: function() {
					$('#promo-btn').html('<i class="fa fa-search"></i>').attr('disabled', false);
				},
				beforeSend: function() {
					$('#promo-btn').html('<i class="fa fa-spin fa-cog"></i>').attr('disabled', true);
				}, 
				error: function(e, t, n) {
					$('#promo-error').html('<br><div class="alert alert-danger"><b><i class="fa fa-times-circle"></i> Error:</b> Something went wrong, try again</div>');
				},
				timeout: 7000
			});
		} else {
			$('#promo-error').html('<br><div class="alert alert-danger"><b><i class="fa fa-times-circle"></i> Error:</b> Promo must be at least 3 characters long</div>');
		}
		
	});

	var e = {
        1: "Appliance Repair",
        2: "Carpentry",
        3: "Concrete",
        4: "Drain Cleaning",
        5: "Doors And Windows",
        6: "Electrical",
        7: "Heating And Cooling",
        8: "Lawn And Landscape",
        9: "Mold Removal",
        10: "Plumbing",
        11: "Painting",
        12: "Roofing",
        13: "Siding",
		14: "Pest Control | Exterminator"
    };	

	$('.back').click(function(e) {
		e.preventDefault();
	});
		
	$('.stepProceed2').click(function(e) {
		e.preventDefault();
		var count = 0;
		$(".selectedZips").each(function(index ) {
			count++;
		});
		if(count>0) {
			$('#div1').removeClass('activestep');
			$('#div2').addClass('activestep');
		} else {
			bootbox.dialog({
				message: 'In order to go to the next step you will need to add some zip codes to your shopping cart by using the search zip code feature.',
				title: "First Add Some Zip Codes:",
				buttons: {
					success: {
						label: "Close",
						className: "btn-success btn-md"
					}
				}
			});
			resetActive(event, 0, 'step-1');
			$(this).parent().removeClass('activestep');
			$('#div1').addClass('activestep');
		}

	});

	$('.stepProceed3').click(function(e) {
		e.preventDefault();
		var form_error = false;
		$("#step-2 input").each(function(index ) {
			var value = $(this).val();
			if($(this).attr("required")) {
				if(value.length==0) {
					$(this).addClass('error_input');
					form_error = true;
				} else {
					
				} 
			} else {
				
			}
		});
		
		
		
		$("#step-2 select").each(function(index ) {
			var value = $(this).val();
			if($(this).attr("required")) {
				if(value.length==0) {
					$(this).addClass('error_input');
					form_error = true;
				} else {
					$(this).removeClass('error_input');
				}
			}
		});
			
		var userEmail = $('#email').val();
		validate_userEmail(userEmail);
		if(validateEmail) {
			
		} else {
			form_error = true;
		}
		
		if(form_error) {
			var l = 15;  
			for(var i=0;i<10;i++)  {
				$("#step-2").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  
			}
			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();
			resetActive(event, 25, 'step-2');
			$(this).parent().removeClass('activestep');
			$('#div2').addClass('activestep');
		} else {
			var bName = $('#bName').val();
			$('.business-name').html('<b>'+bName+'</b>');
			$('#showError').fadeOut();
			resetActive(event, 50, 'step-3');
			$('#div2').removeClass('activestep');
			$('#div3').addClass('activestep');
		}
	});

	$('.stepProceed4').click(function(e) {
		e.preventDefault();
		form_error = false;
		$("#step-3 input").each(function(index ) { //checks inputs for values
			var value = $(this).val();
			if($(this).attr("required")) {
				if(value.length==0) {
					$(this).addClass('error_input');
					form_error = true;
				} else {
					$(this).removeClass('error_input');
				}
			} else {
				$(this).removeClass('error_input');
			}
			if($(this).hasClass('error_input')) {
				form_error = true;
			}
		});
			
		
		
		
		var userEmail = $('#email').val();
		if(validateEmail(userEmail)) {
			$('.email-error-text').html('');
			$('#email').removeClass('error_input');
		} else {
			form_error = true;
			$('#email').addClass('error_input');
			$('.email-error-text').html('<span class="text-danger">Must use a valid email address</span>');
		}
			

		
		var pwd = $('#password').val();
		var pwd2 = $('#password2').val();
		if(pwd.length<7) {
			form_error = true;
			$('.password-error-text').html('<span class="text-danger">Password Must Be 7 Characters Long Or More</span>');
		} else {
			$('.password-error-text').html('');
			if(pwd != pwd2) {
				form_error = true;	
				$('.password-error-text').html('<span class="text-danger">Passwords Do Not Match, Try Again</span>');
			} else {
				$('.password-error-text').html('');
			}
		}
		
		
		if($("#termsService").is(':checked')) {
			$('.terms-error-text').html('');
		} else {
			form_error = true;
			$('.terms-error-text').html('<span class="text-danger">You Must Agree To The Terms Of Service</span>');
		}
		
		
		if(form_error == true) {
			var l = 15;  
			for(var i=0;i<10;i++)  {
				$("#step-3").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  
			}
			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();
			resetActive(event, 50, 'step-3');
			$(this).parent().removeClass('activestep');
			$('#div3').addClass('activestep');
		} else {
			var blankPassword = '***********';
			
			$('.step4details .fill-out').html('<b>User Name:</b> '+userEmail+'<br><b>Email:</b>  '+userEmail+'<br><b>Password:</b> '+blankPassword);
			$('#showError').fadeOut();
			resetActive(event, 75, 'step-4');
			$('#div3').removeClass('activestep');
			$('#div4').addClass('activestep');
		}		

	});	
	
	$('.stepProceed5').click(function(e) {
		e.preventDefault();
		var form_error = false;
		
		$("#step-4 input").each(function(index ) { //checks inputs for values
			var value = $(this).val();
			if($(this).attr("required")) {
				if(value.length==0) {
					$(this).addClass('error_input');
					form_error = true;
				} else {
					$(this).removeClass('error_input');
				}
			} else {
				$(this).removeClass('error_input');
			}
			if($(this).hasClass('error_input')) {
				form_error = true;
			}
		});
		
		
		if($('#checkout_card_number').hasClass('error_input_cc')) {
			$('.cc_helper').html('<span class="text-danger">* Invalid Credit Card</span>');
			form_error = true;
		} else {
			$('.cc_helper').html('');
		}
		
		
		
		$("#step-4 select").each(function(index ) { //checks select boxes for values
			var value = $(this).val();
			if($(this).attr("required")) {
				if(value.length==0) {
					$(this).addClass('error_input');
					form_error = true;
				} else {
					$(this).removeClass('error_input');
				}
			}
		});
		
		var $cardinput = $('#checkout_card_number');
		$('#checkout_card_number').validateCreditCard(function(result) {
			if (result.length_valid || $cardinput.val().length > 16) {
				if (result.luhn_valid) {
					$cardinput.removeClass('error_input_cc');
					$('.cc_helper').html('');
					var cc = $cardinput.val();
					var cc_length = cc.length;
					cc = cc.substring(cc_length-4, cc_length);
					$('.cc-number').html('<b>Credit Card:</b> ****-****-****-'+cc);
				} else {

					form_error = true;
					$cardinput.addClass('error_input_cc');
					$cardinput.addClass('error_input');
				}
			} else {
				form_error = true;
				$cardinput.addClass('error_input_cc');
				$cardinput.addClass('error_input');
				$('.cc_helper').html('<span class="text-danger"> Invalid Credit Card</span>');
			}
		});

		if(form_error) {
			var l = 15;  
			for(var i=0;i<10;i++)  {
				$("#step-5").animate( { 'margin-left': "+=" + ( l = -l ) + 'px' }, 50);  
			}
			$('#showError').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Missing Required Fields</div>').fadeIn();
			resetActive(event, 75, 'step-4');
			$(this).parent().removeClass('activestep');
			$('#div4').addClass('activestep');
		} else {		
			$('#showError').fadeOut();
			resetActive(event, 100, 'step-5');
			$('#div4').removeClass('activestep');
			$('#div5').addClass('activestep');
		}
	});	
	
	$(".zip-results").on("click", ".addZip", function(t) {
        t.preventDefault();
		elem = $(this);
		var isDisabled = $(elem).is(':disabled');
		if(isDisabled) {
		
		} else {
			$(this).removeClass("btn-warning").addClass("btn-success").html('<i class="fa fa-check"></i>');
			var n = [];
			var r = [];
			var i = $(this).data("zip");
			var s = $(this).data("city");
			var o = $(this).data("state");
			var u = parseFloat($(this).data("price"));
			var a = $(this).data("service");
			$(".selectedZips").each(function(e) {
				r.push($(this).data("dup"))
			});
			var f = i + "-" + a;
			
			var zipsCount = 1;
			var allowAdditionalZips = true;
			$('.selectedZips').each(function(count) {
				if(count>48) {
					allowAdditionalZips = false;
				} else {
					zipsCount = count+1;
				}
			});

			
			if(allowAdditionalZips) {
				if ($.inArray(f, r) !== -1) {
					bootbox.dialog({
						message: "We only 1 service type and zip code per account to allow everyone in our system. You have already added <b>" + s + "<small>(" + i + ")</small></b> for <b>" + e[a] + "</b> to your cart. Please choose another zip code or another type of service you offer.",
						title: "Zip Code Already Added For This Service:",
						buttons: {
							success: {
								label: "Close",
								className: "btn-success btn-md"
							}
						}
					});
				} else {
					$.ajax("//network4rentals.com/network/ajax-contractors/add_zip_create_account/" + i + "/" + a + "/" + u, {
						dataType: "json",
						success: function(t) {
							if (t == "43") {
								
								var n = '<div id="' + i + "-" + a + '" class="border-bottom row selectedZips" data-dup="' + i + "-" + a + '">';
								n += '<div class="col-xs-1 counter">' + zipsCount + "</div>";
								n += '<div class="col-xs-2">' + i + '</div>';
								n += '<div class="col-sm-3 hidden-xs">' + s + '</div>';
								n += '<div class="col-sm-1 hidden-xs">' + o + '</div>';
								n += '<div class="col-xs-4">' + e[a] + '</div>';
								n += '<div class="col-xs-1"><button class="btn btn-sm btn-danger removeZip" data-remove="' + i + "-" + a + '"><i class="fa fa-times"></i></button></div>';
								n += '<div class="clearfix"></div></div>';
								$(".zips_purchased").append(n);
								
								var r = '<div class="' + i + "-" + a + '" class="border-bottom selectedZips" data-dup="' + i + "-" + a + '">';
									r += '<div class="row">';
										r += '<div class="col-xs-1 counter">'+zipsCount+'</div>';
										r += '<div class="col-sm-2">' + i + '</div>';
										r += '<div class="col-sm-4 hidden-xs">' + s + '</div>';
										r += '<div class="col-sm-1 hidden-xs">' + o + '</div>';
										r += '<div class="col-xs-4">' + e[a] + '</div>';
									r += '</div>';
								r += '</div>';
								
								$(".step1details .fill-out").append(r);
								reNumberZips();
							} else {
								bootbox.dialog({
									message: "We only allow 3 advertisements per zip code, per service to allow exclusivity to everyone's ads. You have already added <b>" + s + "<small>(" + i + ")</small></b> for <b>" + e[a] + "</b> to your cart. Please choose another zip code or another type of service you offer.",
									title: "Zip Code Already Added For This Service:",
									buttons: {
										success: {
											label: "Close",
											className: "btn-success btn-md"
										}
									}
								})
							}
						},
						complete: function() {
							$('.zip-results .addZip').each(function() {
								$(this).removeClass('disabled').children('i').addClass('fa-check fa-fw').removeClass('fa-spinner fa-spin').attr('disabled', false);
							});
						},
						beforeSend: function() {
							$(elem).children('i').addClass('fa-spinner fa-spin fa-fw').removeClass('fa-plus');
							$('.zip-results .addZip').each(function() {
								$(this).addClass('disabled').children('i').attr('disabled', true);
							});
						}, 
						error: function(e, t, n) {
							$(elem).addClass('btn-warning').removeClass('btn-success').children('i').removeClass('fa-check').addClass('fa-plus');
							if(t==="timeout") {
								alert('Adding the new zip code took longer then expected and timed out, please add the zip code again');
							}
						},
						timeout: 7000
					})
				}
			} else {
				$(this).removeClass('disabled').children('i').addClass('fa-plus fa-fw').removeClass('fa-spinner fa-spin').attr('disabled', false);
				bootbox.dialog({
					message: "There is a limit of 50 items per account during initial set-up. Once your account is created you can add and remove unlimited items from your account page.",
					title: "Only 50 Zip Codes Are Allowed",
					buttons: {
						success: {
							label: "Close",
							className: "btn-success btn-sm"
						}
					}
				});
			}
		}
    });
    	
    $(".zips_purchased").on("click", ".removeZip", function(e) {
        e.preventDefault();
        var t = $(this).data("remove");
        if (t.length > 6) {
            remove_zip_code(t);
        }
    });
	
    $("#billing-same").click(function() {
        if ($("#billing-same").is(":checked")) {
            var e = $("#address").val();
            var t = $("#city").val();
            var n = $("#state option:selected").val();
            var r = $("#zip").val();
            $("#baddress").val(e);
            $("#bcity").val(t);
            $("#bstate option[value=" + n + "]").attr("selected", "selected");
            $("#bzip").val(r);
            $(".confirm-billing-state").html(n + " ");
            $(".confirm-billing-city").html(t + ", ");
            $(".confirm-billing-address").html(e + " ");
            $(".confirm-billing-zip").html(r + " ")
        } else {
            $("#baddress").val("");
            $("#bcity").val("");
            $("#bstate").val("");
            $("#bstate").val("");
            $("#bzip").val("")
        }
    });
	
    $(".phone").mask("(999) 999-9999");
	
	$('#checkout_card_number').mask('9999-9999-9999-9999');
	
    $("#credit_card_number").mask("9999 9999 9999 9999");
	
    $("#first_name").focusout(function() {
        var e = $(this).val();
        var t = $("#last_name").val();
        $(".conf-name").html("<b>Name: </b>" + e + " " + t)
    });
	
    $("#last_name").focusout(function() {
        var e = $("#first_name").val();
        var t = $(this).val();
        $(".conf-name").html("<b>Name: </b>" + e + " " + t)
    });
	
    $("#address").focusout(function() {
        var e = $(this).val();
        $(".confirm-address").html("<b>Address:</b><br>" + e + " ")
    });
	
    $("#city").focusout(function() {
        var e = $(this).val();
        $(".confirm-city").html(e + ", ")
    });
	
    $("#state").focusout(function() {
        var e = $(this).val();
        $(".confirm-state").html(e + " ")
    });
	
    $("#zip").focusout(function() {
        var e = $(this).val();
        $(".confirm-zip").html(e + " ")
    });
	
    $("#baddress").focusout(function() {
        var e = $(this).val();
        $(".confirm-billing-address").html(e + "<br>")
    });
	
    $("#bcity").focusout(function() {
        var e = $(this).val();
        $(".confirm-billing-city").html(e + ", ")
    });
	
    $("#bstate").focusout(function() {
        var e = $(this).val();
        $(".confirm-billing-state").html(e + " ")
    });
	
    $("#bzip").focusout(function() {
        var e = $(this).val();
        $(".confirm-billing-zip").html(e + " ")
    });
	
    $("#phone").focusout(function() {
        var e = $(this).val();
			if(e != '') {
			var t = e.replace(/\D/g, "");
			if (t.length > 9) {
				$(".confirm-phone").html("<b>Phone: </b>" + e)
			} else {
				$(".confirm-phone").html("")
			}
        }
    });
	
    $("#fax").focusout(function() {
        var e = $(this).val();
		if(e != '') {
			var t = e.replace(/\D/g, "");
			if (t.length > 9) {
				$(".confirm-fax").html("<b>Fax: </b>" + e)
			} else {
				$(".confirm-fax").html("")
			}
        }
    });
	
    $("#exp-year").focusout(function() {
        var e = $(this).val();
        var t = $("#exp-month").val();
        $(".cc-expire").html("<b>Expires:</b> " + t + "/" + e)
    });
	
    $("#exp-month").focusout(function() {
        var e = $("#exp-year").val();
        var t = $(this).val();
        $(".cc-expire").html("<b>Expires:</b> " + t + "/" + e)
    });
	
    $("#ccv").focusout(function() {
        var e = $(this).val();
        $(".cc-ccv").html("<b>CCV:</b> " + e)
    });
	
    $("#name_on_card").focusout(function() {
        var e = $(this).val();
        $(".cc-name").html("<b>Name On Card:</b> " + e)
    });
	
    $(".numbersOnly").keyup(function() {
		if($(this).val() != '') {
			this.value = this.value.replace(/[^-\d]/g, "");
		}
    });
	
 
	
    $("#email").focusout(function(e) {
        var t = $(this).val();
		validate_userEmail(t);
    });	
	
	$('#checkout_card_number').focusout(function() {
		var $cardinput = $('#checkout_card_number');
		var cc = $cardinput.val();
		if(cc != '') {
			cc = cc.replace(/\D/g,'');
		}
		if(cc !=='') {
			$('#checkout_card_number').validateCreditCard(function(result) {
				if (result.length_valid || cc.length > 16) {
					if (result.luhn_valid) {
						$cardinput.removeClass('error_input');
						$('.cc_helper').html('');
						var cc = $cardinput.val();
						var cc_length = cc.length;
						cc = cc.substring(cc_length-4, cc_length);
						$('.cc-number').html('<b>Credit Card:</b> ****-****-****-'+cc);
					} else {
						$cardinput.addClass('error_input');
						$('.cc_helper').html('<span class="text-danger"> Invalid Credit Card</span>');
					}
				} else {
					$cardinput.addClass('error_input');
					$('.cc_helper').html('<span class="text-danger"> Invalid Credit Card</span>');
				}
			});
		}
	});

	
	
});


function reNumberZips() {
	$('.zips_purchased .counter').each(function(count) {
		var c = count+1;
		$(this).html(c);
	});
	$('.step1details .counter').each(function(count) {
		var c = count+1;
		$(this).html(c);
	});
}

function resetActive(event, percent, step) {
	$(".progress-bar").css("width", percent + "%").attr("aria-valuenow", percent);
	$(".progress-completed").text(percent + "%");

	$("div").each(function () {
		if ($(this).hasClass("activestep")) {
			$(this).removeClass("activestep");
		}
	});


	hideSteps();
	showCurrentStepInfo(step);
}

function hideSteps() {

	$("div").each(function () {

		if ($(this).hasClass("activeStepInfo")) {

			$(this).removeClass("activeStepInfo");

			$(this).addClass("hiddenStepInfo");

		}

	});

}

function showCurrentStepInfo(step) {        

	var id = "#" + step;

	$(id).addClass("activeStepInfo");

}

function validate_userEmail(email) {

	if (email.length > 4) {
		$.post("//network4rentals.com/network/ajax-contractors/check-email", {
			email: email
		}, function(e) {
			if (e == 0) {
				$("#email").addClass("error_input error");
				$(".email-error-text").html('<span class="text-danger">Email Is Already Registered</span>');
				return false;
			} else {
				$("#email").removeClass("error_input error");
				$(".email-error-text").html("");
				return true;
			}
		})
	} else {
		return true;
	}
	
}

function validateEmail(sEmail) {
    var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    if (filter.test(sEmail)) {
        return true;
    } else {
        return false;
    }
}

function get_zips_contractors(e, t) {
    var n = base_url + "ajax_contractors/show_available_service_zips/" + e + "/" + t;
    $.ajax(n, {
        dataType: "json",
        success: function(e) {
            if (e != "undefined") {
                if (e.length !== 0) {
                    var t = "<h3 class='highlight'>Select The Zips You Would Like To Subscribe To:</h3><hr>";
                    t += '<div class="row border-bottom">';
                    t += '<div class="col-xs-2"><b>Zip:</b></div>';
                    t += '<div class="col-sm-4 hidden-xs"><b>City:</b></div>';
                    t += '<div class="col-sm-1 hidden-xs"><b>State:</b></div>';
                    t += '<div class="col-xs-4"><b>Service Type:</b></div>';
                    t += '<div class="col-xs-1"><b>Add:</b></div>';
                    t += "</div>";
                    var n = [];
                    $(".selectedZips").each(function(e) {
                        n.push($(this).data("dup"))
                    });
                    var r = {
                        1: "Appliance Repair",
                        2: "Carpentry",
                        3: "Concrete",
                        4: "Drain Cleaning",
                        5: "Doors And Windows",
                        6: "Electrical",
                        7: "Heating And Cooling",
                        8: "Lawn And Landscape",
                        9: "Mold Removal",
                        10: "Plumbing",
                        11: "Painting",
                        12: "Roofing",
                        13: "Siding",
						14: "Pest Control | Exterminator",
                    };
			
                    for (var i = 0; i < e.length; i++) {
                        if (e[i]["zipCode"].length > 0) {
                            t += '<div class="row border-bottom">';
                            t += '<div class="col-xs-2">' + e[i]["zipCode"] + "</div>";
                            t += '<div class="col-sm-4 hidden-xs">' + e[i]["city"] + "</div>";
                            t += '<div class="col-sm-1 hidden-xs">' + e[i]["stateAbv"] + "</div>";
                            t += '<div class="col-xs-4">' + r[e[i]["serviceType"]] + "</div>";
                           
							var s = e[i]["zipCode"] + "-" + e[i]["serviceType"];
							
							if ($.inArray(s, n) !== -1) {
								t += '<div class="col-xs-1"><button class="addZip btn btn-success btn-sm ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '" data-price="' + e[i]["contractor_price"] + '"><i class="fa fa-check fa-fw"></i></button></div>';
							} else {
								t += '<div class="col-xs-1"><button class="addZip btn btn-warning btn-sm ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '" data-price="' + e[i]["contractor_price"] + '"><i class="fa fa-plus fa-fw"></i></button></div>';
							}
						   
								
							
							t += "</div>";
                        }
                    }
                }
            } else {
                $(".loadingZips").html("No Zips Found").fadeIn();
                t = ""
            }
            $(".zip-results").html(t)
        },
        error: function(e, t, n) {
			if(t==="timeout") {
				$(".zip-results").html('<i class="fa fa-warning text-danger"></i> Searching zips timed out, try searching again<hr>').fadeIn();
			} else {
				$(".zip-results").html('<i class="fa fa-warning text-danger"></i> No Zips Found, Invalid Zip Code<hr>').fadeIn();
			}
        },
        timeout: 6000,
        beforeSend: function() {
            $(".loadingZips").html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn()
        },
        complete: function() {
            $(".loadingZips").html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut()
        }
    });
}

function remove_zip_code(e) {
    $("#" + e).fadeOut().remove();
    var t = e.split("-");
    $.ajax(base_url + "ajax_contractors/remove_service_zip_create_account/" + t[0] + "/" + t[1], {
        success: function(n) {
            if (n != "fail") {
                $(".addZip").each(function(e) {
                    if ($(this).hasClass(t[0])) {
                        $(this).removeClass("btn-success").addClass("btn-warning").html('<i class="fa fa-plus fa-fw"></i>')
                    }
                });
                $(".step1details ." + e).fadeOut().remove();
				reNumberZips();
            } else {}
        },
        error: function(e, t, n) {},
        complete: function() {
			$('.counter').each(function(count) {
				$(this).html(count+1);
			}); 
            $("#" - e).html('<i class="fa fa-times"></i>');
			$('.selectedZips .removeZip').each(function() {
				$(this).removeClass('disabled').children('i').addClass('fa-times fa-fw').removeClass('fa-spinner fa-spin');
			});
        },				
        timeout: 7000,
        beforeSend: function() {
			$('.selectedZips .removeZip').each(function() {
				$(this).addClass('disabled').children('i').addClass('fa-spinner fa-spin').removeClass('fa-times').attr('disabled', true);
			});
		}, 
    })
}
