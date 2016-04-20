<h2><i class="fa fa-info text-primary"></i> Resources</h2>
<hr>
<p>Below you will find various resources that landlord find useful. This list will be updated frequently so if you don't find something you like at first, keep checking back to see if new additions have been made.</p>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
?>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-magic"></i> Tenant Magic | Tenant Screening Tool</h3>
			</div>
			<div class="panel-body text-center">
				<img src="<?php echo base_url(); ?>public-images/tenantmagic.jpg" style="margin: 0 auto;" class="img-responsive">
				<p>Need a tenant screening company? TenantMagic offers a unique method of screening your potential tenants that will allow you to save time on the interview process and avoid negatively effecting the applicants credit.</p>
				<hr>
				<a href="http://tenantmagic.net/network4rentals/" class="btn btn-primary btn-sm">Learn More</a>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-search"></i> Search For A Contractor In Your Area</h3>
			</div>
			<div class="panel-body">
				<br>
				<?php echo form_open('landlords/contractor-search'); ?>
					<div class="row">
						<div class="col-sm-3 text-right">
							<label><span class="text-danger">*</span> Zip:</label>
						</div>
						<div class="col-sm-4">
							<input type="text" name="zip" class="form-control" maxlength="5">
						</div>
						<div class="col-sm-5">
							<select name="radius" class="form-control">
								<option value="5">5 Mile Radius</option>
								<option value="10">10 Mile Radius</option>
								<option value="20">20 Mile Radius</option>
								<option value="30">30 Mile Radius</option>
								<option value="50">50 Mile Radius</option>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-3 text-right">
							<label><span class="text-danger">*</span> Service:</label>
						</div>
						<div class="col-sm-9">
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
								echo "<select name='serviceType' id='serviceType' class='form-control' required='required'>";
								echo '<option value="">Choose One...</option>';
								foreach($services_array as $key => $val) {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
								echo "</select>";
							?>
						</div>
					</div>
					
					<hr>
					<button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> Search</button>
				<?php echo form_close(); ?>
			</div>
		</div>
	
		
	</div>
</div>

<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-magic"></i> Law Depot Legal Docs</h3>
			</div>
			<div class="panel-body text-center">
				<a href="http://www.lawdepot.com/?pid=pg-7GBWTND5K8-LD_generic_336x280.jpg"><img src="<?php echo base_url('assets/themes/default/images/LD_generic_336x280.jpg'); ?>" class="img-responsive" style="width:336px;height:280px;border:0" /></a>
			</div>
		</div>
	</div>
	
	<div class="col-sm-6">
		
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-building"></i> Search For a Supply House</h3>
			</div>
			<div class="panel-body">
				<br>
				<?php echo form_open('landlords/supply-house-search'); ?>
					<div class="row">
						<div class="col-sm-3 text-right">
							<label><span class="text-danger">*</span> Zip:</label>
						</div>
						<div class="col-sm-4">
							<input type="text" name="zip" class="form-control" maxlength="5">
						</div>
						<div class="col-sm-5">
							<select name="radius" class="form-control">
								<option value="5">5 Mile Radius</option>
								<option value="10">10 Mile Radius</option>
								<option value="20">20 Mile Radius</option>
								<option value="30">30 Mile Radius</option>
								<option value="50">50 Mile Radius</option>
							</select>
						</div>
					</div>
		
					
					<hr>
					<button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> Search</button>
				<?php echo form_close(); ?>
			</div>
		</div>
		
	</div>
	
</div>

<a href="https://network4rentals.com/network/landlords/terms-of-service" class="btn btn-primary"><i class="fa fa-file-o"></i> Terms Of Service</a>
