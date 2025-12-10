mainApp
    .directive("customOnChange", function () {
        return {
            restrict: "A",
            link: function (scope, element, attrs) {
                var func = scope.$eval(attrs.customOnChange);
                element.bind("change", func);
            },
        };
    })

    .controller("perkiraan_hps", [
        "$scope",
        "httpHandler",
        "$filter",
        "$attrs",
        "$timeout",
        function ($scope, httpHandler, $filter, $attrs, $timeout) {

            $scope.id = $("#id").val() || null;

            $scope.tableTenagaKerja = [];
            $scope.tableBahan = [];
            $scope.tablePeralatan = [];

            $scope.tempRowsTenagaKerja = [];
            $scope.tempRowsBahan = [];
            $scope.tempRowsPeralatan = [];

            $scope.total = [];
            $scope.inputTotal = { val: {} };
            $scope.inputHarga = { val: {} };
            $scope.hargaAsli = { val: {} };
            $scope.jumlah = 0;
            $scope.total_percent = 0;
            $scope.total_all = 0;
            $scope.percent = 0;

            $scope.filterTenaga = function () {
                return $scope.options_kel_spesifikasi.filter(x =>
                    x.tipe?.toLowerCase() === "sbu" &&
                    (x.kriteria?.toLowerCase() === "upah" || !x.kriteria)
                );
            };

            $scope.filterBahan = function () {
                return $scope.options_kel_spesifikasi.filter(x =>
                    x.tipe?.toLowerCase() === "ssh" &&
                    x.kriteria?.toLowerCase() === "bahan"
                );
            };

            $scope.filterPeralatan = function () {
                return $scope.options_kel_spesifikasi.filter(x =>
                    x.tipe?.toLowerCase() === "ssh" &&
                    x.kriteria?.toLowerCase() === "peralatan"
                );
            };

            $scope.addRowTenagaKerja = function () {
                $scope.tempRowsTenagaKerja.push({});
                $scope.initSelect2();
            };

            $scope.addRowBahan = function () {
                $scope.tempRowsBahan.push({});
                $scope.initSelect2();
            };

            $scope.addRowPeralatan = function () {
                $scope.tempRowsPeralatan.push({});
                $scope.initSelect2();
            };

            $scope.addItemFromSelectTenagaKerja = function (id, index) {
                let item = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == id);
                $scope.hargaAsli.val[id] = item?.value_harga ?? 0;
                // $scope.inputHarga.val[id] = item?.value_harga ?? 0;
                $scope.inputHarga.val[id] = "";


                if (!$scope.tableTenagaKerja.some(x => x.id == id)) {
                    $scope.tableTenagaKerja.push({ id: id, isDefault: false });
                }
                $scope.tempRowsTenagaKerja.splice(index, 1);
            };

            $scope.addItemFromSelectBahan = function (id, index) {
                let item = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == id);
                $scope.hargaAsli.val[id] = item?.value_harga ?? 0;
                // $scope.inputHarga.val[id] = item?.value_harga ?? 0;
                $scope.inputHarga.val[id] = "";
                if (!$scope.tableBahan.some(x => x.id == id)) {
                    $scope.tableBahan.push({ id: id, isDefault: false });
                }
                $scope.tempRowsBahan.splice(index, 1);
            };

            $scope.addItemFromSelectPeralatan = function (id, index) {
                let item = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == id);
                $scope.hargaAsli.val[id] = item?.value_harga ?? 0;
                // $scope.inputHarga.val[id] = item?.value_harga ?? 0;
                $scope.inputHarga.val[id] = "";
                if (!$scope.tablePeralatan.some(x => x.id == id)) {
                    $scope.tablePeralatan.push({ id: id, isDefault: false });
                }
                $scope.tempRowsPeralatan.splice(index, 1);
            };


            $scope.removeItemKategori = function (kategori, id) {
                if (kategori === "tenaga") {
                    $scope.tableTenagaKerja = $scope.tableTenagaKerja.filter(function (x) {
                        return x.id !== id;
                    });
                }
                if (kategori === "bahan") {
                    $scope.tableBahan = $scope.tableBahan.filter(function (x) {
                        return x.id !== id;
                    });
                }
                if (kategori === "peralatan") {
                    $scope.tablePeralatan = $scope.tablePeralatan.filter(function (x) {
                        return x.id !== id;
                    });
                }

                $scope.jumlahHarga();
            };

            $scope.initSelect2 = function () {
                $timeout(function () {

                    $(".row-select-tenaga").select2().off("select2:select")
                        .on("select2:select", function (e) {
                            var id = e.params.data.id;
                            var index = $(this).data("row");
                            $scope.$apply(function () {
                                $scope.addItemFromSelectTenagaKerja(id, index);
                            });
                        });

                    $(".row-select-bahan").select2().off("select2:select")
                        .on("select2:select", function (e) {
                            var id = e.params.data.id;
                            var index = $(this).data("row");
                            $scope.$apply(function () {
                                $scope.addItemFromSelectBahan(id, index);
                            });
                        });

                    $(".row-select-peralatan").select2().off("select2:select")
                        .on("select2:select", function (e) {
                            var id = e.params.data.id;
                            var index = $(this).data("row");
                            $scope.$apply(function () {
                                $scope.addItemFromSelectPeralatan(id, index);
                            });
                        });

                }, 50);
            };

            $scope.getKelompokById = function (idKelompok) {
                let data = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == idKelompok);
                if (!data) return "";

                console.log(data);

                return (
                    data.kodeKelItem + " - " + data.UraianKelompok
                    // + (data.UraianSpesifikasi ? (" - " + data.UraianSpesifikasi) : "") + " - (" + data.satuan + ") - " + data.tipe
                );
            };

            $scope.getData = function () {
                httpHandler.send({
                    method: "GET",
                    url: urls + "perkiraan_hps/kegiatan",
                }).then(function (res) {
                    $scope.options_kegiatan = res.data.kegiatan;
                    $scope.options_kel_spesifikasi = res.data.kel_spesifikasi;

                    console.log('kel_spesifikasi: ', $scope.options_kel_spesifikasi);
                    console.log('TENAGA: ', $scope.filterTenaga());
                    console.log('BAHAN: ', $scope.filterBahan());
                    console.log('PERALATAN: ', $scope.filterPeralatan());

                    if ($scope.id) $scope.loadSavedData();
                });
            };

            $scope.getData();

            $scope.loadSavedData = function () {
                httpHandler.send({
                    method: "GET",
                    url: urls + "perkiraan_hps/getById",
                    params: { id: $scope.id },
                }).then(function (res) {

                    var detail = res.data.data;

                    $scope.idKegiatan = detail.id_thn_kegiatan;
                    $scope.inputTotal.val = detail.total_item;

                    $scope.tableTenagaKerja = [];
                    $scope.tableBahan = [];
                    $scope.tablePeralatan = [];

                    detail.id_thn_harga.forEach(function (id) {
                        var item = $scope.options_kel_spesifikasi.find(function (o) { return o.id == id; });
                        if (!item) return;

                        if (item.tipe === "SBU" && item.kriteria === "upah")
                            $scope.tableTenagaKerja.push({ id: id, isDefault: true });

                        if (item.tipe === "SSH" && item.kriteria === "bahan")
                            $scope.tableBahan.push({ id: id, isDefault: true });

                        if (item.tipe === "SSH" && item.kriteria === "peralatan")
                            $scope.tablePeralatan.push({ id: id, isDefault: true });
                    });

                    $scope.jumlahHarga();
                });
            };

            $scope.getHarga = function (id) {
                let item = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == id);
                return item?.value_harga ?? 0;
            };

            $scope.getTotal = function (id) {
                let qty = parseFloat($scope.inputTotal.val[id]);
                let harga = parseFloat($scope.inputHarga.val[id]);

                if (isNaN(qty) || isNaN(harga)) {
                    $scope.total[id] = 0;
                    return "";
                }

                let total = qty * harga;
                $scope.total[id] = total;

                $scope.jumlahHarga();
                return total;
            };

            $scope.jumlahHarga = function () {

                var semua = []
                    .concat($scope.tableTenagaKerja)
                    .concat($scope.tableBahan)
                    .concat($scope.tablePeralatan);

                var totalAkhir = 0;
                semua.forEach(function (row) {
                    if ($scope.total[row.id])
                        totalAkhir += $scope.total[row.id];
                });

                $scope.jumlah = totalAkhir;
                $scope.totalHarga($scope.percent);
            };

            $scope.totalHarga = function (percent) {
                var p = parseFloat(percent || 0);
                $scope.total_percent = ($scope.jumlah * p) / 100;
                $scope.total_all = $scope.jumlah + $scope.total_percent;
            };

            $scope.save = function () {
                var semua = []
                    .concat($scope.tableTenagaKerja)
                    .concat($scope.tableBahan)
                    .concat($scope.tablePeralatan)
                    .map(function (x) { return x.id; });

                if (semua.length === 0) {
                    Swal.fire("Peringatan", "Pilih minimal satu item!", "warning");
                    return;
                }

                if (!$scope.idKegiatan || $scope.idKegiatan === "") {
                    Swal.fire("Peringatan", "Pilih Tahun Pekerjaan terlebih dahulu!", "warning");
                    return;
                }

                let harga_satuan = semua.map(id => $scope.inputHarga.val[id] || 0).join(",");
                let total_item = semua.map(id => $scope.inputTotal.val[id] || 0).join(",");

                var url =
                    urls + "perkiraan_hps/saveData?" +
                    "kegiatan=" + $scope.idKegiatan +
                    "&id_thn_harga=" + semua.join(",") +
                    "&harga_satuan=" + harga_satuan +
                    "&total_item=" + total_item +
                    "&percent=" + ($scope.percent || 0);

                window.location.href = urls + "ExportExcel/download?kegiatan=..." ;

            };

        }
    ]);

$(document).ready(function () {
    $("#idKegiatan").select2({
        placeholder: "Pilih Tahun Kegiatan"
    });

    $("#idKegiatan").on("change", function () {
        var scope = angular.element($("#idKegiatan")).scope();
        scope.$apply(function () {
            scope.idKegiatan = $("#idKegiatan").val();
        });
    });
});

