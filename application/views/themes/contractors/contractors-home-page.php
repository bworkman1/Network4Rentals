<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<title>Contractor Advertising | Network 4 Rentals</title>
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta property="og:image" content="https://network4rentals.com/network/public-images/network4rentals-share-fb.jpg" />
		<meta property="og:description" content="We provide targeted advertisement for contractors to landlords in your area." />

		<!-- Le styles -->
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="https://network4rentals.com/wp-content/themes/Network4Rentals.new/css/bootstrap-social.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/contractors/styles.css" rel="stylesheet">
		<link href='http://fonts.googleapis.com/css?family=Average|Fjalla+One' rel='stylesheet' type='text/css'>
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

		<!-- Le fav and touch icons -->
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
		<div id="top_bar" class="homeStyles" style="margin-bottom: 0px;">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-sm-5 hidden-xs'>
						
					</div>
					<div class='col-sm-7'>
						<ul id="topSocials">
							<li>
								<a href="https://www.facebook.com/network4rentals" target="_blank" class="btn btn-social btn-facebook">
									<i class="fa fa-facebook"></i> &nbsp;
								</a>
							</li>
							<li>
								<a href="https://plus.google.com/+Network4rentals" target="_blank" class="btn btn-social btn-google-plus">
									<i class="fa fa-google-plus"></i> &nbsp;
								</a>
							</li>	
							<li>
								<a href="https://twitter.com/Network4Rentals" target="_blank" class="btn btn-social btn-twitter">
									<i class="fa fa-twitter"></i> &nbsp;
								</a>
							</li>
							<li>
								<a href="https://www.linkedin.com/company/network-4-rentals-llc" target="_blank" class="btn btn-social btn-linkedin">
									<i class="fa fa-linkedin fa-fw"></i>  &nbsp;
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		
		<div id="header">
			<div class='container'>
				<div class='row'>
					<div class='col-lg-3 col-md-4'>
						<a href="https://network4rentals.com/"><img class="img-responsive logo" src="http://n4rlocal.com/imgs/N4RLocal-Logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="full-width-image">
			<div class="container text-center">
				<h1>Finally an Affordable Way to Land New Jobs In Your Area</h1>
				<div class="row">
					<div class="col-md-4">
						<div class="benefit">
							<h3>Website</h3>
							<p>independently searchable website allows people to search your business anywhere on the web.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="benefit">
							<h3>Service Request Postings</h3>
							<p>Whenever a landlord receives a work request in your area he will see your details and contact info directly on the request.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="benefit">
							<h3>Unlimited Posting Updates</h3>
							<p>Change your ad as many times as you want and from any mobile device.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="benefit">
							<h3>Easy Forwarding</h3>
							<p>Not only does the landlord have your info, but he can also forward requests and instructions to you with the push of one button.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="benefit">
							<h3>Help Your Current Customers</h3>
							<p>Not only are you seen by many potentially new customers: your current home rental customers can send you work much easier.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
					<div class="col-md-4">
						<div class="benefit">
							<h3>Listing on Resources Page</h3>
							<p>Landlord in need of service can search for you by name, service type, or zip code whenever needed.</p>
							<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-success">Sign Up Today</a>
						</div>
					</div>
				</div>
				<h2>"Spaces are going fast! Sign up today and secure your area before it's gone!"</h2>
			</div>
		</div>
		
		<div id="content" class="container">
			<hr>
			<div class="row">
				<div class="col-md-12">
					<div class="row">
						<div class="col-md-4">
							<div class="panel panel-success">
								<div class="panel-heading">
									What Are You Waiting For?
								</div>
								<div class="panel-body">
									<p>Advertise directly on service requests that are being sent to landlords in your chosen area, for your chosen service.</p>

									<h4>Only $299.99 a year</h4>
									<hr>
									<div class="row">
										<div class="col-xs-6">
											<a href="<?php echo base_url(); ?>contractor/create-account" class="btn btn-info"><i class="fa fa-user"></i> Sign Up Now</a>
										</div>
										<div class="col-xs-6 text-right">
											<a href="<?php echo base_url(); ?>contractor/login" class="btn btn-success"><i class="fa fa-lock"></i> Login</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div class="embed-responsive embed-responsive-16by9">
								<iframe src="https://www.youtube.com/embed/KpdV4H932rk" frameborder="0" allowfullscreen></iframe>
							</div>
						</div>
					</div>	
				</div>
				
			</div>
			<hr/>
		</div> <!-- container -->
		

	

		<footer>
			<div class='container-fluid'>
				<div class="row text-center">
					<div class="col-sm-6">
						&copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved</a>
					</div>
					<div class="col-sm-6">
						Developed &amp; Hosted By <a href="https://emf-websolutions.com/">EMF Web Solutions</a>
					</div>
				</div>
			</div>
		</footer>
		
		<div id="support-help">
			<div id="helpButton" data-toggle="modal" data-target="#helpSupport"><i class="fa fa-question-circle"></i> Need Help?</div>
		</div>
		
		<div class="modal fade" id="helpSupport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<h4 class="modal-title" id="myModalLabel">Help And Support</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-sm-6">
								<h3><i class="fa fa-phone text-success"></i> By Phone</h3>
								<hr>
								<b>Customer Service:</b> (740) 403-7661
							</div>
							<div class="col-sm-6">
								<h3><i class="fa fa-envelope text-success"></i> By Email</h3>
								<hr>
								<a href="http://network4rentals.com/help-support/" target="_blank" class="btn btn-default btn-sm">On-line</a>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<?php
			foreach($js as $file){
					echo "\n\t\t";
					?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";
		?>	
				<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
	</body>
</html>
