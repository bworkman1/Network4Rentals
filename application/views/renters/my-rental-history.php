<?php
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
?>
<div class="panel panel-warning">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-5">
				<i class="fa fa-bars"></i> Rental Resume
			</div>
			<div class="col-sm-7 text-right">
				<a href="<?php echo base_url(); ?>renters/add-landlord" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add Current Landlord</a>
				<a href="<?php echo base_url(); ?>renters/add-past-landlord" class="btn btn-primary pull-right margin-btn"><i class="fa fa-plus"></i> Add Past Landlord</a>
				<?php 
					if(!empty($results)) { 
						echo '<a href="'.base_url().'renters/rental-history_pdf" class="btn btn-primary"><i class="fa fa-print"></i> Print</a>';
					}
					
				?>
			</div>
		</div>
		
	</div>
	<div class="panel-body">
		<p>Below you will find your rental history that can help you keep track of all your past and present rental info.</p>
	</div>
</div>
<?php

	if(!empty($results)) {
		$count = 0;
		echo '<div class="panel-group" id="accordion">';
		foreach($results as $val) {
			echo '
				<div class="panel panel-warning">
					<div class="panel-heading">
						<button class="btn btn-primary" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$count.'"><i class="fa fa-bars"></i> </button> ';
							
				
			if(empty($val['bName'])) { 
				echo $val['landlord_name'].' <small> '.$val['rental_address'].' '.$val['rental_zip'].'</small>';
			} else {
				echo $val['bName'].' <small> '.$val['rental_address'].' '.$val['rental_zip'].'</small>';
			}
			
		
			if($val['move_out'] == '0000-00-00') {
				echo '<a href="'.base_url().'renters/edit-rental-details/'.$val['id'].'" class="pull-right btn btn-primary">Edit Details</a>';
			}
			if($val['current_residence'] == 'y') {
				echo '<b class="pull-right" style="margin-right: 50px">Current Residence</b>';
			} else {
				echo '<b class="pull-right" style="margin-right: 50px">Past Residence</b>';
			}
			if(!empty($val['warning'])) {
				echo '<div class="label label-info  pull-right toolTips" style="margin-right: 8px" title="Move Out Date Is Needed For This Rental Property. Click The Edit Details Link To The Right"><i class="fa fa-info"></i></div>';
			}
			echo '<div class="clearfix"></div>
					</div>';
			$val['landlord_phone'] = "(".substr($val['landlord_phone'], 0, 3).") ".substr($val['landlord_phone'], 3, 3)."-".substr($val['landlord_phone'],6);
			echo '<div id="collapse'.$count.'" class="panel-collapse collapse">
					<div class="panel-body">
						<div class="row">
							<div class="col-sm-6">
								<h3>Landlord Details</h3>
								<p><b>Name:</b> '.$val['landlord_name'].'</p>
								<p><b>Email:</b> '.$val['landlord_email'].'</p>
								<p class="hidden-xs"><b>Phone:</b> '.$val['landlord_phone'].'</p>
								<p class="visible-xs"><b>Phone:</b> <a class="btn btn-primary btn-xs" href="tel:'.$val['landlord_phone'].'"><i class="fa fa-phone"></i> Call</a></p>';
			if($val['current_residence'] == 'y') {
				echo '<p><b>Address:</b>  <br>'.$val['landlord_address'].' <br>'.$val['landlord_city'].' '.$val['state'].'. '.$val['zip'].'</p>';
			}
			echo '<p><b>Current Residence: </b>';
			
			
			if($val['current_residence'] == 'y') {
				echo ' Yes';
				if(empty($val['landlord_email']))  {
					$show_attention_modal = true;
					$landlordId = $val['id'];
				} else {
					$show_attention_modal = false;
				}
			} else {
				echo ' No';
			}
			echo '</p>';
			if(!empty($val['checklist_id'])) {
				echo '<p><b>Check-list:</b> <br><a class="btn btn-primary btn-xs" href="'.base_url().'renters/view-checklist/'.$val['checklist_id'].'"><i class="fa fa-eye"></i> View Check-list</a></p>';
			} else {
				if($val['current_residence'] == 'y') {
					echo '<p><b>Check-list:</b> <br>Fill Out Your Check-list <br><a class="btn btn-primary btn-xs" href="'.base_url().'renters/checklist-form/'.$val['id'].'"><i class="fa fa-check"></i> Go To Check-list</a></p>';
				} else {
					echo '<p><b>Rental Check-list:</b><p></p>Check-list Never Completed</p>';
				}
			}
			if(!empty($val['user'])) {
				echo '<br><span class="label label-success">Registered Landlord</span>';
			} else {
				echo '<span class="label label-danger">Un-Registered Landlord</span>';
			}
							echo '</div>
							<div class="col-sm-6">
								<h3>Rental Details</h3>
								<p><b>Address:</b> <br>'.$val['rental_address'].'<br>'.$val['rental_city'].', '.$val['rental_state'].'. '.$val['rental_zip'].'</p>
								<p><b>Move In Date:</b> '.date('m-d-Y', strtotime($val['move_in'])).'</p>';
			if($val['move_out'] != '0000-00-00') {
				echo '<p><b>Move Out Date:</b> '.date('m-d-Y', strtotime($val['move_out'])).'</p>';
			} else {
				echo '<p><b>Move Out Date:</b> NA</p>';
			}
			echo '<p><b>Rent Paid: </b> $'.$val['amount'].'</p>';
			echo '<p><b>Deposit: </b> $'.$val['deposit'].'</p>';
			echo '<p><b>Disputes: </b> '.$val['count'].'</p>';
			echo '<p><b>Lease: </b> '.$val['lease'].'</p>';
			echo '<p><b>Rent Per Month: </b>$'.$val['payments'].'</p>';
			
			if(!empty($val['day_rent_due'])) {
			$day_suffix = 'th';
			if ($val['day_rent_due'] < 10 | $val['day_rent_due'] > 20)
			{
			  switch ($val['day_rent_due']%10)
			  {
				case 1:
				  $day_suffix = 'st';
				  break;
				case 2:
				  $day_suffix = 'nd';
				  break;
				case 3:
				  $day_suffix = 'rd';
				  break;
				default:
				  break;
			  }
			}
			} else {
				$val['day_rent_due'] = 'Not Set Yet';
				$day_suffix = '';
			}
			
			echo '<p><b>Day Rents Due: </b>'.$val['day_rent_due'].$day_suffix.' day of the month</p>';
			echo '<p><b>Uploaded Lease: </b><br>';
			if(!empty($val['lease_upload'])) {
				echo '<a href="'.base_url().'lease-uploads/'.$val['lease_upload'].'" target="_blank" class="btn btn-primary"><i class="fa fa-print"></i> Print/View</a></p>';
			} else {
				echo 'NA';
			}
			echo '	
							</div>
						</div>';
				echo '<h4>Options</h4>';
			
				echo '<div class="row">';
				echo '<div class="col-sm-4">';
				echo '<a href="'.base_url().'renters/view-messages/'.$val['id'].'" class="btn btn-primary"><i class="fa fa-comments-o"></i> Message Landlord</a>';
				echo '</div>';
				echo '<div class="col-sm-4 text-center">';
				
				if($val['current_residence'] == 'y') {
					if(empty($val['user'])) {
						if(!empty($val['landlord_email'])) {
							
							echo '<a href="'.base_url().'renters/request-registration-email/'.$val['id'].'" class="btn btn-warning btn-sm toolTips" title="Request Your Landlord To Become Part Of N4R On Your Behalf"><i class="fa fa-envelope-o"></i> Send Email</a>';
						}
					}
				}
				echo '</div>';
				echo '<div class="col-sm-4 text-right">';
				echo '<a href="'.base_url().'renters/view-payment-history/'.$val['id'].'" class="btn btn-primary"><i class="fa fa-clock-o"></i> View Payment History</a>';
				echo '</div>';
				echo '</div>';			
				
			

				echo '</div>
				</div>
			  </div>';
			  echo '<div class="spacing15"></div>';
			$count++;
		}
		echo '</div>';
		
		if($show_attention_modal == true) {
			echo '<div class="modal fade" id="attentionUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			  <div class="modal-dialog">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3><i class="fa fa-exclamation-triangle text-warning"></i> Attention</h3>
				  </div>
				  <div class="modal-body">
					<p>Without out an email address or cell phone number of your current landlord our system has no way to communicate with your landlord. If you know your landlords email address click the edit landlord button now. If you know their cell phone number edit you landlords details with the cell phone number and we will automatically convert their cell phone number to an email address. </p>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<a href="'.base_url().'renters/edit-rental-details/'.$landlordId.'" class="btn btn-warning">Edit Current Landlord</a>
				  </div>
				</div>
			  </div>
			</div>';
		}
	} else {
		echo '<b><i class="fa fa-exclamation text-danger"></i> You have not added any landlords to your account. Click the "Add Landlord" button above to add landlords and begin tracking your rental history.</b>';
	}
	
?>

