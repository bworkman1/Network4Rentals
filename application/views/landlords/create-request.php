<h2><i class="fa fa-wrench text-primary"></i> Add A Service Request</h2>
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
<div class="row">
	<?php 
		if(!empty($switches)) {
			echo '<div class="col-md-4">';
			echo '<label><span class="text-danger">*</span> <b>Select A Property Manager:</b></label>';
			echo form_open('landlords/switch-admin-group'); 
			echo '<select class="form-control input-sm groupPicker" name="admin" onchange="this.form.submit()" required="required">';
				echo '<option value="0">Add To My Account</option>';
				for($i=0;$i<count($switches);$i++) {
					if($this->session->userdata('temp_id') == $switches[$i]['id']) {
						echo '<option value="'.$switches[$i]['id'].'" selected="selected">'.$switches[$i]['sub_b_name'].'</option>';
					} else {
						echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
					}
				}
			echo '</select>';
			echo form_close();
			echo '</div>';
		}
	?>
	<div class="col-md-4">
		<label><span class="text-danger">*</span> <b>Select The Property:</b></label>
		<select class="form-control propertySelect input-sm" required="required">
			<option value="">Select One</option>
			<?php
				foreach($properties as $val) {
					echo '<option value="'.$val['id'].'">'.$val['address'].' '.$val['city'].' '.$val['stateAbv'].'</option>';
				}
			?>
		</select>
	</div>
	<div class="col-sm-4">
		<br>
		<div class='thinking'></div>
	</div>
</div>
<br>
<p>If you don't see your property above its because you have not added it to "My Properties" yet. Once you add it into your properties it will show up in the box above.</p>

<hr>
<div class="fade hide-form">
	<?php echo form_open_multipart('landlords/add-service-request'); ?>
		<h3><span class='text-danger'><i class="fa fa-asterisk"></i></span> Select Type of Request</h3>
		<div class="row">
			<div class="col-md-6">
				<div class="checkbox">
					<label>
						<input type="radio" class="occuranceType" required name="reoccurring" value="y"> Reoccurring Preventive Maintenance
					</label>
				</div>
			</div>
			<div class="col-md-6">
				<div class="checkbox">
					<label>
						<input type="radio" class="occuranceType" required name="reoccurring" value="n"> One Time Service Request
					</label>
				</div>
			</div>
		</div>
		<hr>
		<div class='well'>
			<div class='row'>	
				<div class='col-md-6'>
					<label>Address:</label>
					<input type='text' name='address' value='' class='form-control address' required="required" readonly="readonly"/>
					<label>City:</label>
					<input type='text' name='city' value='' class='form-control city' required="required" readonly="readonly"/>
					<label>State:</label>
					<input type='text' name='state' value='' class='form-control state' required="required" readonly="readonly"/>
					<label>Zip:</label>
					<input type='text' name='zip' value='' class='form-control zip' required="required" readonly="readonly"/>
				</div>
				<div class='col-md-6'>
					<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span>Type Of Service:</label>
					<?php 
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other'); 
						echo "<select name='serviceType' id='serviceType' class='form-control' required='required'>";
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
					<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span>Description (Be As Detailed As Possible):</label>
					<textarea style='height: 160px' name='desc' maxlength="500" class='form-control' required="required"><?php echo $_POST['desc']; ?></textarea>
					<div class="row">
						<div class="col-md-6">
							<div id="reoccurringDate">
							
							</div>
						</div>
						<div class="col-md-6">
							<div id="interval">
								
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
			

		<hr>
		<div class='row'>
			<div class='col-lg-12 renterUpload'>
				<div class='well'>
					<legend><i class="fa fa-picture-o"></i> Add An Image:</legend>
					<p class='small italics'> You can include one image to send to your landlord. <br /> Only image files are accepted (gif, jpeg, jpg, png, JPG, JPEG) </p>
					<label>Add Image</label>
					<div class='row'>
						<div class='col-md-6'>
							<input type='file' name='file' class="form-control attachment-img">
						</div>
					</div>
				</div>
				<div class='spacing10'></div>
				<button type='submit' name='submit' class='btn btn-primary btn-sm'><i class="fa fa-share"></i> Submit Request</button>
			</div>
		</div>
		<input type="hidden" name="rental_id" class="rental_id">
		<input type="hidden" name="group_id" class="group_id">
	</form>
</div>