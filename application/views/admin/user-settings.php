<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            User <small>Settings</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-wrench"></i> Settings
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->

<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$error.'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
	}
?>

<div class="row">
	<div class="col-md-4">
		<?php echo form_open('n4radmin/user-settings'); ?>
			<fieldset>
				<div class="form-group">
					<label class="control-label" for="name">Full Name</label>  
					<input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="" value="<?php echo htmlspecialchars($user->name); ?>">
				</div>

				<div class="form-group">
					<label class="control-label" for="email">Email</label>  
					<input id="email" name="email" type="email" placeholder="" class="form-control input-md" required="" value="<?php echo htmlspecialchars($user->email); ?>">
				</div>

				<div class="form-group">
					<label class="control-label" for="password">Password</label>  
					<input id="password" name="password" type="password" placeholder="Must be at least 7 characters long" class="form-control input-md">
				</div>

				<div class="form-group">
					<label class="control-label" for="password">Confirm Password</label>  
					<input id="password" name="password-confirm" type="password" placeholder="" class="form-control input-md">
				</div>
			</fieldset>
			<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Save</button>
		</form>
	</div>
</div>

<br><br>
<br><br>
