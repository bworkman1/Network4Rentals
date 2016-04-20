<?php
	$link = true;
	if(empty($settings->desc)) {
		$settings->desc = 'Looking for your next rental home? Contact us today!';
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
		$link = false;
		if(!empty($settings->bName)) {
			$settings->unique_name = preg_replace('/\W+/', '-', strtolower($settings->bName));		
		} else {
			$settings->unique_name = md5($_SERVER['SERVER_ADDR'].date('Y-m-d h:s'));
		}
	}
?>
<div class="row">
	<div class="col-sm-8">
		<h2><i class="fa fa-link text-primary"></i></i> Public Page Settings</h2>
	</div>
	<div class="col-sm-4 text-right">
		<br>
		<?php
			if($link == true) {
				echo '<a href="http://n4r.rentals/'.strtolower(str_replace(' ', '-', $settings->unique_name)).'" class="btn btn-primary btn-xs pull-left" target="_blank">
						<i class="fa fa-location-arrow"></i> View Public Page
					</a>';
				echo '<button data-toggle="modal" data-target="#email-link" class="btn btn-primary btn-xs pull-right">
						<i class="fa fa-envelope"></i> Email Link
					</button>';	
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
<p>Setting this page up will allow you to give out a link to people interested in renting homes from you. Below your contact information it will list all your rental properties for visitors to see what you have available.</p>
<hr>
<?php echo form_open_multipart('landlords/public-page-settings'); ?>
<div class="row">
	<div class="col-sm-5">
		<br>
		<img src="<?php echo $settings->image; ?>" alt="alt text goes here" class="thumbPreview img-responsive">
	</div>
	<div class="col-sm-7">
		<label><span class="text-danger">*</span> <b>Business Name:</b></label>
		<input type="text" name="bName" value="<?php echo $settings->bName; ?>" class="form-control" placeholder="Business Name" required="required">
		<label><span class="text-danger">*</span> <b>Description:</b></label>
		<textarea class="form-control" style="height: 175px" maxlength="500" name="desc" required="required"><?php echo $settings->desc; ?></textarea>
	</div>
</div>
<div class="row">
	<div class="col-sm-5 landlord-social-icons">
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
		<p><span class="text-danger">*</span> <b>Unique Page Name: </b> <i class="text-primary toolTips fa fa-question" title="Adding a unique page name will give you a custom link unique to your buisness. For example instead of a user going to www.n4r.rentals/<?php echo $settings->unique_name; ?> they can go to <br>www.n4r.rentals/<?php echo $settings->unique_name; ?>"></i> 
		<input type="text" id="uniqueName" value="<?php echo $settings->unique_name; ?>" name="unique_name" class="form-control unique-page-name-check"><div class="error-helper text-danger"></div></p>
		<b>Contact Name:</b>
		<input type="text" name="name" class="form-control" value="<?php echo $settings->name; ?>">
		<p><b>Business Logo: <i class="fa fa-question toolTips text-primary" title="Your logo should be at least 200px by 400px"></i></b><input type="file" name="file" class="attachment-img form-control img-preview"></p>
		<div class="row">
			<div class="col-sm-6">
				<p><b>Address: </b><input type="text" name="address" value="<?php echo $settings->address; ?>" class="form-control" maxlength="100"></p>
				<p><span class="text-danger">*</span> <b>City: </b><input type="text" value="<?php echo $settings->city; ?>" name="city" class="form-control" required="required" maxlength="50"></p>
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
			</div>
		</div>
		<p><b>Email: </b><input type="text" value="<?php echo $settings->email; ?>" name="email" class="form-control"></p>
	</div>
</div>
<hr>
<h3><i class="fa fa-file-o"></i> External Link</h3>
<p>Add a link to your website below. One box accepts the title which will be what people click on to go to the link. And the other box will contain the actual link to your website.</p>
<div class="row">
	<div class="col-sm-6">
		<p><b>Link Title: </b><input type="text" name="link_title" maxlength="30" class="form-control" placeholder="Ex: Visit Our Website" value="<?php echo $settings->link_title; ?>"></p>
	</div>
	<div class="col-sm-6">
		<p><b>URL: </b><input type="url" name="web_link" class="form-control" placeholder="http://website.com" value="<?php echo $settings->web_link; ?>"></p>
	</div>
</div>
<hr>
<div class="well well-sm">
<h3>Only Applies To Group Managers</h3>
<hr>
	<div class="row">
		<div class="col-sm-8">
			<button class="btn btn-primary">View Other Rentals By This Owner</button><h4> <i class="fa fa-arrow-up"></i> Redirect button on listings page for your properties goes to?</h4>
		</div>
		<div class="col-sm-4">
			<select name="admin_redirect" class="form-control">
				<?php
					$options = array('n'=>'My Page', 'y'=>'Admins Page');
					foreach($options as $key => $val) {
						if($settings->admin_redirect == $key) {
							echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';
						} else {
							echo '<option value="'.$key.'">'.$val.'</option>';
						}
					}
				?>
			</select>
		</div>
	</div>
</div>
<hr>
<?php
	if(empty($settings->background)) {
?>
<h4>Select Your Wallpaper/Background</h4>
<p>Since this will be the background at the top of your page and it spans across the whole page no matter what size the screen is, we recommend that your background be 1140px wide by 370px tall. If the size doesn't match your background will repeat and will not look as intended.</p>
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
				echo '<a href="'.base_url().'landlords/delete-public-image/'.$settings->id.'" class="btn btn-danger btn-block btn-sm"><i class="fa fa-image"></i> Change Image</a>';
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
				echo '<img src="https://network4rentals.com/network/public-images/'.$settings->background.'" class="img-responsive">';
			echo '</div>';
		} else {
			echo '<div class="col-sm-9 default1">';
				echo '<img src="https://network4rentals.com/network/public-images/default-1-small-choosing.jpg" class="img-responsive">';
			echo '</div>';
		}
	?>
	
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
<br>
</hr>
<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
<?php echo form_close(); ?>
	<?php
		if(!empty($results)) {
			echo '<hr>
				<h3><i class="fa fa-home text-primary"></i> My Available Rentals</h3>
				<ul class="landlord-page-listings">';
		
			foreach($results as $key => $val) {
				if($val->active == 'y') {
					echo '<li>';
						if(!empty($val->img_show)) {
							echo '<img src="'.base_url().'listing-images/'.$val->img_show.'" alt="alt text goes here" class="pull-left" width="75px" height="75px" alt="'.$val->title.'">';
						} else {
							echo '<img src="http://placehold.it/70x70" alt="alt text goes here" class="pull-left">';
						}
						echo '<h4>'.$val->address.', '.$val->city.' '.$val->stateAbv.'</h4>';
						echo '<a href="'.base_url().'listings/view-listing/'.$val->id.'" target="_blank" class="btn btn-primary btn-xs pull-right"><i class="fa fa-link"></i> View</a>';
						echo '<p>'.$val->title.'<br>';
							echo '<span class=""><b>Bedrooms:</b> '.$val->bedrooms.'</span> ';
							echo ' <span class=""><b>Bathrooms:</b> '.$val->bathrooms.'</span>';
						echo '</p>';
					echo '</li>';
				}
			}
			echo '</ul>';
		}
	?>


<div class="text-center">
	<?php echo $links; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="email-link" tabindex="-1" role="dialog" aria-labelledby="emil-link" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/send-public-link'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-link text-primary"></i> Email Link</h4>
				</div>
				<div class="modal-body">
					<p>Your public link to your account is below. You can click the link and bookmark it for future use if you would like. If you would like to email it to someone enter the persons email address in the box below and we will send it on your behalf.</p>
					
					<div class="public-link">
						<h4>Public Link</h4>
						<p> http://www.n4r.rentals/<?php echo str_replace(' ', '-', $settings->unique_name); ?></p>
					</div>
					<label>Email Address:</label>
					<div class="row">
						<div class="col-sm-6">
							<input type="email" name="email" class="form-control input-sm">
						</div>
						<div class="col-sm-6">
							<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-envelope"></i> Send</button>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>