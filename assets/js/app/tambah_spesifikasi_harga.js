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

.controller('tambah_spesifikasi_harga', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
			url: urls + 'spesifikasi_harga/kel_spesifikasi'
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
			url: urls + 'spesifikasi_harga/getById',
			params: {'id': $scope.id}
		}).then(
			function successCallbacks(response) {
				if(response.data.status == 200){
					$scope.loading = false;
					$scope.idSpesifikasi = response.data.data.idSpesifikasi;
					$scope.kodeKelompok = response.data.data.kodeKelompok;
					$scope.tahun = response.data.data.TahunHarga;
					$scope.harga = response.data.data.harga;
					$scope.satuan = response.data.data.satuan;
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
							window.location.replace(urls + 'spesifikasi_harga');
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
		var idSpesifikasi = $('#idSpesifikasi').val();
		var tahun = $('#tahun').val();
		var harga = $('#harga').val();

		if (idSpesifikasi == null) {
			$('#idSpesifikasi').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Pilih Kelompok Item!',
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
		} else if (harga == "") {
			$('#harga').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Harga!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}

		formData.append("idSpesifikasi", idSpesifikasi);
		formData.append("TahunHarga", tahun);
		formData.append("harga", harga);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'spesifikasi_harga/saveData',
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
							window.location.replace(urls + 'spesifikasi_harga');
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
		window.location.replace(urls + 'spesifikasi_harga');
	}
}]);

$(document).ready(function () {
  $('#idSpesifikasi').select2({
  	placeholder: "Pilih Kelompok Item"
  });
});