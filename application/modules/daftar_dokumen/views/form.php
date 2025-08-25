<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Dokumen > Daftar Dokumen > <?= $title ?></span>
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
											<form enctype="multipart/form-data">
												<div class="form-group row">
													<label for="nama_dokumen" class="col-sm-4 col-form-label text-sm">Nama Dokumen</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="nama_dokumen" name="nama_dokumen" ng-model="nama_dokumen" placeholder="Input Nama Dokumen">
													</div>
												</div>
												<div class="form-group row">
													<label for="id_jenis_dokumen" class="col-sm-4 col-form-label text-sm">Jenis Dokumen</label>
													<div class="col-sm-8">
														<select class="form-control form-control-sm text-sm"
															id="id_jenis_dokumen"
															ng-model="id_jenis_dokumen"
															ng-options="item.id as item.jenis_dokumen for item in listJenisDokumen">
															<option value="">-- Pilih Jenis Dokumen --</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="tahun" class="col-sm-4 col-form-label text-sm">Tahun</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="tahun" name="tahun" ng-model="tahun" ng-maxlength="4" placeholder="Input Tahun">
													</div>
													<div class="col-sm-4"></div>
													<div class="col-sm-8">
														<span ng-show="addReq.tahun.$error.maxlength" style="color: red;">Tahun Maksimal hanya 4 Karakter!</span>
													</div>
												</div>
												<div class="form-group row">
													<label for="deskripsi" class="col-sm-4 col-form-label text-sm">Deskripsi</label>
													<div class="col-sm-8">
														<textarea class="form-control form-control-sm text-sm"
															id="deskripsi"
															name="deskripsi"
															ng-model="deskripsi"
															placeholder="Input Deskripsi"
															rows="4"
															style="resize: vertical;"></textarea>
													</div>
												</div>
												<div class="form-group row">
													<label for="dokumen" class="col-sm-4 col-form-label text-sm">Dokumen</label>
													<div class="col-sm-8">
														<input type="file" class="form-control form-control-md text-sm" id="dokumen" name="dokumen" ng-model="dokumen">

														<!-- Jika ada file lama, tampilkan link -->
														<div ng-if="dokumen">
															<a ng-href="{{urls}}resources/uploads/dokumen/{{dokumen}}" download class="btn btn-sm btn-success mt-2">
																<i class="fas fa-download"></i> Download Dokumen Lama
															</a>
														</div>
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