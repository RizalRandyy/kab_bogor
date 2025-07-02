<!DOCTYPE html>
<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta http-equiv="content-type" content="text/html; charset=utf-8 ;">
		<title><?= !empty($title) ? $title : 'SHK KAB BOGOR' ?></title>
		<link rel="icon" href="<?= base_url() ?>assets/img/logo-pemkab-bogor.png" type="image/x-icon">

	<!-- General CSS Files -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/components.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">
	<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/fontawesome5/css/all.min.css">

		<?php
    if (isset($css) && count($css) > 0) {
        foreach ($css as $vcss) {
            echo '<link rel="stylesheet" type="text/css" href="' . base_url($vcss) . '">';
        }
    }
    ?>

    <link href='https://fonts.googleapis.com/css?family=Comfortaa' rel='stylesheet'>

	<!-- AngularJS -->
	<script src="<?= base_url("assets/plugins/angular/angular.min.js")?>"></script>
    <script src="<?= base_url("assets/plugins/angular/angular-sanitize.min.js")?>"></script>
    <script src="<?= base_url("assets/plugins/angular/dirPagination.js")?>"></script>
    <script src="<?= base_url("assets/plugins/angular/angular-locale_id-id.js")?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/plugins/ng-file-upload/dist/ng-file-upload-shim.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/plugins/ng-file-upload/dist/ng-file-upload.js') ?>"></script>
	<script type="text/javascript" src="<?= base_url('assets/js/angular/ngStorage.min.js') ?>"></script>


	<!-- end angularjs -->
	<!-- library tinymce -->
	<script type="text/javascript" src="<?= base_url('assets/plugins/tinymce/tinymce.js?') . date('Ymd-His') ?>">
	</script>
	<script type="text/javascript" src="<?= base_url('assets/plugins/angular-ui-tinymce/src/tinymce.js?') . date('Ymd-His') ?>"></script>
	<!-- end library tinymce -->

		<script type="text/javascript" src="<?= base_url('assets/js/app.js?') . rand() ?>"></script>
		<script type="text/javascript" src="<?= base_url('assets/js/main.js') ?>"></script>
	</head>
