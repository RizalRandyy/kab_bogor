<div ng-controller="<?= $page ?>" id="<?= $page ?>">
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
												<!-- <div class="form-group row">
													<label for="idKelItem" class="col-sm-4 col-form-label text-sm">Kode Kelompok Item</label>
													<div class="col-sm-8">
														<select multiple class="form-control select2" id="item" ng-model="idKelItem">
															<option value="" disabled>Pilih Kode Kelompok Item</option>
															<option ng-repeat="option2 in options_kel_spesifikasi" value="{{option2.id}}" name="{{option2.id}}" id="{{option2.id}}">{{option2.kodeKelompok}} - {{option2.UraianKelompok}} - {{option2.NamaJenis}} - {{option2.UraianSpesifikasi}} - {{option2.satuan}} - ({{option2.tipe}}) - {{option2.TahunHarga}} - {{option2.harga}}</option>
														</select>
														<div><span>Silahkan pilih beberapa item</span></div>
													</div>
												</div> -->
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12">

														<div class="table-responsive">
															<table class="table table-striped table-md">
																<thead>
																	<tr>
																		<th class="text-center" style="background-color: #AFEEEE;" colspan="6" ng-bind="viewKegiatan"></th>
																	</tr>
																	<tr>
																		<th class="text-center">Kelompok Item</th>
																		<!-- <th class="text-center">Satuan</th> -->
																		<th class="text-center">Harga</th>
																		<th class="text-center"></th>
																		<th class="text-center">Volume</th>
																		<th class="text-center">Total</th>
																		<th class="text-center" ng-if="hasAdditionalItems()">Aksi</th>
																	</tr>
																</thead>
																<tbody>
																	<tr>
																		<td class="text-center" colspan="5" ng-show="loading">
																			<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
																			Loading...
																		</td>
																	</tr>
																	<tr ng-hide="loading" ng-repeat="(key,row) in tableKelompok track by $index">
																		<td style="width: 60%;">
																			<input type="text" class="form-control" style="font-size: 12px;" ng-value="getKelompokById(row.id)" disabled>
																		</td>
																		<!-- <td style="width: 7%;">
																			<input type="text" class="form-control" style="font-size: 12px;" ng-value="getSatuan(row.id)" disabled>
																		</td> -->
																		<td class="text-right" style="width: 15%;">
																			<input name="harga[]" id="harga_{{row.id}}" class="form-control text-right" style="font-size: 12px;" ng-model="inputHarga.val[row.id]" ng-value="getHarga(row.id)">
																		</td>
																		<td class="text-wrap" style="width: 1%;">X</td>
																		<td style="width: 10%;">
																			<input name="banyak[]" id="banyak_{{row.id}}" class="form-control" style="font-size: 12px;" ng-model="inputTotal.val[row.id]" ng-value="total_item[row.id]">
																		</td>
																		<td style="width: 15%;">
																			<input type="text" name="total[]" class="form-control text-right" style="font-size: 12px;" ng-value="getTotal(row.id, total_item[row.id]) | currency:'Rp. '" disabled>

																			<input type="hidden" name="total_hide[]" ng-value="getTotal(row.id, total_item[row.id])" disabled>
																		</td>
																		<td ng-if="!row.isDefault" style="width: 5%; text-align:center;">
																			<button type="button" class="btn btn-danger btn-sm" ng-click="removeItem(row.id)">
																				X
																			</button>
																		</td>
																		<td ng-if="row.isDefault" style="width: 5%;"></td>
																	</tr>

																	<tr ng-repeat="(key,row) in tempRows">
																		<td colspan="5">
																			<select class="form-control row-select"
																				data-row="{{key}}">
																				<option value="" disabled selected>Pilih Kode Kelompok Item</option>
																				<option ng-repeat="option2 in options_kel_spesifikasi"
																					value="{{option2.id}}">
																					{{option2.kodeKelompok}} - {{option2.UraianKelompok}} -
																					{{option2.NamaJenis}} - {{option2.UraianSpesifikasi}} -
																					{{option2.satuan}} - ({{option2.tipe}}) -
																					{{option2.TahunHarga}} - {{option2.harga}}
																				</option>
																			</select>
																		</td>
																	</tr>
																	<tr>
																		<td colspan="5">
																			<button type="button" class="btn btn-success btn-sm" ng-click="addNewRow()">+ Tambah Item</button>
																		</td>
																	</tr>
																</tbody>
															</table>
														</div>
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
										<button class="btn btn-info" type="submit"> Download
										</button>&nbsp;&nbsp;
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