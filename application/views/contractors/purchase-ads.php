<?php
	$zips = $this->session->userdata('ad_zips');
	$zipsCount = count($zips);
	if($zipsCount>0) {
		$total = $zipsCount*4.99;
	} else {
		$total = '0.00';
	}
	$orderDetailsBelow = '<div class="alert alert-info orderDetails">
						<div class="row text-center">
							<div class="col-sm-3">Ad Length</div>
							<div class="col-sm-3"><p class="adLength"><b>3 Months</b></p></div>
							<div class="col-sm-3">Cost</div>
							<div class="col-sm-3"><b>$<span class="priceTag">'.number_format($total, 2).'</span></b></div>
						</div>
					</div><p><small><span class="text-danger">*</span> Ads are placed directly on all past and current service requests for your chosen area and service type. Network4Rentals, LLC can not guarantee that any service requests will be issued or viewed within a specific area or for a specific service type. You are knowingly purchasing ad space at your own risk, and Network4Rentals, LLC does not issue any refunds or credits due to lack of ad traffic.</small></p><br>';
?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-dollar"></i> Purchase Ads</h3>
	</div>
	<div class="panel-body">
		<div class="progress" id="progress1">
			<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
			</div>
			<span class="progress-type">Overall Progress</span>
			<span class="progress-completed">0%</span>
		</div>

		<div class="row text-center step largeIcons hidden-sm hidden-xs">
			<div id="div1" class="col-md-3">
				<div class="activestep">
					<span class="fa fa-map-marker"></span>
					<p>Select Zip Codes</p>
				</div>
			</div>
			<div id="div2" class="col-md-3">
				<span class="fa fa-cog"></span>
				<p>User Account</p>
			</div>
			<div id="div3" class="col-md-3">
				<span class="fa fa-credit-card"></span>
				<p>Payment Info</p>
			</div>
			<div id="div4" class="col-md-3">
				<span class="fa fa-check"></span>
				<p>Confirm Details</p>
			</div>
		</div>
		<div id="errorProcessing"></div>
		<?php echo form_open('', array('id'=>'purchaseZips')); ?>
			<div class="setup-content step activeStepInfo" id="step-1">
				<h3 class="highlight">Select The Areas &amp; Services You Want To Advertise In</h3>
				<hr>
				<p>Select the zip codes and service you would like to purchase additional ad space in. There can only be 3 additional ads per service and zip, and you are only allowed 1 ad per service type and zip code.</p>
				<hr>
				<p><b>Select Your Service And Zip Code And Click The Search Button</b></p>
				<div class="error-helper"></div>
				<div class="row">
					<div class="col-sm-4">
						<?php 
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control | Exterminator'); 
							echo "<select tabindex='1'; id='serviceType' class='form-control input-md' required='required'>";
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
						<br>
					</div>
					<div class="col-sm-4">
						<div class="input-group">
							<input type="text" class="form-control zipSearch input-md" placeholder="Search&hellip;" maxlength="5" value="43055">
							<span class="input-group-btn">
								<button type="button" class="btn btn-warning btn-md searchZips" tabindex="2"><i class="fa fa-search"></i></button>
							</span>
						</div>
						<br>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="loadingZips"></div>
					</div>
				</div>
				<div class="zip-results">
					
				</div>
				<h3 class="highlight">Selected Zips:</h3><hr>
				<div class="zips_purchased">
					<?php
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
						$zips = $this->session->userdata('ad_zips');
						$service = $this->session->userdata('ad_service');
						$city = $this->session->userdata('ad_city');
						$state = $this->session->userdata('ad_state');
			
						if(!empty($zips)) {
							echo '<div class="col-sm-12"><div class="zipsAdded"></div></div>';
							$count = 1;
							for($i=0;$i<count($zips);$i++) {
							
								echo '<div id="'.$zips[$i].'-'.$service[$i].'" class="border-bottom row selectedZips" data-dup="'.$zips[$i].'-'.$service[$i].'">';				
								echo '<div class="col-xs-1 counter">'.$count.'</div>';
								echo '<div class="col-xs-2">'.$zips[$i].'</div>';
								echo '<div class="col-sm-3 hidden-xs">'.$city[$i].'</div>';
								echo '<div class="col-sm-1 hidden-xs">'.$state[$i].'</div>';
								echo '<div class="col-sm-4 col-xs-6">'.$services_array[$service[$i]].'</div>';
								echo '<div class="col-sm-1 col-xs-2"><button class="btn btn-sm btn-danger removeZip" data-remove="'.$zips[$i].'-'.$service[$i].'"><i class="fa fa-times fa-fw"></i></button></div>';
								echo '<div class="clearfix"></div></div>';
								$count++;
							}
						} 
					?>
				</div>
					<br><br><!-- order details -->
					<?php echo $orderDetailsBelow; ?>
					<button class="btn btn-success stepProceed2 steper" tabindex="3" onclick="javascript: resetActive(event, 33, 'step-2');">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
				
			
			</div>
			
			<div class="setup-content step hiddenStepInfo" id="step-2">
				<h3 class="highlight">Billing Info</h3>
				<hr>
				<div class="row">
					<div class="col-sm-6">
						<label class="control-label" for="first_name"><span class="text-danger">*</span> First Name</label>  
						<input id="first_name"  tabindex="5" name="first_name" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="50"  value="<?php echo $billing->f_name; ?>">
					</div>
					<div class="col-sm-6">
						<label class="control-label" for="last_name"><span class="text-danger">*</span> Last Name</label>  
						<input id="last_name" name="last_name" tabindex="6" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="50" value="<?php echo $billing->l_name; ?>">			
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<label class="control-label" for="baddress"><span class="text-danger">*</span> Billing Address</label>  
						<input id="baddress" name="baddress" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="150" tabindex="7" value="<?php echo $billing->baddress; ?>">
						<div class="row">
							<div class="col-sm-6">
								<label class="control-label" for="bcity"><span class="text-danger">*</span> City</label>  
								<input id="bcity"  tabindex="8" name="bcity" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="70" value="<?php echo $billing->bcity; ?>">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8">
								<label class="control-label" for="bstate"><span class="text-danger">*</span> State</label>  
								<?php
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<select id="bstate" name="bstate" class="form-control" required="" tabindex="9">';
									echo '<option value="">Select One...</option>';
									foreach($states as $key => $val) {
										if($key == $billing->bstate) {
											echo '<option selected value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									} 
									echo '</select>';
								?>
							</div>
							<div class="col-sm-4">
								<label class="control-label" for="bzip"><span class="text-danger">*</span> Zip</label> 
								<input id="bzip" tabindex="10" name="bzip" autocomplete="off" type="text" placeholder="" class="numbersOnly form-control input-md" required="" maxlength="5" value="<?php echo $billing->bzip; ?>">
							</div>
						</div>
						<div class="checkbox">
						<label>
						  <input type="checkbox" id="correctInfo" value="1" required> Is Info Correct?
						</label>
					  </div>
					</div>
				</div>
				<br><br><!-- order details -->
				<div id="showError"></div>
				<?php echo $orderDetailsBelow; ?>
				<div class="row">
					<div class="col-sm-6">
						<button class="btn btn-success stepProceed2 steper" tabindex="12" onclick="javascript: resetActive(event, 33, 'step-1');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Back</button>
					</div>
					<div class="col-sm-6 text-right">
						<button class="btn btn-success stepProceed3 steper" tabindex="11">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></button>
					</div>
				</div>
			</div>
			
			<div class="setup-content step hiddenStepInfo" id="step-3">
				<h3 class="highlight"> Payment Info</h3>
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
							<input id="checkout_card_number" name="credit_card" autocomplete="off" type="text" placeholder="1234-5678-9012-3456" class="form-control input-md input-text" required="" data-stripe="number" maxlength="19" tabindex="14" value="<?php echo set_value('credit_card'); ?>">
							<div class="cc_helper"></div>
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Month:</label>  
									<select id="exp-month" name="exp_month" class="form-control input-md" required="" maxlength="2" tabindex="15">
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
									<select id="exp-year" name="exp_year" class="form-control input-md" required="" maxlength="2" tabindex="16">
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
									<input id="ccv" name="ccv" type="text" autocomplete="off" placeholder="123" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="17" value="<?php echo set_value('ccv'); ?>">
								</div>
							</div>
							<label class="control-label" for="name_on_card"><span class="text-danger">*</span> Name On The Card:</label>  
							<input id="name_on_card" name="name_on_card" value="<?php echo set_value('name_on_card'); ?>" autocomplete="off" type="text" class="form-control input-md" required="" maxlength="70" tabindex="18">
							
						</div>
					</div>
					<div class="col-sm-6">
						<div class="well">
							<label>Select Ad Length</label>
							<div class="radio">
								<label>
									<input type="radio" name="frequency" class="frequency" id="frequency1" value="1" checked>
									3 Months - <small>No Discount <span class="noDiscount"></span></small>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="frequency" class="frequency" id="frequency2" value="2">
									6 Months - <small><b>10% Discount </b><span class="biMonthlyDiscount"></span></small>
								</label>
							</div>
							<div class="radio">
								<label>
									<input type="radio" name="frequency" class="frequency" id="frequency3" value="4">
									1 Year -<small><b>20% Discount </b> <span class="yearlyDiscount"></span></small>
								</label>
							</div>
						</div>
					</div>
				</div>
				
				<br><br><!-- order details -->
				<div id="showError"></div>
				<div class="orderDetails">
					<?php echo $orderDetailsBelow; ?>
				</div>
				<div class="row">
					<div class="col-xs-6">
						<button class="btn btn-success stepProceed2 steper" tabindex="21" onclick="javascript: resetActive(event, 66, 'step-2');"><i class="fa fa-angle-left"></i><i class="fa fa-angle-left"></i> Back</button>
					</div>
					<div class="col-xs-6 text-right">
						<button class="btn btn-success stepProceed4 steper" tabindex="20">Next Step <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i> </button> 
					</div>
				</div>
				
			</div>
		
			<div class="row setup-content step hiddenStepInfo" id="step-4">
				<div class="col-xs-12">
					<div class="col-md-12 text-left">
						<div class="row">
							<div class="col-sm-9">
								<h3><i class="fa fa-check text-success"></i> Confirm Details</h3>
								
								<p>Before you submit your order look over the details below and make sure that everything is right. Once you are satisfied with the details, click the submit button to create your account.</p>
							</div>
							<div class="col-sm-3 text-right hidden-xs hidden-sm">
								<br>
								<button class="btn btn-primary printThis steper"><i class="fa fa-print"></i> Print Page</button>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-sm-12">
								<div class="well">
									<div class="step1details">
										<h3 class="highlight">Ad Space Purchasing</h3>
											<?php
													echo '<div class="row">';
														echo '<div class="col-xs-1"><b>#</b></div>';
														echo '<div class="col-xs-2"><b>Zips</b></div>';
														echo '<div class="col-sm-3 hidden-xs"><b>City</b></div>';
														echo '<div class="col-sm-1 hidden-xs"><b>State</b></div>';
														echo '<div class="col-sm-5 col-xs-6 text-right"><b>Service Type</b></div>';
													echo '</div>';
											?>
										<div class="fill-out">
											<?php
												$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
												$zips = $this->session->userdata('ad_zips');
												$service = $this->session->userdata('ad_service');
												$city = $this->session->userdata('ad_city');
												$state = $this->session->userdata('ad_state');
												$total = 0;
								
												if(!empty($zips)) {
												
													$count = 0;
													for($i=0;$i<count($zips);$i++) {
														$count++;
														echo '<div class="'.$zips[$i].'-'.$service[$i].'" data-dup="'.$zips[$i].'-'.$service[$i].'">';
															echo '<div class="row">';
																echo '<div class="col-xs-1 counter">'.$count.'</div>';
																echo '<div class="col-xs-2">'.$zips[$i].'</div>';
																echo '<div class="col-sm-3 hidden-xs">'.$city[$i].'</div>';
																echo '<div class="col-sm-1 hidden-xs">'.$state[$i].'</div>';
																echo '<div class="col-sm-5 col-xs-6 text-right">'.$services_array[$service[$i]].'</div>';
																
											

															echo '</div>';
														echo '</div>';
														$total = $price[$i]+$total;
													}
												}
											?>
										</div> 
										<div class="confirmOrderTotal text-right">
											<?php echo '<hr><h3>Total: <span class="label label-success">$<span class="priceTag"></span></span></h3>'; ?>
										</div>
										<br>
										<button class="btn btn-success stepProceed1 steper" onclick="javascript: resetActive(event, 33, 'step-1');"> <i class="fa fa-pencil"></i> Edit</button>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-6">
								<div class="well step2details">
									<legend><i class="fa fa-user"></i> Contact Info</legend>
									<div class="fill-out">
										</h4><b><i class="fa fa-envolope"></i> Billing Address:</b></h4>
										<div class="conf-billing-address">
											<span class="conf-name"></span>
											<span class="confirm-billing-address"></span>
											<span class="confirm-billing-city"></span>
											<span class="confirm-billing-state"></span>
											<span class="confirm-billing-zip"></span>
										</div>
										<hr>
									</div>
									<br>
									<button class="btn btn-success stepProceed2 steper" onclick="javascript: resetActive(event, 33, 'step-2');"> <i class="fa fa-pencil"></i> Edit</button>
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
									<button class="btn btn-success steper" onclick="javascript: resetActive(event, 66, 'step-3');"> <i class="fa fa-pencil"></i> Edit</button>
								</div>
							</div>
						</div>
						<input type="hidden" name="total" id="orderTotalInput">
						<br><br><!-- order details -->
						<div class="orderDetails">
							
						</div>
						<button type="submit" id="submitPayment" class="btn btn-primary"> Submit Order</button>
					</div>
				</div>
			</div>
		<?php echo form_close(); ?>
		
		
	</div><!-- Payment Body Ends -->
</div>
