<?php
	$price_array = $this->session->userdata('price');
	$total = 0;
	for($i=0;$i<count($price_array);$i++) {
		$total = $price_array[$i]+$total;
	}
?>
<h2><i class="text-success fa fa-user"></i> Create Your Account:</h2>
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

<div class="youtube_video">
	<div class="fluid-width-video-wrapper" style="padding-top: 50%;">
		<iframe width="560" height="315" src="//www.youtube.com/embed/uMfoQB3pg2k" frameborder="0" allowfullscreen></iframe>
	</div>
</div>
<br><br>
<div class="progress" id="progress1">
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
	</div>
	<span class="progress-type">Overall Progress</span>
	<span class="progress-completed">0%</span>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="step largeIcons">
			<div id="div1" class="col-md-3 activestep">
				<span class="fa fa-map-marker"></span>
				<p>Select Zip Codes</p>
			</div>
			<div id="div2" class="col-md-2">
				<span class="fa fa-user"></span>
				<p>User Info</p>
			</div>
			<div id="div3" class="col-md-2">
				<span class="fa fa-cog"></span>
				<p>User Account</p>
			</div>
			<div id="div4" class="col-md-2">
				<span class="fa fa-credit-card"></span>
				<p>Payment Info</p>
			</div>
			<div id="div5" class="col-md-3">
				<span class="fa fa-check"></span>
				<p>Confirm Details</p>
			</div>
		</div>
	</div>
</div>
<?php echo form_open('contractors/create-account', array('id'=>'createAccountForm')); ?>
<div class="row setup-content step activeStepInfo" id="step-1">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-map-marker text-success"></i> Select The Areas You Want To Advertise In</h3>
			<hr>
			<div class="row">
				<div class="col-sm-9">
					<p>Select the zip codes you would like to advertise in. There can only be three contractors per zip code so if you don't find your zip code its because its already taken.</p>
				</div>
				<div class="col-sm-3">
					<h5>Already Have An Account?</h5>
					<a href="<?php echo base_url(); ?>contractors/login" class="btn btn-sm btn-success btn-block"><i class="fa fa-lock"></i> Login</a>
				</div>
			</div>
			<hr>
			<label>Select Your Service And Zip Code And Click The Search Button</label>
			<div class="error-helper"></div>
			<div class="row">
				<div class="col-sm-4">
					<?php 
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
						echo "<select name='serviceType' tabindex='1'; id='serviceType' class='form-control input-md' required='required'>";
						//echo '<option value="">Choose One...</option>';
						foreach($services_array as $key => $val) {
							if(isset($_POST['serviceType'])) {
								if($_POST['serviceType'] == $key) {
									echo '<option selected="selected">'.$val.'</option>';
								}
							} else {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
						}
						echo "</select>";
					?>
				</div>
				<div class="col-sm-4">
					<div class="input-group">
						<input type="text" class="form-control zipSearch input-md" placeholder="Search&hellip;" maxlength="5" value="43055">
						<span class="input-group-btn">
							<button type="button" class="btn btn-warning btn-md searchZips" tabindex="2"><i class="fa fa-search"></i></button>
						</span>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-sm-12">
					<br>
					<div class="loading"></div>
				</div>
			</div>
			
			<br>
			<div class="zip-results">
				
			</div>
			<h3>Selected Zips:</h3><hr>
			<div class="row">
				<div class="zips_purchased">
					<?php
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
						$zips = $this->session->userdata('zips');
						$service = $this->session->userdata('service');
						$price = $this->session->userdata('price');
						$city = $this->session->userdata('city');
						$state = $this->session->userdata('state');
				
						if(!empty($zips)) {
							echo '<div class="col-sm-12"><div class="zipsAdded"></div></div>';
							for($i=0;$i<count($zips);$i++) {
							
								echo '<div id="'.$zips[$i].'-'.$service[$i].'" class="border-bottom selectedZips" data-dup="'.$zips[$i].'-'.$service[$i].'">';				
								echo '<div class="col-xs-2">'.$zips[$i].'</div>';
								echo '<div class="col-sm-3 hidden-xs">'.$city[$i].'</div>';
								echo '<div class="col-sm-1 hidden-xs">'.$state[$i].'</div>';
								echo '<div class="col-xs-4">'.$services_array[$service[$i]].'</div>';
								echo '<div class="col-xs-1 calculate" data-price="'.$price[$i].'">$'.$price[$i].'</div>';
								echo '<div class="col-xs-1"><button class="btn btn-xs btn-danger removeZip" data-remove="'.$zips[$i].'-'.$service[$i].'"><i class="fa fa-times"></i></button></div>';
								echo '<div class="clearfix"></div></div>';
							}
						}
					?>
				</div>
			</div>
			<br><br><!-- order details -->
			<div class="well orderDetails">
				<div class="row text-center">
					<div class="col-sm-3">Subscription Length</div>
					<div class="col-sm-3"> Subscription Total </div>
					<div class="col-sm-3"> Billing Cycle </div>
					<div class="col-sm-3"> Cost Per Bill</div>
				</div>
				<div class="row text-center">
					<div class="col-sm-3">1 Year</div>
					<div class="col-sm-3">$0</div>
					<div class="col-sm-3">Per Month</div>
					<div class="col-sm-3"><b>$0.00</b></div>
				</div>
			</div>
			<button class="btn btn-success btn-sm stepProceed2 steper" tabindex="3" onclick="javascript: resetActive(event, 25, 'step-2');"> Next Step</button>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-2">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-user text-success"></i> Business Info</h3>
			<hr>
			<label class="control-label" for="bName"><span class="text-danger">*</span> Business Name</label>
			<input id="bName" name="bName" tabindex="4" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="70"  value="<?php echo set_value('bName'); ?>">
			<div class="row">
				<div class="col-sm-6">
					<label class="control-label" for="first_name"><span class="text-danger">*</span> Contacts First Name</label>  
					<input id="first_name"  tabindex="5" name="first_name" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="50"  value="<?php echo set_value('first_name'); ?>">
					<label class="control-label" for="address"><span class="text-danger">*</span> Address</label>  
					<input id="address" tabindex="7"name="address" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="150" value="<?php echo set_value('address'); ?>">
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label" for="state"><span class="text-danger">*</span> State</label>  
							<?php
								$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
								echo '<select id="state" name="state" class="form-control" required="" tabindex="9">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									echo '<option value="'.$key.'" '.set_select('state', $key).'>'.$val.'</option>';	
								}
								echo '</select>';
							?>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="zip"><span class="text-danger">*</span> Zip</label> 
							<input id="zip" name="zip" type="text" placeholder="" autocomplete="off" class="form-control input-md numbersOnly" required="" maxlength="5" tabindex="10" value="<?php echo set_value('zip'); ?>">
						</div>
					</div>					
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="last_name"><span class="text-danger">*</span> Contacts Last Name</label>  
					<input id="last_name" name="last_name" tabindex="6" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="50" value="<?php echo set_value('last_name'); ?>">
					<label class="control-label" for="city"><span class="text-danger">*</span> City</label>  
					<input id="city" name="city" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="70" tabindex="8" value="<?php echo set_value('city'); ?>"> 	
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="phone"><span class="text-danger">*</span> Phone</label>  
							<input id="phone" autocomplete="off" tabindex="11" name="phone" type="text" placeholder="" class="form-control input-md phone" required="" value="<?php echo set_value('phone'); ?>">
						</div>
						<div class="col-sm-6">
							<label class="control-label" for="fax">Fax</label>  
							<input id="fax" name="fax" tabindex="12" type="text" autocomplete="off" placeholder="" class="form-control input-md phone" maxlength="20" value="<?php echo set_value('fax'); ?>">
						</div>
					</div>					
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-1">
					<label class="checkbox-inline" for="checkboxes-0">
						<input type="checkbox" id="billing-same" tabindex="13">
					</label>
				</div>
				<label class="col-md-5 control-label" for="checkboxes"><b>Billing Same As Home Address</b></label>
			</div>
			<legend>Billing Info</legend>
			<div class="row">
				<div class="col-sm-6">
					<label class="control-label" for="baddress"><span class="text-danger">*</span> Billing Address</label>  
					<input id="baddress" name="baddress" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="150" tabindex="14" value="<?php echo set_value('baddress'); ?>">
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="bcity"><span class="text-danger">*</span> City</label>  
							<input id="bcity"  tabindex="15" name="bcity" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="70" value="<?php echo set_value('bcity'); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label" for="bstate"><span class="text-danger">*</span> State</label>  
							<?php
								$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
								echo '<select tabindex="16"id="bstate" name="bstate" class="form-control" required="" tabindex="11">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									echo '<option selected="selected" '.set_select('bstate', $key).' value="'.$key.'">'.$val.'</option>';
								}
								echo '</select>';
							?>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="bzip"><span class="text-danger">*</span> Zip</label> 
							<input id="bzip" tabindex="17" name="bzip" autocomplete="off" type="text" placeholder="" class="numbersOnly form-control input-md" required="" maxlength="5" value="<?php echo set_value('bzip'); ?>">
						</div>
					</div>
				</div>
			</div>
			<br><br><!-- order details -->
			<div id="showError"></div>
			<div class="well orderDetails">

			</div>
			<button class="btn btn-success btn-sm stepProceed3 steper" tabindex="16"> Next Step</button>
		</div>
	</div>
</div> 
<div class="row setup-content step hiddenStepInfo" id="step-3">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-gear text-success"></i> User Account</h3>
			<hr> 
			<p>This information is all for creating your account so that you can login and manage your ads and view stats.</p>
			<div class="row">
				<div class="col-md-4">
					<label class="control-label" for="email"><span class="text-danger">*</span> Email:</label>  
					<input id="email" name="email" type="text" autocomplete="off" class="form-control input-md" required="" maxlength="70" tabindex="19" value="<?php echo set_value('email'); ?>">
					<div class="email-error-text"></div>
					<br>
				</div>
				<div class="col-md-4">
					<label class="control-label" for="password"><span class="text-danger">*</span> Password:</label>  
					<input id="password" name="password" type="password" autocomplete="off" class="form-control input-md" required="" maxlength="30" tabindex="20" value="<?php echo set_value('password'); ?>">
					<div class="password-error-text"></div><br>
				</div>
				<div class="col-md-4">
					<label class="control-label" for="password2"><span class="text-danger">*</span> Confirm Password:</label>  
					<input id="password2" name="password2" autocomplete="off" type="password" class="form-control input-md" required="" maxlength="30" tabindex="21" value="<?php echo set_value('password'); ?>"><br>
				</div>
			</div>
			
			<div class="row">
				<div class="col-xs-1">
					<input type="checkbox" id="termsService" <?php echo set_checkbox('terms', 'y'); ?> required="required" value="y" name="terms" tabindex="22">
				</div>
				<div class="col-xs-11">
					I Agree To The <a href="<?php echo base_url(); ?>contractors/terms-of-service" target="_blank">Terms Of Service</a>
				</div>
			</div>
			<div class="terms-error-text"></div>
			
			<br><br><!-- order details -->
			<div id="showError"></div>
			<div class="well orderDetails">

			</div>
			<button class="btn btn-success btn-sm stepProceed4 steper" tabindex="23" onclick="javascript: resetActive(event, 100, 'step-5');"> Next Step</button>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-4">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-credit-card text-success"></i> Payment Info</h3>
			<hr>
			<div class="row">
				<div class="col-sm-2 text-center">
					<i class="fa fa-lock fa-5x" style="color: #ccc;"></i>
				</div>
				<div class="col-sm-10">
					<p>We don't not store any of your credit card data on our servers so you can be sure that your data is safe.</p>
				</div>
			</div>
			<hr>
			<div class="row">
				<div class="col-sm-6">
					<div class="well">
						<label class="control-label" for="credit_card"><span class="text-danger">*</span> Credit Card Number:</label>  
						<input id="checkout_card_number" name="credit_card" autocomplete="off" type="text" placeholder="1234-5678-9012-3456" class="form-control input-md input-text" required="" data-stripe="number" maxlength="19" tabindex="24" value="<?php echo set_value('credit_card'); ?>">
						<div class="cc_helper"></div>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Month:</label>  
								<select id="exp-month" name="exp_month" class="form-control input-md" required="" maxlength="2" tabindex="25">
									<option value='01'>(01) January</option>
									<option value='02'>(02) February</option>
									<option value='03'>(03) March</option>
									<option value='04'>(04) April</option>
									<option value='05'>(05) May</option>
									<option value='06'>(06) June</option>
									<option value='07'>(07) July</option>
									<option value='08'>(08) August</option>
									<option value='09'>(09) September</option>
									<option value='10'>(10) October</option>
									<option value='11'>(11) November</option>
									<option value='12'>(12) December</option>
								</select>
							</div>
							<div class="col-sm-6">
								<label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Year:</label>  
								<select id="exp-year" name="exp_year" class="form-control input-md" required="" maxlength="2" tabindex="26">
									<option value="">Year</option>
									<?php
										for($i=0;$i<8;$i++) {
											echo '<option '.set_select('exp_year', date('Y', strtotime('+'.$i.' years'))).'>'.date('Y', strtotime('+'.$i.' years')).'</option>';
										}
										
										
									?>
								</select>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-4">
								<label class="control-label" for="ccv"><span class="text-danger">*</span> CCV:</label>  
								<input id="ccv" name="ccv" type="text" autocomplete="off" placeholder="123" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="27" value="<?php echo set_value('ccv'); ?>">
							</div>
						</div>
						<label class="control-label" for="name_on_card"><span class="text-danger">*</span> Name On The Card:</label>  
						<input id="name_on_card" name="name_on_card" value="<?php echo set_value('name_on_card'); ?>" autocomplete="off" type="text" class="form-control input-md" required="" maxlength="70" tabindex="28">
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="well">
						<label>Select Payment Frequency</label>
						<select name="frequency" class="form-control" tabindex="29" id="frequency" onchange="calculate_price();">
							<option value="1" <?php echo set_select('frequency', '1'); ?>>Monthly</option>
							<option value="3" <?php echo set_select('frequency', '1'); ?>>Quarterly</option>
							<option value="12" <?php echo set_select('frequency', '1'); ?>>Yearly</option>
						</select>
					</div>
				</div>
			</div>
			<br><br><!-- order details -->
			<div id="showError"></div>
			<div class="well orderDetails">

			</div>
			<button class="btn btn-success btn-sm stepProceed5 steper" tabindex="30"> Next Step</button>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-5">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-check text-success"></i> Confirm Details</h3>
			<hr>
			<p>Before you submit your order look over the details below and make sure that everything is right. Once you are satisfied with the details, click the submit button to create your account.</p>
			<div class="row">
				<div class="col-sm-12">
					<div class="well step1details">
						<legend><i class="fa fa-map-marker"></i> Zips Codes</legend>
						<div class="fill-out">
							<?php
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
								$zips = $this->session->userdata('zips');
								$service = $this->session->userdata('service');
								$price = $this->session->userdata('price');
								$city = $this->session->userdata('city');
								$state = $this->session->userdata('state');
								$total = 0;
								if(!empty($zips)) {
									for($i=0;$i<count($zips);$i++) {
										echo '<div class="'.$zips[$i].'-'.$service[$i].'" data-dup="'.$zips[$i].'-'.$service[$i].'">';
											echo '<div class="row">';
												echo '<div class="col-sm-1">'.$zips[$i].'</div>';
												echo '<div class="col-sm-1">'.$state[$i].'</div>';
												echo '<div class="col-sm-4">'.$city[$i].'</div>';
												echo '<div class="col-sm-3">'.$services_array[$service[$i]].'</div>';
												echo '<div class="col-sm-3">$'.$price[$i].' <small>per month</small></div>';
											echo '</div>';
										echo '</div>';
										$total = $price[$i]+$total;
									}
								}
							?>
						</div>
						<div class="confirmOrderTotal text-right">
							<?php echo '<hr><h4>$'.number_format($total,2).' <span class="freq">Per Month</span></h4>'; ?>
						</div>
						<br>
						<button class="btn btn-success btn-sm stepProceed1 steper" onclick="javascript: resetActive(event, 0, 'step-1');"> <i class="fa fa-pencil"></i> Edit</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="well step2details">
						<legend><i class="fa fa-user"></i> Contact Info</legend>
						<div class="fill-out">
							<div class="row">
								<div class="col-sm-6">
									<div class="business-name"></div> 
									<div class="conf-name"></div> 
									<div class="conf-full-address">
										<span class="confirm-address"></span>
										<span class="confirm-city"></span>
										<span class="confirm-state"></span>
										<span class="confirm-zip"></span>
									</div>
									<div class="confirm-phone"></div> 
									<div class="confirm-fax"></div> 
								</div>
								<div class="col-sm-6">
									</h4><b><i class="fa fa-envolope"></i> Billing Address:</b></h4>
									<div class="conf-billing-address">
										<span class="confirm-billing-address"></span>
										<span class="confirm-billing-city"></span>
										<span class="confirm-billing-state"></span>
										<span class="confirm-billing-zip"></span>
									</div>
								</div>
							</div>
							<hr>
						</div>
						<br>
						<button class="btn btn-success btn-sm stepProceed2 steper" onclick="javascript: resetActive(event, 25, 'step-2');"> <i class="fa fa-pencil"></i> Edit</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="well step4details">
						<legend><i class="fa fa-gear"></i> User Account</legend>
						<div class="fill-out">
							
						</div>
						<br>
						<button class="btn btn-success btn-sm stepProceed3 steper" onclick="javascript: resetActive(event, 50, 'step-3');"> <i class="fa fa-pencil"></i> Edit</button>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="well step3details">
						<legend><i class="fa fa-credit-card"></i> Payment Info</legend>
						<div class="fill-out">
							<div class="cc-frequncy"></div>
							<div class="cc-number"></div>
							<div class="cc-expire"></div>
							<div class="cc-ccv"></div>
							<div class="cc-name"></div>
						</div>
						<br>
						<button class="btn btn-success btn-sm stepProceed4 steper" onclick="javascript: resetActive(event, 75, 'step-4');"> <i class="fa fa-pencil"></i> Edit</button>
					</div>
				</div>
			</div>
			
			<br><br><!-- order details -->
			<div class="well orderDetails">
				
			</div>
			<button type="submit" id="submitAccount" class="btn btn-success btn-sm"> Submit Order</button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
