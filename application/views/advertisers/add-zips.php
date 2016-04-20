<h3><i class="fa fa-map-marker text-success"></i> Add Zips And Services</h3>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}	
?>
<?php echo form_open('contractors/add-zip-codes', array('id'=>'createAccountForm')); ?>
<div class="row setup-content step activeStepInfo" id="step-1">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-map-marker text-success"></i> Select The Areas You Want To Advertise In</h3>
			<hr>
			<p>Select the zip codes you would like to advertise in. There can only be three contractors per zip code so if you don't find your zip code its because its already taken.</p>
			<hr>
			<label>Select Your Service And Zip Code And Click The Search Button</label>
			<div class="error-helper"></div>
			<div class="row">
				<div class="col-sm-4">
					<?php 
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
						echo "<select name='serviceType' id='serviceType' class='form-control input-md' required='required'>";
						//echo '<option value="">Choose One...</option>';
						foreach($services_array as $key => $val) {
							if(isset($_POST['serviceType'])) {
								if($_POST['serviceType'] == $key) {
									echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
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
							<button type="button" class="btn btn-warning btn-md searchZips"><i class="fa fa-search"></i></button>
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

				</div>
			</div>
			<br><br><!-- order details -->
			<div class="well orderDetails">
				<div class="row">
					<div class="col-sm-3">
						<h4>Order Details:</h4>
					</div>
					<div class="col-sm-5">
						<div class="orderTotal">	
							<?php if (isset($total)) { ?>
							<h4>$<?php echo number_format($total, 2); ?></b> Per Month</h4>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="text-right">
							<button class="btn btn-success btn-sm stepProceed2 steper" onclick="javascript: resetActive(event, 25, 'step-2');"> Next Step</button>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-2">
	<div class="col-xs-12">
		<div class="col-md-12 well text-left">
			<h3><i class="fa fa-user text-success"></i> Business Info</h3>
			<hr>
			<label class="control-label" for="bName"><span class="text-danger">*</span> Business Name</label>
			<input id="bName" name="bName" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="70"  tabindex="1" value="<?php echo $user_info->bName; ?>">
			<div class="row">
				<div class="col-sm-6">
					<label class="control-label" for="first_name"><span class="text-danger">*</span> Contacts First Name</label>
					<input id="first_name" name="first_name" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="50"  tabindex="1" value="<?php echo $user_info->f_name; ?>">
					<label class="control-label" for="address"><span class="text-danger">*</span> Address</label>  
					<input id="address" name="address" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="150" tabindex="3" value="<?php echo $user_info->address; ?>">
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label" for="state"><span class="text-danger">*</span> State</label>  
							<?php
								$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
								echo '<select id="state" name="state" class="form-control" required="" tabindex="4">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									if($key==$user_info->state) {
										echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo '</select>';
							?>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="zip"><span class="text-danger">*</span> Zip</label> 
							<input id="zip" name="zip" type="text" placeholder="" autocomplete="off" class="form-control input-md numbersOnly" required="" maxlength="5" tabindex="5" value="<?php echo $user_info->zip; ?>">
						</div>
					</div>					
				</div>
				<div class="col-sm-6">
					<label class="control-label" for="last_name"><span class="text-danger">*</span> Contacts Last Name</label>  
					<input id="last_name" name="last_name" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="50" tabindex="2" value="<?php echo $user_info->l_name; ?>">
					<label class="control-label" for="city"><span class="text-danger">*</span> City</label>  
					<input id="city" name="city" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="70" tabindex="3" value="<?php echo $user_info->city; ?>"> 	
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="phone"><span class="text-danger">*</span> Phone</label>  
							<input id="phone" autocomplete="off" name="phone" type="text" placeholder="" class="form-control input-md phone" maxlength="20" tabindex="6" required="" value="<?php echo $user_info->phone; ?>">
						</div>
						<div class="col-sm-6">
							<label class="control-label" for="fax">Fax</label>  
							<input id="fax" name="fax" type="text" autocomplete="off" placeholder="" class="form-control input-md phone" maxlength="20" tabindex="7" value="<?php echo $user_info->fax; ?>">
						</div>
					</div>					
				</div>
			</div>
			<hr>
			<div class="form-group row">
				<div class="col-md-1">
					<label class="checkbox-inline" for="checkboxes-0">
						<input type="checkbox" id="billing-same" tabindex="8">
					</label>
				</div>
				<label class="col-md-5 control-label" for="checkboxes"><b>Billing Same As Home Address</b></label>
			</div>
			<legend>Billing Info</legend>
			<div class="row">
				<div class="col-sm-6">
					<label class="control-label" for="baddress">Billing Address</label>  
					<input id="baddress" name="baddress" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="150" tabindex="9" value="<?php echo $user_info->baddress; ?>">
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="bcity"><span class="text-danger">*</span> City</label>  
							<input id="bcity" name="bcity" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="70" tabindex="10" value="<?php echo $user_info->bcity; ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<label class="control-label" for="bstate"><span class="text-danger">*</span> State</label>  
							<?php	
								echo $user_info->bstate;
								$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
								echo '<select id="bstate" name="bstate" class="form-control" required="" tabindex="11">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									if($user_info->bstate == $key) {
										echo '<option selected="selected" selected="selected" value="'.$key.'">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo '</select>';
							?>
						</div>
						<div class="col-sm-4">
							<label class="control-label" for="bzip"><span class="text-danger">*</span> Zip</label> 
							<input id="bzip" name="bzip" autocomplete="off" type="text" placeholder="" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="11" value="<?php echo $user_info->bzip; ?>">
						</div>
					</div>
				</div>
			</div>
			<br><br><!-- order details -->
			<div id="showError"></div>
			<div class="well orderDetails">
				<div class="row">
					<div class="col-sm-3">
						<h4>Order Details:</h4>
					</div>
					<div class="col-sm-5">
						<div class="orderTotal">
							<?php if ($total>0) { ?>
								<h4>$<?php echo number_format($total, 2); ?></b> Per Month</h4>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="text-right">
							<button class="btn btn-success btn-sm stepProceed3 steper">Next Step</button>
						</div>
					</div>	
				</div>
			</div>
			<button class="back-a-step btn btn-sm btn-success btn-sm" data-step="1"><i class="fa fa-reply"></i> Previous Step</button>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-3">
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
						<input id="checkout_card_number" name="credit_card" autocomplete="off" type="text" placeholder="1234-5678-9012-3456" class="form-control input-md input-text" required="" data-stripe="number" maxlength="19" tabindex="11" value="<?php echo set_value('credit_card'); ?>">
						<div class="cc_helper"></div>
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Month:</label>  
								<select id="exp-month" name="exp_month" class="form-control input-md" required="" maxlength="2" tabindex="12">
									<option value="">Month</option>
									<?php
										for($i=1;$i<13;$i++) {
											echo '<option '.set_select('exp_month', sprintf("%02s", $i)).' value="'.sprintf("%02s", $i).'">'.sprintf("%02s", $i).'</option>';
										}
									?>
								</select>
							</div>
							<div class="col-sm-6">
								<label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Year:</label>  
								<select id="exp-year" name="exp_year" class="form-control input-md" required="" maxlength="2" tabindex="13">
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
								<input id="ccv" name="ccv" type="text" autocomplete="off" placeholder="123" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="14" value="<?php echo set_value('ccv'); ?>">
							</div>
						</div>
						<label class="control-label" for="name_on_card"><span class="text-danger">*</span> Name On The Card:</label>  
						<input id="name_on_card" name="name_on_card" value="<?php echo set_value('name_on_card'); ?>" autocomplete="off" type="text" class="form-control input-md" required="" maxlength="70" tabindex="15">
						
					</div>
				</div>
				<div class="col-sm-6">
					<div class="well">
						<label>Select Payment Frequency</label>
						<select name="frequency" class="form-control" id="frequency" onchange="calculate_price();">
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
				<div class="row">
					<div class="col-sm-3">
						<h4>Order Details:</h4>
					</div>
					<div class="col-sm-5">
						<div class="orderTotal">
							<?php if ($total>0) { ?>
								<h4>$<?php echo number_format($total, 2); ?></b> Per Month</h4>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="text-right">
							<button class="btn btn-success btn-sm stepProceed4 steper"> Next Step</button>
						</div>
					</div>	
				</div>
			</div>
			<button class="back-a-step btn btn-sm btn-success btn-sm" data-step="2"><i class="fa fa-reply"></i> Previous Step</button>
		</div>
	</div>
</div>
<div class="row setup-content step hiddenStepInfo" id="step-4">
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
									$total = array_sum($price);
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
				<div class="col-sm-6">
					<div class="well step2details">
						<legend><i class="fa fa-user"></i> User Info</legend>
						<div class="fill-out">
							<div class="conf-name"><b>Name: </b><?php echo $user_info->f_name.' '.$user_info->l_name; ?></div>
							<div class="conf-full-address">
								<span class="confirm-address"><b>Address:</b><br><?php echo $user_info->address; ?></span>
								<span class="confirm-city"><?php echo $user_info->city; ?></span>
								<span class="confirm-state"><?php echo $user_info->state; ?></span>
								<span class="confirm-zip"><?php echo $user_info->zip; ?></span>
							</div>
							<div class="confirm-phone"><b>Phone:</b> <?php echo "(".substr($user_info->phone, 0, 3).") ".substr($user_info->phone, 3, 3)."-".substr($user_info->phone,6); ?></div> 
							<div class="confirm-fax"><b>Fax: </b><?php echo "(".substr($user_info->fax, 0, 3).") ".substr($user_info->fax, 3, 3)."-".substr($user_info->fax,6); ?></div> 
							<hr>
							</h4><b><i class="fa fa-envolope"></i> Billing Address:</b></h4>
							<div class="conf-billing-address">
								<span class="confirm-billing-address"><?php echo $user_info->baddress; ?><br></span>
								<span class="confirm-billing-city"><?php echo $user_info->bcity; ?></span>
								<span class="confirm-billing-state"><?php echo $user_info->bstate; ?></span>
								<span class="confirm-billing-zip"><?php echo $user_info->bzip; ?></span>
							</div>
						</div>
						<br>
						<button class="btn btn-success btn-sm stepProceed2 steper" onclick="javascript: resetActive(event, 25, 'step-2');"> <i class="fa fa-pencil"></i> Edit</button>
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
						<button class="btn btn-success btn-sm stepProceed3 steper" onclick="javascript: resetActive(event, 50, 'step-3');"> <i class="fa fa-pencil"></i> Edit</button>
					</div>
				</div>
			</div>
			
			<br><br><!-- order details -->
			<div class="well orderDetails">
				<div class="row">
					<div class="col-sm-3">
						<h4>Order Details:</h4>
					</div>
					<div class="col-sm-5">
						<div class="orderTotal">
							<?php if ($total>0) { ?>
								<h4>$<?php echo $total; ?></b> Per Month</h4>
							<?php } ?>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="text-right">
							<button type="submit" id="submitAccount" class="btn btn-success btn-sm"> Submit Order</button>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>