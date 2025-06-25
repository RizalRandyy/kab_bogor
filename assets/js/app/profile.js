mainApp.controller('profile', ['$scope', 'httpHandler', '$filter', '$attrs', 'Upload', function ($scope, httpHandler, $filter, $attrs, Upload) {
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

    const inputField = document.getElementById("phone");

    inputField.addEventListener("input", function () {
        const value = inputField.value;

        if (value.length === 1 && value[0] === "0") {
            inputField.value = "62";
        } else if(value.length === 1 && value[0] === "8"){
            inputField.value = "628";
        }
    });

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
        }

        Swal.fire({
            title: 'Anda yakin ingin menyimpan perubahan?',
            showCancelButton: true,
            confirmButtonText: 'Save',
            icon: 'warning',
        }).then((result) => {
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
        	formData.append('id', $scope.id_user);
            formData.append("photo", $scope.photos.photoUsers);

        if($skip){
            $.ajax({
                url: mainUrl + "profile/save_user",
                dataType: "JSON",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,                         
                type: 'post',
                success: function(resp) {
                    var result = resp;
                    if(result.status == true)
                    {
                        Swal.fire({
                            title: 'Success',
                            icon: 'success',
                            text: result.message,
                            confirmButtonText: "Oke",
                        }).then((result) => {
                            location.reload();
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
        })
    }


    $scope.clear = function(){
    	$scope.modal_title = 'Add Users';
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

    $scope.edit_user = function(id_user){

        var formData = new FormData();
        formData.append("id", id_user);
        $.ajax({
            url: mainUrl + "profile/detail_user",
            dataType: "JSON",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,                         
            type: 'post',
            success: function(resp) {
                var status = resp.status;
                var data = resp.data;
                if(status){
                    $scope.full_name = data.full_name;
                    $scope.nick_name = data.nick_name;
                    $scope.email = data.email;
                    $scope.phone = data.phone;
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

                    $scope.$apply(); 
                }else{
                    Swal.fire({
                        title: 'Fail',
                        text: 'User not found',
                        icon: 'warning'
                    });
                }
            }
        })    
    }

    $scope.getRole = function () {
        httpHandler.send({
            method: 'GET',
            url: urls + 'profile/getRoleUsers',
        }).then(
        function successCallbacks(response) {
            $scope.temp_role_user = response.data.data;
        },
        function errorCallback(response) {
            $scope.temp_role_user = response.data.data;
        }
        );
    }

    $scope.getRole();

}]);