<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<?php if(empty($title)) { ?>
			<title><?php echo 'Network 4 Rentals | '.$this->uri->segment(2); ?></title>
		<?php } else { ?>
			<title><?php echo $title; ?></title>
		<?php } ?>
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/animate.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/custom-landlord.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<?php
			if(!empty($meta))
			foreach($meta as $name=>$content){
				echo "\n\t\t";
				?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
					 }
			echo "\n";

			if(!empty($canonical))
			{
				echo "\n\t\t";
				?><link rel="canonical" href="<?php echo $canonical?>" /><?php

			}
			echo "\n\t";


			foreach($js as $file){
					echo "\n\t\t";
					?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";
	
			
			foreach($css as $file){
				echo "\n\t\t";
				?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
			} echo "\n\t";
		?>
	
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->

		<link rel="icon" href="<?=base_url()?>assets/themes/default/images/favicon.gif" type="image/gif">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon"/>
		<link rel="image_src" href="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png" />
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-43913727-1', 'auto');
			ga('require', 'linkid', 'linkid.js');
			ga('send', 'pageview');
		</script>
		<!--Start of Zopim Live Chat Script-->
	<script type="text/javascript">
	window.$zopim||(function(d,s){var z=$zopim=function(c){
	z._.push(c)},$=z.s=
	d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
	_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
	$.src='//v2.zopim.com/?3o3ZM56IyHsAJ3CUO7MB7a42jgLlLeRE';z.t=+new Date;$.
	type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
	</script>
	<!--End of Zopim Live Chat Script-->
	</head>

	<body>
	     <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<div id="top-bar" class="hidden-print">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md-6 hidden-sm hidden-xs'>
						<h5>Improving Landlord &amp; Tenant Relations Nationwide</h5>
					</div>
					<div class='col-md-6'>
						<ul id="top-bar-menu">
							<li><a href="<?php echo base_url('renters/login'); ?>">Renters Login</a> | </li>
							<li><a href="<?php echo base_url('landlords/login'); ?>">Landlords Login</a> | </li>
							<li><a href="<?php echo base_url('contractors/login'); ?>">Contractors Login</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div id="header">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-lg-2 col-md-3 col-sm-8 col-xs-9'>
						<a href="https://network4rentals.com"><img class="img-responsive logo wow fadeInUp" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="search-area">
			<div class="container-fluid">
				<?php echo form_open('listings/search/'); ?>
					<div class="row">
						<div class="col-md-2 col-sm-4 col-xs-6">
							<input type="text" name="zipcode" maxlength="5" class="form-control input-lg" value="<?php echo $this->session->userdata('zip'); ?>" required placeholder="Zip Code">
						</div>
						<div class="col-md-2 col-sm-4 col-xs-6">
							<select name="distance" class="form-control input-lg drop-select">
								<option value="">Search Radius</option>
								<?php 
									$radiusSelected = $this->session->userdata('distance');
									$radiusOptions = array('5', '10', '15', '25', '50');
									foreach($radiusOptions as $val) {
										if($radiusSelected == $val) {
											echo '<option value="'.$val.'" selected>'.$val.' Mile Radius</option>';
										} else {
											echo '<option value="'.$val.'">'.$val.' Mile Radius</option>';
										}
									}
								?>							
							</select>
						</div>
						<div class="col-md-2 col-sm-4 col-xs-6">
							<select name="beds" class="form-control input-lg">
								<option value="">Any Bedrooms</option>
								<?php 
									$bedsSelected = $this->session->userdata('beds');
									for($i=1;$i<6;$i++) {
										if($bedsSelected == $i) {
											echo '<option value="'.$i.'" selected>'.$i.'+ Bedrooms</option>';
										} else {
											echo '<option value="'.$i.'">'.$i.'+ Bedrooms</option>';
										}
									}
								?>			
							</select>
						</div>
						<div class="col-md-2 col-sm-4 col-xs-6">
							<select name="baths" class="form-control input-lg">
								<option value="">Any Bathrooms</option>
								<?php 
									$bathsSelected = $this->session->userdata('baths');
									for($i=1;$i<6;$i++) {
										if($bathsSelected == $i) {
											echo '<option value="'.$i.'" selected>'.$i.'+ Bathrooms</option>';
										} else {
											echo '<option value="'.$i.'">'.$i.'+ Bathrooms</option>';
										}
									}
								?>		
							</select>
						</div>		
						<div class="col-md-2 col-sm-4 col-xs-6 text-center">
							<a href="#" id="more-filters"><i class="fa fa-caret-down fa-2x"></i> <span class="label label-success"></span> More Filters</a>
						</div>	
						<div class="col-md-2 col-sm-4 col-xs-6">
							<button type="submit" class="btn btn-lg btn-info btn-block"><i class="fa fa-search"></i> Search</button>
						</div>					
					</div>
					
					<div id="filtered-options">
						<hr>
						<?php
							$listingOptions = array('laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'shopping', 'single_lvl', 'shed', 'park', 'city', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool');
							$newArray = array();
							foreach($listingOptions as $val) {
								$newArray[$val] = $this->session->userdata($val);
							}
						?>
						<div class="row">
							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="checkbox">
									<label for="amenities-3"><input type="checkbox" <?php if($newArray['laundry_hook_ups'] == 'y') {echo "checked";} ?> name="laundry_hook_ups" id="amenities-3" value="y" /> Clothes Washer / Dryer Hook-Ups</label>
								</div>
								<div class="checkbox">
									<label for="amenities-5"><input type="checkbox" <?php if($newArray['off_site_laundry'] == 'y') {echo "checked";} ?> name="off_site_laundry" id="amenities-5" value="y" /> Offsite Laundry</label>
								</div>
								<div class="checkbox">
									<label for="amenities-6"><input type="checkbox" <?php if($newArray['on_site_laundry'] == 'y') {echo "checked";} ?> name="on_site_laundry" id="amenities-6" value="y" /> Onsite Laundry</label>
								</div>
								<div class="checkbox">
									<label for="amenities-7"><input type="checkbox" <?php if($newArray['basement'] == 'y') {echo "checked";} ?> name="basement" id="amenities-7" value="y" /> Basement</label>
								</div>
								<div class="checkbox">
									<label for="amenities-11"><input type="checkbox" <?php if($newArray['shopping'] == 'y') {echo "checked";} ?> name="shopping" id="amenities-11" value="y" /> Near Shopping / Entertainment</label>
								</div>
							</div>
							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="checkbox">
									<label for="amenities-8"><input type="checkbox" <?php if($newArray['single_lvl'] == 'y') {echo "checked";} ?> name="single_lvl" id="amenities-8" value="y" /> Single Level Floor Plan</label>
								</div>
								<div class="checkbox">
									<label for="amenities-9"><input type="checkbox" <?php if($newArray['shed'] == 'y') {echo "checked";} ?> name="shed" id="amenities-9" value="y" /> Storage Shed</label>
								</div>
								<div class="checkbox">
									<label for="amenities-10"><input type="checkbox" <?php if($newArray['park'] == 'y') {echo "checked";} ?> name="park" id="amenities-10" value="y" /> Near A Park</label>
								</div>	
								<div class="checkbox">
									<label for="amenities-12"><input type="checkbox" <?php if($newArray['city'] == 'y') {echo "checked";} ?> name="city" id="amenities-12" value="y" /> Within City Limits</label>
								</div>		
								<div class="checkbox">
									<label for="amenities-13"><input type="checkbox" <?php if($newArray['outside_city'] == 'y') {echo "checked";} ?> name="outside_city" id="amenities-13" value="y" /> Outside City Limits</label>
								</div>		
							</div>
							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="checkbox">
									<label for="amenities-14"><input type="checkbox" <?php if($newArray['deck_porch'] == 'y') {echo "checked";} ?> name="deck_porch" id="amenities-14" value="y" /> Deck / Porch</label>
								</div>		
								<div class="checkbox">
									<label for="amenities-15"><input type="checkbox" <?php if($newArray['large_yard'] == 'y') {echo "checked";} ?> name="large_yard" id="amenities-15" value="y" /> Large Yard</label>
								</div>
								<div class="checkbox">
									<label for="amenities-16"><input type="checkbox" <?php if($newArray['fenced_yard'] == 'y') {echo "checked";} ?> name="fenced_yard" id="amenities-16" value="y" /> Fenced Yard</label>
								</div>		
								<div class="checkbox">
									<label for="amenities-17"><input type="checkbox" <?php if($newArray['partial_utilites'] == 'y') {echo "checked";} ?> name="partial_utilites" id="amenities-17" value="y" /> Some Utilities Included</label>
								</div>	
							</div>
							<div class="col-md-3 col-sm-6 col-xs-6">
								<div class="checkbox">
									<label for="amenities-18"><input type="checkbox" <?php if($newArray['all_utilities'] == 'y') {echo "checked";} ?> name="all_utilities" id="amenities-18" value="y" /> Utilities Included</label>
								</div>		
								<div class="checkbox">
									<label for="amenities-19"><input type="checkbox" <?php if($newArray['appliances'] == 'y') {echo "checked";} ?> name="appliances" id="amenities-19" value="y" /> Appliances Included</label>
								</div>		
								<div class="checkbox">
									<label for="amenities-20"><input type="checkbox" <?php if($newArray['furnished'] == 'y') {echo "checked";} ?> name="furnished" id="amenities-20" value="y" /> Fully Furnished </label>
								</div>		
								<div class="checkbox">
									<label for="amenities-21"><input type="checkbox" <?php if($newArray['pool'] == 'y') {echo "checked";} ?> name="pool" id="amenities-21" value="y" /> Pool</label>
								</div>
								<button id="clearFilters" class="btn btn-sm btn-danger pull-right">Uncheck All</button>
							</div>
						</div>
					</div>
					
				</form>
			</div>
		</div>
		
		<div id="content" class="container-fluid">
			<div class="row">
				<div class="col-md-10">
					<br>
					<?php echo $output;?>
				</div>
				<div class="col-md-2">
					<div class="white-bkg">
						<?php
							$zip = $this->session->userdata('ad_zipCode');
							if(empty($zip)) {
								$this->load->library('ip2location');
								$locations = $this->ip2location->getCity($_SERVER['REMOTE_ADDR']);
								foreach ($locations as $field => $val) {
									if($field=='zipCode') {
										$this->session->set_userdata('ad_zipCode', $val);
									}
								}
							}
							
							if(!empty($result)) {
								echo '<ul class="sponsoredList">';
								foreach($result as $key => $val) {
									if(count($val)>0) {
										echo '<li class="text-center">';
											echo '<a href="'.base_url().'advertisers/post-clicked-on/'.$val->advertiser_id.'/'.$val->advertiser_zips_id.'" target="_blank">';
												if(!empty($val->title)) {
													echo '<h4><b>'.htmlentities($val->title).'</b></h4>';
												}
												if(!empty($val->desc)) {
													echo '<p>'.htmlentities($val->desc).'</p>';
												}
												if(!empty($val->ad_image)) {
													echo '<img class="img-responsive sponsorLogo" src="'.base_url().'public-images/emf_profilepic_(1).png" alt="'.htmlentities($val->title).'">';
												}
												if(!empty($val->bName)) {
													echo '<div class="bottomBoxAd">';
														echo '<b>'.$val->bName.'</b><br>';
														echo $val->phone;
													echo '</div>';
												}
											echo '</a>';
										echo '</li>';
									}
								}
								echo '</ul>';
							} else {
															
								echo '<ul class="sponsoredList">';
									echo '<li class="text-center">';
										echo '<a href="'.base_url().'advertisers/" target="_blank">';  
											echo '<h4 style="background-color: #428BCA; color: #ffffff; padding: 10px 0;"><b>Ad Space Available</b></h4>';
											echo '<h4><b>Choose Your Target Group &amp; Area</b></h4>';
											echo '<ul style="padding: 15px 0; margin: 0; border-bottom: 1px solid #666; border-top: 1px solid #666;">';
												echo '<li style="margin: 3px 0 !important;"><i class="fa fa-thumbs-o-up text-primary"></i> Landlords</li>';
												echo '<li style="margin: 3px  0 !important;"><i class="fa fa-thumbs-o-up text-primary"></i> Tenants</li>';
												echo '<li style="margin: 3px 0 !important;"><i class="fa fa-thumbs-o-up text-primary"></i> Contractors</li>';
												echo '<li style="margin: 3px 0 !important;"><i class="fa fa-thumbs-o-up text-primary"></i> Property Listings</li>';
											echo '</ul>';
											echo '<p><span class="text-danger">*</span> Includes Self Branded Website</p>';
											echo '<p><span class="text-danger">*</span> No Hidden or Adjustable Fees</p>';
											echo '<p><span class="text-danger">*</span> Update As Many Times as You Want and from Anywhere</p>';
										echo '</a>';
									echo '</li>';
								echo '</ul>';
							}
						
						?>
					</div>
				</div>
			</div>
			<hr/>
		</div> <!-- container -->
		

		
		<div class="blue-bkg">
			<div class="container-fluid">
				<div class="row"> 
					<div class="col-md-4"> 
						<div class="white-bkg wow fadeInUp">
							<h3><i class="fa fa-home"></i> Have a Rental?</h3>
							<p>Sign up for a free account and manage your rental listings with ease. Included with your rental listing/landlord account is the option to manage all your rentals in the same system. Check out our <a href="https://network4rentals.com/faqs/">FAQs</a> to learn more.</p>
							<a class="btn btn-primary" href="<?php echo base_url(); ?>landlords/create-account"><i class="fa fa-user"></i> Create Landlord Account</a>
						</div>
					</div>
					<div class="col-md-4"> 
						<div class="white-bkg wow fadeInUp">
							<h3><i class="fa fa-comments"></i> Communication is the Key</h3>
							<p>Communicating with your landlord has never been easier. With our service request system, you can submit service requests to your landlord at your convenience. Check out our <a href="https://network4rentals.com/faqs/">FAQs</a> to learn more.</p>
							<a class="btn btn-primary" href="<?php echo base_url(); ?>renters/create-renter-account"><i class="fa fa-user"></i> Create Renter Account</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="white-bkg wow fadeInUp">				
							<h3><i class="fa fa-envelope-o"></i> Want to be Notified About Listings?</h3>
							<p>Create a renter account for free, or if you already have an account, log in. Once you're logged in, select ISO (In Search Of) from the menu on the left. We'll notify you when a property matching your wishes is listed.</p>
							<p><a href="https://network4rentals.com/network/renters/create-renter-account" class="btn btn-primary">Create Renter Account</a></p>
							
						</div>
					</div>
				</div>
				<br>
				<div class="white-bkg wow fadeInUp">
					<p><img src="https://network4rentals.com/network/public-images/metro_accepted.jpg" class="pull-left" alt="Equal Housing Opportunity" style="padding: 5px 15px 0 0;">
					<small>All real estate advertised herein is subject to the Federal Fair Housing Act, which makes it illegal to advertise "any preference, limitation, discrimination because of race, color, religion, sex, handicap, familial status, or national origin, or intention to make any such preference, limitation or discrimination." We will not knowingly accept any advertising which is in violation of the law. All persons are hereby informed that all dwellings advertised are on an equal opportunity basis.</small></p>
				</div>
			</div>
		</div>
		
		
		
	
		<script type="text/javascript">
			$(document).ready(function() {
				$('#nolandlord').on('shown.bs.modal', function (e) {
					$(this).find('input[type=text]:visible:first').focus();
				})
				$('#nolandlord').modal('show').trigger('shown');
			});
		</script>
		<footer>
			<div class='container-fluid'>
				<div class="row">
					<div class="col-md-6">
						&copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved</a>
					</div> 
					<div class="col-md-6 text-right">
						Developed &amp; Hosted By EMF Web Solutions
					</div>
				</div>
			</div>
		</footer>
		
		<?php
			$userLoggedIn = $this->session->userdata('user_email');
			if(!empty($userLoggedIn)) {
		?>
			
		<?php } ?>
		
	</body>
</html>
