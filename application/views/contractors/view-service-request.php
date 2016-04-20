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
<div id="service-request-details">
	<div class="widget-body">
		<div class="row">
			<div class="col-md-6">
				<h4><b>Service Request</b></h4>
				<p><b>Submitted:</b> <?php echo date('M, d Y @ h:i a', strtotime($details['submitted'])+3600); ?> EST</p>
				<p><b>Status:</b> 
					<?php 
						if($details['complete'] == 'y') { 
							echo '<span class="text-success">Complete</span>';	
						} else {
							echo'<span class="text-danger">Incomplete</span>';
						} 
					?>
				</p>
			</div>
			<div class="col-md-6">
				<div class="btn-group hidden-print pull-right" style="margin-top: 4px">
				<button type="button" class="btn btn-success">Options</button>
				<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul id="serviceRequestOptions" class="dropdown-menu">
					<?php if($request->complete == 'n') { ?>
						<li><a href="#" data-toggle="modal" data-target="#mark-complete"><i class="fa fa-check"></i> Mark As Complete</a></li>
					<?php } ?>
					<li><a href="<?php echo base_url('print-handler/print-service-request/'.$details['id']); ?>"><i class="fa fa-print"></i> Print Request</a></li>
					<li><a href="<?php echo base_url('print-handler/print-service-request/'.$details['id']); ?>"><i class="fa fa-save"></i> Save As PDF</a></li>
					
					<li><a href="" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a></li>
					<li><a href="#" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> View Notes <span class="badge badge-info pull-right"><?php echo $notes; ?></span></a></li>
					<?php 
						if($details['who'] == '1') {
							echo '<li><a href="'.base_url('landlords/message-tenant/'.$details['rental_id']).'"><i class="fa fa-comments"></i> Message Tenant</a></li>';
						
						}
					?>
				</ul>
			</div>
			</div>
		</div>
		
		<hr>
		<div class="row"> 
			<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h5 class="panel-title"><b>Landlord Details:</b></h5>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="borderRow"><b>Name:</b></div>
								<div class="borderRow"><?php echo htmlspecialchars($details['landlord_name']); ?></div>
								
								<div class="borderRow"><b>Phone:</b></div>
								<div class="borderRow"><?php echo "(".substr($details['landlord_phone'], 0, 3).") ".substr($details['landlord_phone'], 3, 3)."-".substr($details['landlord_phone'],6); ?></div>
							</div>
							<div class="col-md-6">
								<div class="borderRow"><b>Business Name:</b></div>
								<div class="borderRow"><?php echo htmlspecialchars($details['bName']); ?></div>
								<div class="borderRow"><b>Email:</b></div>
								<div class="borderRow"><a href="mailto:<?php echo htmlspecialchars($details['landlord_email']); ?>"><?php echo htmlspecialchars($details['landlord_email']); ?></a></div>
							</div>
						</div>	
	
						<div class="borderRow"><b><i class="fa fa-map-marker"></i> Mailing Address:</b> <br class="visible-xs"><?php echo htmlspecialchars($details['landlord_address']).',  '.$details['landlord_city'].' '.htmlspecialchars($details['landlord_state']).' '.htmlspecialchars($details['landlord_zip']); ?></div>
					</div>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Renter Details:</b></h3>
					</div>
					<div class="panel-body">
						<div class="borderRow"><b>Name: </b><?php if(!empty($details['tenant_name'])) {echo htmlspecialchars($details['tenant_name']);} else {echo 'NA';} ?></div>
						<div class="borderRow"><b>Phone:</b> 
						<?php if(!empty($details['tenant_phone'])) {echo "(".substr($details['tenant_phone'], 0, 3).") ".substr($details['tenant_phone'], 3, 3)."-".substr($details['tenant_phone'],6);} else {echo 'NA';} ?></div>
						<div class="borderRow"><b>Email:</b> <a href="mailto:<?php echo htmlspecialchars($details['landlord_email']); ?>"><?php echo htmlspecialchars($details['landlord_email']); ?></a></div>
						
						<div class="borderRow"><b><i class="fa fa-map-marker"></i>  Rental Address:</b><br class="visible-xs"> <a href="https://www.google.com/maps/place/<?php echo str_replace(' ', '+', $details['address'].' '.$details['city'].' '.$details['state'].' '.$details['zip'] ); ?>+Utica+OH" target="_blank"><?php echo htmlspecialchars($details['address']).' '.htmlspecialchars($details['city']). ', '.htmlspecialchars($details['state']). ' '.htmlspecialchars($details['zip']); ?></a></div>
						
						
						
						
					</div>
				</div>			
			</div>
		</div>
		
		<?php if(!empty($suppliers) || !empty($ad_post) ) { ?>
			<?php if(!empty($suppliers)) { ?>
				<div id="supplyHouses" class="well">
					<h4 style="border-bottom: 1px solid #ccc"><b>Need Supplies for the job?</b></h4> 
					<div class="row">
						<?php 
							foreach($suppliers as $row) { 
								echo '<div class="col-md-6">';
									if(!empty($row->url)) {
										echo '<a href="'.$row->url.'" class="pull-left" target="_blank"><img src="'.base_url($row->logo).'" alt="'.$row->business.'" class="hidden-print aligncenter img-responsive suppliesHouseImg" style="max-width: 100px"></a>';
									} else {
										echo '<img src="'.base_url($row->logo).'" alt="'.$row->business.'" class="pull-left img-responsive suppliesHouseImg" style="max-width: 100px">';
									}
									echo '<h3 style="margin-top:5px" class="text-primary"><b>'.ucwords(strtolower($row->business)).'</b></h3>';
								
									echo '<p>We have the supplies you need to complete any '.$services_array[htmlspecialchars($details['service_type'])].' need.<br><em class="hidden-print"><b><i class="fa fa-map-marker"></i>  <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $row->address).'+'.str_replace(' ', '+', $row->city).'+'.$row->state.'" target="_blank">'.$row->address.', '.$row->city.' '.$row->state.'</a></b></em></p>';
								echo '</div>';
							} 
						?>
					</div>
				</div>
			<?php } ?>
				
				
			<?php
				
				if(!empty($ad_post)) {
					echo '<hr>';
					echo '<div class="row sponsorship">';
					echo '<div class="col-sm-12"><h4><i class="fa fa-bullhorn text-primary"></i> Sponsored Contractors</h4></div>';
					shuffle($ad_post);
					foreach($ad_post as $val) {
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
					}
					echo '</div>';
					echo '<hr>';
				}
			?>
				
			
		<?php } //AD POST OR SUPPLIERS CLAUSE ?>
		
		
		<div class="row">
			<?php 
				if(!empty($details['attachment'])) { 
					echo '<div class="col-md-8">';
				} else {
					echo '<div class="col-md-12">';
				}
			?>
			
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title panel-with-button pull-left"><b>Service Request Details:</b></h3>
						<div class="btn-group hidden-print pull-right" style="margin-top: 4px">
							<button type="button" class="btn btn-info btn-sm">Options</button>
							<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul id="serviceRequestOptions" class="dropdown-menu">
								<?php if($details['complete'] == 'n') { ?>
									<li><a href="#" data-toggle="modal" data-target="#mark-complete"><i class="fa fa-check"></i> Mark As Complete</a></li>
								<?php } ?>
								<li><a href="<?php echo base_url('print-handler/print-service-request/'.$details['id']); ?>"><i class="fa fa-print"></i> Print Request</a></li>
								<li><a href="<?php echo base_url('print-handler/print-service-request/'.$details['id']); ?>"><i class="fa fa-save"></i> Save As PDF</a></li>
								<?php if($details['complete'] == 'n') { ?>
									<li><a href="" data-toggle="modal" data-target="#forward-request"><i class="fa fa-reply"></i> Forward Service Request</a></li>
								<?php } ?>
								<li><a href="" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a></li>
								<li><a href="#" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> View Notes <span class="badge badge-info pull-right"><?php echo $notes; ?></span></a></li>
								<?php 
									if($details['who'] == '1') {
										echo '<li><a href="'.base_url('landlords/message-tenant/'.$details['rental_id']).'"><i class="fa fa-comments"></i> Message Tenant</a></li>';
									
									}
								?>
							</ul>
						</div>
						<div class="clearfix"></div>
					</div>
					<div class="panel-body">
						<div class="well">
							<?php
								if($isScheduled !== false) {
									echo '<h4>Scheduled for '.date('m-d-Y h:i a', strtotime($isScheduled->start)).'</h4>';
								} else {
									echo '<h4>Request has not been schedules yet</h4><a href="" class="btn btn-success">Add to Schedule</a>';
								}
							?>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Service Type:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><?php echo $services_array[htmlspecialchars($details['service_type'])]; ?></div>
							</div>
						</div>	
						<div class="row">
							<div class="col-lg-3 col-md-4">	
								<div class="borderRow"><b>Permission To Enter:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<?php
									$details['enter_permission'] = strtolower($details['enter_permission']);
									if($details['enter_permission'] == 'y' || $details['enter_permission'] == 'yes') {
										echo '<div class="text-success borderRow">Yes</div>';
									} elseif($details['enter_permission'] == 'call') {
										echo '<div class="text-success borderRow">Call First</div>';
									} else {
										echo '<div class="text-danger borderRow">No</div>';
									}
								?>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-3 col-md-4">	
								<div class="borderRow"><b>Phone# For Scheduling:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><?php if(!empty($details['schedule_phone'])) {echo "(".substr($details['schedule_phone'], 0, 3).") ".substr($details['schedule_phone'], 3, 3)."-".substr($details['schedule_phone'],6);} ?></div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Submitted:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><?php echo date('M, d Y @ h:i a', strtotime($details['submitted'])+3600); ?> EST</div>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Status:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<?php 
									if($details['complete'] == 'y') { 
										echo '<div class="text-success borderRow">Completed On '.date('m-d-Y h:i:a', strtotime($details['completed'])+3600).'</div>';	
									} else {
										echo'<div class="text-danger borderRow">Incomplete</div>';
									} 
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Description:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><p><?php echo htmlspecialchars($details['description']); ?></p></div>
							</div>
						</div>
						
						<?php !empty($details['contractor_note']) { ?>
							<div class="row">
								<div class="col-lg-3 col-md-4">
									<div class="borderRow"><b>Original Note:</b></div>
								</div>
								<div class="col-lg-9 col-md-8">
									<div class="borderRow"><p><?php echo htmlspecialchars($details['contractor_note']); ?></p></div>
								</div>
							</div>
						<?php } ?>
						
						<div class="borderRow"><b><i class="fa fa-map-marker"></i>  Rental Address:</b><br class="visible-xs"> <a href="https://www.google.com/maps/place/<?php echo str_replace(' ', '+', $details['address'].' '.$details['city'].' '.$details['state'].' '.$details['zip'] ); ?>+Utica+OH" target="_blank"><?php echo htmlspecialchars($details['address']).' '.htmlspecialchars($details['city']). ', '.htmlspecialchars($details['state']). ' '.htmlspecialchars($details['zip']); ?></a></div>
					</div>
				</div>	
			</div>
				<?php if(!empty($details['attachment'])) { ?>
					<div class="col-md-4">
					
						<img src="<?php echo base_url().'service-uploads/'.$details['attachment']; ?>" class="img-responsive service-request-image" alt="Service Attachment">
						<br>
					</div>
			
				<?php } ?>
					
		
		</div>
		
		<?php 
		
			if(!empty($details['items'])) {
				echo '<div class="panel panel-primary">';
					echo '<div class="panel-heading">';
						echo '<h3 class="panel-title"><b><i class="fa fa-archive"></i> Items Related To This Request</b></h3>';
					echo '</div>';
					echo '<div class="panel-body">';
						echo '<div class="table-responsive">';
							echo '<table class="table table-striped">';
								echo '<thead>';
									echo '<tr>';	
										echo '<td><b>Item Name:</b></td>';
										echo '<td><b>Model#:</b></td>';
										echo '<td><b>Brand:</b></td>';
										echo '<td><b>Serial#:</b></td>';
										echo '<td><b>Service Type:</b></td>';
									echo '</tr>'; 
								echo '</thead>'; 
								echo '<tbody>';
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
										
										echo '<tr>
											<td>
												'.htmlspecialchars($val['desc']).'
											</td>
											<td>
												'.htmlspecialchars($val['modal_num']).'
											</td>
											<td>
												'.htmlspecialchars($val['brand']).'
											</td>
											<td>
												'.htmlspecialchars($val['serial']).'
											</td>
											<td>
												'.$services_array[$val['service_type']].'
											</td>
										</td>';	
									}
								echo '</tbody>';
							echo '</table>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
			
			if(!empty($details['incomplete_requests'])) {
				echo '<div class="panel panel-primary">';
					echo '<div class="panel-heading">';
						echo '<h3 class="panel-title"><b><i class="fa fa-exclamation-triangle"></i> Other Incomplete Request At This Address</b></h3>';
					echo '</div>';
					echo '<div class="panel-body">';
						echo '<div class="table-responsive">';
							echo '<table class="table table-striped">';
								echo '<thead>';
									echo '<tr>';	
										echo '<td><b>Submitted:</b></td>';
										echo '<td><b>Service Type:</b></td>';
										echo '<td class="text-right"><b>View:</b></td>';
									echo '</tr>'; 
								echo '</thead>'; 
								echo '<tbody>';
									foreach($details['incomplete_requests'] as $key => $val) {
										echo '<tr>';
											echo '<td>'.date('m-d-Y h:i a', strtotime($val['submitted'])).' <small>EST</small></td>';
											echo '<td>'.$services_array[$val['service_type']].'</td>';
											echo '<td class="text-right"><a href="'.base_url().'landlords/view-service-request/'.$val['id'].'" class="btn btn-xs btn-primary toolTips" 
										title="View Request"><i class="fa fa-info-circle"></i></a></td>';
										echo '</tr>';
									}
								echo '</tbody>';
							echo '</table>';
						echo '</div>';
								
						
						
							
					echo '</div>';
				echo '</div>';
				
				
				

				
			}
		?>
		
	</div>
</div>



<!-- Add A Note Modal -->
<div class="modal fade" id="add-note" tabindex="-1" role="dialog" aria-labelledby="add-note" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('contractor/add-note-to-request/'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-primary"></i> Add Note To Service Request</h4>
			</div>
			<div class="modal-body">
				<label><span class="text-danger">*</span> Add Note:</label>
				<textarea name="note" class="form-control" required="required"></textarea>
				<label>Add Image <small>(Optional)</small>:</label>
				<input type="file" name="img" class="form-control">
				<input type="hidden" name="id" value="<?php echo $resquest->id; ?>">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary "><i class="fa fa-plus"></i> Add Note</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


<!-- Mark As Complete Modal -->
<div class="modal fade" id="mark-complete" tabindex="-1" role="dialog" aria-labelledby="add-note" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('landlords/service_request_complete/'.$details['request_id']); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-check text-primary"></i> Mark Service Request Complete</h4>
			</div>
			<div class="modal-body">
				<p>If you would like to keep track of the cost for this property add be able to total all the cost that have accumulated with this rental add the cost of the repairs below.</p>
				<hr>
				<div class="row">
					<div class="col-sm-4">
						<label>Cost Of Repairs:</label>
						<input type="text" class="form-control" name="cost">
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
					if(!empty($notes)) {
				
						foreach($notes as $val) {
					
							echo '<ul id="notes-list">';
								if(empty($val->contractor_id)) {
									echo '<li class="landlord">';
									echo '<b><em>'.htmlspecialchars($val->contractor_name).':</em></b>';
								} else {
									echo '<li class="contractor">';
									echo '<b><em>Me:</em></b>';
								}
									
									echo '<h5 class="pull-right"><b>Sent On: '.date('m-d-Y h:i a', strtotime($val->s_timestamp)).'</b></h5><div class="clearfix"></div>';
									echo '<p>'.htmlspecialchars($val->note).'</p>';
									if(!empty($val->contractor_image)) {
										echo '<i class="fa fa-paperclip"></i> <a target="_blank" href="https://network4rentals.com/network/public-images/'.$val->contractor_image.'">'.$val->contractor_image.'</a>';
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

