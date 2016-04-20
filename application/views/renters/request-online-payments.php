<div class="panel panel-warning">
	<div class="panel-heading">
		<i class="fa fa-check"></i> Online Payments Request
	</div>
	<div class="panel-body">
		<?php
			if(!empty($sent['success'])) {
				echo '<div class="alert alert-success">'.$sent['success'].'</div>';
			} else {
				echo '<div class="alert alert-error">'.$sent['error'].'</div>';
			}
		?>
	</div>
</div>