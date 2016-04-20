<div class="row">
	<div class="col-md-8">
		<div class="widget">
			<div class="widget-header">
				<div class="title">
					<i class="fa fa-plus"></i> Add Supply House
				</div>
				<span class="tools">
				  <a href="<?php echo base_url('n4radmin/supply-houses'); ?>" class="btn btn-primary"><i class="fa fa-reply"></i> Go Back</a>
				</span>
			</div>
			<div class="widget-body">	
				<?php echo form_open_multipart('', array('id'=>'editSupplyHouse')); ?>
					<div class="row">
						<div class="col-md-6">
							
							<div class="form-group">
								<label><span class="text-danger">*</span> Business Name:</label>
								<input name="name" type="text" maxlength="50" class="form-control" maxlength="" required tabindex="1" value="<?php echo $house->business; ?>">
							</div>
							
							<div class="form-group">
								<label><span class="text-danger">*</span> Address:</label>
								<input name="address" type="text" class="form-control" maxlength="50" required tabindex="3" value="<?php echo $house->address; ?>">
							</div>
							
							<div class="form-group">
								<label>Logo:</label>
								<input name="logo" id="logo" type="file" class="form-control" tabindex="6" >
								<span class="text-danger">*</span> <small>Skip if you already have a logo uploaded</small>
							</div>
							
							<div class="form-group">
								<label>Email:</label>
								<input name="email" id="email" type="email" class="form-control" tabindex="8" value="<?php echo $house->email; ?>">
							</div>
							
							<div class="well well-sm">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="y"  <?php if(!empty($house->affiliate)) {echo 'checked';} ?> id="affiliate" tabindex="9" name="affiliate"> <b> Setup Public Page?</b>
									</label>
								</div>
							</div>
							
						</div>
						<div class="col-md-6">
						
							<div class="form-group">
								<label><span class="text-danger">*</span> Phone:</label>
								<input name="phone" type="text" class="form-control" maxlength="14" required tabindex="2" value="<?php echo $house->phone; ?>">
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><span class="text-danger">*</span> City:</label>
										<input name="city" type="text" class="form-control" maxlength="30" required tabindex="4" value="<?php echo $house->city; ?>">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label><span class="text-danger">*</span> State:</label>
										<select name="state" class="form-control" required tabindex="5">
										<?php
											$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
											
											foreach($states as $key => $val) {
												if($key == $house->state) {
													echo '<option value="'.$key.'" selected>'.$val.'</option>';
												} else {
													echo '<option value="'.$key.'">'.$val.'</option>';
												}
											}
										
										?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label>URL:</label>
								<input name="url" value="<?php echo $house->url; ?>" type="url" class="form-control" maxlength="200" tabindex="7">
							</div>
							
							<div class="row">
								<div class="col-md-8 col-md-offset-2">
									<div class="form-group">
										<h5><b>Logo Preview</b></h5>
										<img id="supplyHouseLogo" src="<?php echo base_url($house->logo); ?>" alt="emf_profilepic" class="aligncenter img-responsive">
									</div>
								</div>
							</div>
								
						</div>
					</div>
					
					<div id="affiliate-account" class="fade <?php if(!empty($house->affiliate)) {echo 'in displayAccount';} ?>">
						<hr>
						<div class="row">
							<div class="col-md-6 col-md-6">
								<div class="form-group">
									<label><span class="text-danger">*</span> Unique Link Name: <small>(n4r.rentals/NAME)</small></label>
									<input id="unique" value="<?php echo $public->unique_name; ?>" name="unique" type="text" class="form-control" required tabindex="10" <?php if(empty($house->affiliate)) {echo 'disabled';} ?>>
								</div>
							</div> 
							<div class="col-md-6 col-md-6"> 
								<div class="form-group">
									<label>Background Image:</label>
									<input id="background" name="background" <?php if(empty($house->affiliate)) {echo 'disabled';} ?> type="file" class="form-control" tabindex="11">
									<span class="text-danger">*</span> <small>If already set, leave blank otherwise you it is required</small>
								</div>
							</div> 
						</div> 
						
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label>Assign To Affiliate?:</label>
									<select name="affiliate_id" class="form-control">
										<option value="">Select an Affiliate...</option>
										<?php
											if(!empty($affiliates)) {
												foreach($affiliates as $row) {
													if($row->unique_id == $house->affiliate_id) {
														echo '<option value="'.$row->unique_id.'" selected>'.$row->first_name.' '.$row->last_name.'</option>';
													} else {
														echo '<option value="'.$row->unique_id.'">'.$row->first_name.' '.$row->last_name.'</option>';
													}
												}
											}
										?>
									</select>
								</div>
							</div>
						</div>
						
					</div>
				
					<hr>	
						
					<div class="row">
						<div class="col-md-6">
							<div class="panel panel-primary">
								<div class="panel-heading text-right">
									<i class="fa fa-gears"></i> Ad Settings
								</div>
								<div class="panel-body">
								
									<p>This section determines where this supply house's ad will appear on the service request.</p>	
									
									<div class="form-group">
										<label><span class="text-danger">*</span> Service Area Zips:</label>
										<textarea name="ad_areas" class="form-control textarea-lg tags" required tabindex="12"><?php echo $house->ad_areas; ?></textarea>
									</div>
									
									<div class="form-group">
										<div class="row">
											<label class="col-md-4">Service Types:</label>
											<div class="col-md-8">
												<select id="ad_services" name="ad_services" class="multiselect form-control" multiple="multiple" required tabindex="13">
													<?php
														$serviceAdsArray = explode(',', $house->ad_service_types);
														foreach($service_types as $val) {
															if(in_array($val->key, $serviceAdsArray)) {
																echo '<option selected value="'.$val->key.'">'.$val->value.'</option>';
															} else {
																echo '<option value="'.$val->key.'">'.$val->value.'</option>';
															}
														}
													?>
												</select>
											</div>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="panel panel-primary">
								<div class="panel-heading text-right">
									<i class="fa fa-map-o"></i> Resource Page Settings
								</div>
								<div class="panel-body">
									
									<p>This section only affects the resource page and will not show up on service requests.</p>

									
									<div class="form-group">
										<label>Service Types:</label>
										<select id="resource_types" name="resource_types" class="multiselect form-control" multiple="multiple" required tabindex="15">
											<?php
												$serviceAdsArray = explode(',', $house->resource_service_types);
												foreach($service_types as $val) {
													if(in_array($val->key, $serviceAdsArray)) {
														echo '<option selected value="'.$val->key.'">'.$val->value.'</option>';
													} else {
														echo '<option value="'.$val->key.'">'.$val->value.'</option>';
													}
												}
											?>
										</select>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<br>
					<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>" required>
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<button id="submit" type="submit" class="btn btn-success btn-block btn">Save Supply House</button>
						</div>
					</div>
					
					<br>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>