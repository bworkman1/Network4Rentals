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
		$settings->image = 'https://placehold.it/550x400';
	} else {
		$settings->image = base_url($settings->image);
	}
	
	if(empty($settings->unique_name)) {
		$settings->unique_name = str_replace('-', ' ', strtolower($settings->bName));
		$settings->unique_name = preg_replace('/[^\da-z ]/i', '', $settings->unique_name);
		$settings->unique_name = str_replace(' ', '-', $settings->unique_name);
		$link = false;
	}
?>

<div class="panel">

	<div class="panel-heading">
		<?php if($link == true) { ?>
			<a href="https://n4rlocal.com/<?php echo strtolower(str_replace(' ', '-', $settings->unique_name)); ?>" class="btn btn-primary pull-right" target="_blank">
				<i class="fa fa-location-arrow"></i> View Public Page
			</a>
		<?php } ?>
		
		<h3><i class="fa fa-desktop text-primary"></i> My Website</h3>
		<div class="clearfix"></div>
		<hr>
	</div>

	<div class="panel-body">
		<?php
			$error = validation_errors('<span>', '</span>');
			if(!empty($error))
			{
				echo '<div class="alert alert-danger"><p><b><i class="fa fa-times-circle fa-lg"></i> Error: </b>'.$error.'</p></div>';
			}
			if($this->session->flashdata('error'))
			{
				echo '<div class="alert alert-danger"><p><b><i class="fa fa-times-circle fa-lg"></i> Error: </b>'.$this->session->flashdata('error').'</p></div>';
			}
			if($this->session->flashdata('success'))
			{
				echo '<div class="alert alert-success"><p><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$this->session->flashdata('success').'</p></div>';
			}	
		?>
		<p>Proper set-up of this page will allow you an on-line marketing presence that can highlight your entire company or be directed specifically to the home rental market. Landlords will use this public page to research potential contractors.</p>		
		<hr>
		<?php echo form_open_multipart('local-partner/my-website'); ?>
		
		
		  <h2>Website Set-up</h2>
		  <ul class="nav nav-tabs">
			<li class="active"><a data-toggle="tab" href="#contact">Contact Details</a></li>
			<li><a data-toggle="tab" href="#home">Home Page</a></li>
			<li><a data-toggle="tab" href="#design">Design</a></li>
			<li><a data-toggle="tab" href="#pages">Pages</a></li>
			<li><a data-toggle="tab" href="#social">Social Networking</a></li>
		  </ul>

		  <div class="tab-content">
			<div id="contact" class="tab-pane fade in active">
			  <h3>Contact Details</h3>
			  <p>Add your contact details including your <strong>category, unique page name, phone number, and more.</strong></p>
			  <div class="row">
				<div class="col-md-4">
				<img id="thumbPreview" src="<?php echo $settings->image; ?>" alt="alt text goes here" class="thumbPreview img-responsive img-center">
				<p><b>Upload Logo: <i class="fa fa-question toolTips text-primary" title="Your logo should be at least 200px by 400px"></i></b><input id="logoImg" type="file" name="file" class="attachment-img form-control img-preview"></p>
				</div>
				<div class="col-md-8">
					<div class="well">
						<small><span class="text-danger">*</span> <b>Unique Page Name: </b> 
						<input type="text" id="uniqueName" value="<?php echo $settings->unique_name; ?>" name="unique_name" class="form-control unique-page-name-check"><div class="error-helper text-danger"></div></p>
				
						<p><span class="text-danger">*</span>Adding a unique page name will give you a custom link unique to your business. For example they can go to <br>www.N4RLocal.com/<?php echo $settings->unique_name; ?></small></p>
					</div>
					<?php
                    echo '<label>Select Business Category</label>';
                    echo '<select class="form-control" name="category" required>';
                        echo '<option value="">Select One...</option>';
                        foreach($categories as $row) {
                            if($row->id == $userCategory->category) {
                                echo '<option selected value="'.$row->id.'">'.$row->category.'</option>';
                            } else {
                                echo '<option value="'.$row->id.'">'.$row->category.'</option>';
                            }
                        }
                    echo '</select>';

					?>
				</div>
			  </div>
			  <div class="row">
				<div class="col-md-6">
				<label><span class="text-danger">*</span> <b>Business Name:</b></label>
				<input type="text" name="bName" value="<?php echo $settings->bName; ?>" class="form-control" placeholder="Business Name" required="required">
				</div>
				<div class="col-md-6">
				<b>Contact Name:</b>
				<input type="text" name="name" class="form-control" value="<?php echo $settings->name; ?>">
				</div>
			  </div>
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
						<p><b><span class="text-danger">*</span>  Zip: </b><input type="text" value="<?php echo $settings->zip; ?>" name="zip" class="form-control zip" maxlength="5" required></p>
						</p>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<p><b>Phone: </b><input type="text" value="<?php echo $settings->phone; ?>" name="phone" class="form-control phoneMask"></p>
					</div>
					<div class="col-sm-6">
						<p><b>Email: </b><input type="email" value="<?php echo $settings->email; ?>" name="email" class="form-control"></p>
					</div>
				</div>
				<div class="row">
					<h3 class="highlight"><i class="fa fa-link text-primary"></i> External Link</h3>
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
			</div>
		<div id="home" class="tab-pane fade">
			  <h3>Home Page</h3>
			  <p>This is the page a user will first see when they visit your site.</p>
			  <label><span class="text-danger">*</span> <b>Description:</b></label>
				<textarea class="form-control" style="height: 200px" maxlength="4000" name="desc" required="required"><?php echo $settings->desc; ?></textarea>
				<hr>
				<h3 class="highlight"><i class="fa fa-link text-primary"></i> SEO Settings</h3>
			<div id="seo" class="row">
				<div class="col-md-6">
					<div class="form-group">
						<p><b>SEO Description:</b>
						<textarea class="form-control" name="seo_description" maxlength="160"><?php echo $settings->seo_description; ?></textarea></p>
						<p><small>Add a short description of your business.</small></p>
					</div>
				</div>
				<div id="keyword-area" class="col-md-6">	
					<p><b>SEO Keywords:</b><br>
					<input id="keywords" class="form-control" name="seo_keywords" maxlength="255" value="<?php echo $settings->seo_keywords; ?>">
					</p>
					<p><small>A max of 10 keywords about your business. <b>Use the "," character to add/set the keyword.</b></small></p>
					<div id="setKeywords" style="display: none"><?php echo $settings->seo_keywords; ?></div>
				</div>
			</div>
			<div class="row">
				<div class="form-group">
					<p><b>Analytics Code:</b>
					<input type="text" class="form-control" name="seo_analytics" placeholder="UA-554588465-1" maxlength="20" value="<?php echo $settings->seo_analytics; ?>"></p>
					<p><small>Must setup up an account with <a href="https://www.google.com/analytics/">google analytics</a> . Once setup you need the code from the javascript that is next to 'create'. Not the whole code.
						<a href="https://www.youtube.com/watch?v=TEhnLCF_Dxw">Watch video on setup</a></small></p>
				</div>
			</div>
		
		
		<hr>
			<div class="row">
				<h3 class="highlight"><i class="fa fa-newspaper-o text-primary"></i> Newsletter:</h3> 
				<p>You can now add a newsletter from <a href="http://www.network4rentals.aweber.com" target="_blank">aweber</a> to be able to send your contacts newsletters every month. You will need to have your account ready and create a form for this to work. Once you create the form click "Install Myself". There will be a box with a code that says src="". You will need everything after the http://forms.aweber.com/form/. Copy the rest of the code and insert into the field below.</p>
				
				<p><b>Newsletter Link: </b><input type="text" name="newsletter" class="form-control" placeholder="03/559470503.js" value="<?php echo $settings->newsletter; ?>"></p>
			</div>
		</div>
			<div id="design" class="tab-pane fade">
			  <h3>Design</h3>
			  <p>Add your own business look by uploading a cover image and header color.</p>
			  <h3 class="highlight"><i class="fa fa-eyedropper text-primary"></i> Website Color</h3>
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
				<div class="website_wrapper">
					<p><small>Wrapper Color: coming soon</small></p>
				</div>
				<?php
			if(empty($settings->background)) {
		?>
		<h3 class="highlight"><i class="fa fa-image text-primary"></i> Select Your Wallpaper/Background</h3>
		<p>Since this will be the background at the top of your page and it spans across the whole page no matter what size the screen is, we recommend that your background be 1600px wide by 370px tall. If the size doesn't match your background will repeat and will not look as intended.</p>
		<p>If you need help with cropping your photo to the right size you can go to <a href="http://www.fotor.com/" target="_blank">http://www.fotor.com</a> and load your image into the system and make it the intended size.</p>
		<hr>
		<?php } else { ?>
			<h4>Your Public Page Background: (<strong>recommended image size 1600px x 370px)</strong></h4>
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
				if(strpos($user_info->background, 'uploads') !== false) {
					echo '<div class="col-sm-9 default1">';
						echo '<img src="'.base_url($settings->background).'" class="img-responsive">';
					echo '</div>';
				} else {
					if(empty($settings->background)) {
						echo '<div class="col-sm-9 default1">';
							echo '<img src="'.base_url().'public-images/default-1-small-choosing.jpg" class="img-responsive">';
						echo '</div>';
					} else {
						echo '<div class="col-sm-9 default1">';
							echo '<img src="'.base_url().'public-images/'.$settings->background.'" class="img-responsive">';
						echo '</div>';
					}
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
				<img src="<?Php echo base_url('/assets/themes/default/images/image-place-holder.jpg'); ?>" id="wallPreview" class="img-responsive" style="max-height: 65px; width: 100%">
			</div>
		</div>
			</div>
			<div id="pages" class="tab-pane fade">
			  <h3>Web Pages</h3>
			  <p>Add up to 4 seo friendly web pages.</p>
			  <?php if(count($web_pages)< 4) { ?>
				<a href="#" data-toggle="modal" data-target="#addPage" class="toolTips pull-right text-primary" title="Add New Page"><i class="fa fa-plus"></i> Add Page</a>
				<?php } ?>
				<h3 class="highlight"><i class="fa fa-file-o text-primary"></i> Website Pages</h3>
				<?php
					if(!empty($web_pages)) {
						echo '<ul id="sortable" class="ui-sortable">';
							foreach($web_pages as $key => $val) {
								echo '<li class="ui-state-default ui-sortable-handle" data-stack="'.$val->id.'"><i class="fa fa-arrows-v toolTips" title="Reorder Me"></i> '.ucwords($val->name).' <a href="'.base_url('local-partner/my-website/edit/'.$val->id).'"><i class="fa fa-gears pull-right toolTips text-primary" title="Edit Page"></i></a></li>';
							}
						echo '</ul>';
					} else {
						echo '<p><b>No pages have been created</b></p>';
					}
				?>
				<p><small><span class="text-danger">*</span> You are allowed a total of 4 pages</small></p>
			</div>
			<div id="social" class="tab-pane fade">
			  <h3>Social Networking</h3>
			  <p>Add some of the top social networking sites. Just visit your page and then copy and paste the link in the correct site.</p>
			  <h3 class="highlight"><i class="fa fa-bullhorn text-primary"></i> Social Networking Links</h3>
				<p><b>Facebook URL: </b><input type="url" name="facebook" class="form-control" placeholder="https://www:facebook.com/my-business" value="<?php echo $settings->facebook; ?>"></p>
				<p><b>Twitter URL: </b><input type="url" name="twitter" class="form-control" placeholder="https://www:twitter.com/my-business" value="<?php echo $settings->twitter; ?>"></p>
				<p><b>Google URL: </b><input type="url" name="google" class="form-control" placeholder="https://www:google.com/my-business" value="<?php echo $settings->google; ?>"></p>
				<p><b>Linkedin URL: </b><input type="url" name="linkedin" class="form-control" placeholder="https://www:linkedin.com/my-business" value="<?php echo $settings->linkedin; ?>"></p>
				<p><b>Youtube URL: </b><input type="url" name="youtube" class="form-control" placeholder="https://www:youtube.com/my-business" value="<?php echo $settings->youtube; ?>"></p>
				<p>Vimeo (coming soon)</p>
				<p>Pinterest (coming soon)</p>
			</div>
		  </div>
<hr>
		<div class="clearfix"></div>
		<br>
		<button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-save"></i> Save</button>
		<?php echo form_close(); ?>
	
	</div>
</div>



<!-- Modal -->
<div class="modal fade" id="addPage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('local-partner/my-website/addpage'); ?>
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
					<button type="submit" class="btn btn-primary addNewPageForm">Add Page</button>
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