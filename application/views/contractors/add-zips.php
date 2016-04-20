<?php	
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><b>Success:</b>'.$this->session->flashdata('success').'</div>';
	}
		
?>	
	<div class="panel panel-success">
				<div class="panel-heading">
					<h3 style="margin: 0; color: #fff;"><i class="fa fa-map-marker"></i> Manage Your Zip Codes and Services</h3>
				</div>
				<div class="panel-body">
					<h4><i class="fa fa-map-marker text-success"></i> Select Your Service And Zip Code And Click The Search Button</h4>
					<div class="error-helper"></div>
					<div class="row">
						<div class="col-sm-4">
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control &#124; Exterminator'); 
								echo "<select tabindex='1' id='serviceType' class='form-control input-lg' required=''>";
								echo '<option value="">Choose One...</option>';
								foreach($services_array as $key => $val) {
									if(isset($_POST['serviceType'])) {
										if($_POST['serviceType'] == $key) {
											echo '<option selected="selected">'.$val.'</option>';
										}
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}
								echo "</select>";
							?>
							<br>
						</div>
						<div class="col-sm-4">
							<div class="input-group">
								<input type="text" class="form-control zipSearch input-lg" placeholder="Search&hellip;" maxlength="5" value="43055">
								<span class="input-group-btn">
									<button type="button" class="btn btn-warning btn-lg searchZips" tabindex="2"><i class="fa fa-search"></i></button>
								</span>
							</div>
							
						</div>
					</div>
					
					<div class="row">
						<div class="col-sm-12">
							<br>
							<div class="loadingZips"></div>
						</div>
					</div>
					
					<br>
					<div class="zip-results">
						
					</div>
					<h3 class="highlight">Zip Codes and Services:</h3>
					<hr>
			
					<div class="zips_purchased">
						<div class="row" style="font-weight: bold">	
							<div class="col-xs-1 counter">#</div>
							<div class="col-xs-1">Zip</div>
							<div class="col-sm-2 hidden-xs">City</div>
							<div class="col-sm-1 hidden-xs">State</div>
							<div class="col-sm-3 col-xs-6">Service</div>
							<div class="col-sm-3 hidden-xs">Ad Status</div>
							<div class="col-xs-1">Remove</div>
						</div>
						<?php
							$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14 =>'Pest Control | Exterminator');		
							if(!isset($zips['error'])) {
								echo '<div class="col-sm-12"><div class="zipsAdded"></div></div>';
								$count = 1;
								foreach($zips as $key => $val) {
									
									echo '<div id="btn-'.$val->id.'" class="border-bottom row selectedZips" data-dup="'.$val->zip.'-'.$val->service_type.'">';	
									echo '<div class="col-xs-1 counter">'.$count.'</div>';
									echo '<div class="col-xs-1">'.$val->zip.'</div>';
									echo '<div class="col-sm-2 hidden-xs">'.$val->city.'</div>';
									echo '<div class="col-sm-1 hidden-xs">'.$val->state.'</div>';
									echo '<div class="col-sm-3 col-xs-6">'.$services_array[$val->service_type].'</div>';
									if($val->purchased =='y') {
										echo '<div class="col-sm-3 hidden-xs">';
											echo '<span class="text-danger">Active w/ad</span>';
										echo '</div>';
									} else {
										echo '<div class="col-sm-3 hidden-xs">';
											echo '<span class="text-success">Available</span>';
										echo '</div>';
									}
									echo '<div class="col-xs-1">';
									if($val->purchased =='y') {
										echo '<button class="btn btn-sm btn-default" disabled data-zip="'.$val->zip.'" data-service="'.$val->service_type.'" data-remove="'.$val->id.'"><i class="fa fa-check  fa-fw"></i></button>';
									} else {
										echo '<button class="btn btn-sm btn-danger removeZip" data-zip="'.$val->zip.'" data-service="'.$val->service_type.'" data-remove="'.$val->id.'"><i class="fa fa-times  fa-fw"></i></button>';
									}
									echo '</div>';
									echo '<div class="clearfix"></div></div>';
									$count++;
								}
							} else {
								echo '<div class="noZips"><div class="text-danger">* '.$zips['error'].'</div></div>';
							}
						?>
					</div>
					<br>				
					<br>				
				</div>
			</div>