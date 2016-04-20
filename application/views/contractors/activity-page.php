
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;" class="pull-left"><i class="fa fa-bullhorn"></i> Recent Activity</h3>
		<?php
			if(!empty($date_to)) {
				echo '<a href="'.base_url().'contractor/reset_dates" class="btn btn-warning btn-sm pull-right">
						<i class="fa fa-times"></i> Reset Dates
					</a>';
			}
		?>
		<button class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#searchDates">
			<i class="fa fa-search"></i> Search By Date
		</button>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">

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
			if($this->session->flashdata('success')) 
			{
				echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'.</div>';
			}

			if(!empty($results)) {
				
				foreach($results as $data) {
					echo '<div class="line-under">';
					echo '<div class="row">';
					echo '<div class="col-sm-6">';
					echo $data->action;
					echo '</div>';
					echo '<div class="col-sm-4 text-center">';
					echo '@ '.date('m-d-Y h:i a', strtotime($data->created)+3600);
					echo ' <small>EST</small></div>';
					echo '<div class="col-sm-2 text-right">';
					if($data->action == 'Received New Service Request' || $data->action == 'New Service Request From Website' || $data->action == 'Marked service request complete' || $data->action == 'Added a note to service request') {
						$link = '<a class="btn btn-primary" href="'.base_url().'contractor/view-service-request/'.$data->action_id.'">View</a>';
					} elseif($data->action == 'Landlord Added New Note To Request') { 
						$link = '<a class="btn btn-primary" href="'.base_url().'contractor/view-service-request/'.$data->action_id.'">View</a>';
					} else {
						$link = '';
					}

					echo $link;
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				
			} else {
				if(!empty($date_to)) {
					echo '<p>No results were found <b>From '.$date_from.'</b> To <b>'.$date_to.'</b>, try widening your search a little.</p><p>You can reset the dates by clicking the <b>reset dates button</b> above or search for a new set of dates.</p>';
				} else {
					echo 'You have no activity yet, as you move around the website you will see this list start to fill in with all your activity. This will create a reference point for you to see what you have done and allow you to view things quickly.';
				}
			}
		?>
		<hr>
		<div class="text-center">
			<?php 

				if(!empty($results)) {
					echo $links;
				}
			?>
		</div>

	</div>
</div>


<!-- Modal -->
<div class="modal fade" id="searchDates" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<?php echo form_open('contractor/notifications'); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h3 class="modal-title" id="myModalLabel"><i class="fa fa-search text-primary"></i> Search Activity By Date</h3>
			</div>
			<div class="modal-body">
				<p>Select The Dates You Would Like To Narrow Your Activity Down To.</p>
				<div class="row">
					<div class="col-sm-6">
						<?php
							echo form_label('<i class="fa fa-calendar"></i> Date From:');
							$data = array(
								'name'        => 'date_from',
								'id'          => 'date_from',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off'
							);
							echo form_input($data);
						?>
					</div>
					<div class="col-sm-6">
						<?php
							echo form_label('<i class="fa fa-calendar"></i> Date To:');
							$data = array(
								'name'        => 'date_to',
								'id'          => 'date_to',
								'maxlength'   => '20',
								'minlength'   => '6',
								'class'       => 'form-control datepicker',
								'placeholder' => 'Click Here To Select A Date',
								'required' 	  => '',
								'autocomplete' => 'off'
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

