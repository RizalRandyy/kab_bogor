<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Lokasi Survey > <?= $title ?></span>
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
	                                                <label for="nama_toko" class="col-sm-4 col-form-label text-sm">Nama Toko</label>
	                                                <div class="col-sm-8">
	                                                    <input type="text" class="form-control form-control-sm text-sm" id="nama_toko" name="nama_toko" ng-model="nama_toko" ng-maxlength="6" placeholder="Input Nama Toko">
	                                                </div>
	                                            </div>
	                                            <div class="form-group row">
	                                                <label for="tautan" class="col-sm-4 col-form-label text-sm">Tautan</label>
	                                                <div class="col-sm-8">
	                                                	<input type="text" class="form-control form-control-sm text-sm" id="tautan" name="tautan" ng-model="tautan" placeholder="Input Link Tautan">
	                                                </div>
	                                            </div>
	                                            <div class="form-group row">
	                                                <label for="koordinat" class="col-sm-4 col-form-label text-sm">Koordinat</label>
	                                                <div class="col-sm-8">
	                                                	<input type="text" class="form-control form-control-sm text-sm" id="koordinat" name="koordinat" ng-model="koordinat" placeholder="contoh: -6.595, 106.819">
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