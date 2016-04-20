<?php
	$price_array = $this->session->userdata('price');
	$total = 0;
	for($i=0;$i<count($price_array);$i++) {
		$total = $price_array[$i]+$total;
	}
	
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
	<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
	</div>
	<span class="progress-type">Overall Progress</span>
	<span class="progress-completed">0%</span>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="step largeIcons text-center">
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
<?php echo form_open('', array('id'=>'createAccountForm')); ?>
<div class="row setup-content step activeStepInfo" id="step-1">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-step"></i> Step 1 of 5</h3>
				</div>
				<div class="panel-body">
					<br>
					<div class="row">
						<div class="col-lg-5">
							<div class="youtube_video youtube_video">
								<div class="embed-responsive embed-responsive-16by9">
									<iframe width="560" height="315" src="//www.youtube.com/embed/uMfoQB3pg2k" allowfullscreen></iframe>
								</div>
							</div>
						</div>
						<div class="col-lg-7">
							<h3><i class="fa fa-map-marker text-success"></i> Select The Areas You Want To Advertise In</h3>
							<hr>
							<div class="row">
								<div class="col-sm-9">
									<p>Select the zip codes you would like to advertise in. There can only be three contractors per zip code so if you don't find your zip code its because its already taken.</p>
									<h4>Already Have An Account?</h4>
									<a href="<?php echo base_url(); ?>contractor/login" class="btn btn-lg btn-success"><i class="fa fa-lock"></i> Login</a>
								</div>
							</div>
						</div>
					</div>
					
					<hr>
					<h4><i class="fa fa-map-marker text-success"></i> Select Your Service And Zip Code And Click The Search Button</h4>
					<div class="error-helper"></div>
					<div class="row">
						<div class="col-lg-4 col-md-6">
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control &#124; Exterminator'); 
								echo "<select tabindex='1' id='serviceType' class='form-control input-lg' required=''>";
								echo '<option value="">Choose One...</option>';
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
							<br>
						</div>
						<div class="col-lg-4 col-md-6">
							<div class="input-group">
								<input type="text" class="form-control zipSearch input-lg" placeholder="Search&hellip;" maxlength="5" value="43055">
								<span class="input-group-btn">
									<button type="button" class="btn btn-warning btn-lg searchZips" tabindex="2"><i class="fa fa-search"></i></button>
								</span>
							</div>
							
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<br>
							<div class="loadingZips"></div>
						</div>
					</div>
					
					<br>
					<div class="zip-results">
						
					</div>
					<h3 class="highlight">Selected Zip Codes:</h3>
					<hr>
			
					<div class="zips_purchased">
						<?php
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
							$zips = $this->session->userdata('zips');
							$service = $this->session->userdata('service');
							$city = $this->session->userdata('city');
							$state = $this->session->userdata('state');
					
							if(!empty($zips)) {
								echo '<div class="col-sm-12"><div class="zipsAdded"></div></div>';
								$count = 1;
								for($i=0;$i<count($zips);$i++) {
								
									echo '<div id="'.$zips[$i].'-'.$service[$i].'" class="border-bottom row selectedZips" data-dup="'.$zips[$i].'-'.$service[$i].'">';				
									echo '<div class="col-xs-1 counter">'.$count.'</div>';
									echo '<div class="col-xs-2">'.$zips[$i].'</div>';
									echo '<div class="col-sm-3 hidden-xs">'.$city[$i].'</div>';
									echo '<div class="col-sm-1 hidden-xs">'.$state[$i].'</div>';
									echo '<div class="col-sm-4 col-xs-7">'.$services_array[$service[$i]].'</div>';
									echo '<div class="col-xs-1"><button class="btn btn-sm btn-danger removeZip" data-remove="'.$zips[$i].'-'.$service[$i].'"><i class="fa fa-times"></i></button></div>';
									echo '<div class="clearfix"></div></div>';
									$count++;
								}
							} 
						?>
					</div>
					
					<br><br><!-- order details -->
					<hr>
					<div class="orderDetails alert alert-info">
						<div class="row text-center">
							<div class="col-xs-6"><b>Subscription Cost Per Year</b></div>
							<div class="col-xs-6"><b>Billing Cycle</b></div>
						</div>
						<div class="row text-center">
							<div class="col-xs-6">$299.99</div>
							<div class="col-xs-6">Yearly</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-6">
							
						</div>
						<div class="col-xs-6 text-right">
							<button class="btn btn-success btn-lg stepProceed2 steper" tabindex="3" onclick="javascript: resetActive(event, 25, 'step-2');">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TAB INDEX 3 -->
<div class="row setup-content step hiddenStepInfo" id="step-2">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-step"></i> Step 2 of 5</h3>
				</div>
				<div class="panel-body">
					<h3 class="highlight">Business Info</h3>
					<hr>
					<label class="control-label" for="bName"><span class="text-danger">*</span> Business Name</label>
					<div class="form-group">
						<input id="bName" name="bName" tabindex="4" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="70"  value="<?php echo set_value('bName'); ?>">
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="first_name"><span class="text-danger">*</span> Contacts First Name</label>  
							<input id="first_name"  tabindex="5" name="first_name" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="50"  value="<?php echo set_value('first_name'); ?>">
							<label class="control-label" for="address"><span class="text-danger">*</span> Address</label>  
							<input id="address" tabindex="7" name="address" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="150" value="<?php echo set_value('address'); ?>">
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
									<input id="phone" autocomplete="off" tabindex="12" name="phone" type="text" placeholder="" class="form-control input-md phone" required="" value="<?php echo set_value('phone'); ?>">
								
									<label class="control-label" for="fax">Fax</label>  
									<input id="fax" name="fax" tabindex="13" type="text" autocomplete="off" placeholder="" class="form-control input-md phone" maxlength="20" value="<?php echo set_value('fax'); ?>">
							
								</div>
								<div class="col-sm-6">
									<label class="control-label toolTip" for="fax">Cell Phone <i class="fa fa-question text-success" data-placement="left" data-toggle="tooltip" title="Enter a cell phone number to receive text messages as you receive service requests"></i></label>  
									<input id="cell" name="cell" tabindex="13" type="text" autocomplete="off" placeholder="" class="form-control input-md phone" maxlength="21" value="<?php echo set_value('cell'); ?>">
									<small><span class="text-danger">*</span> Enter a cell phone number to receive text messages as you receive service requests. This information is not made public.</small>
								</div>
							</div>	
						
						</div>
					</div>
					<hr>
					
					<div class="checkbox">
						<label for="billing-same">
							<input type="checkbox" id="billing-same" tabindex="14">
							Billing Same As Home Address
						</label>
					</div>
					
			
					
					
					<h3 class="highlight">Billing Info</h3>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="baddress"><span class="text-danger">*</span> Billing Address</label>  
							<input id="baddress" name="baddress" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="150" tabindex="15" value="<?php echo set_value('baddress'); ?>">
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="bcity"><span class="text-danger">*</span> City</label>  
									<input id="bcity"  tabindex="16" name="bcity" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="70" value="<?php echo set_value('bcity'); ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8">
									<label class="control-label" for="bstate"><span class="text-danger">*</span> State</label>  
									<?php
										$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
										echo '<select tabindex="17" id="bstate" name="bstate" class="form-control" required="" >';
										echo '<option value="">Select One...</option>';
										foreach($states as $key => $val) {
											echo '<option '.set_select('bstate', $key).' value="'.$key.'">'.$val.'</option>';
										}
										echo '</select>';
									?>
								</div>
								<div class="col-sm-4">
									<label class="control-label" for="bzip"><span class="text-danger">*</span> Zip</label> 
									<input id="bzip" tabindex="18" name="bzip" autocomplete="off" type="text" placeholder="" class="numbersOnly form-control input-md" required="" maxlength="5" value="<?php echo set_value('bzip'); ?>">
								</div>
							</div>
						</div>
					</div>
					<br><br><!-- order details -->
					<div id="showError2"></div>
					<div class="orderDetails alert alert-info">
						<div class="row text-center">
							<div class="col-xs-6"><b>Subscription Cost Per Year</b></div>
							<div class="col-xs-6"><b>Billing Cycle</b></div>
						</div>
						<div class="row text-center">
							<div class="col-xs-6">$299.99</div>
							<div class="col-xs-6 billingCycle">Yearly</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-success back btn-lg" onclick="javascript: resetActive(event, 20, 'step-1');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Previous Step </button>
						</div>
						<div class="col-xs-6 text-right">
							<button class="btn btn-success btn-lg stepProceed3" onclick="javascript: resetActive(event, 50, 'step-3');" tabindex="20">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TAB INDEX 20 -->
<div class="row setup-content step hiddenStepInfo" id="step-3">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
		
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-step"></i> Step 3 of 5</h3>
				</div>
				<div class="panel-body">		
		
					<h3><i class="fa fa-gear text-success"></i> User Account</h3>
					<hr>
					<p>This information is all for creating your account so that you can login and manage your ads and view stats.</p>
					<div class="row">
						<div class="col-md-4">
							<label class="control-label" for="email"><span class="text-danger">*</span> Email:</label>  
							<input id="email" name="email" type="text" autocomplete="off" class="form-control input-md" required="" maxlength="70" tabindex="22" value="<?php if(empty($_GET['email'])) { echo set_value('email'); } else { echo $_GET['email']; } ?>">
							<div class="email-error-text"></div><br>
						</div>
						<div class="col-md-4">
							<label class="control-label" for="password"><span class="text-danger">*</span> Password:</label>  
							<input id="password" name="password" type="password" autocomplete="off" class="form-control input-md" required="" maxlength="30" tabindex="23" value="<?php echo set_value('password'); ?>">
							<div class="password-error-text"></div><br>
						</div>
						<div class="col-md-4">
							
							<label class="control-label" for="password2"><span class="text-danger">*</span> Confirm Password:</label>  
							<input id="password2" name="password2" autocomplete="off" type="password" class="form-control input-md" required="" maxlength="30" tabindex="24" value="<?php echo set_value('password'); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-xs-1">
							<input type="checkbox" id="termsService" <?php echo set_checkbox('terms', 'y'); ?> required="required" value="y" name="terms" tabindex="25">
						</div>
						<div class="col-xs-11">
							I Agree To The <a href="<?php echo base_url(); ?>contractor/terms-of-service" target="_blank">Terms Of Service</a>
						</div>
					</div>
					<div class="terms-error-text"></div>
					
					<br><br><!-- order details -->
					<div id="showError3"></div>
					<div class="orderDetails alert alert-info">
						<div class="row text-center">
							<div class="col-xs-6"><b>Subscription Cost Per Year</b></div>
							<div class="col-xs-6"><b>Billing Cycle</b></div>
						</div>
						<div class="row text-center">
							<div class="col-xs-6">$299.99</div>
							<div class="col-xs-6 billingCycle">Yearly</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-success btn-lg back" tabindex="26" onclick="javascript: resetActive(event, 50, 'step-2');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Previous Step </button>
						</div>
						<div class="col-xs-6 text-right">
							<button class="btn btn-success btn-lg stepProceed4 steper" tabindex="25" onclick="javascript: resetActive(event, 75, 'step-4');">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TAB INDEX 26 -->
<div class="row setup-content step hiddenStepInfo" id="step-4">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-step"></i> Step 4 of 5</h3>
				</div>
				<div class="panel-body">	
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
						<div class="col-md-6">
							<div class="well">
								<label class="control-label"><span class="text-danger">*</span> Credit Card Number:</label>  
								<input id="checkout_card_number" name="credit_card" autocomplete="off" type="text" placeholder="1234-5678-9012-3456" class="form-control input-md input-text" required="" data-stripe="number" maxlength="19" tabindex="27" value="<?php echo set_value('credit_card'); ?>">
								<div class="cc_helper"></div>
								<div class="row">
									<div class="col-sm-6"> 
										<label class="control-label"><span class="text-danger">*</span> Exp Month:</label>  
										<select id="exp-month" name="exp_month" class="form-control input-md" required="" tabindex="28">
											<option value="">Select One</option>
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
										<label class="control-label"><span class="text-danger">*</span> Exp Year:</label>  
										<select id="exp-year" name="exp_year" class="form-control input-md" required=""  tabindex="29">
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
										<input id="ccv" name="ccv" type="text" autocomplete="off" placeholder="123" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="30" value="<?php echo set_value('ccv'); ?>">
									</div>
								</div>
								<label class="control-label" for="name_on_card"><span class="text-danger">*</span> Name On The Card:</label>  
								<input id="name_on_card" name="name_on_card" value="<?php echo set_value('name_on_card'); ?>" autocomplete="off" type="text" class="form-control input-md" required="" maxlength="70" tabindex="31">
								
							</div>
						</div>
						<div class="col-md-6">
							<div class="well">
								<div class="row">
									<div class="col-md-6">
										<label class="control-label" for="promo">Promo Code:</label>  
										<div class="input-group">
											<input type="text" id="promo" name="promo" class="form-control" placeholder="Enter Promo Code">
											<span class="input-group-btn">
												<button id="promo-btn" class="btn btn-success" type="button"><i class="fa fa-search"></i></button>
											</span>
										</div>
									</div>
									<div class="col-md-6">
										<label class="control-label" for="promo">Billing Cycle:</label>  
										<select name="freq" class="form-control" id="freq" required tabindex="33">
											<?php 
												foreach($settings as $row) {
													echo '<option value="'.$row->setting_value.'" data-name="'.ucwords($row->label).'">'.ucwords($row->label).'</option>';
												}
											?>
										</select>
									</div>
								</div>	
								<div id="promo-error"></div>
							</div>
						</div>
					</div>
					<br><br><!-- order details -->
					<div id="showError4"></div>
					<div class="orderDetails alert alert-info">
						<div class="row text-center">
							<div class="col-xs-6"><b>Subscription Cost Per Year</b></div>
							<div class="col-xs-6"><b>Billing Cycle</b></div>
						</div>
						<div class="row text-center">
							<div class="col-xs-6">$299.99</div>
							<div class="col-xs-6 billingCycle">Yearly</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-success btn-lg back" onclick="javascript: resetActive(event, 60, 'step-3');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Previous Step </button>
						</div>
						<div class="col-xs-6 text-right">
							<button class="btn btn-success btn-lg stepProceed5" tabindex="33"> Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- TAB INDEX 33 -->
<div class="row setup-content step hiddenStepInfo" id="step-5">
	<div class="col-xs-12">
		<div class="col-md-12 text-left">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-step"></i> Step 5 of 5</h3>
				</div>
				<div class="panel-body">	
					<h3><i class="fa fa-check text-success"></i> Confirm Details</h3>
					<hr>
					<p>Before you submit your order look over the details below and make sure that everything is right. Once you are satisfied with the details, click the submit button to create your account.</p>
					<div class="row">
						<div class="col-sm-12">
							<div class="well step1details">
								<h3 class="highlight"><i class="fa fa-map-marker"></i> Zips Codes</h3>
								<div class="fill-out">
									<div class="row">
										<div class="col-xs-1">#</div>
										<div class="col-sm-2"><b>Zip</b></div>
										<div class="col-sm-4 hidden-xs"><b>City</b></div>
										<div class="col-sm-1 hidden-xs"><b>State</b></div>
										<div class="col-xs-4"><b>Service Type</b></div>
									</div>
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
												$count = $i+1;
												echo '<div class="'.$zips[$i].'-'.$service[$i].'" data-dup="'.$zips[$i].'-'.$service[$i].'">';
													echo '<div class="row">';
														echo '<div class="col-sm-1 counter">'.$count.'</div>';
														echo '<div class="col-sm-2">'.$zips[$i].'</div>';
														echo '<div class="col-sm-4">'.$city[$i].'</div>';
														echo '<div class="col-sm-1 hidden-xs">'.$state[$i].'</div>';
														echo '<div class="col-sm-4">'.$services_array[$service[$i]].'</div>';
													echo '</div>';
												echo '</div>';
											}
										}
										
									
									?>
								</div>
								<br>
								<button class="btn btn-success btn-sm stepProceed1 back" onclick="javascript: resetActive(event, 0, 'step-1');"> <i class="fa fa-pencil"></i> Edit</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="well step2details">
								<h3 class="highlight"><i class="fa fa-user"></i> Contact Info</h3>
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
											<h4><b><i class="fa fa-envolope"></i> Billing Address:</b></h4>
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
								<button class="btn btn-success btn-sm stepProceed2 back" onclick="javascript: resetActive(event, 25, 'step-2');"> <i class="fa fa-pencil"></i> Edit</button>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="well step4details">
								<h3 class="highlight"><i class="fa fa-gear"></i> User Account</h3>
								<div class="fill-out">
									
								</div>
								<br>
								<button class="btn btn-success btn-sm stepProceed3 back" onclick="javascript: resetActive(event, 50, 'step-3');"> <i class="fa fa-pencil"></i> Edit</button>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="well step3details">
								<h3 class="highlight"><i class="fa fa-credit-card"></i> Payment Info</h3>
								<div class="fill-out">
									<div class="cc-number"></div>
									<div class="cc-expire"></div>
									<div class="cc-ccv"></div>
									<div class="cc-name"></div>
								</div>
								<br>
								<button class="btn btn-success btn-sm stepProceed4 back" onclick="javascript: resetActive(event, 75, 'step-4');"> <i class="fa fa-pencil"></i> Edit</button>
							</div>
						</div>
					</div>
					
					<br><br><!-- order details -->
					<div class="orderDetails alert alert-info">
						<div class="row text-center">
							<div class="col-xs-6"><b>Subscription Cost Per Year</b></div>
							<div class="col-xs-6"><b>Billing Cycle</b></div>
						</div>
						<div class="row text-center">
							<div class="col-xs-6">$299.99</div>
							<div class="col-xs-6 billingCycle">Yearly</div>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-xs-6">
							<button class="btn btn-success btn-lg back" onclick="javascript: resetActive(event, 80, 'step-4');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Previous Step </button>
						</div>
						<div class="col-xs-6 text-right">
							<button type="submit" id="submitAccount" class="btn btn-success btn-lg"> Submit Order</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="settings"></div>
<?php echo form_close(); ?>
<div class="overlay"></div>

<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="errorModalLabel">Error Creating Account</h4>
      </div>
      <div class="modal-body">
        <p>Your account failed to create, please fix the following errors and try again.</p>
		<div class="crateUserErrors text-danger"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>