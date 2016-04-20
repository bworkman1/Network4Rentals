<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('feedback_error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('feedback_error').'</div>';
	}
	if($this->session->flashdata('feedback_success')) {
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('feedback_success').'</div>';
	}
	
?>
<div class="row">
	<div class="col-md-6">
		<!-- Edit User Details -->
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-user"></i> Edit Account Below
			</div>
			<div class="panel-body">
			<?php 
				echo form_open('renters/edit_account');
				echo form_label('<i class="fa fa-asterisk text-danger"></i> Full Name:');
				$data = array(
						  'name'        => 'fullname',
						  'id'          => 'fullname',
						  'maxlength'   => '100',
						  'class'       => 'form-control',
						  'required' 	=> '',
						  'value'		=> $user_info[0]['name']
						);
				echo form_input($data);
				echo form_label('<i class="fa fa-asterisk text-danger"></i> Email:');
				$data = array(
				  'name'        => 'email',
				  'id'          => 'email',
				  'maxlength'   => '100',
				  'class'       => 'form-control',
				  'required' 	=> '',
				  'value'		=> $user_info[0]['email']
				);
				echo form_input($data);
				
				echo form_label('<i class="fa fa-asterisk text-danger"></i> Phone:');
				$data = array(
				  'name'        => 'phone',
				  'id'          => 'phone',
				  'maxlength'   => '15',
				  'class'       => 'form-control phone',
				  'required' 	=> '',
				  'value'		=> $user_info[0]['phone']
				);
				echo form_input($data);	
			?>
			<div class="row">
				<div class="col-md-7">
					<label>Cell Phone</label>
					<input type="text" class="form-control phone cellPhone" max-length="15" name="cell_phone" value="<?php echo $user_info[0]['cell_phone']; ?>" >
				</div>
				<div class="col-md-5">
					<label>Accept Texts</label>
					<select name="sms_msgs" class="form-control">
						<?php
							$options = array('n'=>'No', 'y'=>'Yes');
							foreach($options as $key => $val) {
								if($user_info[0]['sms_msgs'] == $key) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
								}  else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
						?>
					</select>
					
				</div>
			</div>
		
				
			<?php 
				
				$data = array(
					'value' => 'true',
					'type' => 'submit',
					'class' => 'btn btn-primary',
					'content' => '<i class="fa fa-location-arrow"></i> Update My Account'
				);

				echo '<br>';
				echo form_button($data);				
				echo form_close();
			?>
			</div>
		</div>
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-envelope"></i> Test Your Email
			</div>
			<div class="panel-body">
				<p>If you had trouble receiving your email when you created your account its a good idea to try testing your email to make sure you are receiving the emails from us. Once you recieve one from us add the email to your safe senders list.</p>
				<a href="<?php echo base_url(); ?>renters/test-email" class="btn btn-primary"><i class="fa fa-location-arrow"></i> Test Your Email</a>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<!-- Change Password -->
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-key"></i> Change Password
			</div>
			<div class="panel-body">
				<?php
					echo form_open('renters/change_password');
					echo form_label('New Password:');
					$data = array(
					  'name'        => 'password',
					  'id'          => 'password',
					  'maxlength'   => '100',
					  'class'       => 'form-control',
					);
					echo form_password($data);	
					echo form_label('Confirm Password:');
					$data = array(
					  'name'        => 'password1',
					  'id'          => 'password1',
					  'maxlength'   => '100',
					  'class'       => 'form-control',
					);
					echo form_password($data);
					$data = array(
						'value' => 'true',
						'type' => 'submit',
						'class' => 'btn btn-primary',
						'content' => '<i class="fa fa-location-arrow"></i> Change Password'
					);
					echo '<br>';
					echo form_button($data);	
					echo form_close();
				?>			
			</div>
		</div>
		<!-- Add Forwarding Email -->
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-share"></i> Forward Emails
			</div>
			<div class="panel-body">
				<?php
					echo form_open('renters/add_forwarding_email');
					echo 'If you would like your emails you receive from us to go to another email address enter the email address below. You will still receive emails on your original email, only difference is that emails will also go to the email address listed below.<br><br>';
					echo form_label('Forward Email Address:');
					$data = array(
					  'name'        => 'f_email',
					  'id'          => 'f_email',
					  'maxlength'   => '60',
					  'class'       => 'form-control',
					  'value'		=> $user_info[0]['forwarding_email']
					);
					echo form_input($data);	
					$data = array(
						'value' => 'true',
						'type' => 'submit',
						'class' => 'btn btn-primary',
						'content' => '<i class="fa fa-location-arrow"></i> Add Forwarded Email'
					);
					echo '<br>';
					echo form_button($data);	
					echo form_close();
				?>
			</div>
		</div>
	</div>	
</div>