<div class="row">
	<div class="col-md-6">
		<h3 style="margin-top: 0;"><i class="fa fa-file-o"></i> Online Invoices</h3>
	</div>
	<div class="col-md-4 col-md-offset-2">
		<input type="text" id="invoice-search" class="form-control input-sm" placeholder="Search for an invoice">
	</div>
</div>
<hr>
<table id="payments" class="table table-hover table-condensed">
	<thead>
		<tr style="font-weight: bold; border-bottom: 3px solid #444;">
			<td>Name</td>
			<td>Invoice</td>
			<td>Date</td>
			<td>Status</td>
			<td>Amount</td>
			<td>Paid</td>
			<td class="text-right">Details</td>
		</tr>
	</thead>
	<tbody>
		<?php
			foreach($payments as $payment) {
				
				if($payment->paid == 'n') {
					$status = 'Open';
				} else {
					if($payment->payments_sum < $payment->amount) {
						$status = 'Partial';
					} elseif($payment->payments_sum >= $payment->amount) {
						$status = 'Paid';
					}
				}
				
				echo '<tr class="init" style="border-bottom: 1px solid #ddd;">';
					echo '<td>'.$payment->name.'</td>';
					echo '<td>'.$payment->invoice_num.'</td>';
					echo '<td>'.date('m-d-Y', strtotime($payment->created)).'</td>';
					
					echo '<td>'.$status.'</td>';
				
					echo '<td>$'.number_format($payment->amount, 2).'</td>';
					echo '<td>$'.$payment->payments_sum.'</td>';
					echo '<td  class="text-right"><a href="'.base_url('contractor/view-invoice/'.$payment->id).'">View</a></td>';
				echo '<tr>';
			}
		?>
	</tbody>
	<tfoot>
		<tr>
			<td style="font-weight: bold; border-top: 3px solid #444;" colspan="8" class="text-right">
				Total: <?php echo number_format($sum->amount, 2); ?>
			</td>
		</tr>
	</tfoot>
</table>
