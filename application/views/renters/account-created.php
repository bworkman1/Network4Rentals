<h2><i class="fa fa-user text-warning"></i> Renter Account Created</h2><hr>
<?php
	if($this->session->flashdata('warning')) 
	{
		echo '<div class="alert alert-warning"><h4>Warning:</h4>'.$this->session->flashdata('warning').'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
	
?>
<p>Congratulations your account has been created. The next step is the verify your account by following the instructions in the email. Once completed you will be able to login and communicate with your landlord and track your rental history among other benefits.</p>

	<div class="row">
		<div class="col-sm-6">
			<div class="well well-sm">
				<h4><i class="fa fa-exclamation-triangle text-warning"></i> By Email</h4>
				<p>If you are not getting your email be sure to check your spam if you're having trouble finding it.</p>
			</div>
		</div>
		<div class="col-sm-6">
			<div class="well well-sm">
				<h4><i class="fa fa-phone text-warning"></i> By Text <small class="pull-right"><?php echo $this->session->userdata('cell'); ?></small></h4>
				<?php echo form_open('renters/text-code-conformation'); ?>
					<div class="row">
						<div class="col-sm-7"> 
							<input type="text" name="text_code" class="form-control input-md" required placeholder="Enter Code">
						</div>
						<div class="col-sm-5">
							<button type="submit" class="btn btn-warning btn-md btn-block"><i class="fa fa-key"></i> Verify</button>
						</div>		
					</div>
				</form>
			</div>
		</div>
	</div>


<?php if($this->session->userdata('sms') == 'y') { ?>
<h3>Still Having Trouble?</h3>
<hr>
<h5>Change Text Settings And Resend</h5>
<div class="row">
	<div class="col-sm-9">
		<div class="well well-sm">
			<?php echo form_open('', array('id'=>'createNewAccount')); ?>
				<div class="row">
					<div class="col-sm-4">
						<label>Allow Text Messages</label>
						<select class="form-control textMessages" name="sms_msgs" required>
							<?php
								$options = array('n'=>'No', 'y'=>'Yes');
								foreach($options as $key => $val) {
									if($key == $this->session->userdata('sms')) {
										echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="col-sm-4">
						<label>Cell Number</label>
						<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $this->session->userdata('cell'); ?>">
					</div>
					<div class="col-sm-4">
						<br>
						<button type="submit" class="btn btn-warning addTextMessages btn-sm"><i class="fa fa-envelope"></i> Send Text</button>
					</div>
				</div>	
			</form>
		</div>
	</div>
</div>
<?php } ?>
<a href="<?php echo base_url(); ?>renters/resend_account_email" class='btn btn-warning'><i class="fa fa-reply"></i> Re-Send Email</a>

	