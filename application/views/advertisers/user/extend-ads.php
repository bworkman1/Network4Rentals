<div id="extend">
	<h2><i class="fa fa-calendar-o text-primary"></i> Extend Premium Ads</h2>
	<hr>
	<h3><span class="number">1</span> Select Premium Ads</h3>
	<div class="table-responsive">
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Select</th>
					<th>Expires</th>
					<th>Zip</th>
					<th>Users</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$userTypeArray = array('', 'Landlords', 'Renters', 'Contractors', 'Advertisers');
					foreach($my_zips as $val) {
						echo '<tr>';
							echo '<td><input type="checkbox" class="renew" data-id="'.$val->id.'"></td>';
							echo '<td>'.date('m-d-Y', strtotime($val->deactivation_date)).'</td>';
							echo '<td>'.$val->zip_purchased.'</td>';
							echo '<td>'.$userTypeArray[$val->service_purchased].'</td>';
						echo '</tr>';
					}
				?>
			
			</tbody>
		</table>
	</div>
	<hr>
	<form id="extendAds">

		<div id="step-2" class="well well-sm fade in">
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
					<div id="totalCost">To extend <u>1</u> ad(s) for <u>3</u> months will only be <span class="text-success"><u>$23.97</u></span></div>
				</div>
			</div>
			<div id="payment-help" class="text-danger"></div>
		</div>
		
		<div id="step-3" class="row">
			<div class="col-md-9">
				<div class="well well-sm">
					<h3><span class="number">3</span> Enter Payment Details</h3>
					<hr>
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
			</div>
			<div class="col-md-3">
				<div class="panel panel-white">
					<div class="panel-heading">Overview</div>
					<div class="panel-body">
						<div class="checkoutList"><b>Total Ads:</b> <span id="finalAds">1</span></div>
						<div id="finalPrice" class="checkoutList"><b>Total:</b> $<span class="shoppingTotal">23.97</span> for <span id="duration">3 Months</span></div>
						<div class="checkoutList"><b>Expires:</b> <span id="expires">3/31/2016</span></div>
					</div>
					<div class="panel-footer">
						<b>Grand Total: $<span class="shoppingTotal">23.97</span></b>
					</div>
				</div>	
			</div>
		</div>
		<div id="selections"></div>
		<input type="hidden" name="amount" class="charge" required>
	</form>

	<div id="charge" data-monthly="<?php echo $admin_settings[0]->setting_value; ?>"></div>
</div>