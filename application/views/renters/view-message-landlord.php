<h3><i class="fa fa-comment text-primary"></i> New Message</h3>
<hr>
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

<div class="row">
	<div class="col-sm-6">
		<p><h4><b>From:</b></h4> <?php echo $chat['tenants_name']; ?><br><?php echo $chat['tenants_email']; ?></p>
		<h4><b>Message:</b></h4>
		<p><?php echo $chat['message']; ?></p>
	</div>
	<div class="col-sm-6 text-right">
		<p><h4><b>Sent On:</b></h4> <?php echo date('m-d-Y H:i:s a', strtotime($chat['timestamp']) +3600); ?> <small>EST</small></p>
		<?php if(!empty($chat['attachment'])) { ?>
			<h4><b>Attachment:</b></h4>
			<a href="<?php echo base_url(); ?>/message-uploads/<?php echo $chat['attachment']; ?>" target="_blank"><?php echo $chat['attachment']; ?></a>
		<?php } ?>
	</div>
</div>
<hr>
<p>If you would like to reply to this message please login to your account.</p>
<div class="row">
	<div class="col-sm-3">
		<a href="<?php echo base_url(); ?>landlords/create-account" class="btn btn-primary btn-block btn-sm"><i class="fa fa-user"></i> Create Account</a>
	</div>
	<div class="col-sm-3">
		<a href="<?php echo base_url(); ?>landlords/login" class="btn btn-primary btn-block btn-sm"><i class="fa fa-unlock"></i> Login</a>
	</div>
</div>
<br>
<br>
<br>
<br>