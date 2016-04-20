<div class="row">
    <div class="col-md-4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <i class="fa fa-user"></i> Contractor Details
                </div>
            </div>
            <div class="widget-body clearfix">
                <ul class="list-group">
                    <li class="list-group-item">
                        <b>Contact Name:</b>
                        <?php echo $contractor->f_name.' '.$contractor->l_name; ?>
                    </li>
                    <li class="list-group-item">
                        <b>Business Name:</b>
                        <?php echo $contractor->bName; ?>
                    </li>
                    <li class="list-group-item">
                        <b>Address</b>
                        <?php echo $contractor->baddress.', '.$contractor->bcity.' '.$contractor->bstate; ?>
                    </li>
                    <li class="list-group-item">
                        <b>Email:</b>
                        <?php echo $contractor->email; ?>
                    </li>
                    <li class="list-group-item">
                        <b>Phone:</b>
                        <?php
                            if(!empty($contractor->phone)) {
                                echo '('.substr($contractor->phone, 0, 3) . ') ' .
                                    substr($contractor->phone, 3, 3) . '-' .
                                    substr($contractor->phone, 6);
                            } else {
                                echo 'NA';
                            }
                        ?>
                    </li>
                    <li class="list-group-item">
                        <b>Created:</b>
                        <?php echo date('m-d-Y', strtotime($contractor->created)); ?>
                    </li>
                    <li class="list-group-item">
                        <b>Last Login:</b>
                        <?php echo date('m-d-Y', strtotime($contractor->last_login)); ?>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="col-md-8">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    <i class="fa fa-credit-card"></i> Payments Processed
                </div>
            </div>
            <div class="widget-body clearfix">
                <?php
                if (!empty($payments)) {
                ?>
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
                                Date Paid
                            </th>
                            <th style="width:30%" class="hidden-phone">
                                Name
                            </th>
                            <th style="width:10%" class="hidden-phone">
                                Payment Frequency
                            </th>
                            <th style="width:10%" class="hidden-phone">
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach($payments as $row) {
                                echo '<tr>';
                                    echo '<td>'.$row->id.'</td>';
                                    echo '<td>';
                                        if($row->renewal == 'y') {
                                            echo '<span class="success">Renewal</span>';
                                        } else {
                                            echo '<span class="text-primary">New</span>';
                                        }
                                    echo '</td>';
                                    echo '<td>'.date('m-d-Y', strtotime($row->payment_date)).'</td>';
                                    echo '<td>'.$contractor->f_name.' '.$contractor->l_name.'</td>';
                                    echo '<td>'.$row->payment_frequency.' Month(s)</td>';
                                    echo '<td>$'.number_format($row->amount, 2).'</td>';
                                echo '</tr>';
                            }
                        ?>
                    </tbody>
                </table>

                <?php

                } else {
                    echo '<div class="alert alert-info">';
                        echo '<h3>No Payments Found for this User</h3>';
                        echo '<p>We looked into the database but no records where found that were tied to your
                        account.</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</div>