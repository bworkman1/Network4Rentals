<?php
	$page = $this->uri->segment(2);
	$page = ucwords(str_replace ('-', ' ', $page));
	$pageArray = explode(' ', $page);
	
	if(isset($post->post)) {
		$posts = $post->post;
	}
	if(isset($post->title)) {
		$title = $post->title;
	}
	if(isset($_POST['post'])) {
		$posts = $_POST['post'];
	}
	if(isset($_POST['title'])) {
		$title = $_POST['title'];
	}
?>
<?php echo form_open(); ?>
<div class="row">
	<div class="col-sm-9">
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-file"></i> <?php echo $page; ?></h3>
			</div>
			<div class="panel-body">
				<?php
					if(validation_errors() != '') 
					{
						echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
					} 
					if($this->session->flashdata('error')) {
						echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
					}
				?>
				<textarea id="post-input" style="height: 600px; width: 100%" name="post"><?php echo $posts; ?></textarea>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="box">
			<h3><b>Options</b></h3>
			<hr>
			<div class="text-left" style="padding: 5px;">
				<label>Page Name:</label>
				<input type="text" class="form-control" name="title"  value="<?php echo $title; ?>" maxlength="60" required>
				<label>Last Edited:</label><br>
				<small><b>Jan 23 2015 05:15:86</b></small><br>
				<label>Editor IP:</label><br>
				<small><b>192.168.1.1</b></small><br>
				<hr>
				<button class="btn btn-primary btn-block btn-sm addImage" data-toggle="modal" data-target="#uploader"><i class="fa fa-image"></i> Add Image To Page</button>
				
			</div>
			<hr>
			<button type="submit" class="btn btn-block btn-primary savePost"><i class="fa fa-save"></i> Save <?php echo end($pageArray); ?></button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>

<div class="modal fade" id="uploader" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-image text-primary"></i> Upload An Image</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-6">
						<label>Browse Computer For Image</label>
						<form id="imageUploadForm" method="post" action="upload-newsletter-image.php">
							<input type="file" name="photo" id="ImageBrowse" class="form-control" name="img">
						</form>
						<div id="errorImage" class="text-danger"></div>
					</div>
				</div>
				<hr>
				<h3><i class="fa fa-image  text-primary"></i> Images Already Added</h3>
				<hr>
				<div class="uploaderProgress" style="display: none">
					<div class="progress fade">
						<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
								
						</div>
					</div>
					<div class="status fade">0% Complete</div>
				</div>
				<div class="completed-upload"></div>
				<div class="upload-preview"></div>
				<div class="row uploadedImagesPreview">
					<?php
						$dir_user = md5($this->session->userdata('user_id').$this->session->userdata('username'));
						$directory = './public-images/'.$dir_user.'/';
						$images = glob($directory . "*.*");
						$count = 0;
						foreach($images as $image)
						{
							$image = str_replace('./', '', $image);
							$img_array = explode('/', $image);
							$i = count($img_array);
							echo '<div id="uploadedImg-'.$count.'" class="col-sm-2 imageSpace">
									<img src="'.base_url().$image.'" class="uploadedImages img-responsive" data-imgurl="'.$img_array[$i-2].'/'.end($img_array).'">
									<i class="fa fa-times deleteUploadedImage toolTips fa-fw" title="Delete Image" data-imgurl="'.end($img_array).'"></i>
									<br>
								</div>';
							$count++;
						}
					?>
				</div>
				<div class="text-center text-danger">
					<hr>
					<b><i class="fa fa-exclamation-triangle"></i> Warning</b>: If you delete an image and its being used in another page or post, when users visit the page it will have a broken image icon on the page.
				</div>
		  </div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>