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

.controller('tambah_kelompok_item', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
    			url: urls + 'kelompok_item/getById',
    			params: {'id': $scope.id}
    		}).then(
    			function successCallbacks(response) {
    				if(response.data.status == 200){
    					$scope.loading = false;
    					$scope.idKelItem = response.data.data.idKelItem;
    					$scope.urKel = response.data.data.UraianKelompok;
    					$scope.tipe = response.data.data.tipe;
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
    							window.location.replace(urls + 'kelompok_item');
    						}
    					});
    				}
    				
    			}
    		);
        }, 1000);
	}

	$scope.save = function () {
		var idKelItem = $('#idKelItem').val();
		var urKel = $('#urKel').val();
		var tipe = $('#tipe').val();

		if (idKelItem == "") {
			$('#idKelItem').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Kode Item!',
			});
		} else if (idKelItem.length > 6) {
			$('#idKelItem').focus(); 
			return Toast.fire({
				icon: "warning",
				title: 'Kode Item Maksimal hanya 6 Karakter!',
			});
		} else if (urKel == "") {
			$('#urKel').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Uraian Kelompok Item!',
			});
		} else if (tipe == null || tipe == "") {
			$('#tipe').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Tipe Item!',
			});
		}

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}
		formData.append("idKelItem", idKelItem);
		formData.append("UraianKelompok", urKel);
		formData.append("tipe", tipe);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'kelompok_item/saveData',
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
							window.location.replace(urls + 'kelompok_item');
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
		window.location.replace(urls + 'kelompok_item');
	}
}]);