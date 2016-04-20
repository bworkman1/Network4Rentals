<div class="row">
	<div class="col-sm-12">
		<h2><i class="fa fa-home text-primary"></i> Rental Info</h2>
	</div>
</div>
<hr>
<?php 

	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	if(empty($tenant_info['google_map'])) {
		$tenant_info['google_map'] = '';
	}
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
?>
<div class="row">
	<div class="col-sm-6">
		<p><b>Name:</b><br><?php echo htmlentities($tenant_info['name']); ?></p>
		<p><b>Phone:</b><br><?php echo "(".substr(htmlentities($tenant_info['phone']), 0, 3).") ".substr(htmlentities($tenant_info['phone']), 3, 3)."-".substr(htmlentities($tenant_info['phone']),6); ucwords(htmlentities($tenant_info['phone'])); ?></p>
		<?php if(!empty($tenant_info['alt_phone'])) { ?>
			<p><b>Alt Phone:</b><br>
				<?php echo "(".substr(htmlentities($tenant_info['alt_phone']), 0, 3).") ".substr($tenant_info['alt_phone'], 3, 3)."-".substr(htmlentities($tenant_info['alt_phone']),6); ucwords(htmlentities($tenant_info['alt_phone'])); ?>
			</p>
		<?php } ?>
		<p><b>Email:</b><br><?php echo htmlentities($tenant_info['email']); ?></p>
		<?php if(!empty($tenant_info['lease_upload'])) { ?>
		<p><b>Lease:</b><br><a href="<?php echo base_url().'lease-uploads/'.htmlentities($tenant_info['lease_upload']); ?>" target="_blank"><?php echo htmlentities($tenant_info['lease_upload']); ?></a></p>
		<?php } ?>
	</div>

	<div class="col-sm-6">
		<p><b>Address:</b><br><span class="tenant-address-service-requests"><?php echo htmlentities($tenant_info['rental_address']).' '.htmlentities($tenant_info['rental_city']).', '.htmlentities($tenant_info['rental_state']).'</span> '.htmlentities($tenant_info['rental_zip']); ?></p>
		<div class="row">
			<div class="col-sm-6">
				<p><b>Move In Date:</b><br><?php echo date('m-d-Y', strtotime($tenant_info['move_in'])); ?></p>
			</div>
			<div class="col-sm-6">
				<p><b>Move Out Date:</b><br><?php if($tenant_info['move_out'] != '0000-00-00') {echo date('m-d-Y', strtotime($tenant_info['move_out']));} else {echo 'NA';} ?></p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<p><b>Current Residence:</b><br>
					<?php 
						if($tenant_info['current_residence'] == 'y') {
							echo 'Yes';
						} else {
							echo 'No';
						}
					?>
				</p>
			</div>
			<div class="col-sm-6">
				<p><b>Lease Length:</b><br>
				<?php echo htmlentities($tenant_info['lease']); ?></p>
			</div>
		</div>	
		<div class="row">
			<div class="col-sm-6">
				<p><b>Rent Per Month:</b><br>
					<?php echo '$'.htmlentities($tenant_info['payments']); ?>
				</p>
				<p><b>Deposit:</b><br>
					<?php echo '$'.htmlentities($tenant_info['deposit']); ?>
				</p>
			</div>
			<div class="col-sm-6">
				<p><b>Check List:</b><br>
					<?php  
						if(!empty($tenant_info['checklist_id'])) {
							echo 'Completed <a href="'.base_url().'landlords/view-tenant-checklist/'.$tenant_info['checklist_id'].'" class="btn btn-primary btn-xs pull-right toolTips" title="View Check This Tenants Rental Check List"><i class="fa fa-eye"></i></a>';
						} else {
							echo 'Incomplete';
						}
					?>
				</p>
				<p><b>Rent Due Date:</b><br>
					<?php echo htmlentities($tenant_info['day_rent_due']); ?>
				</p>
			</div>
		</div>	
	</div><!-- Col-sm-6 ends -->
</div>

<hr>
<?php if ($payments_set) { ?>
<div class="well well-sm">
	<h4><i class="fa fa-gears"></i> Payment Settings For This Tenant</h4>
	<?php if (empty($tenant_info['listing_id']) AND $tenant_info['current_residence'] == 'y') { ?>
		<h3 class="text-danger">Default payment settings will populate below once you verify the tenants info.</h3>
	<?php } ?>
	<hr>
	<div id="tanant-payment-settings" class="row">
		<div class="col-md-4">
			<label><b>Accept Online Payments: </b></label>
			<div class="btn-group btn-group-justified payment-settings" role="group" aria-label="...">
				<?php
					if($tenant_info['payments_allowed'] == 'y') {
						echo '<div class="btn-group" role="group">
							<button type="button" class="btn btn-success payments yes" data-id="'.$tenant_info['id'].'" data-type="allowed" data-options="y">Yes</button>
						  </div>
						  <div class="btn-group" role="group">
							<button type="button" class="btn btn-default payments no" data-id="'.$tenant_info['id'].'" data-type="allowed" data-options="n">No</button>
						  </div>';
					} else {
						echo '<div class="btn-group" role="group">
							<button type="button" class="btn btn-default payments yes" data-id="'.$tenant_info['id'].'" data-type="allowed" data-options="y">Yes</button>
						  </div>
						  <div class="btn-group" role="group">
							<button type="button" class="btn btn-danger payments no" data-id="'.$tenant_info['id'].'" data-type="allowed" data-options="n">No</button>
						  </div>';
					}
				?>
			</div>
				
		</div>
		<div class="col-md-4">
			<label><b>Accept Partial Payments:</b></label>
			<div class="btn-group btn-group-justified partial-payments" role="group" aria-label="...">
				<?php
					if($tenant_info['partial_payments'] == 'y') {
						echo '<div class="btn-group" role="group">
							<button type="button" class="btn btn-default btn-success partial-payments yes" data-id="'.$tenant_info['id'].'" data-type="partial" partial-payments yes" data-option="y">Yes</button>
						  </div>
						  <div class="btn-group" role="group">
							<button type="button" class="btn btn-default partial-payments no" data-id="'.$tenant_info['id'].'" data-type="partial" data-option="n">No</button>
						  </div>';
					} else {
						echo '<div class="btn-group" role="group">
							<button type="button" class="btn btn-default partial-payments yes" data-id="'.$tenant_info['id'].'" data-type="partial" data-option="y">Yes</button>
						  </div>
						  <div class="btn-group" role="group">
							<button type="button" class="btn btn-default btn-danger partial-payments no" data-id="'.$tenant_info['id'].'" data-type="partial" data-option="n">No</button>
						  </div>';
					}
				?>
			</div>
		</div>
	</div>
	<br>
	<div class="row">
	<?php
		if($tenant_info['partial_payments'] == 'n') {
			echo '<div class=" fade minPayment-input">';
		} else {
			echo '<div class="fade in minPayment-input">';
		}
	?>
		<div class="col-md-4">
			<label>Min Payment Amount</label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-dollar" style="color: #158cba"></i></div>
				<input type="number" class="form-control minPayment money" data-id="<?php echo $tenant_info['id']; ?>" name="min_payment" value="<?php echo htmlspecialchars ($tenant_info['min_payment']); ?>" required maxlength="5">
			</div>
		</div>
		</div>
		<div class="col-md-4">
			<label>Auto Payment Discount</label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-dollar" style="color: #158cba"></i></div>
				<input type="number" class="form-control discount money" data-id="<?php echo $tenant_info['id']; ?>" name="min_payment" value="<?php echo htmlspecialchars ($tenant_info['auto_pay_discount']); ?>" required maxlength="5">
			</div>
		</div>
		<div class="col-sm-4">
			<label>Online Payment Discount</label>
			<div class="input-group">
				<div class="input-group-addon"><i class="fa fa-dollar" style="color: #158cba"></i></div>
				<input type="number" class="form-control money discountPayment" data-id="<?php echo $tenant_info['id']; ?>" name="discount_payment" value="<?php echo htmlspecialchars ($tenant_info['discount_payment']); ?>" required maxlength="5">
			</div>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-xs-4">
			<button class="btn btn-primary saveButton"><i class="fa fa-save"></i> Save</button>
		</div>
		<div class="col-xs-8">
			<div id="saveFeedback"></div>
		</div>
	</div>
</div>
<?php } else { ?>
	<div class="alert alert-info"><h3 style="margin: 0; color: #fff; font-weight: bold">Online Rental Payments</h3><p>You're either not signed up to accept online payments or you have selected to not allow payments online.</p><a href="https://network4rentals.com/network/landlords/payment-settings" style="text-decoration: none" class="btn btn-primary">Update Payment Settings</a></div>
<?php } ?>
<div class="row">
	<div class="col-sm-3">
		<?php if (empty($tenant_info['listing_id']) AND $tenant_info['current_residence'] == 'y') { ?>
			<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#addToListings"><i class="fa fa-plus"></i> Verify Tenant</button>
		<?php } else { ?>
			<button class="btn btn-default btn-sm btn-block toolTips" disabled><i class="fa fa-plus"></i> Tenant Verified</button>	
		<?php } ?>
	</div>
	<div class="col-sm-3">

		<?php if ($tenant_info['current_residence'] == 'y') { ?>
			<?php if ($tenant_info['listing_id'] != '0') { ?>
				<button class="btn btn-primary btn-sm btn-block" data-toggle="modal" data-target="#editRental"><i class="fa fa-edit"></i> Edit Details</button>
			<?php } else { ?>
				<button class="btn btn-default btn-sm btn-block"><i class="fa fa-edit"></i> Edit Details</button>
			<?php } ?>
		<?php } else { ?>
			<button class="btn btn-default btn-sm btn-block"><i class="fa fa-edit"></i> Edit Details</button>
		<?php } ?>
	</div>
	<div class="col-sm-3">
		<a href="<?php echo base_url(); ?>landlords/message-tenant/<?php echo $tenant_info['id']; ?>" class="btn btn-primary btn-sm btn-block"><i class="fa fa-comments-o"></i> Message Tenant</a>
	</div>
	<div class="col-sm-3">
		<a href="<?php echo base_url(); ?>landlords/view-tenant-payments/<?php echo $tenant_info['id']; ?>" class="btn btn-default btn-sm btn-block" disabled><i class="fa fa-dollar"></i> Rent Payments</a>
	</div>
</div>
<hr>
<?php 
	$this->db->where('sub_admins', $this->session->userdata('user_id'));
	$this->db->or_where('main_admin_id', $this->session->userdata('user_id'));
	$query = $this->db->get('admin_groups');
	if ($query->num_rows() > 0) {
		$switches = $query->result_array();
	} else {
		$switches = '';
	}
	$this->db->select('bName, name');
	$query = $this->db->get_where('landlords', array('id'=>$this->session->userdata('user_id')));
	if($query->num_rows()>0) {
		$row = $query->row();
		$select_bname = $row->bName;
		$select_name = $row->name;
	}
		

?>
<?php if($tenant_info['google_map'] == '54n') { ?>
<div class="row">	
	<div class="col-sm-6">
		<br>
		<div id="map_canvas"></div>
	</div>
	<div class="col-sm-6">
		<br>
		<div id="pano"></div>
	</div>
</div>
<hr>
<?php } ?>

<?php if(!empty($rent_payments)) { ?>


	<h3><i class="fa fa-usd text-primary"></i> Payment Details</h3>
	<hr>
	<div class='table-responsive'>
		<table class="table text-center table-striped">
			<tr>
				<td class="text-left"><b>Amount</b></td>
				<td class="text-center"><b>Paid/Started On</b></td>
				<td class="text-center"><b>Payment Type:</b></td>
				<td class="text-center"><b>Recurring Payment:</b></td>
				<td class="text-right"><b>Add Note</b></td>
			</tr>
			<?php
			
				$payments_array = array();
				foreach($rent_payments as $key => $val) {
					$payments_array[] = $val->amount;
					if($val->id == $this->uri->segment(4)) {
						echo '<tr class="success toolTips" title="Note From Activity Page">';
					} else {
						echo '<tr>';
					}
						echo '<td class="text-left">$'.number_format($val->amount, 2).'</td>';
						echo '<td>'.date('m-d-Y', strtotime($val->paid_on)+3600).'</td>';
						if($val->payment_type == 'E-Check') {
							echo '<td class="text-center">'.$val->payment_type.' <a href="https://account.authorize.net/" target="_blank">Check Status</a></td>';
						} else {
							echo '<td class="text-center">'.$val->payment_type.'</td>';
						}
						if($val->recurring_payment == 'n') {
							echo '<td>No</td>';
						} else {
							echo '<td><span class="text-success">Yes</span></td>';
						}
						
						echo '<td class="text-right"><button class="btn btn-primary btn-xs viewNotes" data-altid="'.$tenant_info['tenant_id'].'" data-payment="'.$val->id.'"><i class="fa fa-file-o"></i> Notes</button></td>';
					echo '</tr>';
				}
			?>
		</table>
	</div>
	<div class="well well-sm text-center">
		<b>Total Rent Paid:</b> $<?php echo number_format(array_sum($payments_array), 2); ?>
	</div>
<?php } ?>


<div class="modal fade" id="payment-notes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open('landlords', array('id'=>'noteForm')); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-file-o text-primary"></i> Payment Notes</h4>
				</div>
				<div class="modal-body">
					<p><small><span class="text-danger">*</span> Notes left on this payment will be seen by both you and the landlord.</small></p>
					<hr>
					<ul id="payment-notes-details">
						
					</ul>
					<hr>
					<h4><i class="fa fa-plus text-primary"></i> Add A Note To This Payment</h4>
					<textarea class="form-control" id="noteDetails" style="min-height: 150px" required name="payment_note"></textarea>
					<br>
					<div class="progress" style="display: none">
						<div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
							<span class="sr-only">20% Complete</span>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<label><i class="fa fa-file text-primary"></i> Attach File: </label>
							<input type="file" class="form-control fileNote" name="attach_file" id="fileNote">
						</div>
					</div>
					<small><span class="text-danger">*</span> Allowed File Types: jpg, png, jpeg, gif, pdf, doc, docx</small>
					<div id="payment_id"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" id="addNewNote" class="btn btn-primary">Add Note</button>
				</div>
			</div>
		</form>
	</div>
</div>

<div class="modal fade" id="dispute" tabindex="-1" role="dialog" aria-labelledby="dispute" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Dispute Payment</h4>
			</div>
			<div class="modal-body">
				<p>Disputing this payment marks the payment as not accepted and notifies the tenant of the dispute.</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary btn-sm" id="addNewNote">Send Dispute</button>
			</div>
		</div>
	</div>
</div>



<?php if (empty($tenant_info['listing_id'])) { ?>
<!-- Add To Listing Modal -->
<div class="modal fade" id="addToListings" tabindex="-1" role="dialog" aria-labelledby="addToListings" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<?php echo form_open_multipart('landlords/link-rental-property/'.$tenant_info['id']); ?>
		<div class="modal-content">
			
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-home text-primary"></i> Add This Rental To My Properties</h4>
			</div>
			<div class="modal-body">
				<p>Adding this rental to your properties will allow you more access to detailed reports concerning this property.</p>
				<?php 
					if(empty($tenant_info['properties'])) {
						echo '<div style="display: none;">';
					}
				?>
					<hr>
					<h4><i class="fa fa-question text-primary"></i> Is This An Existing Property Of Yours?</h4>
					<p>If you have already add this property to your properties please select the address below and we will link it for you.</p>
					<div class="row">
						<div class="col-sm-8">
							<select name="existing" class="form-control existing_properties">
								<option value="">Match Existing Properties...</option>
								<?php
									foreach($tenant_info['properties'] as $key => $val) {
										echo '<option value="'.htmlentities($key).'">'.htmlentities($val).'</option>';
									}
								?>
							</select>
						</div>
					</div>
				<?php 
					if(empty($tenant_info['properties'])) {
						echo '</div>';
					}
				?>
				<div class="add-to-properties">
					<hr>
					<label><span class="text-danger">*</span> Title:</label>
					<input type="text" class="form-control listing-title" name="title" required placeholder="Example: Large Country House" maxlength="50">
					<div class="row">
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Address:</label>
							<input type="text" class="form-control" name="address" value="<?php echo htmlentities($tenant_info['rental_address']); ?>" required maxlength="70">
							<label><span class="text-danger">*</span> City:</label>
							<input type="text" class="form-control" name="city" value="<?php echo htmlentities($tenant_info['rental_city']); ?>" required maxlength="70">
							<div class="row">
								<div class="col-sm-7">
									<label><span class="text-danger">*</span> State:</label>
									<?php
										$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
										echo '<select name="stateAbv" class="form-control" required="">';
										echo '<option value="">Select One...</option>';
										foreach($states as $key => $val) {
											if($key == $tenant_info['rental_state']) {
												echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$val.'</option>';
											}
										}
										echo '</select>';
									?>
								</div>
								<div class="col-sm-5">
									<label><span class="text-danger">*</span> Zip:</label>
									<input type="text" class="form-control" maxlength="10" value="<?php echo htmlentities($tenant_info['rental_zip']); ?>" name="zipCode" required>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="row">
								<div class="col-sm-4">
									<label><span class="text-danger">*</span> Baths:</label>
									<input type="text" class="form-control listing-baths" name="bathrooms" required maxlength="4">
								</div>
								<div class="col-sm-4">
									<label><span class="text-danger">*</span> Beds:</label>
									<input type="text" class="form-control listing-beds" name="bedrooms" maxlength="3" required>
								</div>
								<div class="col-sm-4">
									<label>Sq. Feet:</label>
									<input type="text" class="form-control listing-sqfeet" name="sqFeet" maxlength="6">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<label><span class="text-danger">*</span> Rent:</label>
									<input type="text" class="form-control money" maxlength="5" value="<?php echo htmlentities($tenant_info['payments']); ?>" name="price" required>
								</div>
								<div class="col-sm-6">
									<label><span class="text-danger">*</span> Deposit:</label>
									<input type="text" class="form-control listing-deposit money" maxlength="5" name="deposit" value="<?php echo htmlentities($tenant_info['deposit']); ?>" required>
								</div>
							</div>
						</div>
					</div>
					<label><span class="text-danger">*</span> Property Description:</label>
					<textarea class="form-control listing-desc" name="desc" required></textarea>
					<hr>
					<h3><i class="fa fa-reorder"></i> Amenities</h3>
				<div class="row">
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-3"><input type="checkbox" name="laundry_hook_ups" id="amenities-3" value="y" <?php if($listing['laundry_hook_ups'] == 'y') {echo 'checked';} ?>/> Clothes Washer / Dryer Hook-Ups</label>
						</div>
						<div class="checkbox">
							<label for="amenities-5"><input type="checkbox" name="off_site_laundry" id="amenities-5" value="y" <?php if($listing['off_site_laundry'] == 'y') {echo 'checked';} ?>/> Offsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-6"><input type="checkbox" name="on_site_laundry" id="amenities-6" value="y" <?php if($listing['on_site_laundry'] == 'y') {echo 'checked';} ?>/> Onsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-7"><input type="checkbox" name="basement" id="amenities-7" value="y" <?php if($listing['basement'] == 'y') {echo 'checked';} ?>/> Basement</label>
						</div>
						<div class="checkbox">
							<label for="amenities-8"><input type="checkbox" name="single_lvl" id="amenities-8" value="y" <?php if($listing['single_lvl'] == 'y') {echo 'checked';} ?>/> Single Level Floor Plan</label>
						</div>
						<div class="checkbox">
							<label for="amenities-9"><input type="checkbox" name="shed" id="amenities-9" value="y" <?php if($listing['shed'] == 'y') {echo 'checked';} ?>/> Storage Shed</label>
						</div>
						<div class="checkbox">
							<label for="amenities-10"><input type="checkbox" name="park" id="amenities-10" value="y" <?php if($listing['park'] == 'y') {echo 'checked';} ?>/> Near A Park</label>
						</div>	
						<div class="checkbox">
							<label for="amenities-12"><input type="checkbox" name="inside_city" id="amenities-12" value="y" <?php if($listing['inside_city'] == 'y') {echo 'checked';} ?>/> Within City Limits</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-13"><input type="checkbox" name="outside_city" id="amenities-13" value="y" <?php if($listing['outside_city'] == 'y') {echo 'checked';} ?>/> Outside City Limits</label>
						</div>		
					</div>
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-14"><input type="checkbox" name="deck_porch" id="amenities-14" value="y" <?php if($listing['deck_porch'] == 'y') {echo 'checked';} ?>/> Deck / Porch</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-15"><input type="checkbox" name="large_yard" id="amenities-15" value="y" <?php if($listing['large_yard'] == 'y') {echo 'checked';} ?>/> Large Yard</label>
						</div>
						<div class="checkbox">
							<label for="amenities-16"><input type="checkbox" name="fenced_yard" id="amenities-16" value="y" <?php if($listing['fenced_yard'] == 'y') {echo 'checked';} ?>/> Fenced Yard</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-17"><input type="checkbox" name="partial_utilites" id="amenities-17" value="y" <?php if($listing['partial_utilites'] == 'y') {echo 'checked';} ?>/> Some Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-18"><input type="checkbox" name="all_utilities" id="amenities-18" value="y" <?php if($listing['all_utilities'] == 'y') {echo 'checked';} ?>/> Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-19"><input type="checkbox" name="appliances" id="amenities-19" value="y" <?php if($listing['appliances'] == 'y') {echo 'checked';} ?>/> Appliances Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-20"><input type="checkbox" name="furnished" id="amenities-20" value="y" <?php if($listing['furnished'] == 'y') {echo 'checked';} ?>/> Fully Furnished </label>
						</div>		
						<div class="checkbox">
							<label for="amenities-21"><input type="checkbox" name="pool" id="amenities-21" value="y" <?php if($listing['pool'] == 'y') {echo 'checked';} ?>/> Pool</label>
						</div>
						<div class="checkbox">
							<label for="amenities-11"><input type="checkbox" name="shopping" id="amenities-11" value="y" <?php if($listing['shopping'] == 'y') {echo 'checked';} ?>/> Near Shopping / Entertainment</label>
						</div>
					</div>
				</div>
			
					<hr>
					<h3>Add Images To This Listing</h3>
					<p><span class="text-danger"><i class="fa fa-asterisk"></i></span> You can add up to five images to your property so that when it becomes vacant it will show up under your public link and become searchable on N4R website. The featured image is what image the visitor will first see when they are looking for listings.</p>
					<div class="row">
						<div class="col-sm-8">
							<p><b>Image:</b></p>
						</div>
						<div class="col-sm-4 text-center">
							<p><b>Featured Image:</b></p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<p><label>Image 1:</label><br>
							<input type="file" class="img-attachment  form-control" name="file1"></p>
						</div>
						<div class="col-sm-4 text-center">
							<br>
							<input type="radio" name="featured_image" value="1">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8">
							<p><label>Image 2:</label><br>
							<input type="file" class="img-attachment form-control" name="file2"></p>
						</div>
						<div class="col-sm-4 text-center">
							<br>
							<input type="radio" name="featured_image" value="2">
						</div>
					</div>					
					<div class="row">
						<div class="col-sm-8">
							<p><label>Image 3:</label><br>
							<input type="file" class="img-attachment form-control" name="file3"></p>
						</div>
						<div class="col-sm-4 text-center">
							<br>
							<input type="radio" name="featured_image" value="3">
						</div>
					</div>		
					<div class="row">
						<div class="col-sm-8">
							<p><label>Image 4:</label><br>
							<input type="file" class="img-attachment form-control" name="file4"></p>
						</div>
						<div class="col-sm-4 text-center">
							<br>
							<input type="radio" name="featured_image" value="4">
						</div>
					</div>		
					<div class="row">
						<div class="col-sm-8">
							<p><label>Image 5:</label><br>
							<input type="file" class="img-attachment form-control" name="file5"></p>
						</div>
						<div class="col-sm-4 text-center">
							<br>
							<input type="radio" name="featured_image" value="5">
						</div>
					</div>							
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
			</div>
			
		</div><?php echo form_close(); ?>
	</div>
</div>
<?php } ?>


<!-- Edit Details Modal -->
<div class="modal fade" id="editRental" tabindex="-1" role="dialog" aria-labelledby="editRental" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/edit-rental-info/'.$tenant_info['id']); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit text-primary"></i> Edit Rental Details</h4>
			</div>
			
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<label><span class="text-danger">*</span> Address:</label>
						<input type="text" class="form-control" name="address" value="<?php echo htmlentities($tenant_info['rental_address']); ?>" required="required" maxlength="70">
						<label><span class="text-danger">*</span> City:</label>
						<input type="text" class="form-control" required="required" value="<?php echo htmlentities($tenant_info['rental_city']); ?>" name="city" maxlength="70">
						<div class="row">
							<div class="col-sm-6">
								<?php
									echo form_label('<span class="text-danger">*</span> State:');			
									$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
									echo '<select name="state" class="form-control" required="" maxlength="50">';
									echo '<option value="">Select One...</option>';
									foreach($states as $key => $val) {
										if($key == $tenant_info['rental_state']) {
											echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
									echo '</select>';
								?>
							</div>
							<div class="col-sm-6">
								<label><span class="text-danger">*</span> Zip:</label>
								<input type="text" class="form-control" required="required" value="<?php echo htmlentities($tenant_info['rental_zip']); ?>" name="zip" maxlength="10">
							</div>
						</div>
						<label>Lease Upload</label>
						<input type="file" name="lease" class="attachment form-control">
					</div>
		
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-6">
								<label><span class="text-danger">*</span> Move In Date:</label>
								<input type="text" class="form-control dateField datepicker" readonly placeholder="mm/dd/yyyy" required="required" name="move_in" maxlength="10" value="<?php echo date('m-d-Y', strtotime($tenant_info['move_in'])); ?>">
								<hr>
							</div>
							<div class="col-sm-6">
								<label>Move Out Date:</label>
								<?php 
									if($tenant_info['move_out'] != '0000-00-00') {									
										$moveOut = date('m/d/Y', strtotime($tenant_info['move_out'])); 
									} else {
										$moveOut = '';
									}
								?>
								<input type="text" class="form-control dateField datepicker" maxlength="10" placeholder="mm/dd/yyyy" name="move_out" value="<?php echo $moveOut; ?>">
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<?php
									echo form_label('<span class="text-danger">*</span> Lease Length:');
									echo '<select class="form-control" name="lease" required="">';
									$lenths_array = array('Month To Month', '3 Months', '6 Months', '9 Months', '1 Year', '2 Year', '3 Year');
									echo '<option value="">Select One..</option>';
									foreach($lenths_array as $val) {
										if($val == $tenant_info['lease']) {
											echo '<option selected="selected">'.htmlentities($val).'</option>';
										} else {
											echo '<option>'.htmlentities($val).'</option>';
										}
									}
									echo '</select>';
								?>
							</div>
							<div class="col-sm-6">
								<label><span class="text-danger">*</span> Rent Per Month:</label>
								<input type="text" class="form-control" value="<?php echo htmlentities($tenant_info['payments']); ?>" required="required" name="payments" maxlength="5">
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<label><span class="text-danger">*</span> Deposit:</label>
								<input type="text" class="form-control numberOnly" value="<?php echo htmlentities($tenant_info['deposit']); ?>" required="required" name="deposit" maxlength="5">
							</div>
							<div class="col-sm-6">
								<label><span class="text-danger">*</span> Rent Due Date:</label>
								<input type="text" class="form-control numberOnly" value="<?php echo htmlentities($tenant_info['day_rent_due']); ?>" required="required" name="day_rent_due" maxlength="2">	
							</div>
						</div>

					</div>
				</div>
				<div class="row">
					<?php 
					
						if(!empty($switches)) {
							echo '<div class="col-sm-6">';
							echo '<label><b>ReAssign To A Manager:</b></label>';
					
							echo '<select class="form-control groupPicker" name="group_id">';
								echo '<option value="0">I manage this property</option>';
								for($i=0;$i<count($switches);$i++) {
									if($tenant_info['group_id'] == $switches[$i]['id']) {
										echo '<option value="'.$switches[$i]['id'].'" selected="selected">'.$switches[$i]['sub_b_name'].'</option>';
									} else {
										echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
									}
								}
							echo '</select>';
						
							echo '</div>';
						}
					?>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary submitTenatDetails">Update</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>