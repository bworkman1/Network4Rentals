<div class="panel panel-success">
	<div class="panel-heading">
		<h3 style="margin: 0; color: #fff;"><i class="fa fa-gears"></i> My Account</h3>
	</div>
	<div class="panel-body">

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
		<ul class="nav nav-tabs" role="tablist">
			<li class="active">
				<a href="#profile" role="tab" data-toggle="tab"><i class="fa fa-user"></i> Profile</a>
			</li>
			<li>
				<a href="#password" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> Password</a>
			</li>
			<li  style="display: none">
				<a href="#updates" role="tab" data-toggle="tab"><i class="fa fa-code-fork"></i> Updates</a>
			</li>
			<li>
				<a href="#subscription" role="tab" data-toggle="tab"><i class="fa fa-calendar"></i> Subscription</a>
			</li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade in active" id="profile">
				<h3 class="highlight"><i class="fa fa-user"></i> Update Profile</h3>
				<hr>
				<?php echo form_open('contractor/update-personal-info', array('id'=>$updateProfile)); ?>
					<label class="control-label" for="bName"><span class="text-danger">*</span> Business Name</label>
					<input id="bName" name="bName" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="70"  tabindex="1" value="<?php echo $profile->bName; ?>">
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="first_name"><span class="text-danger">*</span> Contact First Name</label>  
							<input id="first_name" name="first_name" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="50"  tabindex="1" value="<?php echo $profile->f_name; ?>">
							<label class="control-label" for="address"><span class="text-danger">*</span> Address</label>  
							<input id="address" name="address" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="150" tabindex="3" value="<?php echo $profile->address; ?>">
							<div class="row">
								<div class="col-sm-8">
									<label class="control-label" for="state"><span class="text-danger">*</span> State</label>  
									<?php
										$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
										echo '<select id="state" name="state" class="form-control" required="" tabindex="4">';
										echo '<option value="">Select One...</option>';
										foreach($states as $key => $val) {
											if($key == $profile->state) {
												echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';	
											} else {
												echo '<option value="'.$key.'" '.set_select('state', $key).'>'.$val.'</option>';	
											}
										}
										echo '</select>';
									?>
								</div>
								<div class="col-sm-4">
									<label class="control-label" for="zip"><span class="text-danger">*</span> Zip</label> 
									<input id="zip" name="zip" type="text" placeholder="" autocomplete="off" class="form-control input-md numbersOnly" required="" maxlength="5" tabindex="5" value="<?php echo $profile->zip; ?>">
								</div>
							</div>					
							<label class="control-label" for="email"><span class="text-danger">*</span> Email</label>
							<input id="email" name="email" type="text" placeholder="" autocomplete="off" class="form-control input-md" required="" maxlength="70"  tabindex="1" value="<?php echo $profile->email; ?>">
						</div>
						<div class="col-sm-6">
							<label class="control-label" for="last_name"><span class="text-danger">*</span> Contact Last Name</label>  
							<input id="last_name" name="last_name" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="50" tabindex="2" value="<?php echo $profile->l_name; ?>">
							<label class="control-label" for="city"><span class="text-danger">*</span> City</label>  
							<input id="city" name="city" autocomplete="off" type="text" placeholder="" class="form-control input-md" required="" maxlength="70" tabindex="3" value="<?php echo $profile->city; ?>"> 	
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="phone"><span class="text-danger">*</span> Phone</label>  
									<input id="phone" autocomplete="off" name="phone" type="text" placeholder="" class="form-control input-md phone" maxlength="20" tabindex="6" required="" value="<?php echo "(".substr($profile->phone, 0, 3).") ".substr($profile->phone, 3, 3)."-".substr($profile->phone,6); ?>">
								</div>
								<div class="col-sm-6">
									<label class="control-label" for="fax">Fax</label>  
									<input id="fax" name="fax" type="text" autocomplete="off" placeholder="" class="form-control input-md phone" maxlength="20" tabindex="7" value="<?php echo "(".substr($profile->fax, 0, 3).") ".substr($profile->fax, 3, 3)."-".substr($profile->fax,6); ?>">
								</div>
							</div>		
							<br>
							<div class="well well-sm">
								<div class="row">
									<div class="col-sm-6">
										<label class="control-label" for="cell">Recieve Text Messages? <i class="fa fa-question text-success" data-placement="left" data-toggle="tooltip" title="Enter a cell phone number to receive text messages as you receive service requests"></i></label>  
										<input id="cell" autocomplete="off" name="cell" type="text" placeholder="" class="form-control input-md phone" maxlength="20" tabindex="8" value="<?php echo "(".substr($profile->cell, 0, 3).") ".substr($profile->cell, 3, 3)."-".substr($profile->cell,6); ?>">
										<small><em>Enter a cell phone number</em></small>
									</div>
									<div class="col-sm-6">
										 <p><small>Enter a cell phone number to receive text messages as you receive service requests. This information is not made public.</small></p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<hr>
					<legend>Billing Info</legend>
					<div class="row">
						<div class="col-sm-6">
							<label class="control-label" for="baddress"><span class="text-danger">*</span> Billing Address</label>  
							<input id="baddress" name="baddress" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="150" tabindex="9" value="<?php echo $profile->baddress; ?>">
							<div class="row">
								<div class="col-sm-6">
									<label class="control-label" for="bcity"><span class="text-danger">*</span> City</label>  
									<input id="bcity" name="bcity" type="text" autocomplete="off" placeholder="" class="form-control input-md" required="" maxlength="70" tabindex="10" value="<?php echo $profile->bcity; ?>">
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8">
									<label class="control-label" for="bstate"><span class="text-danger">*</span> State</label>  
									<?php
										$states = array( 'AL'=>"Alabama", 'AK'=>"Alaska", 'AZ'=>"Arizona", 'AR'=>"Arkansas", 'CA'=>"California", 'CO'=>"Colorado", 'CT'=>"Connecticut", 'DE'=>"Delaware", 'DC'=>"District Of Columbia", 'FL'=>"Florida", 'GA'=>"Georgia", 'HI'=>"Hawaii", 'ID'=>"Idaho", 'IL'=>"Illinois", 'IN'=>"Indiana", 'IA'=>"Iowa", 'KS'=>"Kansas", 'KY'=>"Kentucky", 'LA'=>"Louisiana", 'ME'=>"Maine", 'MD'=>"Maryland", 'MA'=>"Massachusetts", 'MI'=>"Michigan", 'MN'=>"Minnesota", 'MS'=>"Mississippi", 'MO'=>"Missouri", 'MT'=>"Montana", 'NE'=>"Nebraska", 'NV'=>"Nevada", 'NH'=>"New Hampshire", 'NJ'=>"New Jersey", 'NM'=>"New Mexico", 'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",'OK'=>"Oklahoma",'OR'=>"Oregon",'PA'=>"Pennsylvania",'RI'=>"Rhode Island",'SC'=>"South Carolina",'SD'=>"South Dakota",'TN'=>"Tennessee",'TX'=>"Texas",'UT'=>"Utah",'VT'=>"Vermont",'VA'=>"Virginia",'WA'=>"Washington",'WV'=>"West Virginia",'WI'=>"Wisconsin",'WY'=>"Wyoming");
										echo '<select id="bstate" name="bstate" class="form-control" required="" tabindex="11">';
										echo '<option value="">Select One...</option>';
										foreach($states as $key => $val) {
											if($key == $profile->state) {
												echo '<option selected="selected" value="'.$key.'">'.$val.'</option>';	
											} else {
												echo '<option value="'.$key.'">'.$val.'</option>';	
											}
										}
										echo '</select>';
									?>
								</div>
								<div class="col-sm-4">
									<label class="control-label" for="bzip"><span class="text-danger">*</span> Zip</label> 
									<input id="bzip" name="bzip" autocomplete="off" type="text" placeholder="" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="11" value="<?php echo $profile->bzip; ?>">
								</div>
							</div>
						</div>
					</div>
					<br>
					<button type="submit" class="btn btn-success btn-lg"><i class="fa fa-save"></i> Save</button>
				<?php echo form_close(); ?>
				<br>
			</div>
			<div class="tab-pane fade" id="password">
				<h3 class="highlight"><i class="fa fa-key"></i> Change Your Password</h3>
				<hr>
				<?php echo form_open('contractor/update-password', array('id'=>'passwordUpdate')); ?>
					<div class="row">
						<div class="col-sm-6">
							
							<label class="control-label" for="password"><span class="text-danger">*</span> New Password:</label>  
							<input id="pwd" name="password" type="password" autocomplete="off" class="form-control input-md" required="" maxlength="20">
							<label class="control-label" for="password2"><span class="text-danger">*</span> Confirm New Password:</label>  
							<input id="pwd2" name="password_2" autocomplete="off" type="password" class="form-control input-md" required="" maxlength="30">	
							<div class="password-error-text"></div>
						</div><!--end left side -->
					</div>
					<br>
					<button type="submit" class="btn btn-success btn-lg changePass"><i class="fa fa-save"></i> Save</button>
					<br>
				<?php echo form_close(); ?>
			</div>
			<div class="tab-pane fade" style="display: none" id="updates">
				<?php
					if(!empty($updates)) {
						foreach($updates as $key => $val) {
							echo '<div class="well">';
								echo '<h4>'.$val->title.'</h4>';
								echo '<p>'.$val->desc.'</p>';
								echo '<div class="label label-success"><b>Added:</b> '.date('m-d-Y', strtotime($val->update_date)).'</div>';
							echo '</div>';
						}
					} else {
						echo '<p>No News Yet, Check Back Later For Updates When They Are Posted</p>';
					}
				?>
			</div>
			<div class="tab-pane fade" id="subscription">
				<div class="row">
					<div class="col-sm-8">
						<h3 class="highlight"><i class="fa fa-calendar"></i> My Subscription</h3>				
					</div>
					<div class="col-sm-4 text-right">
						<br>
						<button class="btn btn-primary" data-toggle="modal" data-target="#updatePayment">Update My Credit Card</button>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<h4 class="highlight">Yearly Subscription Payments</h4>
						<div class="well">
							<?php
								echo '<div style="border-bottom: 1px solid #ccc">';
									echo '<div class="row">';
										echo '<div class="col-xs-2">';
											echo '<b>#</b>';
										echo '</div>';
										echo '<div class="col-xs-5 text-center">';
											echo '<b>Payment Date</b>';
										echo '</div>';
										echo '<div class="col-xs-5 text-right">';
											echo '<b>Amount</b>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
								$count = 1;
								foreach($subscription as $key => $val) {
									echo '<div class="row">';
										echo '<div class="col-xs-2">';
											echo $count;
										echo '</div>';
										echo '<div class="col-xs-5 text-center">';
											echo date('m-d-Y', strtotime($val->payment_date));
										echo '</div>';
										echo '<div class="col-xs-5 text-right">';
											echo '$'.number_format($val->amount, 2);
										echo '</div>';
									echo '</div>';
								}
								$count++;
								$year = date('Y', strtotime($val->payment_date));
								$monthDay = date('m-d', strtotime($val->payment_date));
								$nextPaymentDate = $monthDay.'-'.($year+1);
							?>
						</div>
						
					</div>
					<div class="col-md-6">
						<h4 class="highlight">Additional Purchases</h4>
						<div class="well">
							<?php
								echo '<div style="border-bottom: 1px solid #ccc">';
									echo '<div class="row">';
										echo '<div class="col-xs-2">';
											echo '<b>#</b>';
										echo '</div>';
										echo '<div class="col-xs-4 text-center">';
											echo '<b>Payment Date</b>';
										echo '</div>';
										echo '<div class="col-xs-1 text-right">';
											echo '<b>Type</b>';
										echo '</div>';
										echo '<div class="col-xs-5 text-right">';
											echo '<b>Amount</b>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
								$count = 1;
								foreach($add_ons as $key => $val) {
									echo '<div class="row">';
										echo '<div class="col-xs-2">';
											echo $count;
										echo '</div>';
										echo '<div class="col-xs-4 text-center">';
											echo date('m-d-Y', strtotime($val->ts));
										echo '</div>';
										echo '<div class="col-xs-1 text-center">';
											echo 'Ad';
										echo '</div>';
										echo '<div class="col-xs-5 text-right">';
											echo '$'.number_format($val->total, 2);
										echo '</div>';
									echo '</div>';
									$count++;
								}
								
								$year = date('Y', strtotime($val->payment_date));
								$monthDay = date('m-d', strtotime($val->payment_date));
								$nextPaymentDate = $monthDay.'-'.($year+1);
							?>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>




<div class="modal fade" id="updatePayment" tabindex="-1" role="dialog" aria-labelledby="updatePayment" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Credit Card Details</h4>
      </div>
      <div class="modal-body">
			<form class="form-horizontal" role="form" id="payment-details">
			<fieldset>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="card-holder-name">Name on Card</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="card-holder-name" id="card-holder-name" value="<?php echo $profile->f_name.' '.$profile->l_name; ?>" placeholder="Card Holder's Name">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="card-number">Card Number</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="card-number" id="card-number" placeholder="Debit/Credit Card Number">
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="expiry-month">Expiration Date</label>
        <div class="col-sm-9">
          <div class="row">
            <div class="col-xs-6">
              <select class="form-control col-sm-2" name="expiry-month" id="expiry-month">
                <option>Month</option>
                <option value="01">Jan (01)</option>
                <option value="02">Feb (02)</option>
				<option value="03">Mar (03)</option>
                <option value="04">Apr (04)</option>
                <option value="05">May (05)</option>
                <option value="06">June (06)</option>
                <option value="07">July (07)</option>
                <option value="08">Aug (08)</option>
                <option value="09">Sep (09)</option>
                <option value="10">Oct (10)</option>
                <option value="11">Nov (11)</option>
                <option value="12">Dec (12)</option>
              </select>
            </div>
            <div class="col-xs-6">
              <select class="form-control" name="expiry-year">
				<?php
					for($i=0;$i<8;$i++) {
						$d = date('y')+$i;
						$date = date('Y')+$i;
						echo '<option value="'.$d.'">'.$date.'</option>';
					}
				?>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-3 control-label" for="cvv">Card CVV</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" name="cvv" id="cvv" placeholder="Security Code">
        </div>
      </div>
    </fieldset>
  </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Update Credit Card</button>
      </div>
    </div>
  </div>
</div>