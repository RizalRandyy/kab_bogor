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

	.controller('tambah_usulan_spesifikasi_item', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
				url: urls + 'usulan_spesifikasi_item/kel_item'
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
			httpHandler.send({
				method: 'GET',
				url: urls + 'usulan_spesifikasi_item/getById',
				params: { 'id': $scope.id }
			}).then(
				function successCallbacks(response) {
					if (response.data.status == 200) {
						$scope.loading = false;
						$scope.kodeItem = response.data.data.idJenisItem;
						$scope.idSpesifikasi = response.data.data.idSpesifikasi;
						$scope.UraianSpesifikasi = response.data.data.UraianSpesifikasi;
						$scope.satuan = response.data.data.satuan;
						$scope.TahunHarga = response.data.data.TahunHarga;
						$scope.harga = response.data.data.harga;
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
								window.location.replace(urls + 'usulan_spesifikasi_item');
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
			var kodeItem = $('#kodeItem').val();
			var idSpesifikasi = $('#idSpesifikasi').val();
			var UraianSpesifikasi = $('#UraianSpesifikasi').val();
			var satuan = $('#satuan').val();
			var TahunHarga = $('#TahunHarga').val();
			var harga = $('#harga').val();
			var idOpd = $('#idOpd').val();

			if (kodeItem == null) {
				$('#kodeItem').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Pilih Kelompok Item!',
				});
			} else if (idSpesifikasi == "") {
				$('#idSpesifikasi').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Kode Jenis Item!',
				});
			} else if (idSpesifikasi.length > 6) {
				$('#idSpesifikasi').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Tipe Item Maksimal hanya 6 Karakter!',
				});
			} else if (UraianSpesifikasi == "") {
				$('#UraianSpesifikasi').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Nama Jenis Item!',
				});
			}
			else if (satuan == "") {
				$('#satuan').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Satuan!',
				});
			} else if (idOpd == null) {
				$('#idOpd').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Pilih OPD / Dinas Pengusul!',
				});
			}else if (TahunHarga == "") {
				$('#TahunHarga').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Tahun!',
				});
			} else if (TahunHarga.length != 4) {
				$('#TahunHarga').focus();
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

			formData.append("idJenisItem", kodeItem);
			formData.append("idSpesifikasi", idSpesifikasi);
			formData.append("UraianSpesifikasi", UraianSpesifikasi);
			formData.append("satuan", satuan);
			formData.append("TahunHarga", TahunHarga);
			formData.append("harga", harga);
			formData.append("idOpd", idOpd);

			Swal.fire({
				title: 'Loading...',
				allowEscapeKey: false,
				allowOutsideClick: false,
				showConfirmButton: false,
				imageUrl: urls + "assets/img/loadertsel.gif",
			});

			httpHandler.send({
				url: urls + 'usulan_spesifikasi_item/saveData',
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
								window.location.replace(urls + 'usulan_spesifikasi_item');
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
			window.location.replace(urls + 'usulan_spesifikasi_item');
		}
	}]);

$(document).ready(function () {
	$('#kodeItem').select2({
		placeholder: "Pilih Kelompok Item"
	});

	$('#idOpd').select2({
		placeholder: "Pilih OPD / Dinas Pengusul"
	});
});