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

.controller('tambah_tahun_kegiatan_hspk', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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

	$scope.getData = function (pageno = 1) {
		httpHandler.send({
			method: 'GET',
			url: urls + 'tahun_kegiatan_hspk/kegiatan'
		}).then(
			function successCallbacks(response) {
				$scope.options = response.data.data;
			}
		);
	}

	$scope.id = !$("#id").val() ? null : $("#id").val();

	if ($scope.id) {

		$scope.loading = true;
		httpHandler.send({
			method: 'GET',
			url: urls + 'tahun_kegiatan_hspk/getById',
			params: {'id': $scope.id}
		}).then(
			function successCallbacks(response) {
				if(response.data.status == 200){
					$scope.loading = false;
					$scope.idKegiatan = response.data.data.idKegiatan;
					$scope.tahun = response.data.data.tahunPekerjaan;
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
							window.location.replace(urls + 'tahun_kegiatan_hspk');
						}
					});
				}
				$scope.getData();
			}
		);
	}else{
        $scope.getData();
    }

	$scope.save = function () {
		var idKegiatan = $('#idKegiatan').val();
		var tahun = $('#tahun').val();

		if (idKegiatan == null) {
			$('#idKegiatan').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Pilih ID Kegiatan!',
			});
		} else if (tahun == "") {
			$('#tahun').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Tahun!',
			});
		} else if (tahun.length != 4) {
			$('#tahun').focus(); 
			return Toast.fire({
				icon: "warning",
				title: 'Input Tahun 4 Karakter!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}
		formData.append("idKegiatan", idKegiatan);
		formData.append("tahunPekerjaan", tahun);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'tahun_kegiatan_hspk/saveData',
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
							window.location.replace(urls + 'tahun_kegiatan_hspk');
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
		window.location.replace(urls + 'tahun_kegiatan_hspk');
	}
}]);

$(document).ready(function () {
  $('#idKegiatan').select2({
  	placeholder: "Pilih Kode Kegiatan"
  });
});