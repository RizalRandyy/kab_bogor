mainApp.controller('user_manage', ['$scope', 'httpHandler', '$filter', '$attrs', 'Upload', function ($scope, httpHandler, $filter, $attrs, Upload) {
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



	$scope.pageno = 1;
	$scope.itemsPerPage = '10';
	$scope.keyword = {};
	$scope.search_Method ={};
	$scope.total_count = 0;
	$scope.message = null;
	$scope.id_user = '';
    $scope.photos = {
        photoUsers: '',
    }
    $scope.full_name = '';
    $scope.nick_name = '';
    $scope.email = '';
    $scope.phone = '';
    $scope.role_user = '';
    $scope.status = '';
    $scope.password = '';
    $scope.id_user = '';

    $scope.getUsers = function (pageno) {
        $scope.total_count = 0;
        $scope.message = null;

        if (pageno == 0) {
            $scope.pageno = 1;
        } else {
            $scope.pageno = pageno * $scope.itemsPerPage - ($scope.itemsPerPage - 1);
        }
        var params = {
            keyword: $scope.keyword ,
            limit: $scope.itemsPerPage,
            offset: pageno != 0 ? pageno : 1,
        }

        $scope.loading = true;

        httpHandler.send({
            method: 'GET',
            url: urls + 'user_manage/getUsers',
            params: params
        }).then(
        function successCallbacks(response) {
            $scope.loading = false;
            $scope.data = response.data.data;
            $scope.total_count = response.data.count;
            $scope.message = response.data.message;
            $scope.curPage = pageno;
            $scope.table_header = response.data.header;
        },
        function errorCallback(response) {
            $scope.loading = false;
            $scope.data = response.data.data;
            $scope.total_count = response.data.count;
            $scope.curPage = pageno;
            $scope.message = response.data.message;
        }
        );
    }

    $scope.searchMethod = (keyname, val) => {
        var val_a = null;
        if(keyname == 'date_of_birth'){

            val_a = $scope.formatdate(val);

        }else{
            val_a = val;
        }
        $scope.keyword[keyname] = val_a;

        $scope.getUsers(1);
    };

    $scope.formChangeRoles = function (params = false) {
    	$('#ChangeRoles').modal('show');
    	$scope.id = params.id;
    	$scope.role_id = params.role_id;
    	$scope.role_id_old = params.role_id;
    }

    $scope.closeFormChangeRoles = function () {
    	$('#ChangeRoles').modal('hide');
    	$scope.id = null;
    	$scope.role_id = null;
    	$scope.role_id_old = null;
    	$scope.getUsers(1);
    }

    $scope.saveChangeRole = function () {
    	if ($scope.role_id == $scope.role_id_old) {
    		return Toast.fire({
    			icon: "warning",
    			title: 'Sorry, role users same with old role!',
    		});
    	}
    	var formData = new FormData();
    	formData.append("id", $scope.id);
    	formData.append("role_id", $scope.role_id);

        Swal.fire({
            title: 'Loading...',
            allowEscapeKey: false,
            allowOutsideClick: false,
            showConfirmButton: false,
            imageUrl: urls + "assets/img/loadertsel.gif",
        });

    	httpHandler.send({
    		url: urls + 'user_manage/saveChangeRole',
    		data: formData,
    		method: 'POST',
    		headers: {
    			'Content-Type': undefined
    		}
    	}).then(
    	function successCallbacks(response) {
    		Swal.close();
    		Swal.fire({
    			title: 'Success',
    			text: response.data.message,
    			icon: 'success',
    			showCancelButton: false,
    			allowEscapeKey: false,
    			allowOutsideClick: false,
    			confirmButtonColor: "#39edab",
    			confirmButtonText: "Okey, redirect to list",
    		}).then((result) => {
    			if (result.value) {
    				$scope.closeFormChangeRoles();
    			}
    		});
    	},
    	function errorCallback(response) {
    		Swal.close();
    		if (response.data.status == 400) {
    			return Toast.fire({
    				icon: "warning",
    				title: response.data.message,
    			});
    		}

    		if (response.data.status != 400) {
    			Swal.fire({
    				title: 'Failed',
    				text: response.data.message,
    				icon: 'error',
    				showCancelButton: false,
    				allowEscapeKey: false,
    				allowOutsideClick: false,
    				confirmButtonColor: "#fc544b",
    				confirmButtonText: "Okey, refresh page",
    			}).then((result) => {
    				if (result.value) {
    					location.reload();
    				}
    			});
    		}
    	});
    }

    $scope.reset = function(is_master)
    {
    	$scope.keyword = {};
    	$scope.search_Method = {};
    	if(is_master == "master"){
    		$scope.getUsers(1);
    	}
    }

    if($attrs.id == 'user_manage'){
    	$scope.getUsers(0);
    }else{
    	$scope.getRole(1);
    }

    $scope.show_modal = function($action, $data = false){
    	$('#modal_users').modal('show');
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
    	if($action == 'add'){
    		$scope.modal_title = 'Tambah User'; 
    		$scope.clear();
    	}else{
    		$scope.modal_title = 'Edit User';
    		$scope.edit_user($data);
    	}
    }

    $scope.show_pwd = function(data){
        $('#change_pwd').modal('show');
        $scope.id_user_chg = data.id;
        $scope.full_name_chg = data.full_name;
    }

    $scope.save_user = function()
    {
        $skip = true;
        if(!$scope.full_name){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "Nama Lengkap tidak boleh kosong!",
                icon: 'warning'
            });
        } else if(!$scope.nick_name){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "Nama Panggilan tidak boleh kosong!",
                icon: 'warning'
            });
        } else if(!$scope.email){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "Email tidak boleh kosong!",
                icon: 'warning'
            });
        } else if(!$scope.phone){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "No Telepon tidak boleh kosong!",
                icon: 'warning'
            });
        } else if(!$scope.role_user){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "User Role tidak boleh kosong!",
                icon: 'warning'
            });
        } else if(!$scope.status){
            $skip = false;
            return Toast.fire({
                title: "Peringatan",
                text:  "Status tidak boleh kosong!",
                icon: 'warning'
            });
        }

    	if($scope.id_user){
            var title = 'Anda yakin ingin menyimpan perubahan?';
        }else{
            var title = 'Anda yakin ingin menambah user baru?';
        }
         
        Swal.fire({
            title: title,
            showCancelButton: true,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Kembali'
        }).then((result) => {
            if(result.isConfirmed){
                $skip = true;

                var kode = $scope.phone.substring(0, 2);

                if(kode != '62'){
                    $scope.phone = '62'+$scope.phone;
                }

            	var formData = new FormData();

            	formData.append('full_name', $scope.full_name);
            	formData.append('nick_name', $scope.nick_name);
            	formData.append('email', $scope.email);
            	formData.append('phone', $scope.phone);
            	formData.append('role_id', $scope.role_user);
            	formData.append('status', $scope.status);
            	formData.append('password', $scope.password);
            	formData.append('id', $scope.id_user);
                formData.append("photo", $scope.photos.photoUsers);

                if($skip){
                    Swal.fire({
                        title: 'Loading...',
                        allowEscapeKey: false,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        imageUrl: urls + "assets/img/loadertsel.gif",
                    });
                    $.ajax({
                        url: mainUrl + "user_manage/save_user",
                        dataType: "JSON",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,                         
                        type: 'post',
                        success: function(resp) {
                            Swal.close();
                            var result = resp;
                            if(result.status == true)
                            {
                                $scope.getUsers(1);
                                $("#modal_users").modal('hide');
                                $scope.close_modal_user();

                                Swal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: result.message,
                                }).then((result) => {
        							if (result.value) {
        								location.reload();
        							}
        						});

                            }else{
                                Swal.fire({
                                    title: "Warning",
                                    icon: 'warning',
                                    text: result.message,
                                    cancelButtonText: "OK",
                                    showCloseButton: true
                                })
                            }
                        }
                    })    
                }
            }
        })
    }

    $scope.getRole = function () {
    	httpHandler.send({
    		method: 'GET',
    		url: urls + 'user_manage/getRoleUsers',
    	}).then(
    	function successCallbacks(response) {
    		$scope.temp_role_user = response.data.data;
    	},
    	function errorCallback(response) {
    		$scope.temp_role_user = response.data.data;
    	}
    	);
    }

    $scope.clear = function(){
    	$scope.modal_title = 'Tambah User';
    	$scope.full_name = '';
    	$scope.nick_name = '';
    	$scope.email = '';
    	$scope.phone = '';
    	$scope.role_user = '';
    	$scope.status = '';
    	$scope.password = '';
    	$scope.id_user = '';

        $('.img-preview').empty();
        $scope.photos.photoUsers = '';

    }

    $scope.close_modal_user = function(){
    	$scope.clear();
    }

    $scope.edit_user = function(data){
    	$scope.full_name = data.full_name;
    	$scope.nick_name = data.nick_name;
    	$scope.email = data.email;
    	$scope.phone = data.phone.substring(2);
    	$scope.role_user = data.role_id;
    	$scope.status = data.status;
    	$scope.password = '';
    	$scope.id_user = data.id;

        if(data.photo){
            $scope.photos.photoUsers = data.photo;
            $('#file-banner').find('.img-preview').html('<img id="preview-photo-file-banner" class="img-fluid preview-photo preview-photo-fill">');
            var output = document.getElementById('preview-photo-file-banner');
            output.src = mainUrl+data.photo;
        }
    }

    $scope.getRole();


    $scope.delete_user = function(id)
    {
    	Swal.fire({
    		title: 'Anda yakin ingin menghapus data ini?',
    		text: "Anda tidak akan dapat mengembalikan ini!",
    		icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Kembali'
    	}).then((result) => {
    		if (result.isConfirmed) 
    		{
    			httpHandler.send({
    				method: 'POST',
    				url: mainUrl + "user_manage/delete_user",
    				data: ({
    					'id': id
    				})

    			}).then(function successCallback(response) {
                    if(response.data.status == true)
                    {
                        $scope.getUsers(1);
                        Swal.fire(
                            'Berhasil!',
                            'Data berhasil dihapus.',
                            'success'
                            )
                    }else{
                        Swal.fire(
                            'Gagal!',
                            'Data gagal dihapus.',
                            'warning'
                            )
                    }
                })
    		}
    	})
    }

    $scope.update_password = function(){

    	var password = $('#chg_password_manage').val();
    	var confirm_pass = $('#confirm_password_manage').val();

    	if(password != confirm_pass){
    		Swal.fire({
                    title: 'Gagal',
                    text: 'Password tidak sama',
                    icon: 'warning'
                });
    		return;
    	}

        var formData = new FormData();
        formData.append("id", $scope.id_user_chg);
        formData.append("password", password);
        formData.append("confirm_pass", confirm_pass);

        Swal.fire({
            title: 'Anda yakin ingin mengganti password?',
            icon: "info",
            showCancelButton: true,
            allowEscapeKey: false,
            allowOutsideClick: false,
            cancelButtonColor: "#808080",
            confirmButtonColor: "#007bff",
            confirmButtonText: "Simpan!",
            cancelButtonText: "Kembali",
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: mainUrl + "user_manage/update_password",
                    dataType: "JSON",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,                         
                    type: 'post',
                    success: function(resp) {
                        if(resp.status){
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Password berhasil diganti',
                                icon: 'success'
                            }).then(function() {
                                location.reload();
                            });
                        }else{
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Password gagal diganti',
                                icon: 'warning'
                            });
                        }
                    }
                }) 
            }

        })
    }

}]);