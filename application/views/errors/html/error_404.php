<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>404 Page Not Found</title>
	<!-- General CSS Files -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
	<!-- <link rel="stylesheet" type="text/css" href="<?= base_url() ?>resources/css/style.css"> -->
<style type="text/css">

::selection { background-color: #E13300; color: white; }
::-moz-selection { background-color: #E13300; color: white; }

body {
	background: url(<?= base_url('assets/img/404.jpg') ?>) fixed;
	background-size: cover;
}

.row{
	margin-top: 130px;
}

h1{
	font-size: 3.5em;
	font-family: 'Bebas Neue Bold'
}

</style>
</head>
<body>

	<div class="container">
		<div class="row">
			<div class="col-xs-6 col-xs-offset-2">
				<h1><?php echo $heading; ?></h1>
				<p><?php echo $message; ?></p>

				<a href="<?= base_url() ?>" class="btn btn-primary">Back to Home</a>
			</div>
		</div>
	</div>
</body>
</html>