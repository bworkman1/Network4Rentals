<div class="panel panel-primary">	
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-envelope"></i> Last Step Verify Your Email</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-8">
				<?php
					if($this->session->flashdata('success')) {
						echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
					} 
					if($this->session->flashdata('error')) {
						echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
					}
				?>
				<h3><i class="fa fa-check"></i> Account Created Successfully</h3>
				<hr>
				<p>Your last step is to verify your email address. We sent you and email to <b><?php echo $this->session->userdata('create_email'); ?></b>. To continue check your email and click the link to confirm your account. If for any reason you cannot find it in your in-box, check your <b><span class="text-danger">*</span> spam/trash</b> folders to see if it made it into those folders.</p>
			</div>
			<div class="col-sm-4">
				<div class="well text-center">
					<h3>Still Having Trouble</h3>
					<hr>
					<p>If you are still having trouble and are sure you checked your spam folder click the link below to resend the verification email. If you still don't receive the email please contact us at the help button below and let us know what email address you use and we will look into the issue on our end.</p>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<a href="<?php echo base_url(); ?>landlord-associations/resend-account-email/" class="btn btn-primary btn-block">Resend Email</a>
						</div>
						<div class="col-sm-6">
							<a href="https://network4rentals.com/help-support/" class="btn btn-primary btn-block">Contact Us</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>