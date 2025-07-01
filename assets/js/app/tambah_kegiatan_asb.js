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

.controller('tambah_kegiatan_asb', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
		httpHandler.send({
			method: 'GET',
			url: urls + 'kegiatan_asb/getById',
			params: {'id': $scope.id}
		}).then(
			function successCallbacks(response) {
				if(response.data.status == 200){
					$scope.loading = false;
					$scope.idASB = response.data.data.idASB;
					$scope.urKeg = response.data.data.UraianKegiatan;
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
							window.location.replace(urls + 'kegiatan_asb');
						}
					});
				}
				
			}
		);
	}

	$scope.save = function () {
		var idASB = $('#idASB').val();
		var urKeg = $('#urKeg').val();
		var satuan = $('#satuan').val();

		if (idASB == null) {
			$('#idASB').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Kode Kegiatan!',
			});
		} else if (idASB.length > 6) {
			$('#idASB').focus(); 
			return Toast.fire({
				icon: "warning",
				title: 'Kode Kegiatan Maksimal hanya 6 Karakter!',
			});
		} else if (urKeg == "") {
			$('#urKeg').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Uraian Kegiatan!',
			});
		} else if (satuan == "") {
			$('#satuan').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Satuan!',
			});
		} else if (satuan.length > 8) {
			$('#idASB').focus(); 
			return Toast.fire({
				icon: "warning",
				title: 'Satuan Kegiatan Maksimal hanya 6 Karakter!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}

		formData.append("idASB", idASB);
		formData.append("UraianKegiatan", urKeg);
		formData.append("satuan", satuan);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'kegiatan_asb/saveData',
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
							window.location.replace(urls + 'kegiatan_asb');
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
		window.location.replace(urls + 'kegiatan_asb');
	}
}]);

$(document).ready(function () {
  $('#idSpesifikasi').select2({
  	placeholder: "Pilih Kelompok Item"
  });
});