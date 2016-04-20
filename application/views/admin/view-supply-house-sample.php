<div class="widget">
	<div class="widget-header">
		<div class="title userImg">
			<i class="fa fa-file-o"></i> Sample Service Request
		</div>
		<div class="tools">
			<div class="btn-group hidden-print pull-right" style="margin-top: 4px">
				<button type="button" class="btn btn-info">Options</button>
				<button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
				</button>
				<ul class="dropdown-menu">
					<li><a href="#"><i class="fa fa-comments text-primary"></i> Message Tenant</a></li>
					<li><a href="#"><i class="fa fa-check-circle text-primary"></i> Rental Checklist</a></li>
					<li><a href="#"><i class="fa fa-download text-primary"></i> Download</a></li>
					<li><a href="#"><i class="fa fa-money text-primary"></i> Pay Rent</a></li>
					<li><a href="#"><i class="fa fa-check text-primary"></i> Mark Complete</a></li>
					<li><a href="#"><i class="fa fa-list text-primary"></i> Notes</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="widget-body">
		<div class="row">
			<div class="col-md-6">
				<img src="https://network4rentals.com/wp-content/themes/Network4Rentals.new/img/Network-4-Rentals-Property-Management-Software-logo.png" class="img-responsive" width="300" height="100">
			</div>
			<div class="col-md-6 text-right-md">
				<h4><b>Service Request</b></h4>
				<p>Submitted: <?php echo date('M, d Y @ h:i a'); ?></p>
				<p><b>Status:</b> <span class="text-danger">Incomplete</span></p>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h5 class="panel-title"><b>Landlord Details:</b></h5>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-md-6">
								<div><b>Name:</b></div>
								<div>John Landlord</div>
								<div><b>Phone:</b></div>
								<div>(555) 555-5555</div>
							</div>
							<div class="col-md-6">
								<div><b>Business Name:</b></div>
								<div>John's Rentals</div>
								<div><b>Email:</b></div>
								<div><a href="mailto:john@landlord.com">john@landlord.com</a></div>
							</div>
						</div>	
						<div><b>Mailing Address:</b><br class="visible-xs">555 Main St, Utica OH 43080</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title"><b>Renter Details:</b></h3>
					</div>
					<div class="panel-body">
						<div><b>Name: </b>Tom Renter</div>
						<div class="row">
							<div class="col-md-6">
								<div><b>Phone:</b></div>
								<div>(555) 555-5555</div>
							</div>
							<div class="col-md-6">
								<div><b>Email:</b></div>
								<div><a href="mailto:tom@renters.com">tom@renters.com</a></div>
							</div>
						</div>
						<div><b>Rental Address:</b><br class="visible-xs"> <a href="https://www.google.com/maps/place/555+Main+St+Utica+OH">555 Main St, Utica OH 43080</a></div>
					</div>
				</div>			
			</div>
		</div>
		<div id="supplyHouses" class="well">
			<h4><b>Need Supplies for the job?</b></h4> 
			<div class="row">
				<div class="col-md-6">
					<a href="" class="pull-left">
						<img src="https://network4rentals.com/network/<?php echo $house->logo; ?>" alt="<?php echo $house->business; ?>" class="aligncenter img-responsive" style="max-width: 80px">
					</a>
					<h4 style="margin-top:5px" class="text-primary"><b>Testing No Username</b></h4>
					<p>We have the supplies you need to complete any Drain Cleaning (Clogged Drain) need.<br><em><b>
								<a href="https://www.google.com/maps/place/<?php echo str_replace(' ', '+', $house->address.' '.$house->city.' '.$house->state); ?>" target="_blank">
									<?php echo $house->address.' '.$house->city.' '.$house->state; ?>
								</a></b></em></p>
				</div>
				<div class="col-md-6">
					<a href="" class="pull-left">
						<img src="https://network4rentals.com/network/uploads/2015/11/Lowes_Companies_Logo.svg_8.png" alt="emf_profilepic" class="aligncenter img-responsive" style="max-width: 80px">
					</a>
					<h4 style="margin-top:5px" class="text-primary"><b>Testing No Username</b></h4>
					<p>We have the supplies you need to complete any Drain Cleaning (Clogged Drain) need.<br><em><b><a href="https://www.google.com/maps/place/546+Main+St+Newark+OH">546 Main St, Newark OH</a></b></em></p>				
				</div>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><b>Service Request Details:</b></h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-3">
						<div><b>Service Type:</b></div>
					</div>
					<div class="col-lg-10 col-md-9">
						<div>Drain Cleaning (Clogged Drain)</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-lg-2 col-md-3">	
						<div><b>Permission To Enter:</b></div>
					</div>
					<div class="col-lg-10 col-md-9">
						<div class="text-danger">No</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-2 col-md-3">
						<div><b>Submitted:</b></div>
					</div>
					<div class="col-lg-10 col-md-9">
						<div>Nov, 19 2015 @ 01:40 pm</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-2 col-md-3">
						<div><b>Status:</b></div>
					</div>
					<div class="col-lg-10 col-md-9">
						<div class="text-success">Complete</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-2 col-md-3">
						<div><b>Description:</b></div>
					</div>
					<div class="col-lg-10 col-md-9">
						<div><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. In euismod accumsan efficitur. Nulla facilisi. Donec semper eu massa at malesuada. Vivamus ultrices, enim sed volutpat ullamcorper, sem elit porttitor justo, eget mollis velit dui nec dolor.</p></div>
					</div>
				</div>
						
				
				<div><b>Rental Address:</b><br class="visible-xs"> <a href="https://www.google.com/maps/place/555+Main+St+Utica+OH">555 Main St, Utica OH 43080</a></div>
			</div>
		</div>			
		
	</div>
</div>
