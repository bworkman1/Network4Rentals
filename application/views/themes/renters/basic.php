<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>

    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Network4Rentals" />
    <link href='https://fonts.googleapis.com/css?family=Alegreya|Open+Sans' rel='stylesheet' type='text/css'>
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


 
</head>

<body>


<?php echo $output;?>


<script src="<?php echo base_url('assets\themes\blue-moon\js/jquery.js'); ?>"></script>
<script src="<?php echo base_url('assets\themes\blue-moon\js/bootstrap.min.js'); ?>"></script>
</body>
</html>