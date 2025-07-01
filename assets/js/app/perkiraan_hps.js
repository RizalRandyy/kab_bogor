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

.controller('perkiraan_hps', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
    $scope.jumlah = 0;
    $scope.percent = 0;
    $scope.total_percent = 0;
    $scope.total_all = 0;
    $scope.inputTotal = {};
    $scope.inputHarga = {};
    $scope.keyword = '';
    $scope.viewKegiatan = '';
    setInputFilter(document.getElementById('percent'), function (value) {
        return /^-?\d*[.]?\d{0,2}$/.test(value);
    });

	$scope.getData = function (pageno = 1) {
		httpHandler.send({
			method: 'GET',
			url: urls + 'perkiraan_hps/kegiatan'
		}).then(
			function successCallbacks(response) {
				$scope.options_kegiatan = response.data.kegiatan;
				$scope.options_kel_spesifikasi = response.data.kel_spesifikasi;
			}
		);
	}
    $scope.getData();
    $scope.id = !$("#id").val() ? null : $("#id").val();

    if ($scope.id) {
		$scope.loading = true;
        $timeout(function() {
    		httpHandler.send({
    			method: 'GET',
    			url: urls + 'perkiraan_hps/getById',
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
    							window.location.replace(urls + 'perkiraan_hps');
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
            $scope.jumlah = 0;
            $scope.percent = 0;
            $scope.total_percent = 0;
        });
    });
    $('#idKegiatan').on("change", function() {
        $scope.$apply(function() {
            var id = $('#idKegiatan').val();
            $scope.viewKegiatan = id;
        });
    });

    $scope.getKelompokById = function(id) {
        setInputFilter(document.getElementById('banyak_'+id), function (value) {
                return /^-?\d*[.]?\d{0,3}$/.test(value);
            });
        setInputFilter(document.getElementById('harga_'+id), function (value) {
                return /^-?\d*$/.test(value);
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
                return data.value_harga;
            }
        }
        return "";
    };

    $scope.getTotal = function(id, val = '1') {
        var banyak = 0;
        var harga = 0;
        var percent = 0;

        const inputBanyak = document.getElementById("banyak_"+id);

        inputBanyak.addEventListener("input", function () {
            const value_banyak = inputBanyak.value;

            if (value_banyak.length === 1 && (value_banyak[0] === ".")) {
                inputBanyak.value = "0.";
            } else if (value_banyak.length === 2 && (value_banyak[0] === "0" && value_banyak[1] != ".")) {
                inputBanyak.value = value_banyak[1];
            } else if (value_banyak.length > 5 && (value_banyak[1].slice(-1) === ".")){
                inputBanyak.value = value_banyak.slice(0,-1);
            }
        });

        if(inputBanyak.value){
            banyak = inputBanyak.value;
            if(banyak.length > 5){
                banyak = inputBanyak.value.slice(0,-1);
            }
        }

        const inputHarga = document.getElementById("harga_"+id);

        inputHarga.addEventListener("input", function () {
            const value_harga = inputHarga.value;

            if (value_harga.length === 1 && (value_harga[0] === ".")) {
                inputHarga.value = "0.";
            } else if (value_harga.length === 2 && (value_harga[0] === "0" && value_harga[1] != ".")) {
                inputHarga.value = value[1];
            } else if (value_harga.length > 5 && (value_harga[1].slice(-1) === ".")){
                inputHarga.value = value_harga.slice(0,-1);
            }
        });
        
        if(inputHarga.value){
            if(inputHarga.value > 0){
                harga = inputHarga.value;
            }else{
                harga = inputHarga.value.slice(0,-1);
            }
        }

        const inputPercent = document.getElementById("percent");

        inputPercent.addEventListener("input", function () {
            const value_percent = inputPercent.value;

            if (value_percent.length === 1 && (value_percent[0] === ".")) {
                inputPercent.value = "0.";
            } else if (value_percent.length === 2 && (value_percent[0] === "0" && value_percent[1] != ".")) {
                inputPercent.value = value_percent[1];

            } else if (value_percent.length > 5 && (value_percent[1].slice(-1) === ".")){
                inputPercent.value = value_percent.slice(0,-1);
            }
        });
        
        if(inputPercent.value){
            percent = inputPercent.value;
            if(percent.length > 5){
                percent = inputPercent.value.slice(0,-1);
            }
        }

        for (var k = 0; k < $scope.options_kel_spesifikasi.length; k++) {
            if ($scope.options_kel_spesifikasi[k].id === id) {
                data = $scope.options_kel_spesifikasi[k];
                total = banyak > 0 ?(banyak*harga):harga;
                $scope.total[id] = parseInt(total);
                $scope.jumlahHarga();
                $scope.totalHarga(percent);
                return total;
                break;
            }
        }
        
        return "";
    };

    $scope.jumlahHarga = function(){
        $scope.gettotal = []
        for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
            for (var j = 0; j < $scope.tableKelompok.length; j++) {
                if ($scope.options_kel_spesifikasi[i].id === $scope.tableKelompok[j]) {
                    $scope.gettotal[j] = $scope.total[$scope.tableKelompok[j]];
                }
            }
        }

        $scope.jumlah = $scope.gettotal.reduce(function (accumulator, currentValue) {
            $scope.totalHarga();
            return accumulator + currentValue;
        }, 0);

        return $scope.jumlah;
    }

    $scope.totalHarga = function(percent){

        var total = ($scope.jumlah/100) * percent;

        $scope.total_percent = total;
        $scope.total_all = $scope.jumlah + total;

        return $scope.total_all;
    }

	$scope.save = function () {
        input_harga = [];
        input_banyak = [];
		var idKegiatan = $('#idKegiatan').val();
		var tahun = $('#item').val();
        var harga = document.getElementsByName('harga[]');
        var banyak = document.getElementsByName('banyak[]');
        var percent = $('#percent').val();

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

        for (var i=0; i<harga.length; i++){
            var a = harga[i];
            if (a.value == "") {
                harga[i].focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Masukan Harga Item!',
                });
            }else if (a.value == "0") {
                harga[i].focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Tidak bisa hanya angka 0!',
                });
            }
            input_harga.push(a.value);
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
		formData.append("kegiatan", idKegiatan);
		formData.append("id_thn_harga", tahun);
        formData.append("harga_satuan", input_harga);
        formData.append("total_item", input_banyak);
        formData.append("percent", input_banyak);

        window.location.replace(urls + 'perkiraan_hps/saveData?' + 'kegiatan=' + idKegiatan + '&id_thn_harga='+ tahun + '&harga_satuan=' + input_harga + '&total_item=' + input_banyak + '&percent=' + percent);
	}

	$scope.redirect = function () {
		window.location.replace(urls + 'perkiraan_hps');
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