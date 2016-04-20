<div class="panel panel-warning">
	<div class="panel-heading">
		<div class="row">
			<div class="col-sm-10">
				<i class="fa fa-comments"></i> Messages
			</div>
			<div class="col-sm-2">
				<button class="btn btn-primary btn-block" data-toggle="modal" data-target="#helpvideo">Need Help?</button>
			</div>
		</div>
	</div>
	<div class="panel-body">
		<?php
			if(!empty($results)) {
				echo '<ul class="message-list">';
				
				foreach($results as $key => $val) {
					echo '<li>';
						echo '<div class="row">';
							echo '<div class="col-sm-8">';
								if($val['new_messages'] >0) {
									$msgs = '<span class="label label-success" style="margin-right: 5px">'.$val['new_messages'].' New</span> ';
								} else {
									$msgs = '<span class="label label-default" style="margin-right: 5px">'.$val['new_messages'].' New </span> ';
								}
								if(empty($val['bName'])) {
									echo $msgs.'  '.htmlentities($val['name']).'<br>';
								} else {
									echo $msgs.'  '.htmlentities($val['bName']).' - '.htmlentities($val['name']);
								}
							echo '</div>';
							echo '<div class="col-sm-4">';
								echo '<div class="pull-right"><a href="'.base_url('renters/view-messages').'/'.$val['rental_id'].'" class="btn btn-primary"><i class="fa fa-envelope-o"></i> View/Send Messages</a></div>';
							echo '</div>';
						echo '</div>';
						echo '<hr></li>';
				}
				
				echo '</ul>';
			} else {
				echo 'You have not added any landlords to your account yet. Once you add landlords to you account you will see the option to message them on this screen.';
			}
				
		?>
	</div>
</div>
<div class="modal fade" id="helpvideo" tabindex="-1" role="dialog" aria-labelledby="info" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="fa fa-question text-warning"></i> How To Message Your Landlord</h4>
			</div>
			<div class="modal-body">
				<div align="center" class="embed-responsive embed-responsive-16by9">
					<iframe width="560" height="315" src="//www.youtube.com/embed/Sf8DHCa3EdQ" frameborder="0" allowfullscreen></iframe>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning btn-sm" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>