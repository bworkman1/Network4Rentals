		    <!-- Main Container start -->
		<div class="dashboard-container">
			<div class="container">
			<!-- Top Nav Start -->
				<div id='cssmenu'>
				  <ul>
					<li <?php if(
						$this->uri->segment(2)=='dashboard' ||
						$this->uri->segment(2)=='my-account' ||
						$this->uri->segment(2)=='payments') {echo 'class="active"';} ?>>
					  <a href='<?php echo base_url('affiliates/dashboard'); ?>'>
						  <i class="fa fa-dashboard"></i>Dashboard</a>
					</li>
					<li <?php if($this->uri->segment(2)=='my-website') {echo 'class="active"';} ?>>
					  <a href='#'><i class="fa fa-home"></i>Rental Details</a>
					  <ul>
						 <li><a href='<?php echo base_url('affiliates/my-website/stats'); ?>'>
								Current Rental Details</a></li>
						 <li><a href='<?php echo base_url('affiliates/my-website/edit'); ?>'>
								Rental History</a></li>
						<li><a href='<?php echo base_url('affiliates/my-website/edit'); ?>'>
								Rent Payment Log</a></li>
						<li><a href='<?php echo base_url('affiliates/my-website/edit'); ?>'>
								Pay Rent Online</a></li>
						<li><a href='<?php echo base_url('affiliates/my-website/edit'); ?>'>
								Create Rent Receipt</a></li>
					  </ul>
					</li>
					<li <?php if($this->uri->segment(2)=='my-referrals') {echo 'class="active"';} ?>>
					  <a href="#"><i class="fa fa-users"></i>My Referrals</a>
                        <ul>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/contractors'); ?>'>
									Contractors</a></li>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/landlords'); ?>'>
									Landlords</a></li>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/renters'); ?>'>
									Renters</a></li>
                        </ul>
					</li>
					<li <?php if($this->uri->segment(2)=='my-referrals') {echo 'class="active"';} ?>>
					  <a href="#"><i class="fa fa-wrench"></i>Service</a>
                        <ul>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/contractors'); ?>'>
									My Service Request</a></li>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/landlords'); ?>'>
									Submit New Request</a></li>
                        </ul>
					</li>
					<li <?php if($this->uri->segment(2)=='help') {echo 'class="active"';} ?>>
						<a href="<?php echo base_url('affiliates/help'); ?>">
							<i class="fa fa-comments"></i>Messages</a>
					</li>
					<li <?php if($this->uri->segment(2)=='help') {echo 'class="active"';} ?>>
						<a href="<?php echo base_url('affiliates/help'); ?>">
							<i class="fa fa-building"></i>Home Listings</a>
						<ul>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/contractors'); ?>'>
									Search Listings</a></li>
                            <li><a href='<?php echo base_url('affiliates/my-referrals/landlords'); ?>'>
									Create ISO</a></li>
                        </ul>
					</li>
					<li <?php if($this->uri->segment(2)=='help') {echo 'class="active"';} ?>>
						<a href="<?php echo base_url('affiliates/help'); ?>">
							<i class="fa fa-question-circle"></i>Help</a>
					</li>
				  </ul>
				</div>
				<!-- Top Nav End -->

				<!-- Sub Nav End -->
				<div class="sub-nav hidden-sm hidden-xs">
					<ul>
						<?php
							if(!empty($sub_nav)) {
								$i=0;
								$class = 'heading';
								foreach($sub_nav as $key => $val) {
									if($key == 'View Website') {
										$blank = ' target="_blank"';
									} else {
										$blank = '';
									}
									if($i>0) {
										$class = '';
									}
									if(strtolower($title) == strtolower($key)) {
										echo '<li class="hidden-sm hidden-xs"><a class="selected '.$class.'" href="'.$val.'" '.$blank.'>'.ucwords($key).'</a></li>';
									} else {
										echo '<li class="'.$class.' hidden-sm hidden-xs"><a class="'.$class.'" href="'.$val.'" '.$blank.'>'.ucwords($key).'</a></li>';
									}
									$i++;
								}
							}
						?>
					<ul>

				</div>
				<!-- Sub Nav End -->