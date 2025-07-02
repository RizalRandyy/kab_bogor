mainApp.controller('lokasi_toko', ['$scope', 'httpHandler', '$filter', '$attrs', function ($scope, httpHandler, $filter, $attrs) {
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

	$scope.loadAllMarkers = function () {
		httpHandler.send({
			method: 'GET',
			url: urls + 'lokasi_toko/getAll'
		}).then(function (response) {
			if (response.data.status === 200) {
				renderMarkers(response.data.data);
			}
		});
	};


	$scope.map = null;

	function initMap() {
		if ($scope.map != null) {
			$scope.map.remove();
		}
		$scope.map = L.map('map');

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; OpenStreetMap contributors'
		}).addTo($scope.map);
	}

	function renderMarkers(data) {
		const bounds = [];

		if ($scope.markerGroup) {
			$scope.map.removeLayer($scope.markerGroup);
		}
		$scope.markerGroup = L.layerGroup().addTo($scope.map);

		data.forEach(function (item) {
			if (item.koordinat && item.koordinat.includes(',')) {
				const parts = item.koordinat.split(',');
				const lat = parseFloat(parts[0]);
				const lng = parseFloat(parts[1]);

				if (!isNaN(lat) && !isNaN(lng)) {
					const latlng = [lat, lng];
					const smallIcon = L.icon({
						iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png', // atau custom marker kamu
						iconSize: [15, 20],
						iconAnchor: [10, 30],
						popupAnchor: [0, -30]
					});

					L.marker(latlng, { icon: smallIcon }).addTo($scope.markerGroup).bindPopup(item.nama_toko);

					bounds.push(latlng);
				}
			}
		});

		if (bounds.length > 0) {
			$scope.map.fitBounds(bounds, {
				padding: [10, 10],
				maxZoom: 17
			});
		}
	}

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
			keyword: $scope.keyword || {},
			limit: $scope.itemsPerPage || 10,
			offset: pageno != 0 ? pageno : 1
		};

		$scope.loading = true;

		httpHandler.send({
			method: 'GET',
			url: urls + 'lokasi_toko/getData',
			params: params
		}).then(
			function successCallbacks(response) {
				$scope.loading = false;
				$scope.data = response.data.data;
				$scope.table_header = response.data.header;
				$scope.total_count = response.data.count;
				$scope.message = response.data.message;
				$scope.curPage = pageno;

				initMap();
				$scope.loadAllMarkers();

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
		window.location.replace(urls + 'lokasi_toko/form/tambah');
	}

	$scope.edit = function (params) {
		window.location.replace(urls + 'lokasi_toko/form/edit?id=' + params.id);
	}

	$scope.delete = function (params) {
		Swal.fire({
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
					url: urls + 'lokasi_toko/deleteData',
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

}]);
