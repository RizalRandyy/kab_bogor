<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from brandio.io/envato/iofrm/html/login9.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 27 Jul 2020 08:00:31 GMT -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="header_title"><?= $title; ?></title>
    <link rel="icon" href="<?= base_url('assets/img/logo-pemkab-bogor.png') ?>">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/iform/css/fontawesome-all.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/iform/css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/iform/css/iofrm-theme9.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/css/digit_box.css">
    <script src="<?php echo base_url() ?>assets/plugins/sweet-alert2/sweetalert.min.js"></script>
    <script src="<?= base_url() ?>assets/iform/js/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<style type="text/css">
    html, body {
        height: 100%;
    }

    #wrap {
        min-height: 100%;
    }

    #main {
        overflow:auto;
        padding-top: 20px;
       /* padding-top: 100px;
        padding-bottom:150px; /* this needs to be bigger than footer height*/*/
    }

    .footer {
        position: relative;
        margin-top: 0px;  /*negative value of footer height */
        height: 60px;
        clear:both;
        margin-top:-60px; 
        padding-top:20px;
        padding-right: 10px;
        background:#333333;
        text-align: center;
    } 
</style>