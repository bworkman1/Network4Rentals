<h2><i class="text-primary fa fa-unlock"></i> Forgot Password</h2>
<p>Forgot your password, no problem. Its easy to reset your password just enter your email address below and follow the directions in the email to reset your password.</p>
<?php
	if(validation_errors()) {
		echo '<div class="alert alert-danger">'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger">'.$this->session->flashdata('error').'</div>';
	}
?>
	
<div class="row">
	<div class="col-sm-5">
    	<div class="well">
			<?php
                echo form_open('landlords/forgot_password');
				echo form_fieldset('Enter Your Details Below');
                echo form_label('<i class="fa fa-asterisk text-danger"></i> Email:');
                $data = array(
                                  'name'        => 'email',
                                  'id'          => 'email',
                                  'maxlength'   => '200',
                                  'class'       => 'form-control',
                                  'required' 	=> ''
                                );
                echo form_input($data);
				echo '<div class="spacing15"></div>';
				echo $image;
				
				echo '<div class="spacing15"></div>';
				echo form_label('<i class="fa fa-asterisk text-danger"></i> Type Letters Above:');
                $data = array(
                                  'name'        => 'captcha',
                                  'id'          => 'captcha',
                                  'maxlength'   => '30',
                                  'class'       => 'form-control',
                                  'required' 	=> ''
                                );
                echo form_input($data);
				echo '<div class="spacing15"></div>';
				echo '<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-location-arrow"></i> Reset</button>';
				echo form_close();
			?>
		</div>
	</div>
    <div class="col-sm-7">
    	<h4>Still Need Help?</h4>
    	<p>If you still having problems and cannot login and have already tried this route check out our faqs for additional help.</p>
		<a href="https://network4rentals.com/faqs/" class="btn btn-primary btn-sm"><i class="fa fa-question"></i> View FAQs</a>
    </div>
</div>