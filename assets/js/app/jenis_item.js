mainApp.controller('jenis_item', ['$scope', 'httpHandler', '$filter', '$attrs', function ($scope, httpHandler, $filter, $attrs) {
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
	$scope.search_Method ={};
	$scope.total_count = 0;
	$scope.message = null;

	$scope.getData = function (pageno) {
		if(pageno == 0)
            $scope.no = 1;
        else
            $scope.no = (pageno*$scope.itemsPerPage) - ($scope.itemsPerPage - 1);

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
			url: urls + 'jenis_item/getData',
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

	$scope.searchMethod = function(keyname, val)
	{
		$scope.keyword[keyname] = val;
		$scope.getData(1);
    }

    $scope.reset = function(is_master)
    {
    	$scope.keyword = {};
    	$scope.search_Method = {};
    	if(is_master == "master"){
    		$scope.getData(1);
    	}
    }

	$scope.tambah = function () {
		window.location.replace(urls + 'jenis_item/form/tambah');
	}

	$scope.edit = function (params) {
		window.location.replace(urls + 'jenis_item/form/edit?id='+params.id);
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
					url: urls + 'jenis_item/deleteData',
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
