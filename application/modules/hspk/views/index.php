<style>
	.table tr td {
		vertical-align: middle !important;
	}

	.loader-img {
		width: 25px !important;
	}

	@media (min-width: 992px) {
	.modal-lg, .modal-xl {
	    max-width: 1200px;
	}
</style>
<div ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section" style="margin-bottom:125px">
		<div class="row animate-box">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card card-statistic-2">
					<div class="card-stats p-5">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:25px; margin-bottom:25px;">
									<h3 class="text-center">HARGA SATUAN PEKERJAAN (HSP) <br>KONSTRUKSI UMUM</h3>
								</div>
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
											<tr style="background-color: rgba(0, 0, 0, 0.02);">
												<td ng-repeat="(a,b) in table_header" class="no-padding px-1">
													<button type="button" class="btn btn-success btn-sm text-center" ng-if="b == 'reset'" title="Reset Search" ng-click="reset('master')">
														<i class="fas fa-redo"></i>
													</button>
													<input ng-if="b != 'reset'" type="text" class="form-control no-margin form-filter " ng-model="search_Method.val[b]" ng-change="searchMethod(b, search_Method.val[b])" ng-model-options="{debounce: 2000}">
												</td>
												<td class="no-padding px-1"></td>
												<td class="no-padding px-1"></td>
											</tr> 
											<tr ng-show="message != null">
												<td colspan="6" class="text-center" ng-bind="message"></td>
											</tr>
											<tr>
												<td colspan="6" ng-show="loading">
													<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
													Loading...
												</td>
											</tr>
											<tr ng-hide="loading" dir-paginate="(key, value) in data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="curPage" pagination-id="paginateID">
												<td ng-bind="key+no"></td>
												<td ng-bind="value.kodeKelompok"></td>
												<td ng-bind="value.UraianKegiatan"></td>
												<td ng-bind="value.satuan"></td>
												<td class="text-center" ng-bind="value.tahunPekerjaan"></td>
												<td class="text-right" ng-bind="value.harga"></td>
												<td class="text-center" style="white-space: nowrap;">
													<a href="" class="btn btn-info btn-sm p-1" title="View: {{value.kodeKelompok}}" ng-click="show_modal(value.id)">
															<i class="fas fa-eye"></i>&nbsp;
														</a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

								<dir-pagination-controls direction-links="true" pagination-id="paginateID" boundary-links="true" on-page-change="getData(newPageNumber)">
								</dir-pagination-controls>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<div class="modal fade" id="modal_detail" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mdlInviteLabel">Analisa Harga Satuan Pekerjaan</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close_modal_fleet()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body mt-2">
					<form id="addReq" name="addReq" class="mt-4">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="row p-0">
									<div class="col-md-12 col-lg-12">
                                        <form>
                                            <div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12">
													
													<div class="table-responsive">
														<table class="table table-striped table-md">
															<thead>
																<tr>
																	<th class="text-center" style="background-color: #AFEEEE;" colspan="8" ng-bind="viewKegiatan"></th>
																</tr>
																<tr>
																	<th class="text-center">Kode</th>
																	<th class="text-center">Kelompok</th>
																	<th class="text-center">Jenis</th>
																	<th class="text-center">Uraian</th>
																	<th class="text-center">Satuan</th>
																	<th class="text-center">Harga</th>
																	<th class="text-center">Banyak</th>
																	<th class="text-center">Total</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td class="text-center" colspan="2" ng-show="loading">
																		<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
																		Loading...
																	</td>
																</tr>
																<tr ng-hide="loading" ng-repeat="(key,data) in tableKelompok">
																	<td class="text-nowrap" ng-bind="data.kodeKelompok">
																	</td>
																	<td ng-bind="data.UraianKelompok">
																	</td>
																	<td ng-bind="data.NamaJenis">
																	</td>
																	<td class="text-nowrap" ng-bind="data.UraianSpesifikasi">
																	</td>
																	<td ng-bind="data.satuan">
																	</td>
																	<td class="text-right" ng-bind="data.harga">
																	</td>
																	<td class="text-center" ng-bind="data.banyak">
																	</td>
																	<td class="text-right" ng-bind="data.total">
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
								<div class="form-group row" style="font-size:18px; font-weight: 900;">
									<div class="col-sm-9">
										<span type="text-right" ng-bind="'Total'"></span>
									</div>
									<div class="col-sm-3">
										<span type="text-right" ng-bind="total"></span>
									</div>
								</div>
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