    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/js/stisla.js"></script>

    <!-- Template JS File -->
    <script src="<?= base_url() ?>assets/js/scripts.js"></script>
    <script src="<?= base_url() ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/app.js?') . rand() ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/js/main.js') ?>"></script>
    <script type="text/javascript" src="<?= base_url('assets/plugins/sweetalert2/dist/sweetalert2.all.min.js')?>"></script>
    <script type="text/javascript">
        $( document ).ready(function() {
            $(".warning").hide();
        });
        $("#btn_change_password").click(function(){
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: false,
                allowEscapeKey: false,
                allowOutsideClick: false,
                showClass: {
                    popup: "animated lightSpeedIn",
                },
                hideClass: {
                    popup: "animated lightSpeedOut",
                },
                onOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer);
                    toast.addEventListener("mouseleave", Swal.resumeTimer);
                },
            });
            var chg_password = $('#chg_password').val();
            var confirm_password = $('#confirm_password').val();
            if(!chg_password){
                $('#chg_password').focus();
                return Toast.fire({
                    title: "Peringatan",
                    text:  "Password Baru tidak boleh kosong!",
                    icon: 'warning'
                });
            } else if(!confirm_password){
                $('#confirm_password').focus();
                return Toast.fire({
                    title: "Peringatan",
                    text:  "Konfirmasi Password tidak boleh kosong!",
                    icon: 'warning'
                });
            } else if(chg_password != confirm_password){
                $('#confirm_password').focus();
                return Toast.fire({
                    title: "Peringatan",
                    text:  "Password tidak sama!",
                    icon: 'warning'
                });
            }

            var formData = new FormData();
            formData.append("id", "<?= $users['id'] ?>");
            formData.append("password", $('#chg_password').val()); 

            Swal.fire({
                title: 'Do you want to change password?',
                showCancelButton: true,
                confirmButtonText: 'yes',
                icon: 'warning',
            }).then((result) => {
                if(result.isConfirmed){
                    $.ajax({
                        url: mainUrl + "profile/update_password",
                        dataType: "JSON",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,                         
                        type: 'post',
                        success: function(resp) {
                            if(resp.status){
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Password Changed Successfully, Please sign in again!',
                                    icon: 'success'
                                }).then((result) => {
                                    location.reload('index.php/logout');
                                });
                                location.reload('index.php/logout');
                            }else{
                                Swal.fire({
                                    title: 'Fail',
                                    text: 'Password Failed to change',
                                    icon: 'warning'
                                });
                            }
                        }
                    }) 
                }

           })
        });

            // $("#confirm_password").change(function(){
            //     if($('#chg_password').val() != $('#confirm_password').val()){
            //         $(".warning").show();
            //         $("#btn_change_password").prop('disabled', true);
            //     }else{
            //         $(".warning").hide();
            //         $("#btn_change_password").prop('disabled', false);
            //     }
            // });
        </script>

    <script type="text/javascript">
        <?php if ($this->session->flashdata('alert-success')) : ?>
            Swal.fire({
                title: 'Success',
                text: '<?php echo $this->session->flashdata('alert-success'); ?>',
                icon: 'success'
            });
        <?php endif; ?>
        <?php if ($this->session->flashdata('alert-warning')) : ?>
            Swal.fire({
                title: 'Failed',
                text: '<?php echo $this->session->flashdata('alert-warning'); ?>',
                icon: 'warning'
            });
        <?php endif; ?>
        <?php if ($this->session->flashdata('alert-danger')) : ?>
            Swal.fire({
                title: 'Oops!',
                text: '<?php echo $this->session->flashdata('alert-danger'); ?>',
                icon: 'error'
            });
        <?php endif; ?>
    </script>

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