<h2 class="pull-left"><i class="fa fa-gears text-primary"></i> Scheduled Preventive Maintenance</h2>
<a href="<?php echo base_url('landlords/reoccurring-preventive-maintenance'); ?>" style="margin-top: 20px" class="btn btn-primary pull-right"><i class="fa fa-calendar"></i> Calendar</a>
<div class="clearfix"></div>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
?>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bookmark-o"></i> Preventive Maintenance Details</h3>
			</div>
			<div class="panel-body">
				<p class="underline"><b>Address:</b><br>
					<span class="tenant-address-service-requests"><?php echo htmlspecialchars($details['address']).' '.htmlspecialchars($details['city']). ', '.htmlspecialchars($details['state']). ' '.htmlspecialchars($details['zip']); ?></span>
				</p>	
				<p class="underline"><b>Service Type:</b> <?php echo $services_array[htmlspecialchars($details['service_type'])]; ?></p>
				<p class="underline"><b>Permission To Enter:</b>  Call First</p>
				<p class="underline"><b>Scheduled:</b> <?php echo date('jS', strtotime($details['reoccurring_date'])); ?> day every 
				<?php 
					if($details['interval'] == 1) {
						echo 'month';
					} else {
						echo $details['interval'].' Months';
					} 
				?> 
				</p>
				<p class="underline"><b>Status:</b> <?php if($details['complete'] == 'y') { echo 'Completed On '.date('m-d-Y h:i:a', strtotime($details['completed'])+3600);	} else { echo'Incomplete';} ?></p>
				<?php if(!empty($details['cost'])) { echo '<p class="underline"><b>Cost:</b> $'.htmlspecialchars($details['cost']).'</p>'; }?>
				 <?php if(isset($service_requests['who'])) { ?><p class="underline"><b>Viewed:</b> <?php if($details['viewed'] != '0000-00-00 00:00:00') { echo date('m-d-Y h:i a', strtotime($details['viewed'])+3600);	} else { echo 'Has Not Been Opened';} ?></p><?php } ?>
				 <?php if(isset($service_requests['who']) && !empty($details['schedule_phone'])) { ?><p class="underline"><b>Ph# For Scheduling:</b> <a href="tel:<?php echo $details['schedule_phone']; ?>"><?php echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6); ?></a></p><?php } ?>
				 <p class="underline"><b>Description:</b> <?php echo $details['description']; ?></p>
				<?php
					if($this->session->userdata('user_id') == $details['landlord_id']) {
						echo '<p><a href="#" data-target="#editPM" data-toggle="modal">Edit PM</a></p>';
					}
				?>
			</div>
		</div>	
	</div>
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-building-o"></i> Landlord Details</h3>
			</div>
			<div class="panel-body">
					<p class="underline"><b>Business Name:</b> <?php echo htmlspecialchars($details['bName']); ?></p>
					<p class="underline"><b>Phone:</b> <?php echo "(".substr($details['landlord_phone'], 0, 3).") ".substr($details['landlord_phone'], 3, 3)."-".substr($details['landlord_phone'],6); ?></p>
					<p class="underline"><b>Email:</b> <?php echo htmlspecialchars($details['landlord_email']); ?></p>
				
					<p class="underline"><b>Submitted To:</b> <?php echo htmlspecialchars($details['landlord_name']); ?></p>
					<p class="underline"><b>Location:</b> <?php echo htmlspecialchars($details['landlord_city']).', '.htmlspecialchars($details['landlord_state']).' '.htmlspecialchars($details['landlord_zip']); ?></p>
			</div>
		</div>
	</div>
</div>

<?php
	if(!empty($ad_post)) {
		echo '<hr>';
	
		echo '<h4><i class="fa fa-bullhorn text-primary"></i> Sponsored Contractors</h4>';
		shuffle($ad_post);
		foreach($ad_post as $val) {
			echo '<div class="row">';
				echo '<div class="col-sm-4 text-center">';
					echo '<div class="link-contractor">';
						echo '<a href="'.base_url().'landlords/contractor-click/'.$val->id.'/'.$val->url.'" target="_blank">';
						echo '<h4><b>'.htmlspecialchars($val->title).'</b></h4>';
						echo '<p>'.htmlspecialchars($val->description).'</p>';
						if(!empty($val->ad_image)) {
							echo '<img src="'.base_url().'contractor-images/'.$val->ad_image.'" class="img-responsive sponsorLogo" alt="'.$val->bName.'">';
						}
						echo '<p style="background: #158CBA; color: #ffffff; margin-bottom: 0; padding-bottom: 5px;"><b>'.htmlspecialchars($val->bName).'</b> <br>';
						echo '('.substr($val->phone, 0, 3).') '.substr($val->phone, 3, 3).'-'.substr($val->phone,6).'</p>';
						echo '</a>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo '<div class="well"><h4><i class="fa fa-bullhorn text-primary"></i> Advertise Here For Only $4.99 per Month</h4>';
			echo "<div class='row'>";
				echo "<div class='col-sm-8'>";
					echo '<p><i class="fa fa-asterisk text-danger"></i> Only 3 Allowed per Service Type &amp; Area</p>';
					echo '<p><i class="fa fa-asterisk text-danger"></i> Includes Self Branded Website</p>';
					echo '<p><i class="fa fa-asterisk text-danger"></i> Landlords and Property Managers Can Search For Your Company</p>';
				echo '</div>';
				echo '<div class="col-sm-4">';
					echo '<a href="'.base_url('contractor').'contractor" class="btn btn-primary  btn-block" target="_blank">Learn More..</a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
	}
?>

<?php if(!empty($ad_post)) { ?>
<hr>
<?php } ?>
<div class="row">
	<div class="col-sm-8">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-picture-o"></i> Attached Picture</h3>
			</div>
			<div class="panel-body">
				<?php if(!empty($details['attachment'])) { ?>
					<img src="<?php echo base_url().'service-uploads/'.$details['attachment']; ?>" class="img-responsive service-request-image" alt="Service Attachment">
				<?php } else { ?>
					<img src="<?php echo base_url().'service-uploads/image_not_found.jpg'; ?>" class="img-responsive service-request-image" alt="Service Attachment">
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-random"></i> Options</h3>
			</div>
			<div class="panel-body">
				<a href="<?php echo base_url(); ?>landlords/print-service-request/<?php echo $details['id']; ?>" class="btn btn-info btn-block"><i class="fa fa-print"></i> Print Request</a>
				<br>
				<a href="<?php echo base_url(); ?>landlords/print-service-request/<?php echo $details['id']; ?>" class="btn btn-info btn-block"><i class="fa fa-save"></i> Save As PDF</a>
				<br>
				<?php if($details['complete'] == 'n' && $this->session->userdata('user_id') == $details['landlord_id']) { ?>
					<a href="" class="btn btn-info btn-block" data-toggle="modal" data-target="#forward-request"><i class="fa fa-reply"></i> Forward Service Request</a>
					<br>
				<?php } ?>
				<a href="" class="btn btn-info btn-block" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a>

				<br>
				<a href="#" class="btn btn-info btn-block" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> <?php echo $notes; ?> Notes</a>
				<br>
				<?php if($this->session->userdata('user_id') == $details['landlord_id']) { ?>
					<a href="<?php echo base_url('landlords/delete-pm-service/'.$details['id']); ?>" class="btn btn-danger btn-block"><i class="fa fa-times"></i>  Delete</a>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<hr>
<input type="hidden" id="serviceRequest_Id" value="<?php echo $details['id']; ?>">

<?php
	if(!empty($details['items'])) {
		echo '<hr><h3><i class="fa fa-archive text-primary"></i> Items Related To This Request</h3>';
		echo '<div class="row">
				<div class="col-sm-3">
					<b>Item Name:</b>
				</div>
				<div class="col-sm-2">
					<b>Model#:</b>
				</div>
				<div class="col-sm-3">
					<b>Brand:</b>
				</div>
				<div class="col-sm-2">
					<b>Serial#:</b>
				</div>
				<div class="col-sm-2">
					<b>Service Type:</b>
				</div>
			</div>';
		foreach($details['items'] as $val) {
			if(empty($val['modal_num'])) {
				$val['modal_num'] = 'NA';
			}
			if(empty($val['brand'])) {
				$val['brand'] = 'NA';
			}
			if(empty($val['serial'])) {
				$val['serial'] = 'NA';
			}
			echo '<div style="height: 1px; width: 100%; background: #ccc; margin: 3px 0;"></div>
					<div class="row">
						<div class="col-sm-3">
							'.htmlspecialchars($val['desc']).'
						</div>
						<div class="col-sm-2">
							'.htmlspecialchars($val['modal_num']).'
						</div>
						<div class="col-sm-3">
							'.htmlspecialchars($val['brand']).'
						</div>
						<div class="col-sm-2">
							'.htmlspecialchars($val['serial']).'
						</div>
						<div class="col-sm-2">
							'.$services_array[$val['service_type']].'
						</div>
					</div>';
		}
	}
?>

<?php 
	if(!empty($details['incomplete_requests'])) {
		echo '<div class="row">';
			echo '<div class="col-sm-9">';
				echo '<h3><i class="fa fa-folder-open text-primary"></i> Other Incomplete Request At This Address</h3>';
			echo '</div>';
			echo '<div class="col-sm-3 text-right">';
				echo '<br>';
				echo '<i class="fa fa-info-circle text-primary fa-2x toolTips" title="If you are not seeing all your service requests for this property check to make sure you have verified all your tenants. Unverified tenants will not be grouped in with this property."></i>';
			echo '</div>';
		echo '</div>';
		echo '<hr>';
		echo '<div class="row"><b>';
			echo '<div class="col-sm-4">';
				echo 'Submitted';
			echo '</div>';
			echo '<div class="col-sm-5">';
				echo 'Service Type';
			echo '</div>';
			echo '<div class="col-sm-3 text-right">';
				echo 'Options';
			echo '</div>';
		echo '</b></div>';
		foreach($details['incomplete_requests'] as $key => $val) {
			echo '<div class="page-results">';
				echo '<div class="row">';
					echo '<div class="col-sm-4">';
						echo date('m-d-Y h:i a', strtotime($val['submitted'])).' <small>EST</small>';
					echo '</div>';
					echo '<div class="col-sm-5">';
						
						echo $services_array[$val['service_type']];
						
					echo '</div>';
					echo '<div class="col-sm-3 text-right">';
						echo '<a href="'.base_url().'landlords/view-service-request/'.$val['id'].'" class="btn btn-xs btn-primary toolTips" title="View Request"><i class="fa fa-info-circle"></i></a>';
					echo '</div>';
				echo '</div>';
			
			echo '</div>';
			
		} 
	}
?>


<?php if($details['complete'] == 'n') { ?>
<!-- Forward Modal -->
<div class="modal fade" id="forward-request" tabindex="-1" role="dialog" aria-labelledby="forward-request" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php echo form_open('landlords/forward-service-request/'.$details['id']); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-reply text-primary"></i> Forward Service Request</h4>
			</div>
			<div class="modal-body">
				<ul id="suggested-contractors-scored">
				</ul>
				<?php
					if(!empty($ad_post)) {
						shuffle($ad_post);
						echo '<legend><i class="fa fa-wrench text-primary"></i> Contractors In Your Area</legend>';
						echo '<p>Clicking one of the buttons below will add the contractors email address to the email input box above. You can then add a message to the email that will send out to that sponsor. Once you fill in the information needed, click the "Send Request" button at the bottom of this window and the email will be sent out to the sponsoring contractor you selected.</p>';
						foreach($ad_post as $val) {
							echo '<div class="row">';
								echo '<div class="col-xs-9">';
									echo '<button class="btn btn-primary btn-block forward-sponsorship" data-email="'.htmlspecialchars($val->email).'">Forward To '.htmlspecialchars($val->bName).'</button>';
								echo '</div>';
								echo '<div class="col-xs-3">';
									echo '<a href="'.base_url().'landlords/contractor-click/'.$val->id.'/'.$val->url.'" class="btn btn-primary btn-block" target="_blank"><i class="fa fa-info-circle"></i> Learn More</a>';
								echo '</div>';
							echo '</div>';
							echo '<br>';
						}
					}
				?>
				<div id="forwardSponsorEmail"></div>
				<div class="well">
					<h3 id="sendRequest"><i class="fa fa-envelope text-primary"></i> Forward To An Email</h3>
					<p>You can add an email to the box below and we will forward your service request to your contractor or handyman so they can have this service request on hand.</p>
					<label>Instructions To Request:</label>
					<textarea name="note" class="form-control" style="height: 150px"></textarea>
					<label><span class="text-danger">*</span> Forward To Another Email Address:</label>
					<input type="email" name="email" class="form-control forward-email-input" required="required">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send Request</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
<?php } ?>

<!-- Add A Note Modal -->
<div class="modal fade" id="add-note" tabindex="-1" role="dialog" aria-labelledby="add-note" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/add-note-to-request/'.$details['id'].'/maintenance/'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-primary"></i> Add Note To Service Request</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-8">
						<label><span class="text-danger">*</span> Who Can See This Note:</label>
						<select class="form-control" name="visibility" required="required">
							<option value="">Select One...</option>
							<option value="0">Only You</option>
							<option value="2">Only You And Your Admins</option>
							<option value="1">You And Others You Send This Request To</option>
						</select>
					</div>
				</div>
				<label><span class="text-danger">*</span> Add Note:</label>
				<textarea name="note" class="form-control" required="required"></textarea>
				<label>Add Image <small>(Optional)</small>:</label>
				<input type="file" name="img" class="form-control">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary "><i class="fa fa-plus"></i> Add Note</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="editPM" tabindex="-1" role="dialog" aria-labelledby="editPM" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/view-preventive-maintenance/'.$details['id'].'/'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-pencil text-primary"></i> Edit PM</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
							<?php
								if(!empty($switches)) {
									echo '<div class="form-group">';
									echo '<label>Assign to a Manager</label>';
									echo '<select class="form-control input-sm" name="admin" required="required">';
										echo '<option value="0">Add To My Account</option>';
										for($i=0;$i<count($switches);$i++) {
											if($details['group_id'] == $switches[$i]['id']) {
												echo '<option value="'.$switches[$i]['id'].'" selected="selected">'.$switches[$i]['sub_b_name'].'</option>';
											} else {
												echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
											}
										}
									echo '</select>';
									echo '</div>';
								}
							?>
						<div class="form-group">
							<label><span class='text-danger'>*</span> Type Of Service:</label>
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other'); 
								echo "<select name='service_type' id='serviceType' class='form-control' required='required'>";
								echo '<option value="">Choose One...</option>';
								foreach($services_array as $key => $val) {
									if($details['service_type'] == $key) {
										echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo "</select>";
							?>
						</div>
						<div class="form-group">
							<label><span class='text-danger'>*</span> Starting Date:</label>
							<input type="text" class="form-control datepicker" name="reoccurring_date" value="<?php echo date('m/d/Y', strtotime($details['reoccurring_date'])); ?>">
						</div>
						<div class="form-group">
							<label><span class='text-danger'>*</span> How often:</label>
							<select class="form-control" name="interval">
								<?php
									$options = array('1'=>'Monthly', '3'=>'Quarterly', '6'=>'Bi-Yearly', '12'=>'Yearly');
									foreach($options as $key => $val) {
										if($details['reoccurring_date'] == $key) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
									
								?>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><span class='text-danger'>*</span> Description:</label>
							<textarea style='height: 273px' name='description' maxlength="500" class='form-control' required="required"><?php echo $details['description']; ?></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary "><i class="fa fa-save"></i> Save</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>




<!-- View Notes Modal -->
<div class="modal fade" id="view-notes" tabindex="-1" role="dialog" aria-labelledby="view-notes" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-primary"></i> Notes Left On This Property</h4>
			</div>
			<div class="modal-body">
				<?php
					if(!empty($details['notes'])) {
						foreach($details['notes'] as $val) {
					
							echo '<ul id="notes-list">';
								if(!empty($val['contractor_id'])) {
									echo '<li class="contractor">';
									echo '<b><em>'.htmlspecialchars($val['contractor_name']).':</em></b>';
								} else {
									echo '<li class="landlord">';
									echo '<b><em>Me:</em></b>';
								}
									
									echo '<h5 class="pull-right"><b>Sent On: '.date('m-d-Y h:i a', strtotime($val['s_timestamp'])).'</b></h5><div class="clearfix"></div>';
									echo '<p>'.htmlspecialchars($val['note']).'</p>';
									if(!empty($val['contractor_image'])) {
										echo '<i class="fa fa-paperclip"></i> <a target="_blank" href="https://network4rentals.com/network/public-images/'.$val['contractor_image'].'">'.$val['contractor_image'].'</a>';
									}
									
									if(empty($val['contractor_id'])) {
										if($val['visibility'] == 0) {
											echo '<small><b>Visibility:</b> Only Me</small>';
										} else if($val['visibility'] == 1) {
											echo '<small><b>Visibility:</b> Everyone I send this request to</small>';
										} else {
											echo '<small><b>Visibility:</b> Only me and the admins can see this note</small>';
										}
									}
								echo '</li>';
							echo '</ul>';
							
						
							
							
							
						}
					} else {
						echo 'No Notes Have Been Left On This Account';
					}
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


