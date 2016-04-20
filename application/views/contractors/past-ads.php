<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-calendar"></i> Past Ads </h3>
	</div>
	<div class="panel-body">
		<?php
			if(empty($ads)) {
				echo '<h2 class="highlight">No Ads Have Expired Yet</h2>';
				echo '<p>Ads are an extra charge to get you listed right in the service request that the landlords see. These ads will be front and center of the landlords that you want to be in front of.</p>';
				echo '<a href="https://network4rentals.com/network/contractor/purchase-ads" class="btn btn-primary">Purchase Ads Now</a>';
			} else {
				echo $ads;
			}
		?>
	</div>
</div>