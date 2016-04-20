<div class="row">
	<div class="col-md-6">
		<h3 style="margin-top: 0;"><i class="fa fa-money"></i> Online Payments</h3>
	</div>
	<div class="col-md-4 col-md-offset-2">
		<input type="text" id="payment-search" class="form-control input-sm" placeholder="Search for a payment">
	</div>
</div>
<hr>
<table id="payments" class="table table-hover table-condensed">
	<thead>
		<tr style="font-weight: bold; border-bottom: 3px solid #444;">
			<td>First</td>
			<td>Last</td>
			<td>Trans Id</td>
			<td>Payment</td>
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
					
					echo '<td>'.date('m-d-Y', strtotime($payment->ts)).'</td>';
					echo '<td>$'.number_format($payment->amount, 2).'</td>';
					echo '<td  class="text-right"><a href="'.base_url('contractor/view-payment-info/'.$payment->id).'">View</a></td>';
				echo '<tr>';
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td style="font-weight: bold; border-top: 3px solid #444;" colspan="7" class="text-right">
				Total: <?php echo number_format($sum->amount, 2); ?>
			</td>
		</tr>
	</tfoot>
</table>
