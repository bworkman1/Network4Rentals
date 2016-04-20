<div class="row">
	<div class="col-md-6">
		<h2><i class="fa fa-plus text-primary"></i> Add New Property</h2>
		<hr>
		<p>Below is the form to add a listing for people to find your rental property. All fields are required except the image which is optional. Once you add a property you will be directed to the listing manager page where you can manage all your listings you have added to N4R.</p>
	</div>
    <div class="col-md-6">
		<div class="spacing15"></div>
		<div class="youtube_video">
			<div class="fluid-width-video-wrapper" style="padding-top: 66.66666666666666%;"><iframe width="560" height="315" src="https://www.youtube.com/embed/xyO_qcBRNUE" frameborder="0" allowfullscreen></iframe></div>
		</div>
	</div>
</div>
<?php
	if(!empty($error)) {
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$error.'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
?>
<div class="spacing15"></div>
<hr>	
<div class="row">			
	<div class="col-lg-12"> 
		<?php echo form_open_multipart('landlords/add-listing'); ?>
		<form id="add-listing" method="post" action="/network/landlords/add-listing.php" class="" enctype="multipart/form-data">
			<p class="small italics"><span class="text-danger">* </span> Required Fields</p>					
			<div class="row">
				<div class="col-md-7">
					<label><span class="text-danger">* </span>Title:</label>
					<input type="text" name="title" title="This is what visitors will see before they view your listing. Be creative to increase click through by visitors" class="form-control" maxlength="70" value="<?php echo $_POST['title']; ?>" required>
					<label><span class="text-danger">* </span>Street Address:</label>
					<input type="text" name="address" class="form-control listing-address" maxlength="70" value="<?php echo $_POST['address']; ?>" required>
					<div class="row">
						<div class="col-lg-5">
							<label>City:</label>
							<input type="text" name="city" class="listing-city form-control" value="<?php echo $_POST['city']; ?>" required>
						</div>
						<div class="col-lg-3">
							<label><span class="text-danger">* </span>Zip:</label>
							<input type="text" name="zip" class="form-control" maxlength="5" value="<?php echo $_POST['zip']; ?>" required>
						</div>
						<div class="col-lg-4">
							<label><span class="text-danger">* </span>State:</label>
							<select name="state" class="listing-state form-control" required>
								<option value="">Select One...</option>
							<?php
							$states = array('AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
					
							foreach($states as $key => $val) {
								if($_POST['state'] == $val) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
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
							<select name="beds" class="form-control" required>
								<option value="">...</option>
								<option>0</option>
								<option>1</option>
								<option>2</option>
								<option>3</option>
								<option>4</option>
								<option>5</option>
								<option>6</option>
								<option>7</option>
								<option>8</option>
								<option>9</option>
							</select>
						</div>
						<div class="col-xs-6">
							<label><span class="text-danger">* </span>Baths:</label>
							<select name="baths" class="form-control" required>
								<option value="">...</option>
								<option>0</option>
								<option>1</option>
								<option>1.5</option>
								<option>2</option>
								<option>2.5</option>
								<option>3</option>
								<option>3.5</option>
								<option>4</option>
								<option>5</option>
								<option>6</option>
								<option>7</option>
								<option>8</option>
								<option>9</option>
							</select>
						</div>
					</div>
					<div class="clear"></div>							
					<div class="row">								
						<div class="col-sm-4">									
							<label><span class="text-danger">* </span>Rent:</label>									
							<input type="text" name="rent" class="form-control" maxlength="4" title="Rent on a monthly basis" value="<?php echo $_POST['rent']; ?>" required>								
						</div>								
						<div class="col-sm-4">									
							<label><span class="text-danger">* </span>Deposit:</label>		
							<input type="text" name="deposit" class="form-control" maxlength="5" value="<?php echo $_POST['deposit']; ?>" required>	
						</div>								
							<div class="col-sm-4">									
								<label>Sq. Feet:</label>									
								<input type="text" class="form-control" name="sqFeet" value="<?php echo $_POST['sqFeet']; ?>">								
							</div>							
						</div>
				</div>
				<div class="col-md-5">
					<label title=""><span class="text-danger">* </span>Description:</label>
					<textarea maxlength="400" title="Leave a great description about your property" style="height: 280px" name="desc" class="form-control add_listing_textarea" required="required"><?php echo $_POST['desc']; ?></textarea>
				</div>		
				</div><!-- Row Ends -->	
				<div class="spacing15"></div>
				<hr>
				<h3><i class="fa fa-reorder"></i> Amenities</h3>
				<div class="row">
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-3"><input type="checkbox" name="laundry_hook_ups" id="amenities-3" value="y" /> Clothes Washer / Dryer Hook-Ups</label>
						</div>
						<div class="checkbox">
							<label for="amenities-5"><input type="checkbox" name="off_site_laundry" id="amenities-5" value="y" /> Offsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-6"><input type="checkbox" name="on_site_laundry" id="amenities-6" value="y" /> Onsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-7"><input type="checkbox" name="basement" id="amenities-7" value="y" /> Basement</label>
						</div>
						<div class="checkbox">
							<label for="amenities-8"><input type="checkbox" name="single_lvl" id="amenities-8" value="y" /> Single Level Floor Plan</label>
						</div>
						<div class="checkbox">
							<label for="amenities-9"><input type="checkbox" name="shed" id="amenities-9" value="y" /> Storage Shed</label>
						</div>
						<div class="checkbox">
							<label for="amenities-10"><input type="checkbox" name="park" id="amenities-10" value="y" /> Near A Park</label>
						</div>	
						<div class="checkbox">
							<label for="amenities-12"><input type="checkbox" name="city" id="amenities-12" value="y" /> Within City Limits</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-13"><input type="checkbox" name="outside_city" id="amenities-13" value="y" /> Outside City Limits</label>
						</div>		
					</div>
					<div class="col-sm-6">
						<div class="checkbox">
							<label for="amenities-14"><input type="checkbox" name="deck_porch" id="amenities-14" value="y" /> Deck / Porch</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-15"><input type="checkbox" name="large_yard" id="amenities-15" value="y" /> Large Yard</label>
						</div>
						<div class="checkbox">
							<label for="amenities-16"><input type="checkbox" name="fenced_yard" id="amenities-16" value="y" /> Fenced Yard</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-17"><input type="checkbox" name="partial_utilites" id="amenities-17" value="y" /> Some Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-18"><input type="checkbox" name="all_utilities" id="amenities-18" value="y" /> Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-19"><input type="checkbox" name="appliances" id="amenities-19" value="y" /> Appliances Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-20"><input type="checkbox" name="furnished" id="amenities-20" value="y" /> Fully Furnished </label>
						</div>		
						<div class="checkbox">
							<label for="amenities-21"><input type="checkbox" name="pool" id="amenities-21" value="y" /> Pool</label>
						</div>
						<div class="checkbox">
							<label for="amenities-11"><input type="checkbox" name="shopping" id="amenities-11" value="y" /> Near Shopping / Entertainment</label>
						</div>
					</div>
				</div>
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
								<div class="clear"></div>
							</div>
						</div>
					</div>			
					<hr>
					<div class="row">
						<hr>
						<div class="spacing15"></div>
						<div class="col-md-12" center-text'="">
							<div class="col-sm-3">
								<label><a class="toolTips" data-placement="right" title="" data-original-title="This option decides which image shows first in the search results when a user searches listings and only one images is shown">Featured.</a></label>							
							</div>
							<div class="col-sm-5">
								<label>File</label>
							</div>
							<div class="col-sm-4">
								<label>Description <a class="toolTip" title="" data-original-title="This will be displayed under the pictures when a user clicks on the image in listings page"><i class="fa fa-question"></i> </a></label>
							</div>
						</div>	
						<div class="clearfix"></div>
					</div>						
					<div class="row">							
						<div class="spacing15"></div>
						<div class="col-md-12" style="height: 50px">
							<div class="col-sm-1">
								<input type="radio" name="featured_image" value="1" checked="checked">
							</div>
							<div class="col-sm-1 thumbPreview1">
								
							</div>
							<div class="col-sm-5">
								<?php echo form_upload(array('name'=>'file1', 'class'=>'form-control')); ?>
								<!--<input id="file1" type="file" onchange="readURL1(this);" name="files[]" class="form-control img-attachment" accept="image/*">-->
							</div>
							<div class="col-sm-5">
								<input type="text" name="desc_1" class="form-control" value="<?Php echo $_POST['desc_1']; ?>">
							</div>
						</div>	
						<div class="clearfix"></div>						
					</div>						
					<div class="row">							
					<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<input type="radio" name="featured_image" value="2">
						</div>
						<div class="col-sm-1 thumbPreview2">
							
						</div>
						<div class="col-sm-5">
							<?php echo form_upload(array('name'=>'file2','class'=>'form-control')); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_2" class="form-control" value="<?Php echo $_POST['desc_2']; ?>">
						</div>
					</div>							
						<div class="clearfix"></div>	
					</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<input type="radio" name="featured_image" value="3">
						</div>
						<div class="col-sm-1 thumbPreview3">
							
						</div>
						<div class="col-sm-5">
							<!-- <input type="file" id="file3" onchange="readURL3(this);" name="files[]" class="form-control img-attachment" accept="image/*"> -->
							<?php echo form_upload(array('name'=>'file3','class'=>'form-control')); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_3" class="form-control" value="<?Php echo $_POST['desc_3']; ?>">
						</div>
					</div>							
					<div class="clearfix"></div>	
					
					</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<input type="radio" name="featured_image" value="4">
						</div>
						<div class="col-sm-1 thumbPreview4">
							
						</div>
						<div class="col-sm-5">
							<!-- <input type="file" id="file4" onchange="readURL4(this);" name="files[]" class="form-control img-attachment" accept="image/*"> -->
							<?php echo form_upload(array('name'=>'file4', 'class'=>'form-control')); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_4" class="form-control" value="<?Php echo $_POST['desc_4']; ?>">
						</div>
					</div>							<div class="clearfix"></div>	</div>						<div class="row">							<div class="spacing15"></div>
					<div class="col-md-12" style="height: 50px">
						<div class="col-sm-1">
							<input type="radio" name="featured_image" value="5"> 
						</div>
						<div class="col-sm-1 thumbPreview5">
							
						</div>
						<div class="col-sm-5">
							<!-- <input type="file" id="file5" name="files[]" onchange="readURL5(this);" class="form-control img-attachment"  accept="image/*"> -->
							<?php echo form_upload(array('name'=>'file5', 'class'=>'form-control')); ?>
						</div>
						<div class="col-sm-5">
							<input type="text" name="desc_5" class="form-control" value="<?Php echo $_POST['desc_5']; ?>">
							<input type="hidden" name="MAX_FILE_SIZE" value="7024000">  
						</div>
					</div>						<div class="clearfix"></div>	</div>						<div class="spacing15"></div>
				<hr>						<div class="spacing15"></div>
				<input type="submit" name="add-listings" value="Create Listing" class="btn btn-primary">
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
	