<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<div style="width: 100%;">
<img src="<?php echo base_url(); ?>/assets/themes/default/images/black_white_network_logo.jpg" style="padding: 15px;" width="458" height="110">
<hr>
<?php 
	if(empty($landlord_info['bName'])) {
		$address_by = $landlord_info['name'];
	} else {
		$address_by = $landlord_info['bName'];
	} 
?>
<table width="100%">
	<tr>
		<td><h2>Printed Chat History</h2></td>
		<td align="right"><p><em><b>Printed On:</b> <?php echo date('m-d-Y'); ?></em></p></td>
	</tr>
	<tr>
		<td><b>Tenant Info:</b></td>
		<td align="right"><b>Landlord Info:</b></td>
	</tr>
	<tr>
		<td><?php echo $user_info[0]['name']; ?></td>
		<td align="right"><?php echo $address_by; ?></td>
	</tr>
	<tr>
		<td><?php echo $user_info[1]['rental_address']; ?></td>
		<td align="right"><?php echo $landlord_info['address']; ?></td>
	</tr>
	<tr>
		<td><?php echo $user_info[1]['rental_city'].' '.$user_info[1]['rental_state'].' '.$user_info[1]['rental_zip']; ?></td>
		<td align="right"><?php echo $landlord_info['city'].' '.$landlord_info['state'].' '.$landlord_info['zip']; ?></td>
	</tr>
	<tr>
		<td><?php echo "(".substr($user_info[0]['phone'], 0, 3).") ".substr($user_info[0]['phone'], 3, 3)."-".substr($user_info[0]['phone'],6) ?></td>
		<td align="right"><?php echo "(".substr($landlord_info['phone'], 0, 3).") ".substr($landlord_info['phone'], 3, 3)."-".substr($landlord_info['phone'],6) ?></td>
	</tr>
</table>


</div>
<?php	

	if(empty($header)) {
		foreach($messages as $key) {
			for($i=0;$i<sizeof($key);$i++) {
				if($key['subject'] != '') {
					$header = ucwords($key['subject']);
					break;
				}
			}
		}
	}
	echo '<table width="100%">';
	echo '<tr><td border="#cdcdcd"><h3><b>Subject:</b> '.$header.'</h3></td></tr>';
	echo '</table>';
	echo '<hr>';
	foreach($messages as $key) {
		echo '<table width="100%">';
			echo '<tr>';
				if($key['sent_by'] == '0') {
					echo '<td width="50%"><p><b>Sent To:</b> '.$address_by.'<br>';	
				} else {
					echo '<td width="50%"><p><b>From:</b> '.$address_by.'<br>';
				}
				
				if(empty($key['landlord_viewed']) OR $key['landlord_viewed'] != '0000-00-00 00:00:00') {
					if($key['sent_by'] == '0') {
						echo '<b>Opened On:</b> Not Opened<br>';
					} else {
						echo '<b>Opened On:</b> Not Opened<br>';
					}
				} else {
					if($key['sent_by'] == '0') {
						echo '<b>Opened On:</b> '.date('m-d-Y h:s A', strtotime($key['landlord_viewed'])).' <small>EST</small><br>';
					}
				}
				echo '</p>';
				echo '<p><b>Message:</b><br>'.$key['message'].'</p>';
				echo '</td>';
				echo '<td width="50%" valign="top" align="right"><b>Sent On:</b> '.date('m-d-Y h:s A', strtotime($key['timestamp'])).' <small>EST</small></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td width="50%"></td>';
				echo '<td width="50%" align="right"><b>Sent To:</b> '.$landlord_info['email'].'</td>';
			echo '</tr>';
		echo '</table>';
		echo '<hr>';
	}
	
?>
