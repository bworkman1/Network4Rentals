<div class="row">
	<div class="col-sm-5">
		<h2><i class="fa fa-comments text-primary"></i> Messages</h2>
	</div>
	<div class="col-sm-2"><br>
		<?php 
			$check = $this->session->userdata('date_to_msg');
			if(!empty($check)) {
				echo '<a href="'.base_url().'landlords/reset-dates-msg/'.(int)$this->uri->segment(3).'" class="btn btn-xs btn-primary btn-block"><i class="fa fa-times"></i> Reset Dates</a>';
			}
		?>
	</div>
	<div class="col-sm-2"><br>
		<button class="btn btn-xs btn-primary btn-block" data-toggle="modal" data-target="#searchMessages"><i class="fa fa-search"></i> Search Dates</button>
	</div>
	<div class="col-sm-3"><br>
		<a href="<?php echo base_url(); ?>landlords/print-messages/<?php echo (int)$this->uri->segment(3); ?>" class="btn btn-xs btn-primary btn-block"><i class="fa fa-print"></i> Print Messages</a>
	</div>
</div>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
?>
<div class="well">
	<p><b>Send Message To:</b> <?php echo $message_to['name']; ?></p>
	<p><b>Message</b></p>
	<?php echo form_open_multipart('landlords/send_new_message/'.(int)$this->uri->segment(3)); ?>
		<textarea class="form-control" name="message" maxlength="1500"></textarea>
		<br>
		<div class="row">
			<div class="col-sm-6">
				<input type="file" name="file" class="form-control">
			</div>
			<div class="col-sm-6 text-right">
				<button type="submit" class="btn btn-primary btn-sm">Send Message</button>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>
<?php
	$timeZone = 3600;
	if(!empty($results)) {
		echo '<ul id="user-messages">';
		foreach($results as $key => $val) {
			if($val->sent_by == 0) {
			  echo '<li class="tenant-sent">';
			} else {
			  echo '<li class="landlord-sent">';
			}
				echo '<div class="row">';
					echo '<div class="col-sm-6">';
						if($val->sent_by == 0) {
							echo '<b>From:</b><br> '.$val->tenant_name;
						} else {
							echo '<b>From:</b><br> '.$val->landlord_name;
						}
					echo '</div>';
					echo '<div class="col-sm-6 text-right">';
						echo '<p><b>Sent On:</b> '. date('m-d-Y h:i a', strtotime($val->timestamp)+$timeZone).'<small> EST</small><br>';
						if($val->sent_by == 0) {
							echo '<b>Opened On:</b> ';
							if($val->landlord_viewed == '0000-00-00 00:00:00' || empty($val->landlord_viewed)) {
								echo 'Not Opened Yet';
							} else {
								echo date('m-d-Y h:i a', strtotime($val->landlord_viewed)+$timeZone).'<small> EST</small></p>';
							}
						} else {
							echo '<b>Opened On:</b> ';
							if($val->tenant_viewed == '0000-00-00 00:00:00' || empty($val->tenant_viewed)) {
								echo 'Not Opened Yet'; 
							} else {
								echo date('m-d-Y h:i a', strtotime($val->tenant_viewed)+$timeZone).'<small> EST</small></p>';
							}
						}
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
					echo '<div class="col-sm-12">';
						echo '<h5><b>Message:</b></h5>';
						echo '<p>'.htmlentities($val->message).'</p>';
						if(!empty($val->attachment)) {
							echo '<p><b>Attachment:</b><br><a href="'.base_url().'message-uploads/'.$val->attachment.'" target="_blank">'.$val->attachment.'</a></p>';
						}
					echo '</div>';
				echo '</div>';
				echo '<div class="row">';
					echo '<div class="col-sm-12 text-right">';
						echo '<p><b>Sent To:</b><br> '.$val->email_sent_to.'<br>';
						echo '</p>';
					echo '</div>';
				echo '</div>';
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<h3><span class="text-danger">*</span>No Messages Found</h3>';
	}
	
?>
<div class="clearfix"></div>

<div class="text-center">
	<?php echo $links; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="searchMessages" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<?php echo form_open('landlords/message-tenant/'.(int)$this->uri->segment(3)); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Narrow Messages Down By Date</h4>
      </div>
      <div class="modal-body">
			<div class="row">
				<div class="col-sm-6">
					<p><b>Choose Start Date:</b><br>
					<input type="text" class="datepicker form-control" name="date_to" value="<?php echo $this->session->userdata('date_to_msg'); ?>" readonly="readonly"></p>
				</div>
				<div class="col-sm-6">
					<p><b>Choose End Date:</b><br>
					<input type="text" class="datepicker form-control" name="date_from" value="<?php echo $this->session->userdata('date_from_msg'); ?>" readonly="readonly"></p>
				</div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Search</button>
      </div>
	  <?php echo form_close(); ?>
    </div>
  </div>
</div>