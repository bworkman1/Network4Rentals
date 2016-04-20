<div class="row">
	<div class="col-sm-10">
		<h2><i class="fa fa-bookmark-o text-success"></i> Service Request Details</h2>
	</div>
	<div class="col-sm-2">
		<br>
		<?php
			$note_count = count($details['notes']);
			if($note_count == '0') {
				$notes = '';
			} else {
				$notes = $note_count;
			}
		?>
		<a href="#" class="btn btn-success btn-xs btn-block" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> <?php echo $notes; ?> Notes </a>
	</div>
</div>
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
<h4><i class="fa fa-wrench text-success"></i> Service Requested</h4>


<div class="row">
	<div class="col-sm-6">
		<p><b>Service Type:</b> <?php echo $services_array[$details['service_type']]; ?></p>
		 <?php if(isset($service_requests['who'])) { ?><p><b>Permission To Enter:</b>  <?php echo ucwords($details['enter_permission']); ?></p> <?php } ?>
		<p><b>Description:</b> <?php echo $details['description']; ?></p>
	</div>
	<div class="col-sm-6">
		<p><b>Submitted:</b> <?php echo date('m-d-Y h:i a', strtotime($details['submitted'])+3600); ?> EST</p>
		<p><b>Status:</b> <?php if($details['complete'] == 'y') { echo 'Completed On '.date('m-d-Y h:i:a', strtotime($details['completed'])+3600);	} else { echo'Incomplete';} ?></p>
		<?php if(!empty($details['cost'])) { echo '<p><b>Cost:</b> $'.number_format($details['cost']).'</p>'; }?>
		 <?php if(isset($service_requests['who'])) { ?><p><b>Viewed:</b> <?php if($details['viewed'] != '0000-00-00 00:00:00') { echo date('m-d-Y h:i a', strtotime($details['viewed'])+3600);	} else { echo 'Has Not Been Opened';} ?></p><?php } ?>
		 <?php if(isset($service_requests['who'])) { ?><p><b>Ph# For Scheduling:</b> <?php echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6); ?></p><?php } ?>
	</div>
</div>
<hr>
<div class="row">
	<?php if($details['who'] == '1') { ?>
	<div class="col-sm-6">
		<h4><i class="fa fa-user text-success"></i> Tenant Info</h4>
		<p><b>Renters Name:</b> <?php echo $details['tenant_name']; ?></p>
		<p><b>Renters Phone:</b> <?php echo "(".substr($details['tenant_phone'], 0, 3).") ".substr($details['tenant_phone'], 3, 3)."-".substr($details['tenant_phone'],6); ?></p>
		<p><b>Call For Scheduling:</b> <?php echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6); ?></p>
		<p><b>Renters Email:</b> <?php echo $details['tenant_email']; ?></p>
	</div>
	<?php } ?>
	<div class="col-sm-6">
		<h4><i class="fa fa-home text-success"></i> Rental Info</h4>
		<p><b>Address:</b><br>
			<span class="tenant-address-service-requests"><?php echo $details['address'].' '.$details['city']. ', '.$details['state']. ' '.$details['zip']; ?></span>
		</p>	
		<?php if($details['who'] == '0') { echo '<p><b><span class="text-danger">*</span> Submitted By Landlord - Not The Tenant</b><br></p>'; }?>		
	</div>
</div>
<hr>
<div class="row">
	<div class="col-sm-8">
		<h4><i class="fa fa-picture-o text-success"></i> Attached Image:</h4>
		<?php if(!empty($details['attachment'])) { ?>
			<img src="<?php echo base_url().'service-uploads/'.$details['attachment']; ?>" class="img-responsive service-request-image" alt="Service Attachment">
		<?php } else { ?>
			<img src="<?php echo base_url().'service-uploads/image_not_found.jpg'; ?>" class="img-responsive service-request-image" alt="Service Attachment">
		<?php } ?>
	</div>
	<div class="col-sm-4">
		<h4><i class="fa fa-random text-success"></i> Options:</h4>
		<a href="<?php echo base_url(); ?>contractors/print-service-request/<?php echo $details['id']; ?>" class="btn btn-success btn-sm btn-block"><i class="fa fa-print"></i> Print Request</a>
		<br>
		<a href="<?php echo base_url(); ?>contractors/print-service-request/<?php echo $details['id']; ?>" class="btn btn-success btn-sm btn-block"><i class="fa fa-save"></i> Save As PDF</a>
		<br>
		<a href="" class="btn btn-success btn-sm btn-block" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a>
		<br>
		<a href="#" class="btn btn-success btn-xs btn-block" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> <?php echo $notes; ?> Notes</a>
	</div>
</div>
<hr>
<div class="row sponsorship">
	<?php
		$noshow = false;
		if($noshow) {
		//if(!empty($ad_post)) { 
			foreach($ad_post as $key => $val) {
				echo '<div class="col-sm-4 text-center">';
					echo '<div class="link-contractor">';
						echo '<a href="'.base_url().'landlords/contractor-click/'.$val['id'].'/'.$val['url'].'" target="_blank">';
						echo '<h4><b>'.htmlspecialchars($val['title']).'</b></h4>';
						echo '<p>'.htmlspecialchars($val['desc']).'</p>';
						echo '<img src="'.base_url().'/public-images/'.$val['logo'].'" class="img-responsive" alt="'.$val['b_name'].'">';
						echo '<p><b>Contact:</b> '.htmlspecialchars($val['name']).'<br>';
						echo '<b>Phone:</b> ('.substr($val['phone'], 0, 3).') '.substr($val['phone'], 3, 3).'-'.substr($val['phone'],6).'</p>';
						echo '</a>';
					echo '</div>';
				echo '</div>';
			}
		}
	?>
</div>
<?php if(!empty($ad_post)) { ?>
<hr>
<?php } ?>
<h4><i class="fa fa-building-o text-success"></i> Landlord Details</h4>
<div class="row">
	<div class="col-sm-6">
		<p><b>Business Name:</b> <?php echo htmlspecialchars($details['bName']); ?></p>
		<p><b>Phone:</b> <?php echo "(".substr($details['landlord_phone'], 0, 3).") ".substr($details['landlord_phone'], 3, 3)."-".substr($details['landlord_phone'],6); ?></p>
		<p><b>Email:</b> <?php echo htmlspecialchars($details['landlord_email']); ?></p>
	</div>
	<div class="col-sm-6">
		<p><b>Submitted To:</b> <?php echo htmlspecialchars($details['landlord_name']); ?></p>
		<p><b>Location:</b> <?php echo htmlspecialchars($details['landlord_city']).', '.htmlspecialchars($details['landlord_state']).' '.htmlspecialchars($details['landlord_zip']); ?></p>
	</div>
</div>

<?php
	if(!empty($details['items'])) {
		echo '<hr><h3><i class="fa fa-archive text-success"></i> Items Related To This Request</h3>';
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

<!-- Add A Note Modal -->
<div class="modal fade" id="add-note" tabindex="-1" role="dialog" aria-labelledby="add-note" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('contractors/add-note-to-request/'.$details['id']); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-success"></i> Add Note To Service Request</h4>
			</div>
			<div class="modal-body">
				<label><span class="text-danger">*</span> Add Note:</label>
				<textarea name="note" class="form-control" required="required"></textarea>
				<label>Add Image <small>(Optional)</small>:</label>
				<input type="file" name="img" class="form-control">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Note</button>
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
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-success"></i> Notes Left On This Property</h4>
			</div>
			<div class="modal-body">
				<?php
					if(!empty($details['notes'])) {
						foreach($details['notes'] as $val) {
							echo '<p>'.htmlspecialchars($val['note']).'</p>';
							echo '<div class="row">';
								echo '<div class="col-sm-6">';
									echo '<small><b>Note Added:</b> '.date('m-d-Y H:s a', strtotime($val['s_timestamp'])+3600).'</small>';
								echo '</div>';
							echo '</div>';
								if(!empty($val['contractor_image'])) {
									echo '<br><i class="fa fa-paperclip"></i> <a href="'.base_url().'service-uploads/'.$val['contractor_image'].'" target="_blank">'.$val['contractor_image'].'</a>';
								}
							
							echo '<hr>';
						}
					} else {
						echo 'No Notes Have Been Left On This Account';
					}
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEvent">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addEventLabel"><i class="fa fa-calendar-o"></i> Add Event</h4>
				</div>
				<div class="modal-body">
					<label>Title:</label>
					<input type="text" id="eventTitle" name="title" placeholder="title" class="form-control" required>
			
			
					<div class="row">
						<div class="col-md-4">
							<div class="input-group">
								<label>Employee:</label>
								<select id="employeeId" class="form-control" required>
									<?php
										echo '<option value="'.$this->session->userdata('user_id').'">Me</option>';
										if(!empty($employees)) {
											foreach($employees as $key => $val) {
												echo '<option value="'.$val->id.'">'.$val->name.'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-md-8">
							<div class="input-group">
								<br>
								<div class="checkbox">
									<label><input id="eventAllDay" type="checkbox" value="true"> All Day Event?</label>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<label>Start Date: <small>mm/dd/yyyy</small></label>
								<div class="row">
									<div class="col-xs-6">
										<input type="text" name="startDate" id="createStartTask" class="form-control dateMask" required>
									</div>
									<div class="col-xs-6">
										<input type="text" name="startTime" id="createTaskStartTime" class="form-control timeMask" required>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="input-group">
								<label>End Date: <small>mm/dd/yyyy</small></label>
								<div class="row">
									<div class="col-xs-6">
										<input type="text" name="endDate" id="createEndTask" class="form-control dateMask" required>
									</div>
									<div class="col-xs-6">
										<input type="text" name="endTime" id="createTaskEndTime" class="form-control timeMask" required>
									</div>
								</div>
							</div>
						</div>
					</div>
					<input id="eventId" type="hidden">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="saveEvent" class="btn btn-success">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>