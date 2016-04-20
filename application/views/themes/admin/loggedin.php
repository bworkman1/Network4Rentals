<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin Panel">
    <meta name="author" content="Brian Workman">

    <title>Network4Rentals Admin Panel</title>
    <?php
		 foreach($css as $file){
		 	echo "\n\t\t";
			?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
		 } echo "\n\t";
    ?>


<?php
		if(!empty($meta))
			foreach($meta as $name=>$content){
				echo "\n\t\t";
				?><meta name="<?php echo $name; ?>" content="<?php echo is_array($content) ? implode(", ", $content) : $content; ?>" /><?php
		 }
	?>
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo base_url('n4radmin'); ?>">N4R Admin Area</a>
        </div>
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">


            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo ucwords($this->session->userdata('userName')); ?> <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li style="display: none">
                        <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>
                    </li style="display: none">
                    <li style="display: none">
                        <a href="#"><i class="fa fa-fw fa-envelope"></i> Inbox</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url('n4radmin/user-settings'); ?>"><i class="fa fa-fw fa-gear"></i> Settings</a>
                    </li>
                    <li class="divider"></li>
                    <li>
                        <a href="<?php echo base_url(); ?>n4radmin/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                    </li>
                </ul>
            </li>
        </ul>
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li class="active">
                    <a href="<?php echo base_url('n4radmin'); ?>"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                </li>
                <li>
                    <a href="javascript:" data-toggle="collapse" data-target="#usersLinks"><i class="fa fa-fw fa-users"></i> Users Details <i class="fa fa-fw fa-caret-down"></i></a>
                    <ul id="usersLinks" class="collapse">
                        <li>
                            <a href="<?php echo base_url('n4radmin/view-group/landlords'); ?>" class="text-primary"><i class="fa fa-fw fa-key"></i> Landlords</a>
                        </li>
                        <li>
                            <a href="<?php echo base_url('n4radmin/view-group/renters'); ?>" class="text-warning"><i class="fa fa-fw fa-users"></i> Renters</a>
                        </li>
						<li>
                            <a href="<?php echo base_url('n4radmin/view-group/contractors'); ?>" class="text-success"><i class="fa fa-fw fa-wrench"></i> Contractors</a>
                        </li>
						<li>
                            <a href="<?php echo base_url('n4radmin/view-group/advertisers'); ?>" class="text-danger"><i class="fa fa-fw fa-tags"></i> Advertisers</a>
                        </li>
                    </ul>
                </li>
				<?php if($this->session->userdata('superadmin')) { ?>
					<li>
						<a href="#" data-target="#addNewAdmin" data-toggle="modal"><i class="fa fa-fw fa-user"></i> Add Admin</a>
					</li>
				<?php } ?>
                <li>
                    <a href="<?php echo base_url('n4radmin/supply-houses'); ?>"><i class="fa fa-building"></i> Supply Houses</a>
                </li>
                <li>
                    <a href="<?php echo base_url('n4radmin/affiliates'); ?>"><i class="fa fa-bullhorn"></i> Affiliates</a>
                </li>
                <li>
                    <a href="<?php echo base_url('n4radmin/logout'); ?>"><i class="fa fa-fw fa-power-off"></i> Logout</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>

    <div id="page-wrapper">

        <div class="container-fluid">
            <?php echo $output;?>
        </div>
    </div>
	
	<?php if($this->session->userdata('superadmin')) { ?>
		<!-- Add New Admin -->
		<div class="modal fade" id="addNewAdmin" tabindex="-1" role="dialog" aria-labelledby="addNewAdmin">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<?php echo form_open('n4radmin/add-new-admin'); ?>
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="addNewAdminLabel"><i class="fa fa-user"></i> Add New Admin</h4>
						</div>
						<div class="modal-body">
							<fieldset>
								<div class="form-group">
									<label class="control-label" for="name">Full Name</label>  
									<input id="name" name="name" type="text" placeholder="" class="form-control input-md" required="">
								</div>

								<div class="form-group">
									<label class="control-label" for="email">Email</label>  
									<input id="email" name="email" type="email" placeholder="" class="form-control input-md" required="">
								</div>

								<div class="form-group">
									<label class="control-label" for="password">Password</label>  
									<input id="password" name="password" type="password" placeholder="Must be at least 7 characters long" class="form-control input-md" required="">
								</div>

								<div class="form-group">
									<label class="control-label" for="super_admin">Super Admin</label>
									<div class="row">
										<div class="col-md-4">
											<div class="radio">
												<label for="super_admin_0">
													<input type="radio" name="super_admin" id="super_admin_0" value="y">
													Yes
												</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="radio">
												<label for="super_admin_1">
													<input type="radio" name="super_admin" id="super_admin_1" value="n">
													No
												</label>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group">
									<label class="control-label" for="super_admin">Send Details To User</label>
									<div class="row">
										<div class="col-md-4">
											<div class="checkbox">
												<label for="emailUserDetails">
													<input type="checkbox" name="email_user" id="emailUserDetails" value="y">
													Yes
												</label>
											</div>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Add User</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php } ?>
	
	
    <?php
        foreach($js as $file){
            echo "\n\t\t";
            ?><script src="<?php echo $file; ?>"></script><?php
        } echo "\n\t";
    ?>
</body>
</html>