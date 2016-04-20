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
			<div class="row">
	

				<div class="col-md-8 col-md-offset-2">


<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error'))
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4><p>'.$this->session->flashdata('error').'</p></div>';
	}
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><h4>Error:</h4><p>'.$error.'</p></div>';
	}
	
?>

	<div class="row">
		<?php 
			echo form_open('landlords/create-user-account', array('id'=>'createNewAccount'));
			echo '<div class="row"><div class="col-lg-12">';
			echo '<h3><i class="fa fa-user text-primary"></i> Create Account</h3>';
			echo '</div></div><hr>';
			echo '<div class="col-md-6">';
			echo '<label>Business Name:</label>';
			echo '<input type="text" name="bName" class="form-control" value="'.$_POST['bName'].'">';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Full Name:');
			if(!empty($_POST['fullname'])) {
				$fullname = $_POST['fullname'];
			} else {
				$fullname = '';
			}
			$data = array(
					  'name'        => 'fullname',
					  'id'          => 'fullname',
					  'maxlength'   => '200',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value' 	=> $fullname
					);
			echo form_input($data);
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Email:');
			if(!empty($_POST['email'])) {
				$email = $_POST['email'];
			} else {
				$email = '';
			}
			$data = array(
					  'name'        => 'email',
					  'id'          => 'email',
					  'maxlength'   => '100',
					  'class'       => 'form-control checkLandlordEmail',
					  'required' 	=> '',
					  'value'		=> $email
					);
			echo form_input($data);
			echo '<div class="error-email text-danger"></div>';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Username:');
			if(!empty($_POST['username'])) {
				$username = $_POST['username'];
			} else {
				$username = '';
			}
			$data = array(
					  'name'        => 'username',
					  'id'          => 'username',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control checkUsername',
					  'required' 	=> '',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'value'		=> $username
					);
			echo form_input($data);	
			echo '<div class="error-username text-danger"></div>';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Password:');			
			$data = array(
					  'name'        => 'password',
					  'id'          => 'password',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control checkPass checker',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'required' 	=> ''
					);
			echo form_input($data);				
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Confirm Password:');
			$data = array(
					  'name'        => 'password1',
					  'id'          => 'password1',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control checkPass2 checker',
					  'placeholder' => 'Must Be At Least 6 Characters',
					  'required' 	=> ''
					);
			echo form_password($data);				
			echo '<div class="pwd-error text-danger"></div>';
			echo '</div>';
			echo '<div class="col-md-6">';
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Phone:');
			if(!empty($_POST['phone'])) {
				$phone = $_POST['phone'];
			} else {
				$phone = '';
			}
			$data = array(
					  'name'        => 'phone',
					  'id'          => 'phone',
					  'maxlength'   => '15',
					  'class'       => 'form-control phone',
					  'required' 	=> '',
					  'value'		=> $phone
					);
			echo form_input($data);	
			echo form_label('<i class="fa fa-asterisk text-danger"></i> Address:');
			if(!empty($_POST['address'])) {
				$address = $_POST['address'];
			} else {
				$address = '';
			}
			$data = array(
					  'name'        => 'address',
					  'id'          => 'address',
					  'maxlength'   => '100',
					  'minlength' 	=> '6',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value'		=> $address
					);
			echo form_input($data);	
			echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
			if(!empty($_POST['city'])) {
				$city = $_POST['city'];
			} else {
				$city = '';
			}
			$data = array(
					  'name'        => 'city',
					  'id'          => 'city',
					  'maxlength'   => '100',
					  'class'       => 'form-control',
					  'required' 	=> '',
					  'value'		=> $city
					);
			echo form_input($data);	
			
			echo '<div class="row">';
				echo '<div class="col-sm-6">';
					echo form_label('<i class="fa fa-asterisk text-danger"></i> State:');			
					$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
					echo '<select name="state" class="form-control" required="">';
					echo '<option value="">Select One...</option>';
					foreach($states as $key => $val) {
						if($key == $_POST['state']) {
							echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}
					echo '</select>';
				echo '</div>';
				echo '<div class="col-sm-6">';
			
					echo form_label('<i class="fa fa-asterisk text-danger"></i> Zip:');
					if(!empty($_POST['zip'])) {
						$zip = $_POST['zip'];
					} else {
						$zip = '';
					}
					$data = array(
							  'name'        => 'zip',
							  'id'          => 'zip',
							  'maxlength'   => '5',
							  'minlength' 	=> '5',
							  'class'       => 'form-control numbersOnly',
							  'required' 	=> '',
							  'value'		=> $zip
							);
					echo form_input($data);	
				echo '</div>';
			echo '</div>';
			
			$hear_about = array(
				'Event or Booth',
				'Friends',
				'Family',
				'Online Search',
				'Literature (handouts, fliers, etc.',
				'Advertisement',
				'Utica Home Coming',
				'Facebook',
				'Google+',
				'Twitter',
				'Linkedin',
				'Tenant Request',
				'Other'
				
			);
			echo form_label('<i class="fa fa-asterisk text-danger"></i> How Did You Hear About Us?');
			echo '<select name="hear" class="form-control" required>';
				echo '<option value="">Select One...</option>';
				foreach($hear_about as $val) {
					if($val == $_POST['hear']) {
						echo '<option selected="selected">'.$val.'</option>';
					} else {
						echo '<option>'.$val.'</option>';
					}
				}
			echo '</select>';
			echo '<br>';
			echo '<div class="row">';
			echo '<label class="col-sm-9" style="padding-top: 5px"><i class="fa fa-asterisk text-danger"></i> How Many Rental Units Do You Have:</label>';
			echo '<div class="col-sm-3">';
			echo '<input type="numsOnly" class="numbersOnly form-control" name="rental_units" value="'.$_POST['rental_units'].'">';
			echo '</div>';
			echo '</div>';
			
			echo '</div>';
			echo '</div>';
		?>
		<br>
		
		<div class="well">
			<div class="row">
				<div class="col-sm-6">
					<label><i class="fa fa-asterisk text-danger"></i> Receive account notifications and confirmations via text message:</label>
					<select class="form-control textMessages" name="sms_msgs" required>
						<option value="">Select One</option>
						<?php
							$options = array('n'=>'No', 'y'=>'Yes');
							foreach($options as $key => $val) {
								if($key == $_POST['sms_msgs']) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
						?>
					</select>
				</div>
				<?php
					if($_POST['sms_msgs'] == 'y') {
						echo '<div class="col-sm-6 textMessagePhoneNumber fade in">';
					} else {
						echo '<div class="col-sm-6 textMessagePhoneNumber fade">';
					}
				?>
				
					<label><i class="fa fa-asterisk text-danger"></i> Cell Phone Number:</label>
					<input type="text" class="form-control phone cellPhone" name="cell_phone" value="<?php echo $_POST['cell_phone']; ?>">
				</div>
			</div>
		</div>
		
		<?php
			echo form_label('<i class="fa fa-asterisk text-danger"></i> I Agree To The <a href="'.base_url().'landlords/terms-of-service">Terms Of Services</a>:');
			echo '<br><input type="checkbox" name="terms" value="y" required="" '.set_checkbox('mycheck', '1').' /> Yes<br>';
			$data = array(
				'value' => 'true',
				'type' => 'submit',
				'class' => 'btn btn-primary createLandlordAccount',
				'content' => '<i class="fa fa-location-arrow"></i> Create My Account'
			);

			echo '<br>';
			echo form_button($data);
			echo form_close();
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
		
		<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
	</body>
</html>
