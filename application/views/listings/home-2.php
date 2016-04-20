<div id="listing-intro" class="text-center">
	<h1 class="wow zoomInUp">Finding Your Next Rental Home<br>Could Be One Search Away</h1>
	<h3 class="label label-primary wow zoomIn" data-wow-delay="1s">Start Your Search Below</h3>
</div>

<div id="search-area">
	<div class="container-fluid wow flash" data-wow-delay="2s">
		<?php echo form_open('listings/search/'); ?>
			<div class="row">
				<div class="col-md-2 col-sm-4 col-xs-6">
					<input type="text" name="zipcode" maxlength="5" class="form-control input-lg" value="<?php echo $this->session->userdata('zip'); ?>" required placeholder="Zip Code">
				</div>
				<div class="col-md-2 col-sm-4 col-xs-6">
					<select name="distance" class="form-control input-lg drop-select">
						<option value="">Search Radius</option>
						<?php 
							$radiusSelected = $this->session->userdata('distance');
							$radiusOptions = array('5', '10', '15', '25', '50');
							foreach($radiusOptions as $val) {
								if($radiusSelected == $val) {
									echo '<option value="'.$val.'" selected>'.$val.' Mile Radius</option>';
								} else {
									echo '<option value="'.$val.'">'.$val.' Mile Radius</option>';
								}
							}
						?>							
					</select>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-6">
					<select name="beds" class="form-control input-lg">
						<option value="">Any Bedrooms</option>
						<?php 
							$bedsSelected = $this->session->userdata('beds');
							for($i=1;$i<6;$i++) {
								if($bedsSelected == $i) {
									echo '<option value="'.$i.'" selected>'.$i.'+ Bedrooms</option>';
								} else {
									echo '<option value="'.$i.'">'.$i.'+ Bedrooms</option>';
								}
							}
						?>			
					</select>
				</div>
				<div class="col-md-2 col-sm-4 col-xs-6">
					<select name="baths" class="form-control input-lg">
						<option value="">Any Bathrooms</option>
						<?php 
							$bathsSelected = $this->session->userdata('baths');
							for($i=1;$i<6;$i++) {
								if($bathsSelected == $i) {
									echo '<option value="'.$i.'" selected>'.$i.'+ Bathrooms</option>';
								} else {
									echo '<option value="'.$i.'">'.$i.'+ Bathrooms</option>';
								}
							}
						?>		
					</select>
				</div>		
				<div class="col-md-2 col-sm-4 col-xs-6 text-center">
					<a href="#" id="more-filters"><i class="fa fa-caret-down fa-2x"></i> <span class="label label-success"></span> More Filters</a>
				</div>	
				<div class="col-md-2 col-sm-4 col-xs-6">
					<button type="submit" class="btn btn-lg btn-info btn-block"><i class="fa fa-search"></i> Search</button>
				</div>					
			</div>
			
			<div id="filtered-options">
				<hr>
				<?php
					$listingOptions = array('laundry_hook_ups', 'off_site_laundry', 'on_site_laundry', 'basement', 'shopping', 'single_lvl', 'shed', 'park', 'city', 'outside_city', 'deck_porch', 'large_yard', 'fenced_yard', 'partial_utilites', 'all_utilities', 'appliances', 'furnished', 'pool');
					$newArray = array();
					foreach($listingOptions as $val) {
						$newArray[$val] = $this->session->userdata($val);
					}
				?>
				<div class="row">
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="checkbox">
							<label for="amenities-3"><input type="checkbox" <?php if($newArray['laundry_hook_ups'] == 'y') {echo "checked";} ?> name="laundry_hook_ups" id="amenities-3" value="y" /> Clothes Washer / Dryer Hook-Ups</label>
						</div>
						<div class="checkbox">
							<label for="amenities-5"><input type="checkbox" <?php if($newArray['off_site_laundry'] == 'y') {echo "checked";} ?> name="off_site_laundry" id="amenities-5" value="y" /> Offsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-6"><input type="checkbox" <?php if($newArray['on_site_laundry'] == 'y') {echo "checked";} ?> name="on_site_laundry" id="amenities-6" value="y" /> Onsite Laundry</label>
						</div>
						<div class="checkbox">
							<label for="amenities-7"><input type="checkbox" <?php if($newArray['basement'] == 'y') {echo "checked";} ?> name="basement" id="amenities-7" value="y" /> Basement</label>
						</div>
						<div class="checkbox">
							<label for="amenities-11"><input type="checkbox" <?php if($newArray['shopping'] == 'y') {echo "checked";} ?> name="shopping" id="amenities-11" value="y" /> Near Shopping / Entertainment</label>
						</div>
					</div>
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="checkbox">
							<label for="amenities-8"><input type="checkbox" <?php if($newArray['single_lvl'] == 'y') {echo "checked";} ?> name="single_lvl" id="amenities-8" value="y" /> Single Level Floor Plan</label>
						</div>
						<div class="checkbox">
							<label for="amenities-9"><input type="checkbox" <?php if($newArray['shed'] == 'y') {echo "checked";} ?> name="shed" id="amenities-9" value="y" /> Storage Shed</label>
						</div>
						<div class="checkbox">
							<label for="amenities-10"><input type="checkbox" <?php if($newArray['park'] == 'y') {echo "checked";} ?> name="park" id="amenities-10" value="y" /> Near A Park</label>
						</div>	
						<div class="checkbox">
							<label for="amenities-12"><input type="checkbox" <?php if($newArray['city'] == 'y') {echo "checked";} ?> name="city" id="amenities-12" value="y" /> Within City Limits</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-13"><input type="checkbox" <?php if($newArray['outside_city'] == 'y') {echo "checked";} ?> name="outside_city" id="amenities-13" value="y" /> Outside City Limits</label>
						</div>		
					</div>
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="checkbox">
							<label for="amenities-14"><input type="checkbox" <?php if($newArray['deck_porch'] == 'y') {echo "checked";} ?> name="deck_porch" id="amenities-14" value="y" /> Deck / Porch</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-15"><input type="checkbox" <?php if($newArray['large_yard'] == 'y') {echo "checked";} ?> name="large_yard" id="amenities-15" value="y" /> Large Yard</label>
						</div>
						<div class="checkbox">
							<label for="amenities-16"><input type="checkbox" <?php if($newArray['fenced_yard'] == 'y') {echo "checked";} ?> name="fenced_yard" id="amenities-16" value="y" /> Fenced Yard</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-17"><input type="checkbox" <?php if($newArray['partial_utilites'] == 'y') {echo "checked";} ?> name="partial_utilites" id="amenities-17" value="y" /> Some Utilities Included</label>
						</div>	
					</div>
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="checkbox">
							<label for="amenities-18"><input type="checkbox" <?php if($newArray['all_utilities'] == 'y') {echo "checked";} ?> name="all_utilities" id="amenities-18" value="y" /> Utilities Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-19"><input type="checkbox" <?php if($newArray['appliances'] == 'y') {echo "checked";} ?> name="appliances" id="amenities-19" value="y" /> Appliances Included</label>
						</div>		
						<div class="checkbox">
							<label for="amenities-20"><input type="checkbox" <?php if($newArray['furnished'] == 'y') {echo "checked";} ?> name="furnished" id="amenities-20" value="y" /> Fully Furnished </label>
						</div>		
						<div class="checkbox">
							<label for="amenities-21"><input type="checkbox" <?php if($newArray['pool'] == 'y') {echo "checked";} ?> name="pool" id="amenities-21" value="y" /> Pool</label>
						</div>
						<button id="clearFilters" class="btn btn-sm btn-danger pull-right">Uncheck All</button>
					</div>
				</div>
			</div>
			
		</form>
	</div>
</div>
