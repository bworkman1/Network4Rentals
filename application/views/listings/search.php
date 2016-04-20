<?php
	
	$searched = $this->session->userdata('searched');

	if($this->session->flashdata('error'))
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}

	if(!empty($success)) {
		echo '<div id="container-fluid">';
			
				$count = 0;
				foreach($success as $key=>$val) {
					
					if($count==0) {
						echo '<div class="row">';
					} elseif($count%4 == 0) {
						echo '</div><div class="row">';
					}

					echo '<div class="col-md-3 col-sm-4 col-xs-6">';
						echo '<div class="items wow fadeInUp" data-address="'.htmlentities(ucwords(strtolower($val['address'].' '.$val['city'].' '.$val['stateAbv'].' '.$val['zipCode']))).'" data-title="'.htmlentities(ucwords(strtolower($val['title']))).'" data-id="'.$val['id'].'" data-lat="'.$val['latitude'].'" data-long="'.$val['longitude'].'">';
							echo '<a href="'.base_url().'listings/view-listing/'.$val['id'].'">';
								echo '<div class="listings">';
									$featured = $val['featured_image'];
									if($val['owner'] == 199 || $val['owner'] == 156) { //199 is todds
										echo '<span class="unregistered"><i class="fa fa-exclamation-triangle"></i> Unregistered Landlord</span>';
									} 
									echo '<div class="listing-image">';
										if(!empty($val['image'.$featured])) {
											echo '<img src="'.base_url().'listing-images/'.$val['image'.$featured].'" class="img-responsive">';
										} else {
											echo '<img src="'.base_url().'listing-images/comingSoon.jpg" class="img-responsive">';
										}
						
										$one_week_ago = strtotime('-1 week');
										if( strtotime($val['lastmodified']) > $one_week_ago ) { 
											echo '<div class="new-listing"><i class="fa fa-star"></i> New Listing</div>';
										}
										
									echo '</div>';
									
									echo '<div class="listings-info">';
										echo '<h4>'.htmlentities(ucwords(strtolower($val['title']))).'</h4>';
										echo '<p><small>'.htmlentities(ucwords(strtolower($val['address']))).' '.htmlentities(ucwords(strtolower($val['city']).', '.htmlentities($val['stateAbv']))).'</small></p>';
										foreach($associations as $k => $v) {
											if($v->registered_landlord_id == $val['owner']) {
												if(!empty($v->image)) {
													echo '<img src="https://network4rentals.com/network/public-images/assoc_thumbs/'.$v->image.'" class="assoc-thumb img-responsive pull-left" width="50" height="50">';
												}
											}
										}
										echo '<div class="clearfix"></div>';
										echo '<div class="row">';
											echo '<div class="col-sm-6">';
												echo '<small><b>Rent: </b>$'.number_format($val['price'], 2).'</small>';
											echo '</div>';
											echo '<div class="col-sm-6">';
													echo '<small>Beds: '.htmlentities($val['bedrooms']).' '.$val->owner.' | Baths: '.htmlentities($val['bathrooms']).'</small>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							echo '</a>';
						echo '</div>';
					echo '</div>';
					$count++;
				}
			echo '</div>';
		echo '</div>';
	} else {

		if(!empty($radius_search)) {
			echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> No listings found but if you widen your radius there are '.$radius_search.' rental(s) available in a 50 mile radius of '.$this->session->userdata('zip').' that match your filters</div>';
		} else {
			echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> No listings found try removing some filters</div>';
		}
		
		echo '<div class="row">';	
			echo '<div class="col-sm-8 wow fadeInUp">';
				echo '<div class="white-bkg">';
					echo '<h3><i class="fa fa-envelope-o"></i> Want to be notified when a listing matches your filters?</h3>';
					echo '<hr>';
					echo '<p>Create a renter account for <b>free</b> or if you already have an account login. Once your logged in select ISO(In Search Of) from the menu on the left. Add your required filters and once a property comes available that matches your filters you will receive an email.</p>
					<div class="row"><div class="col-xs-6"><a href="'.base_url('renters/create-account').'" class="btn btn-primary">Create Renter Account</a></div><div class="col-xs-6"><a href="'.base_url('renters/login').'" class="btn btn-primary">Renter Login</a></div></div>';
				echo '</div>';
				
				//echo '<div class="white-bkg" style="margin-top: 15px;">';
				//	echo '<h3><i class="fa fa-question"></i> Why Use Network 4 Rentals?</h3>';
				//	echo '<hr>';
				//	echo '<p>This is where you will really want to sell them why we are the best and what we can do for the visitor.</p>';
				//echo '</div>';
			echo '</div>';
			echo '<div class="col-sm-4 wow fadeInUp">';
				echo '
					<div class="db-wrapper">
						<div class="db-pricing-eleven db-bk-color-two popular">
							<div class="price">
								<sup>Renters Account Free</sup>
							</div>
							<ul>
								<li><i class="fa fa-file"></i> Paperless </li>
								<li><i class="fa fa-mobile"></i> Access account on any device</li>
								<li><i class="fa fa-wrench"></i> Submit Service Request</li>
								<li><i class="fa fa-money"></i> Record Rent Payments</li>
								<li><i class="fa fa-plus"></i> And More</li>
							</ul>
							<div class="pricing-footer">
								<a href="'.base_url('renters').'" class="btn db-button-color-square btn-lg">Learn More</a>
							</div>
						</div>
                    </div>';
					
		
			echo '</div>';
		echo '</div>';
		
		
		
	}


?>



