<div class="row">
	<div class="col-sm-12">
		<h2><i class="fa fa-user text-primary"></i> Landlord Info</h2>
	</div>
</div>
<hr>
<?php if(!empty($info['bName'])) { ?>
	<h3><?php echo $info['bName']; ?></h3>
<?php } ?>
<div class="row">
	<div class="col-sm-4">
		<p><b>Contact Name:</b><br> <?php echo $info['name']; ?></p>
		<p><b>Address:</b> <br>
			<?php echo $info['address'].'<br> '.$info['city'].', '.$info['state'].' '.$info['zip']; ?>
		</p>
		<p><b>Phone:</b><br> <?php echo "(".substr($info['phone'], 0, 3).") ".substr($info['phone'], 3, 3)."-".substr($info['phone'],6);; ?></p>
	</div>
	<div class="col-sm-4">
		<p><b>Email:</b><br> <?php echo $info['email']; ?></p>
		<?php if(!empty($info['alt_phone'])) { ?>
			<p><b>Alt Phone:</b><br> <?php echo "(".substr($info['alt_phone'], 0, 3).") ".substr($info['alt_phone'], 3, 3)."-".substr($info['alt_phone'],6);; ?></p>
		<?php } ?>
	</div>
	
	<div class="col-sm-4">
		<?php if(!empty($info['profile_img'])) { ?>
			<img src="<?php echo base_url().'public-images/'.$info['profile_img']; ?>" class="img-responsive">
		<?php } ?>
	</div>
</div>
<hr>