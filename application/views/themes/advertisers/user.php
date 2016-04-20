<!DOCTYPE HTML>
<html lang="en">
<head>
    <title><?php echo $title; ?></title>
    <meta name="resource-type" content="document" />
    <meta name="robots" content="all, index, follow"/>
    <meta name="googlebot" content="all, index, follow" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/custom-advertisers.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/themes/default/css/local-partner/styles.css" rel="stylesheet">
    <?php
    if(!empty($meta))
        foreach($meta as $name=>$content){
            echo "\n\t\t";
            ?><meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
        }
    echo "\n";

    if(!empty($canonical))
    {
        echo "\n\t\t";
        ?><link rel="canonical" href="<?php echo $canonical?>" /><?php

    }
    echo "\n\t";

    foreach($css as $file){
        echo "\n\t\t";
        ?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
    } echo "\n\t";
    ?>
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon"/>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', 'UA-43913727-1', 'auto');
        ga('require', 'linkid', 'linkid.js');
        ga('send', 'pageview');
    </script>
	<!--Start of Zopim Live Chat Script-->
	<script type="text/javascript">
	window.$zopim||(function(d,s){var z=$zopim=function(c){
	z._.push(c)},$=z.s=
	d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
	_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
	$.src='//v2.zopim.com/?3o3ZM56IyHsAJ3CUO7MB7a42jgLlLeRE';z.t=+new Date;$.
	type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
	</script>
	<!--End of Zopim Live Chat Script-->
</head>

<body>
<!--[if lt IE 8]>
<p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->
<div id="top_bar">
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-md-5'>
                Improving Landlord &amp; Tenant Relations Nationwide
            </div>
            <div class='col-md-7'>
            </div>
        </div>
    </div>
</div>
<div id="header">
    <div class='container-fluid'>
        <div class='row'>
            <div class='col-lg-2 col-md-3 col-sm-5'>
                <a href="https://network4rentals.com"><img class="img-responsive logo" src="<?php echo base_url(); ?>assets/themes/default/images/N4R-Property-Management-Software-logo.png" alt="Network 4 Rentals"></a>
            </div>
        </div>
    </div>
</div>

<div id="content" class="container-fluid">
    <hr>
    <div class="row">
        <div class="col-md-2">
            <nav class="navbar navbar-default" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand visible-xs visible-sm" href="#">Menu</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav nav-stacked">
                        <li><a href="<?php echo base_url('local-partner/home'); ?>"><i class="fa fa-file fa-fw"></i> Home</a></li>
                        <li><a href="<?php echo base_url('local-partner/my-account'); ?>"><i class="fa fa-gears fa-fw"></i> My Account</a></li>
                        <!-- <li><a href="<?php echo base_url('local-partner/my-zips'); ?>"><i class="fa fa-diamond fa-fw"></i> Premium Ads</a></li>-->
                       <!-- <li><a href="<?php echo base_url('local-partner/stats'); ?>"><i class="fa fa-bar-chart-o fa-fw"></i> Stats</a></li>-->
                        <li><a href="<?php echo base_url('local-partner/my-website'); ?>"><i class="fa fa-laptop fa-fw"></i> My Web Site</a></li>
						
						<?php
							if($this->uri->segment(2)=="payment-settings" || $this->uri->segment(2)=="view-payments") {
								echo '<li class="dropdown open">';
							} else {
								echo '<li class="dropdown">';
							}
						?>
					
					
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-bullseye fa-fw"></i> Payments <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							
								<li class="<?php if($this->uri->segment(2)=="payment-settings"){echo "active";}?>"><a href="<?php echo base_url('local-partner/payments'); ?>"><i class="fa fa-gear fa-fw"></i> Payment Settings</a></li> 
								
								<li class="<?php if($this->uri->segment(2)=="view-payments"){echo "active";}?>"><a href="<?php echo base_url(); ?>local-partner/payments/view_payments"><i class="fa fa-money fa-fw"></i> View Payments</a></li>
								
								<li class="<?php if($this->uri->segment(2)=="create-invoice"){echo "active";}?>"><a href="<?php echo base_url('local-partner/payments/create_invoice'); ?>"><i class="fa fa-plus-circle fa-fw"></i> Create Invoice</a></li>
								
								<li class="<?php if($this->uri->segment(2)=="view-invoices"){echo "active";}?>"><a href="<?php echo base_url('local-partner/payments/view_invoices'); ?>"><i class="fa fa-list fa-fw"></i> All Invoice</a></li>
							</ul>
						</li>
									
						
                        <!--<li><a href="http://network4rentals.com/faqs/"><i class="fa fa-info fa-fw"></i> FAQ"s</a></li>-->
                        <li><a href="http://network4rentals.com/help-support/"><i class="fa fa-question fa-fw"></i> Help/Support</a></li>
                        <li><a href="<?php echo base_url('local-partner/logout'); ?>"><i class="fa fa-lock fa-fw"></i> Logout</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </nav>
        </div>

        <div class="col-md-7">
            <?php echo $output;?>
        </div>

        <div class="col-md-3">
            <?php echo $this->load->get_section('sidebar'); ?>
        </div>
    </div>
    <hr/>
</div> <!-- container -->



<footer>
    <div class='container'>
        <div class="row text-center">
            <div class="col-sm-6">
                &copy; Copyright Network 4 Rentals L.L.C. 2014 All Rights Reserved</a>
            </div>
            <div class="col-sm-6">
                Developed &amp; Hosted By EMF Web Solutions
            </div>
        </div>
    </div>
</footer>


<div class="modal fade" id="helpSupport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Help And Support</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-6">
                        <h3><i class="fa fa-phone text-primary"></i> By Phone</h3>
                        <hr>
                        <b>Customer Service:</b> (740) 403-7661
                    </div>
                    <div class="col-sm-6">
                        <h3><i class="fa fa-envelope text-primary"></i> By Email</h3>
                        <hr>
                        <a href="http://network4rentals.com/help-support/" target="_blank" class="btn btn-default btn-sm">On-line</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<?php
    foreach($js as $file){
        echo "\n\t\t";
        ?><script src="<?php echo $file; ?>"></script><?php
    } echo "\n\t";
?>
<!-- INTERCOM IO STARTS -->
		
		<!--INTERCOM IO END -->
<script type="text/javascript" src="<?php echo base_url('assets/themes/default/js/local-partner/partner.js'); ?>"></script>

</body>
</html>
