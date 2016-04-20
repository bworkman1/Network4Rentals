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
<div class="row">
	<div class="col-sm-8">
		<h2><i class="fa fa-link text-primary"></i></i> Public Page Settings</h2>
	</div>
	<div class="col-sm-4 text-right">
		<br>
		<?php
			if($settings->active == 'y') {
				if($link == true) {
					echo '<a href="http://n4rlocal.com/'.strtolower(str_replace(' ', '-', $settings->unique_name)).'" class="btn btn-primary btn-xs" target="_blank">
							<i class="fa fa-location-arrow"></i> View Public Page
						</a>';
				}
			}
		?>
	</div>
</div>
<hr>
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
	
	
?>
<?php if($settings->active == 'y') { ?>
<p>Setting this page up will allow you to give out a link to people interested in renting homes from you. Below your contact information it will list all your rental properties for visitors to see what you have available.</p>
<hr>
<?php echo form_open_multipart('advertisers/public-page-settings'); ?>
<div class="row">
	<div class="col-sm-5">
		<br>
		<img id="thumbPreview" src="<?php echo $settings->image; ?>" alt="alt text goes here" class="thumbPreview img-responsive">
	</div>
	<div class="col-sm-7">
		<label><span class="text-danger">*</span> <b>Business Name:</b></label>
		<input type="text" name="bName" value="<?php echo $settings->bName; ?>" class="form-control" placeholder="Business Name" required="required">
		<label><span class="text-danger">*</span> <b>Description:</b></label>
		<textarea class="form-control" style="height: 175px" maxlength="1000" name="desc" required="required"><?php echo $settings->desc; ?></textarea>
	</div>
</div>
<div class="row">
	<div class="col-sm-5 advertisers-social-icons">
		<h4 class="text-center">Connect With Us:</h4>
		<div class="list-group text-center">
			<a class="list-group-item facebook" href="#" value="<?php ?>">
				<i class="fa fa-facebook"></i>
			</a>
			<a class="list-group-item twitter" href="#">
				<i class="fa fa-twitter"></i>
			</a>
			<a class="list-group-item google" href="#">
				<i class="fa fa-google-plus"></i>
			</a>
			<a class="list-group-item linkedin" href="#">
				<i class="fa fa-linkedin"></i>
			</a>
			<a class="list-group-item youtube" href="#">
				<i class="fa fa-youtube"></i>
			</a>
		</div>
		<p><b>Facebook URL: </b><input type="url" name="facebook" class="form-control" placeholder="https://www:facebook.com/my-business" value="<?php echo $settings->facebook; ?>"></p>
		<p><b>Twitter URL: </b><input type="url" name="twitter" class="form-control" placeholder="https://www:twitter.com/my-business" value="<?php echo $settings->twitter; ?>"></p>
		<p><b>Google URL: </b><input type="url" name="google" class="form-control" placeholder="https://www:google.com/my-business" value="<?php echo $settings->google; ?>"></p>
		<p><b>Linkedin URL: </b><input type="url" name="linkedin" class="form-control" placeholder="https://www:linkedin.com/my-business" value="<?php echo $settings->linkedin; ?>"></p>
		<p><b>Youtube URL: </b><input type="url" name="youtube" class="form-control" placeholder="https://www:youtube.com/my-business" value="<?php echo $settings->youtube; ?>"></p>
	</div>
	<div class="col-sm-7">
		<div class="well">
			<small><span class="text-danger">*</span> <b>Unique Page Name: </b> 
			<input type="text" id="uniqueName" value="<?php echo $settings->unique_name; ?>" name="unique_name" class="form-control unique-page-name-check"><div class="error-helper text-danger"></div></p>
			<?php if(!empty($settings->unique_name)) { ?>
				<p><span class="text-danger">*</span>Adding a unique page name will give you a custom link unique to your business. For example instead of a user going to www.n4r.rentals/239A392932AKD they can find you at <br>www.n4r.rentals/(your-business-name)</small></p>
			<?php } else { ?>
				<p><span class="text-danger">*</span>Adding a unique page name will give you a custom link unique to your business. For example they can go to <br>www.n4r.rentals/<?php echo $settings->unique_name; ?></small></p>
			<?php } ?>
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
				<p><b> Website: </b><input type="url" value="<?php echo $settings->website; ?>" name="website" class="form-control"></p>
				</p>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<p><b><span class="text-danger">*</span>  Zip: </b><input type="text" value="<?php echo $settings->zip; ?>" name="zip" class="form-control zip" maxlength="5" required></p>
			</div>
			<div class="col-sm-6">
				<p><b>Phone: </b><input type="text" value="<?php echo $settings->phone; ?>" name="phone" class="form-control phones"></p>
			</div>
		</div>
		<p><b>Email: </b><input type="email" value="<?php echo $settings->email; ?>" name="email" class="form-control"></p>
	</div>
</div>

<hr>
<?php
	if(empty($settings->background)) {
?>
<h4>Select Your Wallpaper/Background</h4>
<p>Since this will be the background at the top of your page and it spans across the whole page no matter what size the screen is, we recommend that your background be 2000px wide by 350px tall. If the size doesn't match your background will repeat and will not look as intended.</p>
<p>If you need help with cropping your photo to the right size you can go to <a href="http://www.fotor.com/" target="_blank">http://www.fotor.com</a> and load your image into the system and make it the intended size.</p>
<hr>
<?php } else { ?>
	<h4>Your Public Page Background:</h4>
	<hr>
<?php } ?>
<div clas="row">
	<div class="col-sm-3">
		
		<?php
			if(!empty($settings->background)) {
				echo '<a href="'.base_url().'advertisers/delete-public-image/'.$settings->id.'" class="btn btn-danger btn-block btn-sm"><i class="fa fa-image"></i> Change Image</a>';
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
			echo '<div class="col-sm-9 default1">';
				echo '<img src="'.base_url().'public-images/'.$settings->background.'" class="img-responsive" style="max-height: 65px; width: 100%">';
			echo '</div>';
		} else {
			echo '<div class="col-sm-9 default1">';
				echo '<img src="'.base_url().'public-images/default-1-small-choosing.jpg" class="img-responsive" style="max-height: 65px; width: 100%">';
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
		<input type="file" name="background" class="form-control" id="wallCover">
		<img src="<?Php echo base_url(); ?>/assets/themes/default/images/image-place-holder.jpg" id="wallPreview" class="img-responsive" style="max-height: 65px; width: 100%">
	</div>
</div>
<div class="clearfix"></div>
<br>
</hr>
<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
<?php echo form_close(); ?>
<?php } else { ?>
	<p>Since you don't have an active subscription to any zip codes you wont have access to the public page that displays on http://n4r.rentals</p>
<?php } ?>
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