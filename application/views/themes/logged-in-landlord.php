<!DOCTYPE HTML>	
	<html lang="en">
	<head>
		<title><?php echo 'N4R | '.ucwords(str_replace('-', ' ', $this->uri->segment(2))); ?></title>
		<meta name="resource-type" content="document" />
		<meta name="robots" content="all, index, follow"/>
		<meta name="googlebot" content="all, index, follow" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<!-- Le styles -->
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootstrap.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/bootswatch.css" rel="stylesheet">
		<link href="<?php echo base_url(); ?>assets/themes/default/css/custom-landlord.css" rel="stylesheet">
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
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false&region=us"></script>
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

	<body>
	     <!--[if lt IE 8]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
		<div id="top_bar" class="hidden-print">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md-6'>
						<p class="hidden-xs hidden-sm">Improving Landlord &amp; Tenant Relations Nationwide</p>
					</div>
					<div class='col-md-6 text-right'>
						<?php 
									$incomplete_icon = '<i class="fa fa-exclamation-triangle text-danger fa-fw"></i>';
									
									$this->db->limit(5);
									$results = $this->db->get_where('renter_history', array('link_id'=>$this->session->userdata('user_id'), 'current_residence'=>'y', 'address_locked'=>0));
									if($results->num_rows()>0) {
										foreach($results->result() as $row) {
											$count++;
											$notification[] = '<a href="'.base_url().'landlords/view-tenant-info/'.$row->id.'">'.$incomplete_icon.' Verify Tenant Details</a>';
											
											if(!empty($row->move_in)) {
												if(!empty($row->lease)) {
													switch ($row->lease) {
														case '9 Months':
															$length = 9;
															$mod = 'm';
															break;
														case '1 Year':
															$length = 1;
															$mod = 'y';
															break;
														case '2 Years':
															$length = 2;
															$mod = 'y';
															break;
														default:
															$length = 0;
													}
													
													if($length !== 0){
														if($mod == 'y') {
															$date_check = date("Y-m-d", strtotime(date("Y-m-d", strtotime($row->move_in)) . " + ".$length." year"));
														} else {
															$date_check = date("Y-m-d", strtotime(date("Y-m-d", strtotime($row->move_in)) . " + ".$length." month"));
														}
														
														$date_advance = date("Y-m-d", strtotime(date("Y-m-d", strtotime($date_check)) . " - 30 day"));
																											
														if($date_check > date('Y-m-d') && date('Y-m-d') > $date_advance) {
															$notification[] = '<a href="'.base_url().'landlords/view-tenant-info/'.$row->id.'">'.$incomplete_icon.' Tenants Lease Up Soon</a>';
															$count++;
														}
														
													}
													
													
													
												}
											}
											
											
										}
									}
								
									
									
									echo '<div id="notifications">';
										echo '<button class="btn btn-primary notification-btn">';
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
		<div id="header" class="hidden-print">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-lg-2 col-md-3 col-sm-6 col-xs-10'>
						<a href="https://network4rentals.com"><img class="img-responsive logo" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
					</div>
				</div>
			</div>
		</div>
		
		<div id="content" class="container-fluid">
			<hr>
			<div class="row">
				<div class="col-md-2 paddingRemove hidden-print">
					<div id="landlord-logo">
						<?php	
							$group_id = $this->session->userdata('temp_id');
							$this->db->where('sub_admins', $this->session->userdata('user_id'));
							$this->db->or_where('main_admin_id', $this->session->userdata('user_id'));
							$query = $this->db->get('admin_groups');
							if ($query->num_rows() > 0) {
								$switches = $query->result_array();
							} else {
								$switches = '';
							}
							
							if(!empty($group_id)) {
								$this->db->select('main_admin_id');
								$query = $this->db->get_where('admin_groups', array('id'=>$group_id));
								if($query->num_rows()>0) {
									$row = $query->row();
									$this->db->select('image');
									$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$row->main_admin_id));
									if($query->num_rows()>0) {
										$row = $query->row();
										$profile_img = $row->image;
									}
								}
							} else {	
								$this->db->select('image');
								$query = $this->db->get_where('landlord_page_settings', array('landlord_id'=>$this->session->userdata('user_id')));
								if($query->num_rows()>0) {
									$row = $query->row();
									$profile_img = $row->image; 
								}
							}
							
							$this->db->select('bName, name');
							$query = $this->db->get_where('landlords', array('id'=>$this->session->userdata('user_id')));
							if($query->num_rows()>0) {
								$row = $query->row();
								$select_bname = $row->bName;
								$select_name = $row->name;
							}
							
							if(!empty($profile_img)) {
								echo '<img src="'.base_url().'public-images/'.$profile_img.'" class="landlord-image img-responsive hidden-xs hidden-sm">';
							} else {
								echo '<img src="'.base_url().'public-images/default-user.png" class="landlord-image img-responsive hidden-xs hidden-sm">';
							}
							
							if(!empty($switches)) {
								echo form_open('landlords/switch-admin-group'); 
								echo '<div id="adminSelectMargin">
										<select class="form-control input-md pull-right" name="admin" onchange="this.form.submit()">';
										if(!empty($select_bname)) {
											echo '<option value="0">'.$select_bname.'</option>';
										} else {
											echo '<option value="0">'.$select_name.'</option>';
										}
										for($i=0;$i<count($switches);$i++) {
											if($this->session->userdata('temp_id') == $switches[$i]['id']) {
												$current_owner = $switches[$i]['sub_b_name'];
												echo '<option value="'.$switches[$i]['id'].'" selected="selected">'.$switches[$i]['sub_b_name'].'</option>';
											} else {
												echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
											}
										}
									echo '</select></div>';
								echo form_close();
							}
							
							if($this->session->userdata('temp_id') == false) {
								if(empty($profile_img)) {
									$linkIt = true;
								} else {
									$linkIt = false;
								}
							} else {
								$linkIt = false;
							}
							if($linkIt) {
								echo '<div id="editAccountLink" class="hidden-xs hidden-sm"><a href="'.base_url().'landlords/public-page-settings"><i class="fa fa-edit"></i> Edit Account</a></div>';
							}
						?>
					</div>
					<div class="clearfix"></div>
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
								<li><a href="https://network4rentals.com"><i class="fa fa-bookmark-o fa-fw"></i> Main Page</a></li>
								<?php
									if($this->session->userdata('user_id') == '23' OR $this->session->userdata('user_id') == '73' OR $this->session->userdata('user_id') == '156' OR $this->session->userdata('user_id') == '80') {
										echo '<li><a href="'.base_url().'landlords/record-keeping"><i class="fa fa-lock fa-fw"></i> Admin Panel</a></li>';
									}
								?>
								
								<li class="active"><a href="<?php echo base_url(); ?>landlords/activity"><i class="fa fa-home"></i> Activity</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/add-listing"><i class="fa fa-plus fa-fw"></i> Add Property</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/manage-listings"><i class="fa fa-bars fa-fw"></i> My Properties</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/edit-account"><i class="fa fa-gears fa-fw"></i> My Account</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/service-request"><i class="fa fa-wrench fa-fw"></i> Service Requests</a></li>                          
								<li><a href="<?php echo base_url(); ?>landlords/my-tenants"><i class="fa fa-users fa-fw"></i> My Tenants</a></li>     
								<li><a href="<?php echo base_url(); ?>landlords/videos"><i class="fa fa-video-camera fa-fw"></i> Videos</a></li>     
								<li><a href="http://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help &amp; Support</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/logout"><i class="fa fa-power-off fa-fw"></i> Log Out</a></li>
								<li><a href="<?php echo base_url(); ?>landlords/resources"> &nbsp;&nbsp;<i class="fa fa-info"></i> &nbsp;&nbsp;Resources</a></li>
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav>
				</div>
				<div class="col-md-8">
					<?php echo $output;?>
				</div>
				<div class="col-md-2 hidden-print">
                    <?php echo $this->load->get_section('sidebar'); ?>
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
				<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
	</body>
</html>
