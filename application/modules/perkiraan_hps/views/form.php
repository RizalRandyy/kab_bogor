<div ng-controller="<?= $page ?>" id="<?= $page ?>">
	<script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
	<section class="section">
		<div class="row animate-box">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card">
					<div class="card-body">
						<form id="addReq" name="addReq" ng-submit="save()" class="mt-0">
							<input type="hidden" id="id" value="<?= $id ?>">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:25px; margin-bottom:25px;">
									<h3 class="text-center">SIMULASI HARGA PERKIRAAN SENDIRI (HPS)<br>KONSTRUKSI UMUM</h3>
								</div>

								<!-- Isi -->
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="row p-0">
										<div class="col-md-6 col-lg-12">
											<form>
												<div class="form-group row">
													<label for="idAsb" class="col-sm-4 col-form-label text-sm">Analisis Standar Belanja (ASB)</label>
													<div class="col-sm-8">
														<select class="form-control" id="idAsb" ng-model="idAsb" ng-change="getHspk(idAsb)">
															<option value="" disabled>Pilih Tahun Pekerjaan</option>
															<option ng-repeat="x in options_asb"
																value="{{x.id}}">{{x.idASB}} - {{x.UraianKegiatan}} - ({{x.satuan}}) - {{x.tahunASB}}</option>
														</select>
													</div>
												</div>

												<!-- Tabel -->
												<div class="table-responsive">
													<table class="table table-striped table-md" style="font-size: 12px;">
														<thead>
															<tr>
																<th>No</th>
																<th class="text-center" style="width: 15%;">Kode Item</th>
																<th class="text-center" style="width: 40%;">Uraian Kegiatan</th>
																<th class="text-center" style="width: 10%;">Satuan</th>
																<th class="text-center" style="width: 10%;">Tahun Kegiatan</th>
																<th class="text-center" style="width: 20%;">Harga Kegiatan</th>
																<th class="text-center" style="width: 5%;"></th>
															</tr>
														</thead>
														<tbody>
															<tr ng-show="message != null">
																<td colspan="6" class="text-center" ng-bind="message"></td>
															</tr>
															<tr>
																<td colspan="6" ng-show="loading">
																	<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
																	Loading...
																</td>
															</tr>
															<tr ng-hide="loading"
																ng-repeat="(key, value) in data track by $index">
																<td>{{ key + 1 }}</td>
																<td class="text-center" ng-bind="value.kodeKelompok"></td>
																<td class="text-center" ng-bind="value.UraianKegiatan"></td>
																<td class="text-center" ng-bind="value.satuan"></td>
																<td class="text-center" ng-bind="value.tahunASB"></td>
																<td class="text-right">
																	{{ getHargaKegiatan(value.id) | number:0 }}
																</td>
																<td class="text-center" style="white-space: nowrap;">
																	<a href="" class="btn btn-info btn-sm p-1" title="View: {{value.kodeKelompok}}" ng-click="show_modal(value.id)">
																		<i class="fas fa-eye"></i>&nbsp;
																	</a>
																</td>
															</tr>
														</tbody>
													</table>
												</div>

											</form>
										</div>
									</div>
								</div>

								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="jumlah" class="col-sm-9 col-form-label text-sm">Jumlah</label>
										<div class="col-sm-3">
											<input class="form-control"
												ng-value="hpsStore[activeSpesifikasiId].meta.jumlah | currency:'Rp. '"
												disabled>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="biaya_umum" class="col-sm-9 col-form-label text-sm">Biaya Umum & Keuntungan</label>
										<div class="col-sm-3">
											<div class="input-group input-group-sm">
												<div class="input-group-append">
													<input id="percent"
														class="form-control"
														type="number"
														ng-model="hpsStore[activeSpesifikasiId].meta.percent"
														ng-change="jumlahHarga()">
													<button class="btn btn-danger" style="cursor: text; padding: 1.25rem 1.5rem;" type="button">%</button>
												</div>
												<div class="input-group-append">
													<input class="form-control"
														ng-value="hpsStore[activeSpesifikasiId].meta.total_percent | currency:'Rp. '"
														disabled>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="harga_hsp" class="col-sm-9 col-form-label text-sm">Total Harga Satuan Pekerjaan</label>
										<div class="col-sm-3">
											<input class="form-control"
												ng-value="hpsStore[activeSpesifikasiId].meta.total_all | currency:'Rp. '"
												disabled>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group">
										<button class="btn btn-success"
											type="button"
											ng-click="exportExcelHPS()">
											Download Excel
										</button>

									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

	<style>
		.modal-hps {
			max-width: 95% !important;
		}

		.table-hps {
			font-size: 12px;
		}

		.font-12 {
			font-size: 12px !important;
		}

		.table-hps th,
		.table-hps td {
			padding: 0.35rem;
		}

		.col-total {
			width: 140px;
			min-width: 140px;
			max-width: 140px;
			white-space: nowrap;
		}
	</style>

	<div class="modal fade" id="modal_detail" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-hps">
			<div class="modal-content">
				<div class="modal-header flex-column align-items-center text-center">
					<h5 class="modal-title" ng-bind="hspkTitle"></h5>

					<h6 class="mt-1" id="mdlInviteLabel">SIMULASI HARGA PERKIRAAN SENDIRI (HPS)</h6>

					<button type="button"
						class="close position-absolute"
						style="right: 15px; top: 15px;"
						data-dismiss="modal"
						aria-label="Close"
						ng-click="close_modal_fleet()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body">
					<form id="addReq" name="addReq" class="mt-4">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">

								<table class="table table-striped table-hps">
									<thead>
										<tr>
											<th colspan="7" class="text-center" style="background:#AFEEEE">
												PEKERJA
											</th>
										</tr>
										<tr>
											<th>Item</th>
											<th>SHT Perbub</th>
											<th class="col-total">Harga Survey</th>
											<th>Volume / Kuantitas</th>
											<th>Koefisien</th>
											<th class="col-total">Total</th>
											<th>Aksi</th>
										</tr>
									</thead>

									<tbody>
										<tr ng-repeat="row in tableTenagaKerja track by $index">
											<td style="width: 30%;">
												<input type="text" class="form-control font-12"
													ng-value="getKelompokById(row.id)" disabled>
											</td>
											<td class="text-right">
												{{ getGroup('tenagaKerja').hargaAsli.val[row.id] | number }}
											</td>

											<td>
												<input
													class="form-control text-right font-12 col-total"
													ng-model="getGroup('tenagaKerja').inputHarga.val[row.id]"
													ng-change="getTotal('tenagaKerja', row.id)">
											</td>
											<td>
												<input
													class="form-control text-right font-12"
													ng-model="getGroup('tenagaKerja').inputTotal.val[row.id]"
													ng-change="getTotal('tenagaKerja', row.id)">
											</td>
											<td>
												<input
													step="0.001"
													class="form-control text-right font-12"
													ng-model="getGroup('tenagaKerja').inputKoefisien.val[row.id]"
													ng-change="getTotal('tenagaKerja', row.id)">
											</td>
											<td class="col-total text-right">
												{{ getTotal('tenagaKerja', row.id) | currency:'Rp. ' }}
											</td>
											<td>
												<button type="button" class="btn btn-danger btn-sm font-12"
													ng-click="removeItemKategori('tenagaKerja', row.id)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- ROW TAMBAHAN -->
										<tr ng-repeat="(i, row) in tempRowsTenagaKerja">
											<td colspan="6">
												<select class="form-control row-select-tenagaKerja"
													data-row="{{i}}">
													<option value="">Pilih Pekerja</option>
													<option ng-repeat="opt in filterTenagaKerja()" value="{{opt.id_spesifikasi}}">
														{{opt.kodeKelItem}} - {{opt.UraianKelompok}} - {{opt.UraianSpesifikasi}} - {{opt.satuan}}
													</option>
												</select>
											</td>
											<td>
												<button
													type="button"
													class="btn btn-danger btn-sm font-12"
													ng-click="removeTempRow('tenagaKerja', $index)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- TOMBOL TAMBAH -->
										<tr>
											<td colspan="7">
												<button type="button" class="btn btn-success btn-sm"
													ng-click="addRowTenagaKerja()">
													+ Tambah Pekerja
												</button>
											</td>
										</tr>
									</tbody>
								</table>

								<table class="table table-striped table-hps">
									<thead>
										<tr>
											<th colspan="7" class="text-center" style="background:#AFEEEE">
												BAHAN
											</th>
										</tr>
										<tr>
											<th>Item</th>
											<th>SHT Perbub</th>
											<th class="col-total">Harga Toko</th>
											<th>Volume / Kuantitas</th>
											<th>Koefisien</th>
											<th class="col-total">Total</th>
											<th>Aksi</th>
										</tr>
									</thead>

									<tbody>
										<tr ng-repeat="row in tableBahan track by $index">
											<td style="width: 30%;">
												<input type="text" class="form-control font-12"
													ng-value="getKelompokById(row.id)" disabled>
											</td>
											<td class="text-right">
												{{ getGroup('bahan').hargaAsli.val[row.id] | number }}
											</td>

											<td>
												<input
													class="form-control text-right font-12 col-total"
													ng-model="getGroup('bahan').inputHarga.val[row.id]"
													ng-change="getTotal('bahan', row.id)">
											</td>
											<td>
												<input
													class="form-control text-right font-12"
													ng-model="getGroup('bahan').inputTotal.val[row.id]"
													ng-change="getTotal('bahan', row.id)">
											</td>
											<td>
												<input
													step="0.001"
													class="form-control text-right font-12"
													ng-model="getGroup('bahan').inputKoefisien.val[row.id]"
													ng-change="getTotal('bahan', row.id)">
											</td>
											<td class="col-total text-right font-12">
												{{ getTotal('bahan', row.id) | currency:'Rp. ' }}
											</td>
											<td>
												<button type="button" class="btn btn-danger btn-sm font-12"
													ng-click="removeItemKategori('bahan', row.id)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- ROW TAMBAHAN -->
										<tr ng-repeat="(i, row) in tempRowsBahan">
											<td colspan="6">
												<select class="form-control row-select-bahan"
													data-row="{{i}}">
													<option value="">Pilih Bahan</option>
													<option ng-repeat="opt in filterBahan()" value="{{opt.id_spesifikasi}}">
														{{opt.kodeKelItem}} - {{opt.UraianKelompok}} - {{opt.UraianSpesifikasi}} - {{opt.satuan}}
													</option>
												</select>
											</td>
											<td>
												<button
													type="button"
													class="btn btn-danger btn-sm font-12"
													ng-click="removeTempRow('bahan', $index)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- TOMBOL TAMBAH -->
										<tr>
											<td colspan="7">
												<button type="button" class="btn btn-success btn-sm"
													ng-click="addRowBahan()">
													+ Tambah Bahan
												</button>
											</td>
										</tr>
									</tbody>
								</table>

								<table class="table table-striped table-hps">
									<thead>
										<tr>
											<th colspan="7" class="text-center" style="background:#AFEEEE">
												PERALATAN
											</th>
										</tr>
										<tr>
											<th>Item</th>
											<th>SHT Perbub</th>
											<th class="col-total">Harga Toko</th>
											<th>Volume / Kuantitas</th>
											<th>Koefisien</th>
											<th class="col-total">Total</th>
											<th>Aksi</th>
										</tr>
									</thead>

									<tbody>
										<tr ng-repeat="row in tablePeralatan track by $index">
											<td style="width: 30%;">
												<input type="text" class="form-control font-12"
													ng-value="getKelompokById(row.id)" disabled>
											</td>
											<td class="text-right">
												{{ getGroup('peralatan').hargaAsli.val[row.id] | number }}
											</td>

											<td>
												<input
													class="form-control text-right font-12 col-total"
													ng-model="getGroup('peralatan').inputHarga.val[row.id]"
													ng-change="getTotal('peralatan', row.id)">
											</td>
											<td>
												<input
													class="form-control text-right font-12"
													ng-model="getGroup('peralatan').inputTotal.val[row.id]"
													ng-change="getTotal('peralatan', row.id)">
											</td>
											<td>
												<input
													step="0.001"
													class="form-control text-right font-12"
													ng-model="getGroup('peralatan').inputKoefisien.val[row.id]"
													ng-change="getTotal('peralatan', row.id)">
											</td>
											<td class="col-total text-right">
												{{ getTotal('peralatan', row.id) | currency:'Rp. ' }}
											</td>
											<td>
												<button type="button" class="btn btn-danger btn-sm font-12"
													ng-click="removeItemKategori('peralatan', row.id)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- ROW TAMBAHAN -->
										<tr ng-repeat="(i, row) in tempRowsPeralatan">
											<td colspan="6">
												<select class="form-control row-select-peralatan"
													data-row="{{i}}">
													<option value="">Pilih Peralatan</option>
													<option ng-repeat="opt in filterPeralatan()" value="{{opt.id_spesifikasi}}">
														{{opt.kodeKelItem}} - {{opt.UraianKelompok}} - {{opt.UraianSpesifikasi}} - {{opt.satuan}}
													</option>
												</select>
											</td>
											<td>
												<button
													type="button"
													class="btn btn-danger btn-sm font-12"
													ng-click="removeTempRow('peralatan', $index)">
													Hapus
												</button>
											</td>
										</tr>

										<!-- TOMBOL TAMBAH -->
										<tr>
											<td colspan="7">
												<button type="button" class="btn btn-success btn-sm"
													ng-click="addRowPeralatan()">
													+ Tambah Peralatan
												</button>
											</td>
										</tr>
									</tbody>
								</table>

							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
</div>