<?php
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control / Exterminator');
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><i class="fa fa-times fa-2x pull-left"></i> <b>Error:</b> '.$this->session->flashdata('error').'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><i class="fa fa-check fa-2x pull-left"></i> <b>Success:</b> '.$this->session->flashdata('success').'</div>';
	}
?>	

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-success">
			<div class="panel-heading">
				<h3 style="margin: 0; color: #fff;"><i class="fa fa-wrench"></i> Service Requested</h3>
			</div>
			<div class="panel-body serviceRequestDetails">
				<ul>
					<li><b class="highlight">Address:</b> <p class="indent"><?php echo htmlspecialchars($request->address); ?></p></li>
					<li><b class="highlight">Service Type:</b> <p class="indent"><?php echo htmlspecialchars($services_array[$request->service_type]); ?></p></li>
					<li><b class="highlight">Service Description:</b> <p class="indent"><?php echo htmlspecialchars($request->description); ?></p></li>
					<li><b class="highlight">Schedule Phone:</b> <p class="indent"><?php echo htmlspecialchars($request->schedule_phone); ?></p></li>
				</ul>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 style="margin: 0; color: #fff;"><i class="fa fa-building"></i> Landlord Details</h3>
			</div>
			<div class="panel-body serviceRequestDetails">
				<ul>
					<li><b class="highlight-blue">Contact Name:</b> <p class="indent"><?php echo htmlspecialchars($request->name); ?></p></li>
					<li><b class="highlight-blue">Location:</b> <p class="indent"><?php echo htmlspecialchars($request->address); ?></p></li>
					<li><b class="highlight-blue">Email:</b> <p class="indent"><?php echo htmlspecialchars($request->email); ?></p></li>
					<li><b class="highlight-blue">Phone:</b> <p class="indent"><?php echo htmlspecialchars($request->schedule_phone); ?></p></li>
				</ul>
			</div>
		</div>
	</div>	
</div>

<?php if(!empty($suppliers)) { ?>
	<hr>
	<div class="row">
		<?php 
			foreach($suppliers as $row) { 
				echo '<div class="col-md-6">';
					echo '<img ALIGN=LEFT src="'.base_url($row->logo).'" alt="'.$row->business.'" class="hidden-print aligncenter img-responsive suppliesHouseImg" height="90" width="90">';
				echo '</div>';
				echo '<div class="col-md-6">';
					echo '<p><b>'.ucwords(strtolower($row->business)).'</b><br>We have the supplies you need to complete any '.$services_array[$request->service_type].' need.<br><em class="hidden-print"><b><i class="fa fa-map-marker"></i>  <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->address).'+'.str_replace(' ', '+', $row->city).'+'.$row->state.'" target="_blank">'.$row->address.', '.$row->city.' '.$row->state.'</a></b></em></p>';
				echo '</div>';
			} 
		?>
	</div>
<?php } ?>

<div class="row">
	<div class="col-md-6">
		<?php
			if(!empty($isScheduled)) {
				echo '<div class="alert alert-info"><h3 style="color: #fff;border-bottom: 1px solid #cfcfcf;"><i class="fa fa-calendar"></i> Request Scheduled:</h3><p>This request has been scheduled for '.date('M dS Y h:i a', strtotime($isScheduled->start)).'</p>
				<br><a style="text-decoration: none" href="'.base_url('contractor/my-calendar').'" class="btn btn-primary">View My Calendar</a>
				</div>';
			}
		?>
		<div class="well well-sm serviceOptions">
			<h3 class="highlight"><i class="fa fa-gears"></i> Options </h3>
			<div class="row">
				<div class="col-xs-6 option">
					<a target="_blank" href="<?php echo base_url().'contractor/print-service-request/'.$request->request_id; ?>" class="btn btn-success"><i class="fa fa-save"></i> Print/Save PDF</a>
				</div>
				<div class="col-xs-6 option">
					<button id="viewNotes" data-toggle="modal" data-target="#addNote" class="btn btn-success"><i class="fa fa-file-o"></i> Add/View Notes</button>
				</div>
				<?php if($request->complete !== 'y') {?>
				<div class="col-xs-6 option">
					<button class="btn btn-success" data-target="#mark-complete" data-toggle="modal"><i class="fa fa-check"></i> Mark Complete</button>
				</div>
				<?php } ?>
				<?php if($request->listing_id>0) {?>
				<div class="col-xs-6 option">
					<button data-toggle="modal" data-target="#addItems" class="btn btn-success"><i class="fa fa-plus-circle"></i> Add Item To Property</button>
				</div>
				<?php } ?>
				<div class="col-xs-6 option">
					<button data-toggle="modal" data-target="#addEvent" class="btn btn-success"><i class="fa fa-calendar"></i> Add to Schedule</button>
				</div>
			</div>
		</div>		
	</div>
	<div class="col-md-6">
		<?php if($request->attachment !== '') { ?>
			<div class="thumbnail">
				<img src="<?php echo $request->attachment; ?>" class="img-responsive serviceAttachment" alt="Network 4 Rentals Service Attachment">
			</div>
		<?php } ?>
	</div>
</div>


<div class="modal fade" id="addNote" tabindex="-1" role="dialog" aria-labelledby="addNote" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content modal-lg">
		<?php echo form_open_multipart('contractor/add-note-to-request/'.$this->uri->segment(3), array('id'=>'addNoteForm')); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-success"></i> Add Note To Service Request</h4>
			</div>
			<div class="modal-body">
				<label><span class="text-danger">*</span> Add Note:</label>
				<textarea name="note" class="form-control" maxlength="600" required="required"></textarea>
				<div class="row">
					<div class="col-md-6">
						<label>Add Image <small>(Optional)</small>:</label>
						<input type="file" id="fileNote" name="img" class="form-control">
					</div>
				</div>
				<input type="hidden" value="<?php echo $this->uri->segment(3); ?>" name="id">
				<button type="submit" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add Note</button>
				<div class="clearfix"></div>
				<hr>
				<?php	
					if(!empty($notes)) {
						echo '<h3>Notes On Service Request</h3>';
						foreach($notes as $key => $val) {
							echo '<ul id="notes-list">';
								if(!empty($val->contractor_id)) {
									echo '<li class="contractor">';
									echo '<b><em>'.htmlspecialchars($val->contractor_name).':</em></b>';
								} else if(!empty($val->landlord_id)) {
									echo '<li class="landlord">';
									echo '<b><em>'.htmlspecialchars($request->landlord_info->bName).':</em></b>';
								}
									
									echo '<h5 class="pull-right"><b>Sent On: '.date('m-d-Y h:i a', strtotime($val->s_timestamp)).'</b></h5><div class="clearfix"></div>';
									echo '<p>'.htmlspecialchars($val->note).'</p>';
									if(!empty($val->contractor_image)) {
										echo '<i class="fa fa-paperclip"></i> <a target="_blank" href="https://network4rentals.com/network/public-images/'.$val->contractor_image.'">'.$val->contractor_image.'</a>';
									}
								echo '</li>';
							echo '</ul>';
						}
					}
				?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		<?php echo form_close(); ?>
    </div>
  </div>
</div>


<!-- Mark As Complete Modal -->
<div class="modal fade" id="mark-complete" tabindex="-1" role="dialog" aria-labelledby="mark-complete" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('contractor/mark-service-request-complete/'.$this->uri->segment(3)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-check text-primary"></i> Mark Service Request Complete</h4>
			</div>
			<div class="modal-body">
				<p>If you would like to keep track of the cost for this property add be able to total all the cost that have accumulated with this rental add the cost of the repairs below.</p>
				<hr>
				<div class="row">
					<div class="col-lg-4 col-md-6 col-sm-12">
						<label><span class="text-danger">*</span> Cost Of Repairs:</label>
						<input type="number" class="form-control" name="cost" required>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary "><i class="fa fa-check"></i> Mark Complete</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="addEvent" tabindex="-1" role="dialog" aria-labelledby="addEvent">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo form_open(''); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="addEventLabel"><i class="fa fa-calendar-o"></i> Add to Schedule</h4>
				</div>
				<div class="modal-body">
					<label>Title:</label>
					<input type="text" id="eventTitle" name="title" value="SR: <?php echo htmlspecialchars($services_array[$request->service_type]); ?>" placeholder="title" class="form-control" required>
			
			
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

					
					
						<label>Start Date: <small>mm/dd/yyyy</small></label>
						<div class="row">
							<div class="col-xs-4">
								<input type="text" name="startDate" id="createStartTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
							</div>
							<div class="col-xs-4">
								<input type="text" name="startTime" id="createTaskStartTime" class="form-control timeMask" value="<?php echo date('h'); ?>:00" required>
							</div>
							<div class="col-xs-4">
								<select id="startAm" name="startAm" class="form-control" required>
									<option value="am">AM</option>
									<option value="pm">PM</option>
								</select>
							</div>
						</div>
				
					
					
						<label>End Date: <small>mm/dd/yyyy</small></label>
						<div class="row">
							<div class="col-xs-4">
								<input type="text" name="endDate" id="createEndTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
							</div>
							<div class="col-xs-4">
								<input type="text" name="endTime" id="createTaskEndTime" class="form-control timeMask" value="<?php echo date('h'); ?>:00" required>
							</div>
							<div class="col-xs-4">
								<select id="endAm" name="endAm" class="form-control" required>
									<option value="am">AM</option>
									<option value="pm">PM</option>
								</select>
							</div>
						</div>
						
						<input type="hidden" id="link" value="<?php echo current_url(); ?>">
						
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="saveEvent" class="btn btn-success">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>