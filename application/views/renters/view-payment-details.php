<?php
	if($info[1]['reason'] == '') {
		$info[1]['reason'] = 'No notes';
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

<div class="row">
	<div class="col-md-4">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-user"></i> Landlord Details
			</div>
			<div class="panel-body payment-details">
				<p id="landlordName" data-altid="<?php echo $landlord_info->id; ?>" data-landlordname="<?php if(!empty($landlord_info->bName)) {echo $landlord_info->bName;}else{echo $landlord_info->landlord_name;} ?>"><b>Paid To: </b><br><?php if(!empty($landlord_info->bName)) {echo $landlord_info->bName;}else{echo $landlord_info->landlord_name;} ?></p>
				<?php if(!empty($landlord_info->bName)) {echo '<p><b>Landlord/Contact Name: </b><br>'. ucwords(htmlentities($landlord_info->name)).'</p>';} ?>
				<p><b>Address:</b><br> <?php echo  htmlentities(ucwords($landlord_info->address)); ?> <?php echo  htmlentities(ucwords($landlord_info->city)).' '. htmlentities($landlord_info->state).', '. htmlentities($landlord_info->zip); ?></p>
				<p><b>Email:</b> <br><?php echo  htmlentities(ucwords($landlord_info->email)); ?> </p>
				<p><b>Phone:</b> <br><?php echo "(".substr($landlord_info->phone, 0, 3).") ".substr($landlord_info->phone, 3, 3)."-".substr($landlord_info->phone,6); ?>  </p>
				<p><b>Note On Payment:</b><br> <?php echo htmlentities($info[1]['reason']); ?></p>
			</div>
		</div>
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-gears"></i> Options
			</div>
			<div class="panel-body payment-details text-center">
				<a class="btn btn-primary" href="<?php echo base_url(); ?>renters/view-payment-history/<?php echo htmlentities($info[1]['ref_id']); ?>"><i class="fa fa-reply"></i> Go Back</a>
				<a class="btn btn-primary viewNotes" href="#"  data-payment="<?php echo $this->uri->segment('3'); ?>"><i class="fa fa-file"></i> View Notes</a>
				<?php
					if($info[0]['auto_pay'] == 'y') {
						echo '<button class="btn btn-danger" data-toggle="modal" data-target="#cancelAutoPay"><i class="fa fa-times"></i> Cancel Auto Pay</button>';
					}
				?>
				
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-money"></i> Payment Details 
			</div>
			<div class="panel-body payment-details">	
				<p><b>Status: </b><br><?php echo htmlentities(ucwords($info[1]['status'])); ?></p>
				<p><b>Submitted On: </b><br><?php echo date('m-d-Y', strtotime($info[1]['paid_on'])); ?></p>
				<p><b>Payment Type: </b><br><?php echo  htmlentities(ucwords($info[1]['payment_type'])); ?></p>
				<p><b>Amount: </b><br>$<?php echo  number_format($info[1]['amount'], 2); ?></p>
				<?php
					if($info[1]['disputed_date'] != '0000-00-00') {
						echo '<p><b>Disputed On: </b><br>'.date('m-d-Y', strtotime($info[1]['disputed_date'])+3600).'</p>';
					}
					if($info[1]['resolved_date'] != '0000-00-00') {
						echo '<p><b>Resolved On: </b><br>'.date('m-d-Y', strtotime($info[1]['resolved_date'])).'</p>';
					}
				?>
				<?php
					$info[1]['status'] == 'disputed';
					if($info[1]['status'] == 'disputed') {
						echo '<div class="well">';
						echo '<h4><b>Disputed?</b></h4><p>If you feel this is an error or would like to work it out, contact your landlord using our message center.</p>';
						$sql = "SELECT link_id FROM renter_history WHERE id = ? AND tenant_id = ? LIMIT 1";
						$ids = $this->session->userdata('user_id');
						$result = $this->db->query($sql, array($info[0]['id'], $ids));	
						if ($result->num_rows() > 0) {
							$row = $result->row();
							echo '<a href="'.base_url().'renters/message_landlord/'.$row->link_id.'" class="btn btn-warning btn-xs"><i class="fa fa-comments-o"></i> Message Landlord</a>';
						}
						echo '</div>';
					}
				?>
				<p><b>Auto-Paid Rent?</b> <br>
				<?php 
					if($info[1]['auto_paid'] == 'y') {
						echo '<span class="label label-success">Yes</span>';
					} else {
						echo '<span class="label label-danger">No</span>';
					}
				?>
				</p>
				<?php
					if($info[0]['auto_pay_discount']>0 && $info[0]['payments_allowed'] == 'y' && $info[0]['auto_pay'] != 'y' ) {
						echo '<hr><div class="alert alert-info">You could be saving $'.$info[0]['auto_pay_discount'].' and time every month by electing auto pay.</div><a class="btn btn-primary" href="'.base_url().'renters/pay-rent"><i class="fa fa-calendar"></i>   &nbsp;Add Auto Pay</a>';
					}
					if($info[0]['auto_pay'] == 'y') {
						echo '<p><b>Next Payment Date:</b><br>'.$info[1]['next_payment_date'].'</p>';
					}
				?>
			
			</div>
		</div>
	</div>
	
	<div class="col-md-4">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-user"></i> Rental Details
				
			</div>
			<div class="panel-body payment-details">	
		
				<p><b>Street Address:</b><br> <?php echo htmlentities(ucwords($info[0]['rental_address'])); ?> <?php echo htmlentities(ucwords($info[0]['rental_city'])).' '. htmlentities($info[0]['rental_state']).', '. htmlentities($info[0]['rental_zip']); ?></p>
				<p><b>Moved In:</b><br> <?php echo date('m-d-Y', strtotime($info[0]['move_in'])); ?></p>
				<p><b>Moved Out:</b><br> <?php if($info[0]['move_out'] != '0000-00-00') { echo date('m-d-Y', strtotime($info[0]['move_out'])); } else {echo 'NA';}?></p>
	
				<p><b>Lease Length: </b><br><?php echo $info[0]['lease']; ?></p>
				<p><b>Monthly Payment: </b><br>$<?php echo number_format(ucwords($info[0]['payments']),2); ?></p>
				<p><b>Current Residence: </b><br><?php if($info[0]['current_residence'] == 'y') {echo 'Yes'; }else{echo 'No';}; ?></p>
				
				
			</div>
		</div>
	</div>

</div>


<div class="modal fade" id="payment-notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('landlords'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file-o text-primary"></i> Payment Notes</h4>
				</div>
				<div class="modal-body">
					<p><small><span class="text-danger">*</span> Notes left on this payment will be seen by both you and the landlord.</small></p>
					<hr>
					<ul id="payment-notes-details">
						
					</ul>
					<hr>
					<h4><i class="fa fa-plus text-primary"></i> Add A Note To This Payment</h4>
					<textarea class="form-control" id="noteDetails" style="min-height: 150px" required name="payment_note"></textarea>
					<br>
					<div class="progress" style="display: none">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
							<span class="sr-only">20% Complete</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label><i class="fa fa-file text-primary"></i> Attach File: </label>
							<input type="file" class="form-control fileNote" name="attach_file" id="fileNote">
						</div>
					</div>
					<small><span class="text-danger">*</span> Allowed File Types: jpg, png, jpeg, gif, pdf, doc, docx</small>
					<div id="payment_id"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="addNewNote" class="btn btn-primary">Add Note</button>
				</div>
			</div>
		</form>
	</div>
</div>
<?php
	if($info[0]['auto_pay'] == 'y') {
?>
<div class="modal fade" id="cancelAutoPay" tabindex="-1" role="dialog" aria-labelledby="cancelAutoPay" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Cancel Auto Pay</h4>
      </div>
      <div class="modal-body">
        <p>Your landlord will be notified and if you were recieving a discount for auto pay you will not be responsible for the full amount of your rent.</p>
		<h4>Are you sure you want to cancel auto pay?</h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       <?php echo '<a href="'.base_url().'renters/cancel-auto-pay/'.$cancel_id.'/" class="btn btn-danger"><i class="fa fa-times"></i> I am sure</a>'; ?>
      </div>
    </div>
  </div>
</div>
<?php 
	}
?>