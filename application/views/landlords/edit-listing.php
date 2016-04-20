<h2><i class="fa fa-edit text-primary"></i> Edit Listing</h2>
<hr>

<div class="row">
	<div class="col-md-8">		
		<p>Below is the form to edit a listing you previously added.</p>
	</div>
	<div class="col-md-4 text-right">
		<?php 	
			$this->db->where('sub_admins', $this->session->userdata('user_id'));
			$this->db->or_where('main_admin_id', $this->session->userdata('user_id'));
			$query = $this->db->get('admin_groups');
			if ($query->num_rows() > 0) {
				$switches = $query->result_array();
			} else {
				$switches = '';
			}
			$this->db->select('bName, name');
			$query = $this->db->get_where('landlords', array('id'=>$this->session->userdata('user_id')));
			if($query->num_rows()>0) {
				$row = $query->row();
				$select_bname = $row->bName;
				$select_name = $row->name;
			}

			if(!empty($switches)) {
				if($listing['owner'] == $this->session->userdata('user_id')) {
					echo form_open('landlords/reassign-listing/');
						echo '<label><b>Re-assign To A Manager:</b></label>';
						
						echo '<select class="form-control groupPicker reassignListing" name="group_id" onchange="this.form.submit()">';
							echo '<option value="0">I manage this property</option>';
							for($i=0;$i<count($switches);$i++) {
								if($listing['contact_id'] == $switches[$i]['sub_admins']) {
									echo '<option value="'.$switches[$i]['id'].'" selected="selected">'.$switches[$i]['sub_b_name'].'</option>';
								} else {
									echo '<option value="'.$switches[$i]['id'].'">'.$switches[$i]['sub_b_name'].'</option>';
								}
							}
						echo '</select>';
						echo '<input type="hidden" value="'.$this->uri->segment(3).'" name="listing_id">';
					echo '</form>';
				}
			}
		?>
	</div>
</div>
<?php
	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
	if(!empty($errors)) {
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$errors.'</div>';
	}
?>
<div class="spacing15"></div>
<hr>	
<div class="row">			
	<div class="col-lg-12"> 
		<?php echo form_open_multipart('landlords/edit-listing/'.$listing['id']); ?>
			<p class="small italics"><span class="text-danger">* </span> Required Fields</p>					
			<div class="row">
				<div class="col-md-7">
					<label><span class="text-danger">* </span>Title:</label>
					<input type="text" name="title" title="This is what visitors will see before they view your listing. Be creative to increase click through by visitors" class="form-control" maxlength="70" value="<?php echo $listing['title']; ?>" required="required">
					<label><span class="text-danger">* </span>Street Address:</label>
					<input type="text" name="address" class="form-control listing-address" maxlength="70" value="<?php echo $listing['address']; ?>" required="required">
					<div class="row">
						<div class="col-lg-5">
							<label>City:</label>
							<input type="text" name="city" value="<?php echo $listing['city']; ?>" class="listing-city form-control" required="required">
						</div>
						<div class="col-lg-3">
							<label><span class="text-danger">* </span>Zip:</label>
							<input type="text" name="zipCode" class="form-control" maxlength="5" value="<?php echo $listing['zipCode']; ?>" required="required">
						</div>
						<div class="col-lg-4">
							<label><span class="text-danger">* </span>State:</label>
							<select name="stateAbv" class="listing-state form-control" required="required">
								<option value="">Select One...</option>
							<?php
							$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
					
							foreach($states as $key => $val) {
								if($key == $listing['stateAbv']) {
									echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}

							?>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6">
							<label><span class="text-danger">* </span>Beds:</label>
							<select name="bedrooms" class="form-control" required="required">
								<option value="">Select One...</option>
								<?php
									for($i=1; $i<= 10;$i++) {
										if($listing['bedrooms'] == $i) {
											echo '<option selected="selected">'.$i.'</selected>';
										} else {
											echo '<option>'.$i.'</selected>';
										}
									}
								?>
							</select>
						</div>
						<div class="col-xs-6">
							<label><span class="text-danger">* </span>Baths:</label>
							<select name="bathrooms" class="form-control" required="required">
								<option value="">Select One</option>
								<?php
									for($i=1; $i<= 10;$i+= 0.5) {
										if($listing['bathrooms'] == $i) {
											echo '<option selected="selected">'.$i.'</selected>';
										} else {
											echo '<option>'.$i.'</selected>';
										}
									}
								?>
							</select>
						</div>
					</div>
					<div class="clear"></div>							
					<div class="row">								
						<div class="col-sm-4">									
							<label><span class="text-danger">* </span>Rent:</label>									
							<input type="text" name="rent" class="form-control numbersOnly" maxlength="7" title="Rent on a monthly basis" value="<?php echo $listing['price']; ?>" required="required">								
						</div>								
						<div class="col-sm-4">									
							<label><span class="text-danger">* </span>Deposit:</label>		
							<input type="text" name="deposit" class="form-control numbersOnly" maxlength="7" value="<?php echo $listing['deposit']; ?>" required="required">	
						</div>								
							<div class="col-sm-4">									
								<label>Sq. Feet:</label>									
								<input type="text" value="<?php echo $listing['sqFeet']; ?>" class="numbersOnly form-control" name="sqFeet" maxlength="7">								
							</div>							
						</div>
				</div>
				<div class="col-md-5">
					<label title=""><span class="text-danger">* </span>Description:</label>
					<textarea maxlength="700" title="Leave a great description about your property" style="height: 280px" name="desc" class="form-control add_listing_textarea" required="required"><?php echo $listing['details']; ?></textarea>
				</div>
				</div><!-- Row Ends -->	
				
				<div class="spacing15"></div>
				<hr>	
				
				
				
				<h3><i class="fa fa-reorder"></i> Amenities</h3>
				<div class="row">
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-3"><input type="checkbox" name="laundry_hook_ups" id="amenities-3" value="y" <?php if($listing['laundry_hook_ups'] == 'y') {echo 'checked';} ?>/> Clothes Washer / Dryer Hook-Ups</label>
						</div>
						<div class="checkbox">
							<label for="amenities-5"><input type="checkbox" name="off_site_laundry" id="amenities-5" value="y" <?php if($listing['off_site_laundry'] == 'y') {echo 'checked';} ?>/> Offsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-6"><input type="checkbox" name="on_site_laundry" id="amenities-6" value="y" <?php if($listing['on_site_laundry'] == 'y') {echo 'checked';} ?>/> Onsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-7"><input type="checkbox" name="basement" id="amenities-7" value="y" <?php if($listing['basement'] == 'y') {echo 'checked';} ?>/> Basement</label>
						</div>
						<div class="checkbox">
							<label for="amenities-8"><input type="checkbox" name="single_lvl" id="amenities-8" value="y" <?php if($listing['single_lvl'] == 'y') {echo 'checked';} ?>/> Single Level Floor Plan</label>
						</div>
						<div class="checkbox">
							<label for="amenities-9"><input type="checkbox" name="shed" id="amenities-9" value="y" <?php if($listing['shed'] == 'y') {echo 'checked';} ?>/> Storage Shed</label>
						</div>
						<div class="checkbox">
							<label for="amenities-10"><input type="checkbox" name="park" id="amenities-10" value="y" <?php if($listing['park'] == 'y') {echo 'checked';} ?>/> Near A Park</label>
						</div>	
						<div class="checkbox">
							<label for="amenities-12"><input type="checkbox" name="inside_city" id="amenities-12" value="y" <?php if($listing['inside_city'] == 'y') {echo 'checked';} ?>/> Within City Limits</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-13"><input type="checkbox" name="outside_city" id="amenities-13" value="y" <?php if($listing['outside_city'] == 'y') {echo 'checked';} ?>/> Outside City Limits</label>
						</div>		
					</div>
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-14"><input type="checkbox" name="deck_porch" id="amenities-14" value="y" <?php if($listing['deck_porch'] == 'y') {echo 'checked';} ?>/> Deck / Porch</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-15"><input type="checkbox" name="large_yard" id="amenities-15" value="y" <?php if($listing['large_yard'] == 'y') {echo 'checked';} ?>/> Large Yard</label>
						</div>
						<div class="checkbox">
							<label for="amenities-16"><input type="checkbox" name="fenced_yard" id="amenities-16" value="y" <?php if($listing['fenced_yard'] == 'y') {echo 'checked';} ?>/> Fenced Yard</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-17"><input type="checkbox" name="partial_utilites" id="amenities-17" value="y" <?php if($listing['partial_utilites'] == 'y') {echo 'checked';} ?>/> Some Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-18"><input type="checkbox" name="all_utilities" id="amenities-18" value="y" <?php if($listing['all_utilities'] == 'y') {echo 'checked';} ?>/> Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-19"><input type="checkbox" name="appliances" id="amenities-19" value="y" <?php if($listing['appliances'] == 'y') {echo 'checked';} ?>/> Appliances Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-20"><input type="checkbox" name="furnished" id="amenities-20" value="y" <?php if($listing['furnished'] == 'y') {echo 'checked';} ?>/> Fully Furnished </label>
						</div>		
						<div class="checkbox">
							<label for="amenities-21"><input type="checkbox" name="pool" id="amenities-21" value="y" <?php if($listing['pool'] == 'y') {echo 'checked';} ?>/> Pool</label>
						</div>
						<div class="checkbox">
							<label for="amenities-11"><input type="checkbox" name="shopping" id="amenities-11" value="y" <?php if($listing['shopping'] == 'y') {echo 'checked';} ?>/> Near Shopping / Entertainment</label>
						</div>
					</div>
				</div>
				<hr>
		
				
				<div class="spacing15"></div> 
				<?php
					if($listing['map_correct'] == 'y') {
						echo '<div id="showing-maps" class="show-map">';
					} else {
						echo '<div class="show-map" style="display: none">';
					}
					
				?>
						<div class="row">
							<div class="col-sm-4">
								<h3>Is This Correct? </h3>
							</div>
							<div class="col-sm-2">
								<label><h3>Yes:
								<?php
									if($listing['map_correct'] == 'y') {
										echo '<input type="checkbox" value="y" name="map_correct" checked>';
									} else {
										echo '<input type="checkbox" value="y" name="map_correct">';
									}
								?>
								</h3></label>
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
				<div class="row">	
					<div class="spacing15"></div>
					<div class="col-lg-12">							
						<div class="spacing15"></div>
						<p><span class="text-danger"><i class="fa fa-asterisk"></i></span> Image sizes are limited to 5mb images. If you experience any issues with uploading images and get an error stating that the image is too large there are many ways you can fix this.<br><br><button class="resize_help btn btn-primary btn-sm"><i class="fa fa-question"></i> Need Help</button></p>
						<div class="row">
							<div class="help well">
								<div class="col-lg-12">
									<h3><i class="fa fa-exclamation"></i> Help, My Image Wont Upload</h3>
								</div>
								<div class="col-md-6">
									<h4>Solution 1:</h4>
									<p>Use and online picture resize tool to resize the images down to an acceptable file size.</p>
									<a href="https://www.google.com/search?q=free+online+picture+resizer&amp;oq=free+online+picture+resizer&amp;aqs=chrome..69i57j0l5.10756j0j7&amp;sourceid=chrome&amp;espv=210&amp;es_sm=93&amp;ie=UTF-8" target="_blank"><i class="fa fa-angle-double-right"></i>  Online Picture Resize Tools</a>
									<h4>Solution 3:</h4>
									<p>If you want to resize them all at once and don't want to have to resize them one at time you can try a batch resize tool that will resize all your images at once.</p>
									<a href="http://birme.net/" target="_blank"><i class="fa fa-angle-double-right"></i> BIRME Image Resize Tool</a>
								</div>
								<div class="col-md-6">
									<h4>Solution 2:</h4>
									<p>If you have an image editor such as photoshop, fireworks, or gimp(free) you can shrink them down and upload them after your finished.</p>
									<h4>Solution 4:</h4>
									<p>If you are using a windows computer most likely you will have paint already installed on your computer and you can use this program to resize your images.</p>
									<a href="http://windows.microsoft.com/en-us/windows7/resize-a-picture-using-paint" target="_blank"><i class="fa fa-angle-double-right"></i>  How to resize with paint</a>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>			
					<div class="clearfix"></div>
					<hr>

					<div class="row">
						<div class="spacing15"></div>
						<div class="col-md-12" center-text'="">
							<div class="col-sm-2">
								<label><a class="toolTips" data-placement="right" title="" data-original-title="This option decides which image shows first in the search results when a user searches listings and only one images is shown">Featured.</a></label>							
							</div>
							<div class="col-sm-5">
								<label>File</label>
							</div>
							<div class="col-sm-5">
								<label>Description <a class="toolTip" title="" data-original-title="This will be displayed under the pictures when a user clicks on the image in listings page"><i class="fa fa-question"></i> </a></label>
							</div>
						</div>	
						<div class="clearfix"></div>
					</div>						
					<div class="row">							
						<div class="spacing15"></div>
						<div class="col-md-12" style="height: 50px">
							<div class="col-sm-1">
							<?php
								if($listing['featured_image'] == '1') {
									echo '<input type="radio" name="featured_image" value="1" checked="checked">';
								} else {
									echo '<input type="radio" name="featured_image" value="1">';
								}
							?>
							</div>
							<div class="col-sm-1 thumbPreview1">
								<?php 
									if(!empty($listing['image1'])) {
										echo '<a href="'.base_url().'listing-images/'.$listing['image1'].'" class="lightbox img-responsive" title="'.$listing['desc_1'].'">
												<img src="'.base_url().'listing-images/'.$listing['image1'].'" class="img-responsive">
											</a>';
									}
								?>
							</div>
							<div class="col-sm-5">
								<input type="file" name="image1" class="form-control listingImageUpload">
							</div>
							<div class="col-sm-5">
								<input type="text" name="desc_1" class="form-control" value="<?php echo $listing['desc_1']; ?>">
							</div>
							<!-- 
							<div class="col-sm-1">
								<a href=""><i class="fa fa-times btn btn-danger text-danger" style="color: #fff"></i></a>
							</div> -->
						</div>	
						<div class="clearfix"></div>						
					</div>						
					<div class="row">							
					<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<?php
								if($listing['featured_image'] == '2') {
									echo '<input type="radio" name="featured_image" value="2" checked="checked">';
								} else {
									echo '<input type="radio" name="featured_image" value="2">';
								}
							?>
						</div>
						<div class="col-sm-1 thumbPreview2">
							<?php 
								if(!empty($listing['image2'])) {
									echo '<a href="'.base_url().'listing-images/'.$listing['image2'].'" class="lightbox img-responsive" title="'.$listing['desc_2'].'">
												<img src="'.base_url().'listing-images/'.$listing['image2'].'" class="img-responsive">
											</a>';
								}
							?>
						</div>
						<div class="col-sm-5">
							<input type="file" name="image2" class="form-control listingImageUpload">
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_2" class="form-control" value="<?php echo $listing['desc_2']; ?>">
						</div>
					</div>							
						<div class="clearfix"></div>	
					</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<?php
								if($listing['featured_image'] == '3') {
									echo '<input type="radio" name="featured_image" value="3" checked="checked">';
								} else {
									echo '<input type="radio" name="featured_image" value="3">';
								}
							?>
						</div>
						<div class="col-sm-1 thumbPreview3">
							<?php 
								if(!empty($listing['image3'])) {
									echo '<a href="'.base_url().'listing-images/'.$listing['image3'].'" class="lightbox img-responsive" title="'.$listing['desc_3'].'">
												<img src="'.base_url().'listing-images/'.$listing['image3'].'" class="img-responsive">
											</a>';
								}
							?>
						</div>
						<div class="col-sm-5">
							<input type="file" name="image3" class="form-control listingImageUpload">
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_3" class="form-control" value="<?php echo $listing['desc_3']; ?>">
						</div>
					</div>							
					<div class="clearfix"></div>	
					
					</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<?php
								if($listing['featured_image'] == '4') {
									echo '<input type="radio" name="featured_image" value="4" checked="checked">';
								} else {
									echo '<input type="radio" name="featured_image" value="4">';
								}
							?>
						</div>
						<div class="col-sm-1 thumbPreview4">
							<?php 
								if(!empty($listing['image4'])) {
									echo '<a href="'.base_url().'listing-images/'.$listing['image4'].'" class="lightbox img-responsive" title="'.$listing['desc_4'].'">
												<img src="'.base_url().'listing-images/'.$listing['image4'].'" class="img-responsive">
											</a>';
								}
							?>
						</div>
						<div class="col-sm-5">
							<input type="file" name="image4" class="form-control listingImageUpload">
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_4" class="form-control" value="<?php echo $listing['desc_4']; ?>">
						</div>
					</div>							<div class="clearfix"></div>	</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<?php
								if($listing['featured_image'] == '5') {
									echo '<input type="radio" name="featured_image" value="5" checked="checked">';
								} else {
									echo '<input type="radio" name="featured_image" value="5">';
								}
							?>
						</div>
						<div class="col-sm-1 thumbPreview5">
							<?php 
								if(!empty($listing['image5'])) {
									echo '<a href="'.base_url().'listing-images/'.$listing['image5'].'" class="lightbox img-responsive" title="'.$listing['desc_5'].'">
												<img src="'.base_url().'listing-images/'.$listing['image5'].'" class="img-responsive">
											</a>';
								}
							?>
						</div>
						<div class="col-sm-5">
							<input type="file" name="image5" class="form-control listingImageUpload">
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_5" class="form-control" value="<?php echo $listing['desc_5']; ?>">
							<input type="hidden" name="MAX_FILE_SIZE" value="7024000">  
						</div>
					</div>						<div class="clearfix"></div>	</div>						<div class="spacing15"></div>
				<hr>						<div class="spacing15"></div>
				<div id="imageWarning"></div>
				<input type="hidden" name="id" value="<?php echo (int)$this->uri->segment(3); ?>">
				<button type="submit" class="btn btn-primary">Save Changes</button>
			</div>
		</form>			
	</div>
</div>
</div>

<div class="modal fade" id="mapShow" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">Is This The Correct Address?</h4>
      </div>
      <div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div id="map_canvas"></div>
			</div>
			<div class="col-sm-6">
				<div id="pano"></div>
			</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
	