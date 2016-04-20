<header>
			<a href="https://network4rentals.com" target="_blank" class="logo">
				<img src="<?php echo base_url('assets/themes/blue-moon/img/logos/header-logo-784x216.png'); ?>" id="header-logo" alt="Network4Rentals Renters Login Screen" class="pull-right img-responsive">
			</a>
			<div class="pull-right">
				<ul id="mini-nav" class="clearfix">

			  <li class="list-box user-profile">
				<a id="drop7" data-step="1" data-intro="<h5><b>Your Settings</b></h5>Change your email and other settings by clicking on this guy. You can also add a profile pick in here if you like." data-position='left' href="#" role="button" class="dropdown-toggle user-avtar" data-toggle="dropdown">
                    <?php
                        $img = $this->session->userdata('image');
                        if(substr($img, 0, 4) == 'http') {
                            echo '<img src="'.$this->session->userdata('image').'" alt="'.$this->session->userdata('name').'">';
                        } else {
                            echo '<img src="'.base_url($this->session->userdata('image')).'" alt="'.$this->session->userdata('name').'">';
                        }
                    ?>

				</a>
				<ul class="dropdown-menu server-activity">
				  <li>
					<p><a href="<?php echo base_url('affiliates/my-account'); ?>" class="remove-css"><i class="fa fa-cog text-info"></i> Account Settings</a></p>
				  </li>
				  <li>
					<div class="demo-btn-group clearfix">
					  <a href="https://www.facebook.com/network4rentals" data-original-title="" title="" target="_blank">
						<i class="fa fa-facebook fa-lg icon-rounded info-bg"></i>
					  </a>
					  <a href="https://twitter.com/Network4Rentals" data-original-title="" title="" target="_blank">
						<i class="fa fa-twitter fa-lg icon-rounded twitter-bg"></i>
					  </a>
					  <a href="https://www.linkedin.com/company/network-4-rentals-llc" data-original-title="" title="" target="_blank">
						<i class="fa fa-linkedin fa-lg icon-rounded linkedin-bg"></i>
					  </a>
					  <a href="https://plus.google.com/+Network4rentals" data-original-title="" title="" target="_blank">
						<i class="fa fa-google-plus fa-lg icon-rounded success-bg"></i>
					  </a>
					</div>
				  </li>
				  <li>
					<div class="demo-btn-group clearfix">
					  <a href="<?php echo base_url('affiliates/logout'); ?>" class="btn btn-danger">
						Logout
					  </a>
					</div>
				  </li>
				</ul>
			  </li>
			</ul>
		  </div>
		</header>