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

.controller('tambah_opd', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
        $timeout(function() {
    		httpHandler.send({
    			method: 'GET',
    			url: urls + 'opd/getById',
    			params: {'id': $scope.id}
    		}).then(
    			function successCallbacks(response) {
    				if(response.data.status == 200){
    					$scope.loading = false;
    					$scope.idOpd = response.data.data.idOpd;
    					$scope.namaOpd = response.data.data.namaOpd;
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
    							window.location.replace(urls + 'opd');
    						}
    					});
    				}
    				
    			}
    		);
        }, 1000);
	}

	$scope.save = function () {
		var idOpd = $('#idOpd').val();
		var namaOpd = $('#namaOpd').val();

		if (idOpd == "") {
			$('#idOpd').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Kode OPD!',
			});
		} else if (idOpd.length > 6) {
			$('#idOpd').focus(); 
			return Toast.fire({
				icon: "warning",
				title: 'Kode OPD Maksimal hanya 6 Karakter!',
			});
		} else if (namaOpd == "") {
			$('#namaOpd').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Nama OPD!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}
		formData.append("idOpd", idOpd);
		formData.append("namaOpd", namaOpd);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'opd/saveData',
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
							window.location.replace(urls + 'opd');
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
		window.location.replace(urls + 'opd');
	}
}]);