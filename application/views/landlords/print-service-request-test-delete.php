<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<?php	
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
?>
<table width="100%">
	<thead borde>
		<tr>
			<td width="50%">
				<img src="http://network4rentals.com/wp-content/themes/Network4Rentals.new/img/Network-4-Rentals-Property-Management-Software-logo.png" style="padding: 15px;" width="285" height="80"> 
			</td>
			<td align="right" width="50%">
				<h3>Service Request</h3>
				<p>
					<b>Submitted: </b><?php echo date('m-d-y h:i a', strtotime($details['submitted']) +3600); ?> <small>EST</small><br>
					<b>Address:</b> <?php echo $details['address'].' '.$details['city'].', '.$details['state'].' '.$details['zip']; ?>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
	</thead>
	<tbody>
		<tr align="center" cellpadding="1"> 
			<td width="50%" bgcolor="#F9F9F9"><h4><b><font color="#444444">Tenat Details:</font></b></h4></td>
			<td width="50%" bgcolor="#F9F9F9"><h4><b><font color="#444444">Landlord Details:</font></b></h4></td>
		</tr>
		<tr>
			<td>
				<table width="100%" cellpadding="8" ALIGN=TOP>
					<?php 
						if($details['reoccurring'] == 'n') {
					?>	
					<tr>
						<td>
							<b>Contact Name:</b><br>
							<?php if(isset($details['tenant_name'])) {echo $details['tenant_name'];} else {echo 'NA';} ?>
						</td>
						<td colspan="2">
							<b>Schedule Ph#:</b><br>
							<?php if(isset($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);}else{echo 'NA';} ?>
						</td>
					</tr>
	
					<tr>
						<td>
							<b>Phone#:</b><br>
							<?php if(isset($details['tenant_phone'])) {echo "(".substr($details['tenant_phone'], 0, 3).") ".substr($details['tenant_phone'], 3, 3)."-".substr($details['tenant_phone'],6);}else{echo 'NA';} ?>
						</td>
						<td>
							<b>Alt Ph#:</b><br>
							<?php if(isset($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);}else{echo 'NA';} ?>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<b>Email:</b><br>
							<?php if(isset($details['tenant_email'])) {echo $details['tenant_email'];}else{echo 'NA';} ?>
						</td>
					</tr>
					
					<?php
						}
					?><tr>
						<td colspan="2">
							<b>Address:</b> <br>
							<?php echo $details['address'].' '.$details['city'].', '.$details['state'].' '.$details['zip']; ?>
						</td>
					</tr>
				</table>		
			</td>
			<td>
				<table width="100%" cellpadding="8">
					<tr>
						<td>
							<b>Landlord:</b><br>
							<?php echo $details['bName']; ?>
						</td>
						<td>
							<b>Contact Name: </b><br>
							<?php echo $details['landlord_name']; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone:</b><br>
							<?php echo "(".substr($details['landlord_phone'], 0, 3).") ".substr($details['landlord_phone'], 3, 3)."-".substr($details['landlord_phone'],6) ?>
						</td>
						<td>
							<b>Alt Phone:</b><br>
							<?php if(!empty($details['landlord_alt_phone'])) { ?>
							<?php echo "(".substr($details['landlord_alt_phone'], 0, 3).") ".substr($details['landlord_alt_phone'], 3, 3)."-".substr($details['landlord_alt_phone'],6) ?>
							<?php } else { ?>
								NA
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Mailing Address:</b><br>
							<?php echo $details['landlord_address'].' '.$details['landlord_city'].' '.$details['landlord_state'].' '.$details['landlord_zip']; ?>
						</td>
					</tr>				
					<tr>
						<td>
							<b>Email:</b><br>
							<?php echo $details['landlord_email']; ?>
						</td>
					</tr>			
					
					<tr>
						<td>
							<b>Completed On:</b> <br>
							<?php 
								if($details['completed'] != '0000-00-00 00:00:00') {
									echo date('m-d-y h:ma', strtotime($details['completed'], +3600)). ' <small>EST</small>'; 
								} else {
									echo 'N/A'; 
								}
							?>
						</td>
					</tr>
				</table>			
			</td>   
		</tr> 
		
		<?php if(!empty($suppliers)) { ?>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
			<tr>
				<?php 
					foreach($suppliers as $row) { 
						echo '<td width="50%">';
							echo '<table>';
								echo '<tr>';
									echo '<td width="90px">';
										echo '<img ALIGN=LEFT src="'.base_url($row->logo).'" alt="'.$row->business.'" class="hidden-print aligncenter img-responsive suppliesHouseImg" height="90" width="90">';
									echo '</td>';
									echo '<td>';
										echo '<p><b>'.ucwords(strtolower($row->business)).'</b><br>We have the supplies you need to complete any '.$services_array[htmlspecialchars($details['service_type'])].' need.<br><em class="hidden-print"><b><i class="fa fa-map-marker"></i>  <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->address).'+'.str_replace(' ', '+', $row->city).'+'.$row->state.'" target="_blank">'.$row->address.', '.$row->city.' '.$row->state.'</a></b></em></p>';
									echo '</td>';
								echo '</tr>';
							echo '</table>';
							
							
						echo '</td>';
					} 
				?>
			</tr>
		<?php } ?>
		
		<tr bgcolor="#F9F9F9">
			<td colspan="2"><h4><b>Service Request Details:</b></h4></td>
		</tr>
		<tr>
			<td>
				<table cellpadding="5">
					<tr>
						<td><b>Permission To Enter:</b></td>
						<td>
							<?php
								if($details['enter_permission'] == 'y') {
									echo 'Yes';
								} else {
									echo 'No';
								}
							?>
						</td>
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
									echo '<font color="red">Incomplete</font>';
								} else {
									echo '<font color="green">Complete</font>';
								}
							?>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>Submitted On:</b></td>
						<td><small><?php echo date('m-d-y h:i a', strtotime($details['submitted']) +3600); ?> EST</small></td>
					</tr> 
					<tr>
						<td valign="top"><b>Viewed:</b></td>
						<td><small><?php echo date('m-d-y h:i a', strtotime($details['viewed'])+3600); ?> EST</small></td>
					</tr> 
					<tr>
						<td valign="top">
							<b>Service Description:</b><br><?php echo $details['description']; ?>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<?php if(!empty($details['attachment'])) { ?>
					<center>
						<img width="150" height="150" src="<?php echo base_url(); ?>/service-uploads/<?php echo $details['attachment']; ?>">
					</center>
				<?php } ?>	
			</td>
		</tr>
		
		
			
			
	</tbody>
</table>

<?php
	if(!empty($ad_post)) {
		shuffle($ad_post);
		echo '<table BORDER="1" CELLPADDING="4" CELLSPACING="3">';
			echo '<tr>';
				foreach($ad_post as $val) { 
					echo '<td width="32%" align="left">';	
						echo '<center><h4><b>'.$val->title.'</b></h4>';
						echo '<p>'.$val->description.'</p>';
						if(!empty($val->ad_image)) {
							echo '<img src="'.base_url().'/contractor-images/'.$val->ad_image.'" align="center" width="100px" height="100px">';
						}
						echo '<p><b>'.$val->bName.'</b><br>';
						echo '('.substr($val->phone, 0, 3).') '.substr($val->phone, 3, 3).'-'.substr($val->phone,6).'</p></center>';
					echo '</td>';
				}
			echo '</tr>';
		echo '</table>';
	} else {
		echo '<table>';
			echo '<tr>';
				echo '<td>';	
					echo '<h4><i class="fa fa-bullhorn text-primary"></i> Advertise Here</h4>';
					echo '<p><i class="fa fa-asterisk text-danger"></i> Only 3 Allowed per Service Type &amp; Area</p>';
					echo '<p><i class="fa fa-asterisk text-danger"></i> Includes Self Branded Website</p>';
					echo '<p><i class="fa fa-asterisk text-danger"></i> Landlords and Property Managers Can Search For Your Company</p>';
				echo '</td>';
				
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


