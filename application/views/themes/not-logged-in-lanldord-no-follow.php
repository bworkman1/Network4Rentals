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
		<!-- Le styles -->
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
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

			foreach($css as $file){
				echo "\n\t\t";
				?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
			} echo "\n\t";
		?>
		<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&region=us"></script>
		<!-- Le fav and touch icons -->
		<link rel="icon" href="<?=base_url()?>assets/themes/default/images/favicon.gif" type="image/gif">
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon"/>
		<meta property="og:image" content="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png"/>
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
	</head>

	<body>
	     <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<div id="top_bar">
			<div class='container'>
				<div class='row'>
					<div class='col-md-6'>
						Improving Landlord &amp; Tenant Relations Nationwide
					</div>
					<div class='col-md-6'>
						<!-- 
						<ul id="socials">
							<li class="social-facebook">
								<a href="https://www.facebook.com/network4rentals" target="_blank" title="" data-original-title="Find Network 4 Rentals On Facebook">
									<i class="fa fa-facebook"></i>
								</a>
							</li>
							<li class="social-youtube">
								<a href="https://www.youtube.com/channel/UCkGnqjRnsmCB-Nxwgl7f83w/videos" target="_blank" title="" data-placement="bottom" data-original-title="Find Network For Rentals on Youtube">
									<i class="fa fa-youtube"></i>
								</a>
							</li>
							<li>
								<a href="https://twitter.com/Network4Rentals" target="_blank"  title="" data-original-title="Find Network 4 Rentals On Twitter">
									<i class="fa fa-twitter"></i>
								</a>
							</li>
							<li>
								<a href="https://www.linkedin.com/company/network-4-rentals-llc/" target="_blank" data-original-title="Find Network For Rentals on LinkedIn">
									<i class="fa fa-linkedin"></i>
								</a>
							</li>
							<li>
								<a href="https://network4rentals.com/rss" target="_blank" data-placement="bottom" title="" data-original-title="Follow Our RSS Feed">
									<i class="fa fa-rss"></i>
								</a>
							</li>
							<li>
								<a href="https://www.google.com/+Network4rentals" title="" target="_blank" data-original-title="Find Network For Rentals on Google+">
									<i class="fa fa-google-plus"></i>
								</a>
							</li>
						</ul>
						-->
					</div>
				</div>
			</div>
		</div>
		<div id="header">
			<div class='container'>
				<div class='row'>
					<div class='col-sm-5'>
						<a href="https://network4rentals.com"><img class="img-responsive logo" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
					<div class='col-sm-7'>
					
					</div>
				</div>
			</div>
		</div>
		
		<div id="content" class="container">
			<hr>
			<div class="row">
				<div class="col-md-2">
					<nav class="navbar navbar-default" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand visible-xs visible-sm" href="#">Menu</a>
						</div>		
						<div class="collapse navbar-collapse">
							<ul class="nav nav-stacked">
								<li><a href="http://www.network4rentals.com"><i class="fa fa-home"></i> Home</a></li>
								<li><a href="http://www.network4rentals.com/network/renters/login"><i class="fa fa-building-o"></i> Tenant</a></li>
								<li><a href="http://www.network4rentals.com/network/landlords/"><i class="fa fa-money"></i> Landlord</a></li>
								<!-- <li><a href="http://www.network4rentals.com/network/contractors/login.php"><i class="fa fa-wrench"></i> Contractor</a></li> -->
								<li><a href="http://network4rentals.com/faqs/"><i class="fa fa-info"></i> FAQ"s</a></li>              
								<li><a href="http://network4rentals.com/help-support/"><i class="fa fa-question"></i> Help/Support</a></li>              
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>
				<div class="col-md-8">
					<?php echo $output;?>
				</div>
				<div class="col-md-2">
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
									echo '<li class="text-center well well-sm">';
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
								//show default ad here
								echo '<ul class="sponsoredList">';
									echo '<li class="text-center" style="padding: 5px; border-left: 1px solid #ccc;">';
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
			<hr/>
		</div> <!-- container -->
		

	
		<?php
			/** -- Copy from here -- */


			foreach($js as $file){
					echo "\n\t\t";
					?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";

			/** -- to here -- */
		?>	
			<script type="text/javascript">
				$(document).ready(function() {
					$('#nolandlord').on('shown.bs.modal', function (e) {
						$(this).find('input[type=text]:visible:first').focus();
					})
					$('#nolandlord').modal('show').trigger('shown');
				});
			</script>
		<footer>
			<div class='container'>
				<div class="row text-center">
					<div class="col-sm-6">
						&copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved</a>
					</div>
					<div class="col-sm-6">
						Developed &amp; Hosted By EMF Web Solutions
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
