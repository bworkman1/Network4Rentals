<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
		<a href="https://network4rentals.com/"><img src="https://network4rentals.com/wp-content/themes/Network4Rentals.new/img/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals | Create Renters Account" class="img-responsive" width="455" height="115"></a>
		<br>
		
		<?php
			$error = $this->session->flashdata('error');
			if(!empty($error)) {
				echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-fw fa-lg"></i> Error:</b> '.$error.'</div>';
			}

		?>
		
		<div class="white-box">
			<?php
				$confirmCode = $this->session->userdata('confirm-code');
				$confirmEmail = $this->session->userdata('confirm-email');
				$confirmCell = $this->session->userdata('confirm-cell');
				if(!empty($confirmCode) && !empty($confirmEmail)) {
					if(!empty($confirmCell)) {
						echo '<div id="dynamicContent" data-created="true" data-cell="true">';
					} else {
						echo '<div id="dynamicContent" data-created="true" data-cell="false">';
					}
				} else {
					echo '<div id="dynamicContent">';
				}
			?>
				<legend>Create <b class="text-warning">Renter</b> Account</legend>
				<form action="#" method="post" id="signUp" class="form" role="form">
					
					<div class="row">
						<div class="col-xs-6 col-md-6">
							<div class="form-group">
								<input class="form-control" name="firstname" placeholder="First Name" type="text" required autofocus />
							</div>
						</div>
						<div class="col-xs-6 col-md-6">
							<div class="form-group">
								<input class="form-control" name="lastname" placeholder="Last Name" type="text" required />
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<input class="form-control" name="email" placeholder="Your Email" type="email" value="<?php echo $_GET['email']; ?>"/>
					</div>
					
					<div class="form-group">
						<input class="form-control" name="password" placeholder="Password" type="password" />
					</div>
					
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<input class="form-control" name="zip" placeholder="Zip Code" type="text" maxlength="5" required />
							</div>
						</div>
						<div class="col-md-8">
							<div class="form-group">
								<select name="hear" id="hear" class="form-control" required="">
									<option value="">How did you hear about us?</option>
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
							</div>
						</div>
					</div>
					
					<div class="checkbox">
						<label>
							<input type="checkbox" id="textMsg"> Confirm account by text message?
						</label>
					</div>
					
					<div id="phone"></div>
					
					<p class="text-center"><small>By clicking Create Account, I agree to the <a href="https://network4rentals.com/terms-of-service/" target="_blank">Terms of Service</a> and <a href="https://network4rentals.com/privacy-policy/" target="_blank">Privacy Policy</a>.</small></p>
					<div id="form-feedback"></div>
					<button class="btn btn-lg btn-warning btn-block" id="createAccountBtn" type="submit">
						Create Account</button>
				</form>
			</div>
		</div>
		<div class="text-center">
			<p>Already have an account?</p>
			<h4><a href="https://network4rentals.com/network/renters/login" class="text-warning">Sign In</a></h4>
		</div>
	</div>
</div>
<br><br>
<footer></footer>