<h2><i class="fa fa-gear text-primary"></i> Authorize.net Payment Settings</h2>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg fa-fw"></i> Error: </b>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><b><i class="fa fa-check-circle fa-fw fa-lg"></i> Success:</b> '.$this->session->flashdata('success').'</div>';
	}

	
?>
<?php 
	if(empty($settings)) { 
		echo '<div class="row">
	<div class="col-sm-9">
		<p>In order to start receiving rent payments online you will need to setup an account through us through Authorize.net which is the payment gateway provider we use. You can go to the sign up page by visiting <a href="https://online.formsrus.com/network4rentals/" target="_block">https://online.formsrus.com/network4rentals/</a>.</p>
	</div>
	<div class="col-sm-3 text-center">
		<h3>Why?</h3>
		<ul style="padding: 0; margin: 0;">
			<li><i class="fa fa-check"></i> Tenant Convince</li>
			<li><i class="fa fa-check"></i> Online Records</li>
			<li><i class="fa fa-check"></i> Fast Payments</li>
		</ul>
		<hr>
		<div class="">
			<a href="https://online.formsrus.com/network4rentals/" class="btn btn-primary btn-block btn-sm" target="_blank"><i class="fa fa-file-pdf-o"></i> Submit Application</a>
		</div>
	</div>
</div>';
	}

?>

<div class="row">
	<div class="col-sm-6">
		<div class="well well-sm">
			<label><span class="text-danger">*</span> <b>Select Which Group Of Payments</b></label>
			<?php echo form_open('landlords/group_payment_selection'); ?>
			<select name="payment_group" class="form-control" onchange="form.submit()">
				<?php 
					echo '<option value="0">My Account</option>';
					foreach($groups as $key => $val) {
						if($val->id == $this->session->userdata('payment_group')) {
							echo '<option value="'.$val->id.'" selected="selected">'.$val->sub_b_name.'</option>';
						} else {
							echo '<option value="'.$val->id.'">'.$val->sub_b_name.'</option>';
						}
					}
				?>
			</select>
			<?php echo form_close(); ?>
		</div>
		<?php echo form_open(); ?>
			<div class="form-group">
				<label> API Login id:</label>
				<input type="text" class="form-control" name="net_api" value="<?php echo $settings['net_api']; ?>">
			</div>
			<div class="form-group">
				<label> Transaction key:</label>
				<input type="text" class="form-control" name="net_key" value="<?php echo $settings['net_key']; ?>">
			</div>
			<div class="form-group">
				<label>Silent Post Url:</label>
				<input type="text" class="form-control" disabled value="<?php echo base_url().'landlords/silent-post-url/'.$silent_link; ?>">
			</div>
			<div class="form-group">
				<label>Allow On-line Payments:</label>
				<div class="row">
					<div class="col-sm-5">
						<select name="allow_payments" class="form-control" required>
							<?php
								$options = array('y'=>'Yes','n'=>'No');
								foreach($options as $key => $val) {
									if($settings['allow_payments'] == $key) {
										echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<h4>Payment Types:</h4>
			<div class="well well-sm">
			
				<div class="row">
					<div class="col-sm-6">
						<label>
							<?php
								
								if($settings['accept_echeck'] == 'y') {
									echo '<input type="checkbox" name="checks" value="y" checked>';
								} else {
									echo '<input type="checkbox" name="checks" value="y">';
								}
							?>
							Accept E-Checks:
						</label>
					</div>
					
				</div>
			</div>
			<br>
			<button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save"></i> Save Settings</button>
		<?php echo form_close(); ?>
		
		<hr>
		
		<?php
			if($user_settings['default_partial_payments'] == 'n') {
				$check2 = 'checked';
			} else {
				$check1 = 'checked';
			}
			if($user_settings['default_min_payment'] == '') {
				$user_settings['default_min_payment'] = '0.00';
			}
			if($user_settings['default_auto_pay_discount'] == '') {
				$user_settings['default_auto_pay_discount'] = '0.00';
			}
			if($user_settings['online_payment_discount'] == '') {
				$user_settings['online_payment_discount'] = '0.00';
			}
			
			
		?>
		
		<div class="well">
			<h3 class="text-primary">Default Tenant Payment Settings</h3>
			<p>These settings will automatically apply to tenant when you varify them so you don't have to set these values everytime.</p>
			<hr>
			<?php echo form_open('landlords/save-default-payment-settings'); ?>
				<div class="row">
					<div class="col-md-6">
						<label>Allow Partial Payments:</label>
						<div class="form-group">
							<label class="radio-inline"><input type="radio" value="y" name="default_partial_payments" <?php echo $check1; ?>>Yes</label>
							<label class="radio-inline"><input type="radio" value="n" name="default_partial_payments" <?php echo $check2; ?>>No</label>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Min Payment Amount:</label>
							<input type="text" class="form-control money" name="default_min_payment" value="<?php echo $user_settings['default_min_payment']; ?>">
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Auto Payment Discount:</label>
							<input type="text" class="form-control money" name="default_auto_pay_discount" value="<?php echo $user_settings['default_auto_pay_discount']; ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Online Payment Discount:</label>
							<input type="text" class="form-control money" name="online_payment_discount" value="<?php echo $user_settings['online_payment_discount']; ?>">
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
			</form>
		</div>
	</div>
	<div class="col-sm-6">
	<a href="http://network4rentals.com/network/landlords/online-rent-payment-setup/" class="btn btn-primary btn-sm">Sign Up For An Account</a>
	<br>
	<br>
		<div class="panel-group" id="accordion">
		
			<!-- <div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
						E-Check Sign Up <span class="pull-right"><b>Step 1</b></a>
					</h4>
				</div> 
				
				<div id="collapseFour" class="panel-collapse collapse in">
					<div class="panel-body">
						<div align="center" class="embed-responsive embed-responsive-16by9">
							<video  width="300" height="200" controls>
							<source src="<?php echo base_url('assets/themes/default/videos/Authorize-Signup-N4R-step1.mp4'); ?>" type="video/ogg" />
							<source src="<?php echo base_url('assets/themes/default/videos/Authorize-Signup-N4R-step1.mp4'); ?>" type="video/mp4" />
								Your browser does not support the <video> element.
							</video>
						</div>
					</div>
				</div>
			</div> -->

		 <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive">
					Setting Authorize
				</a>
			  </h4>
			</div>
			<div id="collapseFive" class="panel-collapse in collapse">
			  <div class="panel-body">
				<p><b>To obtain the API Login ID and Transaction Key:</b></p>
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<video  width="300" height="200" controls>
					   <source src="<?php echo base_url('assets/themes/default/videos/Authorize-Setup-N4R-step2.mp4'); ?>" type="video/ogg" />
					   <source src="<?php echo base_url('assets/themes/default/videos/Authorize-Setup-N4R-step2.mp4'); ?>" type="video/mp4" />
					   Your browser does not support the <video> element.
					</video>
				</div>
			  </div>
			</div>
		  </div>
		  
		  
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
					Where Do I Find My API Login id?
				</a>
			  </h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse">
			  <div class="panel-body">
				<p><b>To obtain the API Login ID and Transaction Key:</b></p>
				<ol>
					<li>Log into your Merchant Interface On Authorize.nets Website</li>
					<li>Click Account from the main toolbar.</li>
					<li>Click Settings in the main left side menu.</li>
					<li>Click API Login ID and Transaction Key in the Security Settings section.</li>
					<li>Under Create New Transaction Key, enter your Secret Answer.</li>
					<li>Note: When obtaining a new Transaction Key, you may choose to disable the old Transaction Key by clicking the box titled, Disable Old Transaction Key(s). You may want to do this if you suspect your previous Transaction Key is being used fraudulently.</li>
					<li>Click Submit.</li>
			  </div>
			</div>
		  </div>
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				  Where Do I Find My Transaction Key?
				</a>
			  </h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse">
			  <div class="panel-body">
				<p>Your API Login ID and Transaction Key are unique pieces of information specifically associated with your payment gateway account. However, the API Login ID and Transaction Key are NOT used for logging into the Merchant Interface. These two values are only required when setting up an Internet connection between your e-commerce Web site and the payment gateway. They are used by the payment gateway to authenticate that you are authorized to submit Web site transactions. Transactions that cannot be authenticated by the payment gateway using these values are rejected.</p>
				<p><b>To create an API Login ID or Transaction Key for the first time:</b></p>
				Step 1: Type in your Secret Answer. You should have configured a Secret Question and Secret Answer during account activation.</p>
				<p>Step 2: Click Submit to continue or click Cancel to cancel the action. The API Login ID and Transaction Key generated for your payment gateway account appear.</p>
				<p>Once you have initially created your API Login ID, you may not change it in the Merchant Interface. To change your API Login ID, please contact Customer Support.</p>
				<p>IMPORTANT: The API Login ID is different than your user login ID. Your user login ID allows you to log into your Merchant Interface user account. The API Login ID grants a merchant access to submit transactions to the payment gateway.</p>
				You may obtain a new, unique Transaction Key on this page as often as needed.</p>
				<p>To obtain a new transaction key:</p>
				<p>Step 1: Enter your Secret Answer (the answer to your Secret Question configured at account setup).</p>
				<p>Step 2: To disable the old transaction key, click the check box labeled  Disable Old Transaction Key.</p>
				<p>Note: If the Disable Old Transaction Key check box is not selected, the old transaction key will automatically expire in 24 hours.</p>
				<p>Step 3: Click Submit to continue or click Cancel to cancel the action. Your new transaction key is displayed.</p>
			  </div>
			</div>
		  </div>
		  <div class="panel panel-default">
			<div class="panel-heading">
			  <h4 class="panel-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
				  The Silent Post URL <span class="label label-danger pull-right">Important</span>
				</a>
			  </h4>
			</div>
			<div id="collapseThree" class="panel-collapse collapse">
			  <div class="panel-body">
				<p>The Silent Post URL function is similar to the carbon copy (CC) function of an email message and is needed for your account to be automatically updated with the status of payments through your authorize account.</p>
				
				<p>To add the silent post feature to your account you will need to login to your Authorize.net account. Once logged in select "Account" in the top right menu. Now you will see your settings page and under transaction format settings there will be a link labelled "Silent Post Url". Copy the silent post url from the box labelled "Silent Post Url" in this page and paste it in the URL box in your authorize.ent account and submit it.</p>
				
				<p>Now when a tenant submits a payment to your account, your account will automatically be updated with the payment status of all payments through your account.</p>
			
			  </div>
			</div>
		  </div>
		</div>
	</div>
</div>
