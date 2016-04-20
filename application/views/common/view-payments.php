<div class="row">
	<div class="col-md-6">
		<h3 style="margin-top: 0;"><i class="fa fa-money"></i> Online Payments</h3>
	</div>
	<div class="col-md-4 col-md-offset-2">
		<input type="text" id="payment-search" class="form-control input-sm" placeholder="Search for a payment">
	</div>
</div>
<hr>
<?php
	$error = $this->session->flashdata('error');
	if($error) {
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle"></i> Error: </b>'.$error.'</div>';
	}
?>
<table id="payments" class="table table-hover table-condensed">
	<thead>
		<tr style="font-weight: bold; border-bottom: 3px solid #444;">
			<td>First</td>
			<td>Last</td>
			<td>Trans Id</td>
			<td>Payment</td>
			<td>Invoice</td>
			<td>Date</td>
			<td>Amount</td>
			<td class="text-right">Details</td>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($payments as $payment) {
				echo '<tr class="init" style="border-bottom: 1px solid #ddd;">';
					echo '<td>'.$payment->firstname.'</td>';
					echo '<td>'.$payment->lastname.'</td>';
					echo '<td>'.$payment->trans_id.'</td>';
					if($payment->approval_id) {
						echo '<td>Credit Card</td>';
					} else {
						echo '<td>E-Check</td>';
					}
					echo '<td>'.$payment->invoice_num.'</td>';
					echo '<td>'.date('m-d-Y', strtotime($payment->ts)).'</td>';
					echo '<td>$'.number_format($payment->amount, 2).'</td>';
					echo '<td  class="text-right"><a href="'.base_url('contractor/view-payment-info/'.$payment->id).'">View</a></td>';
				echo '<tr>';
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td style="border-top: 3px solid #444;">
				<br>
				<a href="#" style="text-decoration: none;" data-toggle="modal" data-target="#add-payment" class="btn btn-primary"><i class="fa fa-plus"></i> Add Offline Payment</a>
			</td>
			<td style="font-weight: bold; border-top: 3px solid #444;" colspan="8" class="text-right">
				Total: <?php echo number_format($sum->amount, 2); ?>
			</td>
		</tr>
	</tfoot>
</table>

<div class="modal fade" id="add-payment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add Offline Payment</h4>
      </div>
      <div class="modal-body">
	  <form id="addPayment">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label><span class="text-danger">*</span> First Name:</label>
					<input type="text" name="firstname" class="form-control" maxlength="40">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><span class="text-danger">*</span> Last Name:</label>
					<input type="text" name="lastname" class="form-control" maxlength="40">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Email:</label>
					<input type="text" name="email" class="form-control" maxlength="80">
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					<label><span class="text-danger">*</span> Amount:</label>
					<input type="text" name="amount" class="form-control" maxlength="10">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label>Invoice #:</label>
					<input type="text" name="invoice_id" class="form-control" maxlength="80">
				</div>
			</div>
		</div>
		<div class="form-group">
			<label>Note:</label>
			<textarea name="note" style="height: 100px" class="form-control"></textarea>
		</div>
		<div id="feedback"></div>
		</form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="addPaymentBtn" data-url="<?php echo current_url(); ?>" class="btn btn-primary">Save Payment</button>
      </div>
    </div>
  </div>
</div>
