$(function() {
	$('.cancelAutoPayments').click(function() {
		var tenantId = $(this).data('tenant');
		var subid = $(this).data('subid');
		
		$('#cancelSub').attr('href', 'https://network4rentals.com/network/landlords/cancel-auto-payments/'+tenantId+'/'+subid);
	});
});