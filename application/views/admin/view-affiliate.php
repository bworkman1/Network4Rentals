<?php
    $error = $this->session->flashdata('error');
    $success = $this->session->flashdata('success');
    if(!empty($error)) {
        echo '<div class="alert alert-danger"><b><i class="fa fa-times-circle fa-lg"></i> Error:</b> '.$error.'</div>';
    }
    if(!empty($success)) {
        echo '<div class="alert alert-success"><b><i class="fa fa-check-circle fa-lg"></i> Success:</b> '.$success.'</div>';
    }
?>

<?php
$createdAccount = date('Y-m', strtotime($this->session->userdata('created')));
$today = date('Y-m');

if ($createdAccount<$today) {
    ?>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <nav class="navbar navbar-default" role="navigation">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#payments-menu" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#" data-original-title="" title="">Month</a>
                    </div>

                    <div id="payments-menu" class="navbar-collapse collapse"
                         aria-expanded="false" style="height: 1px;">
                        <ul class="nav navbar-nav">
                            <?php
                            $createdAccount = date('Y-m', strtotime($this->session->userdata('created')));
                            $month = time();
                            $links = array();
                            for ($i = 1; $i <= 12; $i++) {
                                $class='';
                                $month = strtotime('last month', $month);
                                if ((int)$this->uri->segment(5) == date("m", $month)) {
                                    $class = 'active';
                                } else {
                                    if ((int)$this->uri->segment(5)=='' && $i == 1) {
                                        $class = 'active';
                                    } else {
                                        $class = '';
                                    }
                                }

                                if ($createdAccount < date('Y-m', strtotime('+1 month', $month))) {
                                    $links[] = '<li class="toolTip ' . $class . '"
                                            data-toggle="tooltip" data-trigger="hover"
                                            title="' . date("Y", $month) . ' Year" >
                                            <a href="' . base_url('affiliates/payments/custom/' .
                                            date("Y", $month) . '/' . date("m", $month)) . '"
                                            data-original-title="" title="">' . date("M", $month) . '</a></li>';
                                }
                            }

                            $links = array_reverse($links);
                            foreach ($links as $val) {
                                echo $val;
                            }
                            ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
<?php } ?>

<div id="paymentSettings" data-signup="<?php echo $user->signup_commission; ?>" data-renewal="<?php echo $user->renewal_commission; ?>" data-mbonus="<?php echo $user->monthly_bonus; ?>" data-ybonus="<?php echo $user->yearly_bonus; ?>"></div>
<div class="row">

    <div class="col-md-6">
        <div class="widget">
            <div class="widget-header">

                <div class="title">
                    <i class="fa fa-user"></i> User Details
                </div>
                <?php
                    if(!empty($user->image) && file_exists($user->image)) {
                        echo '<img src="'.$user->image.'" class="pull-right img-circle" height="60" width="60">';
                    }
                ?>

            </div>
            <div class="widget-body">
                <div class="row">
                    <div class="col-md-6">
                        <dl class="no-margin">
                            <dt class="text-success">
                                Name
                            </dt>
                            <dd>
                                <?php echo $user->first_name.' '.$user->last_name; ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Address
                            </dt>
                            <dd>
                                <?php echo $user->address.' '.$user->city.', '.$user->state.' '.$user->zip; ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Email
                            </dt>
                            <dd>
                                <?php echo $user->email; ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Phone
                            </dt>
                            <dd>
                                <?php
                                if(!empty($user->phone)) {
                                    echo "(".substr($user->phone, 0, 3).") ".substr($user->phone, 3, 3)."-".substr($user->phone,6);
                                }
                                ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Cell
                            </dt>
                            <dd>
                                <?php
                                    if(!empty($user->cell)) {
                                       echo "(".substr($user->cell, 0, 3).") ".substr($user->cell, 3, 3)."-".substr($user->cell,6);
                                    }
                                ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Last Page Viewed
                            </dt>
                            <dd>
                                <?php echo date('m-d-Y h:i a', strtotime($user->last_viewed)); ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Affiliate Id
                            </dt>
                            <dd>
                                <?php echo $user->unique_id; ?><br><br>
                            </dd>
                        </dl>
                    </div>
                    <div class="col-md-6">
                        <dl class="no-margin">
                            <dt class="text-success">
                                Created
                            </dt>
                            <dd>
                                <?php echo date('m-d-Y h:i a', strtotime($user->created)); ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Sign Up Commission
                            </dt>
                            <dd>
                                <?php echo $user->signup_commission; ?>%<br><br>
                            </dd>
                            <dt class="text-success">
                                Renewal Commission
                            </dt>
                            <dd>
                                <?php echo $user->renewal_commission; ?>%<br><br>
                            </dd>
                            <dt class="text-success">
                                Monthly Bonus
                            </dt>
                            <dd>
                                <?php echo $user->monthly_bonus; ?>%<br><br>
                            </dd>
                            <dt class="text-success">
                                Yearly Bonus
                            </dt>
                            <dd>
                                <?php echo $user->yearly_bonus; ?>%<br><br>
                            </dd>
                            <dt class="text-success">
                                Monthly Quota
                            </dt>
                            <dd>
                                <?php echo $user->monthly_quota; ?><br><br>
                            </dd>
                            <dt class="text-success">
                                Yearly Quota
                            </dt>
                            <dd>
                                <?php echo $user->yearly_quota; ?><br><br>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget">
            <div class="widget-header">

                <div class="title">
                    <i class="fa fa-money"></i> Pending Payments
                </div>

            </div>
            <div class="widget-body">
                <?php
                    if(!empty($pending_payments)) {
                        echo form_open('n4radmin/affiliates-paid');
                            echo '<ul class="underlined-list">';
                                echo '<li>';
                                    echo '<div class="row">';
                                        echo '<div class="col-xs-2">';
                                            echo '<p><b>Mark Paid</b></p>';
                                        echo '</div>';
                                        echo '<div class="col-xs-10">';
                                            echo '<p><b>Payment Details</b></p>';
                                        echo '</div>';
                                    echo '</div>';
                                echo '</li>';

                                $count = 1;
                                foreach($pending_payments as $row) {
                                    echo '<li><label>';
                                        echo '<div class="row">';
                                            echo '<div class="col-xs-2">';
                                                echo '#<b>'. $count.'</b> - <input class="markAsPaid" type="checkbox" name="paymentId[]" value="'.$row->id.'" data-amount="'.$row->amount.'" data-type="'.$row->renewal.'">';
                                            echo '</div>';
                                            echo '<div class="col-xs-10">';
                                                if($row->renewal == 'y') {
                                                    $label = '<span class="label label-success pull-right">Renewal</span>';
                                                } else {
                                                    $label = '<span class="label label-info pull-right">Initial</span>';
                                                }
                                                echo '<p>'.ucwords($row->type).' payment for $'.$row->amount.' on '.date('m-d-Y', strtotime($row->payment_date)).' | <b>Payment ID</b> '.$row->id.' '.$label.'</p>';
                                            echo '</div>';
                                        echo '</div>';
                                    echo '</label></li>';
                                    $count++;
                                }
                            echo '</ul>';
                            echo '<input type="hidden" name="user" value="'.$this->uri->segment(3).'">';
                            echo '<br><br>';

                            echo '<div class="row">';
                                echo '<div class="col-xs-6">';
                                    echo '<div id="amountToPay"></div>';
                                echo '</div>';
                                echo '<div class="col-xs-6">';
                                    echo '<button type="submit" class="btn btn-primary pull-right">Mark as Paid</button>';
                                    echo '<div class="clearfix"></div>';
                                echo '</div>';
                        echo '</div>';

                        echo form_close();
                    } else {
                        echo '<div class="alert alert-info"><h3>No Unpaid Payments For This User</h3></div>';
                    }
                ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="widget">
            <div class="widget-header">

                <div class="title">
                    <i class="fa fa-calendar"></i> Mark Paid Last 30 Days
                </div>

            </div>
            <div class="widget-body">
                <?php
                    if(!empty($recent_payments)) {
                        echo form_open('n4radmin/affiliates-paid');
                        echo '<ul class="underlined-list">';
                            echo '<li>';
                                echo '<p><b>Payment Details</b></p>';
                            echo '</li>';

                            $count = 1;
                            foreach($recent_payments as $row) {
                                echo '<li>';
                                    if($row->renewal == 'y') {
                                        $label = '<span class="label label-success pull-right">Renewal</span>';
                                    } else {
                                        $label = '<span class="label label-info pull-right">Initial</span>';
                                    }
                                    echo '<p><b>#'.$count.'</b> - '.ucwords($row->type).' payment for $'.$row->amount.' on '.date('m-d-Y', strtotime($row->payment_date)).' | <b>Payment ID</b> '.$row->id.' '.$label.'</p>';
                                echo '</li>';
                                $count++;
                            }
                        echo '</ul>';
                    }
                ?>

            </div>
        </div>
    </div>
</div>

