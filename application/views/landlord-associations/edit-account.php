<div class="row">
	<?php echo form_open('landlord-associations/edit-account', array('class'=>'form-horizontal')); ?>
	<div class="col-sm-9">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> Edit Account</h3>
			</div>
			<div class="panel-body">
				<?php
					if(validation_errors() != '') 
					{
						echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
					} 
					if($this->session->flashdata('error')) {
						echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
					}
					if($this->session->flashdata('success')) {
						echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
					}
				?>
				<h4 class="text-center"><i class="fa fa-gear"></i> Account Settings</h4>
				<hr>
				
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group has-feedback row">
							<label for="name" class="col-sm-4 control-label">Contact Name:</label>
							<div class="col-sm-8">
								<input type="text" name="name" class="form-control" maxlength="100" id="name" value="<?php echo $user->name; ?>">
								<span class="fa text-danger name fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="name-error" class="sr-only"></span>
							</div>
						</div>
						<div class="form-group has-feedback row">
							<label for="title" class="col-sm-4 control-label">Assoc Title:</label>
							<div class="col-sm-8">
								<input type="text" name="title" class="form-control" maxlength="100" id="title"  value="<?php echo $user->title; ?>">
								<span class="fa title text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="title-error" class="sr-only"></span>
							</div>
						</div>
						<div class="form-group has-feedback row">
							<label for="address" class="col-sm-4 control-label">Address:</label>
							<div class="col-sm-8">
								<input type="text" name="address" class="form-control" id="address" maxlength="30" value="<?php echo $user->address; ?>">
								<span class="fa address text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="address-error" class="sr-only"></span>
							</div>
						</div>
						
			
						<div class="form-group has-feedback row">
							<label for="state" class="col-sm-4 control-label">State:</label>
							<div class="col-sm-8">
								<?php
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<select id="state" name="state" class="form-control" required>';
									echo '<option value="">Select One...</option>';
									foreach($states as $key => $val) {
										if($key == $user->state) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
									echo '</select>';
								?>
								<span class="fa text-danger state fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="state-error" class="sr-only"></span>
							</div>
						</div>					

						
					</div><!-- Col close -->
					<div class="col-sm-6">
						<div class="form-group has-feedback row">
							<label for="email" class="col-sm-3 control-label">Email:</label>
							<div class="col-sm-9">
								<input type="email" name="email" class="form-control" id="email" maxlength="50" value="<?php echo $user->email; ?>">
								<span class="fa email text-danger form-control-feedback fa-asterisk" aria-hidden="true"></span>
								<span id="email-error" class="sr-only"></span>
							</div>
						</div>
						
						<div class="form-group has-feedback row">
							<label for="phone" class="col-sm-3 control-label">Phone:</label>
							<div class="col-sm-9">
								<input type="text" name="phone" class="form-control phone" id="phone" value="<?php echo $user->phone; ?>">
								<span class="fa phone text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="phone-error" class="sr-only"></span>
							</div>
						</div>
						
						<div class="form-group has-feedback row">
							<label class="col-sm-3 control-label" for="city">City:</label>
							<div class="col-sm-9">
								<input type="text" value="<?php echo $user->city; ?>" class="form-control" name="city" id="city" maxlength="30" required>
								<span class="fa text-danger city fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="city-error" class="sr-only"></span>
							</div>
						</div>
						<div class="form-group has-feedback row">
							<label for="zip" class="col-sm-3 control-label">Zip:</label>
							<div class="col-sm-6">
								<input type="text" name="zip" class="form-control zip numbersOnly" id="zip" value="<?php echo $user->zip; ?>" maxlength="5">
								<span class="fa zip text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="zip-error" class="sr-only"></span>
							</div>
						</div>
					</div><!-- Col close -->
				</div>
				
				<h4 class="text-center"><i class="fa fa-key"></i> Change Password</h4>
				<hr>
				<div class="row">
					<div class="col-sm-6">						
						<div class="form-group has-feedback row">
							<label class="col-md-4 control-label" for="password">Password:</label>
							<div class="col-md-8">
								<input type="password" value="" class="form-control" id="password" name="password" maxlength="20" placeholder="6 characters or more">
								<span class="fa password text-danger form-control-feedback" aria-hidden="true"></span>
								<span id="password-error" class="sr-only"></span>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group has-feedback row">
							<label class="col-md-3 control-label" for="password1">Password:</label>
							<div class="col-md-9">
								<input type="password" value="" class="form-control" id="password1" name="password1" maxlength="20" placeholder="6 characters or more">
								<span class="fa password1 text-danger form-control-feedback" aria-hidden="true"></span>
								<span id="password1-error" class="sr-only"></span>
							</div>
						</div>
					</div>
				</div>
				
				<h4 class="text-center"><i class="fa fa-map-marker"></i> Service Areas</h4>
				<hr>
				<div class="row">
					<div class="col-sm-6 col-sm-offset-3">
						<input type="text" value="<?php echo $user->service_zips; ?>" class="form-control numbersOnly" id="service_zips" name="service_zips" required>
						<span class="service-zip-feedback fa fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
						<span id="service_zips_error" class="sr-only text-danger"></span>
					</div>
				</div>
				
			</div>
			

		</div>
	</div>
	<div class="col-sm-3">
		<div class="box">
			<h4><b>Other Account Details</b></h4>
			<hr>
			<ul id="uneditable-settings" class="text-left">
				<li>
					<div class="row">
						<div class="col-sm-6 text-right">
							<b>Accepted Terms</b>
						</div>	
						<div class="col-sm-6">
							<?php
								if($user->terms === 'y') {
									echo '<small>Agreed</small>';
								}
							?>
						</div>
					</div>
				</li>
				<li>	
					<div class="row">
						<div class="col-sm-6 text-right">
							<b>Account Created</b>
						</div>	
						<div class="col-sm-6">
							<small><?php echo date('m-d-Y h:i', strtotime($user->created)); ?></small>
						</div>
					</div>					
				</li>
				<li>
					<div class="row">
						<div class="col-sm-6 text-right">
							<b>Email Verified</b>
						</div>	
						<div class="col-sm-6">
							<?php
								if($user->terms === 'y') {
									echo '<small><i class="fa fa-check"></i> Verified</small>';
								}
							?>
						</div>
					</div>	
				</li>
			</ul>
			<button type="submit" class="btn btn-primary btn-block saveaccount"><i class="fa fa-save"></i> Save Account Details</button>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
	