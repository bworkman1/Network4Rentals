$(function() {
	$('.quickMenu').click(function() {
		$('.subMenu').stop().slideUp('fast');
		$(this).next().delay(200).stop().slideToggle('fast');
	});
	$('#content').click(function() {
		$('.subMenu').stop().slideUp('fast');
	});
	
	$('.toolTips').tooltip();
});