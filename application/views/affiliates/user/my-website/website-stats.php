<?php
    if(empty($analyticsId) && empty($analyticsAccountId)) {
        echo '<div id="showModel"></div>';
    }
?>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Total Visitors</div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="pull-right number"><?php echo $users; ?></div>
            </div>
            <div class="mini-widget-footer center-align-text">
                <span><a style="color:#fff;" href="https://support.google.com/analytics/answer/2992042?hl=en"
                         target="blank">What does this mean?</a></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Sessions</div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-twitter"></i>
                </div>
                <div class="pull-right number"><?php echo $sessions; ?></div>
            </div>
            <div class="mini-widget-footer center-align-text">
                <span><a style="color:#fff;" href="https://support.google.com/analytics/answer/2731565?hl=en"
                         target="blank"> What does this mean?</a></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Returning Visitors</div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-refresh"></i>
                </div>
                <div class="pull-right number"><?php echo $newUsers['Returning Visitor']; ?></div>
            </div>
            <div class="mini-widget-footer center-align-text">
                <span><a style="color:#fff;" href="https://support.google.com/analytics/answer/1006253?hl=en"
                         target="blank">What does this mean?</a></span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget mini-widget-grey">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">New Visitors</div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-child"></i>
                </div>
                <div class="pull-right number"><?php echo $newUsers['New Visitor']; ?></div>
            </div>
            <div class="mini-widget-footer center-align-text">
                <span><a style="color:#fff;" href="https://support.google.com/analytics/answer/1006253?hl=en"
                         target="blank">What does this mean?</a></span>
            </div>
        </div>
    </div>
</div>

<div class="widget">
    <div class="widget-header">
        <div class="title">
            Visitor By Browser
        </div>
    </div>
    <div class="widget-body">
        <div id="area-chart3" class="chart-height-lgy" style="padding: 0px;"></div>
    </div>
</div>


<div class="row">
    <div class="col-md-4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    Visitor By Browser
                </div>
            </div>
            <div class="widget-body">
                <div class="border-bottom">
                    <div class="row">
                        <div class="col-xs-5 col-xs-offset-1">
                            <b>Browser</b>
                        </div>
                        <div class="col-xs-5 text-right">
                            <b>Visits</b>
                        </div>
                    </div>
                </div>
                <ul class="list-group limit-list-size">
                    <?php
                        if(!empty($browsers)) {
                            foreach($browsers as $key => $val) {
                                echo '<li class="list-group-item">';
                                    echo '<span class="badge badge-info">'.$val.'</span>';
                                    echo '<i class="fa fa-laptop fa-fw fa-2x text-info"></i> '.$key;
                                echo '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    Visitor Referrer
                </div>
            </div>
            <div class="widget-body">
                <div class="border-bottom">
                    <div class="row">
                        <div class="col-xs-5 col-xs-offset-1">
                            <b>Referrer</b>
                        </div>
                        <div class="col-xs-5 text-right">
                            <b>Visits</b>
                        </div>
                    </div>
                </div>
                <ul class="list-group limit-list-size">
                    <?php
                        if(!empty($source)) {
                            foreach($source as $key => $val) {
                                echo '<li class="list-group-item">';
                                    echo '<span class="badge badge-info">'.$val.'</span>';
                                    echo '<i class="fa fa-globe fa-fw fa-2x text-success"></i> '.$key;
                                echo '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    Visitor Operating Systems
                </div>
            </div>
            <div class="widget-body">
                <div class="border-bottom">
                    <div class="row">
                        <div class="col-xs-5 col-xs-offset-1">
                            <b>OS</b>
                        </div>
                        <div class="col-xs-5 text-right">
                            <b>Visits</b>
                        </div>
                    </div>
                </div>
                <ul class="list-group limit-list-size">
                    <?php
                        if(!empty($operatingSystems)) {
                            foreach($operatingSystems as $key => $val) {
                                echo '<li class="list-group-item">';
                                    echo '<span class="badge badge-info">'.$val.'</span>';
                                    echo '<i class="fa fa-globe fa-fw fa-2x text-success"></i> '.$key;
                                echo '</li>';
                            }
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="analyitcsModal" class="modal modal-new fade" tabindex="-1" role="dialog" aria-labelledby="analyitcsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <?php if($setAnalyticsCode) { ?>
                    <h2>Connect your Analytics Account</h2>
                    <h4>In order to continue and see your website stats we need you to allow us to access the data on
                        your analytics account. Click the button below and your website stats will fill into the page.
                    </h4>
                    <a href="#" id="allowAccess" class="btn btn-primary">Connect Account</a>
                <?php } else { ?>
                    <?php if(!empty($analyticsId)) { ?>
                        <h2>It looks like you're already tracking stats</h2>
                        <p>Now all you need to do is add your Account Id to your website settings. Check or help
                            page on how and where to find your account id.</p>
                        <a href="<?php echo base_url('affiliates/help'); ?>" class="btn btn-primary">FAQ page</a>
                    <?php } elseif(!empty($analyticsAccountId)) { ?>
                        <h2>You need to have both Analytics Settings</h2>
                        <p>In order for your stats to work you need both, your <b>Account Id</b> and your
                            <b>Tracking Id</b> set in you website settings page.</p>
                        <a href="<?php echo base_url('affiliates/my-website/edit-website'); ?>" class="btn btn-primary">
                            Add These Details</a>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-md-6">
                                <h2>Analytics Account is Required</h2>
                                <p>Head over to Google Analytics and login with your gmail account or create
                                    a new one. Once your logged in you will need to find you access id. If you are not
                                    familiar with this please visit our
                                    <a href="<?php echo base_url('affiliates/help'); ?>">FAQ page</a>
                                    for more details.</p>
                                <a href="https://www.google.com/analytics/" target="_blank" class="btn btn-primary">
                                    Google Analytics</a>
                            </div>
                            <div class="col-md-6">
                                <h2>Have an Analytics Account but need help?</h2>
                                <p>Visit our FAQ page to learn how and what you need to track your stats on your
                                    site.</p>
                                <a href="<?php echo base_url('affiliates/help'); ?>" class="btn btn-primary">
                                    FAQ page</a>
                            </div>
                        </div>
                    <?php } ?>


                <?php } ?>
            </div>
        </div>
    </div>
</div>