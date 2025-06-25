<script src="<?= base_url() ?>assets/iform/js/popper.min.js"></script>
<script src="<?= base_url() ?>assets/iform/js/bootstrap.min.js"></script>
<script src="<?= base_url() ?>assets/iform/js/main.js"></script>
<script type="text/javascript">
    $( document ).ready(function() {
        $("#submit").hide();
        $("#submit_type").show();
        $(".invalid-feedbacks").hide();
        $(".signup").hide();
        $(".login").show();
        
    });
    
    function onCaptchaChange(response) {
        if(response){
            $("#submit").show();
            $("#submit_type").hide();
            $(".invalid-feedbacks").hide();
        }
    }

    function oncheck() {
        $(".invalid-feedbacks").show();
    }

    $('.noEnterSubmit').keypress(function(e){
        if ( e.which == 13 ) return false;
        //or...
        // if ( e.which == 13 ) e.preventDefault();
    }); 
</script>


<?php if (!empty($js)) : ?>
    <?php foreach ($js as $key => $value) : ?>
        <script src="<?= base_url($value) ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>
<script type="text/javascript">
    <?php if ($this->session->flashdata('success')) : ?>
      swal("Success", '<?php echo $this->session->flashdata('success'); ?>', "success");
  <?php endif; ?>
  <?php if ($this->session->flashdata('error')) : ?>
      swal("Oops!", '<?php echo $this->session->flashdata('error'); ?>', "error");
  <?php endif; ?>
</script>

</html>