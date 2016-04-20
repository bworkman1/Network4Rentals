<?php
	$success = $this->session->flashdata('success');
	if(!empty($success)) {
		echo '<div class="alert alert-success"><i class="fa fa-check fa-2x pull-left fa-fw"></i> '.$success.'</div>';
	}
		$error = $this->session->flashdata('error');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><i class="fa fa-times fa-2x pull-left fa-fw"></i> '.$error.'</div>';
	}
?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-bullseye"></i> Current Ads </h3>
	</div>
	<div class="panel-body">
		<?php
			if(empty($ads)) {
				echo '<h2 class="highlight">No Ads Purchased</h2>';
				echo '<p>Ads are an extra charge to get you listed right in the service request that the landlords see. These ads will be front and center of the landlords that you want to be in front of.</p>';
				echo '<a href="https://network4rentals.com/network/contractor/purchase-ads" class="btn btn-primary">Purchase Ads Now</a>';
			} else {
				echo $ads;
			}
		?>
	</div>
</div>