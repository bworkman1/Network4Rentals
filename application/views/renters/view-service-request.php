<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-bookmark-o"></i> Service Request
	</div>
	<div class="panel-body">
		<?php 
			$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
			if(!empty($requests)) {
				echo '<div class="row" style="border-bottom: 1px solid #ccc;">';
				echo '<div class="col-sm-4">';
				echo '<h4>Service Type:</h4>';
				echo '</div>';
				echo '<div class="col-sm-4">';
				echo '<h4>Submitted:</h4>';
				echo '</div>';
				echo '<div class="col-sm-2">';
				echo '<h4>Status</h4>';
				echo '</div>';
				echo '<div class="col-sm-2 text-right">';
				echo '<h4>View:</h4>';
				echo '</div>';
				echo '</div>';
				for($i=0;$i<count($requests);$i++) {
					echo '<div class="row" style="line-height: 2.4em; border-bottom: 1px solid #ccc;">';
					echo '<div class="col-sm-4">';
					echo '<small>'.$services_array[$requests[$i]['service_type']].'</small>';
					echo '</div>';
					echo '<div class="col-sm-4">';
					echo '<small>'.date('m-d-Y h:i A', strtotime($requests[$i]['submitted'])+3600).' EST</small>';
					echo '</div>';
					echo '<div class="col-sm-2">';
					if($requests[$i]['complete'] == 'y') {
						echo '<small>Complete</small>';
					} else {
						echo '<small>Incomplete</small>';
					}
					
					echo '</div>';
					echo '<div class="col-sm-2 text-right">';
					echo '<a href="'.base_url().'renters/view-request/'.$requests[$i]['id'].'" class="btn btn-primary" >View</a>';
					echo '</div>';
					echo '</div>';
				}
			} else {
				echo 'You have not submitted any service request to any landlords yet. Once you submit one they will appear here and you will be able to keep track of the status of each request.';
			}
		?>
	</div>
</div>