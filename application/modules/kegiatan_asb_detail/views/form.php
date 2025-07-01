<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Isian HSPK > Tahun Kegiatan > <?= $title ?></span>
					</div>
				</div>
				<div class="card">
					<div class="card-body">
						<form id="addReq" name="addReq" ng-submit="save()" class="mt-4">
							<input type="hidden" id="id" value="<?= $id ?>">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12">
									<div class="row p-0">
										<div class="col-md-6 col-lg-12">
											<form>
												<div class="form-group row">
													<label for="idKegiatan" class="col-sm-4 col-form-label text-sm">Kegiatan ASB</label>
													<div class="col-sm-8">
														<select class="form-control" id="idKegiatan" ng-model="idKegiatan">
															<option value="" disabled>Pilih Kegiatan ASB</option>
															<option ng-repeat="x in options_kegiatan" value="{{x.id}}" name="{{x.id+'-'+x.kodeKelompok}}" id="{{x.id+'-'+x.kodeKelompok}}">{{x.kodeKelompok}} - {{x.UraianKegiatan}} - ({{x.satuan}}) - {{x.tahunASB}}</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="idKelItem" class="col-sm-4 col-form-label text-sm">Kelompok HSPK</label>
													<div class="col-sm-8">
														<select multiple class="form-control select2" id="item" >
															<option value="" disabled>Pilih Kelompok HSPK</option>
															<option ng-repeat="option2 in options_kel_spesifikasi" value="{{option2.id}}" name="{{option2.id}}" id="{{option2.id}}">{{option2.kodeKelompok}} - {{option2.UraianKegiatan}} - {{option2.satuan}} - {{option2.tahunPekerjaan}} - {{option2.harga}}</option>
														</select>
														<div><span>Silahkan pilih beberapa item</span></div>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12">

														<div class="table-responsive">
															<table class="table table-striped table-md">
																<thead>
																	<tr>
																		<th class="text-center" style="background-color: #AFEEEE;" colspan="2" ng-bind="viewKegiatan"></th>
																	</tr>
																	<tr>
																		<th class="text-center">Kelompok HSPK</th>
																		<th class="text-center">Harga</th>
																		<th class="text-center">Action</th>
																	</tr>
																</thead>
																<tbody id="table-body">
																	<tr>
																		<td class="text-center" colspan="2" ng-show="loading">
																			<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
																			Loading...
																		</td>
																	</tr>
																	<tr ng-hide="loading" ng-repeat="(key,id) in tableKelompok">
																		<td style="width: 80%;">
																			<input type="text" name="kelItem[]" class="form-control" ng-value="getKelompokById(id)" disabled>
																		</td>
																		<td style="width: 20%;">
																			<input type="text" name="total[]" class="form-control text-right" ng-value="getTotal(id, total_item[id])| currency:'Rp. '" disabled>
																			<input type="hidden" name="total_hide[]" ng-value="getTotal(id, total_item[id])" disabled>
																		</td>
																		<td style="width: 5%;">
																			<button type="button" class="btn btn-danger btn-sm" ng-click="removeItem(id)">Hapus</button>
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
										<label for="idKelItem" class="col-sm-9 col-form-label text-sm">Total</label>
										<div class="col-sm-3">
											<input type="text-right" class="form-control" ng-value="total_all| currency:'Rp. '" disabled>
										</div>
									</div>
								</div>
								<div class="col-12 col-md-12 col-lg-12 text-right">
									<div class="form-group">
										<button class="btn btn-info" type="submit"> Simpan <i class="fas fa-save"></i>
										</button>&nbsp;&nbsp;
										<button class="btn btn-dark" ng-click="redirect()" type="button"> Kembali <i class="fas fa-undo-alt"></i>
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