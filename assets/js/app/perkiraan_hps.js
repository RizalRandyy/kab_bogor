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

            $scope.removeTempRow = function (kategori, index) {
                if (kategori === 'tenaga') {
                    $scope.tempRowsTenagaKerja.splice(index, 1);
                }
                if (kategori === 'bahan') {
                    $scope.tempRowsBahan.splice(index, 1);
                }
                if (kategori === 'peralatan') {
                    $scope.tempRowsPeralatan.splice(index, 1);
                }
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
                    console.log(res);

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
                    console.log(res);


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

            $scope.exportExcelHPS = async function () {
                const wb = new ExcelJS.Workbook();
                const ws = wb.addWorksheet('HPS');

                let rowNum = 1;

                const borderThin = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };

                const setArial11 = (row, bold = false, italic = false) => {
                    row.eachCell(cell => {
                        cell.font = { name: 'Arial', size: 11, bold, italic };
                        cell.alignment = { ...cell.alignment, vertical: 'middle' };
                    });
                };

                const borderRow = row => row.eachCell(c => c.border = borderThin);

                ws.columns = [
                    { width: 8 },
                    { width: 45 },
                    { width: 14 },
                    { width: 8 },
                    { width: 12 },
                    { width: 18 },
                    { width: 20 }
                ];

                ws.getColumn(1).alignment = { horizontal: 'center' };
                ws.getColumn(3).alignment = { horizontal: 'center' };
                ws.getColumn(4).alignment = { horizontal: 'center' };
                ws.getColumn(5).alignment = { horizontal: 'center' };
                ws.getColumn(6).alignment = { horizontal: 'right' };
                ws.getColumn(7).alignment = { horizontal: 'right' };


                ws.mergeCells('A1:G1');
                ws.getCell('A1').value = 'SIMULASI HARGA PERKIRAAN SENDIRI (HPS)';
                setArial11(ws.getRow(1), true);
                ws.getRow(1).alignment = { horizontal: 'center' };

                const kegiatan = $scope.options_kegiatan.find(x => x.id == $scope.idKegiatan);
                const kegiatanText = kegiatan
                    ? `${kegiatan.kodeKelompok} - ${kegiatan.UraianKegiatan} (${kegiatan.satuan}) - ${kegiatan.tahunPekerjaan}`
                    : '';

                ws.mergeCells('A2:G2');
                ws.getCell('A2').value = kegiatanText;
                setArial11(ws.getRow(2), false, true);
                ws.getRow(2).alignment = { horizontal: 'center' };

                rowNum = 2;

                ws.addRow([]);
                ws.addRow(['No', 'Uraian', 'Kode', 'Sat.', 'Koefisien', 'Harga Satuan (Rp)', 'Jumlah Harga (Rp)']);
                rowNum = ws.lastRow.number;

                let headerRow = ws.getRow(rowNum);
                setArial11(headerRow, true);
                borderRow(headerRow);

                const renderGroup = (label, title, data) => {
                    ws.addRow([label, title]);
                    rowNum++;

                    ws.mergeCells(`B${rowNum}:G${rowNum}`);
                    let gr = ws.getRow(rowNum);
                    setArial11(gr, true);
                    gr.getCell(1).alignment = { horizontal: 'center' };
                    borderRow(gr);

                    let no = 1;
                    let subtotal = 0;

                    data.forEach(row => {
                        const d = $scope.options_kel_spesifikasi.find(x => x.id_kelompok == row.id);
                        if (!d) return;

                        const k = Number($scope.inputTotal.val[row.id]) || 0;
                        const h = Number($scope.inputHarga.val[row.id]) || 0;
                        const j = k * h;
                        subtotal += j;

                        ws.addRow([
                            no++,
                            d.UraianKelompok,
                            d.kodeKelItem || '',
                            d.satuan || '',
                            k,
                            h,
                            j
                        ]);

                        rowNum++;
                        let r = ws.getRow(rowNum);
                        r.getCell(5).numFmt = '0.000';
                        r.getCell(6).numFmt = '#,##0.00';
                        r.getCell(7).numFmt = '#,##0.00';
                        setArial11(r);
                        borderRow(r);
                    });

                    ws.addRow(['', `JUMLAH HARGA ${title}`, '', '', '', '', subtotal]);
                    rowNum++;

                    ws.mergeCells(`B${rowNum}:F${rowNum}`);
                    let sr = ws.getRow(rowNum);
                    setArial11(sr, true);
                    sr.getCell(2).alignment = { horizontal: 'center' };
                    sr.getCell(7).numFmt = '#,##0.00';
                    borderRow(sr);

                    return subtotal;
                };

                const totalA = renderGroup('A', 'TENAGA KERJA', $scope.tableTenagaKerja);
                const totalB = renderGroup('B', 'BAHAN', $scope.tableBahan);
                const totalC = renderGroup('C', 'PERALATAN', $scope.tablePeralatan);

                const D = totalA + totalB + totalC;

                ws.addRow(['D', 'Jumlah (A+B+C)', '', '', '', '', D]);
                rowNum++;
                ws.mergeCells(`B${rowNum}:F${rowNum}`);
                let dr = ws.getRow(rowNum);
                setArial11(dr, true);
                dr.getCell(7).numFmt = '#,##0.00';
                borderRow(dr);

                const pct = Number($scope.percent || 0);
                const E = D * pct / 100;

                ws.addRow(['E', `Biaya Umum dan Keuntungan ${pct}% x D`, '', '', `${pct}%`, '', E]);
                rowNum++;
                ws.mergeCells(`B${rowNum}:F${rowNum}`);
                let er = ws.getRow(rowNum);
                setArial11(er);
                er.getCell(5).alignment = { horizontal: 'center' };
                er.getCell(7).numFmt = '#,##0.00';
                borderRow(er);

                ws.addRow(['F', 'Harga Satuan Pekerjaan (D+E)', '', '', '', '', D + E]);
                rowNum++;
                ws.mergeCells(`B${rowNum}:F${rowNum}`);
                let fr = ws.getRow(rowNum);
                setArial11(fr, true);
                fr.getCell(7).numFmt = '#,##0.00';
                borderRow(fr);

                ws.pageSetup.printArea = `A1:G${rowNum}`;

                const buf = await wb.xlsx.writeBuffer();
                const blob = new Blob([buf], {
                    type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                });

                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'Harga Perkiraan Sendiri (HPS).xlsx';
                a.click();
                URL.revokeObjectURL(a.href);
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

