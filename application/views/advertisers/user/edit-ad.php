<h2><i class="fa fa-map-marker text-primary"></i> Edit Premium Ad</h2>
<p>As you build your premium ad below you will see a preview of it to the right. You have 145 characters to describe your company and entice landlords to click your post. Once they click your post they will be taken to your website that you added after you created your account.
<hr>
<?php
	if(empty($page->id)) {
		echo '<div class="alert alert-danger">';
			echo '<b><i class="fa fa-exclamation-triangle"></i> Error: </b><br>In Order For Your Post To Show Up On The Website You Will Need To Create Your Public Page/Web Page, <a href="'.base_url().'advertisers/public-page-settings">Here</a>. Once your page is up and running you will be able to see all your stats showing you how well your post is doing.';
		echo '</div>';
	}
?>
<div class="row">
	<div class="col-sm-6">
	
		<div class="alert alert-info"><?php 
			$userTypeArray = array('', 'Landlords', 'Renters', 'Contractors', 'Advertisers'); 
			echo '<i class="fa fa-question-circle fa-lg"></i> Editing ad for '.$userTypeArray[$ad_details->service_purchased].' in '.$ad_details->zip_purchased; ?>
		</div>
		<div class="well">
			<?php echo form_open_multipart('local-partner/my-zips/edit/'.$this->uri->segment(4)); ?>
				<label><span class="text-danger">*</span> Apply To:</label>
				<select name="apply_post" class="form-control" required>
					<option value="">Select One...</option>
					<option value="1">Apply Changes To Only This Post</option>
					<option value="2">Apply To Post With The Same User Type</option>
					<option value="3">Apply To All Post</option>
				</select>
				<label>Title:</label>
				<input type="text" name="title" class="form-control titleAd" maxlength="30" value="<?php if(isset($ad_details->title)) {echo $ad_details->title;} ?>">
				<label>Post:</label>
				<textarea name="desc" style="height: 110px" class="form-control ad-desc" maxlength="145"><?php if(isset($ad_details->desc)) {echo $ad_details->desc;} ?></textarea>
				<div class="text-counter text-right"><small><b>145</b> Characters Left</small></div>
				<label>Upload An Image</label>
				<input id="file_upload" type="file" name="file" class="form-control">
				<div class="text-right">
					<small><span class="text-danger">*</span> Or Leave Blank To Use Your Logo</small>
				</div>
				<hr>
				<?php if(!empty($page->id)) { ?>
					<button class="btn btn-primary btn-sm pull-right" type="submit"><i class="fa fa-save"></i> Save</button>
					<div class="clearfix"></div>
				<?php } ?>
			<?php echo form_close(); ?>
		</div>
	</div>
	<div class="col-sm-4">
		<div id="ad-preview">
			<div class="title text-center">
				<?php 
					if(!empty($ad_details->title)) {
						echo '<h4><b>'.$ad_details->title.'</b></h4>';
					}
				?>
			</div>
			<div class="description text-center"><?php if(!empty($ad_details->desc)) {echo $ad_details->desc;} ?></div>
			<div class="other-details">
				<div class="embedded-logo">
					<?php 
						if(!empty($ad_details->ad_image)) {
							echo '<img id="preview-img" class="img-responsive logo sponsorLogo" src="'.base_url($ad_details->ad_image).'">';
						} else {
							if(!empty($page->image)) {
								echo '<img id="preview-img" class="img-responsive sponsorLogo" src="'.base_url().'public-images/'.$page->image.'">';
							} else {
								echo '<img id="preview-img" class="img-responsive sponsorLogo" src="'.base_url('assets/themes/default/images/little-white-placeholder-image.png').'">';
							}
						}
					?>
				</div>
				<div class="text-center" style="width: 100%; background-color: #298AC2; color: #fff; padding: 3px 0; margin-top:8px;">
				<b><?php echo $page->bName; ?></b><br>
				 <?php if(!empty($page->phone)) {echo "(".substr($page->phone, 0, 3).") ".substr($page->phone, 3, 3)."-".substr($page->phone,6);} ?>
				
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	document.getElementById('file_upload').onchange = function (evt) {
		var tgt = evt.target || window.event.srcElement,
			files = tgt.files;
		
		// FileReader support
		if (FileReader && files && files.length) {
			var fr = new FileReader();
			fr.onload = function () {
				document.getElementById('preview-img').src = fr.result;
			}
			fr.readAsDataURL(files[0]);
		}
	}
	
	
</script>