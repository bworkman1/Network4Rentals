var base_url = "//network4rentals.com/network/";function get_zips_contractors(e, t) {
    var n = base_url + "contractors/show_available_zips/" + e + "/" + t;
    $.ajax(n, {
        dataType: "json",
        success: function(e) {
            if (e != "undefined") {
                if (e.length !== 0) {
                    var t = "<h3>Select The Zips You Would Like To Subscribe To:</h3><hr>";
                    t += '<div class="row border-bottom">';
                    t += '<div class="col-xs-2"><b>Zip:</b></div>';
                    t += '<div class="col-sm-3 hidden-xs"><b>City:</b></div>';
                    t += '<div class="col-sm-1 hidden-xs"><b>State:</b></div>';
                    t += '<div class="col-xs-4"><b>Service Type:</b></div>';
                    t += '<div class="col-xs-1"><b>Price:</b></div>';
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
                        13: "Siding"
                    };
                    for (var i = 0; i < e.length; i++) {
                        if (e[i]["zipCode"].length > 0) {
                            t += '<div class="row border-bottom">';
                            t += '<div class="col-xs-2">' + e[i]["zipCode"] + "</div>";
                            t += '<div class="col-sm-3 hidden-xs">' + e[i]["city"] + "</div>";
                            t += '<div class="col-sm-1 hidden-xs">' + e[i]["stateAbv"] + "</div>";
                            t += '<div class="col-xs-4">' + r[e[i]["serviceType"]] + "</div>";
                            t += '<div class="col-xs-1">$' + e[i]["contractor_price"] + "</div>";
                            if (e[i]["taken"] != 0) {
                                var s = e[i]["zipCode"] + "-" + e[i]["serviceType"];
                                if ($.inArray(s, n) !== -1) {
                                    t += '<div class="col-xs-1"><button class="addZip btn btn-success btn-xs ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '" data-price="' + e[i]["contractor_price"] + '"><i class="fa fa-check fa-fw"></i></button></div>'
                                } else {
                                    t += '<div class="col-xs-1"><button class="addZip btn btn-warning btn-xs ' + e[i]["zipCode"] + '" data-zip="' + e[i]["zipCode"] + '" data-state="' + e[i]["stateAbv"] + '" data-city="' + e[i]["city"] + '" data-service="' + e[i]["serviceType"] + '" data-price="' + e[i]["contractor_price"] + '"><i class="fa fa-plus fa-fw"></i></button></div>'
                                }
                            } else {
                                t += '<div class="col-xs-1"><span class="label label-danger">Taken</span></div>'
                            }
                            t += "</div>"
                        }
                    }
                }
            } else {
                $(".loading").html("No Zips Found").fadeIn();
                t = ""
            }
            $(".zip-results").html(t)
        },
        error: function(e, t, n) {
            $(".zip-results").html('<i class="fa fa-warning text-danger"></i> No Zips Found, Invalid Zip Code<hr>').fadeIn()
        },
        timeout: 6e3,
        beforeSend: function() {
            $(".loading").html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeIn()
        },
        complete: function() {
            $(".loading").html('<i class="fa fa-spinner fa-spin"></i> Loading... ').fadeOut()
        }
    })
}

function calculate_price() {
    var e = $("#frequency").val();
    var t = [];
    $(".calculate").each(function(e) {
        t.push($(this).data("price").toFixed(2))
    });
    var n = 0;
    var r = t.length;
    var i = 0;
    if (r > 0) {
        for (var s = 0; s < r; s++) {
            i += parseFloat(t[s])
        }
        n = e * i;
        var o = "";
        if (e == 1) {
            o = "Per Month"
        } else if (e == 3) {
            o = "Quarterly"
        } else if (e == 6) {
            o = "Bi-Yearly"
        } else if (e == 12) {
            o = "Yearly"
        }
        $(".orderDetails").html('<div class="row text-center"><div class="col-sm-3">Subscription Length</div><div class="col-sm-3"> Subscription Total </div><div class="col-sm-3"> Billing Cycle </div><div class="col-sm-3"> Cost Per Bill</div></div><div class="row text-center"><div class="col-sm-3">1 Year</div><div class="col-sm-3">$' + (i * 12).toFixed(2) + '</div><div class="col-sm-3">' + o + ' </div><div class="col-sm-3"><b>$' + n.toFixed(2) + "</b></div></div>");
        $(".confirmOrderTotal").html("<hr><h4>$" + n.toFixed(2) + "</b> " + o + "</h4>")
    } else {
        $(".orderDetails").html('<div class="row text-center"><div class="col-sm-3">Subscription Length</div><div class="col-sm-3"> Subscription Total </div><div class="col-sm-3"> Billing Cycle </div><div class="col-sm-3"> Cost Per Bill</div></div><div class="row text-center"><div class="col-sm-3">1 Year</div><div class="col-sm-3">$0.00</div><div class="col-sm-3">1 Month</div><div class="col-sm-3"><b>$0.00</b></div></div>')
    }
}

function remove_zip_code(e) {
    $("#" + e).fadeOut().remove();
    var t = e.split("-");
    $.ajax(base_url + "contractors/remove_zip/" + t[0] + "/" + t[1], {
        success: function(n) {
            if (n != "fail") {
                $(".addZip").each(function(e) {
                    if ($(this).hasClass(t[0])) {
                        $(this).removeClass("btn-success").addClass("btn-warning").html('<i class="fa fa-plus fa-fw"></i>')
                    }
                });
                $(".step1details ." + e).fadeOut().remove()
            } else {}
        },
        error: function(e, t, n) {},
        complete: function() {
            $("#" - e).html('<i class="fa fa-times"></i>');			$('.selectedZips .removeZip').each(function() {				$(this).removeClass('disabled').children('i').addClass('fa-times fa-fw').removeClass('fa-spinner fa-spin');			});
        },				
        timeout: 6e3,
        beforeSend: function() {			$('.selectedZips .removeZip').each(function() {				$(this).addClass('disabled').children('i').addClass('fa-spinner fa-spin').removeClass('fa-times').attr('disabled', true);			});		}, 
    })
}

function convertToSlug(e) {
    return e.toLowerCase().replace(/ /g, "-").replace(/[^\w-]+/g, "")
}
$(document).ready(function() {	
    $(".back-a-step").click(function(e) {
        e.preventDefault();
        var t = $(this).data("step");
        var n = t + 1;
        $("#step-" + t).removeClass("hiddenStepInfo").addClass("activeStepInfo");
        $("#step-" + n).removeClass("activeStepInfo").addClass("hiddenStepInfo");
        $(".text-right").removeClass("activestep")
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
        13: "Siding"
    };
    $(".youtube_video").fitVids();
    var t = $(window).height();
    var n = $("footer").height();
    var r = $("footer").position().top + n;
    $("#support-help").hover(function() {
        $(this).stop().animate({
            right: "0"
        }, 350)
    }).mouseleave(function() {
        $(this).stop().animate({
            right: "-158"
        }, 350)
    });
    if (r < t) {
        $("footer").css("margin-top", 0 + (t - r) + "px")
    }
    $(".titleAd").keyup(function() {
        var e = $(this).val();
        $("#ad-preview .title").html("<h4><b>" + e + "</b></h4>")
    });
    $(".ad-desc").keyup(function() {
        $(".other-details").removeClass("hide");
        var e = $(this).val().length;
        var t = $(this).val();
        var n = 145;
        var r = n - e;
        $(".text-counter").html("<small><b>" + r + "</b> Characters Left</small>");
        $("#ad-preview .description").html(t)
    });
    $(".toolTips").tooltip("toggle");
    $(".toolTips").tooltip("hide");
    $(".public-background").change(function() {
        var e = $(this).val();
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
    });
    $(".unique-page-name-check").focusout(function() {
        var e = $(this).val();
        e = convertToSlug(e);
        $(this).val(e);
        if (e.length > 3) {
            $(".error-helper").html("");
            $.ajax("//network4rentals.com/network/ajax/check_unique_url/" + e, {
                success: function(e) {
                    if (e == 1) {
                        $(".error-helper").html('<i class="fa fa-exclamation-triangle"></i> Unique Name Is Already Taken, Try A Different One').fadeIn()
                    }
                },
                timeout: 6e3,
                error: function(e, t, n) {
                    $(".error-helper").html(e + " | " + t + " | " + n).fadeIn()
                }
            })
        } else {
            $(".error-helper").html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn()
        }
    });
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
                $(".loading").html('<i class="fa fa-warning text-danger"></i> You Must Select A Service Type').fadeIn()
            }
        } else {
            $(".loading").html('<i class="fa fa-warning text-danger"></i> Zip Code Must Be 5 Digits Long').fadeIn()
        }
    });
    $(".zip-results").on("click", ".addZip", function(t) {
        t.preventDefault();
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
        if ($.inArray(f, r) !== -1) {
            bootbox.dialog({
                message: "We only allow 3 advertisements per zip code, per service to allow excusivity to everyone's ads. You have already added <b>" + s + "<small>(" + i + ")</small></b> for <b>" + e[a] + "</b> to your cart. Please choose another zip code or another type of service you offer.",
                title: "Zip Code Already Added For This Service:",
                buttons: {
                    success: {
                        label: "Close",
                        className: "btn-success btn-sm"
                    }
                }
            })
        } else {
            $.ajax("//network4rentals.com/network/contractors/add_zip/" + i + "/" + a + "/" + u, {
                dataType: "json",
                success: function(t) {
                    if (t == "43") {
                        var n = '<div id="' + i + "-" + a + '" class="border-bottom selectedZips" data-dup="' + i + "-" + a + '">';
                        n += '<div class="col-xs-2">' + i + "</div>";
                        n += '<div class="col-sm-3 hidden-xs">' + s + "</div>";
                        n += '<div class="col-sm-1 hidden-xs">' + o + "</div>";
                        n += '<div class="col-xs-4">' + e[a] + "</div>";
                        n += '<div class="col-xs-1 calculate" data-price="' + u + '">$' + u + "</div>";
                        n += '<div class="col-xs-1"><button class="btn btn-xs btn-danger removeZip" data-remove="' + i + "-" + a + '"><i class="fa fa-times"></i></button></div>';
                        n += '<div class="clearfix"></div></div>';
                        $(".zips_purchased").append(n);
                        var r = '<div class="' + i + "-" + a + '" class="border-bottom selectedZips" data-dup="' + i + "-" + a + '">';
                        r += '<div class="row"><div class="col-sm-3">' + i + '</div><div class="col-sm-6">' + e[a] + '</div><div class="col-sm-3">$' + u + " <small>Per Month</small></div></div>";
                        $(".step1details .fill-out").append(r);
                        calculate_price()
                    } else {
                        bootbox.dialog({
                            message: "We only allow 3 advertisements per zip code, per service to allow excusivity to everyone's ads. You have already added <b>" + s + "<small>(" + i + ")</small></b> for <b>" + e[a] + "</b> to your cart. Please choose another zip code or another type of service you offer.",
                            title: "Zip Code Already Added For This Service:",
                            buttons: {
                                success: {
                                    label: "Close",
                                    className: "btn-success btn-sm"
                                }
                            }
                        })
                    }
                },				complete: function() {					$('.zip-results .addZip').each(function() {						$(this).removeClass('disabled').children('i').addClass('fa-plus fa-fw').removeClass('fa-spinner fa-spin').attr('disabled', false);					});				},				beforeSend: function() {					$('.zip-results .addZip').each(function() {						$(this).addClass('disabled').children('i').addClass('fa-spinner fa-spin fa-fw').removeClass('fa-plus').attr('disabled', true);					});				}, 
                error: function(e, t, n) {},
                timeout: 6e3
            })
        }
    });
    calculate_price();
    $(".zips_purchased").on("click", ".removeZip", function(e) {
        e.preventDefault();
        var t = $(this).data("remove");
        if (t.length > 6) {
            remove_zip_code(t);
            calculate_price()
        }
    });
    $(".steper").click(function(e) {
        e.preventDefault()
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
    $(".phone").mask("(999) 999-9999");    $("#credit_card_number").mask("9999 9999 9999 9999");
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
        var t = e.replace(/\D/g, "");
        if (t.length > 9) {
            $(".confirm-phone").html("<b>Phone: </b>" + e)
        } else {
            $(".confirm-phone").html("")
        }
    });
    $("#fax").focusout(function() {
        var e = $(this).val();
        var t = e.replace(/\D/g, "");
        if (t.length > 9) {
            $(".confirm-fax").html("<b>Fax: </b>" + e)
        } else {
            $(".confirm-fax").html("")
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
        this.value = this.value.replace(/[^-\d]/g, "")
    });
    $("#user").keyup(function(e) {
        var t = $(this).val();
        if (t.length > 4) {
            $.post("https://network4rentals.com/network/contractors/check-username", {
                username: t
            }, function(e) {
                if (e == 0) {
                    $("#user").addClass("error_input error");
                    $(".user-error-text").html('<span class="text-danger">User Name Has Already Been Taken</span>')
                } else {
                    $("#user").removeClass("error_input error");
                    $(".user-error-text").html("")
                }
            })
        }
    });
    $("#email").keyup(function(e) {
        var t = $(this).val();
        if (t.length > 4) {
            $.post("https://network4rentals.com/network/contractors/check-email", {
                email: t
            }, function(e) {
                if (e == 0) {
                    $("#email").addClass("error_input error");
                    $(".email-error-text").html('<span class="text-danger">Email Is Already Registered</span>');
                    form_error = true
                } else {
                    $("#email").removeClass("error_input error");
                    $(".email-error-text").html("")
                }
            })
        }
    });		//* HERE */
    $(".updatePayment").click(function(e) {
        e.preventDefault();
        var t = false;
        $(".updateCreditCardData input").each(function(e) {
            var n = $(this).val();
            if ($(this).attr("required")) {
                if (n.length == 0) {
                    $(this).addClass("error_input");
                    t = true
                } else {
                    $(this).removeClass("error_input")
                }
            } else {
                $(this).removeClass("error_input")
            }
        });
        if (t) {
            var n = 15;
            for (var r = 0; r < 10; r++) {
                $(".updateCreditCardData").animate({
                    "margin-left": "+=" + (n = -n) + "px"
                }, 50)
            }
        } else {
            $(this).closest("form").submit()
        }
    });
    var i = $("#checkout_card_number");
    $("#checkout_card_number").validateCreditCard(function(e) {
        if (e.card_type != null) {
            switch (e.card_type.name) {
                case "visa":
                    i.css("background-position", "3px -34px");
                    i.addClass("card_visa");
                    break;
                case "visa_electron":
                    i.css("background-position", "3px -72px");
                    i.addClass("card_visa_electron");
                    break;
                case "mastercard":
                    i.css("background-position", "3px -110px");
                    i.addClass("card_mastercard");
                    break;
                case "maestro":
                    i.css("background-position", "3px -148px");
                    i.addClass("card_maestro");
                    break;
                case "discover":
                    i.css("background-position", "3px -186px");
                    i.addClass("card_discover");
                    break;
                case "amex":
                    i.css("background-position", "3px -223px");
                    i.addClass("card_amex");
                    break;
                default:
                    i.css("background-position", "3px 3px");
                    break
            }
        } else {
            i.css("background-position", "3px 3px")
        }
        if (e.length_valid || i.val().length > 16) {
            if (e.luhn_valid) {
                i.removeClass("error_input_cc");
                $(".cc_helper").html("");
                var t = i.val();
                var n = t.length;
                t = t.substring(n - 4, n);
                $(".cc-number").html("<b>Credit Card:</b> ****-****-****-" + t)
            } else {
                i.addClass("error_input_cc");
                i.addClass("error_input")
            }
        } else {}
    });
    $(".changePass").click(function(e) {
        e.preventDefault();
        var t = false;
        var n = $("#pwd").val();
        var r = $("#pwd2").val();
        if (n.length < 6) {
            t = true;
            $("#pwd").addClass("error_input");
            $("#pwd2").addClass("error_input");
            $(".password-error-text").html('<span class="text-danger">Password Must Be At Least 7 Characters Long</span>')
        }
        if (n != r) {
            t = true;
            $("#pwd").addClass("error_input");
            $("#pwd2").addClass("error_input");
            $(".password-error-text").html('<span class="text-danger">Passwords Do Not Match</span>')
        }
        if (t) {
            $("#password").animate({
                "margin-left": "+=" + (l = -l) + "px"
            }, 50)
        } else {
            $("#pwd").removeClass("error_input");
            $("#pwd2").removeClass("error_input");
            $(".password-error-text").html("");
            $(this).closest("form").submit()
        }
    });
});
