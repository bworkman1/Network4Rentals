<div class="row">
	
	<div class="col-md-9">
	
		<div class="panel panel-blue">
			<div class="payment-heading panel-heading">
				Sign Up Today
			</div>
			<div class="panel-body">
				<?php echo form_open('local-partner/submit-payment', array('id'=>'payment-page')); ?>
					<div class="payment-step">
						<h2><span class="number">1</span> Select Payment Option</h2>
						<ul id="payment-options">
							<?php
								$c = 0;
								foreach($payment_settings as $val) {
									if($c==2) {
										echo '<li class="selected">';
										$checked = 'checked="checked"';
									} else {
										echo '<li class="">';
										$checked  = '';
									}
										echo '<label for="account_signup_billing_period_'.$val->label.'">
												<input class="payment-plan" type="radio" value="'.$val->setting_value.'" '.$checked.' name="amount" data-freq="'.ucwords($val->label).'" id="account_signup_billing_period_'.$val->label.'">
												'.ucwords($val->label).' <span class="price">- $'.$val->setting_value.'</span>
											</label>
										</li>';
									$c++;
								}
							?>	
						</ul>
					</div>
					
					<hr>
					
					<div class="payment-step">
						<h2><span class="number">2</span> Your Basic Information</h2>
						<br>
						<div class="row">
							<div class="col-md-6">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="first_name" placeholder="First Name *" maxlength="30" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="last_name" placeholder="Last Name *" maxlength="30" required>
								</div>
							</div>
						</div>
						
						<div class="input-groups">
							<input type="text" class="form-control input-lg" name="company_name" placeholder="Company Name" maxlength="60">
						</div>		
						
						<div class="row">
							<div class="col-md-6">
								<div class="input-groups">
									<select name="category" class="form-control input-lg" required>
										<option value="">Select a Category *</option>
										<?php
											foreach($categories as $row) {
												echo '<option value="'.$row->id.'">'.$row->category.'</option>';
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
					</div>
				
					<hr>
				
					<div class="payment-step">
						<h2><span class="number">3</span> Your Account Credentials</h2>
						<br>
						<div class="row">
							<div class="col-md-6">
								<div class="input-groups">
									<input type="email" class="form-control input-lg" name="email" placeholder="Email Address *" maxlength="70" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-groups">
									<input type="password" class="form-control input-lg" name="password" placeholder="Password *" maxlength="30" required>
								</div>
							</div>
						</div>					
					</div>
					
					
					<div class="payment-step">
						<h2><span class="number">4</span> Your Payment Info</h2>
						<img src="<?php echo base_url('assets/themes/default/images/payments-accepted.jpg'); ?>" id="paymentsAccepted" class="img-responsive" alt="Payment Accepted" width="179" height="36">
						<br>
						<div class="row">
							<div class="col-md-6">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="cc_fname" placeholder="Frist Name *" maxlength="40" required="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="cc_lname" placeholder="Last Name *" maxlength="40" required="">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="credit_card" placeholder="Card Number *" maxlength="20" required>
								</div>
							</div>
							<div class="col-md-4">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="zip" placeholder="Postal/Zip *" maxlength="5" required>
								</div>
							</div>
							<div class="col-md-2">
								<div class="input-groups">
									<input type="text" class="form-control input-lg" name="ccv" placeholder="CCV *" maxlength="4" required>
								</div>
							</div>
						</div>	

						<div class="row">
							<div class="col-md-5">
								<select name="credit_card_month" id="credit_card_month" class="form-control input-lg" required>
									<option value="01">1 - January</option>
									<option value="02">2 - February</option>
									<option value="03">3 - March</option>
									<option value="04">4 - April</option>
									<option value="05">5 - May</option>
									<option value="06">6 - June</option>
									<option value="07">7 - July</option>
									<option value="08">8 - August</option>
									<option value="09">9 - September</option>
									<option value="10">10 - October</option>
									<option value="11">11 - November</option>
									<option value="12">12 - December</option>
								</select>
							</div>
							<div class="col-md-3">
								<select name="credit_card_year" id="credit_card_year" class="form-control input-lg" required>
									<?php
										$year = date('Y');
										for($i=0;$i<20;$i++) {
											echo '<option value="'.$year.'">'.$year.'</option>';
											$year++;
										}
									?>
								</select>
							</div>
						</div>
						<br>
						<div class="well">
							<div class="row">
								<div class="col-xs-5">
									<input id="promocode" type="text" name="promo" class="form-control" placeholder="Promo Code?" maxlength="20">
								</div>
								<div class="col-xs-2">
									<div id="searchPromo" class="btn btn-primary"><i class="fa fa-search"></i> </div>
								</div>
								<div class="col-md-5">
									<div id="userTotal">
										<p>Total: $<?php echo number_format($payment_settings[2]->setting_value, 2); ?> Yearly</p>
									</div>
								</div>
							</div>
						</div>
						
					</div>
					<div id="otherSettings"></div>
					<hr>
					
					<div class="payment-step">
						<p>By clicking "<b>Join Now</b>" you are also agreeing to our <a href="https://network4rentals.com/terms-of-service" target="_blank">Terms of Service</a> and our <a href="https://network4rentals.com/privacy-policy/" target="_blank">Privacy Policy</a>.</p>
						<br>
						<div class="row">
							<div class="col-md-6">
								<button id="submit" class="btn btn-lg btn-primary">Join Now</button>
							</div>
							<div class="col-md-6 text-right">
								<h3 id="secure-server"><i class="fa fa-lock"></i> Secure Server</h3>
							</div>
						</div>
						
					</div>
				
				<?php echo form_close(); ?>
				
			</div>
			<div class="panel-footer">
			
			</div>
		</div>
		
	</div>
	
	<div class="col-md-3 hidden-sm hidden-xs">
		<div class="panel panel-blue">
			<div class="panel-heading">
				<h4>Why?</h4>
			</div>
			<div class="panel-body">
				<ol id="benefits">
					<li><i class="fa fa-check-circle-o fa-lg"></i> Website</li>
					<li><i class="fa fa-check-circle-o fa-lg"></i> Listed In Sidebar</li>
					<li><i class="fa fa-check-circle-o fa-lg"></i> Listed on Resource Page</li>
					<li><i class="fa fa-check-circle-o fa-lg"></i> Searchable on N4RLocal.com</li>
				</ol>
			</div>
			<div class="panel-footer">
				
			</div>
		</div>
	</div>
	
</div>