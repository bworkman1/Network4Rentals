<h2><i class="fa fa-user text-primary"></i> Landlord Account Created</h2>
<hr>
<p>Congratulations your account has been created. The next step is the verify your account by following the instructions in the email. Once completed you will be able to login and communicate with your tenants and track your rental history Like never before.</p>
<p>Be sure to <b>check your spam</b> if you are having trouble finding the activation email.</p>
<p>If you're using gmail it may take a while for the email to come through.</p>
<?php			
	
	if($this->session->flashdata('resent')) {
		echo '<div class="alert alert-success"><b>Success:</b> '.$this->session->flashdata('resent').'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><b>Error:</b> '.$this->session->flashdata('error').'</div>';
	}
	$email = $this->session->userdata('user_email');
	if(!empty($email)) {
		echo '<hr><div class="row">';
			echo '<div class="col-sm-6"><h4>Email Sent To: </h4>'.$email.'</div>';
			echo '<div class="col-sm-6">';
				echo '<br><a href="'.base_url().'landlords/resend_account_email" class="btn btn-primary pull-right"><i class="fa fa-reply"></i> Re-Send Email</a><br><br>';	
			echo '</div>';
		echo '</div>';
	} else {
		redirect('landlords/create_account');
	}
	
?>
<?php if($this->session->userdata('cell')) { ?>
	<hr>
	<h4 class="text-primary">Received Your Text Message Code?</h4>
	<?php echo form_open('landlords/text-code-conformation'); ?>
		<div class="row">
			<div class="col-sm-3"> 
				<input type="text" name="text_code" class="form-control" required placeholder="Enter Code">
			</div>
			<div class="col-sm-3">
				<button type="submit" class="btn btn-primary"><i class="fa fa-lock"></i> Submit</button>
			</div>		
		</div>
	</form>
	<hr>
<?php } ?>

<?php
	if(isset($info['feedback'])) {
		echo $info['feedback'];
	}
?>
<h4 class="text-primary">Change Text Settings And Resend</h4>
<div class="row">
	<div class="col-sm-9">
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
					<button type="submit" class="btn btn-primary addTextMessages"><i class="fa fa-envelope"></i> Send Text</button>
				</div>
			</div>	
		</form>
	</div>
</div>


	