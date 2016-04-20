<div class="row">
	<div class="col-sm-6">
		<h2><i class="fa fa-paw text-primary"></i> Final Step</h2>
	</div>
	<div class="col-sm-6 text-right">
		<div class="status well well-sm">
			<p style="margin-top: 10px"><span class="pull-left waiting"><small><i class="fa fa-spinner fa-spin"></i> Waiting</small></span> Agreed To Terms Of Service: <span class="label label-danger agreed">No<span></p>
		</div>	
	</div>
</div>
<hr>
<div class="row fadeout">
	<div class="col-sm-6 removeOnSuccess">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h4 style="color: #ffffff;"><i class="fa fa-question"></i> Did The Customer Receive The Email?</h4>
			</div>
			<div class="panel-body">
				<p>If the customer has not received the email you can resend the email or change/resend the email again.</p>
				<hr>
				<div class="row">
					<div class="col-sm-6">
						<button id="resendEmail" class="btn btn-primary btn-sm btn-block">Resend Email</button>
					</div>
					<div class="col-sm-6">
						<button id="changeThatEmail" data-toggle="modal" data-target="#changeEmail" class="btn btn-primary btn-sm btn-block">Change Email Address</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-6 makeFull">
		<div class="panel panel-danger changeOnSuccess">
			<div class="panel-heading">
				<h4 style="color: #ffffff;"><i class="fa fa-user"></i> User Data:</h4>
			</div>
			<div class="panel-body">
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>Name: </b>
					</div>
					<div class="col-sm-9">
						<span class="name"><?php echo $user->f_name; ?> <?php echo $user->l_name; ?></span>
					</div>
				</div>						
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>Email: </b>
					</div>
					<div class="col-sm-9">
						<span class="email"><?php echo $user->email; ?></span>
					</div>
				</div>	
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>Username: </b>
					</div>
					<div class="col-sm-9">
						<span class="user"><?php echo $user->user; ?></span>
					</div>
				</div>	
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>Password: </b>
					</div>
					<div class="col-sm-9">
						<span class="pwd"><?php echo $this->session->userdata('pwd'); ?></span>
					</div>
				</div>	
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>Sub Id: </b>
					</div>
					<div class="col-sm-9">
						<?php echo $user->sub_id; ?>
					</div>
				</div>
				<div class="row" style="border-bottom: 1px solid #c8c8c8; margin-bottom: 5px; padding-bottom: 5px;">
					<div class="col-sm-3 text-right">
						<b>DB Id: </b>
					</div>
					<div class="col-sm-9">
						<span class="iddb"><?php echo $this->session->userdata('userID'); ?></span>
					</div>
				</div>
				<br>
				<div class="alert alert-danger agreedAlert">
					<b>Terms Of Service: </b> <span class="agreed">No</span>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="well well-sm removeOnSuccess">	
	<h3><strong>Last Resort Only:</strong></h3>
	<strong>Worse Case Scenario:</strong> If the customer cannot seem to get the email you can have them type the url in the browser. Not an ideal way of doing this so only use as a last resort.
	<h4><strong>URL:</strong> </h4>
	<p>http://network4rentals.com/network/contractors/terms-of-service-linked/<?php echo $this->session->userdata('hash'); ?></p>
</div>
<div class="modal fade" id="changeEmail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Change The Users Email Address And Send It</h4>
			</div>
			<div class="modal-body">
				<p>If the contractor has not received the email you can change the email address you are sending the email to and update the contractors account with the new email address all at once.</p>
				<div class="row">
					<div class="col-sm-8">
						<label>New Email Address:</label>
						<input type="email" class="email form-control" name="new_email">
						<br>
						<label><input type="checkbox" class="update" name="update" value="y"> Update Email Address?</label>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
				<button type="button" id="sendAndChange" class="btn btn-primary btn-sm" data-dismiss="modal">Change &amp; Send Email</button>
			</div>
		</div>
	</div>
</div>