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
                            <h3 class="text_black" style="text-align: center !important;">Aktivasi Email</h3>
                        </div>
                    </div>
                    <form class="noEnterSubmit" action="<?= base_url('api/users/aktivasi') ?>" method="POST" id="login">
                        <input class="form-control" type="hidden" name="id" value="<?= $id?>" required style="border: 1px solid #007BFF;">
                        <input class="form-control" type="text" value="<?= $user->full_name?>" disabled style="border: 1px solid #007BFF;">
                        <input class="form-control" type="text" value="<?= $user->email?>" disabled style="border: 1px solid #007BFF;">
                        <input class="form-control" type="text" value="<?= $user->phone?>" disabled style="border: 1px solid #007BFF;">
                        <div class="row">
                            <div class="garis"></div>
                        </div>
                        <div class="form-button" style="margin-top: 5px;margin-bottom: 5px;">
                            <div class="row">
                                <button id="btn_submit" type="submit" class="btn btn-primary">Aktivasi</button>
                            </div>
                            <span style="color: white; float:right; display:none;" id="loader_otp">
                                <img src="<?= base_url('assets/img/normal_loader.gif') ?>" alt="loader" width="30" height="30">
                                Loading ...
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
