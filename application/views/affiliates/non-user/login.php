<?php
    $success = $this->session->flashdata('success');
    $error = $this->session->flashdata('error');

    $feedback = '';

    if(!empty($success)) {
        $feedback = '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            <b><i class="fa fa-check-circle"></i> Success:</b> '.$success.'
        </div>';
    }

    if(!empty($error)) {
        $feedback = '<div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                <b><i class="fa fa-exclamation-triangle"></i> Error:</b> '.$error.'
            </div>';
    }
?>

<div class="container">
	<div id="login">
		<h3 class="text-center">Affiliates Login</h3>
        <?php echo $feedback; ?>
		<hr>
		<!-- <div class="row omb_socialButtons">
			<div class="col-xs-6">
				<a href="<?php echo base_url('affiliates/auth/session/facebook'); ?>" class="btn btn-lg btn-block btn-facebook">
					<i class="fa fa-facebook"></i>
					<span class="hidden-xs">Facebook</span>
				</a>
			</div>
			<div class="col-xs-6">
				
			</div>
		</div>
		<hr> -->
		
		<?php echo form_open('affiliates/login/submit'); ?>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
				<input type="text" class="form-control" name="username" placeholder="email address">
			</div>
			<span class="help-block"></span>
								
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
				<input  type="password" class="form-control" name="password" placeholder="Password">
			</div>
			<span class="help-block text-danger"></span>
			<br>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
		<?php echo form_close(); ?>
		
		<br>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<a href="<?php echo base_url('affiliates/auth/session/google'); ?>" class="btn btn-lg btn-block btn-google">
					<i class="fa fa-google-plus"></i>
					<span class="hidden-xs">Google+</span>
				</a>
			</div>
			<div class="col-md-6">
				<p class="forgotPass text-right">
					<!-- <a href="<?php echo base_url('affiliates/forgot-password'); ?>">Forgot password?</a> -->
				</p>
			</div>
		</div>

	</div>

</div>