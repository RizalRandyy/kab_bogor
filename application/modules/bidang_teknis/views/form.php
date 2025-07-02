<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Data Master > Bidang Teknis > <?= $title ?></span>
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
													<label for="idOpd" class="col-sm-4 col-form-label text-sm">OPD</label>
													<div class="col-sm-8">
														<select class="form-control" id="idOpd" ng-model="idOpd">
															<option value="" disabled>Pilih OPD</option>
															<option ng-repeat="option in options" value="{{option.idOpd}}" name="{{option.idOpd}}" id="{{option.idOpd}}">{{option.namaOpd}}</option>
														</select>
													</div>
												</div>
												<div class="form-group row">
													<label for="idBidangTeknis" class="col-sm-4 col-form-label text-sm">Kode Bidang Teknis</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="idBidangTeknis" name="idBidangTeknis" ng-model="idBidangTeknis" ng-maxlength="6" placeholder="Input Kode Bidang Teknis">
													</div>
													<div class="col-sm-4"></div>
													<div class="col-sm-8">
														<span ng-show="addReq.idBidangTeknis.$error.maxlength" style="color: red;">Kode Bidang Teknis Maksimal hanya 6 Karakter!</span>
													</div>
												</div>
												<div class="form-group row">
													<label for="namaBidangTeknis" class="col-sm-4 col-form-label text-sm">Nama Bidang Teknis</label>
													<div class="col-sm-8">
														<input type="text" class="form-control form-control-sm text-sm" id="namaBidangTeknis" name="namaBidangTeknis" ng-model="namaBidangTeknis" placeholder="Input Nama Bidang Teknis">
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