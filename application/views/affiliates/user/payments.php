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

<div class="row">
    <div class="col-lg-12 col-md-12">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <?php
                        $month_names = array(
                             "January","February","March","April","May","June",
                             "July","August","September","October","November","December"
                        );
						
						$seg = $this->uri->segment(5);
                        if (empty($seg)) {
                            $monthName = date("F");
                        } else {
                            $monthNum = str_replace('0', '', $this->uri->segment(5));
                            $monthName = $month_names[$monthNum];
                        }

                        echo 'Eligible Sign ups/renewals for '.$monthName;
                    ?>
                </div>
                    <span class="tools">
                      Records: <?php echo count($new)+count($renewals); ?>
                    </span>
            </div>
            <div class="widget-body">
                <?php if (!empty($new) || !empty($renewals)) { ?>
                    <div class="table-responsive">
                        <table class="table table-condensed table-striped
                            table-bordered table-hover no-margin limit-list-size">
                            <thead>
                            <tr>
                                <th style="width:10%">
                                    Id#
                                </th>
                                <th>
                                    Type
                                </th>
                                <th style="width:15%">
                                    Date
                                </th>
                                <th style="width:30%" class="hidden-phone">
                                    Name
                                </th>
                                <th style="width:10%" class="hidden-phone">
                                    Total Purchase
                                </th>
                                <th style="width:10%" class="hidden-phone">
                                    Comm. Rate
                                </th>
                                <th style="width:10%" class="hidden-phone">
                                    Comm.
                                </th>
                                <th class="hidden-phone">
                                    Pay Freq.
                                </th>
                                <th  class="text-right hidden-phone" style="width:5%">
                                    Actions
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                                $commissionPayments = array();
                                $totalSalesArray = array();
                                if (!empty($new)) {
                                    foreach ($new as $row) {
                                        if (!empty($row->payment)) {
                                            $totalSalesArray[] = $row->payment->amount;
                                            $saleTotal = $row->payment->amount;
                                            $percent = $payment_settings['signup_commission'];
                                            $comm = ($percent / 100) * $saleTotal;
                                            $freq = $row->payment->payment_frequency . ' Month(s)';
                                            $commissionPayments[] = $comm;
                                        } else {
                                            $saleTotal = '0';
                                            $comm = 0;
                                            $freq = 'NA';
                                            $percent = 0;
                                        }
                                        echo '<tr>';
                                            echo '<td>' . $row->id . '</td>';
                                            echo '<td class="text-info">New</td>';
                                            echo '<td>' . date('m-d-Y', strtotime($row->created)) . '</td>';
                                            echo '<td>' . $row->f_name . ' ' . $row->l_name . '</td>';
                                            echo '<td>$' . number_format($saleTotal, 2) . '</td>';
                                            echo '<td>' . $percent . '%</td>';
                                            echo '<td>$' . number_format($comm, 2) . '</td>';
                                            echo '<td>' . $freq . '</td>';
                                            echo '<td class="text-right">';
                                            echo '<a href="'.
                                                base_url('affiliates/my-referrals/contractors/'.$row->id).'"
                                                class="btn btn-warning btn-xs"';
                                                echo 'data-original-title="" title="">View</a>';
                                            echo '</td>';
                                        echo '</tr>';
                                    }
                                }

                                if (!empty($renewals)) {
                                    foreach ($renewals as $row) {
                                        $percent = $payment_settings['renewal_commission'];
                                        $saleTotal = $row->amount;
                                        $comm = ($percent / 100) * $saleTotal;
                                        $commissionPayments[] = $comm;
                                        $freq = $row->payment_frequency . ' Month(s)';
                                        $payments[] = $comm;

                                        echo '<tr>';
                                        echo '<td>' . $row->id . '</td>';
                                        echo '<td class="text-success">Renewal</td>';
                                        echo '<td>' . date('m-d-Y', strtotime($row->created)) . '</td>';
                                        echo '<td>' . $row->f_name . ' ' . $row->l_name . '</td>';
                                        echo '<td>$' . number_format($saleTotal, 2) . '</td>';
                                        echo '<td>' . $percent . '%</td>';
                                        echo '<td>$' . number_format($comm, 2) . '</td>';
                                        echo '<td>' . $freq . '</td>';
                                        echo '<td class="text-right">';
                                        echo '<a href="'.base_url('affiliates/my-referrals/contractors/'.$row->id).'"
                                            class="btn btn-warning btn-xs" ';
                                            echo 'data-original-title="" title="">';
                                            echo 'View</a>';
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                }

                                $monthlyBonusUsers = $payment_settings['monthly_quota'];
                                $needed = $monthlyBonusUsers-count($new);
                                $salesVolume = array_sum($totalSalesArray);
                                $percent = 8;
                                $monthlyPercent = $payment_settings['monthly_bonus'];
                                $bonusCommissionTotal = ($percent / 100) * $salesVolume;

                                $monthlyBonus = ($monthlyPercent / 100) * $bonusCommissionTotal;
                            ?>
                                <tr class="text-right" style="background: #f5f5f5">
                                    <td colspan="9"><h4><b>Commission Total:</b>
                                            $<?php echo number_format(array_sum($commissionPayments), 2); ?></h4></td>
                                </tr>
                                <?php
                                if ($needed<1) {
                                    echo '<tr class="text-right" style="background: #74B749;color:#fff;">';
                                        echo '<td colspan="9"><h5><b>Reached Monthly Bonus:</b>
                                            $'.number_format($monthlyBonus, 2).'</h5></td>';
                                    echo '</tr>';
                                    echo '<tr class="text-right" style="background: #f5f5f5">';
                                        echo '<td colspan="9"><h4><b>Total:</b>
                                            $'.number_format($monthlyBonus+array_sum($commissionPayments), 2);
                                            echo '</h4></td>';
                                    echo '</tr>';
                                }

                                ?>
                            </tbody>
                        </table>
                        <br>

                    </div>
                    <div class="row">
                        <div class="col-md-2 text-center">
                            <?php
                                echo '<div class="alert alert-warning"><h1>';
                                    if(!empty($renewals)) {
                                        echo count($renewals);
                                    } else {
                                        echo '0';
                                    }
                                echo '</h1><h4>Renewal(s) for the month of '.$monthName.'</h4></div>';
                            ?>
                        </div>
                        <div class="col-md-2 text-center">
                            <?php
                                echo '<div class="alert alert-info"><h1>';
                                    if (!empty($new)) {
                                        echo count($new);
                                    } else {
                                        echo '0';
                                    }
                                echo '</h1>';
                                echo '<h4>New sign up(s) for the month of '.$monthName.'</h4></div>';
                            ?>
                        </div>
                        <div class="col-md-8">
                            <?php

                            if ($needed>0) {
                                echo '<div class="notice yellow"><p><b>'.$needed.'</b> more sign ups are
                                needed to reach your monthly bonus for ' . $monthName . '</p></div>';
                            } else {
                                echo '<div class="notice green"><p><b>$'.number_format($monthlyBonus, 2).' Bonus
                                </b> - Congratulations! You reached your monthly
                                bonus for '.$monthName.'</p></div>';
                            }

                            ?>
                            <div class="notice red">
                                <p>You need <b><?php echo $yearlyData['neededYearly']; ?></b>
                                    more sales in <?php echo $yearlyData['left']; ?>
                                    days to reach your yearly bonus!</p>
                            </div>

                            <div class="notice blue">
                                <p>Your yearly schedule runs from <b>
                                        <?php echo $yearlyData['starts'].'</b> to <b>'.$yearlyData['ends']; ?></b></p>
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <h3>No Eligible Sign Ups Or Renewals For <?php echo $monthName; ?></h3>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
