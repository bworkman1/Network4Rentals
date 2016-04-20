<table class="table table-striped">
	<thead>
		<tr>
			<td>
				<?php
					if(filesize($user->image)>0) {
						echo '<img src="'.base_url($user->image).'" style="max-width: 200px; max-height: 150px;" class="img-responsive">';
					} else {
						echo '<img src="'.base_url('public-images/'.$user->image).'" style="max-width: 200px; max-height: 150px;" class="img-responsive">';
					}
				?>
			</td>
			<td colspan="3">
				<ul class="text-right">
					<li><b>Paid To</b></li>
					<li><?php echo $user->bName; ?></li>
					<li><?php echo $user->name; ?></li>
					<li><?php echo $user->address.', '.$user->city.' '.$user->state; ?></li>
				</ul>
			</td>
		</tr>
	</thead>
	
	<?php if($payment->payment) { ?>
		<tr>
			<td  style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">Payment Details</td>
		<tr>
		<tr>
			<td  width="30%" class="text-right"><b>First Name:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->payment->firstname); ?></td>
			<td class="text-right"><b>Last Name:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->payment->lastname); ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Email:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->payment->email); ?></td>
			<td class="text-right"><b>Paid On:</b></td>
			<td><?php echo  date('m-d-Y', strtotime($payment->payment->ts)); ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Transaction Id:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->payment->trans_id); ?></td>
			<td class="text-right"><b>Approval Id:</b></td>
			<td><?php echo $payment->payment->approval_id; ?></td>
		</tr>	
		
		<tr>
			<td class="text-right"><b>Payment Type:</b></td>
			<td colspan="3">
				<?php
					if($payment->payment->approval_id) {
						echo 'Credit Card';
					} else {
						echo 'E-Check';
					}
				?>
			</td>
		</tr>
		
		<tr style="height: 150px">
			<td class="text-right"><b>Note:</b></td>
			<td colspan="3"><?php echo  $this->security->xss_clean($payment->payment->note); ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Sub-total:</b></td>
			<td colspan="3">$
				<?php 
					$amount =   $payment->payment->amount>0?$payment->payment->amount:0;
					$fee = $payment->payment->fee>0?$payment->payment->fee:0;
					$discount = $payment->payment->discount>0?$payment->payment->discount:0;
					$subTotal = ($amount - $fee) + $discount;
					echo  number_format($subTotal, 2);					
				?>
			</td>
		</tr>
		<tr>
			<td class="text-right"><b>Online Payment Discount:</b></td>
			<td colspan="3">- $<?php echo  number_format($this->security->xss_clean($discount), 2); ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Fee:</b></td>
			<td colspan="3">+ $<?php echo  number_format($this->security->xss_clean($fee), 2); ?></td>
		</tr>
		
		<tr>
			<td class="text-right" style="border-top: 1px solid #000"><b>Total:</b></td>
			<td style="border-top: 1px solid #000">$<?php echo  number_format($this->security->xss_clean($amount),2); ?></td>
			<td colspan="2"> </td>
		</tr>
		
		<tr>
			<td class="text-right"></td>
			<td colspan="3"></td>
		</tr>
	<?php } ?>
	
	<?php if ($payment->invoice) { ?>
		<tr>
			<td  style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">Invoice Details</td>
		<tr>
		
		<tr>
			<td class="text-right"><b>Name:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->invoice->name); ?></td>
			<td class="text-right"><b>Email:</b></td>
			<td><?php echo $payment->invoice->email; ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Amount Invoiced:</b></td>
			<td>$<?php echo  $this->security->xss_clean(number_format($payment->invoice->amount, 2)); ?></td>
			<td class="text-right"><b>Attachment:</b></td>
			<td><?php if($payment->invoice->file) { ?><i class="fa fa-paperclip"></i> 
			<a href="<?php echo base_url(str_replace('//', '/', $payment->invoice->file)); ?>" target="_blank" download>
			<?php echo str_replace('//', '/', $payment->invoice->file); ?>
			<?php } ?></td>
		</tr>
		
		<tr>
			<td class="text-right"><b>Invoice Number:</b></td>
			<td><?php echo  $this->security->xss_clean($payment->invoice->invoice_num); ?></td>
			<td class="text-right"></td>
			<td>
				<?php if($payment->invoice->ref_id) { ?>
					<a href="<?php echo base_url('contractor/view-service-request/'.$payment->invoice->ref_id); ?>" style="margin-left: 15px" class="btn btn-primary">View Service Request</a>
				<?php } ?>
			</td>
		</tr>
		
		<tr style="height: 150px">
			<td class="text-right"><b>Note:</b></td>
			<td colspan="3"><?php echo  $this->security->xss_clean($payment->invoice->note); ?></td>
		</tr>
		
		
	<?php } ?>
	
	<?php
		if($payment->invoicePayments) {
			echo '<tr><td  style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">All Payments Towards This Invoice</td><tr>';
			$paymentsSum = array();
			echo '<table class="table table-striped">';
			echo '<thead style="font-weight: bold">';
				echo '<tr>';
					echo '<td>First</td>';
					echo '<td>Last</td>';
					echo '<td>Trans Id</td>';
					echo '<td>Payment</td>';
					echo '<td>Date</td>';
					echo '<td>Amount</td>';
					echo '<td class="text-right">Details</td>';
				echo '</tr>';
			echo '</thead>';
			foreach($payment->invoicePayments as $row) {
				$paymentsSum[] = $row->amount;
				echo '<tr>';
					echo '<td>'.$row->firstname.'</td>';
					echo '<td>'.$row->lastname.'</td>';
					echo '<td>'.$row->trans_id.'</td>';
					if($row->approval_id) {
						echo '<td>Credit Card</td>';
					} elseif(empty($row->approval_id) && empty($row->trans_id)) {
						echo '<td>Offline</td>';
					} else {
						echo '<td>E-Check</td>';
					}
					echo '<td>'.date('m-d-Y', strtotime($row->ts)).'</td>';
					echo '<td>$'.number_format($row->amount, 2).'</td>';
					echo '<td class="text-right"><a href="'.base_url('contractor/view-payment-info/'.$row->id).'">View</a></td>';
				echo '</tr>';
			}
			
			echo '<tr>';
				echo '<td colspan="4"></td>';
				echo '<td class="text-right"><b>Total:</b></td>';
				echo '<td>$'.number_format(array_sum($paymentsSum), 2).'</td>';
				echo '<td></td>';
			echo '</tr>';
			
			echo '</table>';
		}
		
		
	?>
	
</table>
<?php 
	if($payment->payment->id) {
		$id = $payment->payment->id;
	} else {
		$id = $payment->invoice->id;
	}
	echo '<a href="'.base_url($this->uri->segment(1).'/print-payment-details/'.$id.'?type='.$this->uri->segment(2)).'" class="btn btn-primary">Print/Save Details</a>';
	
	if($this->uri->segment(2) == 'view-invoice') {
		echo '<a href="'.base_url($this->uri->segment(1).'/view-invoices').'" class="btn btn-primary" style="margin-left: 15px">All Invoices</a>';
	} else {
		echo '<a href="'.base_url($this->uri->segment(1).'/view-payments').'" class="btn btn-primary" style="margin-left: 15px">All Payments</a>';
	}
?>