<div class="container-fluid">
	<div class="row">
		<div class="listing_overview col-sm-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<p>Details</p>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6 text-right">
							<p><strong>Listed:</strong></p>
							<p><strong>Rent:</strong></p>
							<p><strong>Deposit: </strong></p>
							<p><strong>City: </strong></p>
							<p><strong>Baths: </strong></p>
							<p><strong>Beds: </strong></p>
							<p><strong>Sq. Feet: </strong></p>
							<p><strong>Zip: </strong></p>
						</div>
						<div class="col-xs-6">
							<p><?php echo htmlentities(date('m-d-Y', strtotime($listing->lastmodified)+3600)); ?></p>
							<p>$<?php echo htmlentities($listing->price); ?></p>
							<p>$<?php echo htmlentities($listing->deposit); ?></p>
							<p><?php echo htmlentities($listing->city); ?></p>
							<p><?php echo htmlentities($listing->bathrooms); ?></p>
							<p><?php echo htmlentities($listing->bedrooms); ?></p>
							<p><?php if(!empty($listing->sqFeet)) {echo htmlentities($listing->sqFeet);} else {echo 'NA';} ?></p>
							<p><?php echo htmlentities($listing->zipCode); ?></p>
						</div>
					</div>

					<hr>
					<div class="text-center">
						<button data-toggle="modal" class="btn btn-info" data-target="#contact"><i class="fa fa-envelope"></i> Contact Landlord</button>
					</div>

					<hr>
					<?php
					$options_array = array('central_air', 'laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'single_lvl', 'shed', 'park', 'inside_city', 'outside_city', 'deck_porch', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool', 'shopping', 'garage', 'parking', 'pets');
					$am ='';
					$amenities = false;
					foreach($listing as $key => $val) {
						if(in_array($key, $options_array)) {
							if($val == 'y') {
								$amenities = true;
								$am .= '<span class="aminitiey badge"><i class="fa fa-check"></i> '.ucwords(str_replace('_', ' ', $key)).'</span>';
							}
						}
					}
					if($amenities) {
						echo '<h4 class="text-center">Features</h4>';
						echo '<p>'.$am.'</p>';
						echo '<hr>';
					}

					?>

					<h3 class="text-center">Share This Listing</h3>
					<div class="share_index">
						<ul class="share-buttons">
							<li><a href="https://www.facebook.com/sharer/sharer.php?u=http%3A%2F%2Fnetwork4rentals.com&t=Share%20This%20Page" title="Share on Facebook" target="_blank" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(document.URL) + '&t=' + encodeURIComponent(document.URL)); return false;"><img src="<?php echo base_url('assets/themes/default/images/Facebook.png'); ?>"></a></li>

							<li><a href="https://twitter.com/intent/tweet?source=http%3A%2F%2Fnetwork4rentals.com&text=Share%20This%20Page:%20http%3A%2F%2Fnetwork4rentals.com" target="_blank" title="Tweet" onclick="window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(document.title) + ':%20'  + encodeURIComponent(document.URL)); return false;"><img src="<?php echo base_url('assets/themes/default/images/Twitter.png'); ?>"></a></li>

							<li><a href="https://plus.google.com/share?url=http%3A%2F%2Fnetwork4rentals.com" target="_blank" title="Share on Google+" onclick="window.open('https://plus.google.com/share?url=' + encodeURIComponent(document.URL)); return false;"><img src="<?php echo base_url('assets/themes/default/images/Google+.png'); ?>"></a></li>

							<li><a href="http://pinterest.com/pin/create/button/?url=http%3A%2F%2Fnetwork4rentals.com&description=" target="_blank" title="Pin it" onclick="window.open('http://pinterest.com/pin/create/button/?url=' + encodeURIComponent(document.URL) + '&description=' +  encodeURIComponent(document.title)); return false;"><img src="<?php echo base_url('assets/themes/default/images/Pinterest.png'); ?>"></a></li>

							<li><a href="mailto:?subject=Share%20This%20Page&body=:%20http%3A%2F%2Fnetwork4rentals.com" title="Email" onclick="window.open('mailto:?subject=' + encodeURIComponent(document.title) + '&body=' +  encodeURIComponent(document.URL)); return false;"><img src="<?php echo base_url('assets/themes/default/images/Email.png'); ?>"></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-8">
			<div class="white-bkg">
				<?php
				$success = $this->session->flashdata('success');
				if(!empty($success)) {
					echo '<div class="alert alert-success"><b>Success:</b> '.$success.'</div>';
				}

				$images_array = array(
					'1'=>$listing->images->image1,
					'2'=>$listing->images->image2,
					'3'=>$listing->images->image3,
					'4'=>$listing->images->image4,
					'5'=>$listing->images->image5
				);
				$desc_array = array(
					'1'=>$listing->images->desc_1,
					'2'=>$listing->images->desc_2,
					'3'=>$listing->images->desc_3,
					'4'=>$listing->images->desc_4,
					'5'=>$listing->images->desc_5,
				);
				$showImg = false;
				if(!empty($images_array)) {
					$imgs = '<div class="well well-sm">';
					$imgs .= '<ul id="listingThumbs">';
					$i=1;
					foreach($images_array as $key=>$val) {
						if(!empty($val)) {
							if (strpos($val,'../uploads') !== false) {
								$path = base_url();
								$val = ltrim($val, '../');
							} else {
								$path = base_url('listing-images').'/';
							}
							
							$showImg = true;
							$imgs .=  '<li>';
							$imgs .=  '<a href="'.$path.$val.'" data-group="gallery" class="lightbox" title="Image '.$i.': '.ucwords(strtolower($desc_array[$i])).'" data-group="set">
												<img src="'.$path.$val.'" data-group="gallery" class="img-responsive" alt="'.$desc_array[$i].'">
											</a>';
							$imgs .=  '</li>';
						}
						$i++;
					}
					$imgs .=  '</ul>';
					$imgs .=  '<div class="clearfix"></div></div>';
				}
				if($showImg) {
					echo $imgs;
				}
				?>
				<div class="row">
					<div class="col-md-6">
						<h3><?php echo ucwords(strtolower($listing->title)); ?></h3>
						<h5><i class="fa fa-map-marker text-primary"></i> <span id="address"><?php echo htmlentities(ucwords(strtolower($listing->address))); ?></span>, <span id="city"><?php echo htmlentities(ucwords(strtolower($listing->city))).'</span>, <span id="state">'.htmlentities(strtoupper($listing->stateAbv)); ?></span></h5>
						<hr>
						<p><?php echo htmlentities($listing->details); ?></p>
					</div>
					<div class="col-md-6">
						<?php

						if($listing->owner == 199 || $listing->owner == 156) {
							$listing->page->image = '';
							$listing->landlord->bName = '';
							$listing->landlord->name = $listing->contact_name;
							$listing->landlord->phone = $listing->contact_phone;
							if(!empty($listing->contact_email)) {
								$email = '<p><b>Email: </b>'.$listing->contact_email.'</p>';
							}
						}


						echo '<div class="row">';
						if(!empty($listing->page->image)) {
							echo '<div class="col-xs-4">';
							echo '<img src="'.base_url().'public-images/'.$listing->page->image.'" class="img-responsive">';
							echo '</div>';
						} else {
							echo '<div class="col-xs-4">';
							echo '<img src="'.base_url().'public-images/N4R-Profile.png" class="img-responsive" width="100" height="100">';
							echo '</div>';
						}
						echo '<div class="col-xs-8">';
						echo '<h4><b>Contact Info:</b></h4>';
						if(!empty($listing->landlord->bName)) {
							echo '<h5>'.htmlentities(ucwords($listing->landlord->bName)).' - ';
						}
						echo '<b>'.htmlentities(ucwords($listing->landlord->name)).'</b></h5>';
						if($listing->owner == 156 || $listing->owner == 199) {
							echo '<p><h5><b>Call:</b> '.$listing->contact_phone.'</p>';
						} else {
							echo '<h5><b>Call:</b> '.htmlentities("(".substr($listing->landlord->phone, 0, 3).") ".substr($listing->landlord->phone, 3, 3)."-".substr($listing->landlord->phone,6)).'</h5>';
						}
						echo $email;
						if(!empty($listing->page->unique_name)) {
							echo '<a href="http://n4r.rentals/'.$listing->page->unique_name.'" class="btn btn-primary" btn-sm btn-block">View Other Rentals By This Owner</a>';
						}
						echo '</div>';
						echo '</div>';
						?>
						<?php
							if($listing->owner == 199 || $listing->owner == 156) { //199 is todds
								echo '<br><div class="unregistered-listing alert alert-danger"><i class="fa fa-exclamation-triangle"></i> This landlord is not a registered landlord on N4R</div>';
							}
						?>
					</div>

				</div>
				<?php
				if(!empty($associations)) {
					echo '<hr>';
					$imgPreset = false;
					$imgs = '';
					foreach($associations as $key => $val) {
						if(!empty($val->image)) {
							$imgPreset = true;
							$imgs .= '<a href="http://n4r.rentals/'.$val->unique_name.'" target="_blank"><img src="https://network4rentals.com/network/public-images/assoc_thumbs/'.$val->image.'" class="assoc-thumb img-responsive pull-left" width="50" height="50"></a>';
						}
					}
					if($imgPreset) {
						echo '<h3>Proud member of these associations</h3>';
						echo $imgs;

					}
				}
				?>
				<div class="clearfix"></div>
				<?php if($listing->map_correct == 'y') { ?>
					<hr>
					<div class="spacing30"></div>
					<div class="row googleMapOk">
						<div class="col-md-6">
							<div class="white-bkg">
								<div id="map_canvas"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="white-bkg">
								<div id="pano"></div>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>


		</div>
	</div><!-- Row -->
</div>

<div class="modal fade" id="contact" tabindex="-1" role="dialog" aria-labelledby="contact">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo form_open('', array('id'=>'contact-landlord')); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Contact This Landlord</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<div class="form-group">
							<label><span class="text-danger">*</span> Name:</label>
							<input type="text" class="form-control" name="name" maxlength="40" required>
						</div>
						<div class="form-group">
							<label><span class="text-danger">*</span> Email:</label>
							<input type="text" class="form-control" name="email" maxlength="40" required>
						</div>
					</div>
					<div class="col-sm-6">
						<div class="form-group">
							<label>Phone:</label>
							<input type="text" class="form-control" name="phone" maxlength="14" required>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label><span class="text-danger">*</span> Question:</label>
					<textarea name="details" class="form-control" style="height: 200px" maxlength="500" required><?php echo $_POST['details']; ?></textarea>
				</div>
				<div class="form-group">
					<?php echo $recaptcha_html; ?>
				</div>
				<div id="form-errors"></div>
			</div>
			<div class="modal-footer">
				<input type="hidden" class="form-control" value="<?php echo $listing->id; ?>" name="listing_id" maxlength="15" required>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" id="sendit" class="btn btn-primary"><i class="fa fa-envelope"></i> Send</button>
			</div>
			</form>
		</div>
	</div>
</div>