<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Rental History Report Was Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<div style="width: 100%;">
<img src="<?php echo base_url(); ?>/assets/themes/default/images/black_white_network_logo.jpg" style="padding: 15px;" width="458" height="110">
<hr>
</div>
<h3><?php echo $user[0]['name']; ?> Rental Resume</h3>
<p>Printed On <?php echo date('m-d-Y'); ?></p>
<?php
	for($i=0;$i<sizeof($info);$i++) {
		echo '<br>';
		echo '<hr>';
		echo '<table cellpadding="2" style="width: 100%">';
		echo '<tr>';
		echo '<td colspan="3" align="center" style="font-size: 24px; border:1px solid #777;"><b>';
		if($info[$i]['bName'] != '') {
			echo htmlentities($info[$i]['bName']);
		} else {
			echo htmlentities($info[$i]['landlord_name']);
		}
		echo '</b></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan="3" style="font-size: 22px;"><b><em>Landlord Info</em></b></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><b>Landlords Name:</b><br>'.htmlentities($info[$i]['landlord_name']).'</td>';
		echo '<td><b>Landlords Email:</b><br>'.htmlentities($info[$i]['landlord_email']).'</td>';
		$info[$i]['landlord_phone'] = "(".substr($info[$i]['landlord_phone'], 0, 3).") ".substr($info[$i]['landlord_phone'], 3, 3)."-".substr($info[$i]['landlord_phone'],6);
		echo '<td><b>Landlords Phone:</b><br>'.htmlentities($info[$i]['landlord_phone']).'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><b>Landlords Address:</b><br>'.htmlentities($info[$i]['landlord_address']).'</td>';
		echo '<td><b>Landlords City:</b><br>'.htmlentities($info[$i]['landlord_city']).'</td>';
		echo '<td><b>Landlords State/Zip</b><br>'.htmlentities($info[$i]['state']).' '.htmlentities($info[$i]['zip']).'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td colspan="3" style="font-size: 22px;"><b><em>Rental Info</em></b></td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><b>Rental Address:</b><br>'.htmlentities($info[$i]['rental_address']).'</td>';
		echo '<td><b>Rental City:</b><br>'.htmlentities($info[$i]['rental_city']).'</td>';
		echo '<td><b>Rental State/Zip:</b><br>'.htmlentities($info[$i]['rental_state']).' '.htmlentities($info[$i]['rental_zip']).'</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<td><b>Moved In:</b><br>'.date('m-d-Y', strtotime($info[$i]['move_in'])).'</td>';
		if($info[$i]['move_out'] != '0000-00-00') {	
			echo '<td><b>Moved Out:</b><br>'.date('m-d-Y', strtotime($info[$i]['move_out'])).'</td>';
		} else {
			echo '<td><b>Moved Out:</b><br>NA</td>';
		}
		echo '<td><b>Disputes:</b><br>'.htmlentities($info[$i]['count']).'</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td><b>Lease Length:</b><br>'.htmlentities($info[$i]['lease']).'</td>';
	
		echo '<td><b>Rent:</b><br>$'.htmlentities($info[$i]['payments']).' A Month</td>';
		
		if(!empty($info[$i]['amount'])) {
			echo '<td><b>Total Rent Paid:</b><br>$'.htmlentities($info[$i]['amount']).'</td>';
		}
		echo '</tr>';
		
		echo '<tr>';
		if($info[$i]['address_locked'] != '0') {
			$verified = 'Yes';
		} else {
			$verified = 'No';
		}
		echo '<td><b>Verified:</b><br>'.$verified.'</td>';
		echo '<td><b>Linked Landlord:</b><br>'.date('m-d-Y h:i:s a', strtotime($info[$i]['timestamp'])+3600).' <small>EST</small></td>';
		echo '<td></td>';
		echo '</tr>';
		echo '</table>';
		
	}
?>
