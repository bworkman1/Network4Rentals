<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<title><?php echo 'N4R | '.ucwords(str_replace('-', ' ', $this->uri->segment(2))); ?></title>
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- the styles -->
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/custom.css" rel="stylesheet">
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
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
		<link rel="icon" href="<?=base_url()?>assets/themes/default/images/favicon.gif" type="image/gif">
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

	<body style="background-color: #F3F3F4;">
	     <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<div id="top_bar">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-sm-6 hidden-xs hidden-sm'>
						Improving Landlord &amp; Tenant Relations Nationwide
					</div>
					<div class='col-sm-6 text-right'>
							<?php 
									$incomplete_icon = '<i class="fa fa-exclamation-triangle text-danger fa-fw"></i>';
									$count = 0;
									$this->db->limit(1);
									$results = $this->db->get_where('renter_history', array('tenant_id'=>$this->session->userdata('user_id'), 'current_residence'=>'y'));
									if($results->num_rows()>0) {
										$row = $results->row();
										if(empty($row->checklist_id)) {
											$count++;
											$notification[] = '<a href="'.base_url().'renters/checklist-form/'.$row->id.'">'.$incomplete_icon.' Check-list Incomplete</a>';
										} 
									} else {
										$notification[] = '<a href="'.base_url().'renters/add-landlord">'.$incomplete_icon.' Add New Landlord</a>';
										$count++;
									}
									echo '<div id="notifications">';
										echo '<button class="btn btn-warning notification-btn">';
										if($count>0) {
											echo '<span class="badge badge-primary">'.$count.'</span>';
										}
										echo '<i class="fa fa-bell"></i> Notifications</button>';
										echo '<ul id="notifications-box">';
											if(!empty($notification)) {
												foreach($notification as $val) {
													echo '<li>'.$val.'</li>';
												}
											} else {
												echo '<li><i class="fa fa-thumbs-up text-success"></i> No New Notifications</li>';
											}
										echo '</ul>';
									echo '</div>';
							?>
					</div>
				</div>
			</div>
		</div>
		<div id="header">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md-3 col-sm-6'>
						<a href="https://network4rentals.com"><img class="img-responsive logo img-center" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="content" class="container-fluid">
			<hr>
			<div class="row">
				<div class="col-md-2 menu-column">
					<div class="container visible-sm visible-xs">
					<button class="slideout-menu-toggle visible-sm visible-xs btn btn-primary btn-block"><i class="fa fa-bars"></i> Toggle Menu</button>
					<br>
					</div>
					<div class="slideout-menu">
						<h3>Menu <button class="slideout-menu-toggle btn btn-primary">×</button></h3>
						<ul>
							<li class="<?php if($this->uri->segment(2)=="activity"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/activity"><i class="fa fa-home fa-fw"></i> Activity</a></li>
							<li class="<?php if($this->uri->segment(2)=="current-landlord"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/current-landlord"><i class="fa fa-building-o fa-fw"></i> My Rental Info</a></li>
							<li class="<?php if($this->uri->segment(2)=="edit-account"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/edit-account"><i class="fa fa-pencil-square-o fa-fw"></i> My Account</a></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-angle-down"></i> Service Request</a>
								<ul class="dropdown-menu" role="menu">
									<li class="<?php if($this->uri->segment(2)=="submit-request"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/submit-request"><i class="fa fa-envelope fa-fw"></i> Submit New Request</a></li>
									<li class="<?php if($this->uri->segment(2)=="view-service-request" || $this->uri->segment(2) == 'view-request'){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/view-service-request"><i class="fa fa-user"></i> My Service Request</a></li>
								</ul>
							</li>
							<li  class="<?php if($this->uri->segment(1)=="listings"){echo "active";}?>"><a href="<?php echo base_url(); ?>listings"><i class="fa fa-search fa-fw"></i> Search Listings</a></li>  
							<li  class="<?php if($this->uri->segment(2)=="message-landlords"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/message-landlords"><i class="fa fa-envelope-o fa-fw"></i> Messages</a></li>  
							<li class="dropdown">
								<a href="<?php echo base_url(); ?>renters/my-history" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-angle-down"></i> Rental History</a>
								<ul class="dropdown-menu" role="menu">
									<li  class="<?php if($this->uri->segment(2)=="my-history"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/my-history"><i class="fa fa-calendar-o"></i> My History</a></li>
									<li  class="<?php if($this->uri->segment(2)=="rent-receipt"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/rent-receipt"><i class="fa fa-dollar"></i> Rent Payment Log</a></li>
								</ul>
								<div class="clearfix"></div>
							</li> 
							<li class="<?php if($this->uri->segment(2)=="pay-rent"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/pay-rent"><i class="fa fa-dollar"></i> Pay Rent Online or Create Receipt</a></li>
							<li><a href="<?php echo base_url(); ?>renters/in-search-of"><i class="fa fa-search"></i> In Search Of</a></li>
							<li><a href="<?php echo base_url(); ?>renters/videos"><i class="fa fa-video-camera fa-fw"></i> Videos</a></li>   
							<li><a href="https://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help &amp; Support</a></li>
							<li><a href="<?php echo base_url(); ?>renters/logout"><i class="fa fa-power-off fa-fw"></i> Log Out</a> </li>
						</ul>
					</div>
					
					<nav class="navbar navbar-default hidden-sm hidden-xs" role="navigation">
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="icon-bar white"></span>
								<span class="icon-bar white"></span>
								<span class="icon-bar white"></span>
							</button>
							<a class="navbar-brand visible-xs visible-sm" href="#">Menu</a>
						</div>		
						<div class="collapse navbar-collapse">
							<ul class="nav nav-stacked">
								<li class="<?php if($this->uri->segment(2)=="activity"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/activity"><i class="fa fa-home fa-fw"></i> Activity</a></li>
								<li class="<?php if($this->uri->segment(2)=="current-landlord"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/current-landlord"><i class="fa fa-building-o fa-fw"></i> My Rental Info</a></li>
								<li class="<?php if($this->uri->segment(2)=="edit-account"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/edit-account"><i class="fa fa-pencil-square-o fa-fw"></i> My Account</a></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench fa- fw"></i> Service Request <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										<li class="<?php if($this->uri->segment(2)=="submit-request"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/submit-request"><i class="fa fa-envelope fa-fw"></i> Submit New Request</a></li>
										<li class="<?php if($this->uri->segment(2)=="view-service-request" || $this->uri->segment(2) == 'view-request'){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/view-service-request"><i class="fa fa-user"></i> My Service Request</a></li>
									</ul>
								</li>
								<li  class="<?php if($this->uri->segment(1)=="listings"){echo "active";}?>"><a href="<?php echo base_url(); ?>listings"><i class="fa fa-search fa-fw"></i> Search Listings</a></li>  
								<li  class="<?php if($this->uri->segment(2)=="message-landlords"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/message-landlords"><i class="fa fa-envelope-o fa-fw"></i> Messages</a></li> 
								<li class="dropdown">
									<a href="<?php echo base_url(); ?>renters/my-history" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-clock-o"></i> Rental History<span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										<li  class="<?php if($this->uri->segment(2)=="my-history"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/my-history"><i class="fa fa-calendar-o"></i> My History</a></li>
										<li  class="<?php if($this->uri->segment(2)=="rent-receipt"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/rent-receipt"><i class="fa fa-dollar"></i> Rent Payment Log</a></li>
									</ul>
									<div class="clearfix"></div>
								</li>
								<li  class="<?php if($this->uri->segment(2)=="pay-rent"){echo "active";}?>"><a href="<?php echo base_url(); ?>renters/pay-rent"><i class="fa fa-dollar"></i> Pay Rent Online or Create Receipt</a></li>
								<li><a href="<?php echo base_url(); ?>renters/in-search-of"><i class="fa fa-search"></i> In Search Of</a></li>
								<li><a href="<?php echo base_url(); ?>renters/videos"><i class="fa fa-video-camera fa-fw"></i> Videos</a></li>      
								<li><a href="https://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help &amp; Support</a></li>
								<li><a href="<?php echo base_url(); ?>renters/logout"><i class="fa fa-power-off fa-fw"></i> Log Out</a></li>
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>
				<div class="col-md-8">
					<div id="output">
						<?php echo $output;?>
					</div>
				</div>
				<div class="col-md-2">
					<!-- Sidebar Goes Here-->
                    <?php echo $this->load->get_section('sidebar'); ?>
				</div>
			</div>
			<hr/>
		</div> <!-- container -->
		

	
		<?php
	


			foreach($js as $file){
					echo "\n\t\t";
					?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";
		
			
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
						&copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved
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
