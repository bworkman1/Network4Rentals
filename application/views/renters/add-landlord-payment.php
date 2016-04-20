<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg fa-fw"></i> Error:</b>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg fa-fw"></i>Success:</b>'.$this->session->flashdata('success').'</div>';
	}
	$discount = (int)$renter_info[1]['auto_pay_discount'];
	$rent = (int)$renter_info[1]['payments'];
	
	$sub_total = $rent - $discount;
	$modalAmount = $sub_total+5;
		
?>
<div class="alert alert-info">
	<i class="fa fa-exclamation-triangle"></i> Double Check Your Landlord's Info Before Submitting A Payment
</div>
<hr>

<div class="row">
	<div class="col-lg-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-home"></i> Rental Details
			</div>
			<div class="panel-body payment-details">
				<div class="row">
					<div class="col-lg-6">
						<p><b>Name: </b><br><?php echo $renter_info[0]['name']; ?></p>
						<p><b>Address: </b><br><?php echo $renter_info[1]['rental_address']; ?> <br><?php echo $renter_info[1]['rental_city']; ?>, <?php echo $renter_info[1]['rental_state'].' '.$renter_info[1]['rental_zip']; ?></p>
						
					</div>
					<div class="col-lg-6">
						<p><b>Phone: </b><br><?php echo "(".substr($renter_info[0]['phone'], 0, 3).") ".substr($renter_info[0]['phone'], 3, 3)."-".substr($renter_info[0]['phone'],6); ?></p>
						<?php if(!empty($renter_info[0]['alt_phone'])) { ?>
							<p><b>Alternative: </b><br><?php echo "(".substr($renter_info[0]['alt_phone'], 0, 3).") ".substr($renter_info[0]['alt_phone'], 3, 3)."-".substr($renter_info[0]['alt_phone'],6); ?></p>
						<?php } ?>
					</div>
				</div>
				<p><b>Email: </b><br><?php echo $renter_info[0]['email']; ?></p>
				
			</div>
		</div>			
	</div>
	<div class="col-lg-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-user"></i> Landlord Details
			</div>
			<div class="panel-body payment-details">
				<div id="landlord-details">
					<div class="row">
						<div class="col-lg-6">
							<?php if(!empty($landlord_info['bName'])) { ?>
								<p><b>Business Name: </b><br><?php echo $landlord_info['bName']; ?></p>
							<?php } ?>
							<p><b>Name: </b><br><?php echo $landlord_info['name']; ?></p>
							
							
						</div>
						<div class="col-lg-6">
							<p><b>Phone: </b><br><?php echo "(".substr($landlord_info['phone'], 0, 3).") ".substr($landlord_info['phone'], 3, 3)."-".substr($landlord_info['phone'],6); ?></p>
							<?php if(!empty($landlord_info['alt_phone'])) { ?>
								<p><b>Alternative: </b><br><?php echo "(".substr($landlord_info['alt_phone'], 0, 3).") ".substr($landlord_info['alt_phone'], 3, 3)."-".substr($landlord_info['alt_phone'],6); ?></p>
							<?php } ?>
						</div>
					</div>
					<p><b>Address: </b><br><?php echo $landlord_info['address']; ?> <?php echo $landlord_info['city']; ?>, <?php echo $landlord_info['state'].' '.$landlord_info['zip']; ?></p>
					<p><b>Email: </b><br><?php echo $landlord_info['email']; ?></p>
					
					<?php if(empty($payment_settings)) { ?>
						<p>If you would like to make rent payments online, let your landlord know by, click the button below.
						<br><a href="#" class="btn btn-primary"> Notify Landlord </a><br><br></p>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="white-box">
	<h3 style="border-bottom: 1px solid #dedede;"><i class="fa fa-money fa-fw"></i> Payment Details</h3>
	<div class="row">
		<div class="col-lg-6">
			<p><b>Rent Amount: </b><br>$<?php echo $renter_info[1]['payments']; ?> a Month</p>
			<?php if($payment_settings) { ?>
				<p><b>On-line Payments Accepted: </b><br>
				<?php 
					if($renter_info[1]['payments_allowed'] == 'y') {
						if( $payment_settings->allow_payments == 'y') {
							echo '<span class="label label-success"><i class="fa fa-check-circle"></i> Yes</span>';
						} else {
							echo '<span id="partialPay" class="label label-danger"><i class="fa fa-times-circle"></i> No</span>';
						}
					} else {
						echo '<span id="partialPay" class="label label-danger"><i class="fa fa-times-circle"></i> No</span>';
					}
					
				?></p>
			<?php } ?>
		</div>
		<?php if($payment_settings->allow_payments == 'y') { ?>
			<div class="col-lg-6">
				<?php if($payment_settings) { ?>
					<p><b>Partial Payments Accepted: </b><br>
					<?php 
						if($renter_info[1]['partial_payments'] == 'y' AND $payment_settings->allow_payments == 'y') {
							echo '<span class="label label-success"><i class="fa fa-check-circle"></i> Yes</span>';
						} else {
							echo '<span id="partialPay" class="label label-danger"><i class="fa fa-times-circle"></i> No</span>';
						}
						
					?></p>
					
					<?php 
						if(!empty($renter_info[1]['min_payment']) AND $payment_settings->allow_payments == 'y') {
							echo '<p><b>Min Payment Amount: </b><br>';
							echo '$'.number_format($renter_info[1]['min_payment'], 2).'</p>';
						}
						
					?>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>

<?php if($renter_info[1]['auto_pay'] == 'y') { ?>
		<div class="alert alert-danger">
			<div class="row">
				<div class="col-md-6">
			<i class="fa fa-calendar fa-3x fa-fw pull-left" style="margin-top: 5px"></i> <h3 style="margin:0 0 5px 0; border-bottom: 1px solid #fff; color: #fff; display: inline-block">You Already Setup Auto Pay</h3><p>Billing details we have on file.</p>
				</div>
				<div class="col-md-3">
					<p><b>Next Payment Date:</b></p>
					<p>05-12-2016</p>
					<p><?php echo $autopayment_info->next_date; ?></p>
				</div>
				<div class="col-md-3">
					<p><b>Auto Amount:</b></p>
					<p>$<?php echo number_format($autopayment_info->amount, 2); ?></p>
				</div>
			</div>
		</div>
<?php } ?>

<div class="row">
	<div class="col-md-6">
		<?php 
			if($renter_info[1]['discount_payment']>0 && $landlord_info['payments_allowed'] == 'y') {
				echo '<div class="alert alert-info"><i class="fa fa-laptop fa-4x fa-fw pull-left" style="margin-top: 0px"></i> <h3 style="margin:0 0 5px 0; border-bottom: 1px solid #fff; color: #fff; display: inline-block">Online Payment Discount</h3><p>Pay online and recieve a discount of  $'.number_format($renter_info[1]['discount_payment'], 2).'</p><div class="clearfix"></div></div>'; 
			}	
		?>
	</div>
	<div class="col-md-6">
		<?php 
			if($renter_info[1]['auto_pay_discount']>0 && $landlord_info['payments_allowed'] == 'y') {
				echo '<div class="alert alert-info"><i class="fa fa-calendar fa-4x fa-fw pull-left" style="margin-top: 5px"></i> <h3 style="margin:0 0 5px 0; border-bottom: 1px solid #fff; color: #fff; display: inline-block">Auto Payment Discount</h3><p>Setup auto payments and save $'.number_format($renter_info[1]['auto_pay_discount'], 2).' per month.</p><div class="clearfix"></div></div>'; 
			}	
		?>
	</div>
</div>

<div class="white-box">
	<h4 class="pull-left"><i class="fa fa-question text-primary"></i> Paid rent in person? Add a rent receipt payment.</h4><button class="btn btn-primary pull-right" data-toggle="modal" data-target="#rentReceipt">Add Receipt</button>
	<div class="clearfix"></div>
</div>

<?php if($payment_settings->allow_payments == 'n') { ?>
	<div class="alert alert-danger">
		<div class="row">
			<div class="col-sm-8"><i class="fa fa-exclamation-triangle"></i> Your landlord has not setup their account to accept online payments yet.</div>
			<div class="col-sm-4 text-right"><a href="<?php echo base_url('renters/request-online-payments'); ?>" class="btn btn-primary">Request Online Payments Option</a></div>
		</div>
	</div>
<?php } ?>

<?php if($landlord_info['payments_allowed'] == 'y' && $renter_info[1]['auto_pay'] != 'y') { ?>
<div class="row">
	<div class="col-lg-6">
		<?php if($landlord_info['address_locked'] == '1') { ?>
			<?php if($payment_settings->allow_payments == 'y') { ?>
				<?php if($payment_settings->accept_echeck == 'y') { ?>
					<?php if($landlord_info['payments_allowed'] == 'y') { ?>
					<?php echo form_open('renters/pay-rent-by-check', array('id'=>'payment-form')); ?>
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-check"></i> Payment Details
				</div>
				<div class="panel-body">
					<div class="form-group">
						<div class="form-group has-feedback">
							<label class="control-label" for="name">Account Holder Name:</label>
							<input type="text" name="name"class="form-control payment_name" value="<?php echo $renter_info[0]['name']; ?>" id="name" aria-describedby="name" maxlength="50" required>
							<span class="glyphicon form-control-feedback name" aria-hidden="true"></span>
							<span id="name-error" class="text-danger"></span>
						</div>
					</div>

					<div class="form-group">
						<div class="form-group has-feedback">
							<label class="control-label" for="bank">Bank Name:</label>
							<input type="text" class="form-control bank" name="bank_name" id="bank" value="<?php echo set_value('bank_name'); ?>" aria-describedby="bank" maxlength="50" required>
							<span class="glyphicon form-control-feedback bank" aria-hidden="true"></span>
							<span id="bank-error" class="text-danger"></span>
						</div>
					</div>
					

					<div class="radio">
						<label>
							<input type="radio" name="payment_type" value="1" checked>
							Pay from my Checking
						</label>
					</div>
					<div class="radio">
					  <label>
							<input type="radio" name="payment_type" value="2">
							Pay from my Savings
					  </label>
					</div>
					
					<div class="form-group">
						<div class="form-group has-feedback">
							<label class="control-label" for="routing-num">ABA/Routing Number:</label>
							<input type="text" class="form-control bank numbersOnly routing-number" value="<?php echo set_value('route_num'); ?>" name="route_num" id="routing-number" aria-describedby="routing-number" maxlength="9" required>
							<span class="glyphicon form-control-feedback routing-number" aria-hidden="true"></span>
							<span id="routing-number-error" class="text-danger"></span>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-group has-feedback">
							<label class="control-label" for="routing-num">Bank Account Number:</label>
							<input type="text" class="form-control numbersOnly" name="account_num" value="<?php echo set_value('acount_num'); ?>" id="bank-number" aria-describedby="bank-number" maxlength="20" required>
							<span class="glyphicon form-control-feedback bank-number" aria-hidden="true"></span>
							<span id="bank-number-error" class="text-danger"></span>
						</div>
					</div>
					
					<div class="checkbox">
						<label class="setAutopay">
							<input type="checkbox" name="autopay" class="autopay" value="y">
							Auto Payments? 
						</label>
					</div>
					<div class="checkbox understandAutoPay" style="display: none">
						<label>
							<input type="checkbox" value="y" name="understand" class="understandAutoPayBox">
							I understand how auto pay works and when my payments will be deducted.
						</label>
					</div>			
					<a href="#" data-toggle="modal" data-target="#reoccuringPayments"><small>Learn More</small></a>
					<hr>
					<div id="startDate" class="form-group col-lg-6" style="display: none">
						<div class="form-group has-feedback">
							<label class="control-label" for="start-date">Payment Start Date:</label>
							<div id="insertStartDate">
								
							</div>
							<span class="glyphicon form-control-feedback check-number" aria-hidden="true"></span>
							<span id="start-date-error" class="text-danger"></span>
						</div>
					</div>		
					<div class="clearfix"></div>
					
					<div id="discountNote"></div>
					
					<div class="row">
						<div class="col-md-6">
							<label>Amount:</label>
							<div class="input-group">
								<input type="text" name="amount" class="form-control pay-amount money" data-autodiscount="<?php echo $renter_info[1]['auto_pay_discount']; ?>" data-minamount="<?php echo $renter_info[1]['min_payment']; ?>" data-discount="<?php echo number_format($renter_info[1]['discount_payment'], 2); ?>" data-partial="<?php echo $renter_info[1]['partial_payments']; ?>" data-rent="<?php echo $renter_info[1]['payments']; ?>" maxlength="10" aria-label="Amount" required>
								<div class="total-error"></div>
							
							</div>
						</div>
						<div class="col-md-6">
							<label>Fee:</label>
							<div class="input-group">
								<input type="text" value="$5" maxlength="10" class="form-control fee" disabled required>
							</div>
						</div>
					</div>
					<br>
					 
					<hr>
					<div id="addAmount"></div>
					<button type="submit" class="btn btn-primary submitPayment">Submit Payment</button>
					
				</div>
			</div>
			</form>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
					
	</div>
	<div class="col-lg-6">
		<?php if($landlord_info['address_locked'] == '1') { ?>
			<?php if($payment_settings->allow_payments == 'y') { ?>
				<?php if($payment_settings->accept_echeck == 'y') { ?>
					<?php if($landlord_info['payments_allowed'] == 'y') { ?>
						<div class="panel panel-warning">
							<div class="panel-heading">
								Where To Find Checking Details
							</div>
							<div class="panel-body">
								<img src="<?php echo base_url(); ?>public-images/helpwithcheckinginfo.png" class="img-responsive img-center">
							</div>
						</div>
					
					
						<br>
					<?php } ?>
				<?php } ?>
			<?php } ?>
		<?php } ?>
	</div>
</div>
<?php } ?>
	
<hr>

<div class="modal fade" id="rentReceipt" tabindex="-1" role="dialog" aria-labelledby="rentReceipt" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<?php echo form_open('renters/view-payment-history/'.$landlord_info['ref_id'], array('class'=>'payment-info')); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Rent Receipt</h4>
      </div>
      <div class="modal-body">
        <div class="">
				
					<h4>Payment Details:</h4>	
					<div class="row">
						<div class="col-md-6">
							<label><i class="fa fa-asterisk text-danger"></i> Amount Paid:</label>
							<input type="text" class="form-control input-sm money" name="amount" required>
						</div>
						<div class="col-md-6">
							<label><i class="fa fa-asterisk text-danger"></i> Paid On:</label>
							<input type="text" class="form-control input-sm datepicker" autocomplete="off" name="paid_on" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><i class="fa fa-asterisk text-danger"></i> Payment Type:</label>
							<select name="payment_type" class="form-control" required>
								<option value="">Select One...</option>
								<option value="cash">Cash</option>
								<option value="check">Check</option>
								<option value="money order">Money Order</option>
								<option value="other">Other</option>
							</select>
						</div>
					</div>
					<label>Notes:</label>
					<textarea class="form-control" name="notes"></textarea>
					
					
				
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>
	  <?php echo form_close(); ?>
    </div>
  </div>
</div>

<div class="modal fade" id="reoccuringPayments" tabindex="-1" role="dialog" aria-labelledby="reoccuringPayments" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-refresh text-warning"></i> Re-Occurring Payments</h4>
      </div>
      <div class="modal-body">
        <p>By selecting autopay you are giving the system authorization to draw your set amount plus transaction fee each month on the day you have selected. You can cancel autopay at any time by simply contacting your bank and selecting the cancel autopay button on my rental info page.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">I Understand</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="autoPayFail" tabindex="-1" role="dialog" aria-labelledby="autoPayFail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-danger"></i> Unable To Set Auto-Pay</h4>
      </div>
      <div class="modal-body">
		<p>In order to set re-occurring payments up the rent amount must be equal to your rent amount in your account. If your rent amount is incorrect you will have to message your landlord to fix this.</p>
		<p>In order to fix this please select the re-occurring payment check box and enter your full rent amount.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirm-payment" tabindex="-1" role="dialog" aria-labelledby="confirm-payment" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="confirm"><i class="fa fa-check text-success"></i> Confirm Payment</h4>
      </div>
      <div class="modal-body">
		<div id="confirmData">
			
		</div>
      </div>	
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="confirm-btn">Submit Payment</button>
      </div>
    </div>
  </div>
</div>
