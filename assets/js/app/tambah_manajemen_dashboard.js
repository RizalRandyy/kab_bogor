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

.controller('tambah_manajemen_dashboard', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
    function setInputFilter(textbox, inputFilter) {
        ["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
            if (textbox != null) {
                textbox.addEventListener(event, function () {
                    if (inputFilter(this.value)) {
                        this.oldValue = this.value;
                        this.oldSelectionStart = this.selectionStart;
                        this.oldSelectionEnd = this.selectionEnd;
                    } else if (this.hasOwnProperty("oldValue")) {
                        this.value = this.oldValue;
                        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
                    } else {
                        this.value = "";
                    }
                });
            }
        });
    }

    $scope.id = !$("#id").val() ? null : $("#id").val();
    $scope.tableKelompok = [];
    $scope.id = [];
    $scope.idKelItem = [];
    $scope.disable = false;

	$scope.getKegiatan = function (pageno = 1) {
		httpHandler.send({
			method: 'GET',
			url: urls + 'manajemen_dashboard/kegiatan'
		}).then(
			function successCallbacks(response) {
				$scope.options_kegiatan = response.data.data;
			}
		);
	}
    $scope.getKegiatan();

    $scope.getData = function (pageno = 1) {
		$scope.loading = true;
        $timeout(function() {
    		httpHandler.send({
    			method: 'GET',
    			url: urls + 'manajemen_dashboard/getById'
    		}).then(
    			function successCallbacks(response) {
                    
    				if(response.data.status == 200){
    					$scope.loading = false;
    					$scope.idKelItem = response.data.data.idItem;
                        $scope.tableKelompok = $scope.idKelItem;
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
    							window.location.replace(urls + 'manajemen_dashboard');
    						}
    					});
    				}
                    $scope.getKegiatan();
    			}
    		);
        }, 1000);
	}
    $scope.getData();

    $('#item').on("change", function(e) {
        var kegiatan = $('#item').val();
        $scope.idKelItem = kegiatan;

        $scope.$apply(function() {
            $scope.tableKelompok = $('#item').val();
            $scope.total_all = 0;
        });
    });

    $scope.getKelompokById = function(id) {
        for (var i = 0; i < $scope.options_kegiatan.length; i++) {
            if ($scope.options_kegiatan[i].id === id) {
                data = $scope.options_kegiatan[i];

                return data.kodeKelompok+' - '+data.UraianKelompok+' - '+data.NamaJenis+' - '+data.UraianSpesifikasi+' - '+data.satuan+' - ('+data.tipe+')';
            }
        }
        return "";
    };

	$scope.save = function () {

		var idKegiatan = $scope.idKelItem;

		if (idKegiatan.length == 0 || idKegiatan[0] == "") {
			$('#idKegiatan').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Pilih ID Kegiatan!',
			});
		}

		var formData = new FormData();

		formData.append("idItem", idKegiatan);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'manajemen_dashboard/saveData',
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
							window.location.replace(urls + 'manajemen_dashboard');
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
		window.location.replace(urls + 'manajemen_dashboard');
	}
}]);

$(document).ready(function () {
  $('#idKegiatan').select2({
  	placeholder: "Pilih Tahun Kegiatan"
  });
  $('#item').select2({
  	placeholder: "Pilih Kode Kelompok Item"
  });
});