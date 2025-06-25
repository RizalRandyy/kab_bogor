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

.controller('tambah_kegiatan_hspk_detail', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
    $scope.total = [];
    $scope.total_item = [];
    $scope.idKelItem = [];
    $scope.total_all = 0;
    $scope.inputTotal = {};
    $scope.keyword = '';
    $scope.viewKegiatan = '';

	$scope.getData = function (pageno = 1) {
		httpHandler.send({
			method: 'GET',
			url: urls + 'kegiatan_hspk_detail/kegiatan'
		}).then(
			function successCallbacks(response) {
				$scope.options_kegiatan = response.data.kegiatan;
				$scope.options_kel_spesifikasi = response.data.kel_spesifikasi;
			}
		);
	}
    $scope.getData();

    if ($scope.id) {
		$scope.loading = true;
        $timeout(function() {
    		httpHandler.send({
    			method: 'GET',
    			url: urls + 'kegiatan_hspk_detail/getById',
    			params: {'id': $scope.id}
    		}).then(
    			function successCallbacks(response) {
                    
    				if(response.data.status == 200){
    					$scope.loading = false;
    					$scope.idKegiatan = response.data.data.id_thn_kegiatan;
    					$scope.idKelItem = response.data.data.id_thn_harga;
                        $scope.total_item = response.data.data.total_item;
                        $scope.tableKelompok = $scope.idKelItem;
                        $scope.inputTotal['val'] = $scope.total_item;
                        var result = $scope.options_kegiatan.find(function(item) {
                            return item.id === $scope.idKegiatan;
                        });
                        $scope.viewKegiatan = result.kodeKelompok+' - '+result.UraianKegiatan+' - ('+result.satuan+') - '+result.tahunPekerjaan;

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
    							window.location.replace(urls + 'kegiatan_hspk_detail');
    						}
    					});
    				}
                    $scope.getData();
    			}
    		);
        }, 1000);
	}

    $('#item').on("change", function() {
        $scope.$apply(function() {
            $scope.tableKelompok = $('#item').val();
            $scope.total_all = 0;
        });
    });
    $('#idKegiatan').on("change", function() {
        $scope.$apply(function() {
            var id = $('#idKegiatan').val();
            var result = $scope.options_kegiatan.find(function(item) {
                return item.id === id;
            });
            $scope.viewKegiatan = result.kodeKelompok+' - '+result.UraianKegiatan+' - ('+result.satuan+') - '+result.tahunPekerjaan;
        });
    });

    $scope.getKelompokById = function(id) {
        setInputFilter(document.getElementById('banyak_'+id), function (value) {
                return /^-?\d*[.]?\d{0,3}$/.test(value);
            });
        for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
            if ($scope.options_kel_spesifikasi[i].id === id) {
                data = $scope.options_kel_spesifikasi[i];

                return data.kodeKelompok+' - '+data.UraianKelompok+' - '+data.NamaJenis+' - '+data.UraianSpesifikasi+' - '+data.satuan+' - ('+data.tipe+') - '+data.TahunHarga;
            }
        }
        return "";
    };
    $scope.getHarga = function(id) {

        for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
            if ($scope.options_kel_spesifikasi[i].id === id) {
                data = $scope.options_kel_spesifikasi[i];
                return data.harga;
            }
        }
        return "";
    };
    $scope.getTotal = function(id, val = '1') {
        var angka = 0;

        const inputField = document.getElementById("banyak_"+id);

        inputField.addEventListener("input", function () {
            const value = inputField.value;

            if (value.length === 1 && (value[0] === ".")) {
                inputField.value = "0.";
            } else if (value.length === 2 && (value[0] === "0" && value[1] != ".")) {
                inputField.value = value[1];
            } else if (value.length > 5 && (value[1].slice(-1) === ".")){
                inputField.value = value.slice(0,-1);
            }
        });
            

        if(inputField.value){
            angka = inputField.value;
            if(angka.length > 5){
                angka = inputField.value.slice(0,-1);
            }
        }

        for (var k = 0; k < $scope.options_kel_spesifikasi.length; k++) {
            if ($scope.options_kel_spesifikasi[k].id === id) {
                data = $scope.options_kel_spesifikasi[k];
                total = angka > 0 ?(angka*data.value_harga):data.value_harga;
                $scope.total[id] = parseInt(total);
                $scope.totalHarga();
                return total;
                break;
            }
        }
        
        return "";
    };

    $scope.totalHarga = function(){
        $scope.gettotal = []
        for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
            for (var j = 0; j < $scope.tableKelompok.length; j++) {
                if ($scope.options_kel_spesifikasi[i].id === $scope.tableKelompok[j]) {
                    $scope.gettotal[j] = $scope.total[$scope.tableKelompok[j]];
                }
            }
        }

        $scope.total_all = $scope.gettotal.reduce(function (accumulator, currentValue) {
            return accumulator + currentValue;
        }, 0);

        return total;
    }

	$scope.save = function () {
        input_banyak = [];
		var idKegiatan = $('#idKegiatan').val();
		var tahun = $('#item').val();
        var banyak = document.getElementsByName('banyak[]');

		if (idKegiatan == null) {
			$('#idKegiatan').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Pilih ID Kegiatan!',
			});
		} else if (tahun == "") {
			$('#tahun').focus();
			return Toast.fire({
				icon: "warning",
				title: 'Masukan Tahun!',
			});
		}

        for (var i=0; i<banyak.length; i++){
            var a = banyak[i];
            if (a.value == "") {
                banyak[i].focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Masukan Banyak Item!',
                });
            }else if (a.value == "0") {
                banyak[i].focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Tidak bisa hanya angka 0!',
                });
            }
            input_banyak.push(a.value);
        }

		var formData = new FormData();

		if ($scope.id) {
			formData.append("id", $scope.id);
		}
		formData.append("id_thn_kegiatan", idKegiatan);
		formData.append("id_thn_harga", tahun);
        formData.append("total_item", input_banyak);

		Swal.fire({
			title: 'Loading...',
			allowEscapeKey: false,
			allowOutsideClick: false,
			showConfirmButton: false,
			imageUrl: urls + "assets/img/loadertsel.gif",
		});

		httpHandler.send({
			url: urls + 'kegiatan_hspk_detail/saveData',
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
							window.location.replace(urls + 'kegiatan_hspk_detail');
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
		window.location.replace(urls + 'kegiatan_hspk_detail');
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