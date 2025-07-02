mainApp.controller('user_role', ['$scope', 'httpHandler', '$filter', '$attrs', 'Upload', '$http', function ($scope, httpHandler, $filter, $attrs, Upload, $http) {

	$scope.no = 1;
	$scope.itemsPerPage = 10;
	$scope.totalData = 0;
	$scope.keyword = {};

    $scope.get_role = function (pageno) {
		$scope.loading = true;

		if (pageno == 0)
			$scope.no = 1;
		else
			$scope.no = (pageno * $scope.itemsPerPage) - ($scope.itemsPerPage - 1);


		httpHandler.send({
			method: 'GET',
			url: urls + 'user_role/get_role',
			params: {
				page: pageno != 0 ? pageno : 1,
				limit: $scope.itemsPerPage,
				keyword: $scope.keyword,
				column: true,
			}
		}).then(
			function successCallbacks(response) {
				$scope.loading = false;
				$scope.currentPage = pageno;
				$scope.itemData = response.data.data;
				$scope.totalData = response.data.total;
				$scope.currentPage = pageno;
			}
		);
	}

	$scope.get_role(0);

	$scope.tambah = function () {
		window.location.replace(urls + 'user_role/add_role');
	}

    $scope.delete = function(id)
    {
        Swal.fire({
            title: 'Anda yakin ingin menghapus data ini?',
            text: "Anda tidak akan dapat mengembalikan ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Kembali'
        }).then((result)  => {
          if (result.value) 
          {
            $http({
                method: 'POST',
                url: urls + 'user_role/delete',
                data : ({
                    'id'    :id,
                })
            }).then(function successCallbacks(response) {
                if(response.data.status == 'success'){
                    $scope.get_role(1);
                    Swal.fire(
                        'Success',
                        'Data succesfully deleted',
                        'success'
                    );                                      
                }else{
                    Swal.fire(
                      'Error!',
                      'Something when wrong, plese try again later',
                      'error'
                    );          
                }
            });             
          }else{
            Swal.close();
          }
        })
    }

}]);