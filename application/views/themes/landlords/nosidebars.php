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
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
		<![endif]-->
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&region=us"></script>
		<!-- Le fav and touch icons -->
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
		<div id="top_bar" class="hidden-print">
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
			
				
					<?php echo $output;?>
				
				
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
				<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
	</body>
</html>
