<?php
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error')) {
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
?>
<div class="panel panel-primary">	
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-file-o"></i> All My Posts</h3>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-xs-4">
				<b><small>TITLE</small></b>
			</div>
			<div class="col-xs-3">
				<b><small>UPDATED</small></b>
			</div>
			<div class="col-xs-3">
				<b><small>CREATED</small></b>
			</div>
			<div class="col-xs-2 text-right">
				<b><small>OPTIONS</small></b>
			</div>
		</div>
		<hr>
		<?php 
			if(!empty($post)) {
				foreach($post as $key => $val) { 
		?>
			<div class="row">
				<div class="col-xs-4">
					<?php echo $val->title; ?>
				</div>
				<div class="col-xs-3">
					<small><?php echo date('m-d-Y h:i a', strtotime($val->updated)+3600); ?></small>
				</div>
				<div class="col-xs-3">
					<small><?php echo date('m-d-Y h:i a', strtotime($val->updated)+3600); ?></small>
				</div>
				<div class="col-xs-2 text-right">
					<!-- Single button -->
					<div class="btn-group">
						<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
							Options <span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="<?php echo base_url(); ?>landlord-associations/edit-post/<?php echo $val->id; ?>"><i class="fa fa-pencil"></i> Edit</a></li>
							<li><a href="#"><i class="fa fa-eye"></i> View Post</a></li>
							<li><a href="#"><i class="fa fa-minus-circle"></i> Un-Publish</a></li>
							<li class="divider"></li>
							<li class="text-danger"><a href="<?php echo base_url(); ?>landlord-associations/delete-post/<?php echo $val->id; ?>"><i class="fa fa-times"></i> Delete</a></li>
						</ul>
					</div>
				</div>
			</div>
			<hr>
		<?php } ?>
		<?php } else {?>
			<h3>No Post Added Yet</h3>
		<?php } ?>
		<a class="btn btn-primary pull-right" href="">View Public Page</a>
	</div>
</div>