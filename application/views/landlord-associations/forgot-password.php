<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($success)) {
		echo '<div class="alert alert-success">'.$success.'</div>';
	}
	if(!empty($error)) {
		echo '<div class="alert alert-danger">'.$error.'</div>'; 
	}
	
?>
<div class="panel panel-primary">	
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-key"></i> Reset Password</h3>
	</div>
	<div class="panel-body">
		<?php echo form_open("landlord-associations/reset-password/".$this->uri->segment(3)); ?>
			<div class="row">
				<div class="col-md-6">
					<label>Enter New Password:</label>
					<input type="password" name="pwd" class="form-control" required maxlength="20">
				</div>
				<div class="col-md-6">
					<p><br></p>
					<p class="text-danger">Password must be at least 7 characters long</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<label>Repeat New Password:</label>
					<input type="password" name="pwd_confirm" class="form-control" required maxlength="20">
				</div>
			</div>
			<br>
			<input type="hidden" name="hash" value="<?php echo $this->uri->segment(3); ?>" required>
			<button type="submit" name="" class="btn btn-primary">Change Password</button>
		</form>
	</div>
</div>