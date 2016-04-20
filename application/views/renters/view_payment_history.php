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
			<div class="col-sm-8">
				<i class="fa fa-dollar"></i> Payment History
			</div>
			<div class="col-sm-4 text-right">
				<?php if($address['current_residence'] == 'y') { ?>
				<a href="<?php echo base_url(); ?>renters/pay-rent" class="btn btn-primary"><i class="fa fa-plus"></i> Add Payment</i></a> 
				<?php } ?>
				<a class="btn btn-primary" href="<?php echo base_url(); ?>renters/my-history"><i class="fa fa-reply"></i> Go Back</a>
			</div>
		</div>
	</div>
	<div class="panel-body">

<p><b>Rent you have paid while living at: </b><?php echo $address['rental_address'].', '; echo $address['rental_city'].' '; echo $address['rental_state'].' '; echo $address['rental_zip']; ?></p>
<hr>



<?php if(!empty($payments)) {?>
<div class="table-responsive">
	<table class="table table-hover">
		<thead>
			<tr>
				<td><b>Amount</b></td>
				<td><b>Submitted On</b></td>
				<td><b>Status</b></td>
				<td><b>Payment Type:</b></td>
				<td><b>Auto Pay:</b></td>
				<td></td>
			</tr>
		</thead>
		<?php
			foreach($payments as $val) {
				if($val['auto_paid'] == 'y') {
					$val['auto_paid'] = '<label class="label label-success">Yes</label>';
				} else {
					$val['auto_paid'] = '<label class="label label-danger">No</label>';
				}
				echo '<tr>';
				echo '<td>$'.number_format($val['amount'], 2).'</td>';
				echo '<td>'.date('m-d-Y', strtotime($val['paid_on'])).'</td>';
				echo '<td>Submitted</td>';
				echo '<td>'.ucwords($val['payment_type']).'</td>';
				echo '<td>'.$val['auto_paid'].'</td>';
				echo '<td class="text-right"><a href="'.base_url().'renters/view-payment-details/'.$val['id'].'" class="btn btn-primary btn-block btn-sm"><i class="fa fa-eye"></i> View Details</a></td>';
				echo '</tr>';
			}
		?>
	</table>
</div>
<?php } else { 
	echo $none;
	}
?>
</div>
</div>