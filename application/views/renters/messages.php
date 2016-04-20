<div class="row">
	<div class="col-sm-6">
		<h2><i class="fa fa-comments-o text-warning"></i> Message Landlord</h2>
	</div>
	<div class="col-sm-6 text-right">
		<br>
		<a href="#" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#createMessage"><i class="fa fa-envelope-o"></i> Create New Message</a>
	</div>
</div>

<?php
	if(validation_errors() != '') 
	{ 
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}


?>
<hr>
<?php
	if(!empty($messages)) {

		for($i=0;$i<sizeof($messages);$i++) {
			if(!empty($messages[$i]['bName'])) {
				$address_by = ucwords($messages[$i]['bName']);
			} else {
				$address_by = ucwords($messages[$i]['name']);
			}
			echo '<div class="well">';
			echo '<div class="message">';
			echo '<div class="row message-header">
					<div class="col-sm-12">';
			echo '<span class="show-message btn btn-warning btn-sm toolTips" title="Show Message Details" data-id="'.$messages[$i]['id'].'"><i class="fa fa-bars"></i> </span> '; 
			echo '('.date('M Y', strtotime($messages[$i]['timestamp'])).') - <b> '.ucwords($messages[$i]['subject']).'</b>';
					
			$count_new_messages = $this->message_user->get_new_message_count($messages[$i]['id']);
			if($count_new_messages['count(id)'] > 0) {
				echo ' <span id="label-'.$messages[$i]['id'].'" class="label label-success pull-right">'.$count_new_messages['count(id)'].' New</span>';
			}
			echo '</div></div>';
			echo '<div class="collapse collapse-message-'.$messages[$i]['id'].'"><hr>';
			
			// Show Child Messages
			
			$child_messages = $this->message_user->show_child_messages($messages[$i]['id']);
			if(!empty($child_messages)) {
				for($s=0;$s<count($child_messages);$s++) {
					echo '
						<div class="row">
							<div class="col-sm-12">
								<div class="row">
									<div class="col-sm-6">';
						
					if($child_messages[$s]['sent_by'] == 0) {
						echo '<b>Sent To:</b> '.$address_by.'<br>';
					} else {
						echo '<b>From:</b> '.$address_by.'<br>';
					}
					if($messages[$i]['sent_by'] == 0) {
						echo '<b>Name:</b> '.$child_messages[$s]['name'].'<br>';
						echo '<b>Email:</b> '.$child_messages[$s]['email'].'<br>';
					}
					if($child_messages[$s]['landlord_viewed'] == '0000-00-00 00:00:00' OR empty($child_messages[$s]['landlord_viewed'])) {
						echo '<b>Opened On:</b> Not Opened Yet';
					} else {
						echo '<b>Opened On:</b> '.date('m-d-Y', strtotime($child_messages[$s]['landlord_viewed'])+3600).' <span class="text-warning">'.date('h:i A', strtotime($child_messages[$s]['landlord_viewed'])+3600).' <small>EST</small></span>';
					}
					
					echo '</div>
							<div class="col-sm-6 right-text">
								<b>Sent On:</b> '.date('m-d-Y', strtotime($child_messages[$s]['timestamp'])+3600).' <span class="text-warning">'.date('h:i A', strtotime($child_messages[$s]['timestamp'])+3600).' <small>EST</small></span>
							</div>
						</div>
						<b>Message:</b><br>
						'.$child_messages[$s]['message'].'
						<br>
						<br>
						<div class="row">
							<div class="col-sm-6">';
					if(!empty($child_messages[$s]['attachment'])) {
						echo '<i class="fa fa-paperclip"></i> View Attachment:<br><a href="'.base_url().'message-uploads/'.$child_messages[$s]['attachment'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Attachment: <br> '.$child_messages[$s]['attachment'].'" class="toolTips"> '.$child_messages[$s]['attachment'].'</a>';
					}
					echo '</div><div class="col-sm-6 right-text">';
											
					echo '</div></div></div></div><hr>';
				}
				// End Child Messages
			}
			// Start of orginal message
			echo '<div class="row renter">
					<div class="col-sm-12">
						<div class="row">
							<div class="col-sm-6">';
			if($messages[$i]['sent_by'] == 0) {
				echo '<b>Sent To:</b> '.$address_by.'<br>';
			} else {
				echo '<b>From:</b> '.$address_by.'<br>';
			}
			if($messages[$i]['sent_by'] == 0) {
				echo '<b>Name:</b> '.$messages[$i]['name'].'<br>';
				echo '<b>Email:</b> '.$messages[$i]['email'].'<br>';
			}	
			if($messages[$i]['sent_by'] == 0) { // sent_by 0 means sent by tenant | sent_by 1 means sent by landlord
				if($messages[$i]['landlord_viewed'] == '0000-00-00 00:00:00' OR empty($messages[$i]['landlord_viewed'])) {
					echo '<b>Opened On:</b> Not Opened Yet';
				} else {
					echo '<b>Opened On:</b> '.date('m-d-Y', strtotime($messages[$i]['landlord_viewed'])+3600).' <span class="text-warning">'.date('h:i:s a', strtotime($messages[$i]['landlord_viewed'])+3600).' </span> <small>EST</small>';
				}
			} else {
				if($messages[$i]['tenant_viewed'] != '0000-00-00 00:00:00' or !empty($messages[$i]['tenant_viewed'])) {
					echo '<b>Opened On:</b> '.date('m-d-Y', strtotime($messages[$i]['tenant_viewed'])+3600).' <span class="text-warning">'.date('h:i A', strtotime($messages[$i]['tenant_viewed'])+3600).'</span> <small>EST</small>';
				} else {
					echo '<b>Opened On:</b> Not Opened Yet';
				}
			}
			
			echo '</div>
					<div class="col-sm-6 right-text">
							<b>Sent On:</b> '.date('m-d-Y', strtotime($messages[$i]['timestamp'])+3600).' <span class="text-warning">'.date('h:i A', strtotime($messages[$i]['timestamp'])+3600).' </span> <small>EST</small>
							</div>
						</div>
						<b>Message:</b><br>
						'.$messages[$i]['message'].'
						<br>
						<br>
						<div class="row">
							<div class="col-sm-6">';
			if(!empty($messages[$i]['attachment'])) {
				echo '<i class="fa fa-paperclip"></i> View Attachment: <br><a href="'.base_url().'message-uploads/'.$messages[$i]['attachment'].'" target="_blank" data-toggle="tooltip" data-placement="top" title="View Attachment: <br> '.$messages[$i]['attachment'].'" class="toolTips">'.$messages[$i]['attachment'].'</a>';
			}
			echo '</div>
							<div class="col-sm-6 right-text">';
			
			echo            '</div>
						</div><hr>';
			// End of orginal message 
			
			
			
			echo '<div class="row"><div class="col-sm-6"><a href="'.base_url().'renters/print-message/'.$messages[$i]['id'].'" class="btn btn-warning btn-xs"><i class="fa fa-print"></i> Print Conversation</a></div><div class="col-sm-6 right-text">
					<a href="#replyMessage" class="reply-message-btn btn btn-warning btn-xs pull-right toolTips" data-toggle="modal" data-target="#replyMessage" data-reply-id="'.$messages[$i]['id'].'" title="Replay To This Message"><i class="fa fa-reply"></i> Reply</a>
				</div></div></div></div></div></div></div>';
		}
		
	
	} else {
		echo 'You have not message or received any messages from this landlord yet. To send a message to them click the "Send Message" button above.';
	}
	
	//var_dump($this->session->all_userdata());

?>



<!--Create Message Modal -->
<div class="modal fade" id="createMessage" tabindex="-1" role="dialog" aria-labelledby="createMessage" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('renters/create_new_message'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Send A New Message To This Landlord</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<label><i class="fa fa-asterisk text-danger"></i> Subject:</label>
							<input type="text" class="form-control" name="subject" required="required" maxlength="45">
							<label><i class="fa fa-asterisk text-danger"></i> Message:</label>
							<textarea name="message" class="form-control message" required="required"></textarea>
							<div class="text-right">
								<p><em>Grab This And Pull Down To Resize Box <i class="fa fa-level-up"></i></em></p>
							</div>
							<label>Attach A File: (pdf, jpeg, png, gif, doc, docx only)</label>
							<input type='file' name='attachment' class='form-control attachment'>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning btn-sm sendMsg">Send Message</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<!--Reply Modal -->
<div class="modal fade" id="replyMessage" tabindex="-1" role="dialog" aria-labelledby="replyMessage" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('renters/replied_message'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title" id="myModalLabel">Reply To This Message</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-lg-12">
							<label><i class="fa fa-asterisk text-danger"></i> Message:</label>
							<textarea name="message" class="form-control message" required="required"></textarea>
							<div class="text-right">
								<p><em>Grab This And Pull Down To Resize Box <i class="fa fa-level-up"></i></em></p>
							</div>
							<label>Attach A File: (pdf, jpeg, png, gif, doc, docx only)</label>
							<input type='file' name='attachment' class='form-control attachment-img'>
							<input type='hidden' class='parent-id' name='parent_id' value=''>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-warning btn-sm sendMsg">Send Message</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>