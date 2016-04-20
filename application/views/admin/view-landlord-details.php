<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            View <?php echo ucwords($this->uri->segment(3)); ?><small> Account</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-user"></i> <?php echo ucwords($this->uri->segment(3)).' / '.$user_details->name; ?>
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#user" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-user"></i> User Info</a></li>
    <li role="presentation"><a href="#rental-properties" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-home"></i> Rental Properties</a></li>
    <li role="presentation"><a href="#current-tenants" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-users"></i> Current Tenants</a></li>
    <li role="presentation"><a href="#requests" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-wrench"></i> Service Requests</a></li>
</ul>
<!-- INPUTS -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="user">
		<br>
		<fieldset>
            <legend><i class="fa fa-home"></i> User Info</legend>
			<div class="row">
				<div class="col-md-6">
					<p><b>Name:</b> <?php echo $user_details->name; ?></p>
					<p><b>Email:</b> <?php echo $user_details->email; ?></p>
					<p><b>Forwarding Email:</b> <?php echo $user_details->forwarding_email; ?></p>
					<?php 
						if(!empty($user_details->phone)) {
							echo '<p><b>Phone:</b> ('.substr($user_details->phone, 0, 3).') '.substr($user_details->phone, 3, 3).'-'.substr($user_details->phone,6).'</p>';
						}
					?>
					<?php 
						if(!empty($user_details->cell_phone)) {
							echo '<p><b>Cell:</b> ('.substr($user_details->cell_phone, 0, 3).') '.substr($user_details->cell_phone, 3, 3).'-'.substr($user_details->cell_phone,6).'</p>';
						}
					?>
					<?php 
						if(!empty($user_details->alt_phone)) {
							echo '<p><b>Alt Phone:</b> <?php echo ('.substr($user_details->alt_phone, 0, 3).') '.substr($user_details->alt_phone, 3, 3).'-'.substr($user_details->alt_phone,6).'</p>';
						}
					?>
					<p><b>Created: </b> <?php echo date('m-d-Y', strtotime($user_details->sign_up)); ?></p>
					<p><b>Source: </b> <?php if(empty($user_details->hear)) {echo 'NA';} else {echo $user_details->hear;} ?></p>
				</div>
				<div class="col-md-6">
					<p><b>Browser Info: </b><?php echo $user_details->browser_info; ?></p>
					<p><b>Accept SMS: </b> <?php if($user_details->sms_msgs == 'y') {echo '<span class="label label-success">Yes</span>';} else {echo '<span class="label label-danger">No</span>';} ?></p>
					<p><b>Accepted Terms: </b> <?php if($user_details->terms == 'n') {echo '<span class="label label-danger">No</span>';} else {echo '<span class="label label-success">Yes</span>';} ?></p>
					<p><b>Confirmed Email: </b> <?php if($user_details->confirmed == 'y') {echo '<span class="label label-success">Yes</span>';} else {echo '<span class="label label-danger">No</span>';} ?></p>
					<p><b>Text Message Code: </b> <?php if(empty($user_details->text_msg_code)) {echo 'NA';} else {echo $user_details->text_msg_code;} ?></p>
					<p><b>Received Rent Reminder: </b> <?php if($user_details->received_rent_reminder == 'y') {echo '<span class="label label-success">Yes</span>';} else {echo '<span class="label label-danger">No</span>';} ?></p>
				</div>
			</div>
		</fieldset>
	</div>
	<div role="tabpanel" class="tab-pane" id="rental-properties">
		<br>
		<fieldset>
		
            <legend><i class="fa fa-home"></i> Rental Properties</legend>
			<?php 
				if(!empty($user_properties)) {
					echo $user_properties; 
				} else {
					echo '<div class="alert alert-info">No Tenants found for this user</div>';
				}
			?>
			
		</fieldset>
	</div>
	<div role="tabpanel" class="tab-pane" id="current-tenants">
		<br>
		<fieldset>
            <legend><i class="fa fa-users"></i> Current Tenants</legend>
			<?php 
				if(!empty($tenants)) {
					echo $tenants; 
				} else {
					echo '<div class="alert alert-info">No Tenants found for this user</div>';
				}
			?>
		</fieldset>
	</div>
	<div role="tabpanel" class="tab-pane" id="requests">
		<br>
		<fieldset>
            <legend><i class="fa fa-wrench"></i> Service Requests</legend>
			<?php 
				if(!empty($service_requests)) {
					echo $service_requests; 
				} else {
					echo '<div class="alert alert-info">No Service Requests found for this user</div>';
				}
			?>
		</fieldset>
	</div>
</div>

<div class="big-spacing hidden-xs hidden-sm"></div>