<h2><i class="text-primary fa fa-unlock"></i> Login To Your Account:</h2>
<p>To enjoy all the great benefits of Network 4 Rentals please login to your account. If you are not registered yet please create an account on the left side by clicking the create account link in the menu. For help and support visit our help and support page.</p>
<hr>
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

<div class="row">
	<div class="col-sm-5">
		<div class="well">
		<?php 
			echo form_open('landlords/user');
			echo form_fieldset('<i class="fa fa-unlock"></i> Login:');
			echo form_label('Username/Email:');
			$data = array(
					  'name'        => 'username',
					  'id'          => 'username',
					  'maxlength'   => '100',
					  'size'        => '50',
					  'class'       => 'form-control',
					  'required' 	=> ''
					);
			echo form_input($data);
			echo form_label('Password:');
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
				'class' => 'btn btn-primary btn-sm',
				'content' => '<i class="fa fa-unlock"></i> Login'
			);
			echo '<br>';
			echo form_button($data);
			echo form_close();
		?>
		</div>
	</div>
	<div class='col-sm-7'>
		<h3>Need Help?</h3>
		<p>If you forgot your password or need to create an account with us click on on of the following buttons.</p>
		
		<a href="<?php echo base_url(); ?>landlords/forgot-password" class="btn btn-primary pull-left btn-sm">
			<i class="fa fa-lock"></i> Forgot Password?
		</a>
		<a href="<?php echo base_url(); ?>landlords/create-account" class="btn btn-primary pull-right btn-sm">
			<i class="fa fa-user"></i> Create Free Account
		</a>
	</div>
</div>