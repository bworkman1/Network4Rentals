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
<div class="progress" id="progress1">
	<div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
	</div>
	<span class="progress-type">Overall Progress</span>
	<span class="progress-completed">0%</span>
</div>
<div class="row">
	<div class="col-sm-12">
		<div class="step top-steps row">
			<div id="div1" class="col-xs-2 gotoStep activestep" data-arg1="event" data-arg2="0" data-arg3="step-1" >
				<span class="fa fa-user"></span>
				<p>User Info</p>
			</div>
			<div id="div2" class="col-xs-2 gotoStep" data-arg1="event" data-arg2="25" data-arg3="step-2" >
				<span class="fa fa-search"></span>
				<p>Search Landlord</p>
			</div>
			<div id="div3" class="col-xs-2 gotoStep" data-arg1="event" data-arg2="50" data-arg3="step-3">
				<span class="fa fa-user"></span>
				<p>Landlord Info</p>
			</div>
			<div id="div4" class="col-xs-2 gotoStep" data-arg1="event" data-arg2="75" data-arg3="step-4">
				<span class="fa fa-home"></span>
				<p>Rental Details</p>
			</div>
			<div id="div5" class="col-xs-2 gotoStep" data-arg1="event" data-arg2="100" data-arg3="step-5">
				<span class="fa fa-check"></span>
				<p>Confirm</p>
			</div>
		</div>
	</div>
</div>
<?php echo form_open_multipart('renters/create-account', array('id' => 'createAccount')); ?>
<div class="row setup-content step activeStepInfo" id="step-1">
	<div class="col-md-12 text-left">
		<h3 class="pull-left"><i class="fa fa-search text-warning"></i> Create Your Free Account</h3>
		<button class="btn btn-warning pull-right" data-toggle="modal" data-target="#helpvideo">Need Help?</button>
		<div class="clearfix"></div>
			<hr>
			<p>The first thing you will need to do is add your personal details below.</p>
			<div class="row">
				<div class="col-lg-12">
					<fieldset>
						<legend><i class="fa fa-user"></i> Create Account:</legend>
					</fieldset>
				</div>
				<div class="col-sm-6">
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Full Name:</label>
						<input type="text" name="fullname" maxlength="40" id="fullname"  class="form-control" value="<?php echo set_value('fullname'); ?>" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Email:</label>
						<input type="text" name="email"  id="email" maxlength="100" class="form-control" value="<?php echo set_value('email'); ?>" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Phone:</label>
						<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" id="phone" maxlength="15" class="form-control phone" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> How Did You Hear About Us?</label>
						<select name="hear" id="hear" class="form-control" required>
							<option value="">Select One...</option>
							<option>Event or Booth</option>
							<option>Friends</option>
							<option>Family</option>
							<option>Online Search</option>
							<option>Literature (handouts, fliers, etc.</option>
							<option>Advertisement</option>
							<option>Facebook</option>
							<option>Google+</option>
							<option>Twitter</option>
							<option>Linkedin</option>
							<option>Landlord/Mgr. Request</option>
							<option>Other</option>
						</select>
						<div class="help-text text-danger"></div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Username:</label>
						<input type="text" name="username" id="username" maxlength="100"  class="checkUsername form-control" required="" placeholder="Must Be At Least 6 Characters" value="<?php echo set_value('username'); ?>">
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Password:</label>
						<input type="text" name="password" value="<?php echo set_value('password'); ?>" id="password" maxlength="100" class="form-control" placeholder="Must Be At Least 6 Characters" required> 
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Confirm Password:</label>
						
						<input type="text" name="password1" value="<?php echo set_value('password1'); ?>" id="password1" maxlength="100"  class="form-control" placeholder="Must Be At Least 6 Characters" required>
						
						<div class="help-text text-danger"></div>
					</div>
			
					
			
					<div class="row">
						<div class="col-md-8">
							<div class="input-group">
								<label>  
									<i class="fa fa-asterisk text-danger"></i> I Agree To The 
									<a href="https://network4rentals.com/network/renters/terms-of-service" target="_blank">Terms Of Services</a>
								</label>
								<br>
								<input type="checkbox" id="terms" name="terms" value="y" required> Yes
								<div class="help-text text-danger"></div>
							</div>
						</div>
						<div class="col-md-4">
							<label>I have a landlord?</label>
							<select class="landlordSelect form-control">
								<option value="y">Yes</option>
								<option value="n">No</option>
							</select>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="well">
						<div class="row">
							<div class="col-sm-6">
								<label><i class="fa fa-asterisk text-danger"></i> Receive Text Messages:</label>
								<select class="form-control textMessages" name="sms_msgs" required>
									<?php
										$options = array('n'=>'No', 'y'=>'Yes');
										foreach($options as $key => $val) {
											if($key == $_POST['sms_msgs']) {
												echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$val.'</option>';
											}
										}
									?>
								</select>
							</div>
							<div class="col-sm-6 textMessagePhoneNumber fade">
								<label><i class="fa fa-asterisk text-danger"></i> Cell Phone Number:</label>
								<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $_POST['cell_phone']; ?>">
							</div>
						</div>
					</div>
			<div id="ajaxError" class="text-danger"></div>
			<div class="text-right">
				<button class="btn btn-warning btn-sm stepProceed2" data-arg1="event" data-arg2="25" data-arg3="step-2"> Next Step</button>
			</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-2">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<h3><i class="fa fa-search text-warning"></i> Search For Your Landlord</h3>
			<hr>
			<p>Search for you landlord by business name or by full name. Once you start typing it will pull up landlords close to what you searched for. Finding the right landlord is important for your communication tools to work right so be sure try everything.</p>
			<div class="row">
				<div class="col-sm-6">
					<input type="text" class="form-control input-md" id="landlord-search-step" value="<?php echo get_cookie('rental'); ?>" placeholder="Start Typing To Search 3 Characters Min">
				</div>
				<div class="col-sm-6">
					<div class="thinking"></div>
				</div>
			</div>
			<div id="landlord-results"></div>
			<div style="height: 100px"></div>
			<div class="no-landlord">
				<span class="squaredThree">
					<input type="checkbox" value="None" id="squaredThree" />
					<label for="squaredThree"></label>
				</span>
				<span class="optOut">I can't find my landlord</span>
			</div>
			<div class="text-right">
				<button class="btn btn-warning btn-sm stepProceed3" data-arg1="event" data-arg2="50" data-arg3="step-3"> Next Step</button>
			</div>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-3">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div id="addLandlord"> 
				<h3><i class="fa fa-user text-warning"></i> Landlord Details</h3>
				<p>If you found your landlord through the search look over everything and make sure its correct. If everything looks good continue to the next step.</p>
				<hr>
				<div class="row">
					<div class="col-sm-6">
						<div class="input-group">
							<label>Email:</label>
							<input type="text" class="form-control" maxlength="100" id="landlord_email" name="landlord_email" value="<?php echo set_value('landlord_email'); ?>">
							<div class="help-text text-danger"></div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="input-group">
							<label>Cell Phone:</label>
							<input type="text" name="landlord_cell" class="form-control phone" maxlength="18" id="landlord_cell" value="<?php echo set_value('landlord_cell'); ?>">
							<div class="help-text text-danger"></div>
						</div>
					</div>
				</div>
				<div class="row landlordDetails">
					<div class="col-sm-6">
						<div class="input-group">
							<label>Business Name:</label>
							<input type="text" name="bName" value="<?php echo set_value('bName'); ?>" id="bName" maxlength="100" class="form-control">
						</div>
						<div class="input-group">
							<label><i class="fa fa-asterisk text-danger"></i> Landlords/Contact Name:</label>
							<input type="text" name="landlord_name" value="<?php echo set_value('landlord_name'); ?>" id="landlord_name" maxlength="200" class="form-control" required="required">
							<div class="help-text text-danger"></div>
						</div>
						<div class="input-group">
							<label>Landlords/Contact Phone:</label>
							<input type="text" name="landlord_phone" value="<?php echo set_value('landlord_phone'); ?>" id="landlord_phone" maxlength="20" class="form-control phone">
							<div class="help-text text-danger"></div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="input-group">
							<label>Street Address:</label>
							<input type="text" name="landlord_address" value="<?php echo set_value('landlord_address'); ?>" id="landlord_address" maxlength="200" class="form-control">
							<div class="help-text text-danger"></div>
						</div>
						<div class="input-group">
							<label><i class="fa fa-asterisk text-danger"></i> City:</label>
							<input type="text" name="landlord_city" value="<?php echo set_value('landlord_city'); ?>" id="landlord_city" maxlength="60" class="form-control" required="required">
							<div class="help-text text-danger"></div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="input-group">
									<label><i class="fa fa-asterisk text-danger"></i> State:</label>
									<select id="landlord_state" name="landlord_state" class="form-control" required="">
								<?php
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<option value="">Select One...</option>';
									if(empty($_POST['landlord_state'])) {
										$state = '';
									} else {
										$state = $_POST['landlord_state'];
									}
									foreach($states as $key => $val) {
										if($key == $state) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
								?>
									</select>
									<div class="help-text text-danger"></div>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="input-group">
									<label><i class="fa fa-asterisk text-danger"></i> Zip:</label>
									<input type="text" name="landlord_zip" value="<?php echo set_value('landlord_zip'); ?>" id="landlord_zip" maxlength="5" class="form-control numbersOnly" required>
									<div class="help-text text-danger"></div>
								</div>
								<input type="hidden" id="landlord-id" value="<?php echo set_value('landlord-id'); ?>" name="link_id">
								<input type="hidden" id="group-id" value="<?php echo set_value('group_id'); ?>" name="group_id">
							</div>
						</div>
					</div>
				</div>
			</div>
			<br>
			<div class="text-right">
				<button class="btn btn-warning btn-sm stepProceed4" data-arg1="event" data-arg2="75" data-arg3="step-4"> Next Step</button>
			</div>			
		</div>
	</div> 
</div> 
<div class="row setup-content step hiddenStepInfo" id="step-4">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div class="row">
				<div class="col-sm-6">
					<h3><i class="fa fa-home text-warning"></i> Rental Home Details</h3>
					<p>Add your home details that match your lease.</p>
				</div>
				<div class="col-sm-6">
					<br>
					<div id="landlordProperties"></div>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> Street Address:</label>
					<input type="text" name="rental_address" value="<?php echo set_value('rental_address'); ?>" id="address" maxlength="200" class="form-control" required="">
					<label><i class="fa fa-asterisk text-danger"></i> City:</label>
					<input type="text" name="rental_city" value="<?php echo set_value('rental_city'); ?>" id="city" maxlength="60" class="form-control" required="">
					<div class="row">
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> State:</label>
							<select name="rental_state" id="rental_state" class="form-control" required="">
																<?php
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<option value="">Select One...</option>';
									if(empty($_POST['rental_state'])) {
										$state = '';
									} else {
										$state = $_POST['rental_state'];
									}
									foreach($states as $key => $val) {
										if($key == $state) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
								?>
							</select>
						</div>
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> Zip:</label>
							<input type="text" name="rental_zip" maxlength="5" id="zip" value="<?php echo set_value('rental_zip'); ?>" class="form-control numbersOnly" required>
						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="row">
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> Move In Date:</label>
							<input type="text" name="move_in" value="<?php echo set_value('move_in'); ?>" id="move_in" maxlength="15" class="form-control datepicker" autocomplete="off" required>
						</div>
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> Lease Length:</label>
							<select class="form-control" name="lease" required="required">
								<option value="">Select One..</option><option>Month To Month</option><option>3 Months</option><option>6 Months</option><option>9 Months</option><option>1 Year</option><option>2 Year</option><option>3 Year</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> Rent Per Month:</label>
							<input type="text" id="rent" name="payments" value="<?php echo set_value('payments'); ?>" maxlength="60" class="form-control money" required="">
						</div>
						<div class="col-sm-6">
							<label><i class="fa fa-asterisk text-danger"></i> Deposit</label>
							<input type="text" id="deposit" class="form-control money" name="deposit" value="<?php echo set_value('deposit'); ?>" required>
						</div>
					</div>
					
					<label><i class="fa fa-asterisk text-danger"></i> Day Rents Due On:</label>
					<div class="row">
						<div class="col-sm-4">
							<select class="form-control" name="day_rent_due">
								<?php
									for($i=0;$i<29; $i++) {
										echo '<option>'.($i+1).'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="res-error text-danger"></div>
				</div>
			</div>
			<div class="text-right">
				<button class="btn btn-warning btn-sm stepProceed5" data-arg1="event" data-arg2="100" data-arg3="step-5"> Next Step</button>
			</div>	
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-5">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<h3><i class="fa fa-check text-warning"></i> Confirm Details</h3>
			<p>Before you add this landlord look over the details and make sure everything is correct.</p>
			<hr>
			<h3>Account Details</h3>
			<ul id="user-details">
				
			</ul>
			<div class="row">
				<div class="col-sm-6">
					<ul id="landlord-list">
						
					</ul>
				</div>
				<div class="col-sm-6">
					<ul id="rental-list">
					
					</ul>
				</div>
			</div>
			<hr>
			<div class="text-right">
				<button type="submit" class="btn btn-warning btn-sm"> Submit</button>
			</div>
		</div>
	</div>
</div>

<?php echo form_close(); ?>

<div class="modal fade" id="suggestion-window" tabindex="-1" role="dialog" aria-labelledby="suggestion-window" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">Landlord Found</h4>
      </div>
      <div class="modal-body">
		<p>Based on the info you added it looks like your landlord is already part of the system and you don't have to add them manually. The information below is what we have for your current land landlord, please click the add landlord button below.</p>
        <ul id="email-suggestions">
			
		</ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="info" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-warning"></i> Important</h4>
			</div>
			<div class="modal-body">
				<p>In the first step when you were creating your account you selected that heard about us through your landlord. If you heard about us through your landlord you should be able to find them in the system. When Searching for your landlord try using just a first name or just a last name. If you know the business name try using only a few characters of the business name at a time.</p>
				<p>This step is important that we get it just right so if your not sure contact us or contact your landlord to find out the exact spelling of their business or name.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="landlordContactInfo" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-warning"></i> Important</h4>
			</div>
			<div class="modal-body">
				<p>In order to continue you must have a email address or cell phone number for your landlord so that the system can contact them as you make request in the system. On our end we will try to contact your landlord and explain how the website will help them stay organized.</p>
				<p>If you would like us to contact your landlord and inform them about the website and all the benefits that go with it contact us by clicking the button below.</p>
				<a href="https://network4rentals.com/help-support/" class="btn btn-warning" target="_blank"><i class="fa fa-envelope"></i> Contact Us</a>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="helpvideo" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-question text-warning"></i> How To Create Your Account</h4>
			</div>
			<div class="modal-body">
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<iframe width="560" height="315" src="//www.youtube.com/embed/rOdKwNMYZOc" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>