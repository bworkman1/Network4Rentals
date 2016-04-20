<h3><i class="fa fa-map-marker text-success"></i> My Zips</h3>
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
	<div class="col-sm-4">
		<b>Service:</b>
	</div>	
	<div class="col-sm-2 text-center">
		<b>Options</b>
	</div>
</div>
<?php } ?>
<?php 
	if(!empty($my_zips)) {
		$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding');
		foreach($my_zips as $key => $val) {
			if($page_setting == '2') {
				$warning = '<i class="fa fa-exclamation-triangle toolTips text-danger" title="This Post Is Not Active, Please Fill Out The Details On The \'My Web Page\' Section To Enable Your Ads"></i>';
			} else {
				if($val->created == 'n') {
					$warning = '<i class="fa fa-exclamation-triangle toolTips text-danger" title="This Post Is Not Active, In Order For It To Show Up You Will Need To Create The Post By Clicking The Edit Button"></i>';
				} else {
					$warning = '';
				}
			}
			echo '<div class="row myZips">';
				echo '<div class="col-sm-5">';
					echo '<span class="visible-xs"><b>Area:</b></span>'.$warning.' '.$val->city.' '.$val->state.'- '.$val->zip_purchased;
				echo '</div>';
				echo '<div class="col-sm-4">';
					echo '<span class="visible-xs"><b>Service:</b></span>'.$services_array[$val->service_purchased];
				echo '</div>';
				echo '<div class="col-sm-3 text-center">';
					echo '<a href="'.base_url().'contractors/edit-post/'.$val->id.'" class="btn btn-success btn-xs btn-block"><i class="fa fa-edit"></i> Edit Post/Create Ad</a>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo '<p>You don\'t have any active subscriptions, running right now. Once you add a subscription to our services you will be able to see your zips and services here.</p><a href="'.base_url().'contractors/add-zip-codes" style="margin-top: 5px" class="btn btn-success btn-sm"><i class="fa fa-map-marker"></i> Add Zips/Services</a>';
	}
?>