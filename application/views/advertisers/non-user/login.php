<h2><i class="text-primary fa fa-unlock"></i> Login To Your Account:</h2>
<p>To enjoy all the great benefits of Network 4 Rentals please login to your account. If you are not registered yet please create an account on the left side by clicking the create account link in the menu. For help and support visit our help and support page.</p>

<?php
    if($this->session->flashdata('error'))
    {
        echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error: </b> '.$this->session->flashdata('error').'.</div>';
    }
    if($this->session->flashdata('success'))
    {
        echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success: </b> '.$this->session->flashdata('success').'.</div>';
    }
?>

<div class="row">
    <div class="col-sm-5">
        <div class="well">
            <?php echo form_open('local-partner/login/user/'); ?>
                <fieldset>
                    <legend><i class="fa fa-unlock"></i> Login:</legend>
                    <label>Username/Email:</label>
                    <input type="text" name="username" value="" id="username" maxlength="100" size="50" class="form-control" required="">
                    <label>Password:</label>
                    <input type="password" name="password" value="" id="password" maxlength="100" size="50" class="form-control" required="">
                    <br>
                    <button name="" type="submit" value="true" class="btn btn-primary btn-sm"><i class="fa fa-unlock"></i> Login</button>
                </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div>

    <div class='col-sm-7'>
        <h3>Need Help?</h3>
        <p>If you forgot your password or need to create an account with us click on on of the following buttons.</p>

        <a href="<?php echo base_url('local-partner/forgot-password'); ?>" class="btn btn-primary pull-left btn-sm">
            <i class="fa fa-lock"></i> Forgot Password?
        </a>
        <a href="<?php echo base_url('local-partner/create-account'); ?>" class="btn btn-primary pull-right btn-sm">
            <i class="fa fa-user"></i> Create Account
        </a>
    </div>
</div>