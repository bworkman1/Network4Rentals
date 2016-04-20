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
        <h3 class="text-center"><i class="fa fa-key"></i> Reset Your Password</h3>
        <?php echo $feedback; ?>
        <hr>

        <?php echo form_open('affiliates/forgot-password/update-password-submit/'); ?>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                <input type="text" class="form-control" name="password" placeholder="New Password">
            </div>
            <span class="help-block"></span>

            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock fa-fw"></i></span>
                <input  type="password" class="form-control" name="password2" placeholder="Confirm New Password">
            </div>
            <span class="help-block text-danger"></span>
            <br>
            <input type="hidden" name="user" value="<?php echo $user->id; ?>">
            <input type="hidden" name="hash" value="<?php echo $user->reset_hash; ?>">
            <button class="btn btn-lg btn-primary btn-block" type="submit">Update Password</button>
        <?php echo form_close(); ?>

        <br>
        <p class="forgotPass text-right">
            <a href="<?php echo base_url('affiliates/login'); ?>">Login?</a>
        </p>

    </div>

</div>