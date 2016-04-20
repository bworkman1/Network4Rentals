
<?php 
	$success = $this->session->flashdata('success');
	$err = $this->session->flashdata('error');
	if(!empty($success)) {
		echo '<div class="alert alert-success"><i class="fa fa-check fa-3x pull-left"></i> <b>Success:</b><br>'.$success.'</div>';
	}
	if(!empty($errors)) {
		echo '<div class="alert alert-danger"><i class="fa fa-times fa-3x pull-left"></i> <b>Error:</b><br>'.$errors.'</div>';
	}
	if(!empty($err)) {
		echo '<div class="alert alert-danger"><i class="fa fa-times fa-3x pull-left"></i> <b>Error:</b><br>'.$err.'</div>';
	}

	if(!empty($settings->unique_name)) {
		echo '<a href="http://n4rlocal.com/'.$settings->unique_name.'/" class="btn btn-primary pull-right" target="_blank">View Public Page</a><div class="clearfix"></div><br>';
	}
	
?>	

<?php echo form_open_multipart('landlord-associations/public-page'); ?>

<div class="row">
	<div class="col-sm-5">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"></i> Association Logo</h3>
			</div>
			<div class="panel-body">
				<?php
					if(empty($settings->image)) {
						$settings->image = base_url().'public-images/default-assoc-logo.jpg';
					} else {
						$settings->image = base_url().'public-images/'.$settings->image;
					}
					echo '<img src="'.$settings->image.'" alt="" class="thumbPreview img-responsive img-center">';
				?>
			</div>
		</div>
	</div>
	<div class="col-sm-7">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"></i> Association Information</h3>
			</div>
			<div class="panel-body">
				<label><span class="text-danger">*</span> <b>Association Name:</b></label>
				<input type="text" name="bName" value="<?php echo $settings->bName; ?>" class="form-control" placeholder="Business Name" required="required">
				<label><span class="text-danger">*</span> <b>Description:</b></label>
				<textarea class="form-control" style="height: 175px" maxlength="500" name="desc" required="required"><?php echo $settings->desc; ?></textarea>
			</div>
		</div>
		
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"></i> Need a Domain Name?</h3>
			</div>
			<div class="panel-body">
				<p>If you would like to have a custom domain name instead of the default http://n4r.renters/my-unique-name you can sign up for a domain name and point it to your public page.</p>
				<p><a href="http://www.jdoqocy.com/click-7931809-10378406-1438892975000?sid=Association-Page" class="btn btn-info">Get a Domain Name</a></p> 
			</div>
		</div>
		
	</div>
</div>
<div class="row">
	<div class="col-sm-5 landlord-social-icons">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"></i> Connect With Us:</h3>
			</div>
			<div class="panel-body">
				<p><b>Facebook URL: </b><input type="url" name="facebook" class="form-control" placeholder="https://www:facebook.com/my-business" value="<?php echo $settings->facebook; ?>"></p>
				<p><b>Twitter URL: </b><input type="url" name="twitter" class="form-control" placeholder="https://www:twitter.com/my-business" value="<?php echo $settings->twitter; ?>"></p>
				<p><b>Google URL: </b><input type="url" name="google" class="form-control" placeholder="https://www:google.com/my-business" value="<?php echo $settings->google; ?>"></p>
				<p><b>Linkedin URL: </b><input type="url" name="linkedin" class="form-control" placeholder="https://www:linkedin.com/my-business" value="<?php echo $settings->linkedin; ?>"></p>
				<p><b>Youtube URL: </b><input type="url" name="youtube" class="form-control" placeholder="https://www:youtube.com/my-business" value="<?php echo $settings->youtube; ?>"></p>
			</div>
		</div>
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-image"></i> External Link</h3>
			</div>
			<div class="panel-body">
				<p>Add a link to your website below. One box accepts the title which will be what people click on to go to the link. And the other box will contain the actual link to your website.</p>
				
				<p><b>Link Title: </b><input type="text" name="link_title" maxlength="30" class="form-control" placeholder="Ex: Visit Our Website" value="<?php echo $settings->link_title; ?>"></p>
					
				<p><b>URL: </b><input type="url" name="web_link" class="form-control" placeholder="http://website.com" value="<?php echo $settings->web_link; ?>"></p>
					
			</div>
		</div>	
	</div>
	<div class="col-sm-7">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"></i> Contact Information:</h3>
			</div>
			<div class="panel-body">
				<p><span class="text-danger">*</span> <b>Unique Page Name: </b> <i class="text-primary toolTips fa fa-question" title="Adding a unique page name will give you a custom link unique to your buisness. For example instead of a user going to www.n4r.rentals/<?php echo $settings->unique_name; ?> they can go to <br>www.n4r.rentals/<?php echo $settings->unique_name; ?>"></i> 
				<input type="text" id="uniqueName" value="<?php echo $settings->unique_name; ?>" name="unique_name" class="form-control unique-page-name-check"><div class="error-helper text-danger"></div></p>
				<b>Contact Name:</b>
				<input type="text" name="name" class="form-control" value="<?php echo $settings->name; ?>">
				<p><b>Business Logo: <i class="fa fa-question toolTips text-primary" title="Your logo should be at least 200px by 400px"></i></b><input type="file" name="file" class="attachment-img form-control img-preview"></p>
				<div class="row">
					<div class="col-sm-6">
						<p><b>Address: </b><input type="text" name="address" value="<?php echo $settings->address; ?>" class="form-control" maxlength="100"></p>
						<p><span class="text-danger">*</span> <b>City: </b><input type="text" value="<?php echo $settings->city; ?>" name="city" class="form-control" required="required" maxlength="50"></p>
						<p><b>Zip: </b><input type="text" maxlength="5" value="<?php echo $settings->zip; ?>" name="zip" class="form-control"></p>
					</div>
					<div class="col-sm-6">
						<p><b>Phone: </b><input type="text" value="<?php echo $settings->phone; ?>" name="phone" class="form-control phone"></p>
						<p><b>State: </b>
						<?php
							$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
							echo '<select name="state" class="form-control" required="">';
							echo '<option value="">Select One...</option>';
							foreach($states as $key => $val) {
								if($key == $settings->state) {
									echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
								} else {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
							}
							echo '</select>';
						?>
						</p>
						<p><b>Email: </b><input type="text" value="<?php echo $settings->email; ?>" name="email" class="form-control"></p>
					</div>
				</div>
				
				</div>
		</div>
	
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-image"></i> Pages <i class="fa fa-plus pull-right toolTips addPage" title="Add Page"></i></h3>
			</div>
			<div class="panel-body">
				<ul id="sortable" class="ui-sortable">
					<?php
						foreach($pages as $key => $val) {
							echo '<li class="ui-state-default ui-sortable-handle" data-stack="'.$val->id.'"><i class="fa fa-arrows-v toolTips" title="Reorder Me"></i> '.ucwords($val->name).' <a href="'.base_url().'landlord-associations/edit-page/'.$val->id.'"><i class="fa fa-gears pull-right toolTips text-primary" title="Edit Page"></i></a></li>';
						}
					?>
				</ul>
				<hr>
				<p><small class="text-danger">A total of 4 Pages Allowed</small></p>
				<label>Show Vacant Member Rentals Page</label>
				<div class="row">
					<div class="col-sm-6">
						<select name="show_vacant" class="form-control">
							<?php
								if($settings->show_vacant == 'y') {
									echo '<option value="y" selected>Yes</option>
											<option value="n">No</option>';
								} else {
									echo '<option value="y">Yes</option>
											<option value="n" selected>No</option>';
								}
							?>
							
						</select>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>

<div class="panel panel-primary">	
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-image"></i> Select Your Wallpaper/Background</h3>
	</div>
	<div class="panel-body">

		<p>Since this will be the background at the top of your page and it spans across the whole page no matter what size the screen is, we recommend that your background be 1140px wide by 370px tall. If the size doesn't match your background will repeat and will not look as intended.</p>
		<p>If you need help with cropping your photo to the right size you can go to <a href="http://www.fotor.com/" target="_blank">http://www.fotor.com</a> and load your image into the system and make it the intended size.</p>
		<hr>
		<div clas="row">
			<div class="col-sm-3 deleteImgPlace">
				
				<?php
					if(!empty($settings->background)) {
						echo '<button class="btn btn-danger btn-block btn-lg deleteImageBkg"><i class="fa fa-image"></i> Change Image</button>';
					} else {
						echo '<select class="form-control public-background" name="background_select">';
							echo '<option value="1">Default 1</option>';
							echo '<option value="2">Default 2</option>';
							echo '<option value="3">Default 3</option>';
							echo '<option value="na">Upload Your Own</option>';
						echo '</select>';
					}
				?>
			</div>
			<?php
				if(!empty($settings->background)) {
					echo '<div class="col-sm-9 default1 selectedBkg">';
						echo '<img src="https://network4rentals.com/network/public-images/'.$settings->background.'" class="img-responsive">';
					echo '</div>';
				} 
			?>
			<div class="col-sm-9 default1 hideBkg">
				<img src="https://network4rentals.com/network/public-images/default-1-small-choosing.jpg" class="img-responsive">
			</div>
			<div class="col-sm-9 default2 hideBkg">
				<img src="https://network4rentals.com/network/public-images/default-2-small-choosing.jpg" class="img-responsive">
			</div>
			<div class="col-sm-9 default3 hideBkg">
				<img src="https://network4rentals.com/network/public-images/default-3-small-choosing.jpg" class="img-responsive">
			</div>
			<div class="col-sm-6 default4 hideBkg">
				<input type="file" name="background" class="form-control">
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<br>
<hr>

<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Save</button>
<?php echo form_close(); ?>
		
<!-- Modal -->
<div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Page</h4>
			</div>
			<div class="modal-body">
				<form>
					<label for="pageName">Page Name</label>
					<input type="page_name" class="form-control" id="pageName" placeholder="Home, About etc" maxlength="20" required>
					<div class="pageNameError text-danger"></div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary addNewPageForm">Save changes</button>
			</div>
		</div>
	</div>
</div>
