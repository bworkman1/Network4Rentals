<?php
	$success = $this->session->flashdata('success');
	if(!empty($success)) {
		echo $success;
	}
	$error = $this->session->flashdata('error');
	if(!empty($error)) {
		echo $error;
	}
?>
<div class="widget">
	<div class="widget-header">
		<div class="title">
			<i class="fa fa-building"></i> Supply Houses
		</div>
		<span class="tools">
		  <a href="<?php echo base_url('n4radmin/add-supply-house'); ?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New</a>
		</span>
	</div>
	<div class="widget-body">
		<div class="table-responsive">
			<table class="table table-striped table-bordered" style="margin-bottom: 125px">
				<thead>
					<tr>
						<th>Business</th>
						<th>Address</th>
						<th>Phone</th>
						<th>Email</th>
						<th>Ad Zips</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
						foreach($results as $row) {
							echo '<tr>';
								echo '<td>'.$row->business.'</td>';
								echo '<td>'.$row->address.' '.$row->city.' '.$row->state.'</td>';
								echo '<td>('.substr($row->phone, 0, 3).') '.substr($row->phone, 3, 3).'-'.substr($row->phone,6).'</td>';
								echo '<td>'.$row->email.'</td>';
								echo '<td>'.$row->ad_areas.'</td>';
								echo '<td>';
									echo '<div class="btn-group">';
										echo '<button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Action <span class="caret"></span></button>';
										echo '<ul class="dropdown-menu">';
											echo '<li><a href="'.base_url('n4radmin/edit-supply-house/'.$row->id).'"><i class="fa fa-fw fa-pencil"></i> Edit</a></li>';
											echo '<li><a href="'.base_url('n4radmin/supply-house-sample/'.$row->id).'"><i class="fa fa-fw fa-file-o"></i> View Sample</a></li>';
											echo '<li role="separator" class="divider"></li>';
											if($this->session->userdata('superadmin')==1) {
												echo '<li><a href="'.base_url('n4radmin/delete-supply-house/'.$row->id).'"><i class="fa fa-fw fa-times"></i> Delete</a></li>';
											}
											if(!empty($row->unique_name)) {
												echo '<li><a href="http://n4r.rentals/'.$row->unique_name.'" target="_blank"><i class="fa fa-fw fa-globe"></i> View Website</a></li>';
											}
										echo '</ul>';
									echo '</div>';
								echo '</td>';
							echo '</tr>';	
						}
					?>
						
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="text-center">
	<?php
		echo $links;
	?>
</div>