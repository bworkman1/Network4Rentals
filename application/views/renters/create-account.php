<div class="row">
	<div class="col-md-6">
		<h2><i class="text-warning fa fa-user"></i> Create Renters Account</h2>
		<p>Creating an account with us allows you to submit tickets to your landlord with ease and also keep track of your service request. This cuts back on paper and helps you to keep things organized and easy to find. The best part about it is that its completely free. We also take pride in protecting your privacy so we do not sell your data to any third party services for any reason.</p>
	</div>
	<div class="col-md-6">
		<br><br>
		<div class="youtube_video"><div class="fluid-width-video-wrapper" style="padding-top: 50%;"><iframe src="//www.youtube.com/embed/lx4a3TCpH5s?rel=0" frameborder="0" allowfullscreen="" id="fitvid421683"></iframe></div></div>
	</div>
</div>
<h5>Already Have An Account? <a href="<?php echo base_url(); ?>renters/login" class="btn btn-warning btn-xs"><i class="fa fa-unlock"></i> Login</a></h5>
<h3>Create Your Free Account</h3>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('user_created') == 'n') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>Something went wrong, try again.</div>';
	}
	
	
?>
<div class="">
	<div class="row">
		<?php 
			echo form_open('renters/create_user_account');
			echo '<div class="col-lg-12">';
			echo form_fieldset(' <i class="fa fa-user"></i> Create Account:');
			echo '</div>';
			echo '<div class="col-md-6">';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Full Name:');
			if(!empty($_POST['fullname'])) {
				$fullname = $_POST['fullname'];
			} else {
				$fullname = '';
			}
			$data = array(
					  'name'        => 'fullname',
					  'id'          => 'fullname',
					  'maxlength'   => '200',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value' 	=> $fullname
					);
			echo form_input($data);
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Email:');
			if(!empty($_POST['email'])) {
				$email = $_POST['email'];
			} else {
				$email = '';
			}
			$data = array(
					  'name'        => 'email',
					  'id'          => 'email',
					  'maxlength'   => '100',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value'		=> $email
					);
			echo form_input($data);
			
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Phone:');
			if(!empty($_POST['phone'])) {
				$phone = $_POST['phone'];
			} else {
				$phone = '';
			}
			$data = array(
					  'name'        => 'phone',
					  'id'          => 'phone',
					  'maxlength'   => '15',
					  'class'       => 'form-control phone',
					  'required' 	=> '',
					  'value'		=> $phone
					);
			echo form_input($data);	
			
			$hear_about = array(
				'Event or Booth',
				'Friends',
				'Family',
				'Online Search',
				'Literature (handouts, fliers, etc.',
				'Advertisement',
				'Utica Home Coming',
				'Facebook',
				'Google+',
				'Twitter',
				'Linkedin',
				'Landlord/Mgr. Request',
				'Other'
				
			);
			echo form_label('<i class="fa fa-asterisk text-danger"></i> How Did You Hear About Us?');
			echo '<select name="hear" class="form-control" required>';
				echo '<option value="">Select One...</option>';
				foreach($hear_about as $val) {
					if($val == $_POST['hear']) {
						echo '<option selected="selected">'.$val.'</option>';
					} else {
						echo '<option>'.$val.'</option>';
					}
				}
			echo '</select>';
			
			echo '</div>';
			echo '<div class="col-md-6">';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Username:');
			if(!empty($_POST['username'])) {
				$username = $_POST['username'];
			} else {
				$username = '';
			}
			$data = array(
					  'name'        => 'username',
					  'id'          => 'username',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'value'		=> $username
					);
			echo form_input($data);	
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Password:');			
			$data = array(
					  'name'        => 'password',
					  'id'          => 'password',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'required' 	=> ''
					);
			echo form_password($data);				
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Confirm Password:');
			$data = array(
					  'name'        => 'password1',
					  'id'          => 'password1',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'required' 	=> ''
					);
			echo form_password($data);				
			
		?>
		

		
		<?php
			
			echo form_label('<i class="fa fa-asterisk text-danger"></i> I Agree To The <a href="'.base_url().'renters/terms-of-service" target="_blank">Terms Of Services</a>:');
			echo '<br><input type="checkbox" name="terms" value="y" required="" '.set_checkbox('mycheck', '1').' /> Yes<br>';
		
			echo '</div>';
			echo '</div>';
			
			$data = array(
				'value' => 'true',
				'type' => 'submit',
				'class' => 'btn btn-warning',
				'content' => '<i class="fa fa-location-arrow"></i> Create My Account'
			);
			
			echo '<br>';
			echo form_button($data);
			echo form_close();
		?>
		</div>
