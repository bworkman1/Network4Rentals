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
					  <a href='#'><i class="fa fa-laptop"></i>My Website</a>
					  <ul>
						 <li><a href='<?php echo base_url('affiliates/my-website/stats'); ?>'>
								 Website Stats</a></li>
						 <li><a href='<?php echo base_url('affiliates/my-website/edit'); ?>'>
								 Edit Website</a></li>
						<?php
							$pageSet = $this->session->userdata('unique_name');
							if(!empty($pageSet)) {
								echo '<li><a href="http://n4rlocal.com/'.$pageSet.'" target="_blank">View Website</a></li>';
							}
						?>
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