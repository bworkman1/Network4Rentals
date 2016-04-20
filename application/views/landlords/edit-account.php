<div class="row">
	<div class="col-md-12">
		<h2><i class="fa fa-user text-primary"></i> Account Details</h2>
		<hr>
	</div>
</div>
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
<div id="inviteFeedback"></div>
<div class="edit-account-options">
	<div class="row">
		<div class="col-sm-4">
			<a href="#" data-toggle="modal" data-target="#edit-details">
				<div class="account-option">
					<i class="fa fa-user"></i>
					<h4>Edit My Details</h4>
				</div>
			</a>
		</div>
		<div class="col-sm-4">
			<a href="#" data-toggle="modal" data-target="#password-change">
				<div class="account-option">
					<i class="fa fa-key"></i>
					<h4>Change Password</h4>		
				</div>	
			</a>
		</div>
		<div class="col-sm-4">
			<a href="<?php echo base_url(); ?>landlords/payment-settings">
				<div class="account-option">
					<i class="fa fa-money"></i>
					<h4>Payment Settings</h4>				
				</div>	
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<a href="<?php echo base_url(); ?>landlords/public-page-settings">
				<div class="account-option">
					<i class="fa fa-link"></i>
					<h4>Public Page Settings</h4>
				</div>
			</a>
		</div>
		<div class="col-sm-4">
			<a href="<?php echo base_url(); ?>landlords/my-admins">
				<div class="account-option">
					<i class="fa fa-users"></i>
					<h4>Managers/Sub Groups</h4>
				</div>
			</a>
		</div>
		<div class="col-sm-4">
			<a data-toggle="modal" data-target="#inviteTenants">
				<div class="account-option">
					<i class="fa fa-paper-plane-o"></i>
					<h4>Invite Tenant To N4R</h4>
				</div>
			</a>			
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<a href="<?php echo base_url(); ?>landlords/accounts">
				<div class="account-option">
					<i class="fa fa-building-o"></i>
					<h4>Admin Accounts</h4>
				</div>
			</a>			
		</div>
		<div class="col-sm-4">
			<a data-toggle="modal" data-target="#forward-email">
				<div class="account-option">
					<i class="fa fa-envelope"></i>
					<h4>Forward Service Requests</h4>
				</div>
			</a>			
		</div>
		<div class="col-sm-4">
			<a data-toggle="modal" data-target="#test-email">
				<div class="account-option">
					<i class="fa fa-envelope-square"></i>
					<h4>Test Email</h4>
				</div>
			</a>			
		</div>
	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="password-change" tabindex="-1" role="dialog" aria-labelledby="password-change" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/update-password'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-key text-primary"></i> Change My Password</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<label>New Password:</label>
							<input type="password" name="pwd1" class="form-control pwd pwd1" required="required" maxlength="50">
							<div class='error-info'></div>
						</div>
						<div class="col-sm-6">
							<label>Confirm Password:</label>
							<input type="password" name="pwd2" class="form-control pwd pwd-check" required="required" maxlength="50">
							<div class='error-info'></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Password</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<!-- Invite Tenant Modal -->
<div class="modal fade" id="invite-tenant" tabindex="-1" role="dialog" aria-labelledby="invite-tenant" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/invite-tenant'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-key text-primary"></i> Invite Tenant To N4R</h4>
				</div>
				<div class="modal-body">
					<?php if($public_page_setup) { ?>
					<div class="row">
						<div class="col-sm-6">
							<label>Email:</label>
							<input type="text" name="email" class="form-control" required="required" maxlength="80">
						</div>
						<div class="col-sm-6">
							<label>Confirm Email:</label>
							<input type="text" name="email2" class="form-control" required="required" maxlength="80">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<?php
								if(!empty($switches)) {
									echo '<label>Send As Sub Account:</label>';
									echo '<select class="form-control" name="behalf">';
										echo '<option value="">My Account</option>';
										for($i=0;$i<count($switches);$i++) {
											echo '<option value="'.$switches[$i]['main_admin_id'].'">'.$switches[$i]['sub_b_name'].'</option>';
										}
									echo '</select>';
								}
							?>
						</div>
					</div>
					
					<?php } else { ?>
						<h4>Warning:</h4>
						<p>In order to use this feature you must create your public page. Click the create public page button below to enable this feature.</p>
						<a href="<?php echo base_url(); ?>landlords/public-page-settings/" class="btn btn-primary btn-sm">Create Public Page</a>
					<?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send Invite</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="forward-email" tabindex="-1" role="dialog" aria-labelledby="forward-email" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/add-forwarding-email'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Forward Service Requests</h4>
				</div>
				<div class="modal-body">
					<p>Adding a forwarding email address sends your service request emails to you and the email you add to the box below.</p>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<label>Forwarding Email:</label>
							<input type="text" name="forwarding_email" class="form-control fEmail" maxlength="50" value="<?php echo $info['forwarding_email']; ?>">
						</div>
						<div class="col-sm-3"></div>
						<div class="col-sm-3">
							<?php if(!empty($info['forwarding_email'])) { ?>
								<label>Remove Email:</label><br>
								<a href="<?php echo base_url(); ?>landlords/remove-forwarding-email" class="btn btn-danger btn-sm"><i class="fa fa-times"></i> Remove Email</a>
							<?php } ?>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<label>Forwarding Cell Phone:</label>
							<input type="text" class="form-control phone" name="forwarding_cell" value="<?php echo $info['forwarding_cell']; ?>">
						</div>
						<div class="col-sm-6">
							<label>Accept Text Messages:</label>
							<select class="form-control" name="forwarding_sms_msgs">
								<?php
									$options = array('y'=>'Yes', 'n'=>'No');
									foreach($options as $key=>$val) {
										if($key == $info['forwarding_sms_msgs']) {
											echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Forwarding Email</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<div class="modal fade" id="test-email" tabindex="-1" role="dialog" aria-labelledby="test-email" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/add-forwarding-email'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Email Test</h4>
				</div>
				<div class="modal-body">
					<p>If you are unsure if you are receiving emails from us you can test your email by clicking the send test email below.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<a href="<?php echo base_url(); ?>landlords/test-email/" type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Send Test Email</a>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<div class="modal fade" id="inviteTenants" tabindex="-1" role="dialog" aria-labelledby="inviteTenants" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Email Test</h4>
			</div>
			<div class="modal-body">
				<div id="inviteError"></div>
				<form id="inviteTenant">
					<div class="row">
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Send By:</label>
							<select class="form-control sendBy" name="sendBy">
								<option>Select One...</option>
								<option value="text">Send By Text Message</option>
								<option value="email">Send Email</option>
							</select>
						</div>
						<div class="col-sm-6">
							<?php	
								if(!empty($switches)) {
									echo '<label><span class="text-danger">*</span>  Send As Sub Account:</label>';
									echo '<select class="form-control" name="behalf">';
										echo '<option value="">My Account</option>';
										for($i=0;$i<count($switches);$i++) {
											echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
										}
									echo '</select>';
								}
							?>
						</div>
					</div>
					<div id="selectionInputs"></div>
					<br>
					<button class="btn btn-primary sendIt"><i class="fa fa-envelope"></i> Send Invite</button>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Edit My Details -->
<div class="modal fade" id="edit-details" tabindex="-1" role="dialog" aria-labelledby="edit-details" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/edit-account'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-user text-primary"></i> Edit My Account</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-sm-6">
							<label>Business Name:</label>
							<input type="text" name="bName" class="form-control" required="required" maxlength="100" value="<?php echo htmlentities($info['bName']); ?>">
						</div>
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Name:</label>
							<input type="text" name="name" class="form-control" required="required" maxlength="100" value="<?php echo htmlentities($info['name']); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Email:</label>
							<input type="text" name="email" class="form-control" required="required" maxlength="50" value="<?php echo htmlentities($info['email']); ?>">
						</div>
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Phone:</label>
							<input type="text" name="phone" class="form-control phone" required="required" maxlength="50" value="<?php echo htmlentities($info['phone']); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Address:</label>
							<input type="text" name="address" class="form-control" required="required" maxlength="50" value="<?php echo htmlentities($info['address']); ?>">
						</div>
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> City:</label>
							<input type="text" name="city" class="form-control" required="required" maxlength="50" value="<?php echo htmlentities($info['city']); ?>">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-4">
							<?php
								echo form_label('<span class="text-danger">*</span> State:');			
								$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
								echo '<select name="state" class="form-control" required="" maxlength="50">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									if($key == $info['state']) {
										echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo '</select>';
							?>
						</div>
						<div class="col-sm-3">
							<label><span class="text-danger">*</span> Zip:</label>
							<input type="text" name="zip" class="form-control" required="required" maxlength="50" value="<?php echo htmlentities($info['zip']); ?>">
						</div>
						<div class="col-sm-5">
							<label>Alt Phone:</label>
							<input type="text" name="alt_phone" class="form-control phone" maxlength="20" value="<?php echo htmlentities($info['alt_phone']); ?>">
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-6">
							<label>Receive Text Messages: </label>
							<select class="form-control textMessages" name="sms_msgs" required>
								<option value="">Select One</option>
								<?php
									$options = array('n'=>'No', 'y'=>'Yes');
									foreach($options as $key => $val) {
										if($key == $info['sms_msgs']) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
								?>
							</select>
						</div>
						<div class="col-sm-6 textMessagePhoneNumber">
							<label>Cell Phone Number:</label>
							<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $info['cell_phone']; ?>">
						</div>
					</div>
					
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div> 
</div>
