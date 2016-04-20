<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<title><?php echo 'Network 4 Rentals | '.$this->uri->segment(2); ?></title>
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/custom-advertisers.css" rel="stylesheet">
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
					<div class='col-md-5'>
						Improving Landlord &amp; Tenant Relations Nationwide
					</div>
					<div class='col-md-7'>
						<!-- 
						<ul id="socials">
							<li>
								<a href="https://www.facebook.com/network4rentals" target="_blank" class="toolTip" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Find Network 4 Rentals On Facebook">
									adas<i class="fa fa-facebook"></i>
								</a>
							</li>
							<li>
								<a href="https://www.youtube.com/channel/UCkGnqjRnsmCB-Nxwgl7f83w/videos" target="_blank" class="toolTip" title="" data-placement="bottom" data-original-title="Find Network For Rentals on Youtube">
									<i class="fa fa-youtube"></i>
								</a>
							</li>
							<li>
								<a href="https://twitter.com/Network4Rentals" target="_blank" class="toolTip" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Find Network 4 Rentals On Twitter">
									<i class="fa fa-twitter"></i>
								</a>
							</li>
							<li>
								<a href="https://www.linkedin.com/company/network-4-rentals-llc/" target="_blank" class="toolTip" title="" data-placement="bottom" data-original-title="Find Network For Rentals on LinkedIn">
									<i class="fa fa-linkedin"></i>
								</a>
							</li>
							<li>
								<a href="https://network4rentals.com/rss" target="_blank" class="toolTip" data-placement="bottom" title="" data-original-title="Follow Our RSS Feed">
									<i class="fa fa-rss"></i>
								</a>
							</li>
							<li>
								<a href="https://www.google.com/+Network4rentals" class="toolTip" title="" data-placement="bottom" target="_blank" data-original-title="Find Network For Rentals on Google+">
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
								<li><a href="http://www.network4rentals.com"><i class="fa fa-home fa-fw"></i> Home</a></li>
								<li><a href="<?php echo base_url(); ?>advertisers/home"><i class="fa fa-file fa-fw"></i> Main Page</a></li>
								<li><a href="<?php echo base_url(); ?>advertisers/my-account"><i class="fa fa-gears fa-fw"></i> My Account</a></li>
								<li><a href="<?php echo base_url(); ?>advertisers/my-zips"><i class="fa fa-map-marker fa-fw"></i> My Zips</a></li>
								<li><a href="<?php echo base_url(); ?>advertisers/stats"><i class="fa fa-bar-chart-o fa-fw"></i> Stats</a></li>
								<li><a href="<?php echo base_url(); ?>advertisers/public-page-settings"><i class="fa fa-laptop fa-fw"></i> My Web Page</a></li>
								<li><a href="http://network4rentals.com/faqs/"><i class="fa fa-info fa-fw"></i> FAQ"s</a></li>              
								<li><a href="http://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help/Support</a></li> 
								<li><a href="<?php echo base_url(); ?>advertisers/logout"><i class="fa fa-lock fa-fw"></i> Logout</a></li>
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
							echo '</ul>';
						} else {
							//show default ad here
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
								<h3><i class="fa fa-phone text-primary"></i> By Phone</h3>
								<hr>
								<b>Customer Service:</b> (740) 403-7661
							</div>
							<div class="col-sm-6">
								<h3><i class="fa fa-envelope text-primary"></i> By Email</h3>
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
	</body>
</html>
