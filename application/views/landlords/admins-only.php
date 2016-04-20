<div id="adminsOnly">
	<div class="row">
		<div class="col-sm-9">
			<h3><i class="fa fa-lock text-primary"></i> Admins Only</h3>
			
		</div>
		<div class="col-sm-3">
			<a class="btn btn-primary btn-block btn-sm" href="<?php echo base_url(); ?>landlords/send_admin_test_email"><i class="fa fa-envelope"></i> Test Emails</a>
		</div>
	</div>
	<hr>
	<?php
		if($this->session->flashdata('error')) {
			echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
		}
		if($this->session->flashdata('success')) {
			echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
		}
	?>
	<div class="row">
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block showTenantsInfo">View Tenant Sign Ups</button>
		</div>
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block showLandlordsInfo">View Landlords Sign Ups</button>
			
		</div>
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block showTenantsZipBtn">View Tenants By Zip</button>
		</div>
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block showLandlordsZipBtn">View Landlords By Zip</button>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block rogue-tenants">Inactive Tenants</button>
		</div>
		<div class="col-sm-3">
			<button class="btn btn-primary btn-sm btn-block rogue-landlords">Inactive Landlords</button>
		</div>
	</div>
	<hr>
	<div class="well tenantsInfoBox">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center">There Are <b><?php echo $total_tenants; ?></b> Tenants Registered</h4>
				<?php
					foreach($tenants_sign_ups as $key => $val) {
						echo '<div class="col-sm-2 text-center">';
							echo '<a data-toggle="modal" data-target="#myModal"><div class="box renters-info infobox" data-date="'.date('Y-m-d', strtotime($val->Created)).'" data-type="tenants-date">';
								echo '<span class="sign-up-date">'.date('m-d-Y', strtotime($val->Created)).'</span><br>';
								echo '<h3>'.$val->counter.'</h3>';
							echo '</div></a>';
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>
	
	<div class="well hideInfo landlordsInfoBox">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center">There Are <b><?php echo $total_landlords; ?></b> Landlords Registered</h4>
				<?php
					foreach($landlords_sign_ups as $key => $val) {
						echo '<div class="col-sm-2 text-center">';
							echo '<a data-toggle="modal" data-target="#myModal"><div class="box infobox landlords-info" data-date="'.date('Y-m-d', strtotime($val->Created)).'" data-type="landlords-date">';
								echo '<span class="sign-up-date">'.date('m-d-Y', strtotime($val->Created)).'</span><br>';
								echo '<h3>'.$val->counter.'</h3>';
							echo '</div></a>';
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>	
	<div class="well hideInfo showTenantsZip">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center">Tenants Showing By Zip Code</h4>
				<?php
					foreach($tenants_by_zip as $key => $val) {
						echo '<div class="col-sm-2 text-center">';
							echo '<div class="box tenants-zip">';
								echo $val->rental_zip.'<br>';
								echo '<h3>'.$val->counter.'</h3>';
								echo '<div class="progress">';
									$progress = round(($val->counter/200)*100);
									echo '<div class="progress-bar" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">';
										echo $progress.'%';
									echo '</div>';
								echo '</div>';
								echo '<small>Goal: 200</small>';
							echo '</div>';
						echo '</div>';
					}
				?>
			</div>
		</div>
	</div>
	<div class="well hideInfo showLandlordsZip">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center">Landlords Showing By Zip Code</h4>
				<?php
					foreach($landlords_by_zip as $key => $val) {
						if(!empty($val->zip)) {
							echo '<div class="col-sm-2 text-center">';
								echo '<div class="box landlords-zip">';
									echo $val->zip.'<br>';
									echo '<h3>'.$val->counter.'</h3>';
									echo '<div class="progress">';
										$progress = round(($val->counter/30)*100);
										echo '<div class="progress-bar" role="progressbar" aria-valuenow="'.$progress.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$progress.'%;">';
											echo $progress.'%';
										echo '</div>';
									echo '</div>';
									echo '<small>Goal: 30</small>';
								echo '</div>';
							echo '</div>';
						}
					}
				?>
			</div>
		</div>
	</div>
	<div class="well hideInfo showInactiveLandlords">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center"><b><?php echo count($inactive_landlords); ?></b> Inactive Landlords</h4>
				<?php
					foreach($inactive_landlords as $key => $val) {
						if(!empty($val->zip)) {
							echo '<div class="row box">';
								echo '<div class="col-sm-3">';
									echo $val->name;
								echo '</div>';
								echo '<div class="col-sm-1">';
									echo $val->zip;
								echo '</div>';
								echo '<div class="col-sm-6">';
									echo $val->email;
								echo '</div>';
								echo '<div class="col-sm-2">';
									echo '<button class="btn btn-xs sendEmail btn-default" data-toggle="modal" data-target="#sendEmail" data-id="'.$val->id.'" data-name="'.$val->name.'" data-email="'.$val->email.'" data-type="Landlord"><i class="fa fa-envelope"></i> Send Email</button>'; 
								echo '</div>';
							echo '</div>';
						} 
					}
				?>
			</div>
		</div>
	</div> 
	<div class="well hideInfo showInactiveTenants">
		<div class="row">
			<div class="col-sm-12">
				<h4 class="text-center"><?php echo '<b>'.count($inactive_tenants); ?></b> Inactive Tenants</h4>
				<?php
					//print_r($inactive_tenants);
					if(!empty($inactive_tenants)) {
						echo '<div class="row">';
							echo '<div class="col-sm-1">';
								echo '<b>ID:</b>';
							echo '</div>';
							echo '<div class="col-sm-4">';
								echo '<b>Name:</b>';
							echo '</div>';
							echo '<div class="col-sm-4">';
								echo '<b>Email:</b>';
							echo '</div>';
							echo '<div class="col-sm-3">';
								echo '<b>Confirmed:</b>';
							echo '</div>';	
						echo '</div>';
						foreach($inactive_tenants as $key) { 
							foreach($key as $v => $val) {
							echo '<div class="row box">';
								echo '<div class="col-sm-1">';
									echo $val->id;	
								echo '</div>';
								echo '<div class="col-sm-4">';
									echo $val->name;
								echo '</div>';
								echo '<div class="col-sm-4">';
									echo $val->email;
								echo '</div>';
								echo '<div class="col-sm-1">';
									if($val->confirmed == 'y') {
										echo 'Yes';
									} else {
										echo '<span class="text-danger">No</span>';
									}
								echo '</div>';	
								echo '<div class="col-sm-2">';
									echo '<button class="btn btn-xs sendEmail btn-default" data-toggle="modal" data-target="#sendEmail" data-id="'.$val->id.'" data-name="'.$val->name.'" data-email="'.$val->email.'" data-type="Tenant"><i class="fa fa-envelope"></i> Send Email</button>';
								echo '</div>';
							echo '</div>';
							}
						}
					} else {
						echo 'No Inactive Tenants Found';
					}
				?>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Tenants That Signed Up On</h4>
			</div>
			<div class="modal-body">
				<div class="thinking text-center"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="sendEmail" tabindex="-1" role="dialog" aria-labelledby="sendEmail" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('landlords/send-inactive-email'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Send Email To <span class="emailType"></span></h4><hr>
					<p><b>Name: </b><span class="emailName"></span><br><b>Email:</b><span class="emailEmail"></span></p>
				</div>
				<div class="modal-body">
					<label>Subject:</label>
					<input type="text" class="form-control" name="subject">
					<label>Email Body:</label>
					<textarea id="input" name="email_body"></textarea>
					<input type="hidden" name="email_id">
					<input type="hidden" name="email_type">
					<input type="hidden" name="email_email">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Send Email</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>