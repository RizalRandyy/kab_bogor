<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Isian HSPK > Tahun Kegiatan HSPK > <?= $title ?></span>
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
	                                                <label for="idKegiatan" class="col-sm-4 col-form-label text-sm">Kode Kegiatan</label>
	                                                <div class="col-sm-8">
														<select class="form-control" id="idKegiatan" ng-model="idKegiatan">
															<option value="" disabled>Pilih Kode Kegiatan</option>
															<option ng-repeat="option in options" value="{{option.id}}" name="{{option.id}}" id="{{option.id}}">{{option.idKegiatan}} - {{option.UraianKegiatan}} - ({{option.satuan}})</option>
														</select>
													</div>
	                                            </div>
	                                            <div class="form-group row">
	                                                <label for="idJnsBrg" class="col-sm-4 col-form-label text-sm">Tahun Kegiatan</label>
	                                                <div class="input-group col-sm-8">
														<input class="form-control" ng-minlength="4" ng-maxlength="4" id="tahun" name="tahun" ng-model="tahun" ng-pattern="/^\d+$/" placeholder="Input Tahun" >
														
													</div>
	                                                <div class="col-sm-4"></div>
	                                                <div class="col-sm-8">
		                                                <span ng-show="addReq.tahun.$error.maxlength" style="color: red;">Input Tahun 4 Karakter!</span>
		                                                 <span ng-show="addReq.tahun.$error.minlength" style="color: red;">Input Tahun 4 Karakter!</span>
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