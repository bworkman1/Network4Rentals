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
						<td>$<?php echo number_format($invoice['amount'], 2); ?></td>
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
		<?php if($accept_payments) { ?>
		<tr>
			<td colspan="2" align="center">
				<h4><b>Online Payment Option:</b></h4>
				<p>You can make a payment online using our website. It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
				<br>
				<br>
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
				  <tr>
					<td align="center">
					  <div>
						<!--[if mso]>
						  <v:roundrect xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word" href="https://n4rlocal.com/<?php echo $page->unique_name; ?>/payment" style="height:36px;v-text-anchor:middle;width:150px;" arcsize="5%" strokecolor="#EB7035" fillcolor="#EB7035">
							<w:anchorlock/>
							<center style="color:#ffffff;font-family:Helvetica, Arial,sans-serif;font-size:16px;">Pay Invoice Online</center>
						  </v:roundrect>
						<![endif]-->
						<a href="https://n4rlocal.com/<?php echo $page->unique_name; ?>/payment/?invoice_num=<?php echo $invoice['invoice_num']; ?>" style="background-color:#28B62C;border:1px solid #EB7035;border-radius:3px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:44px;text-align:center;text-decoration:none;width:150px;-webkit-text-size-adjust:none;mso-hide:all;">Pay Invoice Online</a>
					  </div>
					</td>
				  </tr>
				</table>
			</td>
		</tr>
		<?php } ?>
	</tbody>
	
</table>