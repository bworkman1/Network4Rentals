<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-o fa-lg"></i> Error:</b> '.$error.'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><b><i class="fa fa-times-o fa-lg"></i> Success:</b> '.$success.'</div>';
	}
?>

<div class="widget">
	<div class="widget-header">
		<div class="title">
			<i class="fa fa-bullhorn"></i> Affiliates
		</div>
		<span class="tools">
		  <a href="https://network4rentals.com/network/n4radmin/add-affiliate" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
		</span>
	</div>
	<div class="widget-body">
		<div class="table-responsive">
			<?php 
				echo $affiliates;
			?>		
		</div>
		<?php echo $links; ?>
	</div>
</div>