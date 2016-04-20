<div class="panel panel-primary">
	<div class="panel-heading">	
		<i class="fa fa-pencil stylish-icon"></i> Edit Event
	</div>
	<?php echo form_open('landlord-associations/edit-event/'.$this->uri->segment(3)); ?>
		<div class="panel-body">
			<?php
				if(!empty($errors)) {
					echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle fa-2x pull-left"></i> <h4><b>Error:</b></h4>'.$errors.'</div>';
				} 
				
				if(!empty($success)) {
					echo '<div class="alert alert-success"><i class="fa fa-check fa-2x pull-left"></i> <b><h4>Success:</h4></b>'.$success.'</div>';
				}
			?>
			<div class="row">
				<div class="col-md-4">
					<div class="row">
						<div class="col-sm-12">
							<div class="input-group"><label for="date"><i class="fa fa-asterisk text-danger"></i> Starts:</label></div>
							<div class="input-group input-append date" id="datetimepicker">
								<input class="form-control dateInput" data-format="MM/dd/yyyy hh:mm:00" readonly="readonly" name="starts" type="text" value="<?php echo $event_details->start; ?>" required>
								<span class="input-group-addon add-on">
									<i data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">			
							<div class="input-group"><label for="date"><i class="fa fa-asterisk text-danger"></i> Ends:</label></div>
							<div class="input-group input-append date" id="datetimepicker">
								<input class="form-control dateInput" data-format="MM/dd/yyyy hh:mm:00" value="<?php echo $event_details->end; ?>" readonly="readonly" name="ends" type="text">
								<span class="input-group-addon add-on">
									<i data-time-icon="glyphicon glyphicon-time" data-date-icon="glyphicon glyphicon-calendar"></i>
								</span>
							</div>
						</div>
					</div>
					<label><i class="fa fa-asterisk text-danger"></i> Event Title:</label>
					<input type="text" class="form-control"  value="<?php echo $event_details->what; ?>" name="what" maxlength="60">
					<label><i class="fa fa-asterisk text-danger"></i> Venue Name:</label>
					<input type="text" class="form-control"  value="<?php echo $event_details->where; ?>" name="where" maxlength="60">
					<label>Address: <small>Include city and state</small></label>
					<input type="text" class="form-control cal-addresses" value="<?php echo $event_details->address; ?>" name="address" placeholder="37 N Main St Utica Ohio 43037" maxlength="60">
					<div class="row">
						<div class="col-xs-10">
							<label>Include Google Map <i class="fa fa-question-circle text-primary toolTips" title="You will need the coordinates of the event location. This will add a google map when someone views the event."></i><label>
						</div>
						<div class="col-xs-2">
							<input type="checkbox" name="map" class="showCalAddress" value="y" <?php if($event_details->map == 'y') {echo 'checked';} ?>>
						</div>
					</div>
	
					<div class="eventEditMap cal-address" <?php if($event_details->map == 'y') {echo 'style="display: block;"';} ?>>
						<hr>
						<div id="map_canvas"></div>
						<div id="pano"></div>
						<hr>
					</div>
					<hr>
					<div class="row">
						<div class="col-xs-4">
							<label>Event Type <i class="fa fa-question-circle text-primary toolTips" title="Making this event public will make it so that someone visiting your public page can see it on the calendar there"></i><label>
						</div>
						<div class="col-xs-8">
							<select name="public" class="form-control" required>
								<option value="y" <?php if($event_details->public == 'y') {echo 'selected';} ?>>Public Event</option>
								<option value="n" <?php if($event_details->public == 'n') {echo 'selected';} ?>>Members Only Event</option>
							</select>
						</div>
					</div>
				</div>
				<div class="col-md-8">		
					<label>Event Details</label>
					<textarea id="post-input" class="summernote" style="height: 600px; width: 100%" name="details"><?php echo $event_details->details; ?></textarea>
					<small>Image Upload Progress</small>
					<div class="progress">
						<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
							0% Complete
						</div>
					</div>
					<div id="pageFeedback"></div>
				</div>
			</div>
		</div>
		<div class="panel-footer text-center">	
			<button type="submit" class="btn btn-primary btn-public">Edit Event</button>
		</div>
		<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
	</form>
</div>