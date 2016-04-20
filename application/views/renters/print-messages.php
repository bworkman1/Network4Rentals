<div style="A_CSS_ATTRIBUTE:all;position: fixed;bottom: 10px; left: 10px; font-style: italic; font-size: 14px; text-align: center; color: #555;">
<p>Message Report Generated From www.Network4Rentals.com <br> &copy; Copyright Network 4 Rentals LLC. <?php echo date('Y'); ?></p>
</div>
<div style="width: 100%;">
<img src="<?php echo base_url(); ?>/assets/themes/default/images/black_white_network_logo.jpg" style="padding: 15px;" width="458" height="110">
<hr>
<table width="100%">
	<tr>
		<td valign="top">
			<h3>Property Manager:</h3>
			<?php if(!empty($user_data[0]['bName'])) { ?>
				<b><?php echo $user_data[0]['bName']; ?></b><br>
			<?php } ?>
			<?php echo $user_data[0]['name']; ?><br>
			<?php echo "(".substr($user_data[0]['phone'], 0, 3).") ".substr($user_data[0]['phone'], 3, 3)."-".substr($user_data[0]['phone'],6); ?><br>
			<?php echo $user_data[0]['email']; ?><br>
		</td>
		<td valign="top">
			<h3>Rental Property:</h3>
			<p><b><?php echo $user_data[1]['name']; ?></b><br>
			<?php echo $user_data[1]['email']; ?><br>
			<?php echo "(".substr($user_data[1]['phone'], 0, 3).") ".substr($user_data[1]['phone'], 3, 3)."-".substr($user_data[1]['phone'],6); ?></p>
		</td>
	</tr>
	<tr>
		<td colspan="2" align="center"><h3>Messages</h3></td>
	</tr>
	<?php
		foreach($results as $key => $val) {
			echo '<table width="100%" frame="box" cellpadding="3">';
				echo '<tr>';
					echo '<td>';
						if($val['sent_by'] == 1) {
							echo '<tr bgcolor="#eeeeee">';
						} else {
							echo '<tr>';
						}
							echo '<td>';
								if($val['sent_by'] == 1) {
									echo '<em><b>'.$val['landlord_name'].'</b> Wrote</em>';
								} else {
									echo '<em><b>'.$val['tenant_name'].'</b> Wrote</em>';
								}
							echo '</td>';
							echo '<td align="right">';
								echo '<em><b>Sent On:</b> '.date('m-d-Y h:i a', strtotime($val['timestamp']) +3600 ).' |';
								if($val['sent_by'] == 0) {
									echo '<b>To:</b> '.$val['landlord_email'].'</em>';
								} else {
									echo '<b>To:</b> '.$val['tenant_email'].'</em>';
								}
								
							echo '</td>';
						echo '</tr>';
						if($val['sent_by'] == 1) {
							echo '<tr bgcolor="#eeeeee">';
						} else {
							echo '<tr>';
						}
							echo '<td colspan="2">'.$val['message'].'<br>';
							if(!empty($val['attachment'])) {
								echo '<br><b>Attachment: </b><br>'.$val['attachment'];							
							}
							echo '</td>';
						echo '</tr>';
						if($val['sent_by'] == 1) {
							echo '<tr bgcolor="#eeeeee">';
						} else {
							echo '<tr>';
						}
							echo '<td></td>';
							echo '<td align="right"><em><small><b>Opened On:</b>';
							if($val['sent_by']  == 0) {
								echo date('m-d-Y h:i a', strtotime($val['landlord_viewed'])+3600);
							} else {
								echo date('m-d-Y h:i a', strtotime($val['tenant_viewed'])+3600);
							}
							echo '</small></em></td>';
						echo '</tr>';
					echo '</td>';
				echo '</tr>';
			echo '</table>';
		}
		
	?>
</table>