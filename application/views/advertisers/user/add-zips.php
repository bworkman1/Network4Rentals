<h2><i class="fa fa-map-marker text-primary"></i> Additional Advertising Opportunities</h2>
<p>You can purchase additional advertising space and target spacific user types such as a landlord or a contractor. You can only advertise one spot per zip/user side at a time. Your ad will appear down the right side of the page like the example one to the right of this page. Once you purchase your ad you will be able to see how many users have seen your ad, and how many have clicked on it.</p>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}	
?>
<br>
<div id="adTotal" data-price="<?php echo $admin_settings[0]->setting_value; ?>"></div>
<?php echo form_open('', array('id'=>'ad-payment')); ?>
	<div class="row">
		<div class="col-md-6">
			<div class="well well-sm">
				<h3><span class="number">1</span> Add the users you want to advertise to</h3>
				<br>
				<div class="row">
					<div class="col-xs-5">
						<div class="form-group">
							<select id="userType" class="form-control">
								<option value="">Select User Type</option>
								<option>Landlords</option>
								<option>Renters</option>
								<option>Contractors</option>
								<option>Advertisers</option>
							</select>
						</div>
					</div>
					<div class="col-xs-5">
						<div class="form-group">
							<input type="text" class="form-control" id="zipCode" placeholder="Enter Zip" maxlength="5" required>
						</div>
					</div>
					<div class="col-sm-2">
						<button id="addZip" class="btn btn-primary"><i class="fa fa-plus"></i></button>
					</div>
				</div>
				<div id="help-text" class="text-danger"></div>
			</div>
		</div>
		
		<div class="col-md-6">
			<div id="advertiserList" class="well well-sm">
				<h3><i class="fa fa-shopping-cart text-primary"></i> Selected Options</h3>
				<ol class="list-group"><li id="remove" class="list-group-item">Select users and zips</li></ol>
			</div>
		</div>
		
	</div>
	<hr>

	<div id="step-2" class="well well-sm fade">
		<h3><span class="number">2</span> Select Payment Options</h3>
		<br>
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<select id="paymentLength" name="length" class="form-control">
						<option value="">Ad Length</option>
						<option value="3">3 Months</option>
						<option value="6">6 Months</option>
						<option value="12">12 Months</option>
					</select>
				</div>
			</div>
			<div class="col-md-8">
				<div id="totalCost"></div>
			</div>
		</div>
		<div id="payment-help" class="text-danger"></div>
	</div>

	<div id="step-3" class="well well-sm fade">
		<h3><span class="number">3</span> Make Payment</h3>
		<br>
		<div class="row">	
			<div class="col-md-8">
				<div class="form-horizontal">
					<fieldset>					
						<div class="form-group">
							<label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="name" id="card-holder-name" placeholder="Card Holder's Name" maxlength="50" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="card-number">Card Number</label>
							<div class="col-sm-9">
								<input type="text" class="form-control" name="credit_card" id="card-number" placeholder="Debit/Credit Card Number" required>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
							<div class="col-sm-9">
								<div class="row">
									<div class="col-xs-3">
										<select class="form-control col-sm-2" name="expiry_month" id="expiry-month" required>
											<option>Month</option>
											<option value="01">Jan (01)</option>
											<option value="02">Feb (02)</option>
											<option value="03">Mar (03)</option>
											<option value="04">Apr (04)</option>
											<option value="05">May (05)</option>
											<option value="06">June (06)</option>
											<option value="07">July (07)</option>
											<option value="08">Aug (08)</option>
											<option value="09">Sep (09)</option>
											<option value="10">Oct (10)</option>
											<option value="11">Nov (11)</option>
											<option value="12">Dec (12)</option>
										</select>
									</div>
									<div class="col-xs-3">
										<select class="form-control" name="expiry_year" required>
										<?php 
											$year = date('Y');
											for($i=0;$i<9;$i++) {
												echo '<option value="'.substr(($year+$i), 2).'">'.($year+$i).'</option>';
											}
										?>
										</select>
									</div>
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label" for="cvv">Card CVV</label>
							<div class="col-sm-3">
								<input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code" maxlength="4" required>
							</div>
						</div>
					  
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<div id="paymentFeedback"></div>
								<button type="submit" id="submitAdPayment" class="btn btn-primary">Submit Payment</button>
							</div>
						</div>
						
					</fieldset>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="panel panel-white">
					<div class="panel-heading">Overview</div>
					<div class="panel-body">
						<div class="checkoutList"><b>Total Ads:</b> <span id="finalAds">5</span></div>
						<div id="finalPrice" class="checkoutList"><b>Total:</b> $<span class="shoppingTotal">14.99</span> for <span id="duration">12 months</span></div>
						<div class="checkoutList"><b>Expires:</b> <span id="expires">05/15/2016</span></div>
					</div>
					<div class="panel-footer">
						<b>Grand Total: $<span class="shoppingTotal">14.99</span></b>
					</div>
				</div>			
			</div>
		</div>
	</div>
	<div id="selected">
		<input type="hidden" value="" id="amountTotal" name="amount">
	</div>
<?php echo form_close(); ?>






