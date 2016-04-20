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
			foreach($js as $file){
				echo "\n\t\t";
				?><script src="<?php echo $file; ?>"></script><?php
			} echo "\n\t";
		?>
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
		
		
		<script>
			/* DONT FORGET TO CHANGE THIS OUT !!!!! */
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','http://www.google-analytics.com/analytics.js','ga');
			ga('create', '', 'iamsrinu.com');
			ga('send', 'pageview');
		</script>
	</head>

	<body>
	
	
		<?php echo $output;?>
		
		
		<script src="<?php echo base_url('assets\themes\blue-moon\js/jquery.js'); ?>"></script>
		<script src="<?php echo base_url('assets\themes\blue-moon\js/bootstrap.min.js'); ?>"></script>
	</body>	
</html>