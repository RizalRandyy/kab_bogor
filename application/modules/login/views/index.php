<style type="text/css">
    body {
        margin: 0;
        font-family: Arial, Helvetica, sans-serif;
        background-color: #ffffff;
    }

    .form-body {
        min-height: 80vh;
        max-width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        /* padding: 20px; */
        background: none !important;
    }

    .login {
        width: 100%;
        max-width: 500px;
        height: 500px;
        background: #fff;
        border-radius: 20px;
        /* padding: 30px 25px; */
        /* margin-bottom: 100px;
        margin-top: 100px; */
    }

    .login img {
        pointer-events: none;
    }

    .text_black {
        color: #333;
    }

    .garis {
        margin: 1.2rem 0;
        border: 0;
        border-top: 1px solid rgba(0,0,0,.15);
    }

    .login h5 {
        margin-bottom: 4px;
    }

    .login span {
        font-size: 14px;
        color: #555;
    }

    .form-control {
        width: 100%;
        max-width: 400px;   /* batasi lebar input */
        margin: 0 auto 12px; /* biar center */
        display: block;
        padding: 10px 12px;
        border-radius: 6px;
        border: 1px solid #007BFF;
        font-size: 14px;
    }


    .form-control:focus {
        outline: none;
        border-color: #0056b3;
        box-shadow: 0 0 4px rgba(0,123,255,0.4);
    }

    .form-button {
        margin-top: 10px;
    }

    .form-button button {
        min-width: 100px;
        padding: 8px 14px;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #007BFF;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-dark {
        background-color: #343a40;
        border: none;
    }

    .btn-dark:hover {
        background-color: #1d2124;
    }
</style>

<div class="form-body">
    <div class="login text-center">
        <div class="p-5">
        <img src="<?= base_url('assets/img/logo_kab_bogor_hitam_regular.png') ?>" alt="Logo Kabupaten Bogor" width="200">
        <div class="rounded mt-2">
            <h5 class="font-weight-bold mb-1" style="font-size: medium;">APLIKASI</h5>
            <h6 style="font-size:small">STANDAR HARGA KONSTRUKSI</h6>
        </div>
        <h4 class="text_black mt-5" >Masuk</h4>

        <form class="noEnterSubmit mt-3" action="<?= base_url('login/masuk') ?>" method="POST" id="login">
            <input class="form-control" type="text" name="email" placeholder="Email" required>
            <input class="form-control" type="password" name="password" placeholder="Password" required>

            <hr class="garis">

            <div class="form-button d-flex justify-content-center gap-2">
                <button type="submit" class="btn btn-primary mr-2">Masuk</button>
                <button id="back" type="button" class="btn btn-dark">Kembali</button>
            </div>
        </form>
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
            var element_tkp = document.getElementById('header_title');
                element_tkp.innerText = "Masuk";
            $(".loader").hide();
            $(".warning").hide();
        });
        $("#back").click(function(){
            var real_url = window.location.pathname;
            var url_arr = real_url.split("/");
            if ((url_arr[1].toLowerCase() == 'kab_bogor') && (url_arr[1] != null || url_arr[1] != '')) {
                var urls = window.location.origin + '/' + url_arr[1] + '/';
            } else {
                var urls = window.location.origin + '/';
            }
            window.location.replace(urls);
            
        });
    </script>
