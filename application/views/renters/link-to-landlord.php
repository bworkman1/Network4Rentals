<h2><i class="fa fa-plus text-warning"></i> Add Your Landlord</h2>
<p>Adding a landlord allows you to track your rental history, add payments, and find rental history information easily.</p>
<hr>
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
?>
<h4><i class="fa fa-user"></i> Landlords Info</h4>
<hr>
<div id="addLandlord"> 
	<div class="row">
		<div class="col-sm-6">
			<div class="landlord-info">
				<b>Business Name:</b><br>
				<?php echo $landlord_info->bName; ?><br>
			</div>
			<div class="landlord-info">
				<b>Name:</b><br>
				<?php echo $landlord_info->name; ?><br>	
			</div>
			<div class="landlord-info">
				<b>Phone:</b><br>
				<?php echo $landlord_info->phone; ?><br>		
			</div>
		</div><!-- end left side -->
		<div class="col-sm-6">
			<div class="landlord-info">
				<b>Street Address:</b><br>
				<?php echo $landlord_info->address; ?><br>
			</div>
			<div class="landlord-info">
				<b>Street Address:</b><br>
				<?php echo $landlord_info->city; ?><br>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="landlord-info">
						<b>State:</b><br>
						<?php echo $landlord_info->state; ?><br>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="landlord-info">
						<b>Zip:</b><br>
						<?php echo $landlord_info->zip; ?><br>
					</div>
				</div>
			</div>
		</div><!-- end right side -->
	</div>
</div>
<hr>
<?php if ($properties!=false) { ?>
<div class="well well-sm">
<div class="row">
	<div class="col-sm-6">
		<h4><i class="fa fa-home"></i> Select Your Address</h4>
	</div>
	<div class="col-sm-6">
		<select class="form-control propertySelect">
			<option>Select One...</option>
			<?php
				foreach($properties as $key => $val) {
					echo '<option value="'.$val->id.'">'.$val->address.', '.$val->city.' '.$val->stateAbv.'</option>';	
				}
			?>
		</select>
	</div>
</div>
</div>
<?php } ?>

<?php echo form_open_multipart('renters/link_submitted_landlord/'.$this->uri->segment(3)); ?>
	<h4><i class="fa fa-home"></i> Rental Home Details</h4>
	<hr>
	<p><span class="text-danger">*</span> If selected address values are off by a little, once you link to your landlord they will have the option to edit this information to match your lease.</p>
	<div class="row">
		<div class="col-sm-6">
			<input type="hidden" name="check_for_sub" value="<?php echo $landlord_info->id; ?>">
			<input type="hidden" id="link_id" name="rental_id" value="<?php echo $landlord_info->id; ?>">
			<label><i class="fa fa-asterisk text-danger"></i> Street Address:</label>
			<input type="text" id="address" class="form-control" name="rental_address" maxlength="100" required>
			<label><i class="fa fa-asterisk text-danger"></i> City:</label>
			<input type="text" id="city" class="form-control" name="rental_city" maxlength="60" required>
			<div class="row">
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> State:</label>
					<?php
						$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
						echo '<select name="rental_state" id="state" class="form-control" required>';
							echo '<option value="">Select One...</option>';
							foreach($states as $key => $val) {
								if($key == $state) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
						echo '</select>';
					?>
				</div>
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> Zip:</label>
					<input type="text" id="zip" class="form-control" name="rental_zip" maxlength="60" required>
				</div>
			</div>
			
		</div><!-- END LEFT SIDE -->
		<div class="col-sm-6">
			<div class="row">
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> Move In Date:</label>
					<input type="text" name="move_in" value="" id="city" maxlength="60" class="form-control datepicker" autocomplete="off" required="">
					<label><i class="fa fa-asterisk text-danger"></i> Rent Per Month:</label>
					<input type="text" name="payments" id="rent" maxlength="60" class="form-control numbersOnly" required="">
					<label><i class="fa fa-asterisk text-danger"></i> Deposit</label>
					<input type="text" id="deposit" class="form-control numbersOnly" name="deposit">
				</div>	
				<div class="col-sm-6">
					<label>Move Out Date:</label>
					<input type="text" name="move_out" value="" id="move_out" maxlength="60" autocomplete="off" class="form-control datepicker">
					<label><i class="fa fa-asterisk text-danger"></i> Lease Length:</label>
					<select class="form-control" name="lease" required="required">
						<option value="">Select One..</option> 
						<option>Month To Month</option>
						<option>3 Months</option>
						<option>6 Months</option>
						<option>9 Months</option>
						<option>1 Year</option>
						<option>2 Year</option>
						<option>3 Year</option>
					</select>
				</div>
			</div>
		</div><!-- END RIGHT SIDE -->
		
	</div>
	<div class="row">
		<div class="col-sm-6">
			<label>Upload Lease (Allowed Types: gif | jpg | png | jpeg | pdf | doc)</label>
			<input type="file" class="form-control attachment" name="file" size="20" />
		</div>
	</div>
	<br>
	<button class="btn btn-warning btn-sm saveLandlord" type="submit"><i class="fa fa-save"></i> Save Landlord</button>

