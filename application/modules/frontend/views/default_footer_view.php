    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/js/stisla.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.easing.1.3.js"></script>
    <script src="<?= base_url() ?>assets/js/page/bootstrap.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.waypoints.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.stellar.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.mb.YTPlayer.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/owl.carousel.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url() ?>assets/js/page/magnific-popup-options.js"></script>
    <script src="<?= base_url() ?>assets/js/page/jquery.countTo.js"></script>
    <script src="<?= base_url() ?>assets/js/page/main.js"></script>

    <!-- Template JS File -->
    <!-- <script src="<?= base_url() ?>assets/js/scripts.js"></script> -->
    <script src="<?= base_url() ?>assets/js/custom.js"></script>

    <!-- Page Specific JS File -->
    <!-- <script src="<?= base_url() ?>assets/js/page/index.js"></script> -->

    <?php
    if (isset($js) && count($js) > 0) {
        foreach ($js as $key => $vjs) {
            echo '<script type="text/javascript" src="' . (is_int($key) ? base_url($vjs) : $vjs) . '"></script>';
        }
    }
    ?>

</html>