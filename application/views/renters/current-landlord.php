
<div class="row">
	<div class="col-sm-12">
		<div class="btn-group pull-right">
			<button type="button" class="btn btn-primary dropdown-toggle btn-block" data-toggle="dropdown" aria-expanded="false">
				<i class="fa fa-gears"></i> Options <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li><a href="<?php echo base_url(); ?>renters/add-landlord"><i class="fa fa-plus fa-fw"></i> Add New Landlord</a></li>
				<li><a href="<?php echo base_url().'renters/view-messages/'.$tenant_info[1]['id']; ?>"><i class="fa fa-comments fa-fw"></i> Message Your Landlord</a></li>
				<li><a href="<?php echo base_url().'renters/view-payment-history/'.$tenant_info[1]['id']; ?>"><i class="fa fa-dollar fa-fw"></i>  View Payments</a></li>
				<li><a href="<?php echo base_url().'renters/pay-rent'; ?>"><i class="fa fa-money fa-fw"></i>  Pay Rent</a></li>
				<li><a href="<?php echo base_url('renters/edit-rental-details/'.$tenant_info[1]['id']); ?>"><i class="fa fa-pencil fa-fw"></i> Edit Rental Details</a></li>
				<?php
					if(!empty($tenant_info[1]['checklist_id'])) {
						echo '<li><a href="'.base_url().'renters/view-checklist/'.$tenant_info[1]['checklist_id'].'"><i class="fa fa-check fa-fw"></i>  View Checklist</a></li>';
					} else {
						echo '<li><a href="'.base_url().'renters/checklist-form/'.$tenant_info[1]['id'].'"><i class="fa fa-check fa-fw"></i>  Complete New Checklist</a></li>';
					}
				?>
			</ul>
		</div>
	</div>
</div>

<?php if(!empty($landlord_info)) { ?>


	<div class="row">
		<div class="col-sm-12">
			<h3>
				<?php 
					if(!empty($landlord_info['bName'])) {
						$address_by = $landlord_info['bName']; 
					} else {
						$address_by = '';
					}
				?>
			</h3>
		
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-building fa-fw"></i> Landlord Details
				</div>
				<div class="panel-body">
					<?php if(!empty($address_by)) {echo '<p><b>Business Name:</b> <br>'.$address_by.'</p><hr>';}?>
					<p><b>Name:</b> <br><?php echo $landlord_info['name']; ?></p>
					<hr>
					<p><b>Email:</b> <br><?php echo $landlord_info['email']; ?></p>
					<hr>
					<p><b>Address:</b> <br><?php echo $landlord_info['address']; ?><br>
					<?php echo $landlord_info['city'].', '.$landlord_info['state'].' '.$landlord_info['zip']; ?></p><hr>
					<p class="hidden-xs"><b>Phone:</b> <br><?php echo "(".substr($landlord_info['phone'], 0, 3).") ".substr($landlord_info['phone'], 3, 3)."-".substr($landlord_info['phone'],6); ?></p><hr>
					<p class="visible-xs"><b>Phone:</b> <br><a class="btn btn-warning btn-xs" href="tel:<?php echo $landlord_info['phone']; ?>"><i class="fa fa-phone"></i> Call</a></p>
					<?php if(!empty($landlord_info['alt_phone'])) { ?>
						<p class="hidden-xs"><b>Alt Phone:</b> <br><?php echo "(".substr($landlord_info['alt_phone'], 0, 3).") ".substr($landlord_info['alt_phone'], 3, 3)."-".substr($landlord_info['alt_phone'],6); ?></p>
					<?php } ?>
					<?php
						if(empty($landlord_info['email'])) {
							$show_warning = true;
						} else {
							$show_warning = false;
						}
						if(!empty($tenant_info[1]['checklist_id'])) {
							echo '<p><b>Check-list:</b> <br><span class="text-success">Completed</span></p><hr>';
						} else {
							echo '<p><b>Check-list:</b> <br><span class="text-danger">Incomplete</span> <small><em>See Options Button Above</em></small></p><hr>';
						}
						if(!empty($landlord_info['user'])) {
							echo '<br><span class="label label-success">Registered Landlord</span>';
						} else {
							echo '<span class="label label-danger">Un-Registered Landlord</span>';
						}
					?>
				</div>
			</div>
		</div>
		
		<div class="col-sm-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-home fa-fw"></i> Rental Details
				</div>
				<div class="panel-body">
					
					<?php
					
						$num = $tenant_info[1]['day_rent_due'] % 100; // protect against large numbers
						if($num < 11 || $num > 13) {
							switch($num % 10) {
								case 1: $suffix = 'st';
								case 2: $suffix = 'nd';
								case 3: $suffix = 'rd';
							}
						} else {
							$suffix = 'th';
						}

					
						echo '<p><b>Address:</b> <br>'.$tenant_info[1]['rental_address'].'<br>'.$tenant_info[1]['rental_city'].', '.$tenant_info[1]['rental_state'].'. '.$tenant_info[1]['rental_zip'].'</p><hr><p><b>Move In Date:</b><br> '.date('m-d-Y', strtotime($tenant_info[1]['move_in'])).'</p><hr>';
						if($tenant_info[1]['move_out'] != '0000-00-00') {
							echo '<p><b>Move Out Date:</b><br> '.date('m-d-Y', strtotime($tenant_info[1]['move_out'])).'</p><hr>';
						} else {
							echo '<p><b>Move Out Date:</b><br> NA</p><hr>';
						}
						echo '<p><b>Rent Paid: </b><br> $'.$total.'</p><hr>';
						echo '<p><b>Disputes: </b><br> '.$disputed_payments.'</p><hr>';
						echo '<p><b>Lease: </b><br> '.$tenant_info[1]['lease'].'</p><hr>';
						echo '<p><b>Rent Per Month: </b><br>$'.$tenant_info[1]['payments'].'</p><hr>';
						echo '<p><b>Rent Due Date: </b><br>'.$tenant_info[1]['day_rent_due'].' '.$suffix.' of the month <a  class="pull-right" href="'.base_url('renters/edit-rental-details/'.$tenant_info[1]['id']).'">Edit</a></p><hr>';
						echo '<p><b>Uploaded Lease: </b><br>';
						if(!empty($tenant_info[1]['lease_upload'])) {
							echo '<a href="'.base_url().'lease-uploads/'.$tenant_info[1]['lease_upload'].'" target="_blank" class="btn btn-warning btn-xs"><i class="fa fa-print"></i> Print/View</a></p>';
						} else {
							echo '<span class="text-danger">No Lease Uploaded</span>';
						}

					?>
					
					</div></div>
					<?php 
						
						echo '</div></div>';
						if($show_warning == true) {
							echo '<div class="modal fade" id="attentionUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
											<h3><i class="fa fa-exclamation-triangle text-warning"></i> Attention</h3>
										  </div>
										  <div class="modal-body">
											<p>Without out an email address or cell phone number of your current landlord our system has no way to communicate with your landlord. If you know your landlords email address click the edit landlord button now. If you know their cell phone number edit you landlords details with the cell phone number and we will automatically convert their cell phone number to an email address. </p>
										  </div>
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
											<a href="'.base_url().'renters/edit-rental-details/'.$tenant_info[1]['id'].'" class="btn btn-warning">Edit Current Landlord</a>
										  </div>
										</div>
									  </div>
									</div>';
						}
?>
		
		
	
	
<?php } else { ?>
	<p>You have not added any landlords to your account. Once you add a landlord and check the current landlord box their infomration will pull into here.</p>
	<a href="<?php echo base_url(); ?>renters/add_landlord" class="btn btn-warning btn-sm"><i class="fa fa-plus"></i> Add A Landlord</a>
<?php } ?>