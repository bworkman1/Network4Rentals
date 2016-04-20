<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Edit <?php echo ucwords($this->uri->segment(3)); ?><small> Account</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-user"></i> <?php echo $user->name; ?>
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#user" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-user"></i> User Info</a></li>
    <li role="presentation"><a href="#history" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-home"></i> Rental History</a></li>
    <li role="presentation"><a href="#transactions" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-money"></i> Transactions</a></li>
    <li role="presentation"><a href="#requests" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-wrench"></i> Service Requests</a></li>
</ul>
<!-- INPUTS -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="user">
		<br>
		<fieldset>
            <legend><i class="fa fa-user"></i> User Info</legend>
			<div class="row">
				<div class="col-md-8">
					<div class="row">
						<div class="col-md-6">
							<label><span class="text-danger">*</span> Name:</label>
							<input type="text" class="form-control input-lg" name="name" value="<?php echo $user->name; ?>" maxlength="40" required />
						</div>
						<div class="col-md-6">
							<label><span class="text-danger">*</span> Email:</label>
							<input type="text" class="form-control input-lg" name="name" value="<?php echo $user->email; ?>" maxlength="40" required />
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<label><span class="text-danger">*</span> Phone:</label>
							<input type="text" class="form-control input-lg username phone" name="user" value="<?php echo $user->phone; ?>" maxlength="16" required />
						</div>
						<div class="col-md-6">
							<label><span class="text-danger">*</span> User Name:</label>
							<input type="text" class="form-control input-lg username" name="user" value="<?php echo $user->user; ?>" maxlength="30" required />
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label>Password:</label>
							<input type="password" class="form-control input-lg" name="pwd" value="" maxlength="16" />
						</div>
						<div class="col-md-6">
							<label>Password:</label>
							<input type="password" class="form-control input-lg" name="pwd2" value="" maxlength="16" />
						</div>
					</div>

					<div class="row">
						<div class="col-md-4">
							<label>Confirmed Email:</label>
							<select class="form-control input-lg" name="confirmed">
								<?php
									$options = array('Yes'=>'y', 'No'=>'n');
									foreach($options as $key => $val) {
										if($val == $user->confirmed) {
											echo '<option selected value="'.$val.'">'.$key.'</otion>';
										} else {
											echo '<option value="'.$val.'">'.$key.'</otion>';
										}
									}
								?>
							</select>
						</div>
						<div class="col-md-4">
							<label>Cell Phone:</label>
							<input type="text" class="form-control input-lg" name="cell_phone" value="<?php echo $user->cell_phone; ?>"" maxlength="16" />
						</div>
						<div class="col-md-4">
							<label>Alt Phone:</label>
							<input type="text" class="form-control input-lg" name="alt_phone" value="<?php echo $user->alt_phone; ?>" maxlength="16" />
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<label><span class="text-danger">*</span> Forwarding Email:</label>
							<input type="text" class="form-control input-lg username phone" name="forwarding_email" value="<?php echo $user->forwarding_email; ?>" maxlength="16" required />
						</div>
						<!-- EMPTY ROWS -->
					</div>
				</div>
				
				<div class="col-md-4">
					<div class="well well-sm">
						<h3 class="text-center">Other Details</h3>
						<p><b>IP Address:</b> <?php echo $user->ip; ?></p>
						<p><b>Login Hash:</b> <?php echo $user->loginHash; ?></p>
						<p><b>Confirmed Account:</b>
							<?php
								if($user->confirmed == 'y') {
									echo '<span class="label pull-right label-success">Yes</span>';
								} else {
									echo '<span class="label pull-right label-danger">No</span>';
								}
							?>
						</p>
						<p><b>Accepted Terms Of Service:</b>
							<?php
								if($user->terms == 'y') {
									echo '<span class="label pull-right label-success">Yes</span>';
								} else {
									echo '<span class="label pull-right label-danger">No</span>';
								}
							?>
						</p>
						<p><b>Accept SMS Messages:</b> 
							<?php
								if($user->sms_msgs == 'y') {
									echo '<span class="label pull-right label-success">Yes</span>';
								} else {
									echo '<span class="label pull-right label-danger">No</span>';
								}
							?>
						</p>
						<p><b>Received Rent Reminder:</b> 
							<?php
								if($user->received_rent_reminder == 'y') {
									echo '<span class="label pull-right label-success">Yes</span>';
								} else {
									echo '<span class="label pull-right label-danger">No</span>';
								}
							?>
						</p>
						<p><b>Created Account:</b> <?php echo date('m-d-Y H:i a', strtotime($user->sign_up)); ?></p>
						<p><b>How They Heard About Us:</b> <?php echo $user->hear; ?></p>
						<p><b>User Agent:</b> <?php echo $user->browser_info; ?></p>
						<p><b>Text Message Code:</b> <?php echo $user->text_msg_code; ?></p>
						
					</div>
				</div>
			</div>
			
			<hr>
			<div class="row">
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-block btn-lg"><i class="fa fa-envelope"></i> Resend Verification Email</a>
				</div>
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-block btn-lg"><i class="fa fa-mobile"></i> Resend Verification SMS</a>
				</div>
				<div class="col-md-3">
					<button class="btn btn-primary btn-block btn-lg"><i class="fa fa-reply"></i> Send User Email</button>
				</div>
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-block btn-lg">Resend Verification</a>
				</div>
			</div>
			
		</fieldset>
	</div> <!-- End User Tab -->
	<div role="tabpanel" class="tab-pane" id="history">
		<br>
		<fieldset>
            <legend><i class="fa fa-home"></i> Rental History</legend>
			<?php 
				if(!empty($history)) {
					echo $history; 
				} else {
					echo '<div class="alert alert-info">No Rental History found for this user</div>';
				}
			?>
		</fieldset>
	</div>
	<div role="tabpanel" class="tab-pane" id="transactions">
		<br>
		<fieldset>
            <legend><i class="fa fa-money"></i> Transactions</legend>
			<?php 
				if(!empty($transactions)) {
					echo $transactions; 
				} else {
					echo '<div class="alert alert-info">No Transactions found for this user</div>';
				}
			?>
		</fieldset>
	</div>
	<div role="tabpanel" class="tab-pane" id="requests">
		<br>
		<fieldset>
            <legend><i class="fa fa-wrench"></i> Service Requests</legend>
			<?php 
				if(!empty($requests)) {
					echo $requests; 
				} else {
					echo '<div class="alert alert-info">No Service Requests found for this user</div>';
				}
			?>
		</fieldset>
	</div>
</div><!-- End Tabbed Pages -->
<br>
<!-- OPTIONS -->


