$(document).ready(function() {
	$('.stepProceed2').click(function() {
		var counter = 0;
	});
	$('.stepProceed3').click(function() {	
			$('#div2').removeClass('activestep');
			$('#div3').addClass('activestep');
	});
	$('.stepProceed4').click(function() {
	});	
});
function validateEmail(email) {
function resetActive(event, percent, step) {
	$(".progress-bar").css("width", percent + "%").attr("aria-valuenow", percent);
	$(".progress-completed").text(percent + "%");

	$("div").each(function () {
		if ($(this).hasClass("activestep")) {
			$(this).removeClass("activestep");
		}
	});

	if (event.target.className == "col-md-2") {
		$(event.target).addClass("activestep");
	}
	else {
		$(event.target.parentNode).addClass("activestep");
	}

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