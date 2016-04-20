<?php
    $success = $this->session->flashdata('success');
    $error = $this->session->flashdata('error');

    $feedback = '';
		
    if(!empty($success)) {
        $feedback = '<div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
            <b><i class="fa fa-check-circle fa-lg fa-fw fa-3x pull-left"></i> Success:</b> '.$success.'
        </div>';
    }

    if(!empty($error)) {
        $feedback = '<div class="feedback alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>
                <b><i class="fa fa-exclamation-triangle fa-3x pull-left"></i> Error:</b> '.$error.'
            </div>';
    }
	echo $feedback;
?>