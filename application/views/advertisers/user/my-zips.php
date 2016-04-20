<h2><i class="fa fa-map-marker text-primary"></i> My Premium Ads</h2>
<hr>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:</b> '.$this->session->flashdata('error').'.</div>';
	}	
	if($this->session->flashdata('upload_error')) 
	{
		echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:</b> '.$this->session->flashdata('upload_error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$this->session->flashdata('success').'.</div>';
	}
?>

<?php
	$userTypeArray = array('', 'Landlords', 'Renters', 'Contractors', 'Advertisers');
	if(!empty($my_zips)) {
		echo '<div class="row text-center">';
		foreach($my_zips as $key => $val) { ?>
			
				<div class="col-md-3">
					<div class="premo-ad">
						<?php 
							if($val->created == 'y') {
								echo '<div class="switch on"></div>';
							} else {
								echo '<div class="switch off"></div>';
							}
						?>
						<i class="fa fa-diamond fa-4x"></i>
						<h3><?php echo $userTypeArray[$val->service_purchased].' in '.$val->zip_purchased; ?></h3>
						<p>Active Until <?php echo date('m-d-Y', strtotime($val->deactivation_date)); ?></p>
						<p><a href="<?php echo base_url('local-partner/my-zips/edit/'.$val->id); ?>" class="btn btn-default"><i class="fa fa-pencil"></i> Setup Ad</a></p>
					</div>
				</div>
	<?php	}
		echo '</div>';
	}  else {
		echo '<p>You don\'t have any active premium ads. Once you purchase premium ads you will be able to see your zips and services here.</p>';
	}
	
	if(!empty($my_zips)) {
		echo '<hr>';
		echo '<div class="alert alert-info"><h4><i class="fa fa-calendar-plus-o"></i> Want to extend some or all your premium ads? <a href="'.base_url("local-partner/my-zips/extend").'" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Extend</a></h4></div>';
	}
?>
