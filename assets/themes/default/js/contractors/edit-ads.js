$(function() {
	$(".ad-desc").keyup(function() {
        $(".other-details").removeClass("hide");
        var e = $(this).val().length;
        var t = $(this).val();
        var n = 145;
        var r = n - e;
        $(".text-counter").html("<small><b>" + r + "</b> Characters Left</small>");
        $("#ad-preview .description").html(t);
    });
	
	$('.phone').mask('(999) 999-9999');
	
	$(".titleAd").keyup(function() {
        var e = $(this).val();
        $("#ad-preview .title").html("<h4><b>" + e + "</b></h4>");
    });
	
	$(".phone").focusout(function() {
        var p = $(this).val();
        $("#ad-preview .inputPhone").html(p)
    });	
	
	$(".bName").focusout(function() {
        var n = $(this).val();
        $("#ad-preview .bNameInput").html(n);
    });
});