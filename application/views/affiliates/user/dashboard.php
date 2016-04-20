
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Need Help or have questions?</div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-question-circle"></i>
                </div>
                <div class="pull-right number"><a href="https://network4rentals.com/network/affiliates/help" style="color: #ffffff">Help</a></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Commission for <?php echo date('M'); ?></div>
                <div class="pull-right"></div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-money"></i>
                </div>
                <div class="pull-right number"><?php echo '$'.$affiliateData['commission']; ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Sign Ups for <?php echo date('M'); ?></div>
                <div class="pull-right"></div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-upload"></i>
                </div>
                <div class="pull-right number"><?php echo $affiliateData['totalSignUps']; ?></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6">
        <div class="mini-widget">
            <div class="mini-widget-heading clearfix">
                <div class="pull-left">Form Submissions</div>
                <div class="pull-right"></div>
            </div>
            <div class="mini-widget-body clearfix">
                <div class="pull-left">
                    <i class="fa fa-file-o"></i>
                </div>
                <div class="pull-right number"><?php echo $totalForms; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">

        <div class="widget">
            <div class="widget-header">
                <div class="title">
                    Newest Form Submissions
                </div>
            </div>
            <div class="widget-body">
				<?php if (!empty($recentFormSubmissions)) { ?>
					<table class="table table-hover no-margin">
						<thead>
						<tr>
							<th>Name</th>
							<th>Email</th>
							<th>Sent</th>
							<th>View</th>
						</tr>
						</thead>
						<tbody>
							<?php
								foreach($recentFormSubmissions as $row) {
									echo '<tr>';
										echo '<td>'.htmlspecialchars($row->name).'</td>';
										echo '<td>'.htmlspecialchars($row->email).'</td>';
										echo '<td>'.date('m-d-Y', strtotime($row->timestamp)).'</td>';
										echo '<td><a href="'.base_url('affiliates/form-submissions/view-form/'.$row->id)
											.'" class="btn btn-warning btn-sm">View</a></td>';
									echo '</tr>';
								}
							?>

						</tbody>
					</table>
				<?php } else { ?>
					<div class="alert alert-info">
						<h3>No Recent Form Submissions</h3>
						<p>This feature is not quite ready, we are activly working on this feature and once its ready and people fill out your contact form they will appear here.</p>
					</div>
				<?php } ?>
				<?php if (!empty($recentFormSubmissions)) { ?>
					<br>
					<a href="<?php echo base_url('affiliates/form-submissions'); ?>" class="btn btn-info">View All</a>
				<?php } ?>
            </div>
        </div>
    </div>

	<div class="col-md-6">
	
		<div class="widget">
            <div class="widget-header">
                <div class="title">
                    Items That Need Action
                </div>
            </div>
            <div class="widget-body">
				<?Php
					$boxShown = false;
					
					if($actions['public_page_set'] === false) {
						echo '<div class="notice red">
								<p>You have not setup your website yet. Your website is the most important part of selling and should be setup right away.</p>
								<a href="'.base_url('affiliates/my-website/edit').'" style="margin-bottom: 0" class="btn btn-info">Setup Now</a>
							</div>';
						$boxShown = true;
					}
					
					if($actions['password_set'] === false) {
						echo '<div class="notice yellow">
								<p>It looks like you signed in through a social network. That is great but you should set your password just incase you need to login without using it.</p>
								<a href="'.base_url('affiliates/my-account').'" style="margin-bottom: 0" class="btn btn-info">Set Password</a>
							</div>';
						$boxShown = true;
					}
					
					if($actions['analytics_set'] === false) {
						echo '<div class="notice blue">
								<p>Set up Google Analytics for tracking visitors. If you don\'t know what this is visit our help section for more details.</p>
								<a href="'.base_url('affiliates/my-website/edit').'" style="margin-bottom: 0" class="btn btn-info">Setup Analytics</a>
							</div>';
						$boxShown = true;
					}
					
					if($boxShown === false) {
						echo '<div class="alert alert-info"><h3>No Actions Needed</h3><p>It looks like you have compelted all the important stuff. If anything pops up we will post it here.</p></div>';
					}
				?>
			</div>
		</div>
	</div>
	
</div>
