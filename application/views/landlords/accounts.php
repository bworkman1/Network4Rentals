<h2><i class="fa fa-building-o text-primary"></i></i> My Accounts</h2>
<p>Accounts that you manage are listed below.</p>
<?php
	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
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
				echo '<b>Account Name:</b>';
			echo '</div>';
			echo '<div class="col-sm-3">';
				echo '<b>Admin Name:</b>';
			echo '</div>';
			echo '<div class="col-sm-3 text-right">';
				echo '<b>Options</b>';
			echo '</div>';
		echo '</div>';
	echo '</div>';

	if(!empty($results)) {
		foreach($results as $key => $val) {
			echo '<div class="admins-list">';
				echo '<div class="row">';
					echo '<div class="col-xs-6">';
						echo $val['sub_b_name'];
					echo '</div>';
					echo '<div class="col-xs-4">';
						if(!empty($val['bName'])) {
							echo $val['bName'];
						} else {
							echo $val['name'];
						}
					echo '</div>';
					echo '<div class="col-xs-2 text-right">';
						echo '<a href="'.base_url().'landlords/view-landlord/'.$val['id'].'" class="btn btn-primary btn-xs toolTips" style="margin-bottom: 3px;" title="Edit Admin Group"><i class="fa fa-eye"></i></a> ';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	} else {
		echo 'You have no admins added to your account. If you would like to add and admin to your account click the add admin button and add the user name of the person you would like to add.';
	}
?>

<div class="modal fade" id="add-admin" tabindex="-1" role="dialog" aria-labelledby="add-admin" aria-hidden="true">
	<div class="modal-dialog">
		<?php echo form_open('landlords/add-admin'); ?>
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel"><i class="fa fa-envelope text-primary"></i> Add New Administrator</h4>
				</div>
				<div class="modal-body">
					<p>You need the other persons user name in order to set them up as an administrator. Once you have that select their access level and click add and they will be able to login in to their account and see your account.</p>
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
					<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add Administrator</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>