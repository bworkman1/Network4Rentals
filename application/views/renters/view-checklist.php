<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-check"></i> Rental Home Self-Check
		<span class="pull-right label label-primary"><small>Submitted: <?php echo date('m-d-Y h:m A', strtotime($checklist_data['timeStamp'])+3600); ?></small></span>
	</div>
	<div class="panel-body">
<div class="row">
	<div class="col-sm-12 view-checklist">
		<?php 
			$count = 0;
		
			foreach($checklist_data as $key => $val) {			
				if($key !== 'id' && $key !== 'Landlord' && $key !== 'Tenant' && $key !== 'TimeStamp' && $key !== 'Active' && $key !== 'Unique_id') {					
					if(strpos($key, 'Details') == FALSE) {
						if($val == 'Good'){
							echo '<div class="row"><div class="col-sm-4">'.ucwords($key).': </div>';
							echo '<div class="col-sm-4"><div class="label label-success"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-4">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-4">'.ucwords($checklist_data[strtolower($key).'Details']).'</div></div>';
							}			
							echo '<hr>';
						} elseif($val == 'Fair') {
							echo '<div class="row"><div class="col-sm-4">'.ucwords($key).': </div>';
							echo '<div class="col-sm-4"><div class="label label-warning"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-4">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-4">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							echo '<hr>';
						} elseif($val == 'Repair') {
							echo '<div class="row"><div class="col-sm-4">'.ucwords($key).': </div>';
							echo '<div class="col-sm-4"><div class="label label-danger"><i class="fa fa-check"></i> '.$val.'</div></div>';
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-4">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-4">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							echo '<hr>';
						} elseif($val == 'NA') {
							echo '<div class="row"><div class="col-sm-4">'.ucwords($key).': </div>';
							echo '<div class="col-sm-4"><div class="label label-default"><i class="fa fa-check"></i> '.$val.'</div></div>'; 
							if($checklist_data[strtolower($key).'Details'] == '') {
								echo '<div class="col-sm-4">No Details</div> </div>';
							} else {
								echo '<div class="col-sm-4">'.ucwords($checklist_data[strtolower($key).'Details']).'</div> </div>';
							}
							echo '<hr>';
						}
					}
				}
				$count++;
			}
			echo '</div>';
		?>
	</div>
</div>
</div>
</div>
