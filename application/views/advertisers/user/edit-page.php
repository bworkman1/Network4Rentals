<?php 
	echo form_open('local-partner/my-website/edit/'.$details->id); 
?>
		<div class="panel panel-primary">	
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-file"></i> Edit Page</h3>
			</div>
			<div class="panel-body">
				<?php
					if($this->session->flashdata('error')) {
						echo '<div class="alert alert-danger"><p><b><i class="fa fa-times-circle fa-lg"></i> Error:</b> '.$this->session->flashdata('error').'</p></div>';
					}
					if($this->session->flashdata('success')) {
						echo '<div class="alert alert-success"><p><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$this->session->flashdata('success').'</p></div>';
					}
				?>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label><b>Page Name:</b></label>
							<input type="text" class="form-control" name="name"  value="<?php echo ucwords($details->name); ?>" maxlength="60" required>
							<input type="hidden" name="id" value="<?Php echo $details->id; ?>">
						</div>
					</div>
					<div class="col-md-4">	
						<div class="form-group">
							<label><b>Last Edited:</b></label>
							<p><em><?php echo date('m/d/Y h:i a', strtotime('+ 1 hour', strtotime($details->ts))); ?> EST</small></em></p>
						</div>
					</div>
				</div>
				
				<textarea id="post-input" class="summernote" style="height: 600px; width: 100%" name="post"><?php echo $details->page; ?></textarea>
				<div class="progress">
					<div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
						0% Complete
					</div>
				</div>
				<div id="pageFeedback"></div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<p><b>SEO Description:</b>
							<textarea style="height: 100px" class="form-control" name="seo_description" maxlength="160"><?php echo $details->seo_description; ?></textarea></p>
							<p><small>Add a short description about this page. By default it will use the description you used on the main public page.</small></p>
						</div>
					</div>
					<div id="keyword-area" class="col-md-4">	
						<p><b>SEO Keywords:</b><br>
						<input id="keywords" class="form-control" name="seo_keywords" maxlength="255"></textarea>
						</p>
						<p><small>A max of 10 keywords about your business. <b>Use the "," character to add/set the keyword. By default this will use the keywords on the main public page.</b></small></p>
						<div id="setKeywords" style="display: none"><?php echo $details->seo_keywords; ?></div>
					</div>
					<div class="col-md-2">
						<br>
						<button type="submit" class="btn btn-block btn-primary savePost"><i class="fa fa-save"></i> Save <?php echo end($pageArray); ?></button>
					</div>
					<div class="col-md-2">
						<br>
						<a href="<?php echo base_url('local-partner/my-website/deletepage/'.$details->id); ?>" class="btn btn-block btn-danger"><i class="fa fa-times"></i> Delete Page </a>
					</div>
					
				</div>
			</div>

	
</div>

<?php echo form_close(); ?>
