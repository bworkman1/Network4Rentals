<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<div style="width: 100%;">
<img src="https://network4rentals.com/wp-content/uploads/2013/07/network_logo.png" style="padding: 15px;" width="458" height="110">
<hr>
<?php 
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
	if(empty($landlord['bName'])) {
		$address_by = $landlord['name'];
	} else {
		$address_by = $landlord['bName'];
	} 
?>
<table width="100%">
	<tr>
		<td width="50%"><h2>Service Request</h2></td>
		<td  width="50%" align="right"><p><em><b>Printed On:</b> <?php echo date('m-d-Y'); ?></em></p></td>
	</tr>	
	<tr>
		<td><h3><b>Service Location:</b></h3></td>
		<td><h3><b>Landlord Info:</b></h3></td>
	</tr>
	<tr>
		<td valign="top"> 
			<b>Contact Name:</b> <?php echo $user[0]['name']; ?><br>
			
			<b>Phone#:</b> <?php echo "(".substr($user[0]['phone'], 0, 3).") ".substr($user[0]['phone'], 3, 3)."-".substr($user[0]['phone'],6); ?><br>
			
			<b>Schedule Ph#:</b>  <?php echo "(".substr($requests['schedule_phone'], 0, 3).") ".substr($requests['schedule_phone'], 3, 3)."-".substr($requests['schedule_phone'],6); ?><br>
			<b>Address:</b><br>
			<?php echo $user[1]['rental_address'].'<br>'.$user[1]['rental_city'].', '.$user[1]['rental_state'].' '.$user[1]['rental_zip']; ?><br>
			<h3><b>Service Request Details:</b></h3>
			<b>Permission To Enter:</b> <?php echo ucwords($requests['enter_permission']); ?><br>
			<b>Service Type:</b><br><?php echo $services_array[$requests['service_type']]; ?><br>
			<b>Status:</b><br>
			<?php 
				if($requests['complete'] == 'n') {
					echo 'Incomplete';
				} else {
					echo 'Complete';
				}
			?>
		</td>
		<td valign="top">
			<b>Landlord:</b><br><?php echo $address_by; ?><br>
			<b>Contact Name: </b><br><?php echo $landlord['name']; ?><br>
			<b>Address:</b><br><?php echo $landlord['city'].' '.$landlord['state'].' '.$landlord['zip']; ?><br>
			<b>Phone:</b><br><?php echo "(".substr($landlord['phone'], 0, 3).") ".substr($landlord['phone'], 3, 3)."-".substr($landlord['phone'],6) ?><br>
			<b>Email:</b><br><?php echo $landlord['email']; ?><br>
			<b>Completed On:</b> <br>
			<?php 
				if($requests['completed'] != '0000-00-00 00:00:00') {
					echo date('m-d-y h:ma', strtotime($requests['completed'], +3600)).' <small>EST</small>'; 
				} else {
					echo 'Not Marked As Complete'; 
				}
			?>
		</td>
	</tr> 

	<tr>
		<td><b>Submitted On:</b><br><?php echo date('m-d-y h:ma', strtotime($requests['submitted'], +3600)); ?> <small>EST</small></td>
		<td><b>Opened On:</b><br>
			<?php 
				if($requests['viewed'] != '0000-00-00 00:00:00') {
					echo date('m-d-y h:ma', strtotime($requests['viewed'], +3600)).'<small>EST</small> By '.$landlord['email']; 
				} else {
					echo 'Not Opened Yet'; 
				}
			?>
		</td>
	</tr> 

	<tr>
		<td colspan="2"><hr></td>
	</tr>
	<tr>
		<td width="30%">
			<?php if(!empty($requests['attachment'])) { ?><img src="<?php echo base_url(); ?>/service-uploads/<?php echo $requests['attachment']; ?>" width="350px"><?php } ?>
		</td> 
		<td valign="top">
			<b>Service Description:</b><br><?php echo $requests['description']; ?>
		</td>
	</tr>

</table>


</div>