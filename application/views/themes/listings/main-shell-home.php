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
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/animate.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/custom-landlord.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Lora:400,700' rel='stylesheet' type='text/css'>
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
						<a href="https://network4rentals.com"><img class="img-responsive logo  wow fadeInUp" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
			
		<?php echo $output;?>
			
			

	
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
