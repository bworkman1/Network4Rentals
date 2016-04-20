<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<title><?php echo 'Network 4 Rentals | '.$this->uri->segment(2); ?></title>
		<meta charset="UTF-8">
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/contractors/styles.css" rel="stylesheet">
		<link href='//fonts.googleapis.com/css?family=Average%7CFjalla+One' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
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
		<!--[if lt IE 9]>
		  <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon"/>
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
		<div id="top_bar">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md-5 hidden-xs hidden-sm'>
						
					</div>
					<div class='col-md-7'>
						
					</div>
				</div>
			</div>
		</div>
		
		<div id="content" class="container-fluid">
			<div class="row">
				<div class="col-sm-2 remove-column-padding-left mainMenu">
					<a href="https://network4rentals.com/"><img class="img-responsive logo" src="https://n4rlocal.com/imgs/N4RLocal-Logo.png" alt="Network 4 Rentals"></a>
					<div class="menu-column"> 
						<nav class="navbar navbar-default" role="navigation">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand visible-xs visible-sm" href="#">Menu</a>
							</div>		
							<div class="collapse navbar-collapse remove-column-padding-left">
								<ul class="nav nav-stacked">
									<li class="<?php if($this->uri->segment(2)=="notifications"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/notifications"><i class="fa fa-bell fa-fw"></i> Notifications</a></li>
									<li class="<?php if($this->uri->segment(2)=="my-calendar"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/my-calendar"><i class="fa fa-calendar fa-fw"></i> My Calendar</a></li>
									<li class="<?php if($this->uri->segment(2)=="my-account"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/my-account"><i class="fa fa-gears fa-fw"></i> Account Details</a></li>
									<li class="<?php if($this->uri->segment(2)=="manage-zips"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/manage-zips"><i class="fa fa-map-marker fa-fw"></i> My Services &amp; Zips</a></li>
									<?php
										if($this->uri->segment(2)=="purchase-ads" || $this->uri->segment(2)=="past-ads" || $this->uri->segment(2)=="current-ads") {
											echo '<li class="dropdown open">';
										} else {
											echo '<li class="dropdown">';
										}
									?>
									
									
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bullseye fa-fw"></i> Additional Advertising <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
										
											<li class="<?php if($this->uri->segment(2)=="purchase-ads"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/purchase-ads"><i class="fa fa-dollar fa-fw"></i> Purchase Ads</a></li>
											
											<li class="<?php if($this->uri->segment(2)=="current-ads"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/current-ads"><i class="fa fa-user fa-fw"></i> Current Ads</a></li>
											
											<li class="<?php if($this->uri->segment(2)=="past-ads"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/past-ads"><i class="fa fa-user fa-fw"></i> Past Ads</a></li>
										</ul>
									</li>
									
									
									<?php
										if($this->uri->segment(2)=="payment-settings" || $this->uri->segment(2)=="view-payments") {
											echo '<li class="dropdown open">';
										} else {
											echo '<li class="dropdown">';
										}
									?>
								
								
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bullseye fa-fw"></i> Payments <span class="caret"></span></a>
										<ul class="dropdown-menu" role="menu">
										
											<li class="<?php if($this->uri->segment(2)=="payment-settings"){echo "active";}?>"><a href="<?php echo base_url('contractor/payment-settings'); ?>"><i class="fa fa-gear fa-fw"></i> Payment Settings</a></li> 
											
											<li class="<?php if($this->uri->segment(2)=="view-payments"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/view-payments"><i class="fa fa-money fa-fw"></i> View Payments</a></li>
											
											<li class="<?php if($this->uri->segment(2)=="create-invoice"){echo "active";}?>"><a href="<?php echo base_url('contractor/create-invoice'); ?>"><i class="fa fa-plus-circle fa-fw"></i> Create Invoice</a></li>
											
											<li class="<?php if($this->uri->segment(2)=="view-invoices"){echo "active";}?>"><a href="<?php echo base_url('contractor/view-invoices'); ?>"><i class="fa fa-list fa-fw"></i> All Invoice</a></li>
										</ul>
									</li>
									
									
									
									<li class="<?php if($this->uri->segment(2)=="stats"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/stats"><i class="fa fa-bar-chart fa-fw"></i> Account Stats</a></li>
									
									<li class="<?php if($this->uri->segment(2)=="public-page"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/public-page"><i class="fa fa-laptop fa-fw"></i> My N4R Public Profile</a></li>
									<li class="<?php if($this->uri->segment(2)=="service-requests"){echo "active";}?>"><a href="<?php echo base_url(); ?>contractor/service-requests"><i class="fa fa-wrench fa-fw"></i> My Service Request</a></li>
									<li><a href="<?php echo base_url('contractor/resources'); ?>"><i class="fa fa-exclamation fa-fw"></i> Resources</a></li>
									<li><a href="https://network4rentals.com/faqs/"><i class="fa fa-info fa-fw"></i> FAQ"s</a></li>                           
									<li><a href="https://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help/Support</a></li> 
									<li><a href="<?php echo base_url(); ?>contractor/logout"><i class="fa fa-lock fa-fw"></i> Logout</a></li>
								</ul>
							</div><!-- /.navbar-collapse -->
						</nav>
						
						
						
					</div>
				</div>
				<div class="col-sm-8">
					<hr>
					<?php echo $output;?>
				</div>
				<div class="col-md-2">
                    <?php echo $this->load->get_section('sidebar'); ?>
				</div>
			</div>
			<hr/>
		</div> <!-- container -->

		<div class="clearfix"></div>
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
		
		<div id="support-help" class="hide">
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
								<a href="https://network4rentals.com/help-support/" target="_blank" class="btn btn-default btn-sm">On-line</a>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
		<div class="overlay"></div>
	
		<?php
			foreach($js as $file){
					echo "\n\t\t";
					?><script src="<?php echo $file; ?>" type="text/javascript" charset="utf-8"></script><?php
			} echo "\n\t";
		?>	
				<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
	</body>
</html>
