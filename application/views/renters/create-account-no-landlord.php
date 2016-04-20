<?php echo form_open_multipart('renters/create_account_no_landlord', array('id' => 'createAccount')); ?>
<div class="row setup-content step activeStepInfo" id="step-1">
	<div class="col-md-12 text-left">
		<h3 class="pull-left"><i class="fa fa-search text-warning"></i> Create Your Free Account</h3>
		<button class="btn btn-warning pull-right" data-toggle="modal" data-target="#helpvideo">Need Help?</button>
		<div class="clearfix"></div>
			<hr>
			<p>The first thing you will need to do is add your personal details below.</p>
			<?php
				 if(!empty($error)) {
					echo '<div class="alert alert-danger">'.$error.'</div>';
				 }
				?>
			<div class="row">
				<div class="col-lg-12">
					<fieldset>
						<legend><i class="fa fa-user"></i> Create Account:</legend>
					</fieldset>
				</div>
				<div class="col-sm-6">
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Full Name:</label>
						<input type="text" name="fullname" maxlength="40" id="fullname"  class="form-control" value="<?php echo set_value('fullname'); ?>" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Email:</label>
						<input type="text" name="email"  id="email" maxlength="100" class="form-control" value="<?php echo set_value('email'); ?>" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Phone:</label>
						<input type="text" name="phone" value="<?php echo set_value('phone'); ?>" id="phone" maxlength="15" class="form-control phone" required>
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> How Did You Hear About Us?</label>
						<select name="hear" id="hear" class="form-control" required>
							<option value="">Select One...</option>
							<option>Event or Booth</option>
							<option>Friends</option>
							<option>Family</option>
							<option>Online Search</option>
							<option>Literature (handouts, fliers, etc.</option>
							<option>Advertisement</option>
							<option>Facebook</option>
							<option>Google+</option>
							<option>Twitter</option>
							<option>Linkedin</option>
							<option>Landlord/Mgr. Request</option>
							<option>Other</option>
						</select>
						<div class="help-text text-danger"></div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Username:</label>
						<input type="text" name="username" id="username" maxlength="100"  class="checkUsername form-control" required="" placeholder="Must Be At Least 6 Characters" value="<?php echo set_value('username'); ?>">
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Password:</label>
						<input type="text" name="password" value="<?php echo set_value('password'); ?>" id="password" maxlength="100" class="form-control" placeholder="Must Be At Least 6 Characters" required> 
						<div class="help-text text-danger"></div>
					</div>
					<div class="input-group">
						<label><i class="fa fa-asterisk text-danger"></i> Confirm Password:</label>
						
						<input type="text" name="password1" value="<?php echo set_value('password1'); ?>" id="password1" maxlength="100"  class="form-control" placeholder="Must Be At Least 6 Characters" required>
						
						<div class="help-text text-danger"></div>
					</div>
			
					
			
					<div class="row">
						<div class="col-md-8">
							<div class="input-group">
								<label>  
									<i class="fa fa-asterisk text-danger"></i> I Agree To The 
									<a href="https://network4rentals.com/network/renters/terms-of-service" target="_blank">Terms Of Services</a>
								</label>
								<br>
								<input type="checkbox" id="terms" name="terms" value="y" required> Yes
								<div class="help-text text-danger"></div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<br>
			<div class="well">
						<div class="row">
							<div class="col-sm-6">
								<label><i class="fa fa-asterisk text-danger"></i> Receive Text Messages:</label>
								<select class="form-control textMessages" name="sms_msgs" required>
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
							<div class="col-sm-6 textMessagePhoneNumber fade">
								<label><i class="fa fa-asterisk text-danger"></i> Cell Phone Number:</label>
								<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $_POST['cell_phone']; ?>">
							</div>
						</div>
					</div>
			<div id="ajaxError" class="text-danger"></div>
			<div class="text-right">
				<button type="submit" class="btn btn-warning btn-sm"> Submit</button>
			</div>
	</div>
</div>
</form>