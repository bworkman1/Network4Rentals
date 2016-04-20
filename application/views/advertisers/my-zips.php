<h3><i class="fa fa-map-marker text-primary"></i> My Zips</h3>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
?>
<?php if(!empty($my_zips)) { ?>
<div class="row myZips hidden-xs hidden-sm">
	<div class="col-sm-6">
		<b>Area:</b>
	</div>
	<div class="col-sm-3">
		<b>User:</b>
	</div>
	<div class="col-sm-3 text-center">
		<b>Options</b>
	</div>
</div>
<br>
<?php } ?>
<?php 
	$services_array = array('', 'Landlords', 'Tenants', 'Contractors', 'Listings');
	if(!empty($my_zips)) {
		foreach($my_zips as $key => $val) {
			if($page_setting == '2') {
				$warning = '<i class="fa fa-exclamation-triangle toolTips text-danger" title="This Post Is Not Active, Please Fill Out The Details On The \'My Web Page\' Section To Enable Your Ads"></i>';
			} else {
				if($val->created == 'n') {
					$warning = '<i class="fa fa-exclamation-triangle toolTips text-danger" title="This Post Is Not Active, In Order For It To Show Up You Will Need To Create The Post By Clicking The Edit Button"></i>';
				} else {
					$warning = '<i class="fa fa-check toolTips text-success" title="This Post Is Active And Showing"></i>';
				}
			}
			echo '<div class="row myZips">';
				echo '<div class="col-sm-6">';
					echo '<span class="visible-xs"><b>Area:</b></span>'.$warning.' '.$val->city.' '.$val->state.'- '.$val->zip_purchased;
				echo '</div>';
				echo '<div class="col-sm-3">';
					echo $services_array[$val->service_purchased];
				echo '</div>';
				echo '<div class="col-sm-3 text-center">';
					echo '<a href="'.base_url().'advertisers/edit-post/'.$val->id.'" class="btn btn-primary btn-sm btn-block"><i class="fa fa-edit"></i> Edit Post/Create Ad</a>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo '<p>You don\'t have any active subscriptions, running right now. Once you add a subscription to our services you will be able to see your zips and services here.</p><a href="'.base_url().'advertisers/add-zip-codes" style="margin-top: 5px" class="btn btn-primary btn-sm"><i class="fa fa-map-marker"></i> Add Zips/Services</a>';
	}
?>