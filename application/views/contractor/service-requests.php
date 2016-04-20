<div class="row">
	<div class="col-sm-8">
		<h3><i class="fa fa-wrench text-success"></i> Service Request</h3>
	</div>
	<div class="col-sm-4">
		<br>
		<?php echo form_open('contractors/service-requests'); ?>
			<select class="form-control" name="complete" onchange="this.form.submit()">
				<?php
					$options = array('y'=>'Complete Service Requests', 'n'=>'Incomplete Service Requests');
					foreach($options as $key => $val) {
						if($this->session->userdata('ser_comp')==$key) {
							echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}
				?>
			</select>
		<?php echo form_close(); ?>
	</div>
</div>
<hr>
<p>Once you receive a forwarded service request from a landlord it will show up below.</p>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}

	if(!empty($requests)) {
		$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
		echo '<div class="row hidden-xs serviceRequestRow">';
			echo '<div class="col-xs-3">';
				echo '<b>Date:</b>';
			echo '</div>';
			echo '<div class="col-xs-3">';
				echo '<b>Landlord:</b>';
			echo '</div>';
			echo '<div class="col-xs-4">';
				echo '<b>Service Type:</b>';
			echo '</div>';
			echo '<div class="col-sm-2 text-center">';
				echo '<b>Options:</b>';
			echo '</div>';
		echo '</div>';		
		foreach($requests as $key => $val) {
			
			echo '<div class="row serviceRequestRow">';
				echo '<div class="col-sm-3">';
					echo date('m-d-Y', strtotime($val->submitted));
				echo '</div>';
				echo '<div class="col-sm-3">';
					echo $val->landlord_name;
				echo '</div>';
				echo '<div class="col-sm-4">';
					echo $services_array[$val->service_type];
				echo '</div>';
				echo '<div class="col-sm-2">';
					echo '<a class="btn btn-success btn-block btn-xs" href="'.base_url().'contractors/view-service-request/'.$val->id.'">View</a>';
				echo '</div>';
			echo '</div>';
		}
	
	
	}
?>
