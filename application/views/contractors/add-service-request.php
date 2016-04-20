<?php	
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><b>Success:</b>'.$this->session->flashdata('success').'</div>';
	}
		
?>
<div class="panel panel-success">	
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-wrench"></i> Add A Service Request</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open_multipart('contractor/submit-request', array('id'=>'addRequest')); ?>
			<div class='well'>
				<legend><i class="fa fa-home"></i> Service Details:</legend>
				<div class='row'>	
					<div class='col-md-6'>
						<label><span class="text-danger">*</span> Service Address:</label>
						<input type='text' id="service-address" maxlength="40" name='address' value='' class='form-control address' required="required"/>
						<label><span class="text-danger">*</span> Service City:</label>
						<input type='text' id="service-city" maxlength="40" name='city' value='' class='form-control city' required="required"/>
						<div class="row">
							<div class="col-md-6">
								<label><span class="text-danger">*</span> Service State:</label>
								<?php
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<select id="service-state" name="state" class="form-control" required>';
									echo '<option value="">Select One...</option>';
									if(empty($_POST['state'])) {
										$state = '';
									} else {
										$state = $_POST['state'];
									}
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
							<div class="col-md-6">
								<label><span class="text-danger">*</span> Service Zip:</label>
								<input type='text' id="service-zip" maxlength="5" name='zip' value='' class='form-control zip' required="required"/>
							</div>
						</div>
					</div>
					<div class='col-md-6'>
						<label><span class="text-danger">*</span> Type Of Service:</label>
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
						<label><span class="text-danger">*</span> Description (Be As Detailed As Possible):</label>
						<textarea id="description" style='height: 160px' maxlength="500" name='desc' maxlength="500" class='form-control' required="required"><?php echo $_POST['desc']; ?></textarea>
					</div>
				</div>
			</div>
				

			<hr>
			<div class='row'>
				<div class="col-md-6">
					<div class="well">
						<legend><i class="fa fa-user"></i> Contact Details:</legend>
						<label>Owner Name:</label>
						<input type='text' id="contact-name" maxlength="40" name='name' value='' class='form-control'/>
						<label>Owner Phone:</label>
						<input type='text' id="contact-phone" maxlength="14" name='phone' value='' class='form-control'/>
						<label>Owner Email:</label>
						<input type='email' id="contact-email" maxlength="50" name='email' value='' class='form-control'/>
					</div>
				</div>
				<div class='col-md-6 renterUpload'>
					<div class='well'>
						<legend><i class="fa fa-picture-o"></i> Add An Image:</legend>
						<p class='small italics'> You can include one image to send to your landlord. <br /> Only image files are accepted (gif, jpeg, jpg, png, JPG, JPEG) </p>
						<label>Add Image</label>
						<input type='file' name='file' id="attachment" class="form-control attachment-img">
					</div>
					<div class='spacing10'></div>
					<div class="well">
						<legend><i class="fa fa-calendar-o"></i> Schedule Service Request</legend>
						<div class="checkbox">
							<label><input type="checkbox" id="schedule"> Yes add this to my schedule</label>
						</div>
					</div>
				</div>
			</div>
			
			<div class="well scheduleIn displayNone">
				<legend><i class="fa fa-calendar-o"></i> Add To Schedule:</legend>
				<div class="row">
					<div class="col-md-6">
						<label><span class="text-danger">*</span> Start Date: <small>mm/dd/yyyy</small></label>
						<div class="row">
							<div class="col-xs-4">
								<input type="text" maxlength="10" name="startDate" id="createStartTask" class="form-control dateMask">
							</div>
							<div class="col-xs-4">
								<input type="text" maxlength="5" name="startTime" id="createTaskStartTime" class="form-control timeMask">
							</div>
							<div class="col-xs-4">
								<select id="startAm" name="startAm" class="form-control">
									<option value="">Select One</option>
									<option value="am">AM</option>
									<option value="pm">PM</option>
								</select>
							</div>
						</div>
					</div>
				
					<div class="col-md-6">
			
						<label><span class="text-danger">*</span> End Date: <small>mm/dd/yyyy</small></label>
						<div class="row">
							<div class="col-xs-4">
								<input type="text" maxlength="10" name="endDate" id="createEndTask" class="form-control dateMask">
							</div>
							<div class="col-xs-4">
								<input type="text" maxlength="5" name="endTime" id="createTaskEndTime" class="form-control timeMask">
							</div>
							<div class="col-xs-4">
								<select id="endAm" name="endAm" class="form-control">
									<option value="">Select One</option>
									<option value="am">AM</option>
									<option value="pm">PM</option>
								</select>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			
			<button type='submit' name='submit' class='submit btn btn-primary btn-sm'><i class="fa fa-share"></i> Add Request</button>
		</form>
	</div>
</div>