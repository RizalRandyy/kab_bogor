<style type="text/css">
    .form-content{
        background-color: white !important;
    }
    .text_black{
        color: black !important;
    }
    .centered-hr {
        text-align: center; /* Membuat teks di tengah */
        border: none; /* Menghilangkan garis bawaan */
        border-top: 2px solid black; /* Menambahkan garis atas */
        margin: 10px auto; /* Memberi jarak atas dan bawah serta mengatur lebar garis */
        width: 50%; /* Mengatur lebar garis */
    }
    .centered-button {
        display: flex;
        justify-content: center; /* Mengatur tombol di tengah secara horizontal */
        align-items: center; /* Mengatur tombol di tengah secara vertikal (opsional) */
    }
    .imghold{
        width: 100%;
        max-width: 470px;
        position: absolute;
        padding-left: 100px;
        padding-top: 50px;
    }
    .form-body{
        border-radius: 50px;
    }
    .form-holder .form-content {
        border-radius: 50px;
    }
    .style_captcha{
        margin: 5px;
        padding-left: 15px;
    }

    .garis {
        margin-top: 1rem;
        margin-bottom: 1rem;
        border: 0;
        border-top: 1px solid rgba(0,0,0,.1);
        flex: 100%;
        max-width: 100%;
    }
    .or{
        margin-top: 0.2rem;
        margin-left: 0.3rem;
        margin-right:0.3rem; 
    }
    .form-forgot-password{
        margin-top: 100px;
        margin-bottom: 100px;
    }
    .test_margin{
        margin-bottom: 0px !important;
    }
</style>
<div class="form-body" style="background-color: white !important;">
    <div class="row">
        <div class="imghold">
            <img src="<?= base_url() ?>assets/img/logo.png" alt="" class="img-fluid" style="width: 30%; float: right;">
            <img src="<?= base_url() ?>assets/img/login_pic.jpg" width="350" alt="">    
        </div>
        <div class="form-holder">
            <div class="form-content" >
                <div class="form-items login" >
                    <div class="website-logo-inside">
                        <div class="logo-telin">
                            <h3 class="text_black" style="text-align: center !important;">Masuk</h3>
                        </div>
                    </div>
                    <form class="noEnterSubmit" action="<?= base_url('login') ?>" method="POST" id="login">
                        <input class="form-control" type="text" name="email" placeholder="Email" required style="border: 1px solid #007BFF;">
                        <input class="form-control" type="password" name="password" placeholder="Password" required style="border: 1px solid #007BFF;">
                        <div style=" text-align: right;">
                            <small class="text-primary forgotpassword"><a href="#">Lupa Password?</a></small>
                        </div>
                        <div class="row">
                            <div class="garis"></div>
                        </div>
                        <div class="form-button" style="margin-top: 5px;margin-bottom: 5px;">
                            <div class="row">
                                <button type="submit" class="btn btn-primary">Masuk</button>
                                <button id="signup" type="button" class="btn btn-success">Daftar</button>
                            </div>
                            <span style="color: white; float:right; display:none;" id="loader_otp">
                                <img src="<?= base_url('assets/img/normal_loader.gif') ?>" alt="loader" width="30" height="30">
                                Loading ...
                            </span>
                        </div>
                    </form>
                </div>
                <div class="form-items signup" >
                    <div class="website-logo-inside">
                        <div class="logo-telin">
                            <h3 class="text_black" style="text-align: center !important;">Daftar</h3>
                        </div>
                    </div>
                    <form class="noEnterSubmit" action="<?= base_url('api/users/register') ?>" method="POST" id="register">
                        <input class="form-control" type="text" name="full_name" placeholder="Nama Lengkap" required style="border: 1px solid #007BFF;">
                        <input class="form-control" type="text" name="nick_name" placeholder="Nama Panggilan" required style="border: 1px solid #007BFF;">
                        <input class="form-control" type="email" name="email" placeholder="Email" required style="border: 1px solid #007BFF;">
                        <input class="form-control" id="phone" name="phone" placeholder="No Hp (Contoh: 62812344556)" required style="border: 1px solid #007BFF;">
                        <input class="form-control password" type="password" name="password" placeholder="Password" required style="border: 1px solid #007BFF;">
                        <input class="form-control confirm-password" type="password" name="confirm-password" placeholder="Konfirmasi Password" required style="border: 1px solid #007BFF;">
                        <div class="warning mb-2">
                            <small class="text-danger"> password do not match</small>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <button id="btn_register" type="submit" class="btn btn-primary">Register</button>
                            </div>
                            <div class="col-md-6" style=" text-align: right;">
                                <small class="text-primary masuk"><a href="#">Kembali ke Login</a></small>
                            </div>
                            <div >
                            </div>
                        </div>
                    </form>
                </div>
                <div class="form-items form-forgot-password" >
                    <div class="website-logo-inside">
                        <div class="logo-telin">
                            <h3 class="text_black" style="text-align: center !important;">Lupa Password</h3>
                        </div>
                    </div>
                    <form class="noEnterSubmit" action="<?= base_url('api/users/forgot_password') ?>" method="POST" id="login">
                        <input class="form-control" type="email" name="email" placeholder="Input Email" required style="border: 1px solid #007BFF;">
                        <div class="row">
                            <div class="col-md-6">
                                <button id="btn_forgot" type="submit" class="btn btn-primary">Kirim</button>
                            </div>
                            <div class="col-md-6" style=" text-align: right;">
                                <small class="text-primary masuk"><a href="#">Kembali ke Login</a></small>
                            </div>
                            <div >
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
            if (textbox != null) {
                textbox.addEventListener(event, function () {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        this.value = "";
                    }
                });
            }
        });
    }

    $( document ).ready(function() {
        $(".signup").hide();
        $(".form-forgot-password").hide();
        $(".warning").hide();
    });
    $("#signup").click(function(){
        $(".login").hide();
        $(".signup").show();
        $(".form-forgot-password").hide();
        setTimeout(function(){
            setInputFilter(document.getElementById("phone"), function (value) {
                return /^-?\d*$/.test(value);
            });
        }, 1);

        const inputField = document.getElementById("phone");

        inputField.addEventListener("input", function () {
            const value = inputField.value;

            if (value.length === 1 && value[0] === "0") {
                inputField.value = "62";
            } else if(value.length === 1 && value[0] === "8"){
                inputField.value = "628";
            }
        });
        
    });

    $(".forgotpassword").on('click', function(event){
        $(".login").hide();
        $(".signup").hide();
        $(".form-forgot-password").show();
    });

    $(".masuk").click(function(){
        $(".login").show();
        $(".signup").hide();
        $(".form-forgot-password").hide();
    });

    $(".confirm-password").change(function(){
        if($('.password').val() != $('.confirm-password').val() ){
            $(".confirm-password").addClass('test_margin');
            $("#btn_register").prop('disabled', true);
            $(".warning").show();
        }else{
            $(".confirm-password").removeClass('test_margin');
            $("#btn_register").prop('disabled', false);
            $(".warning").hide();
        }

    });
</script>
