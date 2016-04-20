<div class="row">
	<div class="col-sm-12">
		<h2><i class="text-primary fa fa-users"></i> Association Members</h2>
	</div>
</div>
<hr>

<?php
	$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle fa-3x pull-left"></i> <h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle fa-3x pull-left"></i> <h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><i class="fa fa-check fa-3x pull-left"></i> <h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
?>

<div class="row">
	<div class="col-md-6">
	
		<?php
			if(!empty($members)) {
				$sorted_array = array();
				foreach($members as $key => $val) {
					if(isset($sorted_array[$val->member_type])) {
						$sorted_array[$val->member_type][sizeof($sorted_array[$val->member_type])] = $val;
					} else {
						$sorted_array[$val->member_type][0] = $val;
					}
				}
	
				foreach($sorted_array as $key => $val) {
					echo '<div class="panel panel-primary">
							<div class="panel-heading">	
								<i class="fa fa-users stylish-icon"></i> '.$key.' <button class="pull-right addMember btn btn-warning btn-sm toolTips" title="Add Member"  data-category="'.$key.'" data-toggle="modal" data-target="#addMemeber"><i class="fa fa-plus"></i></button>
							</div>
							<ul id="sortable_members" style="min-height: 200px">';
				
					foreach($val as $k => $v) {
						if(!empty($v)) {
							echo '<li class="ui-state-default" data-memid="'.$v->id.'">';
								if($v->active == 'y') {
									echo '<i class="fa fa-circle text-success toolTips" title="User Is Active"></i> ';
								} else {
									echo '<i class="fa fa-circle text-danger toolTips" title="User Is In-Active"></i> ';	
								}
								echo ' '.$v->name.' | '.$v->position;					
								echo '<button class="pull-right memberSettings toolTips" data-id="'.$v->id.'" title="View/Edit User Details"><i class="fa fa-cog fa-fw"></i> </button>';
								if($v->registered_landlord_id>0) {
									echo '<span class="label label-success pull-right" style="margin: 5px 5px 0 0">N4R User</span>';
								} else {
									echo '<span class="label label-danger pull-right" style="margin: 5px 5px 0 0">Not N4R User</span>';
								}
								if($v->registered_landlord_id > 0) {
									if($v->accepted == 'y') {
										echo '<span class="label label-success pull-right" style="margin: 5px 5px 0 0">Accepted</span>';
									} else {
										echo '<span class="label label-info pull-right" style="margin: 5px 5px 0 0">Pending Approval</span>';
									}
								}
								
								
								echo '<div class="clearfix"></div>';
							echo '</li>';
						} else {
							echo '<li>You have no members yet</li>';
						}
						
					}
					echo '</ul></div>';
				}
			} else {
				echo '<div class="panel panel-primary">
						<div class="panel-heading">	
							<i class="fa fa-users stylish-icon"></i> Add New Members<button class="pull-right addMember btn btn-warning btn-sm toolTips" title="Add Member"  data-category="" data-toggle="modal" data-target="#addMemeber"><i class="fa fa-plus"></i></button>
						</div></div>';
			}
			
		?>
		
		
	</div>
	<div class="col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">	
				<i class="fa fa-user stylish-icon"></i> User Details
				<a href="#" style="display: none;" class="pull-right deleteMember btn btn-danger btn-sm toolTips" title="Delete Member"><i class="fa fa-times"></i></a>
			</div>
			<div id="member-settings" style="display: none;">
				<?php echo form_open('landlord-associations/edit-member', array('class'=>'form-horizontal', 'id'=>'editMemberInfo')); ?>
					<br>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">Name</label>
						<div class="col-sm-6 text-left">
							<input type="text" name="name" class="form-control" id="showName" maxlength="30" required>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="showName-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">Email</label>
						<div class="col-sm-6 text-left">
							<input type="text" name="email" class="form-control" id="showEmail" maxlength="30">
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="showEmail-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">Phone</label>
						<div class="col-sm-6 text-left">
							<input type="text" name="phone" class="form-control phone" id="showPhone" maxlength="16">
							<span id="showPhone-error" class="sr-only"></span>
						</div>
					</div>					
					
					
					
					
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">Address</label>
						<div class="col-sm-6 text-left">
							<input type="text" name="address" class="form-control" id="showAddress">
							<span id="showAddress-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">City</label>
						<div class="col-sm-6 text-left">
							<input type="text" name="city" class="form-control" id="showCity" required>
							<span id="showCity-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">State</label>
						<div class="col-sm-6 text-left">
							<?php
								
								echo '<select id="showState" name="state" class="form-control">';
								echo '<option value="">Select One...</option>';
								foreach($states as $key => $val) {
									if($key == $_POST['state']) {
										echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo '</select>';
							?>
							<span id="showState-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-4 control-label">Zip</label>
						<div class="col-sm-6 text-left">
							<input type="text" class="form-control" name="zip" id="showZip" maxlength="5">
							<span id="showZip-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label for="position" class="col-sm-4 control-label">Position Title</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="position" id="showPosition" maxlength="20" required>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="showPosition-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label for="active" class="col-sm-4 control-label">Active</label>
						<div class="col-sm-6">
							<select id="showActive" class="form-control" name="active" required>
								<option value="n">No</option>
								<option value="y">Yes</option>
							</select>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="active-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label for="show_badge" class="col-sm-4 control-label">Show Badge</label>
						<div class="col-sm-6">
							<select id="showBadge" class="form-control" name="show_badge" required>
								<option value="n">No</option>
								<option value="y">Yes</option>
							</select>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="showBadge-error" class="sr-only"></span>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label for="active" class="col-sm-4 control-label">Due Date</label>
						<div class="col-sm-6">
							<input type="text" value="<?php echo set_value('due_date'); ?>" class="form-control date" id="showDate" name="due_date"  placeholder="mm/dd/yyyy" required>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="showDate-error" class="sr-only"></span>
						</div>
					</div>
					
					
					<div class="form-group has-feedback">
						<label for="member_type" class="col-sm-4 control-label">Member Category</label>
						<div class="col-sm-6">
							<select id="member_type" class="form-control" name="member_type">
								<?php
									if(!empty($categories)) {
									foreach($categories as $key => $val) {
										echo '<option>'.$val->member_type.'</option>';
									}
									} else {
										echo '<option value="">Add A Category Below</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="row editcategory">
						<div class="col-xs-9 col-xs-offset-1">
							<div id="categoryEventsEdit">
								<p class="text-right fakeLink" id="addMemberCategoryBtnEdit"><i class="fa fa-plus"></i> Add New Category</p>
							</div>
						</div>
					</div>
					<div id="catFeedbackEdit"></div>
				
					<div class="form-group has-feedback">
						<label for="payment_amount" class="col-sm-4 control-label">Payment Amount</label>
						<div class="col-sm-6">
							<input type="text" id="payment-amount" name="payment_amount" class="form-control amount" value="">
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="payment-amount-error" class="sr-only"></span>
						</div>
					</div>
					
					
					<div class="form-group has-feedback registeredMember">
						<label for="position" class="col-lg-4 control-label">Override Details</label>
						<div class="col-lg-6">
							<select id="custom_values" class="form-control" name="custom_values">
								<option value="n">No</option>
								<option value="y">Yes</option>
							</select>
							<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
							<span id="custom_values-error" class="sr-only"></span>
						</div>
					</div>	
					
					<div class="form-group has-feedback">
						<div class="col-sm-4 col-sm-offset-4">
							<div id="showRegistered"></div>
						</div>
					</div>
					
					<input type="hidden" id="showId" name="member_id">
					<hr>
					<div class="text-center">
						<button type="submit" id="editMember" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
						<br>
						<br>
					</div>
				</form>
			</div>
		</div>
	</div>	
</div>

<!-- Modal -->
<div class="modal" id="addMemeber" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-user text-primary"></i> Add New Member</h4>
			</div>
			<div class="modal-body">
				<div class="results">
					<h4>Step One</h4>
					<p>First try to search for the landlord by name, email, or phone to see if they are already registered in our system. If they are already registered in our system the information below will auto populate into the appropriate boxes saving you from having to type all the information in.</p>
					<hr>
					<form id="searchMemberForm">
						<div class="row">
							<div class="col-sm-3">
								<label>Search By:</label>
								<select id="searchType" name="searchBy" class="form-control">
									<option>Name</option>
									<option>Phone</option>
									<option>Email</option>
									<option value="bName">Business Name</option>
								</select>
							</div>
							<div class="col-sm-4">
								<label>Search For:</label>
								<input id="searchFor" type="text" name="searchFor" class="form-control">
							</div>
							<div class="col-sm-5">
								<br>
								<button id="searchLandlords" class="btn btn-primary" style="margin-top: 5px"><i class="fa fa-search"></i></button>
								<button class="btn btn-primary pull-right" id="cantFind">Skip Search</button>
							</div>
						</div>
						<div id="searchError"></div>
					</form>
					<ul id="searchLandlordResults">
						<li data-ider="0">
							
						</li>
					</ul>
				</div>
				
				<?php echo form_open('', array('id'=>'addingNewMember')); ?>
					<fieldset id="addMemeberForm">
						<h3 class="text-center"><i class="fa fa-user text-primary"></i> Member Details</h3>
						<hr>
						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="name">Name:</label>
							<div class="col-lg-7">
								<input type="text"  value="<?php echo set_value('name'); ?>" class="form-control" name="name" id="name" maxlength="40" required>
								<span class="fa username text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="name-error" class="sr-only"></span>
							</div>
						</div>	

						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="email">Email:</label>
							<div class="col-lg-7">
								<input type="text" value="<?php echo set_value('email'); ?>" class="form-control" name="email" id="email" maxlength="60" required>
								<span class="fa email text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="email-error" class="sr-only"></span>
							</div>
						</div>
						
						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="position">Position Title:</label>
							<div class="col-lg-7">
								<input type="text" value="<?php echo set_value('position'); ?>" class="form-control" name="position" id="position" maxlength="100" placeholder="Example: Vice President/Member etc" required>
								<span class="fa position text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
								<span id="position-error" class="sr-only"></span>
							</div>
						</div>
						


						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="phone">Phone:</label>
							<div class="col-lg-7">
								<input type="text" value="<?php echo set_value('phone'); ?>" class="form-control phone" id="phone" name="phone">
								<span class="fa phone form-control-feedback" aria-hidden="true"></span>
								<span id="phone-error" class="sr-only"></span>
							</div>
						</div>
						
						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="address">Address:</label>
							<div class="col-lg-7">
								<input type="text" value="<?php echo set_value('address'); ?>" name="address" class="form-control" id="address" maxlength="50">
								<span class="fa address form-control-feedback" aria-hidden="true"></span>
								<span id="address-error" class="sr-only"></span>
							</div>
						</div>

						<div class="form-group has-feedback row">
							<label class="col-lg-3 control-label text-right" for="city">City:</label>
							<div class="col-lg-7">
								<input type="text" value="<?php echo set_value('city'); ?>" class="form-control" name="city" id="city" maxlength="30">
								<span class="fa text-danger form-control-feedback" aria-hidden="true"></span>
								<span id="city-error" class="sr-only"></span>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-6">
								<div class="form-group has-feedback row">
									<label class="col-lg-6 control-label text-right" for="state">State:</label>
									<div class="col-lg-6">
										<?php
											echo '<select id="state" name="state" class="form-control" required>';
											echo '<option value="">Select One...</option>';
											foreach($states as $key => $val) {
												if($key == $_POST['state']) {
													echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
												} else {
													echo '<option value="'.$key.'">'.$val.'</option>';
												}
											}
											echo '</select>';
										?>
										<span class="fa text-danger state fa-asterisk form-control-feedback" aria-hidden="true"></span>
										<span id="state-error" class="sr-only"></span>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group has-feedback row">
									<label class="col-lg-3 control-label text-right" for="zip">Zip:</label>
									<div class="col-lg-5">
										<input type="text" value="<?php echo set_value('zip'); ?>" class="form-control onlyNumbers" id="zip" name="zip"  maxlength="5" required>
										<span class="fa zip form-control-feedback" aria-hidden="true"></span>
										<span id="zip-error" class="sr-only"></span>
									</div>
								</div>
							</div>
						</div>
						
						<h3 class="text-center"><i class="fa fa-gears text-primary"></i> Settings</h3><hr>
						<div class="row">
							<div class="col-lg-5">
								<div class="form-group has-feedback row">
									<label class="col-lg-6 control-label text-right" for="show_badge">Show Badge:</label>
									<div class="col-lg-6">
										<?php
											$states = array( ''=>'Select One', 'y'=>'Yes', 'n'=>'No');
											echo '<select id="badge" name="show_badge" class="form-control" required>';
											foreach($states as $key => $val) {
												if($key == $_POST['show_badge']) {
													echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
												} else {
													echo '<option value="'.$key.'">'.$val.'</option>';
												}
											}
											echo '</select>';
										?>
										<span class="fa text-danger badge fa-asterisk form-control-feedback" aria-hidden="true"></span>
										<span id="badge-error" class="sr-only"></span>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="form-group has-feedback row">
									<label class="col-lg-6 control-label text-right" for="due_date">Payment Due:</label>
									<div class="col-lg-6">
										<input type="text" value="<?php echo set_value('due_date'); ?>" class="form-control date" id="date" name="due_date"  placeholder="mm/dd/yyyy" required>
										<span class="fa date text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
										<span id="date-error" class="sr-only"></span>
									</div>
								</div>
							</div>
						</div>
						
						<div class="row">
							<div class="col-lg-5">
								<div class="form-group has-feedback row">
									<label for="member_type" class="col-lg-6  text-right control-label">Member Category</label>
									<div class="col-sm-6">
										<select id="add_member_type" class="form-control" name="member_type" required>
											<?php
												if(!empty($categories)) {
												foreach($categories as $key => $val) {
													echo '<option>'.$val->member_type.'</option>';
												}
												} else {
													echo '<option value="">Add A Category Below</option>';
												}
											?>
										</select>
										
									</div>
								</div>
								<div id="categoryEvents">
									<p class="text-right fakeLink" id="addNewCategoryLink"><i class="fa fa-plus"></i> Add New Category</p>
								</div>
								<div id="catFeedback"></div>
							</div>
							<div class="col-lg-6">	
								<div class="form-group has-feedback row">
									<label for="payment_amount" class="col-lg-6  text-right control-label">Payment Amount</label>
									<div class="col-sm-6">
										<input type="text" name="payment_amount" id="add-payment-amount" class="form-control amount" value="" required>
										<span class="fa text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
										<span id="add-payment-amount-error" class="sr-only"></span>
									</div>
								</div>
							</div>
						</div>	
						
						<br>
						<hr>
						<div class="text-center">
							<div class="col-md-6 col-md-offset-3">
								<input type="hidden" id="landlord_id" name="landlord_id">
								<button type="submit" id="submitNewMember" class="btn btn-primary">Add Member</button>
							</div>
						</div>
				
					</fieldset>
					
					
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="closeAddMember" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="clearfix"></div>

