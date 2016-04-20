<?php
	if($tenant_info->address_locked == 1) {
		echo '<h4 class="well well-sm"><i class="fa fa-lock text-primary"></i> Your Landlord Has Locked This Address So You Cannot Edit The Address On This Account.</h4>';
	}
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
?>
<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-bars"></i> Edit Rental Details
	</div>
	<div class="panel-body">


<?php
	echo form_open_multipart('renters/edit_rental_details');
	echo '<h3 class="pull-left">Landlord Details</h3>';
	echo '<div class="thinking pull-right"></div>';
	echo '<div class="clearfix"></div>';
	echo '<hr>';
	echo '<div class="row landlordDetails">';
	echo '<div class="col-sm-6">';
	echo form_label('Business Name:');
	$data = array(
	  'name'        => 'bName',
	  'id'          => 'bName',
	  'maxlength'   => '100',
	  'class'       => 'form-control',
	  'value' 		=> $landlord_info['bName']
	);
	if(!empty($landlord_info['user'])) {
		$data['readonly'] = 'readonly';
	}

	echo form_input($data);	
	echo form_label('Landlords/Contact Name:');
	$data = array(
	  'name'        => 'landlord_name',
	  'id'          => 'lName',
	  'maxlength'   => '200',
	  'class'       => 'form-control',
	  'value' 		=> $landlord_info['name']
	);
	if(!empty($landlord_info['user'])) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);	
	echo form_label('Landlords/Contact Email:');
	$data = array(
	  'name'        => 'landlord_email',
	  'id'          => 'email',
	  'maxlength'   => '100',
	  'class'       => 'form-control',
	  'value' 		=> $landlord_info['email']
	);
	if(!empty($landlord_info['user'])) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);	
	echo form_label('Landlords/Contact Phone:');
	$data = array(
	  'name'        => 'landlord_phone',
	  'id'          => 'phone',
	  'maxlength'   => '20',
	  'class'       => 'form-control phone',
	  'value' 		=> $landlord_info['phone']
	);
	if(!empty($landlord_info['user'])) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);	
	echo '</div>'; // End Of Left Side
	if($tenant_info->current_residence == 'y') {
		echo '<div class="col-sm-6">';
	} else {
		echo '<div class="col-sm-6" style="display: none">';
	}
		echo form_label('Street Address:');
		$data = array(
		  'name'        => 'landlord_address',
		  'id'          => 'address',
		  'maxlength'   => '200',
		  'class'       => 'form-control',
		  'value' 		=> $landlord_info['address']
		);
		if(!empty($landlord_info['user'])) {
			$data['readonly'] = 'readonly';
		}
		echo form_input($data);
		echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
		$data = array(
		  'name'        => 'landlord_city',
		  'id'          => 'city',
		  'maxlength'   => '60',
		  'class'       => 'form-control',
		  'value' 		=> $landlord_info['city']
		);
		if(!empty($landlord_info['user'])) {
			$data['readonly'] = 'readonly';
		}
		echo form_input($data);
		echo '<div class="row">';
		echo '<div class="col-sm-6">';
		echo form_label('<i class="fa fa-asterisk text-danger"></i> State:');			
		$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
		echo '<select id="state" name="state" class="form-control" required="">';
		echo '<option value="">Select One...</option>';
		foreach($states as $key => $val) {
			if($key == $landlord_info['state']) {
				echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
			} else {
				if(empty($landlord_info['user'])) {
					echo '<option value="'.$key.'">'.$val.'</option>';
				} else {
					echo '<option value="'.$key.'" disabled="disabled">'.$val.'</option>';
				}
			}
		}
		
		echo '</select>';	
	
		echo '</div>';
		echo '<div class="col-sm-6">';
		echo form_label('<i class="fa fa-asterisk text-danger"></i> Zip:');
		$data = array(
		  'name'        => 'zip',
		  'id'          => 'zip',
		  'maxlength'   => '10',
		  'class'       => 'form-control',
		  'value' 		=> $landlord_info['zip']
		);
		if(!empty($landlord_info['user'])) {
			$data['readonly'] = 'readonly';
		}
		echo form_input($data);	
		echo '<input type="hidden" id="landlord-id" name="link_id" value="'.$landlord_info['id'].'">';
		echo '</div>';
		echo '</div>';
	
	echo '</div>'; //end of right side
	echo '</div>';// end of landlord details
	echo '<h3>Rental Home Details</h3>';
	echo '<hr>';
	
	echo '<div class="row">';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Street Address:');
	$data = array(
	  'name'        => 'rental_address',
	  'id'          => 'address',
	  'maxlength'   => '200',
	  'class'       => 'form-control',
	  'required' 	=> '',
	  'value' 		=> $tenant_info->rental_address
	);
	if($tenant_info->address_locked == 1) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);
	echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
	$data = array(
	  'name'        => 'rental_city',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'class'       => 'form-control',
	  'required' 	=> '',
	  'value' 		=> $tenant_info->rental_city
	);
	if($tenant_info->address_locked == 1) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);
	echo '<div class="row">';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> State:');			
	$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
	echo '<select name="rental_state" class="form-control" required="">';
	echo '<option value="">Select One...</option>';
	if(empty($_POST['rental_state'])) {
		$state = '';
	} else {
		$state = $_POST['rental_state'];
	}
	foreach($states as $key => $val) {
		if($key == $tenant_info->rental_state) {
			echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
		} else {
			if($tenant_info->address_locked == 1) {
				echo '<option value="'.$key.'" disabled="disabled">'.$val.'</option>';
			} else {
				echo '<option value="'.$key.'">'.$val.'</option>';
			}
		}
	}
	echo '</select>';	
	echo '</div>';
	
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Zip:');
	$data = array(
	  'name'        => 'rental_zip',
	  'id'          => 'zip',
	  'maxlength'   => '10',
	  'class'       => 'form-control',
	  'required' 	=> '',
	  'value' 		=> $tenant_info->rental_zip
	);
	if($tenant_info->address_locked == 1) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);	
	echo '</div>';
	echo '</div>';
	echo '<label>Upload Lease (Allowed Types: gif | jpg | png | jpeg | pdf | doc)</label>';
	echo '<input type="file" class="form-control attachment" name="file" size="20" />';
	echo '</div>'; // end of left side
	echo '<div class="col-sm-6">';
	echo '<div class="row">';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Move In Date:');
	$data = array(
	  'name'        => 'move_in',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'required' 	=> '',
	  'autocomplete' => 'off',
	  'value'		=> date('m/d/Y', strtotime($tenant_info->move_in))
	);
	if($tenant_info->address_locked == 1) {
		$data['readonly'] = 'readonly';
		$data['class'] = 'form-control';
	} else {
		$data['class'] = 'form-control datepicker';
	}
	echo form_input($data);
	echo '</div>';
	echo '<div class="col-sm-6">';
	echo form_label('Move Out Date:');
	$data = array(
	  'name'        => 'move_out',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'autocomplete' => 'off',
	  'class'       => 'form-control datepicker',
	);
	if($tenant_info->move_out != '0000-00-00') {
		$data['value'] = date('m-d-Y', strtotime($tenant_info->move_out));
	}
	echo form_input($data);
	echo '</div>';
	echo '</div>';

	echo '<div class="row">';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Rent Per Month:');
	$data = array(
	  'name'        => 'payments',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'class'       => 'form-control money',
	  'required' 	=> '',
	  'value' 		=> $tenant_info->payments
	);
	if($tenant_info->address_locked == 1) {
		$data['readonly'] = 'readonly';
	}
	echo form_input($data);
	echo '</div>';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Lease Length:');
	echo '<select class="form-control" name="lease" required="">';
	$lenths_array = array('Month To Month', '3 Months', '6 Months', '9 Months', '1 Year', '2 Year', '3 Year');
	echo '<option value="">Select One..</option>';
	foreach($lenths_array as $val) {
		if($tenant_info->lease == $val) {
			echo '<option selected="selected">'.$val.'</option>';
		} else {
			if($tenant_info->address_locked == 1) {
				echo '<option disabled="disabled">'.$val.'</option>';
			} else {
				echo '<option>'.$val.'</option>';
			}
		}
	}
	echo '</select>';
	echo '</div>';
	echo '</div>';
	echo '<div class="row">';
		echo '<div class="col-sm-6">';
			echo '<label><i class="fa fa-asterisk text-danger"></i> Deposit</label>';
			if($tenant_info->address_locked == 1) {
				$lock = 'readonly="readonly"';
			} else {
				$lock ='';
			}
			echo '<input type="text" class="form-control money" name="deposit" value="'.$tenant_info->deposit.'" '.$lock.'>';
		echo '</div>';
	echo '</div>';
	if($tenant_info->current_residence == 'y') {
		$checked = 'checked';
	} else {
		$checked = '';
	}
	echo '<br><div class="row"><div class="col-sm-6"><label>Current Residence? <br><input type="checkbox" value="y" name="current" '.$checked.'> Yes</label></div><div class="col-sm-6">';
	
	echo '<i class="fa fa-question toolTips pull-right text-primary" title="What day of the week is rent due on?"></i>';
	echo '<label><i class="fa fa-asterisk text-danger"></i> Day Rent Is Due On? </label>';
	if($tenant_info->address_locked == 1) { 
		if(empty($tenant_info->day_rent_due)) {
			$tenant_info->day_rent_due = '1';
		}
		echo '<input type="hidden" name="day_rent_due" value="'.$tenant_info->day_rent_due.'">';
		echo '<select class="form-control" disabled name="day_rent_due">';
	} else {
		echo '<select class="form-control" name="day_rent_due">';
	}
		for($i=1;$i<31;$i++) {
			if($i == $tenant_info->day_rent_due) {
				echo '<option selected="selected">'.$i.'</i>';
			} else {
				echo '<option>'.$i.'</option>';
			}
		}
	echo '</select>';
	
	echo '</div></div>';
	
	echo '</div>'; // end of right side
	echo '</div>'; //end of row
	if($tenant_info->move_out == '0000-00-00') {
		echo '<br><button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-save"></i> Save Details</button>';
	}
	echo form_close();
?>
</div>
</div>