<h2><i class="fa fa-link text-primary"></i></i> Public Page Settings</h2>
<hr>
<?php
	if(!empty($info->image)) {
		echo '<div class="row">';
			echo '<div class="col-sm-4">';
				echo '<br>';
				echo '<img src="'.base_url().'public-images/'.$info->image.'" alt="alt text goes here" class="img-responsive">';
			echo '</div>';
			echo '<div class="col-sm-8">';
				echo '<h2>'.$info->bName.'</h2>';
				echo '<p>'.$info->desc.'</p>';
			echo '</div>';
		echo '</div>';
	} else {
		echo '<div class="row">';
			echo '<div class="col-sm-12">';
				echo '<h2>'.$info->bName.'</h2>';
				echo '<p>'.$info->desc.'</p>';
			echo '</div>';
		echo '</div>';
	}
?>
<div class="row">
	<div class="col-sm-4">
		<h4 class="text-center">Connect With Us:</h4>
		<div class="list-group text-center landlord-social-icons">
			<?php 
				if(!empty($info->facebook)) {
					echo '<a class="list-group-item facebook" href="'.$info->facebook.'" target="_blank"><i class="fa fa-facebook"></i></a>';
				}
				if(!empty($info->twitter)) {
					echo '<a class="list-group-item twitter" href="'.$info->twitter.'" target="_blank"><i class="fa fa-twitter"></i></a>';
				}
				if(!empty($info->google)) {
					echo '<a class="list-group-item google" href="'.$info->google.'" target="_blank"><i class="fa fa-google-plus"></i></a>';
				}
				if(!empty($info->linkedin)) {
					echo '<a class="list-group-item linkedin" href="'.$info->linkedin.'" target="_blank"><i class="fa fa-linkedin" ></i></a>';
				}
				if(!empty($info->youtube)) {
					echo '<a class="list-group-item youtube" href="'.$info->youtube.'" target="_blank"><i class="fa fa-youtube"></i></a>';
				}
			?>
		</div>
	</div>
	<div class="col-sm-8">
		<b>Contact Name:</b>
		<p><?php echo $info->name; ?></p>
		<div class="row">
			<div class="col-sm-6">
				<?php 
					
				?>
				<p><b>Address: </b><?php echo $info->address; ?> <?php echo $info->city; ?> <?php echo $info->state; ?></p>
			</div>
			<div class="col-sm-6">
				<p><b>Phone: </b><?php echo $info->phone; ?></p>
			</div>
		</div>
	</div>
</div>
	<?php
		if(!empty($results)) {
			echo '<hr>
				<h3><i class="fa fa-home text-primary"></i> Our Available Rentals</h3>
				<ul class="landlord-page-listings">';
		}
		foreach($results as $key => $val) {
			if($val->active == 'y') {
				echo '<li>';
					if(!empty($val->img_show)) {
						echo '<img src="'.base_url().'listing-images/'.$val->img_show.'" alt="alt text goes here" class="pull-left" width="75px" height="75px" alt="'.$val->title.'">';
					} else {
						echo '<img src="http://placehold.it/70x70" alt="alt text goes here" class="pull-left">';
					}
					echo '<h4>'.$val->address.', '.$val->city.' '.$val->stateAbv.'</h4>';
					echo '<a href="" class="btn btn-primary btn-xs pull-right"><i class="fa fa-link"></i> View</a>';
					echo '<p>'.$val->title.'<br>';
						echo '<span class=""><b>Bedrooms:</b> '.$val->bedrooms.'</span> ';
						echo ' <span class=""><b>Bathrooms:</b> '.$val->bathrooms.'</span>';
					echo '</p>';
				echo '</li>';
			}
		}
		if(!empty($results)) {
			echo '</ul>';
		}
	?>


<div class="text-center">
	<?php echo $links; ?>
</div>