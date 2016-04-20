<div class="row">
	<div class="col-sm-9">
		<h2><i class="fa fa-users text-primary"></i></i> My Managers</h2>
	</div>
	<div class="col-sm-3">
		<br>
		<button data-toggle="modal" data-target="#add-admin" class="btn btn-primary btn-xs pull-right"><i class="fa fa-plus"></i> Add Sub Business</button>
	</div>
</div>
<p>You can add managers to your account if you want someone else to be able to access your account without giving them your information.</p>
<?php
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<br><div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	
?>
<hr>
<?php
	echo '<div class="admins-list">';
		echo '<div class="row">';
			echo '<div class="col-sm-6">';
				echo '<b>Mgr. Account Name</b>';
			echo '</div>';
			echo '<div class="col-sm-3">';
				echo '<b>Sub Business Name</b>';
			echo '</div>';
			echo '<div class="col-sm-3 text-right">';
				echo '<b>Edit</b>';
			echo '</div>';
		echo '</div>';
	echo '</div>';
	if(!empty($results)) {
		foreach($results as $key => $val) {
			echo '<div class="admins-list">';
				echo '<div class="row">';
					echo '<div class="col-xs-6">';
						if(!empty($val['bName'])) {
							echo $val['bName'];
						} else {
							echo $val['name'];
						}
					echo '</div>';
					echo '<div class="col-xs-4">';
						echo $val['sub_b_name'];
					echo '</div>';
					echo '<div class="col-xs-2 text-right">';
						echo ' <button data-toggle="modal" data-changeid="'.$val['id'].'" data-subbname="'.$val['sub_b_name'].'" data-target="#deleteAdmin" class="btn btn-primary btn-xs toolTips editAdmin" title="Edit This Group"><i class="fa fa-pencil"></i></button>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo 'You have no managers added to your account. If you would like to add and managers to your account click the add manager button and add the user name of the person you would like to add.';
	}

?>

<div class="modal fade" id="add-admin" tabindex="-1" role="dialog" aria-labelledby="add-admin" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/add-admin'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Add New Managers</h4>
				</div>
				<div class="modal-body">
					<p>You need the other persons user name in order to set them up as an managers. Once you have that select their access level and click add and they will be able to login in to their account and see your account.</p>
					<div class="row">
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Sub Business Name: </label>
							<span class="toolTips pull-right" title="Sub business names will be the name that your tenants add to their rental history to contact this property manager. This keeps everything organize and if you end your relationship with this manager the service request posted to this group say in your account.">
							<i class="fa fa-question-circle text-primary"></i></span> 
							<input type="text" name="sub_b_name" class="form-control" required="required" maxlength="50">
						</div>
						<div class="col-sm-6">
							<label><span class="text-danger">*</span> Email:</label>
							<input type="text" name="email" class="form-control" required="required" maxlength="50">
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Save</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<!-- Modal -->
<div class="modal fade" id="deleteAdmin" tabindex="-1" role="dialog" aria-labelledby="deleteAdmin" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('landlords/remove-admin-from-account'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-edit text-primary"></i> Edit Sub Group</h4>
			</div>
			<div class="modal-body">
				<p>Clicking the delete button below will delete this user from you account and not allow him to view your tenants any more. The tenants that he has accumulated through the Sub Business Name you created when you added him will be rolled over to your account or to another account of your choosing.</p>
				<p>In order to add the tenants that linked to their account while acting as your manager, <b>add the new manager before you delete this one</b> or assign them to an existing account.</p>
				<hr>
				<div class="row">
					<div class="col-sm-6">
						<label>Sub Business Name:</label>
						<input type="text" value="" class="form-control input-sm subBName" name="sub_b_name" required="">
						<input type="hidden" value="" class="form-control input-sm subGroupId" name="sub_group_id" required="">
					</div>
					<div class="col-sm-6">
						<label>New Managers Email Address</label>
						<input type="email" class="form-control input-sm" name="new_email" placeholder="New Managers Email">
					</div>
				</div>
				<input type="hidden" class="hidden-admin-id" name="admin_id">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary btn-sm">Save</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>