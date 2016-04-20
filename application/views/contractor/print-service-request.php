<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<?php
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
?>
<img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" style="padding: 15px;" width="438" height="90">
<hr>
<table width="100%">
	<tr>
		<td width="50%"><h3>Service Request</h3></td>
		<td width="50%" align="right"><p><em><b>Printed On:</b> <?php echo date('m-d-Y'); ?></em></p></td>
	</tr>	
	<tr>
		<td><h4><b>Service Address:</b></h4></td>
		<td><h4><b>Landlord Info:</b></h4></td>
	</tr>
	<tr>
		<td>
			<table cellpadding="0">
				<tr>
					<td align="right"><b>Contact Name:</b></td>
					<td><?php if(isset($details['tenant_name'])) {echo $details['tenant_name'];} else {echo 'NA';} ?></td>
				</tr>
				<tr>	
					<td align="right"><b>Email:</b></td>
					<td><?php if(isset($details['tenant_email'])) {echo $details['tenant_email'];}else{echo 'NA';} ?></td>
				</tr>				
				<tr>	
					<td align="right"><b>Phone#:</b></td>
					<td><?php if(isset($details['tenant_phone'])) {echo "(".substr($details['tenant_phone'], 0, 3).") ".substr($details['tenant_phone'], 3, 3)."-".substr($details['tenant_phone'],6);}else{echo 'NA';} ?></td>
				</tr>
				<tr>
					<td align="right"><b>Alt Ph#:</b></td>
					<td><?php if(isset($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);}else{echo 'NA';} ?></td>
				</tr>				
				<tr>
					<td align="right"><b>Schedule Ph#:</b></td>
					<td><?php if(isset($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);}else{echo 'NA';} ?></td>
				</tr>	
				<tr>
					<td align="right" valign="top"><b>Address:</b></td>
					<td><?php echo $details['address'].'<br>'.$details['city'].', '.$details['state'].' '.$details['zip']; ?></td>
				</tr>
			</table>		
		</td>
		<td>
			<table cellpadding="0">
				<tr>
					<td align="right"><b>Landlord:</b></td>
					<td><?php echo $details['bName']; ?></td>
				</tr>
				<tr>
					<td align="right"><b>Contact Name: </b></td>
					<td><?php echo $details['landlord_name']; ?></td>
				</tr>
				<tr>
					<td align="right"><b>Address:</b></td>
					<td><?php echo $details['landlord_city'].' '.$details['landlord_state'].' '.$details['landlord_zip']; ?></td>
				</tr>
				<tr>
					<td align="right"><b>Phone:</b></td>
					<td><?php echo "(".substr($details['landlord_phone'], 0, 3).") ".substr($details['landlord_phone'], 3, 3)."-".substr($details['landlord_phone'],6) ?></td>
				</tr>			
				<tr>
					<td align="right"><b>Alt Phone:</b></td>
					<td>
						<?php if(!empty($details['landlord_alt_phone'])) { ?>
						<?php echo "(".substr($details['landlord_alt_phone'], 0, 3).") ".substr($details['landlord_alt_phone'], 3, 3)."-".substr($details['landlord_alt_phone'],6) ?>
						<?php } else { ?>
							NA
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td align="right"><b>Email:</b></td>
					<td><?php echo $details['landlord_email']; ?></td>
				</tr>
				<tr>
					<td align="right" valign="top"><b>Completed On:</b> </td>
					<td>
						<?php 
							if($details['completed'] != '0000-00-00 00:00:00') {
								echo date('m-d-y h:ma', strtotime($details['completed'], +3600)). '<small>EST</small>'; 
							} else {
								echo 'N/A'; 
							}
						?>
					</td>
				</tr>
			</table>			
		</td>   
	</tr> 
	<tr>
		<td><h4><b>Service Request Details:</b></h4></td>
		<td></td>
	</tr>
	<tr>
		<td>
			<table>
				<tr>
					<td><b>Permission To Enter:</b></td>
					<td><?php if(!isset($details['who'])) {echo ucwords($details['enter_permission']);}else{echo 'NA';} ?></td>
				</tr>
				<tr>
					<td><b>Service Type:</b></td>
					<td><?php echo $services_array[$details['service_type']]; ?></td>
				</tr>
				<tr>
					<td><b>Status:</b></td>
					<td>
						<?php
							if($details['complete'] == 'n') {
								echo 'Incomplete';
							} else {
								echo 'Complete';
							}
						?>
					</td>
				</tr>
				<tr>
					<td valign="top"><b>Submitted On:</b></td>
					<td><?php echo date('m-d-y h:i a', strtotime($details['submitted']) +3600); ?> <small>EST</small></td>
				</tr> 
				<tr>
					<td valign="top"><b>Viewed:</b></td>
					<td><?php echo date('m-d-y h:i a', strtotime($details['viewed'])+3600); ?> <small>EST</small></td>
				</tr> 
			</table>
		</td>
		<td valign="top">
			<b>Service Description:</b><br><?php echo $details['description']; ?>
		</td>
	</tr>
</table>
<?php
	//if(!empty($ad_post)) {
	$noshow = false;
	if($noshow) {
		echo '<table>';
			echo '<tr>';
				foreach($ad_post as $key => $val) {
					echo '<td width="32%" align="left">';	
						echo '<center><h4><b>'.$val['title'].'</b></h4>';
						echo '<p>'.$val['desc'].'</p>';
						echo '<img src="'.base_url().'/public-images/'.$val['logo'].'" width="200px">';
						echo '<p><b>Contact:</b> '.$val['name'].'<br>';
						echo '<b>Phone:</b> ('.substr($val['phone'], 0, 3).') '.substr($val['phone'], 3, 3).'-'.substr($val['phone'],6).'</p></center>';
					echo '</td>';

					
					
				}
			echo '</tr>';
		echo '</table>';
	}
?>
<?php
	if(!empty($details['items'])) {
		echo '<hr><h3><i class="fa fa-archive text-primary"></i> Items Related To This Request</h3>';
		echo '<table width="100%">
				<tr>
					<td><b>Item Name:</b></td>
					<td><b>Model#:</b></td>
					<td><b>Brand:</b></td>
					<td><b>Serial#:</b></td>
					<td><b>Service Type:</b></td>
				</tr>';
		foreach($details['items'] as $val) {
			if(empty($val['modal_num'])) {
				$val['modal_num'] = 'NA';
			}
			if(empty($val['brand'])) {
				$val['brand'] = 'NA';
			}
			if(empty($val['serial'])) {
				$val['serial'] = 'NA';
			}
			echo '<tr>
						<td>
							'.htmlspecialchars($val['desc']).'
						</td>
						<td>
							'.htmlspecialchars($val['modal_num']).'
						</td>
						<td>
							'.htmlspecialchars($val['brand']).'
						</td>
						<td>
							'.htmlspecialchars($val['serial']).'
						</td>
						<td>
							'.$services_array[$val['service_type']].'
						</td>
					</tr>';
		}
		echo '</table>';
	}
?>
<?php if(!empty($details['attachment'])) { ?>
	<hr>
	<center>
		<img width="45%" src="<?php echo base_url(); ?>/service-uploads/<?php echo $details['attachment']; ?>">
	</center>
<?php } ?>	

