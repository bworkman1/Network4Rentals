<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $title; ?></title>
		
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="Network4Rentals" />
		
		<?php
			if(!empty($meta)) {
				foreach($meta as $name=>$content) {
					echo "\n\t\t";
					?><meta name="<?php echo $name; ?>" content="<?php echo is_array($content) ? implode(", ", $content) : $content; ?>" /><?php
				}
			} else {
				echo '<meta name="description" content="Network4Rentals property management software for renters" />';
				echo '<meta name="keywords" content="Network4Rentals, Property Management, Software" />';
			}
		?>		
		
		<link href="<?php echo base_url('assets\themes\blue-moon\css\bootstrap.min.css'); ?>" rel="stylesheet">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
		<link href="<?php echo base_url('assets\themes\blue-moon\css\new.css'); ?>" rel="stylesheet">
		
		<link rel="icon" type="image/x-icon" href="<?php echo base_url('assets\themes\blue-moon\img\favicons\favicon.ico'); ?>" />
		<link rel="icon" type="image/png" href="<?php echo base_url('assets\themes\blue-moon\img\favicons\favicon.png'); ?>" />
		<link rel="icon" type="image/gif" href="<?php echo base_url('assets\themes\blue-moon\img\favicons\favicon.gif'); ?>" />
		
		<?php
			foreach($css as $file){
				echo "\n\t\t";
		?><link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
			} echo "\n\t";
		?>
		
		<!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	
	</head>

	<body>
		<?php echo $this->load->get_section('header'); ?>
		
			<?php echo $this->load->get_section('nav'); ?>
		
				<!-- Dashboard Wrapper Start -->
				<div class="dashboard-wrapper">
					<div class="row">
						<div class="col-lg-10 col-md-9">
							<?php echo $output;?>
						</div>
						<div class="col-lg-2 col-md-3">
							<?php echo $this->load->get_section('ads'); ?>
						</div>
					</div>
				</div>
			
			<footer>
				<p>&copy; <?php echo date('Y'); ?> Network4Rentals LLC </p>
			</footer>
			
			</div>
		</div>
		
		<script src="<?php echo base_url('assets/themes/blue-moon/js/jquery.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/bootstrap.min.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/menu.js'); ?>"></script>

		<script src="<?php echo base_url('assets/themes/blue-moon/js/sparkline.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/jquery.easing.1.3.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/jquery-barIndicator.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/custom-barIndicator.js'); ?>"></script>
		<script src="<?php echo base_url('assets/themes/blue-moon/js/jquery.scrollUp.js'); ?>"></script>

		<?php
			foreach($js as $file){
				echo "\n\t\t";
				?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";
		?>
		<script type="text/javascript">
			//ScrollUp
			$(function () {
				$.scrollUp({
				  scrollName: 'scrollUp', // Element ID
				  topDistance: '300', // Distance from top before showing element (px)
				  topSpeed: 300, // Speed back to top (ms)
				  animation: 'fade', // Fade, slide, none
				  animationInSpeed: 400, // Animation in speed (ms)
				  animationOutSpeed: 400, // Animation out speed (ms)
				  scrollText: 'Top', // Text for element
				  activeOverlay: false, // Set CSS color to display scrollUp active point, e.g '#00FFFF'
				});
				$('.toolTips').tooltip();
				 $('[data-toggle="popover"]').popover();
			});

			//Tooltip
		
				
			// SparkLine Bar
			$(function () {
				$("#emails").sparkline([3,2,4,2,5,4,3,5,2,4,6,9,12,15,12,11,12,11], {
					type: 'line',
					width: '200',
					height: '70',
					lineColor: '#3693cf',
					fillColor: '#e5f3fc',
					lineWidth: 3,
					spotRadius: 6
				});
			});
		</script>
	</body>	
</html>