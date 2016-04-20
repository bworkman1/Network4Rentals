<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-bars"></i> Purchased Ad Stats</h3>
	</div>
	<div class="panel-body">
		<div style="width: 100%" class="hidden-xs hidden-sm">
			<?php if(!empty($stats)) { ?>
				<canvas id="canvas" style="width: 100%; max-height: 450px"></canvas>
			<?php } else { ?>
				<h2 class="highlight">No Ads Purchased</h2>
				<p>Ads are an extra charge to get you listed right in the service request that the landlords see. These ads will be front and center of the landlords that you want to be in front of.</p>
				<a href="https://network4rentals.com/network/contractor/purchase-ads" class="btn btn-primary">Purchase Ads Now</a>			<?php } ?>
		</div>
		<div class="visible-xs visible-sm">
			<?php
				if(!empty($stats)) {
					foreach($stats as $key => $val) {
						echo '<h4 class="highlight">'.$val->label.'</h4>';
						echo '<div class="row">';
						echo '<div class="col-xs-6"><b>Clicks: </b> '.$val->clicks.'</div>';
						echo '<div class="col-xs-6"><b>Impressions:</b> '.$val->impressions.'</div>';
						echo '</div>';
						echo '<hr>';
					}
				} else {
					echo '<h2>You have not purchased any ads yet</h2>';
				}
			?>
		</div>
	</div>
</div>

<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-bars"></i> Service Requests Stats Overall</h3>
	</div>
	<div class="panel-body">
		<?php
			$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
			for($i=0;$i<count($bottom_stats);$i++) {
				//echo $bottom_stats[$i]['service_type'];
				$total = $bottom_stats[$i]['total'];
				$request = $bottom_stats[$i]['my_requests'];

				$subTotal = ($request/$total)*100;
		
				echo '<div class="row">';	
					echo '<div class="col-sm-6">';
						echo '<p>'.$services_array[$bottom_stats[$i]['service_type']].' - '.$bottom_stats[$i]['zip'].'</p>';
					echo '</div>';
					echo '<div class="col-sm-6">';
						echo '<div class="progress">';
							echo '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.$subTotal.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$subTotal.'%">';
								echo number_format($subTotal).'%';
							echo '</div>';
						echo '</div>';
						echo '<div class="text-right">';
							echo '<small>'.$bottom_stats[$i]['my_requests'].' Request out of '.$bottom_stats[$i]['total'].' Request Total</small>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
				echo '<hr>';
			}
		?>
	</div>
</div>