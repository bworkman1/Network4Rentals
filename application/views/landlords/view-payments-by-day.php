<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	
	if(!empty($error)) {
		echo '<div class="alert alert-error"><b>Error:</b> '.$error.'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><b>Success:</b> '.$success.'</div>';
	}
?>

<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="row">
			<div class="col-xs-9">
				<h4><i class="fa fa-calendar-o"></i> Payments Made On <?php echo $date['month'].'/'.$date['day'].'/'.$date['year']; ?></h4>
			</div>
			<div class="col-xs-3">
				<a href="<?php echo base_url().'landlords/payment-data/'.$date['year'].'/'.$date['month'];?>" class="btn btn-warning pull-right"><i class="fa fa-reply"></i> Go Back</a>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<?php echo $table; ?>
		<h4 class="disputed">Red background means the payment is currently disputed.</h4>
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
					<p>Notes left on this payment will be seen by both you and the tenant.</p>
					<ul id="payment-notes-details">
						
					</ul>
					<hr>
					<h4><i class="fa fa-plus text-primary"></i> Add A Note To This Payment</h4>
					<textarea class="form-control" id="noteDetails" style="min-height: 150px" required name="payment_note"></textarea>
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

<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title"><i class="fa fa-exclamation-triangle text-danger"></i> Cancel Auto Payment</h4>
			</div>
			<div class="modal-body">
				Warning, you are about to cancel the auto payments one of your tenants has setup. If you continue you will no longer receive payments automatically and one a monthly basis. If this is not what you want click the cancel button.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<a href="#" id="cancelSub" class="btn btn-danger">Terminate Auto Pay</a>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="disputePayment" tabindex="-1" role="dialog" aria-labelledby="disputePayment" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('landlords'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="disputeLabel"><i class="fa fa-flag text-danger"></i> Dispute Payment</h4>
				</div>
				<div class="modal-body">
					<p>Disputing a payment will notify the tenant of the negative mark on their account. Once the dispute is solved you can mark it as undisputed.</p>
					<hr>
					<h4>Reason For Dispute:</h4>
					<textarea class="form-control" id="disputeDetails" style="min-height: 150px" required name="payment_note"></textarea>
					<div id="dispute_id"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="addDispute" class="btn btn-primary">Add Dispute</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="editPayment" tabindex="-1" role="dialog" aria-labelledby="editPayment" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('landlords'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title"><i class="fa fa-pencil"></i> Edit Payment</h4>
				</div>
				<div class="modal-body">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="addDispute" class="btn btn-primary">Add Dispute</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="settleDispute" tabindex="-1" role="dialog" aria-labelledby="settleDispute" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="editPayment"><i class="fa fa-flag"></i> Settle Dispute</h4>
				</div>
				<div class="modal-body">
					You are about to mark this payment as resolved. Please make sure the you are click the correct payment before you continue.
					<div id="dispute-data"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="resolveDispute" class="btn btn-primary">Settle Dispute</button>
				</div>
			</div>
		</form>
	</div>
</div>
