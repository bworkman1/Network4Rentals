<div class="well">
	<?php echo form_open($this->uri->segment(1).'/supply-house-search'); ?>
		<div class="row">
				<div class="col-md-2  col-sm-6 col-xs-6">
					<label><span class="text-danger">*</span> Zip:</label>
					<input type="text" value="<?php echo $_POST['zip']; ?>" name="zip" class="form-control" maxlength="5">
				</div>
			
				<div class="col-md-3 col-sm-6 col-xs-6">
					<label><span class="text-danger">*</span> Radius:</label>
					<select name="radius" class="form-control">
						<?php
							$options = array('5'=>'5 Mile Radius', '10'=>'10 Mile Radius', '20'=>'20 Mile Radius', '30'=>'30 Mile Radius', '50'=>'50 Mile Radius');
							
							foreach($options as $key => $val) {
								if($key == $_POST['radius']) {
									echo '<option selected value="'.$key.'">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
							
						?>
						
					</select>
				</div>
							
			
				<div class="col-md-5 col-sm-6 col-xs-6">
					<label>Type:</label>
				
					<?php 
						$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
						echo "<select name='serviceType' id='serviceType' class='form-control'>";
						echo '<option value="">Choose One...</option>';
						foreach($services_array as $key => $val) {
							if($key==$_POST['serviceType']) {
								echo '<option selected value="'.$key.'">'.$val.'</option>';
							} else {
								echo '<option value="'.$key.'">'.$val.'</option>';
							}
						}
						echo "</select>";
					?>
				</div>
			
				<div class="col-md-2">
					<br>
					<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-search"></i> Search</button>
				</div>	
		</div>
		
	<?php echo form_close(); ?>
</div>
<?php if(!empty($results)) { ?>

	<div class="row">
		<div class="col-md-4">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-search"></i> Supply House Results </h3>
				</div>
				<div class="panel-body">
					
					<div class="list-group" style="max-height: 480px; overflow-y: scroll">
						<?php
							$i=0;
							foreach($results as $row) {
								$phone = '('.substr($row->phone, 0, 3).') '.substr($row->phone, 3, 3).'-'.substr($row->phone,6);
								echo '<a href="#" class="supplyHouseResult list-group-item" data-id="'.$i.'" data-phone="'.$phone.'" data-address="'.$row->address.', '.$row->city.' '.$row->state.'" data-title="'.$row->business.'" data-lat="'.$row->lat.'" data-url="http://n4r.rentals/'.$row->unique_name.'" data-long="'.$row->longitude.'">';
									echo '<img style="padding-right: 5px" src="'.base_url($row->logo).'" width="45" height="45" class="pull-left" />';
									echo '<p>';
										echo '<b> '.$row->business.'</b><br>';
										echo $row->address.', '.$row->city.' '.$row->state;
									echo '</p>';
									echo '<div class="clearfix"></div>';
								echo '</a>';
								$i++;
								
							}
						?>
						
							
						
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-md-8">
			<div class="panel panel-primary">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="fa fa-search"></i> Supply House Map 
						<?php
							if(!empty($_POST['serviceType'])) {
								echo 'For <b>'.$services_array[$_POST['serviceType']].'</b> Supply Houses';
							}
						?>
					</h3>
				</div>
				<div class="panel-body">
					<div id="map" style="height: 500px"></div>	
				</div>
			</div>
		</div>
	</div>
	
<?php } else { ?>
	<div class="alert alert-info">
		<h3 style="color: #fff; font-weight: bold">No Results Found</h3>
		<p>Check back later, we add supply houses daily so something is sure to show up in your area soon. If you own a supply house and would like to list your business contact us below.</p>
		<a href="https://network4rentals.com/help-support/" class="btn btn-primary">Support</a>
	</div>


<?php } ?>
	