<h2><i class="fa fa-unlock text-primary"></i> </i>Change Your Password Below</h2>
<p>Your email has been verified and you can now change your password below.</p>
<?php
if(validation_errors()) {
    echo '<div class="alert alert-danger">'.validation_errors().'</div>';
}
?>
<div class="row">
    <div class="col-sm-6">
        <div class="well">
            <?php
            echo form_open('local-partner/forgot-password/newpass');
            echo '<h4>Password Must Be 6 or More Characters</h4>';
            echo form_label('<i class="fa fa-asterisk text-danger"></i> Password:');
            $data = array(
                'name'        => 'password',
                'id'          => 'Password',
                'maxlength'   => '100',
                'class'       => 'form-control',
                'required' 	=> ''
            );
            echo form_password($data);
            echo form_label('<i class="fa fa-asterisk text-danger"></i> Confirm Password:');
            $data = array(
                'name'        => 'confirm_password',
                'id'          => 'Password2',
                'maxlength'   => '100',
                'class'       => 'form-control',
                'required' 	=> ''
            );
            echo form_password($data);
            $data = array(
                'token' => $this->session->userdata('token')
            );
            echo form_hidden($data);
            echo '<div class="spacing15"></div>';
            echo '<br>';
            echo '<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-location-arrow"></i> Change Password</button>';
            echo form_close();
            ?>
        </div>
    </div>
    <div class="col-sm-6">
        <h4>Remember Your Password?</h4>
        <p>Your password has not been changed yet, you can still login to your account using your old password.</p>
        <a href="<?php echo base_url('local-partner/login/'); ?>" class="btn btn-primary btn-sm"><i class="fa fa-unlock"></i> Login</a>
        <hr>
        <h4>Still Need Help?</h4>
        <p>If you still having problems and cannot login and have already tried this route check out our faqs for additional help.</p>
        <a href="https://network4rentals.com/faqs/" class="btn btn-primary btn-sm"><i class="fa fa-question"></i> View FAQs</a>
    </div>
</div>