
<h3 class="highlight">Edit Post</h3>
<p>As you build your post below you will see a preview of it to the right. You have 145 characters to describe your company and entice landlords to click your post. Once they click your post they will be taken to your public page/web page that you added after you created your account.
<hr>
<?php
	if(is_array($feedback)) {
		$feed = '';
		foreach($feedback as $val) {
			$feed .= $val.'<br>';
		}
		$feedback = $feed;
	}
	if(!empty($feedback)) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle pull-left fa-2x"></i> '.$feedback.'</div>';
	}
?>

<div class="row">
	<div class="col-sm-6">
		<div class="well">
			<?php echo form_open_multipart('contractor/edit-ad/'.$this->uri->segment(3)); ?>
				<label><b><span class="text-danger">*</span> Apply To:</b></label>
				<select name="apply_post" class="form-control" required>
					<option value="">Select One...</option>
					<option value="1">Apply Changes To Only This Post</option>
					<option value="2">Apply To Post With The Same Service Type</option>
					<option value="3">Apply To All Post</option>
				</select>
				<label><b><span class="text-danger">*</span> Title:</b></label>
				<input type="text" name="title" class="form-control titleAd" maxlength="30" value="<?php if(isset($ad_details->title)) {echo $ad_details->title;} ?>">
				<label><b><span class="text-danger">*</span> Business Name:</b></label>
				<input type="text" name="bName" class="form-control bName" maxlength="30" value="<?php if(isset($ad_details->bName)) {echo $ad_details->bName;} ?>">
				<label><b><span class="text-danger">*</span> Phone:</b></label>
				<input type="text" name="phone" class="form-control phone" maxlength="16" value="<?php if(isset($ad_details->phone)) {echo $ad_details->phone;} ?>">
				<label><b><span class="text-danger">*</span> Description:</b></label>
				<textarea name="description" class="form-control ad-desc" style="height: 150px" maxlength="145"><?php if(isset($ad_details->description)) {echo $ad_details->description;} ?></textarea>
				<div class="text-counter"><small><b>145</b> Characters Left</small></div>
				<label><b>Upload An Image</b></label>
				<input id="file_upload" type="file" name="file" class="form-control">
				<hr>
				<input type="hidden" name="id" value="<?php echo $this->uri->segment(3); ?>">
				<button class="btn btn-success pull-right" type="submit"><i class="fa fa-save"></i> Save</button>
				<div class="clearfix"></div>
		
			<?php echo form_close(); ?>
		</div>
	</div>
	<div class="col-lg-4 col-md-6">
		<div id="ad-preview" class="text-center">
			<div class="title">
				<?php 
					if(!empty($ad_details->title)) {
						echo '<h4><b>'.$ad_details->title.'</b></h4>';
					}
				?>
			</div>
			<div class="description"><?php if(!empty($ad_details->description)) {echo $ad_details->description;} ?></div>
			<div class="other-details">
				<div id="imageUploaded" class="embedded-logo">
					<?php 
						if(!empty($ad_details->ad_image)) {
							echo '<img id="preview-img" class="img-responsive img-center" src="'.base_url().'contractor-images/'.$ad_details->ad_image.'" alt="'.$ad_details->bName.'" onChange="readURL(this);">';
						}
					?>
				</div>
				<div style="width: 100%; background-color: #298AC2; color: #fff; padding: 3px 0; margin-top:8px;">
				<b class="bNameInput"><?php echo $ad_details->bName; ?></b><br>
				<p class="inputPhone"><?php echo "(".substr($ad_details->phone, 0, 3).") <span>".substr($ad_details->phone, 3, 3)."-</span>".substr($ad_details->phone,6); ?></p>
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
				document.getElementById('imageUploaded').innerHTML = '<img id="preview-img" class="img-responsive img-center" src="'+fr.result+'">';
				//document.getElementById('preview-img').src = fr.result;
			}
			fr.readAsDataURL(files[0]);
		}
	}
</script>