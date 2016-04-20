<h4 class="landlordaccount">Final Step to Activate your FREE Landlord Account</h4>
<p class="text-center">Complete activation form below and receive free onboarding services through December 2015!</p>
<div style="height:15px"></div>
<h5>Already Have An Account? <a href="<?php echo base_url(); ?>landlords/login" class="btn btn-primary btn-xs"><i class="fa fa-unlock"></i> Login</a></h5>
<h3>Create Your Free Account</h3>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error'))
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4><p>'.$this->session->flashdata('error').'</p></div>';
	}
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><h4>Error:</h4><p>'.$error.'</p></div>';
	}
	
?>

	<div class="row">
		<?php 
			echo form_open('landlords/create-user-account', array('id'=>'createNewAccount'));
			echo '<div class="col-lg-12">';
			echo form_fieldset(' <i class="fa fa-user"></i> Create Account:');
			echo '</div>';
			echo '<div class="col-md-6">';
			echo '<label>Business Name:</label>';
			echo '<input type="text" name="bName" class="form-control" value="'.$_POST['bName'].'">';
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
				$email = $_GET['email'];
			}
			$data = array(
					  'name'        => 'email',
					  'id'          => 'email',
					  'maxlength'   => '100',
					  'class'       => 'form-control checkLandlordEmail',
					  'required' 	=> '',
					  'value'		=> $email
					);
			echo form_input($data);
			echo '<div class="error-email text-danger"></div>';
			
			echo '<div class="error-username text-danger"></div>';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Password:');			
			$data = array(
					  'name'        => 'password',
					  'id'          => 'password',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control checkPass checker',
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
					  'class'       => 'form-control checkPass2 checker',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'required' 	=> ''
					);
			echo form_password($data);				
			echo '<div class="pwd-error text-danger"></div>';
			echo '</div>';
			echo '<div class="col-md-6">';
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
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Address:');
			if(!empty($_POST['address'])) {
				$address = $_POST['address'];
			} else {
				$address = '';
			}
			$data = array(
					  'name'        => 'address',
					  'id'          => 'address',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value'		=> $address
					);
			echo form_input($data);	
			echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
			if(!empty($_POST['city'])) {
				$city = $_POST['city'];
			} else {
				$city = '';
			}
			$data = array(
					  'name'        => 'city',
					  'id'          => 'city',
					  'maxlength'   => '100',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value'		=> $city
					);
			echo form_input($data);	
			
			echo '<div class="row">';
				echo '<div class="col-sm-6">';
					echo form_label('<i class="fa fa-asterisk text-danger"></i> State:');			
					$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
					echo '<select name="state" class="form-control" required="">';
					echo '<option value="">Select One...</option>';
					foreach($states as $key => $val) {
						if($key == $_POST['state']) {
							echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}
					echo '</select>';
				echo '</div>';
				echo '<div class="col-sm-6">';
			
					echo form_label('<i class="fa fa-asterisk text-danger"></i> Zip:');
					if(!empty($_POST['zip'])) {
						$zip = $_POST['zip'];
					} else {
						$zip = '';
					}
					$data = array(
							  'name'        => 'zip',
							  'id'          => 'zip',
							  'maxlength'   => '5',
							  'minlength' 	=> '5',
							  'class'       => 'form-control numbersOnly',
							  'required' 	=> '',
							  'value'		=> $zip
							);
					echo form_input($data);	
				echo '</div>';
			echo '</div>';
			
			$hear_about = array(
				'Event or Booth',
				'Friends',
				'Family',
				'Online Search',
				'Literature (handouts, fliers, etc.',
				'Advertisement',
				'Facebook',
				'Google+',
				'Twitter',
				'Linkedin',
				'Tenant Request',
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
			echo '<br>';
			
			
			echo '</div>';
			echo '</div>';
			echo '<div class="row">';
			echo '<label class="col-sm-9 text-right" style="padding-top: 5px"><i class="fa fa-asterisk text-danger"></i> How Many Rental Units Do You Have:</label>';
			echo '<div class="col-sm-3">';
			echo '<input type="numsOnly" class="numbersOnly form-control" name="rental_units" value="'.$_POST['rental_units'].'">';
			echo '</div>';
			echo '</div>';
		?>
		<br>
		
		<div class="well">
			<div class="row">
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> Receive account notifications and confirmations via text message:</label>
					<select class="form-control textMessages" name="sms_msgs" required>
						<option value="">Select One</option>
						<?php
							$options = array('n'=>'No', 'y'=>'Yes');
							foreach($options as $key => $val) {
								if($key == $_POST['sms_msgs']) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
						?>
					</select>
				</div>
				<?php
					if($_POST['sms_msgs'] == 'y') {
						echo '<div class="col-sm-6 textMessagePhoneNumber fade in">';
					} else {
						echo '<div class="col-sm-6 textMessagePhoneNumber fade">';
					}
				?>
				
					<label><i class="fa fa-asterisk text-danger"></i> Cell Phone Number:</label><br><br>
					<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $_POST['cell_phone']; ?>">
				</div>
			</div>
		</div>
			
		<label><i class="fa fa-asterisk text-danger"></i> I Agree To The <a href="<?php echo base_url('landlords/terms-of-service'); ?>">Terms Of Services</a>: <input type="checkbox" name="terms" value="y" required="" /> Yes</label>
		<hr>
		<?php
			$data = array(
				'value' => 'true',
				'type' => 'submit',
				'class' => 'btn btn-primary createLandlordAccount',
				'content' => '<i class="fa fa-location-arrow"></i> Create My Account'
			);

			echo '<br>';
			echo form_button($data);
			echo form_close();
		?>
		
