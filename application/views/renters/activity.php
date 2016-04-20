<div class="row">
	<div class="col-sm-5">
		<h2><i class="fa fa-bullhorn text-primary"></i> Recent Activity</h2>
	</div>
	<div class="col-sm-7 text-right">
		<br>
		<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#searchDates">
			<i class="fa fa-search"></i> Search By Date
		</button>
		<?php
			$dates = $this->session->userdata('date_to');
			if(!empty($dates)) {
				echo '<a href="'.base_url('renters/reset-dates').'" class="btn btn-warning btn-sm">
						<i class="fa fa-times"></i> Reset Dates
					</a>';
			}
		?>
	</div>
</div>
<hr>
<div class="activity">
<?php
	
	if(!empty($reset)) {
		echo $reset;
	}
	if(validation_errors() != '') 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	} 
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
		if($this->session->flashdata('warning')) 
	{
		echo '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle fa-fw fa-2x pull-left" style="margin-top: 10px;"></i> <b>Warning: </b>'.$this->session->flashdata('warning').'.</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
	}
	
	if(!empty($activity)) {
		echo $activity;
	} else {
		if(!empty($date_to)) {
			echo '<p>No results were found <b>From '.$date_from.'</b> To <b>'.$date_to.'</b>, try widening your search a little.</p><p>You can reset the dates by clicking the <b>reset dates button</b> above or search for a new set of dates.</p>';
		} else {
			echo 'You have no activity yet, as you move around the website you will see this list start to fill in with all your activity. This will create a reference point for you to see what you have done and allow you to view things quickly.';
		}
	}
?>
<hr>
</div>
<div class="text-center">
	<?php echo $links; ?>
</div>
<!-- Modal -->
<div class="modal fade" id="searchDates" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('renters/activity'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="myModalLabel"><i class="fa fa-search text-primary"></i> Search Activity By Date</h3>
			</div>
			<div class="modal-body">
				<p>Select The Dates You Would Like To Narrow Your Activity Down To.</p>
				<div class="row">
					<div class="col-sm-6">
						<?php
							$dateFrom = $this->session->userdata('date_from');
							echo form_label('<i class="fa fa-calendar"></i> Date From:');
							$data = array(
								'name'        => 'date_from',
								'id'          => 'date_from',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off',
								'value' => $dateFrom
							);
							echo form_input($data);
						?>
					</div>
					<div class="col-sm-6">
						<?php
							$dateTo = $this->session->userdata('date_to');
							echo form_label('<i class="fa fa-calendar"></i> Date To:');
							$data = array(
								'name'        => 'date_to',
								'id'          => 'date_to',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off',
								'value' => $dateTo,
							);
							echo form_input($data);
							
						?>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save Dates</button>
			</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>


<?php
	$createdAccount = $this->session->flashdata('guide_user');
	if($createdAccount) {
		echo '<div class="modal fade" id="newUserModal" tabindex="-1" role="dialog" aria-labelledby="newUserModal" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel">What do I do now?</h4>
					</div>
					<div class="modal-body">
						<div class="row text-center">
							<div class="col-sm-4">
								<h4>Use Network4Rentals to find my next home</h4>
								<div><a href="'.base_url('listings').'" class="btn btn-warning btn-block">Search Listings</a></div>
								<div><a href="'.base_url('renters/in-search-of').'" class="btn btn-warning btn-block">In Search Of</a></div>
							</div>
							<div class="col-sm-4">
								<h4>Use Network4Rentals to communicate with my landlord and pay or record rent payments online</h4>
								<div><a href="'.base_url('renters/add-landlord').'"><i class="fa fa-plus"></i> Add a Landlord</a></div>
							</div>
							<div class="col-sm-4">
								<h4>Learn how to use Network4Rentals Services</h4>
								<div><a href="'.base_url('renters/videos').'">Tenant How to Videos</a></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						
					</div>
				</div>
			</div>
		</div>';
	} /*elseif($landlord_check == 0) {
		echo '<div class="modal fade" id="nolandlord" tabindex="-1" role="dialog" aria-labelledby="nolandlord" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						<h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-warning"></i> You Have Not Added Any Landlord\'s Yet</h4>
					</div>
					<div class="modal-body">
						<p>N4R is a great place to connect to your current landlord and manage your rental history all in one place. One of the main ways to achieve this is to link a landlords account to yours. Once you add a landlord you will be able to enjoy all the benefits N4R has to offer. Click the add landlord button below and it will take you to where you need to be.</p>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<a href="<?php echo base_url(); ?>renters/add-landlord" class="btn btn-warning"><i class="fa fa-plus"></i> Add A Landlord</a>
					</div>
				</div>
			</div>
		</div>';
	} */
	
?>

