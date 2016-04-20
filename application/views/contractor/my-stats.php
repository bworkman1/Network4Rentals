<div class="row">
	<div class="col-sm-8">
		<h3><i class="fa fa-bar-chart fa-fw text-success"></i> My Post Stats</h3>
	</div>
	<div class="col-sm-4">
		<br>
		<?php
			if(!empty($my_zips)) {
				echo form_open('contractors/stats');
				$services_array = array(1=>'Appliance Repair', 2=>'Carpentry', 3=>'Concrete', 4=>'Drain Cleaning (Clogged Drain)', 5=>'Doors And Windows', 6=>'Electrical', 7=>'Heating And Cooling', 8=>'Lawn And Landscape', 9=>'Mold Removal', 10=>'Plumbing', 11=>'Painting', 12=>'Roofing', 13=>'Siding'); 
				echo '<select class="form-control" name="select_ad" onchange="this.form.submit()">';
				$count=0;
				foreach($my_zips as $key => $val) {
					$service_num = $val->service_purchased;
					if($count == $_POST['select_ad']) {
						echo '<option selected="selected" value="'.$count.'">'.$val->zip_purchased.' | '.$services_array[$service_num].'</option>';
					} else {
						echo '<option value="'.$count.'">'.$val->zip_purchased.' | '.$services_array[$service_num].'</option>';
					}
					$count++;
				}
				echo '</select>';
				echo form_close();
			}
		?>
	</div>
</div>
<hr>
<?php if($page_setting) { // Show Ad Stats ?>
	<?php if(!empty($my_zips)) { // Show Ad Stats ?>
		<table id="source" style="display: none;">
			<!--<caption>Post Stats For 43023 | Appliance Repair</caption>-->
			<thead>
				<tr>
					<th></th>
					<th>Web Page Visits</th>
					<th><i class="fa fa-question"></i> Post Viewed</th>
					<th>Posts Clicked On</th>	
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Ad Stats</th>
					<?php if(isset($_POST['select_ad'])) { ?>
						<td><?php echo $my_zips[$_POST['select_ad']]->visits; ?></td>
						<td><?php echo $my_zips[$_POST['select_ad']]->impressions; ?></td>
						<td><?php echo $my_zips[$_POST['select_ad']]->clicks; ?></td>
					<?php } else { ?>
						<td><?php echo $my_zips[0]->visits; ?></td>
						<td><?php echo $my_zips[0]->impressions; ?></td>
						<td><?php echo $my_zips[0]->clicks; ?></td>
					<?php } ?>
				</tr>
			</tbody>
		</table>
		<div id="target" style="width: 100%; height: 400px">
		<?php if(isset($_POST['select_ad'])) { ?>
			<a href="<?php echo base_url(); ?>contractors/edit-post/<?php echo $my_zips[$_POST['select_ad']]->id; ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-pencil"></i> Edit This Post</a>
		<?php } else { ?>
			<a href="<?php echo base_url(); ?>contractors/edit-post/<?php echo $my_zips[0]->id; ?>" class="btn btn-success btn-sm pull-right"><i class="fa fa-pencil"></i> Edit This Post</a>
		<?php } ?>
	<?php } else { ?>
		<p>You don't have any active subscriptions, running right now. Once you add a subscription to our services you will be able to see stats about your ads and how many page visitors you have in real time.</p><a href="<?php echo base_url(); ?>contractors/add-zip-codes" style="margin-top: 5px" class="btn btn-success btn-sm"><i class="fa fa-map-marker"></i> Add Zips/Services</a>
	<?php } ?>
</div>


<?php } else { //else show warning ?>
	<div class="row">	
		<div class="col-xs-1">
			<i class="fa fa-exclamation-triangle text-danger fa-3x"></i>
		</div>
		<div class="col-xs-11">
			<p>You have not set up your personal web page in our system yet. Once you add your details and create your personal page your ads will display and your stats for your post will be shown below.</p>
		</div>
	</div>
<?php } ?>