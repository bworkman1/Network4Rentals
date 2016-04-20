
	<?php
		if(!empty($error)) {
			echo '<div class="alert alert-danger"><b>Error:</b> '.$error.'</div>';
		} 
		$success = $this->session->flashdata('success');
		if(!empty($success)) {
			echo '<div class="alert alert-success"><b>Success:</b> '.$success.'</div>';
		}
	?>
	<button type="button" class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#video">
			Watch Video
		</button>
<h2><i class="fa fa-plus text-primary"></i> Edit Property</h2>
<hr>	
<p>Below is the form to add a listing for people to find your rental property. All fields are required except the image which is optional. Once you add a property you will be directed to the listing manager page where you can manage all your listings you have added to N4R.</p>
<div class="spacing15"></div> 
<hr>	 
<?php echo form_open('landlords/edit-listing/'.$this->uri->segment(3), array('id'=>'add-listing')); ?>
	<p class="small italics"><span class="text-danger">* </span> Required Fields</p>					
	<div class="row">
		<div class="col-md-7">
			<div class="form-group">
				<label><span class="text-danger">* </span>Title:</label>
				<input type="text" name="title" title="This is what visitors will see before they view your listing. Be creative to increase click through by visitors" class="form-control" maxlength="70" value="<?php echo $details['title']; ?>" required>
			</div>
			<div class="form-group">
				<label><span class="text-danger">* </span>Street Address:</label>
				<input type="text" name="address" class="form-control listing-address" maxlength="70" value="<?php echo $details['address']; ?>" required>
			</div>
			<div class="row">
				<div class="col-lg-5">
					<div class="form-group">
						<label>City:</label>
						<input type="text" name="city" class="listing-city form-control" value="<?php echo $details['city']; ?>" required>
					</div>
				</div>
				<div class="col-lg-4">
					<div class="form-group">
						<label><span class="text-danger">* </span>State:</label>
						<select name="stateAbv" class="listing-state form-control" required>
							<option value="">Select One...</option>
							<?php
								$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
			
								foreach($states as $key => $val) {
									if($details['stateAbv'] == $key) {
										echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
									} else {
										echo '<option value="'.$key.'">'.$val.'</option>';
									}
								}

							?>
						</select>
					</div>
				</div>
				<div class="col-lg-3">
					<div class="form-group">
						<label><span class="text-danger">* </span>Zip:</label>
						<input type="text" name="zipCode" class="form-control" maxlength="5" value="<?php echo $details['zipCode']; ?>" required>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
						<label><span class="text-danger">* </span>Beds:</label>
						<select name="bedrooms" class="form-control" required>
							<option value="">...</option>
							<?php
								for($i=0;$i<10;$i++) {
									if($i == $details['bedrooms']) {
										echo '<option selected>'.$i.'</option>';
									} else {
										echo '<option>'.$i.'</option>';
									}
								}
							?>
							
						</select>
					</div>
				</div>
				<div class="col-xs-6">
					<div class="form-group">
						<label><span class="text-danger">* </span>Baths:</label>
						<select name="bathrooms" class="form-control" required>
							<option value="">...</option>
							<?php
								$bath_options = array(1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5);
								foreach($bath_options as $val) {
									if($val ==  $details['bathrooms']) {
										echo '<option selected>'.$val.'</option>';
									} else {
										echo '<option>'.$val.'</option>';
									}
								}
							?>
							
						</select>
					</div>
				</div>
			</div>
			<div class="clear"></div>							
			<div class="row">								
				<div class="col-sm-4">		
					<div class="form-group">
						<label><span class="text-danger">* </span>Rent:</label>									
						<input type="text" name="price" class="form-control" maxlength="4" title="Rent on a monthly basis" value="<?php echo $details['price']; ?>" required>
					</div>		
				</div>								
				<div class="col-sm-4">									
					<label><span class="text-danger">* </span>Deposit:</label>		
					<input type="text" name="deposit" class="form-control" maxlength="5" value="<?php echo $details['deposit']; ?>" required>	
				</div>								
					<div class="col-sm-4">									
						<label>Sq. Feet:</label>									
						<input type="text" class="form-control" name="sqFeet" value="<?php echo $details['sqFeet']; ?>">								
					</div>							
				</div>
		</div>
		<div class="col-md-5">
			<label title=""><span class="text-danger">* </span>Description:</label>
			<textarea maxlength="400" title="Leave a great description about your property" style="height: 280px" name="details" class="form-control add_listing_textarea" required="required"><?php echo $details['details']; ?></textarea>
		</div>		
	</div><!-- Row Ends -->	
	
	<div class="spacing15"></div>
	<hr>
	<h3><i class="fa fa-reorder"></i> Amenities</h3>
	<div class="row">
		<div class="col-sm-6">
			<div class="checkbox">
				<label for="amenities-3"><input type="checkbox" <?php if($details['laundry_hook_ups']=='y'){echo 'checked';} ?> name="laundry_hook_ups" id="amenities-3" value="y" /> Clothes Washer / Dryer Hook-Ups</label>
			</div>
			<div class="checkbox">
				<label for="amenities-5"><input type="checkbox" <?php if($details['off_site_laundry']=='y'){echo 'checked';} ?> name="off_site_laundry" id="amenities-5" value="y" /> Offsite Laundry</label>
			</div>
			<div class="checkbox">
				<label for="amenities-6"><input type="checkbox" <?php if($details['on_site_laundry']=='y'){echo 'checked';} ?> name="on_site_laundry" id="amenities-6" value="y" /> Onsite Laundry</label>
			</div>
			<div class="checkbox">
				<label for="amenities-7"><input type="checkbox" <?php if($details['basement']=='y'){echo 'checked';} ?> name="basement" id="amenities-7" value="y" /> Basement</label>
			</div>
			<div class="checkbox">
				<label for="amenities-8"><input type="checkbox" <?php if($details['single_lvl']=='y'){echo 'checked';} ?> name="single_lvl" id="amenities-8" value="y" /> Single Level Floor Plan</label>
			</div>
			<div class="checkbox">
				<label for="amenities-9"><input type="checkbox" <?php if($details['shed']=='y'){echo 'checked';} ?> name="shed" id="amenities-9" value="y" /> Storage Shed</label>
			</div>
			<div class="checkbox">
				<label for="amenities-10"><input type="checkbox" <?php if($details['park']=='y'){echo 'checked';} ?> name="park" id="amenities-10" value="y" /> Near A Park</label>
			</div>	
			<div class="checkbox">
				<label for="amenities-12"><input type="checkbox" <?php if($details['inside_city']=='y'){echo 'checked';} ?> name="inside_city" id="amenities-12" value="y" /> Within City Limits</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-13"><input type="checkbox" <?php if($details['outside_city']=='y'){echo 'checked';} ?> name="outside_city" id="amenities-13" value="y" /> Outside City Limits</label>
			</div>		
		</div>
		<div class="col-sm-6">
			<div class="checkbox">
				<label for="amenities-14"><input type="checkbox" <?php if($details['deck_porch']=='y'){echo 'checked';} ?> name="deck_porch" id="amenities-14" value="y" /> Deck / Porch</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-15"><input type="checkbox" <?php if($details['large_yard']=='y'){echo 'checked';} ?> name="large_yard" id="amenities-15" value="y" /> Large Yard</label>
			</div>
			<div class="checkbox">
				<label for="amenities-16"><input type="checkbox" <?php if($details['fenced_yard']=='y'){echo 'checked';} ?> name="fenced_yard" id="amenities-16" value="y" /> Fenced Yard</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-17"><input type="checkbox" <?php if($details['partial_utilites']=='y'){echo 'checked';} ?> name="partial_utilites" id="amenities-17" value="y" /> Some Utilities Included</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-18"><input type="checkbox" <?php if($details['all_utilities']=='y'){echo 'checked';} ?> name="all_utilities" id="amenities-18" value="y" /> Utilities Included</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-19"><input type="checkbox" <?php if($details['appliances']=='y'){echo 'checked';} ?> name="appliances" id="amenities-19" value="y" /> Appliances Included</label>
			</div>		
			<div class="checkbox">
				<label for="amenities-20"><input type="checkbox" <?php if($details['furnished']=='y'){echo 'checked';} ?> name="furnished" id="amenities-20" value="y" /> Fully Furnished </label>
			</div>		
			<div class="checkbox">
				<label for="amenities-21"><input type="checkbox" <?php if($details['pool']=='y'){echo 'checked';} ?> name="pool" id="amenities-21" value="y" /> Pool</label>
			</div>
			<div class="checkbox">
				<label for="amenities-11"><input type="checkbox" <?php if($details['shopping']=='y'){echo 'checked';} ?> name="shopping" id="amenities-11" value="y" /> Near Shopping / Entertainment</label>
			</div>
			
		</div>	
	</div>
	<?php 
		$user_id = $this->session->userdata('user_id');
		if($user_id == 199  || $user_id == 156) { ?>
		<hr>
		<div class="well well-sm">
			<h4>Overwrite Contact Info</h4>
			<div class="row">								
				<div class="col-sm-4">		
					<div class="form-group">
						<label>Contact Name:</label>									
						<input type="text" name="contact_name" class="form-control" maxlength="50" title="" value="<?php echo $details['contact_name']; ?>">
					</div>		
				</div>								
				<div class="col-sm-3">		
					<div class="form-group">
						<label>Contact Phone:</label>		
						<input type="text" name="contact_phone" class="form-control" maxlength="16" value="<?php echo $details['contact_phone']; ?>">	
					</div>
				</div>								
				<div class="col-sm-3">		
					<div class="form-group">
						<label>Contact Email:</label>									
						<input type="email" class="form-control" name="contact_email" maxlength="50" value="<?php echo $details['contact_email']; ?>">
					</div>					
				</div>
				<div class="col-sm-2">			
					<div class="form-group">
						<label>Owner Id:</label>									
						<input type="text" class="form-control" name="owner" maxlength="12" value="<?php echo $this->session->userdata('user_id'); ?>">								
					</div>
				</div>					
			</div>
		</div>
	<?php } ?>
	<hr>
	<div class="spacing15"></div> 
	<div class="show-map" style="display: none">
		<div class="row">
			<div class="col-sm-4">
				<h3>Is This Correct? </h3>
			</div>
			<div class="col-sm-2">
				<label><h3>Yes: <input type="checkbox" value='y' name="map_correct" checked></h3></label>
			</div>	
		</div>
		<div class="row">	
			<div class="col-sm-6">
				<div id="map_canvas"></div>
			</div>
			<div class="col-sm-6">
				<div id="pano"></div>
			</div>
		</div>
	</div>
		
	<h3><i class="fa fa-picture-o"></i> Images</h3>	
	<p>To add images click the add image button below. Once you select your image it will appear in the preview box. You can upload up to five images on a listing.</p>
	<hr>
	<div class="row">
		<div class="col-md-2 col-xs-6">
			<?php if(!empty($details['image1'])&&!empty($details['image2'])&&!empty($details['image3'])&&!empty($details['image4'])&&!empty($details['image5'])) {$hide = 'fade';} ?>
					
			<span class="btn btn-info btn-block btn-lg <?php echo $hide; ?>" style="min-height: 125px" id="imageBtn"><br><i class="fa fa-picture-o fa-4x"></i><br> Add Image<br><br></span>
		</div>
		<div class="col-md-2 col-xs-6">
			<div id="imgPreview1" class="preview">
				<?php
					if(!empty($details['image1'])) {
						if(file_exists('../'.$details['image1'])) {
							echo '<img src="../'.$details['image1'].'" class="img-responsive" alt="Image 1">';
						} else {
							echo '<img src="'.base_url().'listing-images/'.$details['image1'].'" class="img-responsive">';
						}
						
						if($details['featured_image'] == 1) {
							$feat1 = 'checked';
						}
						echo '<div class="checkbox"><label><input type="radio" '.$feat1.' name="featured_image" value="1"> Featured?</label></div>';
					}
				?>
			</div>
			<div class="spacing15"></div>
		</div>
		<div class="col-md-2 col-xs-6">
			<div id="imgPreview2" class="preview">
				<?php
					if(!empty($details['image2'])) {
						if(file_exists('../'.$details['image2'])) {
							echo '<img src="../'.$details['image2'].'" class="img-responsive" alt="Image 2">';
						} else {
							echo '<img src="'.base_url().'listing-images/'.$details['image2'].'" class="img-responsive">';
						}
						if($details['featured_image'] == 2) {
							$feat2 = 'checked';
						}
						echo '<div class="checkbox"><label><input type="radio" '.$feat2.' name="featured_image" value="2"> Featured?</label></div>';
					}
				?>
			</div>
			<div class="spacing15"></div>
		</div>
		<div class="col-md-2 col-xs-6">
			<div id="imgPreview3" class="preview">
				<?php
					if(!empty($details['image3'])) {
						if(file_exists('../'.$details['image3'])) {
							echo '<img src="../'.$details['image3'].'" class="img-responsive" alt="Image 3">';
						} else {
							echo '<img src="'.base_url().'listing-images/'.$listing['image3'].'" class="img-responsive">';
						}
						if($details['featured_image'] == 3) {
							$feat3 = 'checked';
						}
						echo '<div class="checkbox"><label><input type="radio" '.$feat3.' name="featured_image" value="3"> Featured?</label></div>';
					}
				?>
			</div>
			<div class="spacing15"></div>
		</div>

		<div class="col-md-2 col-xs-6">
			<div id="imgPreview4" class="preview">
				<?php
					if(!empty($details['image4'])) {
						if(file_exists('../'.$details['image4'])) {
							echo '<img src="../'.$details['image4'].'" class="img-responsive" alt="Image 4">';
						} else {
							echo '<img src="'.base_url().'listing-images/'.$details['image4'].'" class="img-responsive">';
						}
						if($details['featured_image'] == 4) {
							$feat4 = 'checked';
						}
						echo '<div class="checkbox"><label><input type="radio" '.$feat4.' name="featured_image" value="4"> Featured?</label></div>';
					}
				?>
			</div>
			<div class="spacing15"></div>
		</div>
		<div class="col-md-2 col-xs-6">
			<div id="imgPreview5" class="preview">
				<?php
					
					if(!empty($details['image5'])) {
						if(file_exists('../'.$details['image5'])) {
							echo '<img src="../'.$details['image5'].'" class="img-responsive" alt="Image 5">';
						} else {
							echo '<img src="'.base_url().'listing-images/'.$details['image5'].'" class="img-responsive">';
						}
						if($details['featured_image'] == 5) {
							$feat5 = 'checked';
						}
						echo '<div class="checkbox"><label><input type="radio" '.$feat5.' name="featured_image" value="5"> Featured?</label></div>';
					}
				?>
			</div>
			<div class="spacing15"></div>
		</div>		
	</div>

	<input type="hidden" class="imgInputName" value="<?php echo $details['image1']; ?>" name="image1">
	<input type="hidden" class="imgInputName" value="<?php echo $details['image2']; ?>" name="image2">
	<input type="hidden" class="imgInputName" value="<?php echo $details['image3']; ?>" name="image3">
	<input type="hidden" class="imgInputName" value="<?php echo $details['image4']; ?>" name="image4">
	<input type="hidden" class="imgInputName" value="<?php echo $details['image5']; ?>" name="image5">
	
	<hr>	
	<button type="submit" name="add-listings" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Save Listing</button>
	
</form>			

<form enctype="multipart/form-data" style="display: none">
  <input type="file" id="image" name="img">
</form>

<div class="modal fade" id="video" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Adding/Editing Property</h4>
      </div>
      <div class="modal-body">
       <div class="embed-responsive embed-responsive-16by9"><iframe width="560" height="315" src="https://www.youtube.com/embed/xyO_qcBRNUE" frameborder="0" allowfullscreen></iframe></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
