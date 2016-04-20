<div class="row">
	<div class="col-sm-8">
		<h2>
			<i class="fa fa-wrench text-primary"></i> Service Request
		</h2>
	</div>
	<div class="col-sm-4">
	
	</div>
</div>
<hr>
<?php	
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
		
?>
<div class="edit-account-options">
	<div class="row">
		<div class="col-md-3 col-xs-6">
			<a href="<?php echo base_url(); ?>landlords/all-service-requests">
				<div class="account-option">
					<i class="fa fa-eye"></i>
					<h4>View All Request</h4>			
				</div>	
			</a>
		</div>
		<div class="col-md-3 col-xs-6">
			<a href="<?php echo base_url(); ?>landlords/all-complete-service-requests">
				<div class="account-option">
					<i class="fa fa-check-square-o"></i>
					<h4>View Completed Requests</h4>		
				</div>	
			</a>
		</div>
		<div class="col-md-3 col-xs-6">
			<a href="<?php echo base_url(); ?>landlords/all-incomplete-service-requests">
				<div class="account-option">
					<i class="fa fa-square-o"></i>
					<h4>View Incomplete Requests</h4>		
				</div>	
			</a>
		</div>	
		<div class="col-md-3 col-xs-6">
			<a href="<?php echo base_url(); ?>landlords/add-service-request">
				<div class="account-option">
					<i class="fa fa-plus"></i>
					<h4>Add Request</h4>		
				</div>	
			</a>
		</div>
	</div>
	<div class="row">	
		<div class="col-md-3 col-xs-6">
			<a href="#" data-toggle="modal" data-target="#searchRequest">
				<div class="account-option">
					<i class="fa fa-search"></i>
					<h4>Search Request</h4>
				</div>	
			</a>
		</div>
		<?php if($new_requests>0) { ?>
			<div class="col-md-3 col-xs-6">
				<a href="<?php echo base_url(); ?>landlords/new-service-requests">
					<div class="account-option">
						<i class="fa fa-envelope-o"></i>
						<h4><span class="label label-primary"><?php echo $new_requests; ?></span> New Request</h4>
					</div>
				</a>
			</div>
		<?php } ?>
		<div class="col-md-3 col-xs-6">
			<a href="<?php echo base_url('landlords/reoccurring-preventive-maintenance'); ?>">
				<div class="account-option">
					<img src="<?php echo base_url('assets/themes/default/images/pm-icon.JPG'); ?>" class="img-center img-responsive" width="82" height="82" alt="PM" style="margin: 0 auto">
					<h4>Reoccurring PM's</h4>
				</div>	
			</a>
		</div>
	</div>
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchRequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('landlords/search-requests'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-search text-primary"></i> Search Service Request</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<label>Start Date:</label>
						<input type="text" autocomplete="off" name="start_date" class="form-control datepicker">
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
					<div class="col-sm-6">
						<label>End Date:</label>
						<input type="text" autocomplete="off" name="end_date" class="form-control datepicker">
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
				
				
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>