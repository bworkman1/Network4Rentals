<?php
	$expired = $this->session->userdata('expiredSub');
	echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> Your subscription has expired, in order to continue using our services you will need to subscribe to another year of services.</div>';
	
	if(isset($errors)) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$errors.'</div>';
	}
?>

<div class="panel panel-primary">	
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-credit-card"></i> Payment</h3>
	</div>
	<div class="panel-body">
		<div class="row">
		<div class="col-md-6">
	
		  <?php echo form_open("landlord-associations/update-payment-settings", array("id"=>"payment", "class"=>"form-horizontal", "role"=>"form")); ?>
			<fieldset>
			  <legend>Payment</legend>
			  <div class="form-group">
				<label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" name="card_holder_name" id="card-holder-name" placeholder="Card Holder's Name"  data-toggle="tooltip" data-placement="top" title="Must have first and last name" maxlength="30" value="<?php echo $_POST['card_holder_name']; ?>" required>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3 control-label" for="card-number">Card Number</label>
				<div class="col-sm-9">
				  <input type="text" class="form-control" name="card_number" id="card-number" placeholder="Debit/Credit Card Number" maxlength="19" data-toggle="tooltip" data-placement="top" title="Invalid Card Number" value="<?php echo $_POST['card_number']; ?>" required>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
				<div class="col-sm-9">
				  <div class="row">
					<div class="col-xs-6">
					  <select class="form-control col-sm-2" name="expiry_month" id="expiry-month" required>
						<option value="">Month</option>
						<?php
							$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
							for($i=0;$i<12;$i++) {
								if(sprintf("%02d", ($i+1)) == $_POST['expiry_month']) {
									echo '<option selected value="'.sprintf("%02d", ($i+1)).'">'.$months[$i].' ('.sprintf("%02d", ($i+1)).')</option>';
								} else {
									echo '<option value="'.sprintf("%02d", ($i+1)).'">'.$months[$i].' ('.sprintf("%02d", ($i+1)).')</option>';
								}
							}
						?>
					  </select>
					</div>
					<div class="col-xs-6">
					  <select class="form-control" name="expiry-year" id="expiry-year" required>
						<option value="">Year</option>
						<?php
							$baseYear = date('Y');
							for($i=0;$i<8;$i++) {
								if(($baseYear+1) == $_POST['expiry_month']) {
									echo '<option selected>'.($baseYear+$i).'</option>';
								} else {
									echo '<option>'.($baseYear+$i).'</option>';
								}
							}
						?>
					  </select>
					</div>
				  </div>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-3 control-label" for="cvv">Card CVV</label>
				<div class="col-sm-6">
				  <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code" maxlength="4" required>
				</div>
			  </div>
			  <div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
				  <button type="submit" class="checkPayments btn btn-success">Pay Now</button>
				</div>
				<div class="col-sm-3 submitError">
				
				</div>
			  </div>
			</fieldset>
		  </form>
		</div>
		<div class="col-md-4  col-md-offset-2 text-center">
			<br>
			<p><i class="text-success fa fa-lock fa-fw fa-4x"></i> <br>Your connection to this site is private and secure.</p>
			<hr>
			<p>If you are seeing this and believe its in error contact us and we will investigate to find out what is going on.</p> 
			<a href="https://network4rentals.com/help-support/" class="btn btn-primary">Contact Us</a>
		</div>
	</div>
</div>
