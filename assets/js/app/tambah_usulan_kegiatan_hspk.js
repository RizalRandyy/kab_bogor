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

	.controller('tambah_usulan_kegiatan_hspk', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
				url: urls + 'kegiatan_hspk/bidang_teknis'
			}).then(
				function successCallbacks(response) {
					$scope.options = response.data.data;
				}
			);

			httpHandler.send({
				method: 'GET',
				url: urls + 'usulan_spesifikasi_item/Opd'
			}).then(
				function successCallbacks(response) {
					$scope.optionsOpd = response.data.data;
				}
			);
		}

		$scope.id = !$("#id").val() ? null : $("#id").val();

		if ($scope.id) {

			$scope.loading = true;
			$timeout(function () {
				httpHandler.send({
					method: 'GET',
					url: urls + 'usulan_kegiatan_hspk/getById',
					params: { 'id': $scope.id }
				}).then(
					function successCallbacks(response) {
						if (response.data.status == 200) {
							$scope.loading = false;
							$scope.idKeg = response.data.data.idKegiatan;
							$scope.urKeg = response.data.data.UraianKegiatan;
							$scope.idBidangTeknis = response.data.data.idBidangTeknis;
							$scope.satuan = response.data.data.satuan;
							$scope.tahun = response.data.data.tahunPekerjaan;
							$scope.idOpd = response.data.data.idOpd;
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
									window.location.replace(urls + 'usulan_kegiatan_hspk');
								}
							});
						}
						$scope.getData();
					}
				);
			}, 1000);
		} else {
			$scope.getData();
		}

		$scope.save = function () {
			var idKeg = $('#idKeg').val();
			var urKeg = $('#urKeg').val();
			var idBidangTeknis = $('#idBidangTeknis').val();
			var satuan = $('#satuan').val();
			var tahun = $('#tahun').val();
			var idOpd = $('#idOpd').val();

			if (idKeg == null) {
				$('#idKeg').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Kode Kegiatan!',
				});
			} else if (idKeg.length > 6) {
				$('#idKeg').focus();
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
			} else if (idBidangTeknis == "") {
				$('#idBidangTeknis').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Bidang Kegiatan!',
				});
			} else if (satuan == "") {
				$('#satuan').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Satuan!',
				});
			} else if (satuan.length > 8) {
				$('#idKeg').focus();
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
			} else if (idOpd == null) {
				$('#idOpd').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Pilih OPD / Dinas Pengusul!',
				});
			}

			var formData = new FormData();

			if ($scope.id) {
				formData.append("id", $scope.id);
			}

			formData.append("idKegiatan", idKeg);
			formData.append("UraianKegiatan", urKeg);
			formData.append("idBidangTeknis", idBidangTeknis);
			formData.append("satuan", satuan);
			formData.append("tahunPekerjaan", tahun);
			formData.append("idOpd", idOpd);

			Swal.fire({
				title: 'Loading...',
				allowEscapeKey: false,
				allowOutsideClick: false,
				showConfirmButton: false,
				imageUrl: urls + "assets/img/loadertsel.gif",
			});

			httpHandler.send({
				url: urls + 'usulan_kegiatan_hspk/saveData',
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
								window.location.replace(urls + 'usulan_kegiatan_hspk');
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
			window.location.replace(urls + 'usulan_kegiatan_hspk');
		}
	}]);

$(document).ready(function () {
	$('#idSpesifikasi').select2({
		placeholder: "Pilih Kelompok Item"
	});

	$('#idOpd').select2({
		placeholder: "Pilih OPD / Dinas Pengusul"
	});
});