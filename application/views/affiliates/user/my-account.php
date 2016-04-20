<div class="widget">
    <div class="widget-header">
        <div class="title">
            My Account Settings
        </div>
    </div>
    <div class="widget-body">
        <?php
            $success = $this->session->flashdata('success');
            $error = $this->session->flashdata('error');

            $feedback = '';

            if (!empty($success)) {
                $feedback = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                <b><i class="fa fa-check-circle"></i> Success:</b> '.$success.'
            </div>';
            }

            if (!empty($error)) {
                $feedback = '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                    <b><i class="fa fa-exclamation-triangle"></i> Error:</b> '.$error.'
                </div>';
            }
            echo $feedback;
        ?>

        <?php echo form_open('affiliates/my-account/mailingsettings/', array('class'=>'form-horizontal row-border')); ?>
            <h3><i class="fa fa-home"></i> Mailing Address: </h3>
            <p class="mini-title">Payments are made based on these settings</p>
            <br>
            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Name</label>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-5 col-xs-6">
                    <input type="text" name="first_name" class="form-control"
                           value="<?php echo $user->first_name; ?>" maxlength="35" placeholder="First Name" required>
                </div>
                <div class="col-md-5 col-xs-6">
                    <input type="text" name="last_name" class="form-control"
                           value="<?php echo $user->last_name; ?>" maxlength="35" placeholder="Last Name" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Mailing Address</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="address"
                           value="<?php echo $user->address; ?>" placeholder="Address" maxlength="40" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Mailing City
                    <span class="hidden-xs">/State</span></label>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-5 col-sm-6">
                    <input type="text" name="city" class="form-control"
                           value="<?php echo $user->city; ?>" maxlength="30" placeholder="City">
                </div>
                <label class="col-xs-12 control-label visible-xs">State</label>
                <div class="col-md-5 col-sm-6">
                    <select class="form-control" name="state" required>
                        <option>Select State..</option>
                        <?php
                        $states = array(
                            'AL'=>'Alabama', 'AK'=>'Alaska', 'AZ'=>'Arizona', 'AR'=>'Arkansas', 'CA'=>'California',
                            'CO'=>'Colorado', 'CT'=>'Connecticut', 'DE'=>'Delaware', 'DC'=>'District of Columbia',
                            'FL'=>'Florida', 'GA'=>'Georgia', 'HI'=>'Hawaii', 'ID'=>'Idaho', 'IL'=>'Illinois',
                            'IN'=>'Indiana', 'IA'=>'Iowa', 'KS'=>'Kansas', 'KY'=>'Kentucky', 'LA'=>'Louisiana',
                            'ME'=>'Maine', 'MD'=>'Maryland', 'MA'=>'Massachusetts', 'MI'=>'Michigan',
                            'MN'=>'Minnesota', 'MS'=>'Mississippi', 'MO'=>'Missouri', 'MT'=>'Montana',
                            'NE'=>'Nebraska', 'NV'=>'Nevada', 'NH'=>'New Hampshire', 'NJ'=>'New Jersey',
                            'NM'=>'New Mexico', 'NY'=>'New York', 'NC'=>'North Carolina', 'ND'=>'North Dakota',
                            'OH'=>'Ohio', 'OK'=>'Oklahoma', 'OR'=>'Oregon', 'PA'=>'Pennsylvania', 'RI'=>'Rhode Island',
                            'SC'=>'South Carolina', 'SD'=>'South Dakota', 'TN'=>'Tennessee', 'TX'=>'Texas',
                            'UT'=>'Utah', 'VT'=>'Vermont', 'VA'=>'Virginia', 'WA'=>'Washington', 'WV'=>'West Virginia',
                            'WI'=>'Wisconsin', 'WY'=>'Wyoming', );
                        foreach ($states as $key => $val) {
                            if($key==$user->state) {
                                echo '<option selected value="'.$key.'">'.$val.'</option>';
                            } else {
                                echo '<option value="'.$key.'">'.$val.'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Mailing Zip</label>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-4 col-sm-5 col-xs-6">
                    <input class="form-control" type="text" name="zip"
                           value="<?php echo $user->zip; ?>" maxlength="35" placeholder="Zip Code" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    <button type="submit" class="btn btn-primary">Save Address Settings</button>
                </div>
            </div>
        </form>

        <hr>

        <h3><i class="fa fa-user"></i> Account Settings:</h3>
        <br>
        <?php
            echo form_open_multipart('affiliates/my-account/accountSettings/',
                array('class'=>'form-horizontal row-border'));
        ?>
            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Email</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="email" placeholder="Email"
                           value="<?php echo $user->email; ?>"maxlength="70" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label"><span class="text-danger">*</span> Phone</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="phone"
                           value="<?php echo '('.
                               substr($user->phone, 0, 3) .') '.
                               substr($user->phone, 3, 3) .'-'.
                               substr($user->phone, 6); ?>"
                           placeholder="(555) 555-5555" maxlength="15" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Cell Phone</label>
                <div class="col-md-10">
                    <input class="form-control" type="text" name="cell_phone"
                           value="<?php
                                if (!empty($user->cell)) {
                                echo '('.
                               substr($user->cell, 0, 3) .') '.
                               substr($user->cell, 3, 3) .'-'.
                               substr($user->cell, 6); }?>"
                           placeholder="(555) 555-5555" maxlength="15">
                    <span class="help-block">Add if you would like to receive SMS notifications</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Profile Image</label>
                <div class="col-md-10">
                    <input class="form-control" type="file" name="profile">
                    <span class="help-block">JPEG, JPG, PNG, GIF file types accepts</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Password</label>
                <div class="clearfix visible-sm visible-xs"></div>
                <div class="col-md-5 col-sm-6">
                    <input type="password" name="password1" class="form-control" maxlength="20"
                           placeholder="Change Password">
                </div>
                <br class="visible-xs">
                <div class="col-md-5 col-sm-6">
                    <input type="password" name="password2" class="form-control" maxlength="20"
                           placeholder="Confirm Password">
                </div>

                <div class="col-md-10 col-md-offset-2">
                    <span class="help-block">Password must be at least 7 characters long</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-10 col-md-offset-2">
                    <button type="submit" class="btn btn-primary">Save Account Settings</button>
                </div>
            </div>
        </form>
    </div>
</div>