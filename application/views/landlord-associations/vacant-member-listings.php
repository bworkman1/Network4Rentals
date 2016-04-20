<div class="panel panel-primary">
	<div class="panel-heading">	
		<i class="fa fa-home stylish-icon"></i> Members Vacant Listings
		<span class="label label-success pull-right"><b><em><?php echo $total; ?> Total</em></b></span>
	</div>
	<div class="body">
		<br>
		
		<table class="table table-condensed">
			<thead>
				<tr>
					<th style="text-align: left !important">Title</th>
					<th class="text-center">Member</th>
					<th class="text-center">Beds</th>
					<th class="text-center">Baths</th>
					<th class="text-center">City</th>
					<th class="text-center">Zip</th>
					<th class="text-center">Link</th>
				</tr>
			</thead>
			<tbody>
				<?php 
					if(!empty($rentals)) {
						foreach($rentals as $key => $val) {
							echo '<tr>';
								echo '<td  style="text-align: left !important">';
									echo $val->title;
								echo '</td>';
								echo '<td>';
									echo $val->owner_name;
								echo '</td>';					
								echo '<td>';
									echo $val->bedrooms;
								echo '</td>';
								echo '<td>';
									echo $val->bathrooms;
								echo '</td>';
								echo '<td>';
									echo $val->city;
								echo '</td>';
								echo '<td>';
									echo $val->zipCode;
								echo '</td>';
								echo '<td>';
									echo '<a href="https://network4rentals.com/network/listings/view-listing/'.$val->id.'" target="_blank" class="btn btn-primary">View</a>';
								echo '</td>';
							echo '</tr>';
						}
					} else {
						echo '<tr>';
							echo '<td colspan="7"><div class="alert alert-info">None of your members have vacant listings</div></td>';
						echo '</tr>';
					}
				?>
			</tbody>
		</table>
	
		<?php echo $pagination; ?>

	</div>
</div>