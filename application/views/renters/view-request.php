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
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-building-o"></i> Landlord Details
			</div>
			<div class="panel-body">
				<p><b>Submitted To:</b> <?php if(!empty($landlord['bName'])) {echo $landlord['bName'];} else {echo $landlord['name'];} ?></p>
				<p><b>Phone:</b> <?php echo "(".substr($landlord['phone'], 0, 3).") ".substr($landlord['phone'], 3, 3)."-".substr($landlord['phone'],6); ?></p>
				<p><b>Email:</b> <?php echo $landlord['email']; ?></p>
				<p><b>Location:</b> <?php echo $landlord['city'].', '.$landlord['state'].' '.$landlord['zip']; ?></p>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-warning">
			<div class="panel-heading">
				<i class="fa fa-home"></i> Service Location
			</div>
			<div class="panel-body">
				
				<p><b>Renter Name</b><br>
				<?php echo $user[0]['name']; ?></p>
				<p><b>Address</b><br>
					<?php
						echo $user[1]['rental_address'];
					?>
				</p>
				<p><b>Phone:</b> <?php echo "(".substr($user[0]['phone'], 0, 3).") ".substr($user[0]['phone'], 3, 3)."-".substr($user[0]['phone'],6); ?></p>
			</div>
		</div>
	</div>
</div>

<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-wrench"></i> Service Requested
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-6">
				<p><b>Service Type:</b> <?php echo $services_array[$requests['service_type']]; ?></p>
				<p><b>Description:</b> <?php echo $requests['description']; ?></p>
				<p><b>Permission To Enter:</b>  <?php echo ucwords($requests['enter_permission']); ?></p>
				<p><b>Call For Scheduling:</b> <?php echo "(".substr($requests['schedule_phone'], 0, 3).") ".substr($requests['schedule_phone'], 3, 3)."-".substr($requests['schedule_phone'],6); ?></p>
			</div>
			<div class="col-sm-6">
				<p><b>Submitted:</b> <?php echo date('m-d-Y h:i a', strtotime($requests['submitted'])+3600); ?> <small>EST</small></p>
				<p><b>Status:</b> 
					<?php 
						if($requests['complete'] == 'y') { 
							echo 'Completed On '.date('m-d-Y h:i a', strtotime($requests['completed'])+3600).' <small>EST</small>';	
						} else { 
							echo'Incomplete';} 
						?>
				</p>
				<p><b>Viewed:</b> <?php if($requests['viewed'] != '0000-00-00 00:00:00') { echo date('m-d-Y h:i a', strtotime($requests['viewed'])+3600).' <small>EST</small>';	} else { echo 'Has Not Been Opened Yet';} ?></p>
				
			</div>
		</div>
	</div>
</div>
				

<hr>
	<div class="row">
	
		<div class="col-md-4">
			<?php if(!empty($requests['attachment'])) { ?>
				<img src="<?php echo base_url().'service-uploads/'.$requests['attachment']; ?>" class="img-responsive service-request-image" alt="Service Attachment">
			<?php } else { ?>
				<img src="<?php echo base_url(); ?>service-uploads/image_not_found.jpg" class="img-responsive service-request-image" alt="Service Attachment">
			<?php } ?>
		</div>
		
		<div class="col-md-4">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-file-o"></i> Additional Notes
				</div>
				<div class="panel-body">
					<ul id="renterNotes">
						<?php 
							foreach($notes as $row) {
								echo '<li><p><i class="fa fa-comment-o"></i> '.$row->note.'</p><p class="text-right"><small><i class="fa fa-clock-o"></i> '.date('m-d-Y h:i a', strtotime($row->s_timestamp)).'</small></p></li>';
							}
						?>
					</ul>
					<?php echo form_open('renters/addNoteToRequest'); ?>
						<textarea name="note" class="form-control" maxlength="255" required style="height: 100px"></textarea>
						<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
						<button type="submit" class="btn btn-sm btn-primary pull-right" style="margin-top: 5px;"><i class="fa fa-plus fa-fw"></i> Add</button>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
		
		<div class="col-sm-4">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-gears"></i> Options
				</div>
				<div class="panel-body">
					<?php if(!empty($landlord['user'])) { ?>
						<a href="<?php echo base_url(); ?>renters/status-update/<?php echo $this->uri->segment(3); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-refresh"></i> Request Status Update</a>	<br>
						
					<?php } else { ?>
						<?php if($requests[0]['complete'] == 'n') { ?>
							<a href="<?php echo base_url(); ?>renters/service-request_complete/<?php echo $this->uri->segment(3); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-check"></i> Mark As Complete</a>
							<br>
					
						<?php } ?>
					<?php } ?>
					
					<?php if($requests[0]['complete'] == 'n') { ?>
						
						<a href="<?php echo base_url(); ?>renters/resend-service-request/<?php echo $this->uri->segment(3); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-share"></i> Resend Request</a>
						<br>
						
					<?php } ?>
	
					<a href="<?php echo base_url('print-handler/print-service-request/'.$this->uri->segment(3)); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-print"></i> Print Request</a>
					<br>
					<a href="<?php echo base_url('print-handler/print-service-request/'.$this->uri->segment(3)); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-save"></i> Save Request</a>
					<br>
					<a href="<?php echo base_url('renters/view-messages/'.$user[1]['id']); ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-comments"></i> Message Landlord</a>
			
			</div>
		</div>		
	</div>
</div>
