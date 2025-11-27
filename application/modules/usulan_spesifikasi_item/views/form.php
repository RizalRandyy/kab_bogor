<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Usulan > Isian SSH > SSH > <?= $title ?></span>
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
													<label for="kodeItem" class="col-sm-4 col-form-label text-sm">Kode Kelompok Item</label>
													<div class="col-sm-8">
														<select class="form-control" id="kodeItem" ng-model="kodeItem">
															<option value="" disabled>Pilih Kelompok Item</option>
															<option ng-repeat="option in options" value="{{option.id}}" name="{{option.id}}" id="{{option.id}}">{{option.IdKelItem}} - {{option.UraianKelompok}} - {{option.NamaJenis}} - ({{option.tipe}})</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="idSpesifikasi" class="col-sm-4 col-form-label text-sm">Kode Spesifikasi</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="idSpesifikasi" name="idSpesifikasi" ng-model="idSpesifikasi" ng-maxlength="6" placeholder="Input Kode Spesifikasi">
													</div>
													<div class="col-sm-4"></div>
													<div class="col-sm-8">
														<span ng-show="addReq.idSpesifikasi.$error.maxlength" style="color: red;">Kode Spesifikasi Maksimal hanya 6 Karakter!</span>
													</div>
												</div>
												<div class="form-group row">
													<label for="UraianSpesifikasi" class="col-sm-4 col-form-label text-sm">Nama Spesifikasi</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="UraianSpesifikasi" name="UraianSpesifikasi" ng-model="UraianSpesifikasi" placeholder="Input Nama Spesifikasi">
													</div>
												</div>
												<div class="form-group row">
													<label for="satuan" class="col-sm-4 col-form-label text-sm">satuan</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="satuan" name="satuan" ng-model="satuan" placeholder="Input Satuan Item">
													</div>
												</div>
												<div class="form-group row">
													<label for="tahun" class="col-sm-4 col-form-label text-sm">Tahun</label>
													<div class="input-group col-sm-8">
														<input class="form-control" ng-minlength="4" ng-maxlength="4" id="tahun" name="tahun" ng-model="tahun" ng-pattern="/^\d+$/" placeholder="Input Tahun">

													</div>
													<div class="col-sm-4"></div>
													<div class="col-sm-8">
														<span ng-show="addReq.tahun.$error.maxlength" style="color: red;">Input Tahun 4 Karakter!</span>
														<span ng-show="addReq.tahun.$error.minlength" style="color: red;">Input Tahun 4 Karakter!</span>
													</div>
												</div>
												<div class="form-group row">
													<label for="harga" class="col-sm-4 col-form-label text-sm">Harga</label>
													<div class="input-group col-sm-8">
														<div class="input-group-append">
															<button class="btn btn-secondary" style="cursor: text;" type="button">Rp.</button>
														</div>
														<input class="form-control" ng-minlength="8" ng-maxlength="13" id="harga" name="harga" ng-model="harga" ng-pattern="/^\d+$/" placeholder="Input Harga">
													</div>
												</div>
												<div class="form-group row">
													<label for="dokumen" class="col-sm-4 col-form-label text-sm">Dokumen</label>
													<div class="col-sm-8">
														<input type="file" class="form-control form-control-md text-sm" id="dokumen" name="dokumen" ng-model="dokumen">

														<!-- Jika ada file lama, tampilkan link -->
														<div ng-if="dokumen">
															<a ng-href="{{urls}}resources/uploads/dokumen_usulan_ssh/{{dokumen}}" download class="btn btn-sm btn-success mt-2">
																<i class="fas fa-download"></i> Download Dokumen Lama
															</a>
														</div>
													</div>
												</div>
												<div class="form-group row">
													<label for="tautan" class="col-sm-4 col-form-label text-sm">Tautan (Opsional)</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="tautan" name="tautan" ng-model="tautan" placeholder="Input Tautan">
													</div>
												</div>
											</form>
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