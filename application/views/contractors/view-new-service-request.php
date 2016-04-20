<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-fw- fa-lg"></i> Error:</b> '.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-fw fa-lg"></i> Success:</b>'.$this->session->flashdata('success').'</div>';
	}
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');

?>
<div id="service-request-details">
	<div class="widget-body">
		<div class="row">
			<div class="col-md-6">
				<h4><b>Service Request</b></h4>
				<p><b>Submitted:</b> <?php echo date('M, d Y @ h:i a', strtotime($request->contractor_received)+3600); ?> EST</p>
				<p><b>Status:</b> 
					<?php 
						if($request->complete == 'y') { 
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
					<li><a href="<?php echo base_url('print-handler/print-service-request/'.$request->id); ?>"><i class="fa fa-print"></i> Print Request</a></li>
					<li><a href="<?php echo base_url('print-handler/print-service-request/'.$request->id); ?>"><i class="fa fa-save"></i> Save As PDF</a></li>
					<?php if($details['complete'] == 'n') { ?>
						<li><a href="" data-toggle="modal" data-target="#forward-request"><i class="fa fa-reply"></i> Forward Service Request</a></li>
					<?php } ?>
					<li><a href="" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a></li>
					<li><a href="#" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> View Notes <span class="badge badge-info pull-right"><?php echo count($notes); ?></span></a></li>
					<?php if($request->complete == 'n') { ?>
						<li><a href="#" data-toggle="modal" data-target="#addItems"><i class="fa fa-plus"></i> Add Item <span class="badge badge-info pull-right"><?php echo count($items); ?></span></a></li>
					<?php } ?>
					
				</ul>
			</div>
			</div>
		</div>
		<?php echo $error; ?>
		<hr>
		
		<?php 
			
			echo '<div class="alert alert-info"><b><i class="fa fa-info-circle fa-lg"></i> Notice:</b> Create invoice for this request and allow landlord to pay online. <button data-toggle="modal" data-target="#createInvoice" class="btn btn-primary btn-sm pull-right">Create Invoice</a></div>';
			
		?>
		
		<?php if($request->page_submit == 'y') { ?>
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title panel-with-button"><b>Contact Details:</b></h3>
				</div>
				<div class="panel-body">
					<div class="col-md-6">
						<div class="borderRow"><b>Name:</b></div>
						<div class="borderRow"><?php echo htmlspecialchars($request->name); ?></div>
						<?php if(!empty($request->schedule_phone)) { ?>
							<div class="borderRow"><b>Phone:</b></div>
							<div class="borderRow">
								<?php 
									echo '<a href="tel:'.$request->schedule_phone.'">';
									echo "(".substr($request->schedule_phone, 0, 3).") ".substr($request->schedule_phone, 3, 3)."-".substr($request->schedule_phone,6); 
									echo '</a>';
								?>
							</div>
						<?php } ?>
					</div>
					<div class="col-md-6">
						<div class="borderRow"><b>Address:</b></div>
						<div class="borderRow"><?php echo htmlspecialchars($request->address); ?></div>
						<div class="borderRow"><b>Email:</b></div>
						<div class="borderRow"><a href="mailto:<?php echo htmlspecialchars($request->email); ?>"><?php echo htmlspecialchars($request->email); ?></a></div>
					</div>
				</div>
			</div>
		<?php } ?>
		
		<div class="row"> 
			<div class="col-lg-6">
				<?php if(!empty($landlord)) { ?>
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Landlord Details:</b></h3>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div class="borderRow"><b>Name:</b></div>
								<div class="borderRow"><?php echo htmlspecialchars($landlord->name); ?></div>
								<?php if(!empty($landlord->phone)) { ?>
									<div class="borderRow"><b>Phone:</b></div>
									<div class="borderRow">
										<?php 
											echo '<a href="tel:'.$landlord->phone.'">';
											echo "(".substr($landlord->phone, 0, 3).") ".substr($landlord->phone, 3, 3)."-".substr($landlord->phone,6); 
											echo '</a>';
										?>
									</div>
								<?php } ?>
							</div>
							<div class="col-md-6">
								<div class="borderRow"><b>Business Name:</b></div>
								<div class="borderRow"><?php echo htmlspecialchars($landlord->bName); ?></div>
								<div class="borderRow"><b>Email:</b></div>
								<div class="borderRow"><a href="mailto:<?php echo htmlspecialchars($landlord->email); ?>"><?php echo htmlspecialchars($landlord->email); ?></a></div>
							</div>
						</div>	
	
						<div class="borderRow"><b><i class="fa fa-map-marker"></i> Mailing Address:</b> <br class="visible-xs"><?php echo htmlspecialchars($landlord->address).',  '.$landlord->city.' '.htmlspecialchars($landlord->state).' '.htmlspecialchars($landlord->zip); ?></div>
					</div>
				</div>
				<?php } ?>
			</div>
			
			<div class="col-lg-6">
				<?php if(!empty($renter)) { ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							<h3 class="panel-title"><b>Renter Details:</b></h3>
						</div>
						<div class="panel-body">
							<div class="borderRow"><b>Name: </b><?php if(!empty($renter->name)) {echo htmlspecialchars($renter->name);} else {echo 'NA';} ?></div>
							
							<div class="borderRow"><b>Phone:</b> 
							<?php 
								if(!empty($renter->phone)) {
									echo '<a href="tel:'.$renter->phone.'">';
										echo "(".substr($renter->phone, 0, 3).") ".substr($renter->phone, 3, 3)."-".substr($renter->phone,6);
									echo '</a>';
								} else {
									echo 'NA';
								} 
							?>
							</div>
							<div class="borderRow"><b>Email:</b> <a href="mailto:<?php echo htmlspecialchars($renter->email); ?>"><?php echo htmlspecialchars($renter->email); ?></a></div>
							
							<div class="borderRow"><b><i class="fa fa-map-marker"></i>  Rental Address:</b><br class="visible-xs"> 
							<a href="https://www.google.com/maps/place/<?php echo str_replace(' ', '+', $rental->rental_address.' '.$rental->rental_city.' '.$rental->rental_state.' '.$rental->rental_zip ); ?>" target="_blank"><?php echo htmlspecialchars($rental->rental_address).' '.htmlspecialchars($rental->rental_city). ', '.htmlspecialchars($rental->rental_state). ' '.htmlspecialchars($rental->rental_zip); ?></a></div>
						</div>
					</div>			
				<?php } ?>
			</div>
		</div>
		
		
		
		<?php if(!empty($supply) || !empty($ad_post) ) { ?>
			<?php if(!empty($supply)) { ?>
				<div id="supplyHouses" class="well">
					<h4 style="border-bottom: 1px solid #ccc"><b>Need Supplies for the job?</b></h4> 
					<div class="row">
						<?php 
							foreach($supply as $row) { 
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
				if(!empty($request->attachment)) { 
					echo '<div class="col-md-8">';
				} else {
					echo '<div class="col-md-12">';
				}
			?>
			
				<div class="panel panel-success">
					<div class="panel-heading">
						<h3 class="panel-title panel-with-button pull-left"><b>Service Request Details:</b></h3>
						<div class="btn-group hidden-print pull-right" style="margin-top: 4px">
							<button type="button" class="btn btn-info btn-sm">Options</button>
							<button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							</button>
							<ul id="serviceRequestOptions21" class="dropdown-menu">
								<?php if($request->complete == 'n') { ?>
									<li><a href="#" data-toggle="modal" data-target="#mark-complete"><i class="fa fa-check"></i> Mark As Complete</a></li>
								<?php } ?>
								<li><a href="<?php echo base_url('print-handler/print-service-request/'.$request->id); ?>"><i class="fa fa-print"></i> Print Request</a></li>
								<li><a href="<?php echo base_url('print-handler/print-service-request/'.$request->id); ?>"><i class="fa fa-save"></i> Save As PDF</a></li>
								<?php if($details['complete'] == 'n') { ?>
									<li><a href="" data-toggle="modal" data-target="#forward-request"><i class="fa fa-reply"></i> Forward Service Request</a></li>
								<?php } ?>
								<li><a href="" data-toggle="modal" data-target="#add-note"><i class="fa fa-file"></i> Add A Note</a></li>
								<li><a href="#" data-toggle="modal" data-target="#view-notes"><i class="fa fa-file"></i> View Notes <span class="badge badge-info pull-right"><?php echo count($notes); ?></span></a></li>
								<?php if($request->complete == 'n') { ?>
								<li><a href="#" data-toggle="modal" data-target="#addItems"><i class="fa fa-plus"></i> Add Item <span class="badge badge-info pull-right"><?php echo count($items); ?></span></a></li>
								<?php } ?>
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
									echo '<h4><b>Scheduled for '.date('m-d-Y h:i a', strtotime($isScheduled->start)).'</b></h4>';
								} else {
									echo '<h4 class="pull-left"><b>Request has not been scheduled yet</b></h4><a href="#" data-toggle="modal" data-target="#addEvent" class="btn btn-success pull-right">Add to Schedule</a>';
									echo '<div class="clearfix"></div>';
								}
							?>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Service Type:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><?php echo $services_array[htmlspecialchars($request->service_type)]; ?></div>
							</div>
						</div>	
						
						<div class="row">
							<div class="col-lg-3 col-md-4">	
								<div class="borderRow"><b>Permission To Enter:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<?php
									$request->enter_permission = strtolower($request->enter_permission);
									if($request->enter_permission == 'y' || $request->enter_permission == 'yes') {
										echo '<div class="text-success borderRow">Yes</div>';
									} else {
										echo '<div class="text-success borderRow">Call First</div>';
									}
								?>
							</div>
						</div>
						
						<?php if($request->schedule_phone!='na') { ?>
							<div class="row">
								<div class="col-lg-3 col-md-4">	
									<div class="borderRow"><b>Phone# For Scheduling:</b></div>
								</div>
								<div class="col-lg-9 col-md-8">
									<div class="borderRow">
									<?php 
										echo '<a href="tel:'.$request->schedule_phone.'">';
											echo "(".substr($request->schedule_phone, 0, 3).") ".substr($request->schedule_phone, 3, 3)."-".substr($request->schedule_phone,6); 
										echo '</a>';
									?>
								</div>
								</div>
							</div>
						<?php } ?>
						
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Submitted:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><?php echo date('M, d Y @ h:i a', strtotime($request->submitted)+3600); ?> EST</div>
							</div>
						</div>
					
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Status:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<?php 
									if($details['complete'] == 'y') { 
										echo '<div class="text-success borderRow">Completed On '.date('m-d-Y h:i:a', strtotime($request->completed)+3600).'</div>';	
									} else {
										echo'<div class="text-danger borderRow">Incomplete</div>';
									} 
								?>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-3 col-md-4">
								<div class="borderRow"><b>Description from resident:</b></div>
							</div>
							<div class="col-lg-9 col-md-8">
								<div class="borderRow"><p><?php echo htmlspecialchars($request->description); ?></p></div>
							</div>
						</div>
						
						<?php if(!empty($request->contractor_note)) { ?>
							<div class="row">
								<div class="col-lg-3 col-md-4">
									<div class="borderRow"><b>Instruction from Landlord:</b></div>
								</div>
								<div class="col-lg-9 col-md-8"> 
									<div class="borderRow"><p><?php echo htmlspecialchars($request->contractor_note); ?></p></div>
								</div>
							</div>
						<?php } ?>
						
						<?php 
				
							if(!empty($request->address)) {
								echo '<div class="borderRow"><b><i class="fa fa-map-marker"></i>  Rental Address:</b> <br class="visible-xs">';
									echo ' <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $request->address).'" target="_blank">';
										echo htmlspecialchars($request->address);
									echo '</a>';
								echo '</div>';
							} else {

								echo '<div class="borderRow"><b><i class="fa fa-map-marker"></i>  Rental Address: </b><br class="visible-xs">';
									echo ' <a href="https://www.google.com/maps/place/'.str_replace(' ', '+', $rental->rental_address.' '.$rental->rental_city.' '.$rental->rental_state).'" target="_blank">';
										echo $rental->rental_address.' '.$rental->rental_city.' '.$rental->rental_state;
									echo '</a>';
								echo '</div>';
							}
						?>
					</div>
				</div>	
				
				
				
			</div>
				<?php if(!empty($request->attachment)) { ?>
					<div class="col-md-4">
						<img src="<?php echo base_url().'service-uploads/'.$request->attachment; ?>" class="img-responsive service-request-image" alt="Service Attachment">
						<br>
					</div>
			
				<?php } ?>
					
		
		</div>
		<?php if(!empty($invoices)) { ?>
					<div class="panel panel-success">
						<div class="panel-heading">
							<h3 class="panel-title panel-with-button"><b><i class="fa fa-inbox"></i> Invoices:</b></h3>
						</div>
						<div class="panel-body">
							<?php echo form_open('contractor/mark-as-paid'); ?>
								<div class="table-responsive">
									<table class="table table-striped table-condensed">
										<thead>
											<tr>
												<th>Select</th>
												<th>Amount</th>
												<th>Attachment</th>
												<th>Email Sent</th>
												<th>Created</th>
												<th>Paid</th>
												<th>View</th>
											</tr>
										</thead>
										<tbody>
											<?php 
												foreach($invoices as $row) {
													echo '<tr>';
														echo '<td>';
														if($row->paid=='n') {
															echo '<input name="paid[]" type="checkbox" value="'.$row->id.'"></td>';
														}
														echo '</td>';
														echo '<td>$'.number_format(htmlspecialchars($row->amount), 2).'</td>';
														echo '<td>';
															if(!empty($row->file)) {
																echo '<a href="'.base_url($row->file).'" download title="'.$row->file.'"><i class="fa fa-link"></i> Download</a>';
															} else {
																echo 'NA';
															}
														echo '</td>';
														echo '<td>'.htmlspecialchars($row->email).'</td>';
														echo '<td>'.date('m-d-Y h:i a', strtotime($row->created)).'</td>';
														echo '<td>';
															if($row->paid=='y') {
																echo 'Paid';
															} else {
																echo 'Open';
															}
														echo '</td>';
														
														echo '<td><a href="'.base_url('contractor/view-invoice/'.$row->id).'" class="btn btn-primary btn-sm">View </a></td>';
													echo '</tr>';
				
												} 
											?>
										</tbody>
									</table>
								</div>
								<br>
								<button type="submit" class="btn btn-primary">Mark Checked Invoices As Paid</button>
							</form>
						</div>
					</div>
					
				<?php } ?>
		<?php 
		
			if(!empty($items)) {
				echo '<div class="panel panel-success">';
					echo '<div class="panel-heading">';
						echo '<h3 class="panel-title"><b><i class="fa fa-archive"></i> Items Related To This Request</b></h3>';
					echo '</div>';
					echo '<div class="panel-body">';
						echo '<div class="table-responsive">';
							echo '<table class="table table-striped">';
								echo '<thead>';
									echo '<tr>';	
										echo '<td><b>Image</b></td>';
										echo '<td><b>Item Name:</b></td>';
										echo '<td><b>Model#:</b></td>';
										echo '<td><b>Brand:</b></td>';
										echo '<td><b>Serial#:</b></td>';
										echo '<td><b>Service Type:</b></td>';
									echo '</tr>'; 
								echo '</thead>'; 
								echo '<tbody>';
									foreach($items as $val) {
										if(!empty($val->image)) {
											$img = '<a href="'.base_url($val->image).'" data-group="gallery" class="lightbox" title="'.$val->desc.'"><img src="'.base_url($val->image).'" data-group="gallery" class="img-responsive" height="40" width="40"></a>';
										} else {
											$img = '';
										}
										echo '<tr>';
											
											echo '<td style="line-height: 50px">'. $img .'</td>';
											echo '<td style="line-height: 50px">'. $val->desc .'</td>';
											echo '<td style="line-height: 50px">'. $val->modal_num .'</td>';
											echo '<td style="line-height: 50px">'. $val->brand .'</td>';
											echo '<td style="line-height: 50px">'. $val->serial .'</td>';
											echo '<td style="line-height: 50px">'. $services_array[$val->service_type] .'</td>';
								
										echo '</tr>';
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
					<label>Contractor Instructions:</label>
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
			<?php echo form_open_multipart('contractor/add-note-to-request/'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file text-success"></i> Add Note To Service Request</h4>
			</div>
			<div class="modal-body">
				<label><span class="text-danger">*</span> Add Note:</label>
				<textarea name="note" class="form-control" required="required"></textarea>
				<label>Add Image <small>(Optional)</small>:</label>
				<input type="file" name="img" class="form-control">
				<input type="hidden" id="requestID" name="id" value="<?php echo $request->id; ?>">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default " data-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-success "><i class="fa fa-plus"></i> Add Note</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<!-- Mark As Complete Modal -->
<div class="modal fade" id="mark-complete" tabindex="-1" role="dialog" aria-labelledby="add-note" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('contractor/mark-service-request-complete/'.$request->id); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-check text-success"></i> Mark Service Request Complete</h4>
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
				<button type="submit" class="btn btn-success "><i class="fa fa-check"></i> Mark Complete</button>
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
									echo '<b><em>'.htmlspecialchars($landlord->name).':</em></b>';
								} elseif($val->visibility == 3) {	
									echo '<li class="renter">';
									echo '<b><em>Tenant:</em></b>';
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
					<?php 
						if(!empty($request->address)) {
							$addy = $request->address;
						} else {
							$addy = htmlspecialchars($rental->rental_address).' '.htmlspecialchars($rental->rental_city). ', '.htmlspecialchars($rental->rental_state); 
						}
					?>
					
					<input type="text" id="eventTitle" name="title" placeholder="title" class="form-control" value="SR: <?php echo $addy; ?>" required>
			
			
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
							<input type="text" name="endTime" id="createTaskEndTime" class="form-control timeMask" value="<?php echo date('h:i'); ?>" required>
						</div>
						<div class="col-sm-4 col-xs-6">
							<select id="endAm" name="endAm" class="form-control" required>
								<?php 
									$options = array('am', 'pm');
									foreach($options as $val) {
										if(date('a') == $val) {
											echo '<option value="'.$val.'" selected>'.strtoupper($val).'</option>';
										} else {
											echo '<option value="'.$val.'">'.strtoupper($val).'</option>';
										}
									}
								?>
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

<div class="modal fade" id="addItems" tabindex="-1" role="dialog" aria-labelledby="addItems" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php echo form_open('contractor/add-item-to-property/'.$request->id); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-link text-primary"></i> Attach Item An Item To This Home</h4>
			</div>
			<div class="modal-body">
				<p>Attaching an item to this home will help your repair man in determining which parts they may need for a job. A example of this would be if you attached a refrigerator to this rental property and added all the information below. When you receive a service request from one of your tenants that has to do with appliances, at the bottom of your service request you will see a list of possible items that refer to appliances.</p>
				<hr>
				<h4><i class="fa fa-plus text-primary"></i> Add Item</h4>
				<div class="row">
					<div class="col-sm-6">
						<label><span class="text-danger">*</span> Item Name:</label>
						<input type="text" class="form-control" maxlength="50" name="desc" placeholder="Example: Refrigerator">
						<label>Modal #:</label>
						<input type="text" class="form-control" maxlength="50" name="modal_num">
						<label>Serial #:</label>
						<input type="text" class="form-control" maxlength="50" name="serial">
					</div>
					<div class="col-sm-6">
						<label>Brand:</label>
						<input type="text" class="form-control" maxlength="50" name="brand">
						<label><span class="text-danger">*</span> Service Type:</label>
						<?php 
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
							echo "<select name='service_type' id='serviceType' class='form-control' required='required'>";
							echo '<option value="">Choose One...</option>';
							foreach($services_array as $key => $val) {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
							echo "</select>";
						?>
					</div>
				</div>
				<h4>Items Attached To This Rental</h4>
				<ul id="listing_items">
					
				</ul>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Save changes</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


<div class="modal fade" id="createInvoice" tabindex="-1" role="dialog" aria-labelledby="createInvoice" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-list text-primary"></i> Create Invoice</h4>
			</div>
			<div class="modal-body">
				<form action="#" id="create-invoice" method="post" accept-charset="utf-8" data-type="contractor" enctype="multipart/form-data" _lpchecked="1" data-url="<?php echo current_url(); ?>">					
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><span class="text-danger">*</span> Name:</label>
							<input type="text" name="name" class="form-control" maxlength="60" value="<?php echo $landlord->name; ?>" required="">
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label><span class="text-danger">*</span> Amout:</label>
									<input type="text" name="amount" class="form-control money" maxlength="15" value="" required="">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label><span class="text-danger">*</span> Invoice Number:</label>
									<input type="text" name="invoice" class="form-control" value="" maxlength="25" required="">
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Payee Email: <small class="text-primary">recieve invoice to email</small></label>
							<input type="email" name="email" class="form-control" value="<?php echo $landlord->email; ?>" maxlength="70">
						</div>
						<div class="form-group">
							<label>Attach Invoice File:</label>
							<input type="file" id="file" name="file" class="form-control">
						</div>
						<div class="form-group">
							<label>
								<input type="checkbox" name="send_email" value="y">
								Send Invoice By Email:
							</label>
						</div>
						
						<input type="hidden" name="ref_id" value="<?php echo $reqeust->id; ?>">
						<input type="hidden" name="payee_id" value="<?php echo $landlord->id; ?>">
					</div>
					<div class="col-md-6">
						<label>Note:</label> 
						<textarea class="form-control" name="note" style="height: 200px" maxlength="500"></textarea>
					</div>
					
				</div>
				
				<div class="row">
					<div class="col-md-6">
				
						<div id="feedback"></div>
					</div>
				</div>
				<button type="submit" id="submit" class="btn btn-primary btn-lg">Create Invoice</button>
			</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				
			</div>

		</div>
	</div>
</div>


<input type="hidden" id="link" value="<?php echo current_url(); ?>">
