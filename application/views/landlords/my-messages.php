<div class="row">
	<div class="col-sm-5">
		<h2><i class="fa fa-envelope text-primary"></i> N4R Mail</h2>
	</div>
	<div class="col-sm-4">
		<br>
		<?php
			$current_owner = '';
			if(!empty($switches)) {
				echo '<em>Currently Viewing As</em>';
				echo form_open('landlords/switch-admin-group'); 
				echo '<select class="form-control input-sm pull-right select-landlord" name="admin" onchange="this.form.submit()">';
					echo '<option value="0">My Accout</option>';
					for($i=0;$i<count($switches);$i++) {
						if($this->session->userdata('temp_id') == $switches[$i]['main_admin_id']) {
							$current_owner = $switches[$i]['sub_b_name'];
							echo '<option value="'.$switches[$i]['main_admin_id'].'" selected="selected" data-bname="'.$switches[$i]['sub_b_name'].'">'.$switches[$i]['sub_b_name'].' - Tenants</option>';
						} else {
							echo '<option value="'.$switches[$i]['main_admin_id'].'">'.$switches[$i]['sub_b_name'].' Tenants</option>';
						}
					}
				echo '</select>';
				echo form_close();
			}
		?>	
	</div>
	<div class="col-sm-3">
		<br>
		<a href="#new-message" class="btn btn-primary btn-block btn-sm new-message" data-toggle="modal" data-dismiss="modal"><i class="fa fa-pencil"></i> New Message</a>
	</div>
</div>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
?>
<div class="mail">
	
<?php
	if(!empty($results)) {
		echo '<ul>';
		for($i=0;$i<count($results);$i++) {
			if(!empty($results[$i]['landlord_viewed'])) {
				if($results[$i]['new_messages']>0) {
					echo '<li>';
				} else {
					echo '<li class="message-viewed">';
				}
			} else {
				echo '<li>';
			}
			echo '
					<a href="" class="view-full-message" data-message="'.$results[$i]['id'].'">
						<div class="row">
							<div class="col-sm-3">';
								if($results[$i]['new_messages']>0) {
									echo '<label class="label label-primary new-message-shown">'.$results[$i]['new_messages'].'</label> ';
								}
								echo $results[$i]['name'].'
							</div>
							<div class="col-sm-7">
								'.substr($results[$i]['subject'], 0, 60).'
							</div>
							<div class="col-sm-2 text-right">';
								
								echo ' '.date('M-j', strtotime($results[$i]['timestamp'])).'
							</div>
						</div>
					</a>
				</li>';
		}
		echo '</ul>';
	} else {
		echo '<p>You have no messages in your inbox yet. Once you send one or receive one you will see them below.</p>';
	}
?>
</div>
<div class="clearfix"></div>
<div class="text-center">
	<br><div class="thinking"></div>
	<?php echo $links; ?>
</div>
<div class="modal fade" id="view-message" tabindex="-1" role="dialog" aria-labelledby="view-message" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o text-primary"></i> Communications From <span id="sender-speaker"></span><!--SENDER NAME--></h4>
				<h4>Subject:</h4>
				<p class="message-view-title">Subject: <!-- email subject goes here--> </p>
			</div>
			<div class="modal-body">
				<div class="thinking"></div>
				<div class="mail-message">
					<!-- messages goes here-->
				</div>
			</div>
			<div class="modal-footer">
				<div id="print-message-button"></div>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button class="btn btn-primary btn-sm reply-message" data-toggle="modal" data-dismiss="modal" href="#reply-message"><i class="fa fa-reply"></i> Reply</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="reply-message" tabindex="-1" role="dialog" aria-labelledby="view-message" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open_multipart('landlords/reply-messages'); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o text-primary"></i> Reply</h4>
			</div>
			<div class="modal-body">
				<label><b>Message:</b></label>
				<textarea class="form-control" name="message" style="height: 300px" maxlength="700"></textarea>
				<input type="hidden" id="hidden-id" name="hidden_id">
				<div class="row">
					<div class="col-sm-6">
						<label><b>Attach A File:</b></label>
						<input type="file" class="attachment form-control" name="file">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-location-arrow"></i> Send</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>

<div class="modal fade" id="new-message" tabindex="-1" role="dialog" aria-labelledby="view-message" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open_multipart('landlords/send-new-message'); ?>
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope-o text-primary"></i> New Message</h4>
				<!-- <p><span class="text-danger">*</span> Sending Message As <span class="who"><b>Yourself</b></span></p> -->
			</div>
			<div class="modal-body">
				<div class="row">
						
						<div class="col-sm-6">
							<?php
								echo '<label><b>Select An Account To Send From:</b></label>';
								echo '<select class="form-control input-sm pull-right select-landlord" name="admin" required>';
								echo '<option data-init="'.$this->session->userdata('user_id').'" value="0">My Accout</option>';
									if($switches) {
										for($i=0;$i<count($switches);$i++) {
											if($this->session->userdata('temp_id') == $switches[$i]['main_admin_id']) {
												$current_owner = $switches[$i]['sub_b_name'];
												echo '<option value="'.$switches[$i]['main_admin_id'].'" selected="selected" data-bname="'.$switches[$i]['sub_b_name'].'">'.$switches[$i]['sub_b_name'].' - Tenants</option>';
											} else {
												echo '<option value="'.$switches[$i]['main_admin_id'].'">'.$switches[$i]['sub_b_name'].' Tenants</option>';
											}
										}
									}
								echo '</select>';
							?>
						</div>
			
					<div class="col-sm-6">
						<label><b>Select Tenants:</b></label>
						<div class="message-property">
							<select id="selectTenants" name="tenants[]" style="width: 100%" multiple='multiple'>
								
							</select>
						</div>
					</div>
				</div>
				<label><b>Subject:</b></label>
				<input type="text" name="subject" maxlength="70" class="form-control" required>
				<label><b>Message:</b></label>
				<textarea class="form-control" name="message" style="height: 300px" maxlength="700" required></textarea>
				<div class="row">
					<div class="col-sm-6">
						<label><b>Attach A File:</b></label>
						<input type="file" class="attachment form-control" name="file">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-location-arrow"></i> Send</button>
			</div>
		</div>
		<?php echo form_close(); ?>
	</div>
</div>