<div class="row">
	<div class="col-sm-9">
		<h2><i class="fa fa-envelope text-primary"></i> Viewing Message</h2>
	</div>
	<div class="col-sm-3 text-right">
		<br>
		<a href="<?php echo base_url(); ?>landlords/my-messages" class="btn btn-primary btn-xs">View All Messages</a>
	</div>
</div>
<hr>
<div class="mail-message">
<?php	
	echo '<ul>';
	foreach($results as $key => $val) {
		if($val['sent_by'] == 1) {
			echo '<li class="landlord-msg">';
		} else {
			echo '<li class="tenant-msg">';
		}
			echo '<div class="row">';
				echo '<div class="col-sm-6">';
					if($val['sent_by'] == 1) {
						echo '<small><em><b>You Wrote:</b></em></small>';
					} else {
						echo '<em><b>'.$val['name'].' Wrote:</b></em>';
					}
					echo '</div>';	
					echo '<div class="col-sm-6 text-right">';
					if($val['sent_by'] == 1) {
						echo '<small><em><b>Sent On:</b></em> '.date('m-d-Y H-i-m a', strtotime($val['timestamp'])-3600).'| <em><b>To:</b></em> '.$val['email'].'</small>';
					} else {
						echo '<small><em><b>Sent On:</b></em> '.date('m-d-Y H-i-m a', strtotime($val['timestamp'])-3600).' | <em><b>To:</b></em>Me</small>';
					}
				echo '</div>';	
			echo '</div>';	
			echo '<p>'.$val['message'].'</p>';
			if(!empty($val['attachment'])) {
				echo '<p><i class="fa fa-paperclip"></i> Attachment: <a href="https://network4rentals.com/n4r/message-uploads/'.$val['attachment'].'" target="_blank">'.$val['attachment'].'</a></p>';
			}
			echo '<div class="row">';		
				echo '<div class="col-sm-12 text-right">';
					if($val['sent_by'] == 1) {
						echo '<small><em><b>Opened On:</b> '.date('m-d-Y H-i-m a', strtotime($val['tenant_viewed'])-3600).'</em></small>';
					} else {
						echo '<small><em><b>Opened On:</b> '.date('m-d-Y H-i-m a', strtotime($val['landlord_viewed'])-3600).'</em></small>';
					}
				echo '</div>';	
			echo '</div>';	
		echo '</li>';		
	}
	echo '</ul>';
?>
</div>