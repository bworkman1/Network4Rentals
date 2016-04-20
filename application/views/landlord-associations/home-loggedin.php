<?php
	$warning = $this->session->userdata('warning');
	if(!empty($warning)) {
		echo '<div class="alert alert-warning">'.$warning.'</div>';
	}
	$danger = $this->session->userdata('danger');
	if(!empty($danger)) {
		echo '<div class="alert alert-danger">'.$danger.'</div>';
	}
?>

<div class="row">
	<div class="col-sm-3">
		<div class="home-box">
			<div class="box-side color">
				<i class="fa fa-users"></i>
			</div>
			<div class="box-right-side">
				<br>
				Total Members<br><b><?php echo $memebers_total; ?></b>
			</div>
			<div class="clearfix"></div>
		</div> 
	</div>
	<div class="col-sm-3">
		<div class="home-box">
			<div class="box-side color2">
				<i class="fa fa-calendar"></i> 
			</div>
			<div class="box-right-side"> 
				<?php if(!empty($stats['next_event'])) { ?>
					<b>Next Event</b><br>
					<small><?php echo date('m-d-Y h:i a', strtotime($stats['next_event']->start)+3600); ?></small> <br><?php echo $stats['next_event']->what; ?>
				<?php } else { ?>
					<br><b>Next Event</b><br>
					<small>No Upcoming Events</small>
				<?php } ?> 
			</div>	
			<div class="clearfix"></div>
		</div>	
	</div>
	<div class="col-sm-3">
		<div class="home-box">
			<div class="box-side color3">
				<i class="fa fa-calendar"></i>
			</div>
			<div class="box-right-side">
				<br>
				<?php echo $stats['page_posts']; ?> Post On Public Page
			</div>	
			<div class="clearfix"></div>
		</div>			
	</div>
	<div class="col-sm-3">
		<div class="home-box">
			<div class="box-side color4">
				<i class="fa fa-calendar"></i>
			</div>
			<div class="box-right-side">
				<br>
				<?php 
					if($public_page->visits >0) {
						echo 'Public Page Views'.'<br><b>'.$public_page->visits.'</b>';
					} else {
						echo '0 Public Page Views';
					}
				
				?> 
			</div>	
			<div class="clearfix"></div>
		</div>		
	</div>
</div>
<div class="spacing15"></div>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-calendar stylish-icon"></i> Quick Calendar</h3>
			</div>
			<div class="panel-body">
				<?php
					$prefs = array (
					   'show_next_prev'  => TRUE,
					   'next_prev_url'   => site_url('landlord-associations/home/')
					 );

					$this->load->library('calendar', $prefs);
					echo $this->calendar->generate($this->uri->segment(3), $this->uri->segment(4));
				?>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bell stylish-icon"></i> Recent Events</h3>
			</div>
			<div class="panel-body">
				<ul id="recent-events">
					<li>No Recent Events</li>
				</ul>
			</div>
		</div>
	</div>
</div>