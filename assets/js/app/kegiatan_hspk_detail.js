mainApp.controller('kegiatan_hspk_detail', ['$scope', 'httpHandler', '$filter', '$attrs', function ($scope, httpHandler, $filter, $attrs) {
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

	$scope.no = 1;
	$scope.itemsPerPage = 10;
	$scope.keyword = {};
	$scope.search_Method = {};
	$scope.total_count = 0;
	$scope.message = null;

	$scope.getData = function (pageno) {
		if (pageno == 0)
			$scope.no = 1;
		else
			$scope.no = (pageno * $scope.itemsPerPage) - ($scope.itemsPerPage - 1);

		$scope.total_count = 0;
		$scope.message = null;

		var params = {
			keyword: $scope.keyword,
			limit: $scope.itemsPerPage,
			offset: pageno != 0 ? pageno : 1,
		}

		$scope.loading = true;

		httpHandler.send({
			method: 'GET',
			url: urls + 'kegiatan_hspk_detail/getData',
			params: params
		}).then(
			function successCallbacks(response) {
				$scope.loading = false;
				$scope.data = response.data.data;
				$scope.table_header = response.data.header;
				$scope.total_count = response.data.count;
				$scope.message = response.data.message;
				$scope.curPage = pageno;
			}
		);
	}

	$scope.getData(0);

	$scope.searchMethod = function (keyname, val) {
		$scope.keyword[keyname] = val;
		$scope.getData(1);
	}

	$scope.reset = function (is_master) {
		$scope.keyword = {};
		$scope.search_Method = {};
		if (is_master == "master") {
			$scope.getData(1);
		}
	}

	$scope.tambah = function () {
		window.location.replace(urls + 'kegiatan_hspk_detail/form/tambah');
	}

	$scope.edit = function (params) {
		window.location.replace(urls + 'kegiatan_hspk_detail/form/edit?id=' + params.id);
	}

	$scope.delete = function (params) {
		Swal.fire({
			// title: "Hey!",
			title: "Anda yakin ingin menghapus data ini?",
			text: "Menghapus data ini akan menghapus juga data yang berkaitan dengan data ini.",
			icon: "info",
			showCancelButton: true,
			allowEscapeKey: false,
			allowOutsideClick: false,
			cancelButtonColor: "#808080",
			confirmButtonColor: "#D11A2A",
			confirmButtonText: "Hapus!",
			cancelButtonText: "Kembali",
		}).then((result) => {
			if (result.value) {
				var formData = new FormData();
				formData.append("id", params.id);

				httpHandler.send({
					url: urls + 'kegiatan_hspk_detail/deleteData',
					data: formData,
					method: 'POST',
					headers: {
						'Content-Type': undefined
					}
				}).then(
					function successCallbacks(response) {
						Swal.close();
						Toast.fire({
							icon: 'success',
							title: response.data.message,
						});
						$scope.getData(1);
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
							confirmButtonText: "Okey, refresh page",
						}).then((result) => {
							if (result.value) {
								location.reload();
							}
						});
					});
			}
		});
	}

	$scope.exportExcelById = async function (encryptedId) {
		try {
			const response = await httpHandler.send({
				method: 'GET',
				url: urls + 'kegiatan_hspk_detail/getById',
				params: { id: encryptedId }
			});

			if (response.data.status !== 200) {
				return Swal.fire({
					icon: "error",
					title: "Gagal",
					text: "Data tidak ditemukan untuk export.",
				});
			}

			const detailData = response.data.data;


			const optionsResponse = await httpHandler.send({
				method: 'GET',
				url: urls + 'kegiatan_hspk_detail/kegiatan'
			});

			const options = optionsResponse.data.kel_spesifikasi;
			const id_harga = detailData.id_thn_harga;
			const total_item = detailData.total_item;
			console.log(total_item);


			const workbook = new ExcelJS.Workbook();
			const worksheet = workbook.addWorksheet('Detail Kegiatan HSPK');

			worksheet.columns = [
				{ header: 'Kode Kelompok', key: 'kodeKelompok', width: 20 },
				{ header: 'Nama Item', key: 'namaItem', width: 25 },
				{ header: 'Spesifikasi', key: 'spesifikasi', width: 30 },
				{ header: 'Tipe', key: 'tipe', width: 12 },
				{ header: 'Tahun Harga', key: 'tahunHarga', width: 15 },
				{ header: 'Satuan', key: 'satuan', width: 10 },
				{ header: 'Harga Satuan', key: 'harga', width: 15 },
				{ header: 'Banyak', key: 'qty', width: 10 },
				{ header: 'Subtotal', key: 'subtotal', width: 15 },
			];

			worksheet.getRow(1).font = { bold: true };

			let total = 0;

			for (let i = 0; i < id_harga.length; i++) {
				const id = id_harga[i];
				const jumlah = total_item[id];

				const data = options.find(item => item.id === id);
				console.log("📦 data:", data);
				if (!data) continue;

				const harga = data.value_harga ?? 0;
				const subtotal = harga * jumlah;
				total += subtotal;

				worksheet.addRow([
					data.kodeKelompok ?? '',
					data.UraianKelompok ?? '',
					data.UraianSpesifikasi ?? '',
					data.tipe ?? '',
					data.TahunHarga ?? '',
					data.satuan ?? '',
					harga,
					jumlah,
					subtotal
				]);
			}


			worksheet.addRow(['', '', '', '', '', '', '', 'TOTAL', total]);

			worksheet.eachRow({ includeEmpty: false }, (row) => {
				row.eachCell({ includeEmpty: false }, (cell) => {
					cell.border = {
						top: { style: 'thin' },
						left: { style: 'thin' },
						bottom: { style: 'thin' },
						right: { style: 'thin' }
					};
				});
			});

			const buffer = await workbook.xlsx.writeBuffer();
			const blob = new Blob([buffer], {
				type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
			});

			const link = document.createElement("a");
			link.href = URL.createObjectURL(blob);
			link.download = "Detail-Kegiatan-HSPK.xlsx";
			link.click();
			URL.revokeObjectURL(link.href);
		} catch (error) {
			console.error("Export error:", error);
			Swal.fire({
				icon: "error",
				title: "Export Gagal",
				text: "Terjadi kesalahan saat mengambil data.",
			});
		}
	};

}]);
