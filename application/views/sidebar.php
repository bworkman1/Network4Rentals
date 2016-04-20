<?php
	
	if(!empty($result)) {
		echo '<ul class="sponsoredList">';
		foreach($result as $key => $val) {
			if(count($val)>0) {
				echo '<li class="text-center well well-sm">';
					echo '<a href="'.base_url().'advertisers/post-clicked-on/'.$val->advertiser_id.'/'.$val->advertiser_zips_id.'" target="_blank">';
						if(!empty($val->title)) {
							echo '<h4><b>'.htmlentities($val->title).'</b></h4>';
						}
						if(!empty($val->desc)) {
							echo '<p>'.htmlentities($val->desc).'</p>';
						}
						if(!empty($val->ad_image)) {
							echo '<img class="img-responsive sponsorLogo" src="'.base_url($val->ad_image).'">';
						}
						if(!empty($val->bName)) {
							echo '<div class="bottomBoxAd">';
								echo '<b>'.$val->bName.'</b><br>';
								echo "(".substr($val->phone, 0, 3).") ".substr($val->phone, 3, 3)."-".substr($val->phone,6);
							echo '</div>';
						}
					echo '</a>';
				echo '</li>';
			}
		}
		echo '</ul>';
	}
?>