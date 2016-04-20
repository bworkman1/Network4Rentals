<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-plus"></i> Add A Landlord
	</div>
	<div class="panel-body">
<br>
<div class="row">
<div class="col-sm-10">
	<p>Adding a landlord allows you to track your rental history, add payments, and find rental history information easily.</p>
</div>
<div class="col-sm-2">
	<button class="btn btn-warning btn-block btn-sm" data-toggle="modal" data-target="#helpvideo">Need Help?</button>
</div>
</div>
<br>
<hr>
<br>
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
<div class="well">
	<div class="row">
		<div class="col-sm-7">
			Search For Your Landlord By Name Or Business To Auto Fill Input
		</div>
		<div class="col-sm-5">
			<input type="text" class="form-control input-sm" id="landlord-search" placeholder="Start Typing To Search 3 Characters Min">
			<div id="results">
			</div>
		</div>
	</div>
</div>
<div id="addLandlord"> 
<?php
	echo form_open_multipart('renters/add_landlord');
	echo '<h3 class="pull-left">Landlord Details</h3>';
	echo '<div class="thinking pull-right"></div>';
	echo '<div class="clearfix"></div>';
	echo '<hr>';
	echo '<div id="landlordaltinfo">';
	echo '<h4>One Or The Other Is Required</h4>';
		echo '<div class="well">';
			echo '<div class="row">';
				echo '<div class="col-sm-6">';
					echo '<label><i class="fa fa-asterisk text-danger"></i> Email:</label>';
					echo '<input type="email" class="form-control" maxlength="100" id="email" name="landlord_email" required>';
				echo '</div>';
				echo '<div class="col-sm-6">';
					echo '<label><i class="fa fa-asterisk text-danger"></i> Cell Phone:</label>';
					echo '<input type="text" name="cell_phone" class="form-control phone" maxlength="18" id="cell-phone">';
				echo '</div>';
			echo '</div>';
			echo '<div id="phoneError" class="text-danger"></div>';
		echo '</div>';
	echo '</div>';
	echo '<div class="row landlordDetails">';
	echo '<div class="col-sm-6">';
	echo form_label('Business Name:');
	$data = array(
	  'name'        => 'bName',
	  'id'          => 'bName',
	  'maxlength'   => '100',
	  'class'       => 'form-control'
	);

	echo form_input($data);	
	echo form_label('Landlords/Contact Name:');
	$data = array(
	  'name'        => 'landlord_name',
	  'id'          => 'lName',
	  'maxlength'   => '200',
	  'class'       => 'form-control',
	  'required'       => 'required'
	);
	echo form_input($data);	

	echo form_label('Landlords/Contact Phone:');
	$data = array(
	  'name'        => 'landlord_phone',
	  'id'          => 'phone',
	  'maxlength'   => '20',
	  'class'       => 'form-control phone'
	);
	echo form_input($data);	
	echo '</div>'; // End Of Left Side
	echo '<div class="col-sm-6">';
	echo form_label('Street Address:');
	$data = array(
	  'name'        => 'landlord_address',
	  'id'          => 'address',
	  'maxlength'   => '200',
	  'class'       => 'form-control',
	);
	echo form_input($data);
	echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
	$data = array(
	  'name'        => 'landlord_city',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'class'       => 'form-control',
	  'required'       => 'required'
	);
	echo form_input($data);
	echo '<div class="row">';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> State:');			
	$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
	echo '<select id="state" name="state" class="form-control" required="">';
	echo '<option value="">Select One...</option>';
	if(empty($_POST['state'])) {
		$state = '';
	} else {
		$state = $_POST['state'];
	}
	foreach($states as $key => $val) {
		if($key == $state) {
			echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
		} else {
			echo '<option value="'.$key.'">'.$val.'</option>';
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
	  'required'	=> 'required'
	);
	echo form_input($data);	
	echo '<input type="hidden" id="landlord-id" name="link_id">';
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
	  'required' 	=> ''
	);
	echo form_input($data);
	echo form_label('<i class="fa fa-asterisk text-danger"></i> City:');
	$data = array(
	  'name'        => 'rental_city',
	  'id'          => 'city',
	  'maxlength'   => '60',
	  'class'       => 'form-control',
	  'required' 	=> '',
	);
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
		if($key == $state) {
			echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
		} else {
			echo '<option value="'.$key.'">'.$val.'</option>';
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
	);
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
	  'class'       => 'form-control datepicker',
	  'autocomplete' => 'off',
	  'required' 	=> ''
	);
	if(!empty($_POST['move_in'])) {
		$data['value'] = date('m-d-Y', strtotime($_POST['move_in']));
	}
	echo form_input($data);
	echo '</div>';
	echo '<div class="col-sm-6">';
	echo form_label('Move Out Date:');
	$data = array(
	  'name'        => 'move_out',
	  'id'          => 'move_out',
	  'maxlength'   => '60',
	  'autocomplete' => 'off',
	  'class'       => 'form-control datepicker'
	);
	if(!empty($_POST['move_out'])) {
		$data['value'] = date('m-d-Y', strtotime($_POST['move_out']));
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
	);
	echo form_input($data);	
	echo '</div>';
	echo '<div class="col-sm-6">';
	echo form_label('<i class="fa fa-asterisk text-danger"></i> Lease Length:');
	echo '<select class="form-control" name="lease" required="required">';
	$lenths_array = array('Month To Month', '3 Months', '6 Months', '9 Months', '1 Year', '2 Year', '3 Year');
	echo '<option value="">Select One..</option>';
	foreach($lenths_array as $val) {
		echo '<option>'.$val.'</option>';
	}
	echo '</select>';
	echo '</div>';
	echo '</div>';
	echo '<div class="row">';
	echo '<div class="col-sm-6">';
		echo '<label><i class="fa fa-asterisk text-danger"></i> Deposit</label>';
		echo '<input type="text" class="form-control money" name="deposit">';
	echo '</div>';
	echo '<div class="col-sm-6">';
		echo '<label><i class="fa fa-asterisk text-danger"></i> Day Rent Is Due:</label>';
		echo '<select class="form-control" name="day_rent_due">';
			for($i=0;$i<29; $i++) {
				echo '<option>'.($i+1).'</option>';
			}
		echo '</select>';
	echo '</div>';
	echo '</div>'; // end of right side
	echo '<div class="res-error text-danger"></div>';
	echo '</div>'; // end of right side
	echo '</div>'; //end of row
	echo '<input type="hidden" name="group_id" id="group_id">';
	echo '<br><button class="btn btn-warning btn-sm saveLandlord" type="submit"><i class="fa fa-save"></i> Save Landlord</button>';
	echo form_close();
?>
</div>
</div>
</div>
<br><br>
<br><br>
<div class="modal fade" id="suggestion-window" tabindex="-1" role="dialog" aria-labelledby="suggestion-window" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Similar Landlords Found</h4>
      </div>
      <div class="modal-body">
		<p>It looks like the landlord you are searching for already exists. Take a look at some of the suggestions below and see if you can find your landlord.</p>
        <div id="suggestions">
		
		</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="helpvideo" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-question text-warning"></i> How To Create Your Account</h4>
			</div>
			<div class="modal-body">
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<iframe width="560" height="315" src="//www.youtube.com/embed/0Mxi6EJEQ5c?list=PLc6pWpJ0Cx_mGBqbIZm6KVc-wCMbvb2Ya" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>