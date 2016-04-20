<h2><i class="fa fa-bookmark-o text-primary"></i> Viewing Rental Home Self-Check</h2>
<hr>
<h4><i class="fa fa-user text-primary"></i> Tenant Details</h4>
<hr>
<div class="row">
	<div class="col-sm-6">
		<?php
			echo '<p><b>Tenant Name:</b> '.$checklist_data['name'].'</p>';
			echo '<p><b>Email:</b> '.$checklist_data['email'].'</p>';
			echo '<p><b>Phone:</b> ('.substr($checklist_data['phone'], 0, 3).') '.substr($checklist_data['phone'], 3, 3).'-'.substr($checklist_data['phone'],6);
			echo '<p><b>Alt Phone:</b> ('.substr($checklist_data['alt_phone'], 0, 3).') '.substr($checklist_data['alt_phone'], 3, 3).'-'.substr($checklist_data['alt_phone'],6);
		?>
	</div>
	<div class="col-sm-6">
		<?php
			echo '<p><b>Address:</b> <br>'.$checklist_data['rental_address'].' '.$checklist_data['rental_city'].', '.$checklist_data['rental_state'].' '.$checklist_data['rental_zip'].'</p>';
			echo '<p><b>Submitted On:</b> '.date('m-d-Y h:m A', strtotime($checklist_data['timeStamp'])+3600).'</p>';
		?>	
	</div>
</div>

<h4><i class="fa fa-check text-primary"></i> Check List Details</h4>
<hr>
<div class="row">
	<div class="col-sm-12 view-checklist">
		<?php 
			$count = 0;
			foreach($checklist_data as $key => $val) {			
				if($key !== 'id' && $key !== 'Landlord' && $key !== 'Tenant' && $key !== 'TimeStamp' && $key !== 'Active' && $key !== 'Unique_id') {					
					if(strpos($key, 'Details') == FALSE) {
						if($val == 'Good'){
							echo '<div class="row"><div class="col-sm-3">'.ucwords($key).': </div>';
							echo '<div class="col-sm-2"><div class="label label-success"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-7">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-7">'.ucwords($checklist_data[strtolower($key).'Details']).'</div></div>';
							}			
						
						} elseif($val == 'Fair') {
							echo '<div class="row"><div class="col-sm-3">'.ucwords($key).': </div>';
							echo '<div class="col-sm-2"><div class="label label-warning"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-7">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-7">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							
						} elseif($val == 'Repair') {
							echo '<div class="row"><div class="col-sm-3">'.ucwords($key).': </div>';
							echo '<div class="col-sm-2"><div class="label label-danger"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-7">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-7">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							
						} elseif($val == 'NA') {
							echo '<div class="row"><div class="col-sm-3">'.ucwords($key).': </div>';
							echo '<div class="col-sm-2"><div class="label label-default"><i class="fa fa-check"></i> '.$val.'</div></div>'; 
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-7">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-7">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							
						}
					}
				}
				$count++;
			}
			echo '</div>';
		?>
	
</div>
