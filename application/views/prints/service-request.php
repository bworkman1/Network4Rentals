<!-- Associative array with landlord, renter, rental, request keys -->

<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<?php	
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
?>
<table width="100%">
	<thead>
		<tr>
			<td width="50%">
				<img src="<?php echo './assets/themes/default/images/Network-4-Rentals-Property-Management-Software-logo.png'; ?>" width="285" height="80"/>
			</td>
			<td align="right" width="50%">
				<h3>Service Request</h3>
				<p>
					<b>Submitted: </b><?php echo date('m-d-y h:i a', strtotime($request->submitted) +3600); ?> <small>EST</small><br>
					<?php 
						if($request->reoccurring != 'n') {
							echo '<b>Address:</b> '.$request->address;
						} else {
							echo '<b>Address:</b> '.$rental->rental_address.' '.$rental->rental_city.', '.$rental->rental_state.' '.$rental->rental_zip;
						}
					?>
				</p>
			</td>
		</tr>
		<tr>
			<td colspan="2"><hr></td>
		</tr>
	</thead>
	<tbody>
		<tr align="center" cellpadding="0"> 
			<td width="50%" height="20px" bgcolor="#F9F9F9"><p><b><font color="#444444">Tenat Details:</font></b></p></td>
			<td width="50%" height="20px" bgcolor="#F9F9F9"><p><b><font color="#444444">Landlord Details:</font></b></p></td>
		</tr>
		<tr>
			<td width="50%">
				<table width="100%" cellpadding="2" ALIGN="TOP">
					<?php 
						if($request->reoccurring == 'n') {
					?>	
					<tr>
						<td>
							<b>Contact Name:</b><br>
							<?php if(isset($renter->name)) {echo $renter->name;} else {echo 'NA';} ?>
						</td>
						<td colspan="2">
							<b>Schedule Ph#:</b><br>
							<?php if(isset($request->schedule_phone)) {echo "(".substr($request->schedule_phone, 0, 3).") ".substr($request->schedule_phone, 3, 3)."-".substr($request->schedule_phone,6);}else{echo 'NA';} ?>
						</td>
					</tr>
	
					<tr>
						<td>
							<b>Phone#:</b><br>
							<?php if(isset($renter->tenant_phone)) {echo "(".substr($renter->tenant_phone, 0, 3).") ".substr($renter->tenant_phone, 3, 3)."-".substr($renter->tenant_phone,6);}else{echo 'NA';} ?>
						</td>
						<td>
							<b>Alt Ph#:</b><br>
							<?php if(isset($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);}else{echo 'NA';} ?>
						</td>
					</tr>

					<tr>
						<td colspan="2">
							<b>Email:</b><br>
							<?php if(isset($renter->email)) {echo $renter->email;}else{echo 'NA';} ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Address:</b> <br>
							<?php echo $rental->rental_address.' '.$rental->rental_city.', '.$rental->rental_state.' '.$rental->rental_zip; ?>
						</td>
					</tr>
					<?php
						} else {
					?>
					<tr>
						<td colspan="2">
							<b>Address:</b> <br>
							<?php echo $request->address; ?>
						</td>
					</tr>	
					<?php } ?>
				</table>		
			</td>
			<td width="50%">
				<table width="100%" cellpadding="8">
					<tr>
						<td width="50%">
							<b>Landlord:</b><br>
							<?php echo $landlord->bName; ?>
						</td>
						<td width="50%">
							<b>Name: </b><br>
							<?php echo $landlord->name; ?>
						</td>
					</tr>
					<tr>
						<td>
							<b>Phone:</b><br>
							<?php echo "(".substr($landlord->phone, 0, 3).") ".substr($landlord->phone, 3, 3)."-".substr($landlord->phone,6) ?>
						</td>
						<td>
							<b>Alt Phone:</b><br>
							<?php if(!empty($landlord->alt_phone)) { ?>
							<?php echo "(".substr($landlord->alt_phone, 0, 3).") ".substr($landlord->alt_phone, 3, 3)."-".substr($landlord->alt_phone,6) ?>
							<?php } else { ?>
								NA
							<?php } ?>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<b>Mailing Address:</b><br>
							<?php echo $landlord->address.', '.$landlord->city.' '.$landlord->state.' '.$landlord->zip; ?>
						</td>
					</tr>				
					<tr>
						<td colspan="2">
							<b>Email:</b><br>
							<?php echo $landlord->email; ?>
						</td>
					</tr>			
					
				</table>	
			</td>
		</tr>
				
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
										echo '<p><b>'.ucwords(strtolower($row->business)).'</b><br>We have the supplies you need to complete any '.$services_array[htmlspecialchars($request->service_type)].' need.<br><em class="hidden-print"><b><i class="fa fa-map-marker"></i>  <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->address).'+'.str_replace(' ', '+', $row->city).'+'.$row->state.'" target="_blank">'.$row->address.', '.$row->city.' '.$row->state.'</a></b></em>';
										if(!empty($row->phone)) {
											echo " (".substr($row->phone, 0, 3).") ".substr($row->phone, 3, 3)."-".substr($row->phone,6);
										}
										echo '</p>';
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
			<td width="70%">
				<table width="100%" cellpadding="5">
					<tr>
						<td><b>Permission To Enter:</b></td>
						<td>
							<?php
								if($request->enter_permission == 'y') {
									echo '<font color="green">Yes</font>';
								} else {
									echo '<font color="red">No</font>';
								}
							?>
						</td>
					</tr>
					<tr>
						<td><b>Service Type:</b></td>
						<td><?php echo $services_array[$request->service_type]; ?></td>
					</tr>
					<tr>
						<td><b>Status:</b></td>
						<td>
							<?php
									$request->enter_permission = strtolower($request->enter_permission);
									if($request->enter_permission == 'y' || $request->enter_permission == 'yes') {
										echo 'Yes';
									} else {
										echo 'Call First';
									}
								?>
						</td>
					</tr>
					<tr>
						<td valign="top"><b>Submitted On:</b></td>
						<td><small><?php echo date('m-d-y h:i a', strtotime($request->submitted) +3600); ?> EST</small></td>
					</tr> 
					<tr>
						<td valign="top"><b>Viewed:</b></td>
						<td><small><?php echo date('m-d-y h:i a', strtotime($request->viewed)+3600); ?> EST</small></td>
					</tr> 
					<tr>
						<td valign="top">
							<b>Description from resident:</b><br><?php echo $request->description; ?>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<b>Instruction from Landlord:</b><br><?php echo $request->contractor_note; ?>
						</td>
					</tr>
				</table>
			</td>
			<td width="30%">
				<?php if(!empty($request->attachment)) { ?>
					<center>
						<img width="150" height="150" src="<?php echo base_url(); ?>/service-uploads/<?php echo $request->attachment; ?>">
					</center>
				<?php } ?>	
			</td>
		</tr>
		
		
			
			
	</tbody>
</table>

<?php
	/*if(!empty($ad_post)) {
		echo '<br>';
		echo '<br>';
		echo '<br>';
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
	} */
?>
<?php

	if(!empty($items)) {
		echo '<hr><h3><i class="fa fa-archive text-primary"></i> Items Related To This Request</h3>';
		echo '<table width="100%">
				<tr>
					<td><b>Item Name:</b></td>
					<td><b>Model#:</b></td>
					<td><b>Brand:</b></td>
					<td><b>Serial#:</b></td>
					<td><b>Service Type:</b></td>
				</tr>';
		foreach($items as $val) {
			if(empty($val->modal_num)) {
				$val->modal_num = 'NA';
			}
			if(empty($val->brand)) {
				$val->brand = 'NA';
			}
			if(empty($val->serial)) {
				$val->serial = 'NA';
			}
			echo '<tr>
						<td>
							'.htmlspecialchars($val->desc).'
						</td>
						<td>
							'.htmlspecialchars($val->modal_num).'
						</td>
						<td>
							'.htmlspecialchars($val->brand).'
						</td>
						<td>
							'.htmlspecialchars($val->serial).'
						</td>
						<td>
							'.$services_array[$val->service_type].'
						</td>
					</tr>';
		}
		echo '</table>';
	} 
?>


