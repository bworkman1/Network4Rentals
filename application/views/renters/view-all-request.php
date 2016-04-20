<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-bookmark-o"></i> All My Service Request
	</div>
	<div class="panel-body">
		<?php 
			if(!empty($requests)) {
				echo '<ul class="list-service-requests">';
				foreach($requests as $key => $val) {
					echo '<li>';
						echo '<div class="row">';
							echo '<div class="col-sm-6">';
								echo '<small><i class="fa fa-map-marker text-warning"></i> '.$val->rental_address.' '.$val->rental_city.' '.$val->rental_state;
							echo '</small></div>';
							echo '<div class="col-sm-2">';
								echo '<span class="label label-success">'.$val->complete.' Complete</span>';
							echo '</div>';
							echo '<div class="col-sm-2">';
								echo '<span class="label label-danger">'.$val->incomplete.' In-Complete</span>';
							echo '</div>';
							if($val->complete != 0 || $val->incomplete != 0) {
								echo '<div class="col-sm-2">';
									echo '<a href="'.base_url().'renters/view-requests/'.$val->id.'" class="btn btn-primary btn-block btn-sm">View</a>';
								echo '</div>';
							}
						echo '</div>';
					echo '</li>';
				}
			} else {
				echo 'You have not added any landlords to your account. Go to your "<a href="https://network4rentals.com/network/renters/my-history">Rental History</a>" page to add a landlord to your account.';
			}
		?>
	</div>
</div>