<h2><i class="text-warning fa fa-unlock"></i> Login To Your Account:</h2>
<hr>
<p class="hidden-xs">To enjoy all the great benefits of Network 4 Rentals please login to your account. If you are not registered yet please create an account on the left side by clicking the create account link in the menu. For help and support visit our help and support page.</p>
<hr class="hidden-xs">
<?php
	if($this->session->flashdata('createAccount')) {
		echo '<div class="alert alert-success">'.$this->session->flashdata('createAccount').'</div>';
	}
	
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

<div class="row">
	<div class="col-md-5">
		<div class="well">
		<?php 
			echo form_open('renters/user');
			echo form_label('<i class="fa fa-user"></i> Username/Email:');
			$data = array(
					  'name'        => 'username',
					  'id'          => 'username',
					  'maxlength'   => '100',
					  'size'        => '50',
					  'class'       => 'form-control',
					  'required' 	=> ''
					);
			echo form_input($data);
			echo form_label('<br><i class="fa fa-key"></i> Password:');
			$data = array(
					  'name'        => 'password',
					  'id'          => 'password',
					  'maxlength'   => '100',
					  'size'        => '50',
					  'class'       => 'form-control',
					  'required' 	=> ''
					);
			echo form_password($data);
			$data = array(
				'value' => 'true',
				'type' => 'submit',
				'class' => 'btn btn-warning btn-md',
				'content' => '<i class="fa fa-unlock"></i> Login'
			);
			echo '<br>';
			echo form_button($data);
			echo form_close();
		?>
		</div>
	</div>
	<div class='col-md-7'>
		<h2>Need Help?</h2>
		<p>If you forgot your password or need to create an account with us click on on of the following buttons.</p>
		<div class="hidden-sm hidden-xs">
			<a href="<?php echo base_url(); ?>renters/forgot-password" class="btn btn-warning pull-left btn-lg">
				<i class="fa fa-lock"></i> Forgot Password/User name?
			</a>
			<a href="<?php echo base_url(); ?>renters/create-renter-account" class="btn btn-warning pull-right btn-lg">
				<i class="fa fa-user"></i> Create Free Account
			</a>
		</div>
		<div class="visible-sm visible-xs">
			<a href="<?php echo base_url(); ?>renters/forgot-password" class="btn btn-warning btn-md btn-block">
				<i class="fa fa-lock"></i> Forgot Password/User name?
			</a>
			<br>
			<a href="<?php echo base_url(); ?>renters/create-renter-account" class="btn btn-warning btn-md btn-block">
				<i class="fa fa-user"></i> Create Free Account
			</a>
		</div>
	</div>
</div>