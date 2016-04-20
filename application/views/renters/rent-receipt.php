<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
	$discount = (int)$renter_info[1]['auto_pay_discount'];
	$rent = (int)$renter_info[1]['payments'];
	
	$sub_total = $rent - $discount;
	$fee = $sub_total*.01;
	$modalAmount = $sub_total+$fee;
	

	
?>
<div class="alert alert-info">
	<i class="fa fa-exclamation-triangle"></i> Double Check Your Landlord's Info Before Submitting A Rent Receipt
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
				<div class="row">
					<div class="col-lg-6">
						<p><b>Rent Amount: </b><br>$<?php echo $renter_info[1]['payments']; ?> a Month</p>
						<p><b>On-line Payments Accepted: </b><br>
						<?php 
							if($renter_info[1]['payments_allowed'] == 'y') {
								echo '<span class="label label-success">Yes</span>';
							} else {
								echo '<span class="label label-danger">No</span>';
							}
							
						?></p>
						
					</div>
					<div class="col-lg-6">
						<p><b>Partial Payments Accepted: </b><br>
						<?php 
							if($renter_info[1]['payments_allowed'] == 'y') {
								echo '<span class="label label-success">Yes</span>';
							} else {
								echo '<span class="label label-danger">No</span>';
							}
							
						?></p>
						
						<?php 
							if(!empty($renter_info[1]['min_payment'])) {
								echo '<p><b>Min Payment Amount: </b><br>';
								echo '$'.$renter_info[1]['min_payment'].'</p>';
							}
							
							
							if($renter_info[1]['payments_allowed'] == 'y') {
								echo '<p class="text-center"><a style="margin-bottom: 15px;" href="'.base_url().'renters/pay-rent" class="btn btn-primary">Pay Rent Online</a></p>';
							}
						?>
						
					</div>
				</div>
				<?php if($renter_info[1]['auto_pay'] == 'y') { ?>
					<?php
						foreach($payments as $row) {
							if($row['recurring_payment'] == 'y') {
								$paymentId = $row['id'];
							}
						}
					?>
						<div class="alert alert-info">
							<p style="font-size: 1.3em;"><i class="fa fa-exclamation-triangle"></i> Auto Payment Is Active</p>
							<div class="row">
								<div class="col-lg-6">
									<p><b>Next Payment Date:</b></p>
									
									<p><?php echo $autopayment_info->next_date; ?></p>
								</div>
								<div class="col-lg-6">
									<p><b>Auto Amount:</b></p>
									<p>$<?php echo number_format($autopayment_info->amount, 2); ?></p>
								</div>
							</div>
							<a href="<?php echo base_url('renters/cancel-auto-pay/'.$paymentId); ?>" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Cancel Autopay</a>
						</div> 
				<?php } ?>
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
					<?php 
						if(!empty($renter_info[1]['auto_pay_discount']) && $landlord_info['payments_allowed'] == 'y' &&$renter_info[1]['auto_pay'] != 'y') {
							echo '<div class="alert alert-info">Offers a rent discount of $'.$renter_info[1]['auto_pay_discount'].' if you sign up for auto-pay.</div>';
						}	
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-6">
		<?php echo form_open('renters/view-payment-history/'.$landlord_info['ref_id'], array('class'=>'payment-info')); ?>
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-check"></i> Payment Details
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-md-6">
							<label><i class="fa fa-asterisk text-danger"></i> Amount Paid: <small>(Whole Numbers Only)</small></label>
							<input type="text" class="form-control input-sm numbersOnly" name="amount" required>
						</div>
						<div class="col-md-6">
							<label><i class="fa fa-asterisk text-danger"></i> Date Paid On:</label>
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
					<br>
					<button type="submit" class="btn btn-primary">Add Receipt</button>
				</div>
			</div>
		</form>	
	</div>
	<div class="col-lg-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-check"></i> Last 5 Payments
			</div>
			<div class="panel-body">
				<?php
					echo '<ul id="quickViewPayments">';
						$count = 0;
						echo '<li>';
							echo '<div class="row">';
								echo '<div class="col-xs-4"><b>Amount</b></div>';
								echo '<div class="col-xs-4"><b>Payment Type</b></div>';
								echo '<div class="col-xs-4"><b>Date</b></div>';
							echo '</div>';
							echo '<hr>';
						echo '</li>';
						foreach($payments as $val) {
							if($count==4) {
								return false;
							} else {
								echo '<li>';
									echo '<div class="row">';
										echo '<div class="col-xs-4">$'.number_format($val['amount'], 2).'</div>';
										echo '<div class="col-xs-4">'.ucwords($val['payment_type']).'</div>';
										echo '<div class="col-xs-4">'.date('m-d-Y', strtotime($val['paid_on'])).'</div>';
									echo '</div>';
									echo '<br>';
								echo '</li>';
							}
							$count++;
						}
					echo '</ul>';
					echo '<hr><a class="btn btn-primary" href="https://network4rentals.com/network/renters/view-payment-history/'.$renter_info[1]['id'].'">View All Payments</a>';
				?>
			</div>
		</div>
	</div>
</div>



