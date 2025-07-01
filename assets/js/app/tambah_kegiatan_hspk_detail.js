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
            $timeout(function () {
                httpHandler.send({
                    method: 'GET',
                    url: urls + 'kegiatan_hspk_detail/getById',
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
                                    window.location.replace(urls + 'kegiatan_hspk_detail');
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
                    $scope.tableKelompok.push(selectedId);
                }
            });

            $('#item').val(null).trigger('change');
        });

        $('#idKegiatan').on("change", function () {
            $scope.$apply(function () {
                var id = $('#idKegiatan').val();
                var result = $scope.options_kegiatan.find(function (item) {
                    return item.id === id;
                });
                $scope.viewKegiatan = result.kodeKelompok + ' - ' + result.UraianKegiatan + ' - (' + result.satuan + ') - ' + result.tahunPekerjaan;
            });
        });

        $scope.removeItem = function (id) {
            $scope.tableKelompok = $scope.tableKelompok.filter(function (itemId) {
                return itemId !== id;
            });

            if ($scope.inputTotal && $scope.inputTotal.val) {
                delete $scope.inputTotal.val[id];
            }

            if ($scope.total) {
                delete $scope.total[id];
            }

            $scope.totalHarga();
        };


        $scope.getKelompokById = function (id) {
            setInputFilter(document.getElementById('banyak_' + id), function (value) {
                return /^-?\d*[.]?\d{0,3}$/.test(value);
            });
            for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
                if ($scope.options_kel_spesifikasi[i].id === id) {
                    data = $scope.options_kel_spesifikasi[i];

                    return data.kodeKelompok + ' - ' + data.UraianKelompok + ' - ' + data.NamaJenis + ' - ' + data.UraianSpesifikasi + ' - ' + data.satuan + ' - (' + data.tipe + ') - ' + data.TahunHarga;
                }
            }
            return "";
        };
        $scope.getHarga = function (id) {

            for (var i = 0; i < $scope.options_kel_spesifikasi.length; i++) {
                if ($scope.options_kel_spesifikasi[i].id === id) {
                    data = $scope.options_kel_spesifikasi[i];
                    return data.harga;
                }
            }
            return "";
        };
        $scope.getTotal = function (id, val = '1') {
            var angka = 0;

            const inputField = document.getElementById("banyak_" + id);

            inputField.addEventListener("input", function () {
                const value = inputField.value;

                if (value.length === 1 && (value[0] === ".")) {
                    inputField.value = "0.";
                } else if (value.length === 2 && (value[0] === "0" && value[1] != ".")) {
                    inputField.value = value[1];
                } else if (value.length > 5 && (value[1].slice(-1) === ".")) {
                    inputField.value = value.slice(0, -1);
                }
            });


            if (inputField.value) {
                angka = inputField.value;
                if (angka.length > 5) {
                    angka = inputField.value.slice(0, -1);
                }
            }

            for (var k = 0; k < $scope.options_kel_spesifikasi.length; k++) {
                if ($scope.options_kel_spesifikasi[k].id === id) {
                    data = $scope.options_kel_spesifikasi[k];
                    total = angka > 0 ? (angka * data.value_harga) : data.value_harga;
                    $scope.total[id] = parseInt(total);
                    $scope.totalHarga();
                    return total;
                    break;
                }
            }

            return "";
        };

        $scope.totalHarga = function () {
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
            const input_banyak = [];
            const idKegiatan = $('#idKegiatan').val();
            const tahun = $scope.tableKelompok;

            if (!idKegiatan) {
                $('#idKegiatan').focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Pilih ID Kegiatan!',
                });
            }

            if ($scope.tableKelompok.length === 0) {
                $('#item').focus();
                return Toast.fire({
                    icon: "warning",
                    title: 'Pilih setidaknya satu item!',
                });
            }

            for (let i = 0; i < $scope.tableKelompok.length; i++) {
                const id = $scope.tableKelompok[i];
                const jumlah = $scope.inputTotal.val?.[id];

                if (jumlah == null || jumlah === "") {
                    document.getElementById(`banyak_${id}`).focus();
                    return Toast.fire({
                        icon: "warning",
                        title: `Masukkan jumlah untuk item ke-${i + 1}!`,
                    });
                } else if (jumlah === "0" || parseFloat(jumlah) === 0) {
                    document.getElementById(`banyak_${id}`).focus();
                    return Toast.fire({
                        icon: "warning",
                        title: `Jumlah item ke-${i + 1} tidak boleh 0!`,
                    });
                }

                input_banyak.push(jumlah);
            }

            const formData = new FormData();

            if ($scope.id) {
                formData.append("id", $scope.id);
            }

            formData.append("id_thn_kegiatan", idKegiatan);
            console.log("id_thn_harga:", $scope.tableKelompok);
            console.log("total_item:", input_banyak);

            formData.append("id_thn_harga", $scope.tableKelompok);
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
                        confirmButtonColor: "#fc544b",
                        confirmButtonText: "Oke",
                    });
                });
        };


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

    let selectedItems = [];

    $('#item').on('select2:select', function (e) {
        const selectedId = e.params.data.id;
        const label = e.params.data.text;

        if (!selectedItems.includes(selectedId)) {
            selectedItems.push(selectedId);
        }

        $('#item').val(null).trigger('change');
    });

    $('#table-body').on('click', '.remove-item', function () {
        const row = $(this).closest('tr');
        const id = row.data('id');

        selectedItems = selectedItems.filter(i => i !== id);
        row.remove();
    });
});

