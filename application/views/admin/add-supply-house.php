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
				<?php echo form_open_multipart('', array('id'=>'addSupplyHouse')); ?>
					<div class="row">
						<div class="col-md-6">
						
							<div class="form-group">
								<label><span class="text-danger">*</span> Business Name:</label>
								<input name="name" type="text" maxlength="50" class="form-control" maxlength="" required tabindex="1">
							</div>
							
							<div class="form-group">
								<label><span class="text-danger">*</span> Address:</label>
								<input name="address" type="text" class="form-control" maxlength="50" required tabindex="3">
							</div>
							
							<div class="form-group">
								<label><span class="text-danger">*</span> Logo:</label>
								<input name="logo" id="logo" type="file" class="form-control" required tabindex="6">
							</div>
							
							<div class="form-group">
								<label>Email:</label>
								<input name="email" id="email" type="email" class="form-control" tabindex="8">
							</div>
							
							<div class="well well-sm">
								<div class="checkbox">
									<label>
										<input type="checkbox" value="y" id="affiliate" tabindex="9" name="affiliate"> <b> Setup Public Page?</b>
									</label>
								</div>
							</div>
							
						</div>
						<div class="col-md-6">
						
							<div class="form-group">
								<label><span class="text-danger">*</span> Phone:</label>
								<input name="phone" type="text" class="form-control" maxlength="14" required tabindex="2">
							</div>
							
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label><span class="text-danger">*</span> City:</label>
										<input name="city" type="text" class="form-control" maxlength="30" required tabindex="4">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label><span class="text-danger">*</span> State:</label>
										<select name="state" class="form-control" required tabindex="5">
										<?php
											$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
											
											foreach($states as $key => $val) {
												echo '<option value="'.$key.'">'.$val.'</option>';
											}
										
										?>
										</select>
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<label>URL:</label>
								<input name="url" type="url" class="form-control" maxlength="200" tabindex="7">
							</div>
							
						</div>
					</div>
					
					<div id="affiliate-account" class="fade">
						<hr>
						<div class="row">
							<div class="col-md-6 col-md-6">
								<div class="form-group">
									<label><span class="text-danger">*</span> Unique Link Name: <small>(n4r.rentals/NAME)</small></label>
									<input id="unique" name="unique" type="text" class="form-control" required tabindex="10" disabled>
								</div>
							</div>
							<div class="col-md-6 col-md-6">
								<div class="form-group">
									<label><span class="text-danger">*</span> Background Image:</label>
									<input id="background" name="background" type="file" class="form-control" required disabled tabindex="11">
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
													echo '<option value="'.$row->unique_id.'">'.$row->first_name.' '.$row->last_name.'</option>';
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
										<textarea name="ad_areas" class="form-control textarea-lg tags" required tabindex="12"></textarea>
									</div>
									
									<div class="form-group">
										<div class="row">
											<label class="col-md-4">Service Types:</label>
											<div class="col-md-8">
												<select id="ad_services" name="ad_services" class="multiselect form-control" multiple="multiple" required tabindex="13">
													<?php
														foreach($service_types as $val) {
															echo '<option value="'.$val->key.'">'.$val->value.'</option>';
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
												foreach($service_types as $val) {
													echo '<option value="'.$val->key.'">'.$val->value.'</option>';
												}
											?>
										</select>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					
					<br>
					
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<button id="submit" type="submit" tabindex="16" class="btn btn-success btn-block btn">Save Supply House</button>
						</div>
					</div>
					
					<br>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>