$(function() {
	$('.phone').mask('(999)-999-9999');
	
    $(".public-background").change(function() {
		var e = $(this).val();
        changeImagesOut(e);
    });	
	
	$body = $("body");
	$(document).on({
		ajaxStart: function() { $body.addClass("loading"); },
		ajaxStop: function() { $body.removeClass("loading");}    
	});
	
	$('#keyword-area input').tagsinput({
		maxTags: 10
	});
	
	if($('#setKeywords').html() != '') {
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
	
	$(".unique-page-name-check").focusout(function() {
        var e = $(this).val();
        e = convertToSlug(e);
        $(this).val(e);
        if (e.length > 3) {
            $(".error-helper").html("");
            $.ajax("//network4rentals.com/network/ajax/check_unique_url/" + e, {
                success: function(e) {
                    if (e == 1) {
                        $(".error-helper").html('<i class="fa fa-exclamation-triangle"></i> Unique Name Is Already Taken, Try A Different One').fadeIn();
                    }
                },
                timeout: 6e3,
                error: function(e, t, n) {
                    $(".error-helper").html(e + " | " + t + " | " + n).fadeIn();
                }
            });
        } else {
            $(".error-helper").html('<i class="fa fa-exclamation-triangle"></i> Unique Name Must Be 6 Characters Or More').fadeIn()
        }
    });
	
	
	$('#deleteImage').click(function(e) {
		e.preventDefault();
		var id = $(this).data('imageid');
		$.ajax("//network4rentals.com/network/ajax_contractors/delete_public_image/", {
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
			timeout: 6e3,
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
		color = '28B62C';
	} else {
		color = setColor;
	}
	console.log(color);
	$('.colorPicker').colorpicker({
		color: color,
		format: 'hex'
	});
});

function convertToSlug(e) {
    return e.toLowerCase().replace(/ /g, "-").replace(/[^\w-]+/g, "")
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