<table width="100%">
	<thead>
		<tr>
			<td>
				<h3>Invoice</h3>
			</td>
			<td align="right">
				<?php 
					if($page->image) {
						if(is_file(base_url($page->image))) {
							$img = $page->image;
						} else {
							$img = 'public-images/'.$page->image;
						}
						echo '<img src="'.base_url($img).'" alt="Business Name" width="200" height="100">';
					}
				?>
			</td>
		</tr>
	</thead>
	
	<tbody>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td width="50%">
				<table width="100%">
					<tr>
						<td><b>To:</b></td>
						<td><b><?php echo $invoice['name']; ?></b></td>
					</tr>
					
					<tr>
						<td><b>Invoice #:</b></td>
						<td><?php echo $invoice['invoice_num']; ?></td>
					</tr>
					
					<tr>
						<td><b>Date:</b></td>
						<td><?php echo date('m-d-Y'); ?></td>
					</tr>
					
					<tr>
						<td><b>Amount:</b></td>
						<td>$<?php echo number_format($invoice['amount'], 2); ?> <font color="#49E20E">PAID</font></td>
					</tr>
						
				</table>
			</td>
			<td width="50%" align="right">	
				<table width="100%">
				
					<tr>
						<td><b>From:</b></td>
						<td><b><?php echo $page->bName; ?></b></td>
					</tr>
					
					<tr>
						<td></td>
						<td><?php echo $page->phone; ?></td>
					</tr>
					
					<tr>
						<td></td>
						<td><?php echo $page->email; ?></td>
					</tr>
					
					<tr>
						<td></td>
						<td><?php echo $page->address; ?></td>
					</tr>
					
					<tr>
						<td></td>
						<td><?php echo $page->city; ?>, <?php echo $page->state; ?> <?php echo $page->zip; ?><br></td>
					</tr>
						
				</table>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		
		<tr>
			<td colspan="2">
				<?php if($invoice['note']) { ?>
					<h4><b>Note</b></h4>
					<p><?php echo $invoice['note']; ?></p>
				<?php } ?>
			</td>
		</tr>
		
		<tr>
			<td colspan="2"><hr></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<h4><b>Thank you for your Payment!</b></h4>
				<p>Your payment has been successfully processed. If you have any questions feel free to contact us.</p>
				<br>
				<br>
				
			</td>
		</tr>
	</tbody>
	
</table>