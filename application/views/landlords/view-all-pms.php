<?php
	
	if($this->session->flashdata('error')) 
	{
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <b>Error:</b> '.$this->session->flashdata('error').'</div>';
	}
	if($this->session->flashdata('success')) 
	{
		echo '<div class="alert alert-success"><h4>Success:</h4>'.$this->session->flashdata('success').'</div>';
	}
?>
<div class="panel panel-primary">
	<div class="panel-heading">
		<h3 style="color: #fff; margin: 0; padding: 0;" class="pull-left">
			<i class="fa fa-wrench"></i> 	Preventive Maintenance Requests
		</h3>
		<a href="<?php echo base_url('landlords/add-service-request'); ?>" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Add New</a>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">
		<div id="large-calendar">
			<?php
				echo $calendar;
			?>
		</div>
	</div>
</div>
