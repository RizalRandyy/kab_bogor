<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Data Master > Jenis Item > <?= $title ?></span>
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
	                                                <label for="idKelompokItem" class="col-sm-4 col-form-label text-sm">Kode Kelompok Item</label>
	                                                <div class="col-sm-8">
														<select class="form-control" id="idKelompokItem" ng-model="idKelompokItem">
															<option value="" disabled>Pilih Kelompok Item</option>
															<option ng-repeat="option in options" value="{{option.id}}" name="{{option.id}}" id="{{option.id}}">{{option.IdKelItem}} - {{option.UraianKelompok}} - ({{option.tipe}})</option>
														</select>
													</div>
	                                            </div>
	                                            <div class="form-group row">
	                                                <label for="idJnsBrg" class="col-sm-4 col-form-label text-sm">Kode Jenis Barang</label>
	                                                <div class="col-sm-8">
	                                                    <input type="text" class="form-control form-control-sm text-sm" id="idJnsBrg" name="idJnsBrg" ng-model="idJnsBrg" ng-maxlength="6" placeholder="Input Kode Jenis Barang">
	                                                </div>
	                                                <div class="col-sm-4"></div>
	                                                <div class="col-sm-8">
		                                                <span ng-show="addReq.idJnsBrg.$error.maxlength" style="color: red;">Kode Item Maksimal hanya 6 Karakter!</span>
		                                            </div>
	                                            </div>
	                                            <div class="form-group row">
	                                                <label for="nama_jns" class="col-sm-4 col-form-label text-sm">Nama Jenis Item</label>
	                                                <div class="col-sm-8">
	                                                	<input type="text" class="form-control form-control-sm text-sm" id="nama_jns" name="nama_jns" ng-model="nama_jns" placeholder="Input Uraian Kelompok Item">
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