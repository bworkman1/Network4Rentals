<!-- Page Heading -->
<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">
            Dashboard <small>Statistics Overview</small>
        </h1>
        <ol class="breadcrumb">
            <li class="active">
                <i class="fa fa-dashboard"></i> Dashboard
            </li>
        </ol>
    </div>
</div>
<!-- /.row -->

<?php
	$error = $this->session->flashdata('error');
	$success = $this->session->flashdata('success');
	if(!empty($error)) {
		echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> '.$error.'</div>';
	}
	if(!empty($success)) {
		echo '<div class="alert alert-success"><i class="fa fa-check-circle"></i> '.$success.'</div>';
	}
?>

<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-key fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge dashLandlords" data-landlords="<?php echo $sums['landlords']; ?>"><?php echo $active['landlords'].' of '.$sums['landlords']; ?></div>
                        <div>Active Landlords</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('n4radmin/view-group/landlords'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Landlords</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-wrench fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge dashContractors" data-contractors="<?php echo $sums['contractors']; ?>"><?php echo $active['contractor'].' of '.$sums['contractors']; ?></div>
                        <div>Active Contractors</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('n4radmin/view-group/contractors'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Contractors</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-yellow">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge dashRenters" data-renters="<?php echo $sums['renters']; ?>"><?php echo $active['renters'].' of '.$sums['renters']; ?></div>
                        <div>Active Renters</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('n4radmin/view-group/renters'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Renters</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-tags fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                        <div class="huge dashAdvertisers" data-advertisers="<?php echo $sums['advertisers']; ?>"><?php echo $active['advertisers'].' of '.$sums['advertisers']; ?></div>
                        <div>Active Advertisers</div>
                    </div>
                </div>
            </div>
            <a href="<?php echo base_url('n4radmin/view-group/advertisers'); ?>">
                <div class="panel-footer">
                    <span class="pull-left">View Advertisers</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-fw"></i> User Sign Up Chart By Day</h3>
            </div>
            <div class="panel-body">
                <div id="morris-area-chart"></div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->

<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-long-arrow-right fa-fw"></i> Total User Accounts</h3>
            </div>
            <div class="panel-body">
                <div id="morris-donut-chart"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">		
	
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-comments-o fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge dashLandlords" data-landlords="139"><?php echo $sums['total_messages']; ?></div>
						<div>Total Messages</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Messages</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
		
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-wrench fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge dashLandlords" data-landlords="139"><?php echo $sums['total_service_reqeuests']; ?></div>
						<div>Total Service Requests</div>
					</div>
				</div>
			</div>
			<a href="#">
				<div class="panel-footer">
					<span class="pull-left">View Service Requests</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
		
		<div class="panel panel-info">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-users fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge dashLandlords" data-landlords="139"><?php echo $sums['user_sum']; ?></div>
						<div>Total Users</div>
					</div>
				</div>
			</div>
		</div>
				
          
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-money fa-fw"></i> Transactions Panel</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Order Date</th>
                            <th>Order Time</th>
                            <th>Amount (USD)</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(!empty($payments)) {
                                foreach($payments as $key=>$val) {
                                    echo '<tr>';
                                        echo '<td>'.$val->trans_id.'</td>';
                                        echo '<td>'.date('m-d-Y', strtotime($val->ts)).'</td>';
                                        echo '<td>'.date('h:i A', strtotime($val->ts)).'</td>';
                                        echo '<td>$'.number_format($val->total, 2).'</td>';
                                    echo '</tr>';
                                }
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <a href="#">View All Transactions <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.row -->
