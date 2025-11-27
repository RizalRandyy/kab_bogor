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

	.controller('tambah_daftar_dokumen', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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

		httpHandler.send({
			method: 'GET',
			url: urls + 'daftar_dokumen/getJenisDokumen'
		}).then(function success(response) {
			if (response.data.status == 200) {
				$scope.listJenisDokumen = response.data.data.map(function (item) {
					item.id = parseInt(item.id);
					return item;
				});
			}
		});

		$scope.id = !$("#id").val() ? null : $("#id").val();

		if ($scope.id) {

			$scope.loading = true;
			$timeout(function () {
				httpHandler.send({
					method: 'GET',
					url: urls + 'daftar_dokumen/getById',
					params: { 'id': $scope.id }
				}).then(
					function successCallbacks(response) {
						if (response.data.status == 200) {
							$scope.loading = false;
							$scope.nama_dokumen = response.data.data.nama_dokumen;
							$scope.nomor_dokumen = response.data.data.nomor_dokumen;
							$scope.id_jenis_dokumen = parseInt(response.data.data.id_jenis_dokumen);
							$scope.tahun = response.data.data.tahun;
							$scope.deskripsi = response.data.data.deskripsi;
							$scope.dokumen = response.data.data.dokumen;
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
									window.location.replace(urls + 'opd');
								}
							});
						}

					}
				);
			}, 1000);
		}

		$scope.save = function () {
			var namaDokumen = $('#nama_dokumen').val();
			var nomorDokumen = $('#nomor_dokumen').val();
			var idJenisDokumen = $scope.id_jenis_dokumen;
			var deskripsi = $('#deskripsi').val();
			var tahun = $('#tahun').val();
			var dokumen = $('#dokumen')[0].files[0];

			if (namaDokumen == "") {
				$('#nama_dokumen').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Nama Dokumen!',
				});
			} else if (idJenisDokumen == "") {
				$('#id_jenis_dokumen').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Jenis Dokumen!',
				});
			} else if (tahun == "") {
				$('#tahun').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Tahun!',
				});
			} else if (tahun.length > 6) {
				$('#tahun').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Tahun Maksimal hanya 4 Karakter!',
				});
			} else if (deskripsi == "") {
				$('#deskripsi').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Deskripsi!',
				});
			} else if (dokumen == "") {
				$('#dokumen').focus();
				return Toast.fire({
					icon: "warning",
					title: 'Masukan Dokumen yang akan di Unggah!',
				});
			}

			var formData = new FormData();

			if ($scope.id) {
				formData.append("id", $scope.id);
			}
			formData.append("nama_dokumen", namaDokumen);
			formData.append("nomor_dokumen", nomorDokumen);
			formData.append("id_jenis_dokumen", idJenisDokumen);
			formData.append("tahun", tahun);
			formData.append("deskripsi", deskripsi);
			var file = document.getElementById("dokumen").files[0];
			if (file) {
				formData.append("dokumen", file);
			}
			if ($scope.dokumen) {
				formData.append("dokumen_lama", $scope.dokumen);
			}

			Swal.fire({
				title: 'Loading...',
				allowEscapeKey: false,
				allowOutsideClick: false,
				showConfirmButton: false,
				imageUrl: urls + "assets/img/loadertsel.gif",
			});

			httpHandler.send({
				url: urls + 'daftar_dokumen/saveData',
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
								window.location.replace(urls + 'daftar_dokumen');
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
			window.location.replace(urls + 'daftar_dokumen');
		}
	}]);