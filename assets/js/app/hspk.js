mainApp.controller('hspk', ['$scope', 'httpHandler', '$filter', '$attrs', function ($scope, httpHandler, $filter, $attrs) {
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
			url: urls + 'hspk/getData',
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
		$scope.getData(0);
    }

    $scope.reset = function(is_master)
    {
    	$scope.keyword = {};
    	$scope.search_Method = {};
    	if(is_master == "master"){
    		$scope.getData(0);
    	}
    }

    $scope.show_modal = function($data){
        $('#modal_detail').modal('show');
        $scope.view($data);

    }

    $scope.view = function($id){
    httpHandler.send({
            method: 'GET',
            url: urls + 'hspk/getById',
            params: {'id': $id}
        }).then(
            function successCallbacks(response) {
                
                if(response.data.status == 200){
                    $scope.loading = false;
                    $scope.kegiatan = response.data.data.kegiatan;
                    $scope.spesifikasi = response.data.data.spesifikasi;
                    $scope.total = response.data.data.total;
                    $scope.tableKelompok = $scope.spesifikasi;

                    $scope.viewKegiatan = $scope.kegiatan

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
                        }
                    });
                }
            }
        );
    }

}]);
