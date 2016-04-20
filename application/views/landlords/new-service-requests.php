<div class="row">
	<div class="col-sm-8">
		<h2><i class="fa fa-file-o text-primary"></i> New Service Request</h2>
	</div>
	<div class="col-sm-4">
	</div>
</div> 
<hr>
<?php
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
	
	if(!empty($results)) {
		echo '<div class="row"><b>';
			echo '<div class="col-sm-2">';
				echo 'Submitted';
			echo '</div>';
			echo '<div class="col-sm-4">';
				echo 'Address';
			echo '</div>';
			echo '<div class="col-sm-4">';
				echo 'Service Type';
			echo '</div>';
			echo '<div class="col-sm-2 text-right">';
				echo 'Options';
			echo '</div>';
		echo '</b></div>';
		foreach($results as $key => $val) {
			echo '<div class="page-results">';
				echo '<div class="row">';
					echo '<div class="col-sm-2">';
						echo date('m-d-Y', strtotime($val['submitted']));
					echo '</div>';
					echo '<div class="col-sm-4">';
						if(!empty($val['address'])) {
							echo $val['address'];
						}
					echo '</div>';
					echo '<div class="col-sm-4">';
						echo $services_array[$val['service_type']];
					echo '</div>';
					echo '<div class="col-sm-2 text-right">';
						echo '<a href="'.base_url().'landlords/view-service-request/'.$val['id'].'" class="btn btn-xs btn-primary toolTips" title="View Request"><i class="fa fa-info-circle"></i></a>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		} 
		
		echo '<div class="text-center">';
			echo $links;
		echo '</div>';
	} else {
		echo 'There are no service request available for you to view.';
	}
?>

