<h2><i class="text-primary fa fa-unlock"></i> Forgot Password</h2>
<p>Forgot your password, no problem. Its easy to reset your password just enter your email address below and follow the directions in the email to reset your password.</p>
<hr>
<?php
if($this->session->flashdata('error')) {
    echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error: </b>'.$this->session->flashdata('error').'</div>';
}
if($this->session->flashdata('success')) {
    echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success: </b>'.$this->session->flashdata('success').'</div>';
}
?>

<div class="row">
    <div class="col-md-6">
        <div class="well">
            <?php
            echo form_open('local-partner/forgot-password/reset');
            echo form_fieldset('Enter Your Details Below');
            echo form_label('<i class="fa fa-asterisk text-danger"></i> Email:');
            $data = array(
                'name'        => 'email',
                'id'          => 'email',
                'maxlength'   => '200',
                'class'       => 'form-control',
                'required' 	=> ''
            );
            echo form_input($data);
            echo '<br>';
            echo $captcha;
            echo '<br>';

            echo '<div class="spacing15"></div>';
            echo '<br>';
            echo '<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-location-arrow"></i> Reset</button>';
            echo form_close();
            ?>
        </div>
    </div>
    <div class="col-md-6">
        <h4>Still Need Help?</h4>
        <p>If you still having problems and cannot login and have already tried this route check out our faqs for additional help.</p>
        <a href="https://network4rentals.com/faqs/" class="btn btn-primary btn-sm"><i class="fa fa-question"></i> View FAQs</a>
    </div>
</div>