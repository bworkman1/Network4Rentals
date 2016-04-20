<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($success)) {
		echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$success.'</div>';
	}
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><b><i class="fa fa-check-circle fa-lg"></i> Error:</b> '.$error.'</div>';
	}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<button data-toggle="modal" data-target="#addItems" class="btn btn-success pull-right"><i class="fa fa-plus"></i></button>
		<h3 class="panel-title" style="line-height: 2em"><i class="fa fa-plus"></i> Items Added To Property</h3>
	</div>
	<div class="panel-body">
		<h4><b>Property Address:</b> <?php echo $rental->address.', '.$rental->city.' '.$rental->stateAbv; ?></h4>
		<p>Attaching an item to this home will help your repair man in determining which parts they may need for a job. A example of this would be if you attached a refrigerator to this rental property and added all the information below. When you receive a service request from one of your tenants that has to do with appliances, at the bottom of your service request you will see a list of possible items that refer to appliances.</p>
		<hr>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				  <tr>
					<th>Image</th>
					<th>Item Name</th>
					<th>Brand</th>
					<th>Modal #</th>
					<th>Service Type</th>
					<th>Serial #</th>
					<th class="text-right">Options</th>
				  </tr>
				</thead>
				<?php 
					$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
					$property_id = $this->uri->segment(3);
					foreach($items as $row) { 
						if(!empty($row['image'])) {
							$img = '<a href="'.base_url($row['image']).'" data-group="gallery" class="lightbox" title="'.$row['desc'].'"><img src="'.base_url($row['image']).'" data-group="gallery" class="img-responsive" height="40" width="40"></a>';
						} else {
							$img = '';
						}
						echo '<tr>';
							
							echo '<th style="line-height: 50px">'. $img .'</th>';
							echo '<th style="line-height: 50px">'. $row['desc'] .'</th>';
							echo '<th style="line-height: 50px">'. $row['brand'] .'</th>';
							echo '<th style="line-height: 50px">'. $row['modal_num'] .'</th>';
							echo '<th style="line-height: 50px">'. $services_array[$row['service_type']] .'</th>';
							echo '<th style="line-height: 50px">'. $row['serial'] .'</th>';
							echo '<th class="text-right" style="line-height: 50px"><button class="editRentalItem btn btn-primary" data-target="#editItem" data-toggle="modal" data-id="'.$row['id'].'"><i class="fa fa-pencil fa-sm"></i></button> <a href="'.base_url('landlords/delete-rental-item/'.$property_id.'/'.$row['id']).'" class="btn btn-danger"><i class="fa fa-times fa-sm"></i></a></th>';
						echo '</tr>';
					} 
				?>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="addItems" tabindex="-1" role="dialog" aria-labelledby="addItems" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/add-rental-item'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-link text-primary"></i> Edit Item</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<label><span class="text-danger">*</span> Item Name:</label>
						<input type="text" class="form-control" maxlength="50" name="desc" placeholder="Example: Refrigerator">
						<label>Modal #:</label>
						<input type="text" class="form-control" maxlength="50" name="modal_num">
						<label>Serial #:</label>
						<input type="text" class="form-control" maxlength="50" name="serial">
					</div>
					<div class="col-sm-6">
						<label>Brand:</label>
						<input type="text" class="form-control" maxlength="50" name="brand">
						<label><span class="text-danger">*</span> Service Type:</label>
						<?php 
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
							echo "<select name='service_type' id='serviceType' class='form-control' required='required'>";
							echo '<option value="">Choose One...</option>';
							foreach($services_array as $key => $val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							echo "</select>";
						?>
						<label>Service Type: <small>(jpg, jpeg, png)</label>
						<input type="file" name="img" class="form-control">
						
						<input type="hidden" name="id" class="form-control" value="<?php echo $this->uri->segment(3); ?>">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Add Item</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="editItem" tabindex="-1" role="dialog" aria-labelledby="editItem" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php echo form_open_multipart(); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-link text-primary"></i> Attach Item An Item To This Home</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<label><span class="text-danger">*</span> Item Name:</label>
						<input type="text" class="form-control" maxlength="50" name="desc" placeholder="Example: Refrigerator">
						<label>Modal #:</label>
						<input type="text" class="form-control" maxlength="50" name="modal_num">
						<label>Serial #:</label>
						<input type="text" class="form-control" maxlength="50" name="serial">
					</div>
					<div class="col-sm-6">
						<label>Brand:</label>
						<input type="text" class="form-control" maxlength="50" name="brand">
						<label><span class="text-danger">*</span> Service Type:</label>
						<?php 
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
							echo "<select name='service_type' id='serviceType' class='form-control' required='required'>";
							echo '<option value="">Choose One...</option>';
							foreach($services_array as $key => $val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							echo "</select>";
						?>
						<label>Service Type: <small>(jpg, jpeg, png)</label>
						<input type="file" name="img" class="form-control">
						
						<input type="hidden" name="id" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Add Item</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>