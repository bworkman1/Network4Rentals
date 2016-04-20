<?php
	if(!empty($feedback['success'])) {
		echo '<div class="alert alert-success"><b>Success:</b> '.$feedback['success'].'</div>';
	} elseif(!empty($feedback['error'])) {
		echo '<div class="alert alert-danger"><b>Error:</b> '.$feedback['error'].'</div>';
	}
?>
<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-search"></i> In Search Of
	</div>
	<div class="panel-body">
		<h3 class="pull-left"><i class="fa fa-home text-warning"></i> Property Details</h3>
		<a href="<?php echo base_url('renters/delete-iso'); ?>" class="pull-right btn btn-primary"><i class="fa fa-times"></i> Delete Search</a>
		<div class="clearfix"></div>
		<p>Are you looking for a property but keep missing the house you need. Fill out the form below and you will be notified via email with any properties that match your needs.</p>
		<hr>
		<?php echo form_open('renters/in-search-of'); ?>
			<fieldset>
				<!-- Select Basic -->	
				<div class="row">
					<div class="col-sm-8">
						<div class="row">
							<div class="col-sm-6">
								<label class="text-right" for="bedrooms"> Bedrooms</label>
								<select id="bedrooms" name="bedrooms" class="form-control">
									<option value="">Select One</option>
									<?php
										for($i=0;$i<9;$i++) {
											if($info[0]->bedrooms == $i) {
												echo '<option value="'.$i.'" selected>'.$i.'</option>';
											} else {
												echo '<option value="'.$i.'">'.$i.'</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="col-sm-6">
								<label class="text-right" for="Bathrooms">Bathrooms</label>
								<select id="Bathrooms" name="bathrooms" class="form-control">
									
									<option value="">...</option>
									<?php
										$bath_options = array(1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5);
										foreach($bath_options as $val) {
											if($val == $_POST['bathrooms']) {
												echo '<option selected>'.$val.'</option>';
											} else {
												echo '<option>'.$val.'</option>';
											}
										}
									?>
							
						
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">

								<label class="text-right" for="rentFrom">Rent From</label>
								<input id="rentFrom" name="rentFrom" type="text" placeholder="100" class="form-control input-md" value="<?php echo $info[0]->rentFrom; ?>" required="" />
							</div>
							<div class="col-sm-6">
								<label class="text-right" for="rentTo">Rent To</label>
								<input id="rentTo" value="<?php echo $info[0]->rentTo; ?>" name="rentTo" placeholder="700" type="text" class="form-control input-md" required="" />
							</div>
						</div>
					</div>
					<div id="zipCodesSelection" class="col-sm-4">
						<label class="control-label" for="zips">Zip Codes</label>
						<input name="zip" id="tags" class="form-control" value="<?php echo $info[0]->zip; ?>">
						<small>Enter a zip code and press tab or enter and a green box will form around the zip. If a duplicate is added a red box will appear and not allow you to add more.</small>
					</div>
				</div>
				<br><hr />
				
				<h3><i class="fa fa-list text-warning"></i> Amenities</h3>
				<hr>
				<div class="form-group isoBox">
					<?php echo $table; ?>	
				</div>
				<button class="btn btn-primary" type="submit">Submit</button>
			</fieldset>
		<?php echo form_close(); ?>
	</div>
</div>



