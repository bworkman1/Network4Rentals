<h3><i class="fa fa-gears text-primary"></i> My Account</h3>
<hr>
<?php

if($this->session->flashdata('error'))
{
    echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:</b> '.$this->session->flashdata('error').'.</div>';
}
if($this->session->flashdata('success'))
{
    echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$this->session->flashdata('success').'.</div>';
}


	
?>
<ul class="nav nav-tabs" role="tablist">
    <li class="active">
        <a href="#profile" role="tab" data-toggle="tab"><i class="fa fa-user"></i> Profile</a>
    </li>
    <li>
        <a href="#password" role="tab" data-toggle="tab"><i class="fa fa-lock"></i> Password</a>
    </li>
    <li>
        <a href="#updates" role="tab" data-toggle="tab"><i class="fa fa-code-fork"></i> Updates</a>
    </li>
    <!--<li>
        <a href="#subscription" role="tab" data-toggle="tab"><i class="fa fa-calendar"></i> Subscription</a>
    </li>-->
</ul>
<br>
<div class="tab-content">
    <div class="tab-pane fade in active" id="profile">
        <h4><i class="fa fa-user text-primary"></i> Profile</h4>
        <hr>
        <?php echo form_open('local-partner/my-account/update'); ?>
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
                        <input id="phone" autocomplete="off" name="phone" type="text" placeholder="" class="form-control input-md phones" maxlength="20" tabindex="6" required="" value="<?php echo "(".substr($profile->phone, 0, 3).") ".substr($profile->phone, 3, 3)."-".substr($profile->phone,6); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label class="control-label" for="fax">Fax</label>
                        <input id="fax" name="fax" type="text" autocomplete="off" placeholder="" class="form-control input-md phones" maxlength="20" tabindex="7" value="<?php echo "(".substr($profile->fax, 0, 3).") ".substr($profile->fax, 3, 3)."-".substr($profile->fax,6); ?>">
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
        <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Save</button>
        <?php echo form_close(); ?>
        <br>
    </div>
    <div class="tab-pane fade" id="password">
        <h4><i class="fa fa-key text-primary"></i> Change Password</h4>
        <hr>
        <?php echo form_open('local-partner/my-account/password'); ?>
        <div class="row">
            <div class="col-sm-6">
                <label class="control-label" for="password"><span class="text-danger">*</span> Password:</label>
                <input id="pwd" name="password" type="password" autocomplete="off" class="form-control input-md" required="" maxlength="20">
                <div class="password-error-text"></div>
            </div><!--end left side -->
            <div class="col-sm-6">
                <label class="control-label" for="password2"><span class="text-danger">*</span> Confirm Password:</label>
                <input id="pwd2" name="password_2" autocomplete="off" type="password" class="form-control input-md" required="" maxlength="30">
            </div><!--end right side -->
        </div>
        <br>
        <button type="submit" class="btn btn-primary btn-sm changePass"><i class="fa fa-save"></i> Save</button>
        <br>
        <?php echo form_close(); ?>
    </div>
    <div class="tab-pane fade" id="updates">
        <h4><i class="fa fa-code-fork text-primary"></i> Recent Updates</h4>
        <hr>
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
            <div class="col-sm-6">
                <h4><i class="fa fa-calendar text-primary"></i> My Subscription</h4>
            </div>
            <div class="col-sm-6 text-right">
                <?php if($subscription==false) { ?>
                    <a href="<?php echo base_url('local-partner/add-zip-codes'); ?>" style="margin-top: 5px" class="btn btn-primary btn-sm"><i class="fa fa-map-marker"></i> Add Zips/Services</a>
                <?php } ?>
            </div>
        </div>
        <hr>
        <?php
			if($subscription==false) {
				echo '<div class="well well-sm">';
				echo '<h4><i class="fa fa-exclamation-triangle text-danger"></i> You Have No Active Subscriptions</h4>';
				echo '<p>To add more zip codes to your account click the link above that says "Add Zips/Services".</p>';
				echo '</div>';
				echo '<hr>';
			} else {
				echo '<div class="well">';
				echo '<p><i class="fa fa-bullhorn fa-fw text-primary"></i> Currently hold  <b>'.count($subscription).'</b> premium ads</p>';
				echo '<pre>';
					var_dump($payment);
				echo '</pre>';
				switch($payment->payment_frequency)
				{
					case "1":         $months = 1; break;
					case "3":       $months = 3; break;
					case "6":   $months = 6; break;
					case "12":        $months = 12; break;
					default:                $months = 1; break;
				}
				$today = $payment->payment_date;
				$next_due_date = strtotime($today.' + '.$months.' Months');
				echo '<p><i class="fa fa-calendar fa-fw text-primary"></i> Next Billing Date: <b>'.date('m-d-Y', $next_due_date).'</b></p>';
				echo '<p><i class="fa fa-credit-card fa-fw text-primary"></i> Credit Card: <b>****-****-****-'.$payment->last_4.'</b> <small> | Expires On </small>'.date('m-Y', strtotime($payment->expires)).'</br>';
				if(strtolower($status['status']) == 'active') {	//This is supposed to contain either (active, expired, suspended, cancelled, terminated) ok set for testing purposes
					echo '<label class="label label-success pull-right">'.$status['status'].'</label>';
				} else {
					echo '<label class="label label-danger pull-right">'.$status['status'].'</label>';
				}
				echo '</div>';
				
				echo '<hr>';
				echo '<br>';
				echo '<a href="#billing_info" role="tab" data-toggle="tab" class="btn btn-primary btn-sm"><i class="fa fa-credit-card"></i> Edit Billing Info</a>';
			}
        ?>

    </div>
    <div class="tab-pane fade" id="billing_info">
        <?php echo form_open('advertisers/my-account'); ?>
        <div class="row">
            <div class="col-sm-6">
                <h4><i class="fa fa-credit-card text-primary"></i> Billing Info</h4>
            </div>
            <div class="col-sm-6 text-right">
                <br>
                <?php
                if(strtolower($status['status']) == 'active') {	//This is supposed to contain either (active, expired, suspended, cancelled, terminated) ok set for testing purposes
                    echo '<label class="label label-success">'.$status['status'].'</label>';
                } else {
                    echo '<label class="label label-danger">'.$status['status'].'</label>';
                }
                ?>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-6">
                <div class="well updateCreditCardData">
                    <?php echo form_open('advertisers/my_account'); ?>
                    <legend><i class="fa fa-credit-card"></i> Update Credit Card Number</legend>
                    <label class="control-label" for="credit_card"><span class="text-danger">*</span> Credit Card:</label>
                    <input id="checkout_card_number" name="credit_card" autocomplete="off" type="text" placeholder="1234-5678-9012-3456" class="form-control input-md input-text numbersOnly" required="" data-stripe="number" maxlength="19" tabindex="11" value="<?php echo set_value('credit_card'); ?>">
                    <div class="cc_helper"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Month:</label>
                            <select id="exp-month" name="exp_month" class="form-control input-md" required="" maxlength="2" tabindex="12">
                                <option value="">Month</option>
                                <?php
                                for($i=1;$i<13;$i++) {
                                    echo '<option '.set_select('exp_month', sprintf("%02s", $i)).' value="'.sprintf("%02s", $i).'">'.sprintf("%02s", $i).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label" for="credit_card"><span class="text-danger">*</span> Exp Year:</label>
                            <select id="exp-year" name="exp_year" class="form-control input-md" required="" maxlength="2" tabindex="13">
                                <option value="">Year</option>
                                <?php
                                for($i=0;$i<8;$i++) {
                                    echo '<option '.set_select('exp_year', date('Y', strtotime('+'.$i.' years'))).'>'.date('Y', strtotime('+'.$i.' years')).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <label class="control-label" for="ccv"><span class="text-danger">*</span> CCV:</label>
                            <input id="ccv" name="ccv" type="text" autocomplete="off" placeholder="123" class="numbersOnly form-control input-md" required="" maxlength="5" tabindex="14" value="<?php echo set_value('ccv'); ?>">
                        </div>
                    </div>
                    <label class="control-label" for="name_on_card"><span class="text-danger">*</span> Name On The Card:</label>
                    <input id="name_on_card" name="name_on_card" value="<?php echo set_value('name_on_card'); ?>" autocomplete="off" type="text" class="form-control input-md" required="" maxlength="70" tabindex="15">
                    <br>
                    <button type="submit" class="btn btn-primary btn-sm updatePayment"><i class="fa fa-update"></i> Update</button>
                    <?php echo form_close(); ?>
                </div>
            </div>
            <div class="col-sm-6">
                <h4><i class="fa fa-plus"></i> Add Areas/Zips</h4>
                <hr>
                <p>At this time there is no way to add/remove certain services without cancelling your subscription and resubscribing to the zip codes you want. If you want to trade a zip code out you can contact us at <a href="http://network4rentals.com/help-support" target="_blank">https://network4rentals.com/help-support.</a></p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-9">
            </div>
            <div class="col-sm-3 text-right">
                <?php if(strtolower($status['code']) == 'ok') { ?>
                    <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancel"><i class="fa fa-times"></i> Cancel Subscription</button>
                <?php } ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="cancel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-danger"></i> Cancelling  Your Subscription</h4>
            </div>
            <div class="modal-body">
                <p>When you created your account you agreed to a contact for 1 year no matter how you decided to pay for your sponsorship. Your sign up date was on <b><?php echo date("m-d-Y", strtotime($payment->payment_date)); ?></b> so you are not eligable to cancel your account until <b><?php echo date("m-d-Y", strtotime(date("Y-m-d", strtotime($payment->payment_date)) . " + 365 day")); ?></b>. If you would like your to cancel your account once your year has expired please contact support below.</p>
                <br><br>
                <div class="row">
                    <div class="col-sm-6">
                        <h3><i class="fa fa-phone text-primary"></i> By Phone</h3>
                        <hr>
                        <b>Customer Service:</b> (740) 403-7661
                    </div>
                    <div class="col-sm-6">
                        <h3><i class="fa fa-envelope text-primary"></i> By Email</h3>
                        <hr>
                        <a href="http://network4rentals.com/help-support/" target="_blank" class="btn btn-default btn-sm">On-line</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>