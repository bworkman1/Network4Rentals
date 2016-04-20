<?php
	if(isset($_POST['page'])) {
		$page->page = $_POST['page'];
	}
	if(isset($_POST['name'])) {
		$page->name = $_POST['name'];
	}
?>
<?php echo form_open(); ?>
<div class="row">
	<div class="col-sm-9">
		<?php
			if(!empty($errors)) {
				echo '<div class="alert alert-danger"><i class="fa fa-times fa-3x pull-left"></i> <b>Error:</b><br> '.$errors.'</div>';
			}
		?>	
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-file"></i> Edit Page</h3>
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
				
				<textarea id="post-input" class="summernote" style="height: 600px; width: 100%" name="post"><?php echo $page->page; ?></textarea>
				<div class="progress">
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
						0% Complete
					</div>
				</div>
				<div id="pageFeedback"></div>
			</div>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="box">
			<h3><b>Options</b></h3>
			<hr>
			<div class="text-left" style="padding: 5px;">
				<label>Page Name:</label>
				<input type="text" class="form-control" name="name"  value="<?php echo ucwords($page->name); ?>" maxlength="60" required>
				<label>Last Edited:</label><br>
				<small><?php echo date('m-d-Y h:i', strtotime($page->ts)); ?></small><br>
				<input type="hidden" name="id" value="<?Php echo $page->id; ?>">
			</div>
			<hr>
			<button type="submit" class="btn btn-block btn-primary savePost"><i class="fa fa-save"></i> Save <?php echo end($pageArray); ?></button>
			<br>
			<a href="<?php echo base_url('landlord-associations/delete-page/'.$page->id); ?>" class="btn btn-block btn-danger"><i class="fa fa-times"></i> Delete Page </a>
		</div>
	</div>
</div>

<?php echo form_close(); ?>
