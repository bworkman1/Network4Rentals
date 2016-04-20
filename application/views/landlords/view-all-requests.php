<h2>
	<i class="fa fa-eye text-primary"></i> Service Requests
</h2>
<hr>
<?php echo form_open('landlords/search-requests'); ?>
	<div class="row">
		<div class="col-md-2 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Start Date:</label>
				<input type="text" autocomplete="off" name="start_date" class="form-control datepicker">
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Service Type:</label>
				<?php 
					$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other'); 
					echo "<select name='serviceType' id='serviceType' class='form-control'>";
					echo '<option value="">Choose One...</option>';
					foreach($services_array as $key => $val) {
						if($_POST['serviceType'] == $key) {
							echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					} 
					echo "</select>";
				?>
			</div>
		</div>
		<div class="col-md-2 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>End Date:</label>
				<input type="text" autocomplete="off" name="end_date" class="form-control datepicker">
			</div>
		</div>
		<div class="col-md-4 col-sm-6 col-xs-6">
			<div class="form-group">
				<label>Address:</label>
				<select class="form-control" name="address">
					<?php 
						if(!empty($my_properties)) {
							echo '<option value="">Select One..</option>';
							foreach($my_properties as $key => $val) {
								echo '<option value="'.$val->id.'">'.$val->address.' '.$val->zipCode.'</option>';
							}
						} else {
							echo '<option value="">No Properties Added</option>';
						}
					?>
				</select>
			</div>
		</div>
		<div class="col-md-1 hidden-sm hidden-xs">
			<div class="form-group">
				<br>
				<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-primary pull-right hidden-lg hidden-md"><i class="fa fa-search"></i></button>
</form>
<hr>
<?php 
	
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
	if(!empty($results)) {
		echo '<div class="row"><b>';
			echo '<div class="col-sm-2">';
				echo '';
			echo '</div>';
			echo '<div class="col-sm-2">';
				echo 'Submitted';
			echo '</div>';
			echo '<div class="col-sm-3">';
				echo 'Address';
			echo '</div>';
			echo '<div class="col-sm-3">';
				echo 'Service Type';
			echo '</div>';
			echo '<div class="col-sm-1 text-center">';
				echo 'Cost';
			echo '</div>';
			echo '<div class="col-sm-1 text-right">';
				echo 'Options';
			echo '</div>';
		echo '</b></div>';
		$incomplete = 0;
		$complete = 0;
		foreach($results as $key => $val) {
			echo '<div class="page-results">';
				if($val['complete'] == 'y') {
					echo '<div class="success">';
					$complete++;
				} else {
					echo '<div class="danger">';
					$incomplete++;
				}
					echo '<div class="row">';
						echo '<div class="col-sm-2">';
							if($val['complete'] == 'y') {
								echo 'Complete';
							} else {
								echo 'Incomplete';
							}
						echo '</div>';
						echo '<div class="col-sm-2">';
							echo date('m-d-Y', strtotime($val['submitted']));
						echo '</div>';
						echo '<div class="col-sm-3">';
							if(!empty($val['address'])) {
								echo $val['address'];
							}
						echo '</div>';
						echo '<div class="col-sm-3">';
							if($val['service_type'] == 'Drain Cleaning (Clogged Drain)') {
								echo 'Drain Cleaning';
							} else {
								echo $services_array[$val['service_type']];
							}
						echo '</div>';
						echo '<div class="col-sm-1 text-center">';
							if(empty($val['cost'])) {
								echo '$0';
							} else {
								$cost[] = (int)$val['cost'];
								echo '$'.$val['cost'];
							}
						echo '</div>';
						echo '<div class="col-sm-1 text-right">';
							echo '<a href="'.base_url().'landlords/view-service-request/'.$val['id'].'" class="btn btn-xs btn-primary toolTips" title="View Request"><i class="fa fa-info-circle"></i></a>';
						echo '</div>';
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
<div style="height: 20px"></div>
<?php if(!empty($results) AND empty($sorted)) { ?>
<div class="row">
	<div class="col-sm-2">
		<div class="success text-center" style="padding: 3px">
			<b><h4><?php echo $complete; ?></h4>Complete</b>
		</div>
	</div>
	<div class="col-sm-2">
		<div class="danger text-center" style="padding: 3px">
			<b><h4><?php echo $incomplete; ?></h4>Incomplete</b>
		</div>
	</div>
	<div class="col-md-3 col-md-offset-5">
		<?php if(!empty($cost)) { ?>
			<div class="row"><div class="col-sm-10 text-right">Total Cost <b>$<?php echo number_format(array_sum($cost), 2); ?></b></div></div>
		<?php } ?>
	</div>
</div>
<?php } ?>