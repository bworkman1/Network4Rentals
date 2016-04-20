<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;" class="pull-left"><i class="fa fa-calendar"></i> My Calendar</h3>
		<a href="#" style="margin-left:5px" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#addEvent"><i class="fa fa-plus"></i> Add Event</a>
		<a href="<?php echo base_url('contractor/add-service-request'); ?>" class="btn btn-primary btn-sm pull-right"><i class="fa fa-wrench"></i> Add Work Request</a>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">
		<div id="calendar"></div>
		<br>
		<div class="well well-sm">
			<ul style="margin: 0; padding: 0;">
				<li style="float: left; margin: 5px;"><b>Employee Colors:</b></li>
				<?php
					echo '<li style="font-size: .8em; border: 1px solid #cdcdcd; float: left; margin: 5px; padding-right: 4px;"><p style="margin: 0;line-height: .8em;"><span style="background-color: #28B62C; width: 20px; height: 20px; display: inline-block"></span> <span style="position: relative;
		top: -5px;">Your Color</span></p></li>';
					foreach($employees as $val) {
						echo '<li style="font-size: .8em; border: 1px solid #cdcdcd; float: left; margin: 5px; padding-right: 4px;"><p style="margin: 0;line-height: .8em;"><span style="background-color: '.$val->color.'; width: 20px; height: 20px; display: inline-block"></span> <span style="position: relative;
		top: -5px;">'.$val->name.'</span></p></li>';
					}
				?>
			</ul>
			<div class="clearfix"></div>
			<hr>
			<a href="<?php echo base_url('contractor/add-employee'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add/Edit Employees</a>
			<div class="clearfix"></div>
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

					<label>Start Date: <small>mm/dd/yyyy</small></label>
					<div class="row">
						<div class="col-sm-4 col-xs-6">
							<input type="text" name="startDate" id="createStartTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
						</div>
						<div class="col-sm-4 col-xs-6">
							<input type="text" name="startTime" id="createTaskStartTime" class="form-control timeMask" value="<?php echo date('h:i'); ?>" required>
						</div>
						<div class="col-sm-4 col-xs-6">
							<select id="startAm" name="startAm" class="form-control" required>
								<option value="am">AM</option>
								<option value="pm">PM</option>
							</select>
						</div>
					</div>
			
				
				
					<label>End Date: <small>mm/dd/yyyy</small></label>
					<div class="row">
						<div class="col-sm-4 col-xs-6">
							<input type="text" name="endDate" id="createEndTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
						</div>
						<div class="col-sm-4 col-xs-6">
							<input type="text" name="endTime" id="createTaskEndTime" class="form-control timeMask" value="<?php echo date('m/d/Y'); ?>" required>
						</div>
						<div class="col-sm-4 col-xs-6">
							<select id="endAm" name="endAm" class="form-control" required>
								<option value="am">AM</option>
								<option value="pm">PM</option>
							</select>
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

<div class="modal fade" id="eventOptions" tabindex="-1" role="dialog" aria-labelledby="eventOptions">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="addEventLabel"><i class="fa fa-cog"></i> Event Options</h4>
      </div>
		<div class="modal-body">
			<label>Title:</label>
			<input type="text" id="editEventTitle" name="title" placeholder="title" class="form-control">
	
	
			<div class="row">
				<div class="col-md-4"> 
					<div class="input-group">
						<label>Employee:</label>
						<select id="editEmployeeId" class="form-control">
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
							<label><input id="editEventAllDay" type="checkbox" value="true"> All Day Event?</label>
						</div>
					</div>
				</div>
			</div>
			
		
			<label>Start Date: <small>mm/dd/yyyy</small></label>
			<div class="row">
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<input type="text" name="startDate" id="editCreateStartTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
					</div>
				</div>
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<input type="text" name="startTime" id="editCreateTaskStartTime" class="form-control timeMask" value="<?php echo date('h:i'); ?>" required>
					</div>
				</div>
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<select id="editStartAm" name="startAm" class="form-control" required>
							<option value="am">AM</option>
							<option value="pm">PM</option>
						</select>
					</div>
				</div>
			</div>
	
		
		
			<label>End Date: <small>mm/dd/yyyy</small></label>
			<div class="row">
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<input type="text" name="endDate" id="editCreateEndTask" class="form-control dateMask" value="<?php echo date('m/d/Y'); ?>" required>
					</div>
				</div>
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<input type="text" name="endTime" id="editCreateTaskEndTime" class="form-control timeMask" value="<?php echo date('m/d/Y'); ?>" required>
					</div>
				</div>
				<div class="col-sm-4 col-xs-6">
					<div class="form-group">
						<select id="editEndAm" name="endAm" class="form-control" required>
							<option value="am">AM</option>
							<option value="pm">PM</option>
						</select>
					</div>
				</div>
			</div>
			
	
		</div>
      <div class="modal-footer">
		<button type="button" id="deleteEvent" data-id="" class="btn btn-danger pull-left"><i class="fa fa-times"></i> Delete</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="editEvent" class="btn btn-success">Save</button>
      </div>
    </div>
  </div>
</div>