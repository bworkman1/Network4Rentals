<?php
	$link = true;
	if(empty($settings->desc)) {
		$settings->desc = 'Business Description';
	}
	if(empty($settings->facebook)) {
		$settings->facebook = '';
	}
	if(empty($settings->google)) {
		$settings->google = '';
	}
	if(empty($settings->linkedin)) {
		$settings->linkedin = '';
	}
	if(empty($settings->youtube)) {
		$settings->youtube = '';
	}
	if(empty($settings->twitter)) {
		$settings->twitter = '';
	}
	if(empty($settings->image)) {
		$settings->image = 'https://placehold.it/450x450';
	} else {
		$settings->image = base_url().'public-images/'.$settings->image;
	}

	if(empty($settings->unique_name)) {
		$settings->unique_name = str_replace('-', ' ', strtolower($settings->bName));
		$settings->unique_name = preg_replace('/[^\da-z ]/i', '', $settings->unique_name);
		$settings->unique_name = str_replace(' ', '-', $settings->unique_name);
	}
?>
<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error'))
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4><p>'.$this->session->flashdata('error').'</p></div>';
	}
	if($this->session->flashdata('success'))
	{
		echo '<div class="alert alert-success"><h4>Success:</h4><p>'.$this->session->flashdata('success').'</p></div>';
	}	
	if($this->session->userdata('success'))
	{
		echo '<div class="alert alert-success"><h4>Success:</h4><p>'.$this->session->userdata('success').'</p></div>';
		$this->session->unset_userdata('success');
	}	
?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-user"></i> N4R Profile</h3>
	</div>
	<div class="panel-body">
		<br>
		<div class="row">
			<div class="col-sm-8">
				<p>Proper setup of this page will allow you an online marketing presence that can highlight your entire company or be directed specifically to the home rental market. Landlords will use this public page to research potential contractors.</p>
			</div>
			<div class="col-sm-4 text-right">
				<?php
					if($settings->active == 'y') {
						if($link == true) {
							echo '<a href="http://n4rlocal.com/'.strtolower(str_replace(' ', '-', $settings->unique_name)).'" class="btn btn-success btn-lg" target="_blank">
									<i class="fa fa-location-arrow"></i> View Public Page
								</a>';
						}
					}
				?>
			</div>
		</div>
		<?php if($settings->active == 'y') { ?>
		
		<hr>
		<?php echo form_open_multipart('contractor/public-page'); ?>
		<div class="row">
			<div class="col-sm-5">
				<br>
				<img id="thumbPreview" src="<?php echo $settings->image; ?>" alt="alt text goes here" class="thumbPreview img-responsive img-center">
			</div>
			<div class="col-sm-7">
				<label><span class="text-danger">*</span> <b>Business Name:</b></label>
				<input type="text" name="bName" value="<?php echo $settings->bName; ?>" class="form-control" placeholder="Business Name" required="required">
				<label><span class="text-danger">*</span> <b>Description:</b></label>
				<textarea class="form-control" style="height: 200px" maxlength="500" name="desc" required="required"><?php echo $settings->desc; ?></textarea>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-5 contractor-social-icons">
				<h3 class="highlight"><i class="fa fa-bullhorn text-success"></i> Social Networking Links</h3>
				<p><b>Facebook URL: </b><input type="url" name="facebook" class="form-control" placeholder="https://www:facebook.com/my-business" value="<?php echo $settings->facebook; ?>"></p>
				<p><b>Twitter URL: </b><input type="url" name="twitter" class="form-control" placeholder="https://www:twitter.com/my-business" value="<?php echo $settings->twitter; ?>"></p>
				<p><b>Google URL: </b><input type="url" name="google" class="form-control" placeholder="https://www:google.com/my-business" value="<?php echo $settings->google; ?>"></p>
				<p><b>Linkedin URL: </b><input type="url" name="linkedin" class="form-control" placeholder="https://www:linkedin.com/my-business" value="<?php echo $settings->linkedin; ?>"></p>
				<p><b>Youtube URL: </b><input type="url" name="youtube" class="form-control" placeholder="https://www:youtube.com/my-business" value="<?php echo $settings->youtube; ?>"></p>
				<hr>
				<?php if(count($web_pages)< 4) { ?>
				<a href="#" data-toggle="modal" data-target="#addPage" class="toolTips pull-right text-success" title="Add New Page"><i class="fa fa-plus"></i> Add Page</a>
				<?php } ?>
				<h3 class="highlight"><i class="fa fa-file-o text-success"></i> Website Pages</h3>
				<?php
					if(!empty($web_pages)) {
						echo '<ul id="sortable" class="ui-sortable">';
							foreach($web_pages as $key => $val) {
								echo '<li class="ui-state-default ui-sortable-handle" data-stack="'.$val->id.'"><i class="fa fa-arrows-v toolTips" title="Reorder Me"></i> '.ucwords($val->name).' <a href="'.base_url().'contractor/edit-page/'.$val->id.'"><i class="fa fa-gears pull-right toolTips text-success" title="Edit Page"></i></a></li>';
							}
						echo '</ul>';
					} else {
						echo '<p><b>No pages have been created</b></p>';
					}
				?>
				<p><small><span class="text-danger">*</span> You are allowed a total of 4 pages</small></p>
				<hr>
				<h3 class="highlight"><i class="fa fa-eyedropper text-success"></i> Website Color</h3>
				<p><small>Header Footer Color: Default Green: click the eye dropper to select a color</small></p>
				<div class="input-group colorPicker">
					<?php
						if(empty($settings->website_color)) {
							$settings->website_color = '#28B62C';
						}
					?>
					<input type="text" value="<?php echo $settings->website_color; ?>" name="website_color" class="colorPick form-control" />
					<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
				</div>
		
				
			</div>
			<div class="col-sm-7">
						<br>
				<div class="well">
					<small><span class="text-danger">*</span> <b>Unique Page Name: </b> 
					<input type="text" id="uniqueName" value="<?php echo $settings->unique_name; ?>" name="unique_name" class="form-control unique-page-name-check"><div class="error-helper text-danger"></div></p>
			
					<p><span class="text-danger">*</span>Adding a unique page name will give you a custom link unique to your business. For example they can go to <br>www.n4rlocal.com/<?php echo $settings->unique_name; ?></small></p>
				</div>
				<b>Contact Name:</b>
				<input type="text" name="name" class="form-control" value="<?php echo $settings->name; ?>">
				<p><b>Business Logo: <i class="fa fa-question toolTips text-primary" title="Your logo should be at least 200px by 400px"></i></b><input id="logoImg" type="file" name="file" class="attachment-img form-control img-preview"></p>
				<div class="row">
					<div class="col-sm-6">
						<p><b>Address: </b><input type="text" name="address" value="<?php echo $settings->address; ?>" class="form-control" maxlength="100"></p>
						<p><span class="text-danger">*</span> <b>City: </b><input type="text" value="<?php echo $settings->city; ?>" name="city" class="form-control" required="required" maxlength="50"></p>
					</div>
					<div class="col-sm-6">
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
						<p><b>Website: </b><input type="text" value="<?php echo $settings->website; ?>" name="website" class="form-control"></p>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><b><span class="text-danger">*</span>  Zip: </b><input type="text" value="<?php echo $settings->zip; ?>" name="zip" class="form-control zip" maxlength="5" required></p>
					</div>
					<div class="col-sm-6">
						<p><b>Phone: </b><input type="text" value="<?php echo $settings->phone; ?>" name="phone" class="form-control phone"></p>
					</div>
				</div>
				<p><b>Email: </b><input type="email" value="<?php echo $settings->email; ?>" name="email" class="form-control"></p>
				<hr>	
			</div>
		</div>
		<hr>
		<h3 class="highlight"><i class="fa fa-link text-success"></i> SEO Settings</h3>
		<div id="seo" class="row">
			<div class="col-md-4">
				<div class="form-group">
					<p><b>Analytics Code:</b>
					<input type="text" class="form-control" name="seo_analytics" placeholder="UA-554588465-1" maxlength="20" value="<?php echo $settings->seo_analytics; ?>"></p>
					<p><small>Must setup up an account with <a href="http://www.google.com/analytics/">google analytics</a> . Once setup you need the code from the javascript that is next to 'create'. Not the whole code.
					<a href="https://www.youtube.com/watch?v=TEhnLCF_Dxw">Watch video on setup</a></p>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<p><b>SEO Description:</b>
					<textarea class="form-control" name="seo_description" maxlength="160"><?php echo $settings->seo_description; ?></textarea></p>
					<p><small>Add a short description of your business.</small></p>
				</div>
			</div>
			<div id="keyword-area" class="col-md-4">	
				<p><b>SEO Keywords:</b><br>
				<input id="keywords" class="form-control" name="seo_keywords" maxlength="255"></textarea>
				</p>
				<p><small>A max of 10 keywords about your business. <b>Use the "," character to add/set the keyword.</b></small></p>
				<div id="setKeywords" style="display: none"><?php echo $settings->seo_keywords; ?></div>
			</div>
		</div>
		
		<hr>
		<div class="row">
			<div class="col-md-6">
				<h3 class="highlight"><i class="fa fa-link text-success"></i> External Link</h3>
				<p>Add a link to your website below. One box accepts the title which will be what people click on to go to the link. And the other box will contain the actual link to your website.</p>
				<div class="row">
					<div class="col-sm-6">
						<p><b>Link Title: </b><input type="text" name="link_title" maxlength="30" class="form-control" placeholder="Ex: Visit Our Website" value="<?php echo $settings->link_title; ?>"></p>
					</div>
					<div class="col-sm-6">
						<p><b>URL: </b><input type="url" name="web_link" class="form-control" placeholder="http://website.com" value="<?php echo $settings->web_link; ?>"></p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h3 class="highlight"><i class="fa fa-newspaper-o text-success"></i> Newsletter:</h3> 
				<p>You can now add a newsletter from <a href="http://www.network4rentals.aweber.com" target="_blank">aweber</a> to be able to send your contacts newsletters every month. You will need to have your account ready and create a form for this to work. Once you create the form click "Install Myself". There will be a box with a code that says src="". You will need everything after the http://forms.aweber.com/form/. Copy the rest of the code and insert into the field below.</p>
				
				<p><b>Newsletter Link: </b><input type="text" name="newsletter" class="form-control" placeholder="03/559470503.js" value="<?php echo $settings->newsletter; ?>"></p>
					
			</div>
		</div>
		<hr>
		<?php
			if(empty($settings->background)) {
		?>
		<h3 class="highlight"><i class="fa fa-image text-success"></i> Select Your Wallpaper/Background</h3>
		<p>Since this will be the background at the top of your page and it spans across the whole page no matter what size the screen is, we recommend that your background be 2000px wide by 350px tall. If the size doesn't match your background will repeat and will not look as intended.</p>
		<p>If you need help with cropping your photo to the right size you can go to <a href="http://www.fotor.com/" target="_blank">http://www.fotor.com</a> and load your image into the system and make it the intended size.</p>
		<hr>
		<?php } else { ?>
			<h4>Your Public Page Background:</h4>
			<hr>
		<?php } ?>
		<div clas="row">
			<div class="col-sm-3">
				<div id="imgOptions">
				<?php
					if(!empty($settings->background)) {
						echo '<div id="deleteBtn"><button id="deleteImage" data-removeimg="'.$settings->background.'" data-imageid="'.$settings->id.'" class="btn btn-danger btn-block btn-sm fade in"><i class="fa fa-image"></i> Change Image</button></div>';
						echo '<select class="form-control public-background fade" name="background_select">';
							echo '<option value="'.$settings->background.'">Current Image</option>';
							echo '<option value="1">Default 1</option>';
							echo '<option value="2">Default 2</option>';
							echo '<option value="3">Default 3</option>';
							echo '<option value="na">Upload Your Own</option>';
						echo '</select>';
					} else {
						echo '<div id="deleteBtn"><button id="deleteImage" data-imageid="'.$settings->id.'" class="btn btn-danger btn-block btn-sm fade"><i class="fa fa-image"></i> Change Image</button></div>';
						echo '<select class="form-control public-background fade in" name="background_select">';
							echo '<option value="1">Default 1</option>';
							echo '<option value="2">Default 2</option>';
							echo '<option value="3">Default 3</option>';
							echo '<option value="na">Upload Your Own</option>';
						echo '</select>';
					}
				?>
				</div>
			</div>
			<?php
				if(!empty($settings->background)) {
					echo '<div class="col-sm-9 default1">';
						echo '<img src="'.base_url().'public-images/'.$settings->background.'" class="img-responsive">';
					echo '</div>';
				} else {
					echo '<div class="col-sm-9 default1">';
						echo '<img src="'.base_url().'public-images/default-1-small-choosing.jpg" class="img-responsive">';
					echo '</div>';
				}
			?>
			
			<div class="col-sm-9 default2 hideBkg">
				<img src="<?Php echo base_url(); ?>public-images/default-2-small-choosing.jpg" class="img-responsive">
			</div>
			<div class="col-sm-9 default3 hideBkg">
				<img src="<?Php echo base_url(); ?>public-images/default-3-small-choosing.jpg" class="img-responsive">
			</div>
			<div class="col-sm-6 default4 hideBkg">
				<div style="height: 30px"></div>
				<input type="file" name="background" class="form-control" id="wallCover">
				<img src="<?Php echo base_url(); ?>/assets/themes/default/images/image-place-holder.jpg" id="wallPreview" class="img-responsive" style="max-height: 65px; width: 100%">
			</div>
		</div>
		<div class="clearfix"></div>
		<br>
		</hr>
		<button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Save</button>
		<?php echo form_close(); ?>
		<?php } else { ?>
			<p>Since you don't have an active subscription to any zip codes you wont have access to the public page that displays on http://n4rlocal.com</p>
		<?php } ?>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('contractor/add-new-page'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus"></i> Add New Page</h4>
				</div>
				<div class="modal-body">
					
					<label for="pageName">Page Name</label>
					<input type="text" name="pagename" class="form-control" id="pageName" placeholder="Home, About etc" maxlength="50" required>
					<div class="pageNameError text-danger"></div>
					
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-success addNewPageForm">Add Page</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	document.getElementById('logoImg').onchange = function (evt) {
		var tgt = evt.target || window.event.srcElement,
			files = tgt.files;

		// FileReader support
		if (FileReader && files && files.length) {
			var fr = new FileReader();
			fr.onload = function () {
				document.getElementById('thumbPreview').src = fr.result;
			}
			fr.readAsDataURL(files[0]);
		}
	}
	document.getElementById('wallCover').onchange = function (evt) {
		var tgt = evt.target || window.event.srcElement,
			files = tgt.files;

		// FileReader support
		if (FileReader && files && files.length) {
			var fr = new FileReader();
			fr.onload = function () {
				document.getElementById('wallPreview').src = fr.result;
			}
			fr.readAsDataURL(files[0]);
		}
	}
</script>