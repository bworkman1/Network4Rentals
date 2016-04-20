<div class="contents">

	<div class="row">
		<div class="col-sm-9">
		
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title">Enter Your Information Below To Create Your Account</h3>
				</div>
				<div class="panel-body">
					<span class="fa text-danger fa-asterisk" aria-hidden="true"></span> Indicates a required field
					<hr>
					<?php
						if(validation_errors() != '') 
						{
							echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
						} 
						if(!empty($error)) {
							echo '<div class="alert alert-danger"><h4>Error:</h4>'.$error.'</div>';
						}
					?>
					<?php echo form_open('landlord-associations/create-account', array('id'=>'createAccount')); ?>
						<fieldset>
							<h3 class="text-center"><i class="fa fa-user text-primary"></i> User Details</h3>
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="emails">Email:</label>
								<div class="col-lg-7">
									<input type="text" value="<?php echo set_value('email'); ?>" class="form-control" name="email" id="emails" maxlength="60" required>
									<span class="fa email text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="email-error" class="sr-only"></span>
								</div>
							</div>
							
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="password1">Password:</label>
								<div class="col-lg-7">
									<input type="password" value="<?php echo set_value('password2'); ?>" class="form-control" id="password1" name="password" maxlength="20" placeholder="6 characters or more" required>
									<span class="fa password1 text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="password1-error" class="sr-only"></span>
								</div>
							</div>
							
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="password2">Confirm Password:</label>
								<div class="col-lg-7">
									<input type="password" value="<?php echo set_value('password2'); ?>" class="form-control" id="password2" placeholder="6 characters or more" name="password2" maxlength="20" required>
									<span class="fa password2 text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="password2-error" class="sr-only"></span>
								</div>
							</div>	
				
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="referrer">Heard About Us:</label>
								<div class="col-lg-5">
									<select id="referrer" class="form-control" name="referrer">
										<option>Online</option>
										<option>Event or Booth</option>
										<option>Friends</option>
										<option>Family</option>
										<option>Online Search</option>
										<option>Literature (handouts, etc.)</option>
										<option>Advertisement</option>
										<option>Facebook</option>
										<option>Google+</option>
										<option>Twitter</option>
										<option>Linkedin</option>
										<option>Landlord Request</option>
										<option>Other</option>
									</select>
									<span class="fa referrer text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="referrer-error" class="sr-only"></span>
								</div>
							</div>
							<hr>
							<div class="alert alert-info text-center">
								Only $99.99 A Year! Billing is yearly! Every year on <?php echo date('m-d'); ?> you will be billed $99.99
							</div>
							<h3 class="text-center"><i class="fa fa-credit-card text-primary"></i> Payment Details</h3>
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="cc_name">Name On Card:</label>
								<div class="col-lg-7">
									<input type="text" value="<?php echo set_value('cc_name'); ?>" name="cc_name" class="form-control removeRequired" id="cc_name" maxlength="50" required>
									<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="cc_name-error" class="sr-only"></span>
								</div>
							</div>
							<div class="form-group has-feedback row">
								<label class="col-lg-3 control-label" for="cc_number">Card Number:</label>
								<div class="col-lg-7">
									<input type="text" value="<?php echo set_value('cc_number'); ?>" name="cc_number" class="form-control c_card removeRequired" id="cc_number" maxlength="20" required>
									<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="cc_number-error" class="sr-only"></span>
								</div>
							</div>
							
							<div class="form-group has-feedback row">
								<label class="col-md-3 control-label" for="exp_month">Expires:</label><br class="hidden-md hidden-lg">
								<div class="col-md-3 col-xs-5">
									<select name="exp_month" class="form-control removeRequired" id="exp_month" required>
										<option value="">Month</option>
										<?php
											$months = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
											for($i=0;$i<12;$i++) {
												echo '<option value="'.sprintf("%02d", ($i+1)).'">'.sprintf("%02d", ($i+1)).' - ('.$months[$i].')</option>';
											}
										?>
									</select>	
									<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="exp_month-error" class="sr-only"></span>
								</div>
								<div class="col-md-1 col-xs-1 text-center">
									/
								</div>
								<div class="col-md-3 col-xs-5">
									<select name="exp_yy" class="form-control removeRequired" id="exp_yy" required>
										<option value="">Year</option>
										<?php
											$baseYear = date('Y');
											for($i=0;$i<8;$i++) {
												echo '<option>'.($baseYear+$i).'</option>';
											}
										?>
									</select>	
									<span id="exp_yy-error" class="sr-only"></span>
								</div>
							</div>
							<div class="form-group has-feedback row">
								<label class="col-md-3 control-label" for="cv_code">CV Code:</label>
								<div class="col-sm-3">
									<input type="text" value="<?php echo set_value('cv_code'); ?>" name="cv_code" class="form-control removeRequired" id="cv_code" maxlength="4" required>
									<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="cv_code-error" class="sr-only"></span>
								</div>
								<div class="col-sm-3">
									<div class="form-group has-feedback row">
										<input type="text" value="<?php echo set_value('coupon'); ?>" name="coupon" class="form-control" id="coupon" maxlength="10" placeholder="Coupon Code">
										
									</div>
								</div>
								<div class="col-sm-3">
									<span id="coupon-error"></span>
								</div>
							</div>
							<div id="couponFeedback"></div>
							<hr>
							<h3 class="text-center"><i class="fa fa-file text-primary"></i> Terms Of Service</h3>
							<p class="text-center">View our <a href="#" target="_blank">terms of service</a></p>
							<div class="form-group has-feedback row">
								<label class="col-md-4 col-md-offset-3 control-label" for="terms">I agree To The Terms Of Service:</label>
								<div class="col-md-1">
									<input type="checkbox" id="terms" name="terms" value="y" required>
									<span id="terms-error" class="sr-only"></span>
								</div>
							</div>
							<br>
							<div class="text-center">
								<div class="col-md-6 col-md-offset-3">
									<button type="submit" id="submit-account" class="btn btn-primary">Create Account</button>
								</div>
							</div>
					
						</fieldset>
					</form>
				</div>
			</div>			
		</div>
		<div class="col-sm-3">
			<div class="box text-left">
				<h3>Tips</h3>
				<ul>
					<li><span class="text-danger">*</span> Username Must Be 6 Characters Long And Unique</li>
					<li><span class="text-danger">*</span> Password Must Be 6 Characters Long</li>
					<li><span class="text-danger">*</span> Must use your first and last name</li>
					<li><span class="text-danger">*</span> Service areas are the zip codes you work in</li>
				</ul>
			</div>
			<div class="box text-left">
				<h3>Why Create An Account?</h3>
				<ul>
					<li><span class="text-danger">*</span> Raise awareness of your organization, and potentially increase membership</li>
					<li><span class="text-danger">*</span> Connect to landlords throughout your area and well beyond. Many investors own property in areas that they don't reside in, and may otherwise never hear of your organization.</li>
					<li><span class="text-danger">*</span> Allow your members to proudly show others online that they are a responsible member of your organization.</li>
					<li><span class="text-danger">*</span> User friendly event calendar that can be updated from any computer or mobile device.</li>
					<li><span class="text-danger">*</span> Send out and archive group messages or alerts as easy as sending a text.</li>
					<li><span class="text-danger">*</span> Manage newsletters, and implement email marketing campaigns (*Aweber)</li>
					<li><span class="text-danger">*</span> Promote your approved service providers and increase the value of their affiliation with your group.</li>
					<li><span class="text-danger">*</span> Become part of a one-stop communication hub for the entire home rental industry. </li>
				</ul>
			</div>
		</div>
	</div>
	
</div>
