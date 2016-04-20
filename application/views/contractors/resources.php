<h2><i class="fa fa-info text-primary"></i> Resources</h2>
<hr>
<p>Below you will find various resources that contractors find useful. This list will be updated frequently so if you don't find something you like at first, keep checking back to see if new additions have been made.</p>
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
				<?php echo form_open('contractor/supply-house-search'); ?>
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

<a href="https://network4rentals.com/terms-of-service/" target="_blank" class="btn btn-primary"><i class="fa fa-file-o"></i> Terms Of Service</a>
