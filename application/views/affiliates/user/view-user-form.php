<?php
    $user->phone =  '('.substr(htmlspecialchars($user->phone), 0, 3) .') '.
        substr(htmlspecialchars($user->phone), 3, 3) .'-'.
        substr(htmlspecialchars($user->phone), 6);
?>
<div class="row">
    <div class="col-md-8">

        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <i class="fa fa-user"></i> User Submitted Form
                </div>
            </div>
            <div class="widget-body">
                <p><b>Name</b>: <?php echo htmlspecialchars($user->name); ?></p>
                <p><b>Name</b>: <?php echo htmlspecialchars($user->email); ?></p>
                <p><b>Name</b>: <?php echo $user->phone; ?></p>
                <p><b>Name</b>: <?php echo date('m-d-Y', strtotime($user->timestamp)); ?></p>
                <p><b>Question/Comment:</b><br><?php echo htmlspecialchars($user->desc); ?></p>
                <br>
                <a href="<?php echo base_url('affiliates/form-submissions'); ?>" class="btn btn-info">View All</a>
            </div>
        </div>
    </div>
</div>
