<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><b>Error:</b> '.$error.'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><b>Success:</b> '.$success.'</div>';
	}
?>

<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;" class="pull-left"><i class="fa fa-users"></i> My Employees</h3>
		<button data-target="#addEmployee" data-toggle="modal" class="btn btn-primary pull-right btn-sm"><i class="fa fa-plus"></i> Add Employee</button>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">
		<?php if(!empty($employees)) { ?>
		<div class="table-responsive">
		
			<table class="table table-striped table-condensed">
				<thead>
					<tr>
						<td><b>Name:</b></td>
						<td><b>Email:</b></td>
						<td><b>Color:</b></td>
						<td class="text-right"><b>Options:</b></td>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($employees as $key =>$val) {
							echo '<tr>';
								echo '<td>'.$val->name.'</td>';
								echo '<td>'.$val->email.'</td>';
								echo '<td>';
									echo '<span class="box" style="border:1px solid #c9c9c9;height:30px;width:30px;display:inline-block;background:'.$val->color.';">';
									echo '</span>';
								echo '</td>';
								echo '<td class="text-right"><small><a href="#" data-target="#editEmployee" data-toggle="modal" class="editEmployee" data-id="'.$val->id.'">Edit</a> | <a href="'.base_url('contractor/delete-employee/'.$val->id).'">Delete</a></small></td>';
							echo '</tr>';
							
						}
					?>
				
				</tbody>	
				<tfoot>
					<tr>
						<td><b>Name:</b></td>
						<td><b>Email:</b></td>
						<td><b>Color:</b></td>
						<td class="text-right"><b>Options:</b></td>
					</tr>
				</tfoot>
			</table>
			<?php } else { ?>
				<div class="alert alert-block alert-info fade in no-margin">
                    
                      <h4 class="alert-heading">
                       No Emplyees
                      </h4>
                      <p>
                        You have not added any employees yet, click the add employees button below to add one.
                      </p>
                      <p>
                        <button data-target="#addEmployee" data-toggle="modal" class="btn btn-success" data-original-title="" title="">
                          Add New Employee
                        </button>
                       
                      </p>
                    </div>
			<?php } ?>
		</div>
	</div>
</div>


<div class="modal fade" id="editEmployee" tabindex="-1" role="dialog" aria-labelledby="editEmployee">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
		<?php echo form_open('contractor/edit-employee'); ?>
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel">Edit Employee</h4>
		  </div>
		  <div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<label><span class="text-danger">*</span> Employee Name:</label>
								<input type="text" id="employeeName" maxlength="30" name="name" class="form-control" required tabindex="1">
							</div>
							<br>
							<label><span class="text-danger">*</span> Select a Color:</label>
							<div id="color" class="input-group colorPicker colorpicker-component colorpicker-element">
								<input type="text" maxlength="7" required tabindex="3" name="color" class="form-control">
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
							<div class="help"><small>Click the eyedropper</small></div>
							
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<label><span class="text-danger">*</span> Employee Email:</label>
								<input id="employeeEmail" type="email" maxlength="60" name="email" class="form-control" required tabindex="2">
							</div>
						</div>
					</div>
					<input type="hidden" id="employeeId" name="id">
		  </div>
		  <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			<button type="submit" class="btn btn-primary">Save Employee</button>
		  </div>
		</form>	
    </div>
  </div>
</div>

<div class="modal fade" id="addEmployee" tabindex="-1" role="dialog" aria-labelledby="addEmployee">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo form_open('contractor/add-employee'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Edit Employee</h4>
				</div>
				<div class="modal-body">
				
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<label><span class="text-danger">*</span> Employee Name:</label>
								<input type="text" maxlength="30" name="name" class="form-control" required tabindex="1">
							</div>
							<br>
							<label><span class="text-danger">*</span> Select a Color:</label>
							<div class="colorPicker input-group colorpicker-component colorpicker-element">
								<input type="text" maxlength="7" required tabindex="3" name="color" class="form-control">
								<span class="input-group-addon"><i class="fa fa-eyedropper"></i></span>
							</div>
							<div class="help"><small>Click the eyedropper</small></div>
							
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<label><span class="text-danger">*</span> Employee Email:</label>
								<input type="email" maxlength="60" name="email" class="form-control" required tabindex="2">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>