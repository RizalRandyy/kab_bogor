mainApp

	.directive('customOnChange', function () {
		return {
			restrict: 'A',
			link: function (scope, element, attrs) {
				var onChangeFunc = scope.$eval(attrs.customOnChange);
				element.bind('change', onChangeFunc);
			}
		};
	})

	.controller('tambah_usulan_kegiatan_asb', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
				url: urls + 'usulan_kegiatan_asb/Opd'
			}).then(
				function successCallbacks(response) {
					$scope.options = response.data.data;
				}
			);

			httpHandler.send({
				method: 'GET',
				url: urls + 'usulan_kegiatan_asb/Opd_pengusul'
			}).then(
				function successCallbacks(response) {
					$scope.optionsOpdPengusul = response.data.data;
				}
			);
		}

		$scope.id = !$("#id").val() ? null : $("#id").val();

		if ($scope.id) {

			$scope.loading = true;
			httpHandler.send({
				method: 'GET',
				url: urls + 'usulan_kegiatan_asb/getById',
				params: { 'id': $scope.id }
			}).then(
				function successCallbacks(response) {
					if (response.data.status == 200) {
						$scope.loading = false;
						$scope.id = response.data.data.id;
						$scope.idASB = response.data.data.idASB;
						$scope.idOpdPengusul = response.data.data.idOpdPengusul;
						$scope.idOpd = response.data.data.idOpd;
						$scope.namaOpd = response.data.data.namaOpd;
						$scope.urKeg = response.data.data.UraianKegiatan;
						$scope.satuan = response.data.data.satuan;
						$scope.tahun = response.data.data.tahunASB;
					} else {
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
								window.location.replace(urls + 'usulan_kegiatan_asb');
							}
						});
					}
					$scope.getData();
				}
			);
		} else {
			$scope.getData();
		}

		$scope.save = function () {
			var idASB = $('#idASB').val();
			var idOpdPengusul = $('#idOpdPengusul').val();
			var idOpd = $('#idOpd').val();
			var urKeg = $('#urKeg').val();
			var satuan = $('#satuan').val();
			var tahun = $('#tahun').val();

			if (idASB == null) {
				$('#idASB').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Kode Kegiatan!',
				});
			} else if (idOpd == null) {
				$('#idOpd').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Pilih Bidang!',
				});
			} else if (idOpdPengusul == null) {
				$('#idOpdPengusul').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Pilih OPD / Dinas Pengusul!',
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

			formData.append("idASB", idASB);
			formData.append("idOpdPengusul", idOpdPengusul);
			formData.append("idOpd", idOpd);
			formData.append("UraianKegiatan", urKeg);
			formData.append("satuan", satuan);
			formData.append("tahunASB", tahun);

			Swal.fire({
				title: 'Loading...',
				allowEscapeKey: false,
				allowOutsideClick: false,
				showConfirmButton: false,
				imageUrl: urls + "assets/img/loadertsel.gif",
			});

			httpHandler.send({
				url: urls + 'usulan_kegiatan_asb/saveData',
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
								window.location.replace(urls + 'usulan_kegiatan_asb');
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
			window.location.replace(urls + 'usulan_kegiatan_asb');
		}
	}]);

$(document).ready(function () {
	$('#idSpesifikasi').select2({
		placeholder: "Pilih Kelompok Item"
	});

	$('#idOpd').select2({
		placeholder: "Pilih Bidang"
	});

	$('#idOpdPengusul').select2({
		placeholder: "Pilih OPD / Dinas Pengusul"
	});
});