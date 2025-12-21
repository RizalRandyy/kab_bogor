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

            $scope.hpsStore = {};
            $scope.createEmptyState = function () {
                return {
                    bahan: {
                        table: [],
                        tempRows: [],
                        inputHarga: { val: {} },
                        inputTotal: { val: {} },
                        inputKoefisien: { val: {} },
                        hargaAsli: { val: {} },
                        total: {}
                    },
                    peralatan: {
                        table: [],
                        tempRows: [],
                        inputHarga: { val: {} },
                        inputTotal: { val: {} },
                        inputKoefisien: { val: {} },
                        hargaAsli: { val: {} },
                        total: {}
                    },
                    tenagaKerja: {
                        table: [],
                        tempRows: [],
                        inputHarga: { val: {} },
                        inputTotal: { val: {} },
                        inputKoefisien: { val: {} },
                        hargaAsli: { val: {} },
                        total: {}
                    },
                    meta: {
                        percent: 0,
                        jumlah: 0,
                        total_percent: 0,
                        total_all: 0,
                        hasMappedApi: false
                    }
                };
            };

            $scope.getData = function () {
                httpHandler.send({
                    method: "GET",
                    url: urls + "perkiraan_hps/asb",
                }).then(function (res) {
                    $scope.options_asb = res.data.asb;
                    $scope.options_kel_spesifikasi = res.data.kel_spesifikasi;

                    if ($scope.id) $scope.loadSavedData();
                });
            };

            $scope.getData();

            $scope.getHspk = function (idAsb) {

                if (!idAsb) return;

                $scope.loading = true;

                httpHandler.send({
                    method: 'GET',
                    url: urls + 'perkiraan_hps/getAsbById',
                    params: { id: idAsb }
                }).then(function (response) {

                    $scope.loading = false;

                    if (response.data.status == 200) {

                        $scope.kegiatan = response.data.data.kegiatan;
                        $scope.spesifikasi = response.data.data.spesifikasi;
                        $scope.hspkTitle = $scope.kegiatan;
                        $scope.data = angular.copy($scope.spesifikasi);

                        $scope.hspkList = angular.copy($scope.spesifikasi);
                        console.log($scope.hspkList);


                    } else {
                        Swal.fire({
                            title: 'Failed',
                            text: response.data.message,
                            icon: response.data.status == 500 ? 'error' : 'warning',
                            confirmButtonColor: "#fc544b",
                            confirmButtonText: "Oke",
                        });
                    }
                });
            };

            $scope.getHargaKegiatan = function (idSpesifikasi) {
                let s = $scope.hpsStore[idSpesifikasi];
                if (!s) return 0;

                return s.meta.jumlah || 0;
            };

            $scope.mapApiToHpsStore = function (idSpesifikasi, list) {

                if (!$scope.hpsStore[idSpesifikasi]) {
                    $scope.hpsStore[idSpesifikasi] = $scope.createEmptyState();
                }

                let s = $scope.hpsStore[idSpesifikasi];

                // JANGAN MAP ULANG JIKA SUDAH PERNAH
                if (s.meta.hasMappedApi) {
                    console.log('API sudah dimapping, skip');
                    return;
                }

                list.forEach(item => {

                    let id = item.id_spesifikasi;
                    let koef = Number(item.total_item) || 0;

                    if (item.tipe === 'SSH' && item.kriteria === 'bahan') {
                        s.bahan.hargaAsli.val[id] = item.value_harga ?? 0;
                        s.bahan.inputKoefisien.val[id] = koef;
                        s.bahan.table.push({ id, isDefault: true });
                    }

                    if (item.tipe === 'SSH' && item.kriteria === 'peralatan') {
                        s.peralatan.hargaAsli.val[id] = item.value_harga ?? 0;
                        s.peralatan.inputKoefisien.val[id] = koef;
                        s.peralatan.table.push({ id, isDefault: true });
                    }

                    if (item.tipe === 'SBU' && item.kriteria === 'upah') {
                        s.tenagaKerja.hargaAsli.val[id] = item.value_harga ?? 0;
                        s.tenagaKerja.inputKoefisien.val[id] = koef;
                        s.tenagaKerja.table.push({ id, isDefault: true });
                    }
                });

                s.meta.hasMappedApi = true;

                $scope.jumlahHarga();
            };


            $scope.view = function ($id) {
                httpHandler.send({
                    method: 'GET',
                    url: urls + 'perkiraan_hps/sshByHspk',
                    params: { 'id': $id }
                }).then(
                    function successCallbacks(response) {

                        if (response.data.status == 200) {
                            // console.log(response.data.kel_spesifikasi);

                            $scope.loading = false;
                            $scope.kegiatan = response.data.kegiatan[0];
                            $scope.hspkTitle = $scope.kegiatan.UraianKegiatan;

                            $scope.mapApiToHpsStore(
                                $id, // ID HSPK
                                response.data.kel_spesifikasi
                            );

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
                                }
                            });
                        }
                    }
                );
            }

            $scope.openHpsModal = function (idSpesifikasi) {
                $scope.activeSpesifikasiId = idSpesifikasi;

                $scope.view(idSpesifikasi);

                if (!$scope.hpsStore[idSpesifikasi]) {
                    $scope.hpsStore[idSpesifikasi] = $scope.createEmptyState();
                }

                let s = $scope.hpsStore[idSpesifikasi];

                $scope.tableBahan = s.bahan.table;
                $scope.tempRowsBahan = s.bahan.tempRows;

                $scope.tablePeralatan = s.peralatan.table;
                $scope.tempRowsPeralatan = s.peralatan.tempRows;

                $scope.tableTenagaKerja = s.tenagaKerja.table;
                $scope.tempRowsTenagaKerja = s.tenagaKerja.tempRows;

                $('#modal_detail').modal('show');
            };


            $scope.openHspk = {};
            $scope.toggleHspkDetail = function (index, id_hspk) {

                // Jika sedang dibuka → tutup saja
                if ($scope.openHspk[index] === true) {
                    $scope.openHspk[index] = false;
                    return;
                }

                // Tutup semua dulu
                $scope.openHspk = {};

                // Set baris ini terbuka
                $scope.openHspk[index] = true;

                // Loading detail HSPK
                $scope.hspkLoading = true;

                httpHandler.send({
                    method: 'GET',
                    url: urls + 'perkiraan_hps/getById',
                    params: { id: id_hspk }
                }).then(response => {

                    $scope.hspkLoading = false;

                    if (response.data.status === 200) {
                        $scope.hspkDetail = response.data.data.spesifikasi;
                        $scope.hspkDetailTotal = response.data.data.total;
                    }
                });
            };

            // FROM HERE
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

            $scope.filterTenagaKerja = function () {
                return $scope.options_kel_spesifikasi.filter(x =>
                    x.tipe?.toLowerCase() === "sbu" &&
                    x.kriteria?.toLowerCase() === "upah"
                );
            };

            $scope.addRowBahan = function () {
                $scope.tempRowsBahan.push({});
                $scope.initSelect2();
            };

            $scope.addRowPeralatan = function () {
                $scope.tempRowsPeralatan.push({});
                $scope.initSelect2();
            };

            $scope.addRowTenagaKerja = function () {
                $scope.tempRowsTenagaKerja.push({});
                $scope.initSelect2();
            };

            $scope.addItemFromSelect = function (kategori, id, index) {
                let g = $scope.getGroup(kategori);
                if (!g) return;

                let item = $scope.options_kel_spesifikasi.find(x => x.id_spesifikasi == id);
                if (!item) return;

                if (!g.table.some(x => x.id == id)) {
                    g.hargaAsli.val[id] = item.value_harga ?? "";
                    g.inputHarga.val[id] = "";
                    g.inputTotal.val[id] ??= "";
                    g.inputKoefisien.val[id] ??= "";

                    g.table.push({ id, isDefault: false });
                }

                g.tempRows.splice(index, 1);
            };

            $scope.getGroup = function (kategori) {
                let s = $scope.hpsStore[$scope.activeSpesifikasiId];
                return s ? s[kategori] : null;
            };

            $scope.removeItemKategori = function (kategori, id) {
                let g = $scope.getGroup(kategori);
                if (!g) return;

                const idx = g.table.findIndex(x => x.id == id);
                if (idx !== -1) {
                    g.table.splice(idx, 1);
                }

                delete g.inputHarga.val[id];
                delete g.inputTotal.val[id];
                delete g.inputKoefisien.val[id];
                delete g.hargaAsli.val[id];
                delete g.total[id];

                $scope.jumlahHarga();
            };


            $scope.removeTempRow = function (kategori, index) {
                if (kategori === 'bahan') {
                    $scope.tempRowsBahan.splice(index, 1);
                }

                if (kategori === 'peralatan') {
                    $scope.tempRowsPeralatan.splice(index, 1);
                }

                if (kategori === 'tenagaKerja') {
                    $scope.tempRowsTenagaKerja.splice(index, 1);
                }
            };

            $scope.initSelect2BySelector = function (selector, placeholder, onSelect) {
                $timeout(function () {
                    let el = $(selector);
                    if (!el.length) return;

                    el.each(function () {
                        let $this = $(this);

                        if ($this.hasClass("select2-hidden-accessible")) {
                            $this.select2("destroy");
                        }

                        $this.select2({
                            width: "100%",
                            placeholder,
                            dropdownParent: $("#modal_detail"),
                            allowClear: true
                        });

                        $this.off("select2:select").on("select2:select", function () {
                            let id = $(this).val();
                            let index = $(this).data("row");
                            if (index === undefined) return;

                            if (!$scope.$$phase) {
                                $scope.$apply(() => onSelect(id, index));
                            } else {
                                onSelect(id, index);
                            }
                        });
                    });
                }, 0);
            };

            $scope.initSelect2 = function () {
                $scope.initSelect2BySelector(
                    ".row-select-bahan",
                    "Pilih Bahan",
                    (id, index) => $scope.addItemFromSelect("bahan", id, index)
                );

                $scope.initSelect2BySelector(
                    ".row-select-peralatan",
                    "Pilih Peralatan",
                    (id, index) => $scope.addItemFromSelect("peralatan", id, index)
                );

                $scope.initSelect2BySelector(
                    ".row-select-tenagaKerja",
                    "Pilih Tenaga Kerja",
                    (id, index) => $scope.addItemFromSelect("tenagaKerja", id, index)
                );
            };

            $scope.getKelompokById = function (idKelompok) {
                let data = $scope.options_kel_spesifikasi.find(x => x.id_spesifikasi == idKelompok);
                if (!data) return "";

                return (
                    data.kodeKelItem + " - " + data.UraianKelompok + " - " + data.UraianSpesifikasi + " - " + data.satuan
                );
            };

            // TO HERE
            $scope.show_modal = function (idSpesifikasi) {
                $scope.openHpsModal(idSpesifikasi);
                console.log('DATA BUAT EXCEL:', angular.copy($scope.hpsStore));
            };

            // HERE
            $scope.loadSavedData = function () {
                httpHandler.send({
                    method: "GET",
                    url: urls + "perkiraan_hps/getById",
                    params: { id: $scope.id },
                }).then(function (res) {

                    var detail = res.data.data;
                    // console.log(res);


                    $scope.idKegiatan = detail.id_thn_kegiatan;
                    $scope.inputTotal.val = detail.total_item;

                    let s = $scope.hpsStore[$scope.activeSpesifikasiId];
                    if (!s) return;

                    s.bahan.table.length = 0;
                    s.peralatan.table.length = 0;
                    s.tenagaKerja.table.length = 0;

                    detail.id_thn_harga.forEach(function (id) {
                        var item = $scope.options_kel_spesifikasi.find(function (o) { return o.id == id; });
                        if (!item) return;

                        if (item.tipe === "SSH" && item.kriteria === "bahan") {
                            s.bahan.table.push({ id, isDefault: true });
                        } else if (item.tipe === "SSH" && item.kriteria === "peralatan") {
                            s.peralatan.table.push({ id, isDefault: true });
                        } else if (item.tipe === "SBU" && item.kriteria === "upah") {
                            s.tenagaKerja.table.push({ id, isDefault: true });
                        }
                    });

                    $scope.jumlahHarga();
                });
            };

            $scope.getHarga = function (id) {
                let item = $scope.options_kel_spesifikasi.find(x => x.id_spesifikasi == id);
                return item?.value_harga ?? 0;
            };

            $scope.getTotal = function (kategori, id) {
                let g = $scope.getGroup(kategori);
                if (!g) return 0;

                let qty = +g.inputTotal.val[id] || 0;
                let harga = +g.inputHarga.val[id] || 0;
                let koef = +g.inputKoefisien.val[id] || 1;

                let total = qty * harga * koef;
                g.total[id] = total;

                $scope.jumlahHarga();
                return total;
            };

            $scope.jumlahHarga = function () {
                let s = $scope.hpsStore[$scope.activeSpesifikasiId];
                if (!s) return;

                let total = 0;

                [s.bahan, s.peralatan, s.tenagaKerja].forEach(group => {
                    group.table.forEach(row => {
                        total += group.total[row.id] || 0;
                    });
                });

                s.meta.jumlah = total;
                s.meta.total_percent = total * (s.meta.percent / 100);
                s.meta.total_all = total + s.meta.total_percent;
            };


            $scope.totalHarga = function (percent) {
                var p = parseFloat(percent || 0);
                $scope.total_percent = ($scope.jumlah * p) / 100;
                $scope.total_all = $scope.jumlah + $scope.total_percent;
            };
            // TO HERE

            $scope.exportExcelHPS = async function () {

                const s = $scope.hpsStore[$scope.activeSpesifikasiId];
                if (!s) {
                    alert('Data HPS belum dipilih');
                    return;
                }

                const bahan = s.bahan;
                const peralatan = s.peralatan;
                const tenagaKerja = s.tenagaKerja;
                const meta = s.meta;

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


                ws.mergeCells('A1:F1');
                ws.getCell('A1').value = 'SIMULASI HARGA PERKIRAAN SENDIRI (HPS)';
                setArial11(ws.getRow(1), true);
                ws.getRow(1).alignment = { horizontal: 'center' };

                const asb = $scope.options_asb.find(x => x.id == $scope.idAsb);
                const asbText = asb
                    ? `${asb.kodeKelompok} - ${asb.UraianKegiatan} (${asb.satuan}) - ${asb.tahunASB}`
                    : '';


                ws.mergeCells('A2:F2');
                ws.getCell('A2').value = asbText;
                setArial11(ws.getRow(2), false, true);
                ws.getRow(2).alignment = { horizontal: 'center' };

                rowNum = 2;

                ws.addRow([]);
                ws.addRow(['No', 'Uraian', 'Satuan', 'Perkiraan Kuantitas', 'Harga Satuan (Rp)', 'Jumlah Harga (Rp)']);
                rowNum = ws.lastRow.number;

                let headerRow = ws.getRow(rowNum);
                setArial11(headerRow, true);
                borderRow(headerRow);

                let no = 1;
                $scope.hspkList.forEach(hspk => {
                    const s = $scope.hpsStore[hspk.id];
                    if (!s) return;

                    const jumlah = s.meta.total_all || 0;

                    ws.addRow([
                        no++,
                        hspk.UraianKegiatan,
                        hspk.satuan,
                        1,
                        jumlah,
                        jumlah
                    ]);

                    rowNum++;
                    let r = ws.getRow(rowNum);
                    r.getCell(5).numFmt = '#,##0.00';
                    r.getCell(6).numFmt = '#,##0.00';
                    borderRow(r);
                });

                // const renderGroup = (label, title, group) => {
                //     ws.addRow([label, title]);
                //     rowNum++;

                //     ws.mergeCells(`B${rowNum}:G${rowNum}`);
                //     let gr = ws.getRow(rowNum);
                //     setArial11(gr, true);
                //     gr.getCell(1).alignment = { horizontal: 'center' };
                //     borderRow(gr);

                //     let no = 1;
                //     let subtotal = 0;

                //     group.table.forEach(row => {
                //         const d = $scope.options_kel_spesifikasi.find(
                //             x => x.id_spesifikasi == row.id
                //         );
                //         if (!d) return;

                //         const qty = Number(group.inputTotal.val[row.id]) || 0;
                //         const harga = Number(group.inputHarga.val[row.id]) || 0;
                //         const koef = Number(group.inputKoefisien.val[row.id]) || 1;

                //         const jumlah = qty * harga * koef;
                //         subtotal += jumlah;

                //         ws.addRow([
                //             no++,
                //             d.UraianKelompok,
                //             d.satuan || '',
                //             qty,
                //             harga,
                //             jumlah
                //         ]);

                //         rowNum++;
                //         let r = ws.getRow(rowNum);
                //         r.getCell(5).numFmt = '0.000';
                //         r.getCell(6).numFmt = '#,##0.00';
                //         r.getCell(7).numFmt = '#,##0.00';
                //         setArial11(r);
                //         borderRow(r);
                //     });

                //     ws.addRow(['', `JUMLAH HARGA ${title}`, '', '', '', '', subtotal]);
                //     rowNum++;

                //     ws.mergeCells(`B${rowNum}:F${rowNum}`);
                //     let sr = ws.getRow(rowNum);
                //     setArial11(sr, true);
                //     sr.getCell(2).alignment = { horizontal: 'center' };
                //     sr.getCell(7).numFmt = '#,##0.00';
                //     borderRow(sr);

                //     return subtotal;
                // };

                // const totalA = renderGroup('A', 'TENAGA KERJA', tenagaKerja);
                // const totalB = renderGroup('B', 'BAHAN', bahan);
                // const totalC = renderGroup('C', 'PERALATAN', peralatan);

                // const D = totalA + totalB + totalC;

                // ws.addRow(['D', 'Jumlah (A+B+C)', '', '', '', '', D]);
                // rowNum++;
                // ws.mergeCells(`B${rowNum}:F${rowNum}`);
                // let dr = ws.getRow(rowNum);
                // setArial11(dr, true);
                // dr.getCell(7).numFmt = '#,##0.00';
                // borderRow(dr);

                // const pct = Number(meta.percent || 0);
                // const E = meta.total_percent;
                // const F = meta.total_all;

                // ws.addRow(['E', `Biaya Umum dan Keuntungan ${pct}% x D`, '', '', `${pct}%`, '', E]);
                // rowNum++;
                // ws.mergeCells(`B${rowNum}:F${rowNum}`);
                // let er = ws.getRow(rowNum);
                // setArial11(er);
                // er.getCell(5).alignment = { horizontal: 'center' };
                // er.getCell(7).numFmt = '#,##0.00';
                // borderRow(er);

                // ws.addRow(['F', 'Harga Satuan Pekerjaan (D+E)', '', '', '', '', F]);
                // rowNum++;
                // ws.mergeCells(`B${rowNum}:F${rowNum}`);
                // let fr = ws.getRow(rowNum);
                // setArial11(fr, true);
                // fr.getCell(7).numFmt = '#,##0.00';
                // borderRow(fr);

                const ws2 = wb.addWorksheet('Rincian SSH');
                let row2 = 1;

                ws2.columns = [
                    { width: 6 },
                    { width: 45 },
                    { width: 14 },
                    { width: 12 },
                    { width: 12 },
                    { width: 18 }
                ];

                ws2.addRow([
                    'No',
                    'Uraian',
                    'Satuan',
                    'Koefisien',
                    'Harga',
                    'Jumlah'
                ]);

                let h2 = ws2.getRow(row2);
                setArial11(h2, true);
                borderRow(h2);

                const renderSSHGroup = (ws, title, group) => {
                    ws.addRow(['', title]);
                    row2++;

                    ws.mergeCells(`B${row2}:F${row2}`);
                    let gr = ws.getRow(row2);
                    setArial11(gr, true);
                    borderRow(gr);

                    let no = 1;
                    let subtotal = 0;

                    group.table.forEach(row => {
                        const d = $scope.options_kel_spesifikasi.find(
                            x => x.id_spesifikasi == row.id
                        );
                        if (!d) return;

                        const qty = Number(group.inputTotal.val[row.id]) || 0;
                        const harga = Number(group.inputHarga.val[row.id]) || 0;
                        const koef = Number(group.inputKoefisien.val[row.id]) || 1;

                        const jumlah = qty * harga * koef;
                        subtotal += jumlah;

                        ws.addRow([
                            no++,
                            d.UraianSpesifikasi,
                            d.satuan || '',
                            koef,
                            harga,
                            jumlah
                        ]);

                        row2++;
                        let r = ws.getRow(row2);
                        r.getCell(5).numFmt = '#,##0.00';
                        r.getCell(6).numFmt = '#,##0.00';
                        setArial11(r);
                        borderRow(r);
                    });

                    return subtotal;
                };

                $scope.hspkList.forEach(hspk => {
                    const s = $scope.hpsStore[hspk.id];
                    if (!s) return;

                    // Judul HSPK
                    ws2.addRow([`${hspk.kodeKelompok || ''}`, hspk.UraianKegiatan]);
                    row2++;

                    ws2.mergeCells(`B${row2}:F${row2}`);
                    let hr = ws2.getRow(row2);
                    setArial11(hr, true);
                    borderRow(hr);

                    // TENAGA KERJA
                    renderSSHGroup(ws2, 'TENAGA KERJA', s.tenagaKerja);

                    // BAHAN
                    renderSSHGroup(ws2, 'BAHAN', s.bahan);

                    // PERALATAN
                    renderSSHGroup(ws2, 'PERALATAN', s.peralatan);

                    // Total HSPK
                    ws2.addRow(['', 'TOTAL HSPK', '', '', '', s.meta.total_all]);
                    row2++;

                    ws2.mergeCells(`B${row2}:E${row2}`);
                    let tr = ws2.getRow(row2);
                    setArial11(tr, true);
                    tr.getCell(6).numFmt = '#,##0.00';
                    borderRow(tr);

                    ws2.addRow([]); // spasi antar HSPK
                    row2++;
                });


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

            // $scope.exportExcelHPS = async function () {

            //     const s = $scope.hpsStore[$scope.activeSpesifikasiId];
            //     if (!s) {
            //         alert('Data HPS belum dipilih');
            //         return;
            //     }

            //     const bahan = s.bahan;
            //     const peralatan = s.peralatan;
            //     const tenagaKerja = s.tenagaKerja;
            //     const meta = s.meta;

            //     const wb = new ExcelJS.Workbook();
            //     const ws = wb.addWorksheet('HPS');

            //     let rowNum = 1;

            //     const borderThin = {
            //         top: { style: 'thin' },
            //         left: { style: 'thin' },
            //         bottom: { style: 'thin' },
            //         right: { style: 'thin' }
            //     };

            //     const setArial11 = (row, bold = false, italic = false) => {
            //         row.eachCell(cell => {
            //             cell.font = { name: 'Arial', size: 11, bold, italic };
            //             cell.alignment = { ...cell.alignment, vertical: 'middle' };
            //         });
            //     };

            //     const borderRow = row => row.eachCell(c => c.border = borderThin);

            //     ws.columns = [
            //         { width: 8 },
            //         { width: 45 },
            //         { width: 14 },
            //         { width: 8 },
            //         { width: 12 },
            //         { width: 18 },
            //         { width: 20 }
            //     ];

            //     ws.getColumn(1).alignment = { horizontal: 'center' };
            //     ws.getColumn(3).alignment = { horizontal: 'center' };
            //     ws.getColumn(4).alignment = { horizontal: 'center' };
            //     ws.getColumn(5).alignment = { horizontal: 'center' };
            //     ws.getColumn(6).alignment = { horizontal: 'right' };
            //     ws.getColumn(7).alignment = { horizontal: 'right' };


            //     ws.mergeCells('A1:G1');
            //     ws.getCell('A1').value = 'SIMULASI HARGA PERKIRAAN SENDIRI (HPS)';
            //     setArial11(ws.getRow(1), true);
            //     ws.getRow(1).alignment = { horizontal: 'center' };

            //     const asb = $scope.options_asb.find(x => x.id == $scope.idAsb);
            //     const asbText = asb
            //         ? `${asb.kodeKelompok} - ${asb.UraianKegiatan} (${asb.satuan}) - ${asb.tahunASB}`
            //         : '';


            //     ws.mergeCells('A2:G2');
            //     ws.getCell('A2').value = asbText;
            //     setArial11(ws.getRow(2), false, true);
            //     ws.getRow(2).alignment = { horizontal: 'center' };

            //     rowNum = 2;

            //     ws.addRow([]);
            //     ws.addRow(['No', 'Uraian', 'Kode', 'Sat.', 'Koefisien', 'Harga Satuan (Rp)', 'Jumlah Harga (Rp)']);
            //     rowNum = ws.lastRow.number;

            //     let headerRow = ws.getRow(rowNum);
            //     setArial11(headerRow, true);
            //     borderRow(headerRow);

            //     const renderGroup = (label, title, group) => {
            //         ws.addRow([label, title]);
            //         rowNum++;

            //         ws.mergeCells(`B${rowNum}:G${rowNum}`);
            //         let gr = ws.getRow(rowNum);
            //         setArial11(gr, true);
            //         gr.getCell(1).alignment = { horizontal: 'center' };
            //         borderRow(gr);

            //         let no = 1;
            //         let subtotal = 0;

            //         group.table.forEach(row => {
            //             const d = $scope.options_kel_spesifikasi.find(
            //                 x => x.id_spesifikasi == row.id
            //             );
            //             if (!d) return;

            //             const qty = Number(group.inputTotal.val[row.id]) || 0;
            //             const harga = Number(group.inputHarga.val[row.id]) || 0;
            //             const koef = Number(group.inputKoefisien.val[row.id]) || 1;

            //             const jumlah = qty * harga * koef;
            //             subtotal += jumlah;

            //             ws.addRow([
            //                 no++,
            //                 d.UraianKelompok,
            //                 d.kodeKelItem || '',
            //                 d.satuan || '',
            //                 koef,
            //                 harga,
            //                 jumlah
            //             ]);

            //             rowNum++;
            //             let r = ws.getRow(rowNum);
            //             r.getCell(5).numFmt = '0.000';
            //             r.getCell(6).numFmt = '#,##0.00';
            //             r.getCell(7).numFmt = '#,##0.00';
            //             setArial11(r);
            //             borderRow(r);
            //         });

            //         ws.addRow(['', `JUMLAH HARGA ${title}`, '', '', '', '', subtotal]);
            //         rowNum++;

            //         ws.mergeCells(`B${rowNum}:F${rowNum}`);
            //         let sr = ws.getRow(rowNum);
            //         setArial11(sr, true);
            //         sr.getCell(2).alignment = { horizontal: 'center' };
            //         sr.getCell(7).numFmt = '#,##0.00';
            //         borderRow(sr);

            //         return subtotal;
            //     };

            //     const totalA = renderGroup('A', 'TENAGA KERJA', tenagaKerja);
            //     const totalB = renderGroup('B', 'BAHAN', bahan);
            //     const totalC = renderGroup('C', 'PERALATAN', peralatan);

            //     const D = totalA + totalB + totalC;

            //     ws.addRow(['D', 'Jumlah (A+B+C)', '', '', '', '', D]);
            //     rowNum++;
            //     ws.mergeCells(`B${rowNum}:F${rowNum}`);
            //     let dr = ws.getRow(rowNum);
            //     setArial11(dr, true);
            //     dr.getCell(7).numFmt = '#,##0.00';
            //     borderRow(dr);

            //     const pct = Number(meta.percent || 0);
            //     const E = meta.total_percent;
            //     const F = meta.total_all;

            //     ws.addRow(['E', `Biaya Umum dan Keuntungan ${pct}% x D`, '', '', `${pct}%`, '', E]);
            //     rowNum++;
            //     ws.mergeCells(`B${rowNum}:F${rowNum}`);
            //     let er = ws.getRow(rowNum);
            //     setArial11(er);
            //     er.getCell(5).alignment = { horizontal: 'center' };
            //     er.getCell(7).numFmt = '#,##0.00';
            //     borderRow(er);

            //     ws.addRow(['F', 'Harga Satuan Pekerjaan (D+E)', '', '', '', '', F]);
            //     rowNum++;
            //     ws.mergeCells(`B${rowNum}:F${rowNum}`);
            //     let fr = ws.getRow(rowNum);
            //     setArial11(fr, true);
            //     fr.getCell(7).numFmt = '#,##0.00';
            //     borderRow(fr);

            //     ws.pageSetup.printArea = `A1:G${rowNum}`;

            //     const buf = await wb.xlsx.writeBuffer();
            //     const blob = new Blob([buf], {
            //         type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            //     });

            //     const a = document.createElement('a');
            //     a.href = URL.createObjectURL(blob);
            //     a.download = 'Harga Perkiraan Sendiri (HPS).xlsx';
            //     a.click();
            //     URL.revokeObjectURL(a.href);
            // };
        }
    ]);

$(document).ready(function () {
    $("#idAsb").select2({
        placeholder: "Pilih Tahun Kegiatan",
        width: '100%'
    });

    $("#idAsb").on("change", function () {
        var scope = angular.element($("#idAsb")).scope();
        scope.$apply(function () {
            scope.idAsb = $("#idAsb").val();
            scope.getHspk(scope.idAsb);
        });
    });
});

