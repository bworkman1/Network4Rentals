<table width="100%">

		<tr>
			<td>
				<?php
					if(filesize($user->image)>0) {
						echo '<img src="'.base_url($user->image).'" class="img-responsive" width="150px" height="100px">';
					} else {
						echo '<img src="'.base_url('public-images/'.$user->image).'" class="img-responsive" width="150px" height="100px">';
					}
				?>
			</td>
			<td colspan="3" align="right">
				<b>Paid To</b><br>
				<?php echo $user->bName; ?><br>
				<?php echo $user->name; ?><br>
				<?php echo $user->address.', '.$user->city.' '.$user->state; ?><br>
			</td>
		</tr>
		<?php if($payment->payment) { ?>
			<tr>
				<td  style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">Payment Details</td>
			</tr>
			<tr>
				<td width="30%" class="text-right"><b>First Name:</b></td>
				<td width="30%"><?php echo  $this->security->xss_clean($payment->payment->firstname); ?></td>
				<td width="30%" class="text-right"><b>Last Name:</b></td>
				<td width="30%"><?php echo  $this->security->xss_clean($payment->payment->lastname); ?></td>
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
				<td>
					<?php
						if($payment->payment->approval_id) {
							echo 'Credit Card';
						} else {
							echo 'E-Check';
						}
					?>
				</td>
				<td colspan="2"></td>
			</tr>
			
			<tr style="height: 150px" height="150">
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
				<td style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">Invoice Details</td>
			</tr>
			
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
				<td><?php if($payment->invoice->file) { ?><i class="fa fa-paperclip"></i> <a href="<?php echo base_url($payment->invoice->file); ?>" target="_blank" download><?php echo $payment->invoice->file; ?><?php } ?></td>
			</tr>
			
			<tr>
				<td class="text-right"><b>Invoice Number:</b></td>
				<td><?php echo  $this->security->xss_clean($payment->invoice->invoice_num); ?></td>
				<td class="text-right"></td>
				<td></td>
			</tr>
			
			<tr style="height: 150px" height="150">
				<td class="text-right"><b>Note:</b></td>
				<td colspan="3"><?php echo  $this->security->xss_clean($payment->invoice->note); ?></td>
			</tr>
			
			<tr>
				<td class="text-right"></td>
				<td colspan="3"></td>
			</tr>
		<?php } else if($invoice) { ?>
		
		
			<tr>
				<td style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">Invoice Details</td>
			</tr>
			
			<tr>
				<td class="text-right"><b>Name:</b></td>
				<td><?php echo  $this->security->xss_clean($invoice->name); ?></td>
				<td class="text-right"><b>Email:</b></td>
				<td><?php echo $payment->invoice->email; ?></td>
			</tr>
			
			<tr>
				<td class="text-right"><b>Amount Invoiced:</b></td>
				<td>$<?php echo  $this->security->xss_clean(number_format($invoice->amount, 2)); ?></td>
				<td class="text-right"><b>Attachment:</b></td>
				<td><?php if($payment->invoice->file) { ?><i class="fa fa-paperclip"></i> <a href="<?php echo base_url($invoice->file); ?>" target="_blank" download><?php echo $invoice->file; ?><?php } ?></td>
			</tr>
			
			<tr>
				<td class="text-right"><b>Invoice Number:</b></td>
				<td><?php echo  $this->security->xss_clean($invoice->invoice_num); ?></td>
				<td class="text-right"></td>
				<td></td>
			</tr>
			
			<tr style="height: 150px" height="150">
				<td class="text-right"><b>Note:</b></td>
				<td colspan="3"><?php echo  $this->security->xss_clean($invoice->note); ?></td>
			</tr>
			
			<tr>
				<td class="text-right"></td>
				<td colspan="3"></td>
			</tr>
		<?php } ?>
		
		<?php
			if($payment->invoicePayments) {
				echo '<tr><td  style="background: #444444; color: #ffffff; font-weight: bold;" colspan="4">All Payments Towards This Invoice</td><tr>';
				$paymentsSum = array();
				echo '<table class="table table-striped" width="100%">';
				echo '<thead style="font-weight: bold">';
					echo '<tr>';
						echo '<td>First</td>';
						echo '<td>Last</td>';
						echo '<td>Trans Id</td>';
						echo '<td>Payment</td>';
						echo '<td>Date</td>';
						echo '<td>Amount</td>';
					echo '</tr>';
				echo '</thead>';
				$count = 0;
				foreach($payment->invoicePayments as $row) {
					$paymentsSum[] = $row->amount;
					if($count%2) {
						echo '<tr style="background: #F9F9F9; boder-bottom: 1px solid #999999">';
					} else {
						echo '<tr style="boder-bottom: 1px solid #999999">';
					}
						
						echo '<td>'.$row->firstname.'</td>';
						echo '<td>'.$row->lastname.'</td>';
						echo '<td>'.$row->trans_id.'</td>';
						if($row->approval_id) {
							echo '<td>Credit Card</td>';
						} else {
							echo '<td>E-Check</td>';
						}
						echo '<td>'.date('m-d-Y', strtotime($row->ts)).'</td>';
						echo '<td>$'.number_format($row->amount, 2).'</td>';
						
					echo '</tr>';
					$count++;
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