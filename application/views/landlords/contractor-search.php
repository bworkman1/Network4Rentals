<div class="row">
	<div class="col-sm-6">
		<h2><i class="fa fa-search text-primary"></i> Searched Contractor</h2>
	</div>
	<div class="col-sm-6 text-right">
		<br>
		<?php
			foreach($searched as $key=>$val) {
				if($key=='service') {
					$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
					echo '<div class="badge badge-primary" style="margin-right: 5px;"><b>'.ucwords($key).'</b>:'.$services_array[$val].'</div>';
				} else {
					echo '<div class="badge badge-primary" style="margin-right: 5px;"><b>'.ucwords($key).'</b>:'.$val.'</div>';
				}
			}
		?>	
	</div>
</div>
<hr>
<?php
	if(validation_errors() != '') 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.validation_errors().'</div>';
	}
	if($this->session->flashdata('error')) 
	{
		echo '<br><div class="alert alert-danger"><h4>Error:</h4>'.$this->session->flashdata('error').'.</div>';
	}
	if(!empty($results)) {	
		echo '<ul class="search-contractors-list">';
			foreach($results as $key => $val) {
				
					echo '<li>';
						echo '<div class="row">';
							echo '<div class="col-sm-2">';	
								if(!empty($val->image)) {
									echo '<img src="'.base_url().'/public-images/'.$val->image.'" class="img-responsive">';
								}
							echo '</div>';
							echo '<div class="col-sm-8">';
								echo '<h4>'.htmlentities($val->bName).'</h4><p>'.htmlentities(substr($val->desc, 0, 40)).'</p>';
							echo '</div>';
							echo '<div class="col-sm-2">';
								echo '<br><a href="http://n4r.rentals/'.htmlentities($val->unique_name).'" target="_blank" class="btn btn-sm btn-primary btn-block"><i class="fa fa-link"></i> Visit</a>';
							echo '</div>';
						echo '</div>';
					echo '</li>';
				
			}
		echo '</ul>';
		echo '<div class="text-center">';
			echo '<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search"><i class="fa fa-search"></i> Search Again</button>';
		echo '</div>';
	} else {
		echo '<div class="row well well-sm">';
			echo '<div class="col-sm-9">';
				echo '<h3 class="text-danger">No Results Found</h3>';
				echo '<p>Were sorry, at this time we don\'t have any contractors registered with us around <b>'.$searched['zip'].'</b>. Try searching again and widening your search.</p><br>';
				echo '<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#search"><i class="fa fa-search"></i> Search Again</button>';
			echo '</div>';
		echo '</div>';
	}
	
?>
<div class="modal fade" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		 <?php echo form_open('landlords/contractor-search'); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-search text-primary"></i> Search Again</h4>
      </div>
      <div class="modal-body">
       
					<div class="row">
						<div class="col-sm-2 text-right">
							<label><span class="text-danger">*</span> Zip:</label>
						</div>
						<div class="col-sm-5">
							<input type="text" name="zip" value="<?php echo $_POST['zip']; ?>" class="form-control" maxlength="5">
						</div>
						<div class="col-sm-5">
							<select name="radius" class="form-control">
								<option value="5">5 Mile Radius</option>
								<option value="10">10 Mile Radius</option>
								<option value="20">20 Mile Radius</option>
								<option value="30">30 Mile Radius</option>
								<option value="50">50 Mile Radius</option>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-sm-2 text-right">
							<label><span class="text-danger">*</span> Service:</label>
						</div>
						<div class="col-sm-10">
							<?php 
								$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
								echo "<select name='serviceType' id='serviceType' class='form-control' required='required'>";
								echo '<option value="">Choose One...</option>';
								foreach($services_array as $key => $val) {
									echo '<option value="'.$key.'">'.$val.'</option>';
								}
								echo "</select>";
							?>
						</div>
					</div>				
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> Search</button>
      </div>
	  <?php echo form_close(); ?>
    </div>
  </div>
</div>
