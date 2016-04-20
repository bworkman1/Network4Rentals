<a href="javascript:window.print()" class="pull-right btn btn-default"><i class="fa fa-print"></i></a>
<a href="<?php echo base_url('print-handler/print-service-request/'.$this->uri->segment(3)); ?>" class="pull-right btn btn-success"><i class="fa fa-download"></i></a>
<h2><i class="fa fa-bookmark-o text-primary pull-left"></i> Service Request Details</h2>
<div class="clearboth"></div>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', '14'=>'Other');
?>
<div class="well hidden-print">
	<i class="fa fa-exclamation-triangle text-danger"></i> You Are Viewing This Service Request In QuickView. To see more options login to your account and re-click the link. <br><br><a href="<?php echo base_url(); ?>landlords/login" class="btn btn-primary btn-sm">Landlords Login</a>
	<a href="<?php echo base_url(); ?>contractors/login" class="btn btn-success btn-sm pull-right">Contractors Login</a>
</div>
<div class="row">
	<div class="col-sm-6">
		<h3><i class="fa fa-building-o text-primary"></i> Landlord Details</h3>
		<hr>
		<p><b>Submitted To:</b> 
		<?php 
			if(!empty($landlord['bName'])) {
				echo $landlord['bName'];
			} else {
				echo $landlord['name'];
			} 
		?>
		</p>
		<?php
			if(!empty($landlord['phone'])) {
				echo '<p><b>Phone:</b> <a href="tel:'.$landlord['phone'].'">('.substr($landlord['phone'], 0, 3).') '.substr($landlord['phone'], 3, 3).'-'.substr($landlord['phone'],6).'</a></p>';
			}
		?>
		
		<p><b>Email:</b> <?php echo $landlord['email']; ?></p>
		<p><b>Location:</b> <?php echo $landlord['city'].', '.$landlord['state'].' '.$landlord['zip']; ?></p>
	</div>
	<div class="col-sm-6">
		<h3><i class="fa fa-home text-primary"></i> Service Location:</h3>
		<hr>
		<p><b>Renter Name</b><br>
		<?php echo $requests['name']; ?></p>
		<p><b>Address</b><br>
			<?php echo $requests['rental_address'].'<br>'.$requests['rental_city']. ' '.$requests['rental_state']. ' '.$requests['rental_zip']; ?>
		</p>
		<p><b>Phone:</b> 
		<?php 
			if(!empty($requests['phone'])) {
				echo '<a href="tel='.$requests['phone'].'">';
					echo "(".substr($requests['phone'], 0, 3).") ".substr($requests['phone'], 3, 3)."-".substr($requests['phone'],6);
				echo '</a>';
			}
		?>
		</p>
	</div>
</div>
<hr>
<div class="row sponsorship">
	<?php
		
		if(!empty($ad_post)) {
			echo '<hr>';
			echo '<div class="row sponsorship">';
			echo '<div class="col-sm-12"><h4><i class="fa fa-bullhorn text-primary"></i> Sponsored Contractors</h4></div>';
			shuffle($ad_post);
			foreach($ad_post as $val) {
				echo '<div class="col-sm-4 text-center">';
					echo '<div class="link-contractor">';
						echo '<a href="'.base_url().'landlords/contractor-click/'.$val->id.'/'.$val->url.'" target="_blank">';
						echo '<h4><b>'.htmlspecialchars($val->title).'</b></h4>';
						echo '<p>'.htmlspecialchars($val->description).'</p>';
						if(!empty($val->ad_image)) {
							echo '<img src="'.base_url().'contractor-images/'.$val->ad_image.'" class="img-responsive sponsorLogo" alt="'.$val->bName.'">';
						}
						echo '<p style="background: #158CBA; color: #ffffff; margin-bottom: 0; padding-bottom: 5px;"><b>'.htmlspecialchars($val->bName).'</b> <br>';
						echo '('.substr($val->phone, 0, 3).') '.substr($val->phone, 3, 3).'-'.substr($val->phone,6).'</p>';
						echo '</a>';
					echo '</div>';
				echo '</div>';
			}
			echo '</div>';
			echo '<hr>';
		}
	?>
</div>
<?php if(!empty($ad_post)) { ?>
<hr>
<?php } ?>
<h4><i class="fa fa-wrench text-primary"></i> Service Requested</h4>
<div class="row">
	<div class="col-sm-6">
		<p><b>Service Type:</b> <?php echo $services_array[$requests['service_type']]; ?></p>
		<p><b>Permission To Enter:</b>  <?php echo ucwords($requests['enter_permission']); ?></p>
		<p><b>Description:</b> <?php echo $requests['description']; ?></p>
	</div>
	<div class="col-sm-6">
		<p><b>Submitted:</b> <?php echo date('m-d-Y h:i a', strtotime($requests['submitted'])+3600); ?></p>
		<p><b>Status:</b> <?php if($requests['complete'] == 'y') { echo 'Completed On '.date('m-d-Y h:i a', strtotime($requests['completed'])+3600).' <small>EST</small>';	} else { echo'Incomplete';} ?></p>
		<p><b>Viewed:</b> <?php if($requests['viewed'] != '0000-00-00 00:00:00') { echo date('m-d-Y h:i a', strtotime($requests['viewed'])+3600).' <small>EST</small>';	} else { echo 'Has Not Been Opened Yet';} ?></p>
		<p><b>Call For Scheduling:</b> <?php echo "(".substr($requests['schedule_phone'], 0, 3).") ".substr($requests['schedule_phone'], 3, 3)."-".substr($requests['schedule_phone'],6); ?></p>
	</div>
</div>
<hr>
<?php
	if(!empty($requests['contractor_note'])) {
		echo '<h4><i class="fa fa-pencil text-primary"></i> Note From Landlord</h4>';
		echo '<p>'.htmlspecialchars($requests['contractor_note']).'</p>';
		echo '<hr>';
	}
?>
<div class="row">
	<div class="col-sm-8">
		<?php if(!empty($requests['attachment'])) { ?>
			<h4><i class="fa fa-picture-o text-primary"></i> Attached Image:</h4>
			<img src="<?php echo base_url().'service-uploads/'.$requests['attachment']; ?>" class="img-responsive service-request-image" alt="Service Attachment">
		<?php } ?>
	</div>
	<div class="col-sm-4">
		
		
	</div>
</div>
<?php
	if(!empty($requests['items'])) {	
		echo '<hr><h3><i class="fa fa-archive text-primary"></i> Items Related To This Request</h3>';
		echo '<div class="row">
				<div class="col-sm-3">
					<b>Item Name:</b>
				</div>
				<div class="col-sm-2">
					<b>Model#:</b>
				</div>
				<div class="col-sm-3">
					<b>Brand:</b>
				</div>
				<div class="col-sm-2">
					<b>Serial#:</b>
				</div>
				<div class="col-sm-2">
					<b>Service Type:</b>
				</div>
			</div>';
		foreach($requests['items'] as $val) {
			if(empty($val['modal_num'])) {
				$val['modal_num'] = 'NA';
			}
			if(empty($val['brand'])) {
				$val['brand'] = 'NA';
			}
			if(empty($val['serial'])) {
				$val['serial'] = 'NA';
			}
			echo '<div style="height: 1px; width: 100%; background: #ccc; margin: 3px 0;"></div>
					<div class="row">
						<div class="col-sm-3">
							'.$val['desc'].'
						</div>
						<div class="col-sm-2">
							'.$val['modal_num'].'
						</div>
						<div class="col-sm-3">
							'.$val['brand'].'
						</div>
						<div class="col-sm-2">
							'.$val['serial'].'
						</div>
						<div class="col-sm-2">
							'.$val['service_type'].'
						</div>
					</div>';
		}
	}
?>