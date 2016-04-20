
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title pull-left"><i class="fa fa-calendar stylish-icon"></i> Full Calendar</h3>
				<a href="<?php echo base_url('landlord-associations/add-new-event'); ?>" class="btn btn-info pull-right">Add Event</a>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">
				<div id="large-calendar">
					<?php
						$prefs = array (
						   'show_next_prev'  => TRUE,
						   'next_prev_url'   => ''
						 );
		
						$this->load->library('calendar', $prefs);
						echo $this->calendar->generate($this->uri->segment(3), $this->uri->segment(4), $events);
					?>
				</div>
			</div>
		</div>



<div class="modal fade" id="eventDetails" tabindex="-1" role="dialog" aria-labelledby="eventDetails" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Modal title</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-4">
						<div><b><i class="fa fa-clock-o"></i> Starts:</b><br> <span id="start"></span></div>
					</div>
					<div class="col-sm-4"> 
						<div><b><i class="fa fa-clock-o"></i> Ends:</b><br> <span id="end"></span></div>
					</div>
					<div class="col-sm-4">
						<div><b><i class="fa fa-lock"></i> Public:</b><br> <span id="public"></span></div>
					</div>
				</div>
				<hr> 
				<div><b><i class="fa fa-bullhorn"></i> Where:</b><br><span id="where">Plaza hotel penthouse suite 449, BYOB</span></div>
				
				<div id="show_map" class="fade">
					<hr>
					<div><b><i class="fa fa-dot-circle-o"></i> Address:</b>
					<br><span id="address"></span></div>
					<hr>
					<b><i class="fa fa-map-marker"></i> Map:</b>
					<div id="event_map"></div>
				</div>
				<hr>
			</div>
			<div class="modal-footer">
				<div class="row">
					<div class="col-sm-3 text-center">
						<button class="deleteBtn btn btn-danger btn-block" data-deleteid=""><i class="fa fa-times"></i> Delete Event</button>
					</div>
					<div class="col-sm-3 text-center">
						<a href="" class="btn btn-primary btn-block editEventBtn"><i class="fa fa-pencil"></i> Edit Event</a>
					</div>
					<div class="col-sm-3 text-center">
						<a href="" class="btn btn-primary btn-block eventDetailsBtn"><i class="fa fa-pencil"></i> Event Details</a>
					</div>
					<div class="col-sm-3">
						<button class="btn btn-default btn-block" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>


