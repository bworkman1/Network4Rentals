<div class="row">
	<div class="col-sm-5">
		<h2><i class="fa fa-bullhorn text-primary"></i> Recent Activity</h2>
	</div>
	<div class="col-sm-7 text-right">
		<br>
		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#searchDates">
			<i class="fa fa-search"></i> Search By Date
		</button>
		<?php
			if(!empty($date_to)) {
				echo '<a href="'.base_url().'landlords/reset_dates" class="btn btn-primary btn-sm">
						<i class="fa fa-times"></i> Reset Dates
					</a>';
			}
		?>
	</div>
</div>
<hr>
<div class="activity">
<?php
	
	if(!empty($reset)) {
		echo $reset;
	}
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	
	if(!empty($results)) {

		foreach($results as $data) {
			echo '<div class="line-under">';
			echo '<div class="row">';
			echo '<div class="col-sm-6">';
			echo $data->action;
			echo '</div>';
			echo '<div class="col-sm-4 text-center">';
			echo '<i class="fa fa-clock-o"></i> '.date('m-d-Y h:i a', strtotime($data->created)+3600);
			echo ' <small>EST</small></div>';
			echo '<div class="col-sm-2 text-right">';
			if($data->action == 'Added New Property') {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/edit-listing/'.$data->action_id.'">View Property</a>';
			} else if($data->action == 'Made Changes On Property Listing') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/edit-listing/'.$data->action_id.'">View Property</a>';
			} else if($data->action == 'Received New Service Request' || $data->action == 'Requested Status Update On Service Request') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-service-request/'.$data->action_id.'">View Request</a>';
			} else if($data->action == 'Updated Public Page Setting') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/public-page-view">View Page</a>';
			} else if($data->action == 'Marked Service Request Complete') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-service-request/'.$data->action_id.'">View Request</a>';	
			} else if($data->action == 'New Tenant Linked To You') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-tenant-info/'.$data->action_id.'">View Tenant</a>';	
			} else if($data->action == 'Completed Rental Check-list') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-tenant-checklist/'.$data->action_id.'">View List</a>';	
			} else if($data->action == 'Added A Service Request') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-service-request/'.$data->action_id.'">View Request</a>';
			} else if($data->action == 'Message Sent To Tenant') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/message-tenant/'.$data->action_id.'">View Message</a>';
			} else if($data->action == 'Assigned Manager To Tenant') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-tenant-info/'.$data->action_id.'">View Tenant</a>';
			} else if($data->action == 'A Tenant Viewed Your Messages' or $data->action == 'Replied To Tenants Message' or $data->action == 'Message Received From Tenant') {	
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/message-tenant/'.$data->action_id.'">View Messages</a>';	
			} else if($data->action == 'Added A Rent Payment' or $data->action == 'Renter Paid Offline Payment') {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-tenant-info/'.$data->action_id.'">View Payments</a>'; 
			} else if($data->action == 'New note left on payment' or $data->action == 'Tenant Set-up Auto Rent Payments' or $data->action == 'Tenant Cancelled Auto Payments') {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-tenant-info/'.$data->action_id.'">View Note</a>'; 
			} else if($data->action == 'New property add to group') {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/edit-listing/'.$data->action_id.'">View Listing</a>'; 
			} else if($data->action == 'You Have Been Invited To Join A Landlord Association') {
				$link = '<a href="#" data-inviteid="'.$data->action_id.'" class="btn btn-primary assoc-invite btn-xs">View Invite</a>'; 			 
			} else if($data->action == 'New Event Added By Landlord Association') {
				$link = '<a href="'.base_url().'landlords/view-association-events/'.$data->action_id.'" target="_blank" class="btn btn-primary btn-xs">View Events</a>'; 
			} elseif(strpos($data->action,'Forwarded Service Request To') !== false) {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/view-service-request/'.$data->action_id.'">View Request</a>';
			} elseif($data->action == 'Rental Payments Processed Today') {
				$link = '<a class="btn btn-primary btn-xs" href="'.base_url().'landlords/payment-data/'.date('Y', strtotime($data->created)).'/'.date('m', strtotime($data->created)).'">View Payments</a>';
			} else {
				$link = "";
			}
			echo $link;
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
		
	} else {
		if(!empty($date_to)) {
			echo '<p>No results were found <b>From '.$date_from.'</b> To <b>'.$date_to.'</b>, try widening your search a little.</p><p>You can reset the dates by clicking the <b>reset dates button</b> above or search for a new set of dates.</p>';
		} else {
			echo 'You have no activity yet, as you move around the website you will see this list start to fill in with all your activity. This will create a reference point for you to see what you have done and allow you to view things quickly.';
		}
	}
?>
<hr>
</div>
<div class="text-center">
	
	<?php 
		if(!empty($results)) {
			echo $links;
		}
	?>
</div>
<!-- Modal -->
<div class="modal fade" id="searchDates" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('landlords/activity'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="myModalLabel"><i class="fa fa-search text-primary"></i> Search Activity By Date</h3>
			</div>
			<div class="modal-body">
				<p>Select The Dates You Would Like To Narrow Your Activity Down To.</p>
				<div class="row">
					<div class="col-sm-6">
						<?php
							echo form_label('<i class="fa fa-calendar"></i> Date From:');
							$data = array(
								'name'        => 'date_from',
								'id'          => 'date_from',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off'
							);
							echo form_input($data);
						?>
					</div>
					<div class="col-sm-6">
						<?php
							echo form_label('<i class="fa fa-calendar"></i> Date To:');
							$data = array(
								'name'        => 'date_to',
								'id'          => 'date_to',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off'
							);
							echo form_input($data);
							
						?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Dates</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="acceptInvite" tabindex="-1" role="dialog" aria-labelledby="acceptInvite">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"></h4>
			</div>
			<div class="modal-body">
				<p>Joining associations will allow you to display their logo on your rental listings show that you are a member of these associations.</p>
				<hr>
				<div id="inviteData">
				
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php 
	
	$first = $this->session->userdata('firstTime');
	if($first) {
?>
<div class="modal fade" id="whatnow" tabindex="-1" role="dialog" aria-labelledby="whatnow">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title" id="myModalLabel">What do I do now?</h3>
			</div>
			<div class="modal-body text-center">
				<div class="row">
					<div class="col-md-6">
						<i class="fa fa-home fa-fw fa-3x text-primary"></i>
						<h3>Advertise my vacancies for free</h3>
						<a href="<?php echo base_url('landlords/add-listing'); ?>" class="btn btn-primary">Add a property listing</a>
					</div>
					
					<div class="col-md-6">
						<i class="fa fa-question-circle fa-fw fa-3x text-primary"></i>
						<h3>Learn more about N4R services</h3>
						<a href="<?php echo base_url('landlords/videos'); ?>" class="btn btn-primary">Landlord How to Videos</a>
					</div>
				</div>
				<hr>
				<p>Verify my public page and begin using N4R to communicate with my tenants &amp; contractors, list my vacancies, collect online rent payments and much more...</p>
				<a href="<?php echo base_url('landlords/public-page-settings'); ?>" class="btn btn-primary">Verify my Public Page</a>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<?php $this->session->unset_userdata('firstTime'); } ?>