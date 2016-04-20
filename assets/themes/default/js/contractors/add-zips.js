var base_url = "//network4rentals.com/network/";
$(function() {
	 $('body').tooltip({
		selector: '.showToolTip'
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
        14: "Pest Control &#124; Exterminator",
    };
	
    $(".searchZips").click(function(e) {
        e.preventDefault();
        var t = $(".zipSearch").val();
        var n = $("#serviceType").val();
        t = t.replace(/\D/g, "");
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

	$(".zips_purchased").on("click", ".removeZip", function(e) {
        e.preventDefault();
		var elem = $(this);
        var t = $(this).data("remove");
        var s = $(this).data("service");
        var z = $(this).data("zip");
        remove_zip_code(t, s, z, elem);
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
					$.ajax("//network4rentals.com/network/ajax-contractors/add_zips_to_account/" + i + "/" + a + "/", {
						dataType: "json",
						success: function(t) {
							if(typeof t.error != 'undefined') {
								bootbox.dialog({
									message: "We only allow 3 advertisements per zip code, per service to allow exclusivity to everyone's ads. You have already added <b>" + s + "<small>(" + i + ")</small></b> for <b>" + e[a] + "</b> to your cart. Please choose another zip code or another type of service you offer.",
									title: "Zip Code Already Added For This Service:",
									buttons: {
										success: {
											label: "Close",
											className: "btn-success btn-md"
										}
									}
								});
							} else {
								$('.noZips').remove();
								var n = '<div id="' + i + "-" + a + '" class="border-bottom row selectedZips" data-dup="' + i + "-" + a + '">';
								n += '<div class="col-xs-1 counter">' + zipsCount + "</div>";
								n += '<div class="col-xs-1">' + i + '</div>';
								n += '<div class="col-sm-2 hidden-xs">' + s + '</div>';
								n += '<div class="col-sm-1 hidden-xs">' + o + '</div>';
								n += '<div class="col-sm-3 col-xs-6">' + e[a] + '</div>';
								n += '<div class="col-sm-3 hidden-xs"></div>';
								n += '<div class="col-sm-1 col-xs-2"><button class="btn btn-sm btn-default removeZip showToolTip" data-toggle="tooltip" title="Refresh Screen To Remove"  data-zip="'+i+'" data-service="'+a+'" data-remove="'+t.success+'" data-data-remove="' + i + "-" + a + '"><i class="fa fa-times  fa-fw"></i></button></div>';
								n += '<div class="clearfix"></div></div>';
								$(".zips_purchased").append(n);
								reNumberZips();
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
							$(this).removeClass('disabled').children('i').addClass('fa-plus fa-fw').removeClass('fa-spinner fa-spin').attr('disabled', false);

							if(t==="timeout") {
								alert('Adding the new zip code took longer then expected and timed out, please add the zip code again');
							}
						},
						timeout: 12000
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
	
});

function get_zips_contractors(e, t) {
    var n = base_url + "ajax_contractors/show_available_zips/" + e + "/" + t;
    $.ajax(n, {
        dataType: "json",
        success: function(e) {
            if (e != "undefined") {
                if (e.length !== 0) {
                    var t = "<h3 class='highlight'>Select The Zips You Would Like To Subscribe To:</h3><hr>";
                    t += '<div class="row border-bottom">';
                    t += '<div class="col-sm-2 col-xs-3"><b>Zip:</b></div>';
                    t += '<div class="col-sm-4 hidden-xs"><b>City:</b></div>';
                    t += '<div class="col-sm-1 hidden-xs"><b>State:</b></div>';
                    t += '<div class="col-sm-4 col-xs-7"><b>Service Type:</b></div>';
                    t += '<div class="col-sm-1 col-xs-2"><b>Add:</b></div>';
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
						14: "Pest Control &#124; Exterminator",
                    };
                    for (var i = 0; i < e.length; i++) {
                        if (e[i]["zipCode"].length > 0) {
                            t += '<div class="row border-bottom">';
                            t += '<div class="col-sm-2 col-xs-3">' + e[i]["zipCode"] + "</div>";
                            t += '<div class="col-sm-4 hidden-xs">' + e[i]["city"] + "</div>";
                            t += '<div class="col-sm-1 hidden-xs">' + e[i]["stateAbv"] + "</div>";
                            t += '<div class="col-sm-4 col-xs-7">' + r[e[i]["serviceType"]] + "</div>";
                           
							var s = e[i]["zipCode"] + "-" + e[i]["serviceType"];
							if ($.inArray(s, n) !== -1) {
								t += '<div class="col-sm-1 col-xs-2"><button class="addZip btn btn-success btn-sm '+e[i]["serviceType"]+'-'+e[i]["zipCode"]+' ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '" data-price="' + e[i]["contractor_price"] + '"><i class="fa fa-check fa-fw"></i></button></div>'
							} else {
								t += '<div class="col-sm-1 col-xs-2"><button class="addZip btn btn-warning btn-sm '+e[i]["serviceType"]+'-'+e[i]["zipCode"]+' ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '"><i class="fa fa-plus fa-fw"></i></button></div>'
							}
                           
                            t += "</div>"
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

function remove_zip_code(e, s, z, elem) {
    $.ajax(base_url + "ajax_contractors/remove_zip_account/", {
		type: 'POST',
		dataType: 'json',
		data: {'id':e, 'service':s},
        success: function(n) {
			if(typeof n.error != 'undefined') {
				
			} else {
				$('.'+s+'-'+z).html('<i class="fa fa-plus fa-fw"></i>').closest('.btn').removeClass('btn-success').addClass('btn-warning');
				elem.parent().parent().fadeOut().remove();
			}
        },
        error: function(e, t, n) {
			if(t==="timeout") {
				alert('Removing the new zip code took longer then expected and timed out, please try again');
			}
		},
        complete: function() {
			$('.counter').each(function(count) {
				$(this).html(count+1);
			}); 
            $("#" - e).html('<i class="fa fa-times"></i>');
			$('.selectedZips .removeZip').each(function() {
				$(this).removeClass('disabled').children('i').addClass('fa-times fa-fw').removeClass('fa-spinner fa-spin');
			});
        },				
        timeout: 6000,
        beforeSend: function() {
			$('.selectedZips .removeZip').each(function() {
				$(this).addClass('disabled').children('i').addClass('fa-spinner fa-spin').removeClass('fa-times').attr('disabled', true);
			});
		}, 
    })
}

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