<a data-toggle="modal" data-target="#invite-tenant" class="pull-right btn btn-primary"><i class="fa fa-paper-plane-o"></i> Invite Tenant</a>	
<h2><i class="fa fa-users text-primary"></i> My Tenants</h2>
<hr>
<?php
	
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <b>Error:</b> '.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
?>
<div class="row">
	<div class="col-sm-5">
		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#messageAll"><i class="fa fa-comments-o"></i> Message All Current Tenants</button>
	</div>
	<div class="col-sm-4 text-right">
		<em>Sort Tenants By Past Or Current</em>
	</div>
	<div class="col-sm-3">
		<?php
			echo form_open('landlords/my-tenants'); 
			echo '<select class="form-control input-sm" name="tenants" onchange="this.form.submit()">';
				$switch_current = array('n'=>'Past Tenants', 'y'=>'Current Tenants');
				foreach($switch_current as $key => $val) {
					if($this->session->userdata('current_residence') == 'y') {
						echo '<option value="'.$key.'" selected="selected">'.htmlentities($val).'</option>';
					} else {
						echo '<option value="'.$key.'">'.htmlentities($val).'</option>';
					}
				}
			echo '</select>';
			echo form_close();
		?>	
	</div>
</div>
<hr>
<?php
	
	if(!empty($results)) {

		echo '<div class="borderBottom" style="border-bottom: 1px solid #cccccc; line-height: 1.8em; padding-bottom: 2px; font-size: 1.2em;">';
			echo '<div class="row">';
				echo '<div class="col-sm-4">';
					echo '<b>Tenant Name:</b>';
				echo '</div>';
				echo '<div class="col-sm-4">';
					echo '<b>Address:</b>';
				echo '</div>';
				echo '<div class="col-sm-2">';
					$sorted = $this->session->userdata('current_residence');
					if($sorted == 'y') {
						echo '<b>Rent Due</b>';
					} else {
						echo '<b>Moved Out</b>';
					}
				echo '</div>';
				echo '<div class="col-sm-2 text-center">';
					echo '<b>Options:</b>';	
				echo '</div>';
			echo '</div>';
		echo '</div>';
		foreach($results as $data) {
			echo '<div class="borderBottom" style="border-bottom: 1px solid #cccccc; line-height: 1.8em; padding-bottom: 2px; font-size: 1.2em;">';
				echo '<div class="row">';
					echo '<div class="col-sm-4">';
						if($data['address_locked'] == '0') {
							echo '<i class="fa fa-flag text-danger toolTips" title="This tenant is unverified. View tenant info and verify the tenants info."></i> ';
						}
						echo $data['name'];
					echo '</div>';
					echo '<div class="col-sm-4">';
						echo htmlentities($data['rental_address']).' <small>';
						echo htmlentities($data['rental_city']).' ';
						echo htmlentities($data['rental_state']).'</small>';
					echo '</div>';
					echo '<div class="col-sm-2">';
						$sorted = $this->session->userdata('current_residence');
						if($sorted == 'y') {
							echo htmlentities($data['day_rent_due']);
						} else {
							if($data['move_out'] != '0000-00-00') {
								echo date('m/d/Y', strtotime($data['move_out']));
							} else {
								echo 'NA';
							}
						}
						
					echo '</div>';
					echo '<div class="col-sm-2 text-center">';
						echo '<div class="btn-group">
						  <button style="margin-top: 5px" type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
							Options <span class="caret"></span>
						  </button>
						  <ul class="dropdown-menu" role="menu">
							<li>	
								<a href="'.base_url().'landlords/message-tenant/'.$data['rental_id'].'" class=""><i class="fa fa-comment"></i> Message Tenant</a>
							</li>
							<li>
								<a href="'.base_url().'landlords/view-tenant-info/'.$data['rental_id'].'"><i class="fa fa-user"></i> View Tenant Info</a>
							</li>
						  </ul>
						</div>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		if($this->session->userdata('current_residence') == 'y') {
			echo '<div class="alert alert-info">You Have No Current Tenants Linked To Your Account</div>';
		} else {
			echo 'You Have No Past Tenants Yet';
		}
	}

?>
<div class="text-center">
	<?php echo $links; ?>
</div>

<div class="modal fade" id="messageAll" tabindex="-1" role="dialog" aria-labelledby="messageAll" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/send-group-message'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Send A Message To All <?php if(empty($current_owner)) {echo 'My Tenants'; } else { echo $current_owner.' Tenants';} ?></h4>
			</div>
			<div class="modal-body">
				<label><span class="text-danger">*</span> <b>Message:</b></label>
				<textarea class="form-control" name="message" maxlength="700" required="required"></textarea>
				<label><b>Attach File:</b> <small>(PDF, DOC, DOCX, JPG, JPEG, PNG, GIF)</small></label>
				<input type="file" name="attachment" class="form-control attachment">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary btn-sm">Send Messages</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<div class="modal fade" id="invite-tenant" tabindex="-1" role="dialog" aria-labelledby="invite-tenant" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('landlords/invite-tenant'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-key text-primary"></i> Invite Tenant To N4R</h4>
				</div>
				<div class="modal-body"> 
					<?php if($public_page_setup) { ?>
					<div class="row">
						<div class="col-sm-6">
							<label>Email:</label>
							<input type="text" name="email" class="form-control" required="required" maxlength="80">
						</div>
						<div class="col-sm-6">
							<label>Confirm Email:</label>
							<input type="text" name="email2" class="form-control" required="required" maxlength="80">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<?php
								if(!empty($switches)) {
									echo '<label>Send As Sub Account:</label>';
									echo '<select class="form-control" name="behalf">';
										echo '<option value="">My Account</option>';
										for($i=0;$i<count($switches);$i++) {
											echo '<option value="'.$switches[$i]['main_admin_id'].'">'.$switches[$i]['sub_b_name'].'</option>';
										}
									echo '</select>';
								}
							?>
						</div>
					</div>
					
					<?php } else { ?>
						<h4>Warning:</h4>
						<p>In order to use this feature you must create your public page. Click the create public page button below to enable this feature.</p>
						<a href="<?php echo base_url(); ?>landlords/public-page-settings/" class="btn btn-primary btn-sm">Create Public Page</a>
					<?php } ?>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send Invite</button>
				</div>
			</form>
		</div>
	</div>
</div>