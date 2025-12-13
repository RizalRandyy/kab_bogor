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

								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="row p-0">
										<div class="col-md-6 col-lg-12">
											<form>
												<div class="form-group row">
													<label for="idKegiatan" class="col-sm-4 col-form-label text-sm">Harga Satuan Pokok Pekerjaan</label>
													<div class="col-sm-8">
														<select class="form-control" id="idKegiatan" ng-model="idKegiatan">
															<option value="" disabled>Pilih Tahun Pekerjaan</option>
															<option ng-repeat="x in options_kegiatan"
																value="{{x.id}}">{{x.kodeKelompok}} - {{x.UraianKegiatan}} - ({{x.satuan}}) - {{x.tahunPekerjaan}}</option>
														</select>
													</div>
												</div>
												<p>Debug idKegiatan: {{ idKegiatan }}</p>
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12">

														<table class="table table-striped">
															<thead>
																<tr>
																	<th colspan="7" class="text-center" style="background:#AFEEEE">
																		TENAGA KERJA
																	</th>
																</tr>
																<tr>
																	<th>Item</th>
																	<th>Harga Asli</th>
																	<th>Harga Toko</th>
																	<th></th>
																	<th>Volume</th>
																	<th>Total</th>
																	<th>Aksi</th>
																</tr>
															</thead>

															<tbody>
																<tr ng-repeat="row in tableTenagaKerja track by $index">
																	<td style="width: 30%;">
																		<input type="text" class="form-control"
																			ng-value="getKelompokById(row.id)" disabled>
																	</td>
																	<td class="text-right">
																		{{ hargaAsli.val[row.id] | currency:'Rp. ' }}
																	</td>

																	<td>
																		<input class="form-control text-right"
																			ng-model="inputHarga.val[row.id]">
																	</td>
																	<td>X</td>
																	<td>
																		<input class="form-control"
																			ng-model="inputTotal.val[row.id]">
																	</td>
																	<td>
																		{{ getTotal(row.id, inputTotal.val[row.id]) | currency:'Rp. ' }}
																	</td>
																	<td>
																		<button type="button" class="btn btn-danger btn-sm"
																			ng-click="removeItemKategori('tenaga', row.id)">
																			Hapus
																		</button>
																	</td>
																</tr>

																<!-- ROW TAMBAHAN -->
																<tr ng-repeat="(i, row) in tempRowsTenagaKerja">
																	<td colspan="6">
																		<select class="form-control row-select-tenaga"
																			data-row="{{i}}"
																			ng-if="options_kel_spesifikasi.length > 0">

																			<option value="">Pilih Tenaga Kerja</option>
																			<option ng-repeat="opt in filterTenaga()" value="{{opt.id_kelompok}}">
																				{{opt.kodeKelItem}} - {{opt.UraianKelompok}}
																			</option>


																		</select>

																	</td>
																</tr>

																<!-- TOMBOL TAMBAH -->
																<tr>
																	<td colspan="7">
																		<button type="button" class="btn btn-success btn-sm"
																			ng-click="addRowTenagaKerja()">
																			+ Tambah Tenaga Kerja
																		</button>
																	</td>
																</tr>
															</tbody>
														</table>
														<table class="table table-striped">
															<thead>
																<tr>
																	<th colspan="7" class="text-center" style="background:#AFEEEE">
																		BAHAN
																	</th>
																</tr>
																<tr>
																	<th>Item</th>
																	<th>Harga Asli</th>
																	<th>Harga Toko</th>
																	<th></th>
																	<th>Volume</th>
																	<th>Total</th>
																	<th>Aksi</th>
																</tr>
															</thead>

															<tbody>
																<tr ng-repeat="row in tableBahan track by $index">
																	<td style="width: 30%;">
																		<input type="text" class="form-control"
																			ng-value="getKelompokById(row.id)" disabled>
																	</td>
																	<td class="text-right">
																		{{ hargaAsli.val[row.id] | currency:'Rp. ' }}
																	</td>

																	<td>
																		<input class="form-control text-right"
																			ng-model="inputHarga.val[row.id]">
																	</td>
																	<td>X</td>
																	<td>
																		<input class="form-control"
																			ng-model="inputTotal.val[row.id]">
																	</td>
																	<td>
																		{{ getTotal(row.id, inputTotal.val[row.id]) | currency:'Rp. ' }}
																	</td>
																	<td>
																		<button type="button" class="btn btn-danger btn-sm"
																			ng-click="removeItemKategori('bahan', row.id)">
																			Hapus
																		</button>
																	</td>
																</tr>

																<!-- ROW TAMBAHAN -->
																<tr ng-repeat="(i, row) in tempRowsBahan">
																	<td colspan="7">
																		<select class="form-control row-select-bahan"
																			data-row="{{i}}">
																			<option value="">Pilih Bahan</option>
																			<option ng-repeat="opt in filterBahan()" value="{{opt.id_kelompok}}">
																				{{opt.kodeKelItem}} - {{opt.UraianKelompok}}
																			</option>

																		</select>
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
														<table class="table table-striped">
															<thead>
																<tr>
																	<th colspan="7" class="text-center" style="background:#AFEEEE">
																		PERALATAN
																	</th>
																</tr>
																<tr>
																	<th>Item</th>
																	<th>Harga Asli</th>
																	<th>Harga Toko</th>
																	<th></th>
																	<th>Volume</th>
																	<th>Total</th>
																	<th>Aksi</th>
																</tr>
															</thead>

															<tbody>
																<tr ng-repeat="row in tablePeralatan track by $index">
																	<td style="width: 30%;">
																		<input type="text" class="form-control"
																			ng-value="getKelompokById(row.id)" disabled>
																	</td>
																	<td class="text-right">
																		{{ hargaAsli.val[row.id] | currency:'Rp. ' }}
																	</td>

																	<td>
																		<input class="form-control text-right"
																			ng-model="inputHarga.val[row.id]">
																	</td>
																	<td>X</td>
																	<td>
																		<input class="form-control"
																			ng-model="inputTotal.val[row.id]">
																	</td>
																	<td>
																		{{ getTotal(row.id, inputTotal.val[row.id]) | currency:'Rp. ' }}
																	</td>
																	<td>
																		<button type="button" class="btn btn-danger btn-sm"
																			ng-click="removeItemKategori('peralatan', row.id)">
																			Hapus
																		</button>
																	</td>
																</tr>

																<!-- ROW TAMBAHAN -->
																<tr ng-repeat="(i, row) in tempRowsPeralatan">
																	<td colspan="7">
																		<select class="form-control row-select-peralatan"
																			data-row="{{i}}">
																			<option value="">Pilih Peralatan</option>
																			<option ng-repeat="opt in filterPeralatan()" value="{{opt.id_kelompok}}">
																				{{opt.kodeKelItem}} - {{opt.UraianKelompok}}
																			</option>

																		</select>
																	</td>
																</tr>


																<!-- TOMBOL TAMBAH -->
																<tr>
																	<td colspan="6">
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
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="jumlah" class="col-sm-9 col-form-label text-sm">Jumlah</label>
										<div class="col-sm-3">
											<input type="text-right" class="form-control" ng-value="jumlah| currency:'Rp. '" disabled>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="biaya_umum" class="col-sm-9 col-form-label text-sm">Biaya Umum & Keuntungan</label>
										<div class="col-sm-3">
											<div class="input-group input-group-sm">
												<div class="input-group-append">
													<input type="text-right" id="percent" name="percent" class="form-control" ng-model="percent">
													<button class="btn btn-danger" style="cursor: text; padding: 1.25rem 1.5rem;" type="button">%</button>
												</div>
												<div class="input-group-append">
													<input type="text-right" class="form-control" ng-value="total_percent| currency:'Rp. '" disabled>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group row">
										<label for="harga_hsp" class="col-sm-9 col-form-label text-sm">Total Harga Satuan Pekerjaan</label>
										<div class="col-sm-3">
											<input type="text-right" class="form-control" ng-value="total_all| currency:'Rp. '" disabled>
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
</div>