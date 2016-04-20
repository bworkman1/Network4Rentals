<?php
    $success = $this->session->flashdata('success');
    $error = $this->session->flashdata('error');

    $feedback = '';

    if(!empty($success)) {
        $feedback = '<div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                <b><i class="fa fa-check-circle"></i> Success:</b> '.$success.'
            </div>';
    }

    if(!empty($error)) {
        $feedback = '<div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                    <b><i class="fa fa-exclamation-triangle"></i> Error:</b> '.$error.'
                </div>';
    }
?>

<div class="container">
    <div id="login">
        <h3 class="text-center"><i class="fa fa-lock"></i> Forgot Password</h3>
        <?php echo $feedback; ?>
        <hr>
        <?php echo form_open('affiliates/forgot-password/reset-password'); ?>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" class="form-control" name="username" placeholder="email address">
            </div>
            <span class="help-block"><small>We'll send you an email with instructions on how to reset your password</small></span>
            <span class="help-block text-danger"></span>
            <br>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
        <?php echo form_close(); ?>
        <br>
        <p class="forgotPass text-right">
            <a href="<?php echo base_url('affiliates/login'); ?>">Login?</a>
        </p>
    </div>

</div>