<?php
$success = $this->session->flashdata('success');
$error = $this->session->flashdata('error');

$feedback = '';

if(!empty($success)) {
    $feedback = '<div class="alert alert-success alert-dismissible">
            <b><i class="fa fa-check-circle fa-lg fa-fw fa-2x pull-left" style="margin-top: 2px"></i> Success:</b> '.$success.'
        </div>';
}

if(!empty($error)) {
    $feedback = '<div class="feedback alert alert-danger alert-dismissible">
                <b><i class="fa fa-exclamation-triangle fa-2x pull-left"  style="margin-top: 2px"></i> Error:</b> '.$error.'
            </div>';
}
echo $feedback;
?>
<div class="panel panel-success">
	<div class="panel-heading">
		<h3 class="pull-left" style="margin: 0; color: #fff;"><i class="fa fa-file"></i> My Service Requests</h3>
		<a href="<?php echo base_url('contractor/add-service-request'); ?>" class="pull-right btn btn-primary">+ Add Request</a>
		<div class="clearfix"></div>
	</div>
	<div class="panel-body">
        <div class="list-group">
            <div class="row">
                <div class="col-md-4 col-md-offset-8">
                    <?php echo form_open('contractor/sort-requests', array('id'=>'sort-request')); ?>
                    <select class="form-control" name="sort" onchange="this.form.submit()">
                        <?php
                            $options = array("" => "Showing All", "y" => "Complete", "n" => "Incomplete");
                            $selected = $this->session->userdata('sort_request');
                            foreach($options as $key => $val) {
                                if($selected == $key) {
                                    echo '<option selected>' . $val . '</option>';
                                } else {
                                    echo '<option>'.$val.'</option>';
                                }
                            }
                        ?>
                    </select>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
		<?php	
			if(!empty($requests)) {
				echo '<div class="bottom-border margin-bottom">
					<div class="row">
						<div class="col-sm-1 hidden-xs">
							<b>#</b>
						</div>
						<div class="col-sm-3 col-xs-5">
							<b>Service Type &amp; Zip</b>
						</div>
						<div class="col-sm-3 hidden-xs">
							<b>Customer</b>
						</div>
						<div class="col-sm-2 col-xs-4">
							<b>Received</b>
						</div>
						<div class="col-sm-2 hidden-xs">
							<b>Complete</b>
						</div>
						<div class="col-sm-1 text-right col-xs-3">
							<b>View</b>
						</div>
					</div>
				</div>';
				$count=1;
				$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding', 14=>'Pest Control / Exterminator');
				foreach($requests as $key => $val) {
					if($val->complete == 'n') {
						$status = '<i class="fa fa-times fa-fw text-danger"></i> <span class="hidden-sm hidden-xs">Incomplete</span>';
					} else {
						$status = '<i class="fa fa-check fa-x2 fa-fw text-success"></i> <span class="hidden-sm hidden-xs">Complete</span>';
					}
					echo '<div class="bottom-border padding-top"><div class="row">
						<div class="col-sm-1 hidden-xs">
							<p>'.$val->counter.'</p>
						</div>
						<div class="col-sm-3 col-xs-5">
							<p>'.$services_array[$val->service_type].' | '.$val->rental_zip.'</p>
						</div>
						<div class="col-sm-3 hidden-xs">
							<p>'.$val->name.'</p>
						</div>
						<div class="col-sm-2 col-xs-4">';
							if(!empty($val->contractor_received)) {
								echo '<p>'.date('m-d-Y h:i A', strtotime($val->contractor_received)+3600).'</p>';
							} else {
								echo '<p>'.date('m-d-Y h:i A', strtotime($val->submitted)+3600).'</p>';
								
							}
						echo '</div>
						<div class="col-sm-2 hidden-xs">
							<p>'.$status.'</p>
						</div>
						<div class="col-sm-1 text-right col-xs-3">
							<p class="hidden-xs"><a href="'.base_url().'contractor/view-service-request/'.$val->id.'" class="btn btn-primary">View</a></p>
							<p class="visible-xs"><a href="'.base_url().'contractor/view-service-request/'.$val->id.'" class="btn btn-primary"><i class="fa fa-gears fa-fw"></i></a></p>
						</div>
					</div></div>';
					$count++;
				}
			} else {
				echo '<div class="alert alert-info"><h2 style="color: #fff">You don\'t have any service requests yet</h2>';
				echo '<p>Service reqeust landlords send you and ones from your website will all funnel into this page as you get them. If you would like to add your own to help keep track of your work load you can do that also.</p><p><a style="text-decoration: none" href="'.base_url('contractor/add-service-request').'" class="btn btn-primary">Add Service Request</a></p></div>';
			}
			echo '<div class="text-center">'.$links.'</div>';

		?>
		
	</div>
</div>