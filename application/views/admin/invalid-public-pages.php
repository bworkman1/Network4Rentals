<h1>Public Page Data</h1>
<hr>
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-chain-broken"></i> Possible Invalid Page Links                    
            </div>
			<div class="panel-body">
				<?php
					if(!empty($pages['invalid_page_names'])) {
						foreach($pages['invalid_page_names'] as $key => $val) {
							echo '<div class="row" style="border-bottom: 1px solid #cdcdcd; margin-bottom: 4px">';
								echo '<div class="col-xs-9">';
									echo '<p>'.$val['bname'].'</p>';
								echo '</div>';
								echo '<div class="col-xs-3">';
									echo '<p><a href="http://n4r.rentals/'.$val['unique_name'].'" class="btn btn-primary btn-xs btn-block" target="_blank">View</a></p>';
								echo '</div>';
							echo '</div>';
						}
					} else {
						echo '<div class="alert alert-info">No invalid pages detected</div>';
					}
				?>
			</div>
			<div class="panel-footer">
				<i class="fa fa-info-circle fa-3x text-info pull-left"></i> <p>These pages won't rank well in google with the current link set to random numbers and letters</p>
			</div>
        </div>
    </div>


    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <i class="fa fa-file-o"></i> <?php echo sizeOf($pages['no_landlord_page_setup']); ?> Landlords Without Public Pages                
            </div>
			<div class="panel-body">
				<?php
					if(!empty($pages['no_landlord_page_setup'])) {
						echo '<div class="row">';
						$i = 0;
						foreach($pages['no_landlord_page_setup'] as $key => $val) {
							if($i>11) {
								echo '<div class="col-md-6 hideRest">';
								$hidding = true;
							} else {
								echo '<div class="col-md-6">';
							}
								echo '<div class="row" style="border-bottom: 1px solid #cdcdcd; margin-bottom: 4px">';
									echo '<div class="col-xs-9">';
										echo '<p style="margin-top: 5px;">'.$val['name'].'</p>';
									echo '</div>';
									echo '<div class="col-xs-3">';
										echo '<p><a href="'.base_url('n4radmin/view-user-details/landlord/'.$val['id']).'" class="btn btn-primary btn-xs btn-block">View</a></p>';
									echo '</div>';
								echo '</div>';
							echo '</div>';
							$i++;
						}
						echo '</div>';
					} else {
						echo '<div class="alert alert-info">No invalid pages detected</div>';
					}
					
					if($hidding) {
						echo '<br><a href="#" class="viewAllWIthout btn btn-default">View All</a>';
					}
				?>
			</div>
        </div>
    </div>	
	
	
</div>

