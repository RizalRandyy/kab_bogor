mainApp

    .directive('customOnChange', function () {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                var onChangeFunc = scope.$eval(attrs.customOnChange);
                element.bind('change', onChangeFunc);
            }
        };
    })

    .controller('rab', ['$scope', 'httpHandler', '$filter', '$attrs', '$timeout', function ($scope, httpHandler, $filter, $attrs, $timeout) {
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
        $scope.inputTotal = {
            val: {}
        };
        $scope.inputHarga = {
            val: {}
        };
        $scope.keyword = '';
        $scope.viewKegiatan = '';
        $scope.tempRows = [];

        $scope.addNewRow = function () {
            $scope.tempRows.push({});

            $timeout(() => {
                $('.row-select').select2({
                    placeholder: "Pilih Kode Kelompok Item",
                    width: '100%'
                });

                $('.row-select').off('select2:select').on('select2:select', function (e) {
                    let selectedId = e.params.data.id;
                    let rowIndex = $(this).data('row');

                    let scope = angular.element(document.getElementById('rab')).scope();

                    scope.$apply(function () {
                        scope.addItemFromSelect(selectedId, rowIndex);
                    });
                });

            }, 10);
        };

        $scope.addItemFromSelect = function (selectedId, index) {

            if (!$scope.tableKelompok.includes(selectedId)) {
                $scope.tableKelompok.push({
                    id: selectedId,
                    isDefault: false
                });

                $scope.inputTotal.val[selectedId] = 1;
                $scope.total[selectedId] = $scope.getHarga(selectedId);
                $scope.jumlahHarga();
                $scope.totalHarga($scope.percent);
            }

            $scope.tempRows.splice(index, 1);
        };

        $scope.removeItem = function (id) {
            $scope.tableKelompok = $scope.tableKelompok.filter(row => row.id !== id);
        };

        $scope.hasAdditionalItems = function () {
            return $scope.tableKelompok.some(item => !item.isDefault);
        };


        // setInputFilter(document.getElementById('percent'), function (value) {
        //     return /^-?\d*[.]?\d{0,2}$/.test(value);
        // });

        $scope.getData = function (pageno = 1) {
            httpHandler.send({
                method: 'GET',
                url: urls + 'rab/kegiatan'
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
            $timeout(function () {
                httpHandler.send({
                    method: 'GET',
                    url: urls + 'rab/getById',
                    params: { 'id': $scope.id }
                }).then(
                    function successCallbacks(response) {

                        if (response.data.status == 200) {
                            $scope.loading = false;
                            $scope.idKegiatan = response.data.data.id_thn_kegiatan;
                            $scope.idKelItem = response.data.data.id_thn_harga;
                            $scope.total_item = response.data.data.total_item;
                            $scope.tableKelompok = $scope.idKelItem;
                            $scope.inputTotal['val'] = $scope.total_item;
                            var result = $scope.options_kegiatan.find(function (item) {
                                return item.id === $scope.idKegiatan;
                            });
                            $scope.viewKegiatan = result.kodeKelompok + ' - ' + result.UraianKegiatan + ' - (' + result.satuan + ') - ' + result.tahunPekerjaan;

                        } else {
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
                                    window.location.replace(urls + 'rab');
                                }
                            });
                        }
                        $scope.getData();
                    }
                );
            }, 1000);
        }

        $('#item').on('select2:select', function (e) {
            const selectedId = e.params.data.id;
            const label = e.params.data.text;

            $scope.$apply(function () {
                if (!$scope.tableKelompok.includes(selectedId)) {
                    $scope.tableKelompok.push({
                        id: selectedId,
                        isDefault: false
                    });

                }
            });

            $('#item').val(null).trigger('change');
        });

        $('#idKegiatan').on("change", function () {
            const kegiatanId = $(this).val();
            $scope.$apply(function () {
                $scope.idKegiatan = $('#idKegiatan').val();
                $scope.loadDetailByKegiatan(kegiatanId);
            });
        });

        $scope.loadDetailByKegiatan = function (kegiatanId) {
            httpHandler.send({
                method: 'GET',
                url: urls + 'rab/getDetailByKegiatan',
                params: { id: kegiatanId }
            }).then(function (response) {

                $scope.tableKelompok = response.data.detail_ids.map(id => ({
                    id: id,
                    isDefault: true
                }));

                $scope.inputTotal.val = response.data.total_item;
                $scope.viewKegiatan = response.data.kegiatan_text;
            });
        }


        $scope.getKelompokById = function (id) {
            setInputFilter(document.getElementById('banyak_' + id), function (value) {
                return /^-?\d*[.]?\d{0,3}$/.test(value);
            });
            setInputFilter(document.getElementById('harga_' + id), function (value) {
                return /^-?\d*$/.test(value);
            });
            for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
                if ($scope.options_kel_spesifikasi[i].id === id) {
                    data = $scope.options_kel_spesifikasi[i];

                    return data.kodeKelompok + ' - ' + data.UraianKelompok + ' - ' + data.NamaJenis + ' - ' + data.UraianSpesifikasi + ' - ' + data.satuan + ' - (' + data.tipe + ') - ' + data.TahunHarga;
                }
            }
            return "";
        };

        // $scope.getSatuan = function (id) {
        //     for (let i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
        //         if ($scope.options_kel_spesifikasi[i].id == id) {
        //             return $scope.options_kel_spesifikasi[i].satuan;
        //         }
        //     }
        //     return "";
        // };

        $scope.getHarga = function (id) {

            for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
                if ($scope.options_kel_spesifikasi[i].id === id) {
                    data = $scope.options_kel_spesifikasi[i];
                    return data.value_harga;
                }
            }
            return "";
        };

        $scope.getTotal = function (id, val = '1') {
            var banyak = 0;
            var harga = 0;
            var percent = 0;

            const inputBanyak = document.getElementById("banyak_" + id);

            inputBanyak.addEventListener("input", function () {
                const value_banyak = inputBanyak.value;

                if (value_banyak.length === 1 && (value_banyak[0] === ".")) {
                    inputBanyak.value = "0.";
                } else if (value_banyak.length === 2 && (value_banyak[0] === "0" && value_banyak[1] != ".")) {
                    inputBanyak.value = value_banyak[1];
                } else if (value_banyak.length > 5 && (value_banyak[1].slice(-1) === ".")) {
                    inputBanyak.value = value_banyak.slice(0, -1);
                }
            });

            if (inputBanyak.value) {
                banyak = inputBanyak.value;
                if (banyak.length > 5) {
                    banyak = inputBanyak.value.slice(0, -1);
                }
            }

            const inputHarga = document.getElementById("harga_" + id);

            inputHarga.addEventListener("input", function () {
                const value_harga = inputHarga.value;

                if (value_harga.length === 1 && (value_harga[0] === ".")) {
                    inputHarga.value = "0.";
                } else if (value_harga.length === 2 && (value_harga[0] === "0" && value_harga[1] != ".")) {
                    inputHarga.value = value[1];
                } else if (value_harga.length > 5 && (value_harga[1].slice(-1) === ".")) {
                    inputHarga.value = value_harga.slice(0, -1);
                }
            });

            if (inputHarga.value) {
                if (inputHarga.value > 0) {
                    harga = inputHarga.value;
                } else {
                    harga = inputHarga.value.slice(0, -1);
                }
            }

            const inputPercent = document.getElementById("percent");

            inputPercent.addEventListener("input", function () {
                const value_percent = inputPercent.value;

                if (value_percent.length === 1 && (value_percent[0] === ".")) {
                    inputPercent.value = "0.";
                } else if (value_percent.length === 2 && (value_percent[0] === "0" && value_percent[1] != ".")) {
                    inputPercent.value = value_percent[1];

                } else if (value_percent.length > 5 && (value_percent[1].slice(-1) === ".")) {
                    inputPercent.value = value_percent.slice(0, -1);
                }
            });

            if (inputPercent.value) {
                percent = inputPercent.value;
                if (percent.length > 5) {
                    percent = inputPercent.value.slice(0, -1);
                }
            }

            for (var k = 0; k < $scope.options_kel_spesifikasi.length; k++) {
                if ($scope.options_kel_spesifikasi[k].id === id) {
                    data = $scope.options_kel_spesifikasi[k];
                    total = banyak > 0 ? (banyak * harga) : harga;
                    $scope.total[id] = parseInt(total);
                    $scope.jumlahHarga();
                    $scope.totalHarga(percent);
                    return total;
                    break;
                }
            }

            return "";
        };

        $scope.jumlahHarga = function () {
            $scope.gettotal = [];

            for (var i = 0; i < $scope.tableKelompok.length; i++) {
                let row = $scope.tableKelompok[i];
                let id = row.id;

                if ($scope.total[id]) {
                    $scope.gettotal.push($scope.total[id]);
                }
            }

            $scope.jumlah = $scope.gettotal.reduce((a, b) => a + b, 0);
            $scope.totalHarga($scope.percent);

            return $scope.jumlah;
        }

        $scope.totalHarga = function (percent) {

            var total = ($scope.jumlah / 100) * percent;

            $scope.total_percent = total;
            $scope.total_all = $scope.jumlah + total;

            return $scope.total_all;
        }

        $scope.save = function () {

            let idKegiatan = $scope.idKegiatan;
            console.log(idKegiatan);

            let id_thn_harga = $scope.tableKelompok.map(row => row.id);

            let hargaInputs = document.getElementsByName('harga[]');
            let banyakInputs = document.getElementsByName('banyak[]');
            let percent = $scope.percent ?? 0;

            if (!idKegiatan) {
                $('#idKegiatan').focus();
                return Toast.fire({ icon: "warning", title: 'Pilih ID Kegiatan!' });
            }

            if (id_thn_harga.length === 0) {
                return Toast.fire({ icon: "warning", title: 'Pilih minimal 1 item!' });
            }

            let harga_satuan = [];
            let total_item = [];

            for (let i = 0; i < hargaInputs.length; i++) {
                if (!hargaInputs[i].value || hargaInputs[i].value === "0") {
                    hargaInputs[i].focus();
                    return Toast.fire({ icon: "warning", title: 'Masukan Harga Item!' });
                }
                harga_satuan.push(hargaInputs[i].value);
            }

            for (let i = 0; i < banyakInputs.length; i++) {
                if (!banyakInputs[i].value || banyakInputs[i].value === "0") {
                    banyakInputs[i].focus();
                    return Toast.fire({ icon: "warning", title: 'Masukan Banyak Item!' });
                }
                total_item.push(banyakInputs[i].value);
            }

            let url = urls + 'rab/saveData?' +
                'kegiatan=' + idKegiatan +
                '&id_thn_harga=' + id_thn_harga.join(',') +
                '&harga_satuan=' + harga_satuan.join(',') +
                '&total_item=' + total_item.join(',') +
                '&percent=' + percent;

            window.location.replace(url);
        };

        $scope.redirect = function () {
            window.location.replace(urls + 'rab');
        }
    }]);

$(document).ready(function () {
    $('#idKegiatan').select2({
        placeholder: "Pilih Kegiatan HSPK"
    });
    $('#item').select2({
        placeholder: "Pilih Kode Kelompok Item"
    });
});