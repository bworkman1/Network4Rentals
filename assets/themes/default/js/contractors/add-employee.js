    $(function(){
        $('.colorPicker').colorpicker({
			'format':'hex'
		});
		
		$(".editEmployee").click(function(){
			var id = $(this).data('id');
			$.ajax({
				url: "https://network4rentals.com/network/ajax-contractors/getemployeeinfo", 
				data: {'id':id},
				type: 'post',
				dataType: 'json',
				success: function(result){
					$('#employeeName').val(result.name);
					$('#employeeEmail').val(result.email);
					$('.colorPicker').find('input').val(result.color);
					$('.fa-eyedropper').css({'background-color': result.color});
					$('#employeeId').val(result.id);
				}
			});
		});
		
		
		
    });