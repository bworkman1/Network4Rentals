
<div class="blue-background"></div>
<div class="container-fluid">
	<div id="login" class="card">
		<img src="<?php echo base_url('assets/themes/blue-moon/img/logos/Network-4-Rentals-Property-Management-Software-logo-494x136.png'); ?>" class="img-responsive">
		<h3 class="text-center text-primary">Renters Login</h3>
		<?php echo $this->load->get_section('alerts'); ?>
			
		<?php echo form_open('renter/login/submit'); ?>
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-envelope fa-fw"></i></span>
				<input type="text" class="form-control input-lg" name="username" placeholder="Email Address">
			</div>
		
			<div class="input-group">
				<span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
				<input  type="password" class="form-control input-lg" name="password" placeholder="Password">
			</div>
			<span class="help-block text-danger"></span>
			<button class="btn btn-primary btn-lg btn-block" type="submit">Login</button>
			
			<div class="checkbox">
				<label for="remember">
					<input type="checkbox" id="remember" name="remember" value="y">
					Remember Me?
				</label>
			</div>
			<hr>
			<p><a href="<?php echo base_url(); ?>">Forgot password?</a></p>
			<p><a href="<?php echo base_url(); ?>">Sign up for free</a></p>
		<?php echo form_close(); ?>
		<br>
	</div>

</div>
