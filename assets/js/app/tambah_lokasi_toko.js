mainApp

.directive('customOnChange', function() {
  return {
    restrict: 'A',
    link: function (scope, element, attrs) {
      var onChangeFunc = scope.$eval(attrs.customOnChange);
      element.bind('change', onChangeFunc);
    }
  };
})

.controller('tambah_lokasi_toko', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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

	$scope.id = !$("#id").val() ? null : $("#id").val();

	if ($scope.id) {

		$scope.loading = true;
        $timeout(function() {
    		httpHandler.send({
    			method: 'GET',
    			url: urls + 'lokasi_toko/getById',
    			params: {'id': $scope.id}
    		}).then(
    			function successCallbacks(response) {
    				if(response.data.status == 200){
    					$scope.loading = false;
    					$scope.nama_toko = response.data.data.nama_toko;
    					$scope.tautan = response.data.data.tautan;
    				}else{
    					Swal.close();
    					Swal.fire({
    						title: 'Failed',
    						text: response.data.message,
    						icon: response.data.status == 500 ? 'error' : 'warning',
    						showCancelButton: false,
    						allowEscapeKey: false,
    						allowOutsideClick: false,
    						confirmButtonColor: "#fc544b",
    						confirmButtonText: "Oke",
    					}).then((result) => {
    						if (result.value) {
    							window.location.replace(urls + 'lokasi_toko');
    						}
    					});
    				}
    				
    			}
    		);
        }, 1000);
	}

	$scope.save = function () {
		var nama_toko = $('#nama_toko').val();
		var tautan = $('#tautan').val();
		var tipe = $('#tipe').val();

		if (nama_toko == "") {
			$('#nama_toko').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Nama Toko!',
			});
		} else if (tautan == "") {
			$('#tautan').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Link Tautan!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}
		formData.append("nama_toko", nama_toko);
		formData.append("tautan", tautan);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'lokasi_toko/saveData',
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
					confirmButtonText: "Oke",
				}).then((result) => {
					if (result.value) {
						if ($scope.id) {
							window.location.replace(urls + 'lokasi_toko');
						} else {
							location.reload();
						}
					}
				});
			},
			function errorCallback(response) {
				Swal.close();
				Swal.fire({
					title: 'Failed',
					text: response.data.message,
					icon: response.data.status == 500 ? 'error' : 'warning',
					showCancelButton: false,
					allowEscapeKey: false,
					allowOutsideClick: false,
					confirmButtonColor: "#fc544b",
					confirmButtonText: "Oke",
				}).then((result) => {
					if (result.value) {
						// location.reload();
					}
				});
			});
	}

	$scope.redirect = function () {
		window.location.replace(urls + 'lokasi_toko');
	}
}]);