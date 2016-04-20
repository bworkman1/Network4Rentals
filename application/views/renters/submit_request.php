<?php	
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}

?>

<?php if(!empty($landlord['email'])) { ?>
<div class="panel panel-warning">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-10">
				<i class="fa fa-wrench"></i> Submit A Service Request
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary btn-block btn-sm" data-toggle="modal" data-target="#helpvideo">Need Help?</button>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12">
				<p>In order to submit a service request all you need to know is the landlords email address. Once you submit the request your landlord will receive an email with this info. When they open the email you will be notified via email that they have viewed your request.</p>
			</div>
		</div>
	</div>
</div>


	<div class="panel panel-warning">
		<div class="panel-heading">
			<i class="fa fa-building-o"></i> Landlord Info
		</div>
		<div class="panel-body">
			<div class='row'>
				<div class='col-lg-6'>
					<p><b>Landlord:</b><br> <?php if(empty($landlord['bName'])) {echo $landlord['name'];} else {echo $landlord['bName'];} ?></p>
					<p><b>Address:</b><br> <?php echo $landlord['address'].'<br>'.$landlord['city'].' '.$landlord['state'].' '.$landlord['zip']; ?>
				</div>
				<div class='col-lg-6'>
					<p><b>Phone:</b><br> <?php echo "(".substr($landlord['phone'], 0, 3).") ".substr($landlord['phone'], 3, 3)."-".substr($landlord['phone'],6); ?></p>
					<p><b>Email:</b><br> <?php echo $landlord['email']; ?></p>		
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12">
					<p><i class="fa fa-asterisk text-danger"></i> This is who this service request will go to. If this is not correct you can edit this information by going to "Rental History" and editing your current landlord. Check the current residence box and save it.</p>
					<a href="<?php echo base_url(); ?>renters/my_history" class="btn btn-primary"><i class="fa fa-edit"></i> Rental History</a>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-user"></i> Your Info
				</div>
				<div class="panel-body">
					<p><i class="fa fa-asterisk text-danger"></i>  Note: If you need to edit any info below do so through the edit account link on the left side or the button below.</p>
					<p><a href="<?php echo base_url(); ?>renters/edit_account"><i class="fa fa-pencil"></i> Edit Account</a></p>
					<hr>
					<label><b>Your Name:</b></label>
					<p> <?php echo ucwords($user[0]['name']); ?></p>
					<label><b>Phone:</b></label>
					<p><?php echo "(".substr($user[0]['phone'], 0, 3).") ".substr($user[0]['phone'], 3, 3)."-".substr($user[0]['phone'],6); ucwords($user[0]['phone']); ?> </p>
					<label><b>Email:</b></label>
					<p><?php echo $user[0]['email']; ?></p>
					<label><b>Address:</b></label>
					<p><?php echo ucwords($user[1]['rental_address']); ?> <?php echo ucwords($user[1]['rental_city']); ?>, <?php echo ucwords($user[1]['rental_state']); ?> <?php echo $user[1]['rental_zip']; ?></p>
				</div>
			</div>
		</div>
		
		<div class="col-md-6">
			<div class="panel panel-warning">
				<div class="panel-heading">
					<i class="fa fa-wrench"></i> Enter Service Request
				</div>
				<div class="panel-body">
					<?php echo form_open_multipart('renters/submit-request'); ?>
					
						<div class="form-group">
							<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span>Type Of Service:</label>
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
								echo "<select name='serviceType' id='serviceType' class='form-control' required='required'>";
								echo '<option value="">Choose One...</option>';
								foreach($services_array as $key => $val) {
									if($_POST['serviceType'] == $key) {
										echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo "</select>";
							?>
						</div>
						
						<div class="form-group">
							<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span> Phone For Scheduling:</label>
							<input type='text' placeholder='(555) 555-5555' name='phone2' value='' class='form-control phone' required="required" />
						</div>
						
						<div class="form-group">
							<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span>Permission To Enter Residence:</label>
							<select name='Permission' id='Permission' class='form-control' required="required">
								<option value="">Select One...</option>
								<?php 
									$options_array = array('yes' => 'Yes', 'call' => 'Call First');
									foreach($options_array as $key => $val) {
										if($_POST['Permission'] == $val) {
											echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
										} else {
											echo '<option value="'.$key.'">'.$val.'</option>';
										}
									}
								?>
							</select>
						</div>
						
						<div class="form-group">
							<label><span class='text-danger'><i class="fa fa-asterisk"></i> </span>Description (Be As Detailed As Possible):</label>
							<textarea style='height: 200px' name='desc' class='form-control' maxlength="700" title="Description Must Not Be More Then 700 Characters Long" required="required"><?php if(isset($_POST['desc'])) {echo $_POST['desc'];} ?></textarea>
						</div>
						
						<div class="form-group">
							<label><i class="fa fa-picture-o"></i> Add An Image: <small>You can include one image to send to your landlord.  Only image files are accepted (gif, jpeg, jpg, png, JPG, JPEG)</small></label>
							<input type='file' name='file' class="form-control attachment-img">
						</div>
						
						<div class="form-group">
							<button type='submit' name='submit' class='btn btn-primary btn-block btn-lg'><i class="fa fa-share"></i> Submit Request</button>
						</div>
						
						<input type="hidden" name="rental_id" value="<?php echo $user[1]['id']; ?>">
						<input type="hidden" name="address" value="<?php echo ucwords($user[1]['rental_address'].' '.$user[1]['rental_city'].', '.$user[1]['rental_state']); ?>">
					</form>
				</div>
			</div>
		</div>
	</div>
	
	

<?php } else { ?>
	<h3><i class="fa fa-exclamation-triangle text-danger"></i> Unable To Submit A Request</h3>
	<p>Your current landlord is not registered and doesn't have an email address attached to their info. If you think this is in error check your current landlords email address field. If it is empty add an email address or a cell phone number and come back here.</p>
	<a href="<?php echo base_url(); ?>renters/my_history" class="btn btn-warning btn-xs"><i class="fa fa-edit"></i> Rental History</a>
<?php } ?>


<div class="modal fade" id="helpvideo" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-question text-warning"></i> How To Create Your Account</h4>
			</div>
			<div class="modal-body">
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<iframe width="560" height="315" src="//www.youtube.com/embed/F_Ekp7rcQos?list=PLc6pWpJ0Cx_mGBqbIZm6KVc-wCMbvb2Ya" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>