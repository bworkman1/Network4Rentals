<h2><i class="text-primary fa fa-home"></i> <?php echo $listingHeader; ?></h2>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	
?>
<br>
<div class="panel panel-primary">
	<div class="panel-heading">
		<div class="row">
			<div class="col-md-6">
				<h3 class="panel-title" style="line-height: 2em"><i class="fa fa-search"></i> Narrow Results</h3>
			</div>
			<div class="col-md-3 col-md-offset-3">
				<input type="text" id="searchProperties" class="form-control input-sm" name="search" maxlength="15" placeholder="Search by street address">
			</div>
		</div>
	</div>
	<div class="panel-body text-center" style="">
		<div class="alert alert-info text-left">
			<i class="fa fa-exclamation-triangle"></i> Active/Inactive indicates whether or not the listing shows up when a renter searches for your property.
		</div>
		<div id="currentData">
			<div class="row">
				<?php 	
					if(!empty($results)) {
						$i = 0;
						foreach($results as $data) {
							
				?>
								
								<div class="col-md-4">
									<div class="property-item text-center">
										<?php 
											if(!empty($data->img_show)) {
												echo '<div class="listing-image" style="background-image:url(https://network4rentals.com/network/'.ltrim($data->img_show, '../').')"></div>';
											} else {
												echo '<div class="listing-image" style="background-image:url(https://network4rentals.com/network/listing-images/comingSoon.jpg)"></div>';
												
											}
										?>
										
										
										<h3><?php echo $data->title; ?></h3>
										<p><?php echo $data->address.', '.$data->city.' '.$data->stateAbv;?></p>
										<div class="row">
											<div class="col-xs-6">	
												<p>Beds: <?php echo $data->bedrooms; ?></p>
											</div>
											<div class="col-xs-6">
												<p>Baths: <?php echo $data->bathrooms; ?></p>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6">	
												<button type="button" class="btn btn-primary dropdown-toggle btn-block" data-toggle="dropdown">
														Options <span class="caret"></span>
												</button>
												<ul class="dropdown-menu" role="menu">
													<li>	
														<a href="<?php echo base_url('landlords/edit-listing/'.$data->id); ?>"><i class="fa fa-edit"> Edit Listing</i></a>
													</li>
													<li>
														<a href="<?php echo base_url('landlords/add-service-request'); ?>"><i class="fa fa-plus-square-o"></i> Add Service Request</a>
													</li>
													<li>
														<a href="" class="viewTheseRequest" data-id="<?php echo $data->id; ?>"><i class="fa fa-wrench"></i> View Service Requests</a>
													</li>
													<li>
														<a href="<?php echo base_url('landlords/property-items/'.$data->id); ?>"><i class="fa fa-plus"></i> Add/Edit/View Items</a>
													</li>
													<?php
														if($data->active == 'y') {
															echo '<li><a href="https://network4rentals.com/network/listings/view-listing/'.$data->id.'" target="_blank"><i class="fa fa-link"></i> View Listing</a></li>';
														}
													?>
													<li class="divider"></li>
													<li>
														<a href="" data-toggle="modal" data-target="#deleteListing" class="deleteListing" data-listingid="<?php echo $data->id; ?>"><i class="fa fa-times"></i> Delete Listing</a>
													</li>
												</ul>
											</div>
											<div class="col-xs-6">
												<?php
													if($data->active != 'y') {
														echo '<button class="btn btn-default btn-block toogleAd" data-state="1" data-id="'.$data->id.'">Inactive</button>';
													} else {
														echo '<button class="btn btn-success btn-block toogleAd" data-state="2" data-id="'.$data->id.'">Active</button>';
													}
												?>
												
											</div>
										</div>
									</div>
								</div>
								
							
							<?php
				
							$i++;
							if ($i%3 == 0) {
								echo '</div><div class="row">';
							
							}
						}
					} else {
						echo '<p>You have no property listings added yet. If you would like to add a property click the button below. Once you create one it will show up here and you can manage this listing here.</p><a href="'.base_url().'landlords/add-listing" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Add Listing</a>';
					}
				?>
			</div>
		</div>
		
		<div id="searchedData">
			
		</div>
	</div>
</div>



<div id="pagination" class="text-center fade in">
	<?php echo $links; ?>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteListing" tabindex="-1" role="dialog" aria-labelledby="deleteListing" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-danger"></i> Are You Sure You Want To Delete This Listing</h4>
      </div>
      <div class="modal-body">
		<p>Once you delete this listing there is no going back. This will also delete any service request you have added to this property manually. This will not effect any service request that one of your tenants has added.</p>
      </div>
      <div class="modal-footer">
		<?php echo form_open('landlords/delete-listing'); ?>
			<input type="hidden" name="delete_id" class="hiddenId">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-danger">I Am Sure</button>
		<?php echo form_close(); ?>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addExpense" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open_multipart('landlords/add-property-expense', array('id'=>'addExpenseItem')); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"><i class="fa fa-dollar"></i> Add Expense</h4>
				</div>
				<div class="modal-body">
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group has-error">
									<label for="expenseType"><span class="text-red">*</span> Type Of Expense</label>
									<select id="expenseType" class="form-control" name="expense_type">
										<?php
											$types = array(
												'0'	=> 'Select One',
												'1' => 'Utility',
												'2' => 'General',
												'3' => 'Repair',
												'4' => 'Capital Improvement'
											);  
											foreach($types as $key => $val) {
												if($key == $_POST['expense_type']) {
													echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
												} else {
													echo '<option value="'.$key.'">'.$val.'</option>';
												}
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="expense_cost"><span class="text-red">*</span> Cost</label>
									<input type="text" class="form-control price" id="expense_cost" name="cost">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-8">
								<div class="form-group">
									<label for="expense_file">Upload File</label>
									<input type="file" name="file" id="expense_file" class="form-control" placeholder="mm/dd/yyyy">
									<p id="fileUploadError" class="help-block">Upload a copy of the reciet or picture of the expense. <small>jpg, png, gif, pdf</small></p>
								</div>
							</div>
							<div class="col-sm-4">
								<div class="form-group">
									<label for="expense_date"><span class="text-red">*</span> Date</label>
									<input type="text" id="expense_date" name="date" class="form-control date">
								</div>
							</div>
						</div>
						<input type="hidden" name="property_id" id="property_id">
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" id="submitExpense" class="btn btn-primary"><i class="fa fa-plus"></i> Add</button>
				</div>
			</div>
		</form>
	</div>
</div>