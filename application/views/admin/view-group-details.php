<div class="row">
    <div class="col-lg-12">
		<div class="row">
			<div class="col-sm-6">
				<h1>
					User Details
				</h1>
			</div>
			<div class="col-sm-6">
				<div class="pull-right">
					<?php echo $links; ?>
				</div>
				<div class="pull-right">
					<br>
					<button style="margin-right:15px" data-toggle="modal" data-target="#search-users" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
				</div>
			</div>
		</div>
		<hr>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-users"></i> <?php echo ucwords($this->uri->segment(3)); ?>
            </li>
			<?php if($this->session->userdata('user_sort_by')) { ?>
				<li  class="pull-right">
					<p class="pull-right"><a href="<?php echo base_url('n4radmin/remove-user-sorting'); ?>">Remove Sort</a></p>
				</li>
			<?php } ?>
        </ol>
		
		<?php
			$success = $this->session->flashdata('success');
			$error = $this->session->flashdata('error');
			
			if(!empty($success)) {
				echo '<div class="alert alert-success"><i class="fa fa-thumbs-up"></i> '.ucwords($success).'</div>';
			}
			if(!empty($error)) {
				echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.ucwords($error).'</div>';
			}
		?>
		
    </div>
</div>

<?php echo $table; ?>
<div class="pull-right">
	<?php echo $links; ?>
</div>

<div class="modal" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteUser">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo form_open('n4radmin/delete-user'); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="searchLabel">Are you sure you want to delete <span class=" text-danger"></span></h4>
				</div>
				<div class="modal-body">
					Deleting this user will <b>delete all their data</b> with their account causing views to be broken or incomplete that are associated with that account. Only delete users you are sure that you want to delete.
					<div id="deleteUserError"></div>
				</div>
				<input type="hidden" id="user_id_delete" value="" name="user_id">
				<input type="hidden" id="user_type_delete" value="" name="user_type">
				
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-danger">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="search-users" tabindex="-1" role="dialog" aria-labelledby="search-users">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<?php echo form_open(); ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="searchLabel">Search For A User</h4>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>User Type:</label>
								<select class="form-control" name="user_type">
									<?php 
										$options = array('Landlords', 'Renters', 'Contractors', 'Advertisers');
										foreach($options as $val) {
											if(strtolower($val) == $this->uri->segment(3)) {
												echo '<option selected="selected">'.$val.'</option>';
											} else {
												echo '<option>'.$val.'</option>';
											}
										}
									?>
								</select>
							</div>
							
							<div class="form-group">
								<label>Search By:</label>
								<select name="searchBy" class="form-control">
									<?php
										$options = array('user'=>'username', 'name'=>'Name', 'email'=>'Email', 'phone'=>'Phone', 'city'=>'City', 'state'=>'State', 'zip'=>'Zip');
										echo '<option value="">Select One</option>';
										foreach($options as $key => $val) {
											if(strtolower($val) == $_POST['val']) {
												echo '<option value="'.$key.'" selected="selected">'.$val.'</option>';
											} else {
												echo '<option value="'.$key.'">'.$val.'</option>';
											}
										}
									?>
								</select>
							</div>
							
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Source:</label>
								<select name="source" class="form-control">
									<option value="">Select One (optional)</option>
									<option>Advertisement</option>
									<option>Event or Booth</option>
									<option>Facebook</option>
									<option>Family</option>
									<option>Friends</option>
									<option>Google+</option>
									<option>Linkedin</option>
									<option>Literature (handouts, fliers, etc.</option>
									<option>Online Search</option>
									<option>Other</option>
									<option>Tenant Request</option>
									<option>Utica Home Coming</option>
								</select>
							</div>
							
							<div class="form-group">
								<label>Search For:</label>
								<input type="text" name="searchFor" class="form-control">
							</div>
							
						</div>
						
						
						
					</div>
					
				</div>
			
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Search</button>
				</div>
			</form>
		</div>
	</div>
</div>