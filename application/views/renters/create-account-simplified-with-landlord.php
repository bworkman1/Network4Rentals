<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
		<a href="https://network4rentals.com/"><img src="https://network4rentals.com/wp-content/themes/Network4Rentals.new/img/Network-4-Rentals-Property-Management-Software-logo.png" alt="Network 4 Rentals | Create Renters Account" class="img-responsive" width="455" height="115"></a>
		<br>
		
		<?php
			$error = $this->session->flashdata('error');
			if(!empty($error)) {
				echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-fw fa-lg"></i> Error:</b> '.$error.'</div>';
			}
			
			if(!empty($landlord)) {
				if(!empty($landlord->image)) {
					if(file_exists('public-images/'.$landlord->image)) {
						$img_src = base_url('public-images/'.$landlord->image);
					} else {
						$img_src = base_url($landlord->image);
					}
					$img = '<img src="'.$img_src.'" class="img-cirlce pull-left" height="40" width="40" style="margin-right: 10px">';
				} else {
					$img = '<img src="'.base_url('assets/themes/default/images/n4r-icon.png').'" class="img-cirlce pull-left" height="40" width="40" style="margin-right: 10px">';
				}
				
				if(empty($landlord->bName)) {
					$name = $landlord->name;
				} else {
					$name = $landlord->bName;
				}
				
				echo '<div class="white-box">';
					echo '<legend>You are <b class="text-warning">Linking</b> to...</legend>';
					echo $img;
					echo '<h4 style="margin: 7px 0 0 0;"><b>'.html_escape($name).'</b></h4>';
					echo '<p>'.ucwords(html_escape($landlord->city)).' '.ucwords(html_escape($landlord->state)).'</p>';
					
					if(!empty($subs)) {
						echo '<div style="margin-top: 15px" class="list-group">';
							echo '<div class="list-group-item" style="padding-bottom: 5px"><label><input type="radio" selected name="group_id" value=""> '.html_escape($name).'</label></div>';
							foreach($subs as $row) {
								echo '<div class="list-group-item" style="padding-bottom: 5px"><label><input type="radio" name="group_id" value="'.html_escape($row->id).'"> '.html_escape($row->sub_b_name).'</label></div>';
							}
						echo '</div>';
					}
					
					echo '<div class="text-right"><a href="http://n4r.rentals" class="text-warning"><small>Not my landlord</small></a></div>';
				echo '</div>';
			}
		?>
		

		<form action="#" method="post" id="signUp" class="form" role="form">
			<div class="white-box">
				<legend>Create <b class="text-warning">Renter</b> Account</legend>
				
				
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
			</div>
				
			<div class="white-box">
				<legend>Rental <b class="text-warning">Details</b></legend>
				<div class="form-group">
					<input class="form-control" name="address" placeholder="Street Address" type="text" maxlength="30" required />
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<input class="form-control" name="city" placeholder="City" type="text" maxlength="30" required />
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<select class="form-control" name="state" required>
								<option>Select State..</option>
							</select>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-8">
						<div class="form-group">
							<input class="form-control" name="zip" placeholder="Zip Code" type="text" maxlength="5" required />
						</div>
					</div>
				</div>

					
						
				<div class="checkbox">
					<label>
						<input type="checkbox" id="textMsg"> Confirm account by text message?
					</label>
				</div>
				
				<div id="phone"></div>
				
				<input type="hidden" name="landlord_id" value="<?php echo $landlord->id; ?>">
	
				
				<p class="text-center"><small>By clicking Create Account, I agree to the <a href="https://network4rentals.com/terms-of-service/" target="_blank">Terms of Service</a> and <a href="https://network4rentals.com/privacy-policy/" target="_blank">Privacy Policy</a>.</small></p>
				<div id="form-feedback"></div>
				<button class="btn btn-lg btn-warning btn-block" id="createAccountBtn" type="submit">
					Create Account</button>
					
			
			</div>
		</form>
		
		<div class="text-center">
			<p>Already have an account?</p>
			<h4><a href="https://network4rentals.com/network/renters/login" class="text-warning">Sign In</a></h4>
		</div>
	</div>
</div>
<br><br>
<footer></footer>
