<div class="row">
	<div class="col-md-6">
		<h3 style="margin-top: 0;"><i class="fa fa-plus-circle"></i> Create Invoice</h3>
	</div>
</div>
<?php
	if($this->session->flashdata('success')) {
		echo '<div class="alert alert-success"><b><i class="fa fa-check-circle"></i> Success:</b> '.$this->session->flashdata('success').'</div>';
	}
?>
<hr>
<div class="well">
	<form action="#" id="create-invoice" method="post" accept-charset="utf-8" data-type="<?php echo $this->uri->segment(1); ?>" enctype="multipart/form-data" _lpchecked="1" data-url="<?php echo current_url(); ?>">					
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					<label><span class="text-danger">*</span> Name:</label>
					<input type="text" name="name" class="form-control money" maxlength="60" value="" required="">
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label><span class="text-danger">*</span> Amount:</label>
							<input type="text" name="amount" class="form-control money" maxlength="15" value="" required="">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label><span class="text-danger">*</span> Invoice Number:</label>
							<input type="text" name="invoice" class="form-control" value="" maxlength="25" required="">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label>Payee Email: <small class="text-primary">receive invoice to email</small></label>
					<input type="email" name="email" class="form-control" value="" maxlength="70">
				</div>
				<div class="form-group">
					<label>Attach Invoice File:</label>
					<input type="file" id="file" name="file" class="form-control">
				</div>
				<div class="form-group">
					<label>
						<input type="checkbox" name="send_email" value="y">
						Send Invoice By Email:
					</label>
				</div>
				
				
			</div>
			<div class="col-md-6">
				<label>Note:</label>
				<textarea class="form-control" name="note" style="height: 200px" maxlength="500"></textarea>
			</div>
			
		</div>
		
		<div class="row">
			<div class="col-md-6">
				
				<div id="feedback"></div>
			</div>
		</div>
		<button type="submit" id="submit" class="btn btn-primary btn-lg">Create Invoice</button>
	</form>
</div>

