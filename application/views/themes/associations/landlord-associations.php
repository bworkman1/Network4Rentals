<!DOCTYPE HTML>
	<html lang="en">
	<head>
		<?php if(empty($title)) { ?>
			<title><?php echo 'Network 4 Rentals | '.$this->uri->segment(2); ?></title>
		<?php } else { ?>
			<title><?php echo $title; ?></title>
		<?php } ?>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
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
		<script src="//use.typekit.net/ksj7yhg.js"></script>
		<script>try{Typekit.load();}catch(e){}</script>
		<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon"/>
		<meta property="og:image" content="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png"/>
		<link rel="image_src" href="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png" />
		<script>
			if(window.location.protocol != 'https:') {
			  location.href = location.href.replace("http://", "https://");
			}
		</script>
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
	     <!--[if lt IE 9]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<div id="top_bar">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md-6'>
						Improving Landlord &amp; Tenant Relations Nationwide
					</div>
					
				</div>
			</div>
		</div>
		<div id="header">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-lg-2 col-md-3 col-sm-4'>
						<a href="https://network4rentals.com"><img class="img-responsive logo" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="content" class="container-fluid">
			<hr>
			<div class="row">
				<div class="col-lg-2" style="padding-left: 0">
					<nav class="navbar sidebar navbar-default">
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
								<li class=""><a href="http://network4rentals.com"><i class="fa fa-home"></i> N4R Home</a></li>
								<li class="<?php if($this->uri->segment(2)==""){echo "active";}?>"><a href="<?php echo base_url(); ?>landlord-associations/"><i class="fa fa-home"></i> Association Home</a></li>
								<li class="<?php if($this->uri->segment(2)=="create-account"){echo "active";}?>"><a href="<?php echo base_url(); ?>landlord-associations/create-account"><i class="fa fa-user"></i> Create Account</a></li>
								<li><a href="#login" class="md-trigger" data-modal="modal-1"><i class="fa fa-unlock"></i> Login</a></li>
								<li><a href="<?php echo base_url(); ?>renters/login"><i class="fa fa-building-o"></i> Tenant</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/login"><i class="fa fa-money"></i> Landlord</a></li>
								<!-- <li><a href="http://www.network4rentals.com/network/contractors/login"><i class="fa fa-wrench"></i> Contractor</a></li> -->
								<li><a href="http://network4rentals.com/faqs/"><i class="fa fa-info"></i> FAQ"s</a></li>              
								<li><a href="http://network4rentals.com/help-support/"><i class="fa fa-question"></i> Help/Support</a></li>              
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>
				<div class="col-lg-8">
					<?php echo $output;?>
				</div>
				<div class="col-lg-2">
					<!-- Sidebar Goes Here-->
					
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
						}
					
					?>
				</div>
			</div>
			<hr/>
		</div> <!-- container -->
		
		<!-- Login Modal -->
		<div class="md-modal md-effect-1" id="modal-1">
			<div class="md-content">
				<h3><i class="fa fa-lock"></i> Login To Your Account</h3>
				<div class="md-close pull-right closeModal"><i class="fa fa-times"></i></div>
				<div id="login">
					<form>
					<fieldset>
						<div class="row">
							<br>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label" for="user">Username:</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="user" id="user" maxlength="20" required>
									<span class="fa user text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="user-error" class="sr-only"></span>
								</div>
							</div>
							<br>
							<div class="form-group has-feedback">
								<label class="col-sm-3 control-label" for="pass">Password:</label>
								<div class="col-sm-9">
									<input type="password" class="form-control" name="pass" id="pass" maxlength="20" required>
									<span class="fa pass text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
									<span id="pass-error" class="sr-only"></span>
								</div>
							</div>
						</div>
						<br>
						<div id="login-error" class="text-center text-danger"></div>
						<div class="row">
							<div class="col-sm-6">
								<a href="#forgotPass" class="md-trigger forgotPassword" data-modal="modal-2"> Forgot Password? </a>
							</div>
							<div class="col-sm-6">
								<button type="submit" id="login-btn" class="pull-right btn btn-primary">Login</button> 
							</div>
						</div>
					</fieldset>
					</form>
				</div>
			</div>
		</div>
		
		<!-- Forgot Password Modal -->
		<div class="md-modal md-effect-1" id="modal-2">
			<div class="md-content">
				<h3><i class="fa fa-key"></i> Forgot Password</h3>
				<div class="md-close pull-right closeModal"><i class="fa fa-times"></i></div>
				<div id="forgotPass">
					<form id="forgotPasswordForm">
						<fieldset>
							<div class="row">
								<br>
								<div class="form-group has-feedback">
									<label class="col-sm-3 control-label" for="email">Email:</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" name="email" id="email" maxlength="40" required>
										<span class="fa user text-danger fa-asterisk form-control-feedback" aria-hidden="true"></span>
										<span id="user-errors" class="sr-only"></span>
										<br>
										<div class="g-recaptcha pull-right" data-sitekey="6LdghgkTAAAAAIWEcseUoPimgYuuoDN24S9kdW3M"></div>
									</div>
								</div>
								<br>
								
							</div>
							<br>
							<div id="forgot-error"></div>
							<div id="forgot-success"></div>
							<div class="row">
								<div class="col-sm-6">
									<a href="#login" class="md-trigger loginForgotPass" data-modal="modal-1"> Login </a>
								</div>
								<div class="col-sm-6">
									<button type="submit" id="forgot-btn" class="btn btn-primary pull-right">Reset Password</button> 
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div>
		</div>
		
		
		<div class="md-overlay"></div><!-- the overlay element -->

		<script>
			// this is important for IEs
			var polyfilter_scriptpath = '/js/';
		</script>
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
						&copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved
					</div>
					<div class="col-sm-6"> 
						Developed &amp; Hosted By EMF Web Solutions
					</div>
				</div>
			</div>
		</footer>
	</body>
</html>
