<style>
	.table tr td {
		vertical-align: middle !important;
	}

	.loader-img {
		width: 25px !important;
	}
</style>
<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Isian SSH > Tahun Harga</span>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
						<?php if ($users['role_access']['spesifikasi_harga']['accessadd_spesifikasi_harga'] == 'on') { ?>
						<form class="form-inline float-right">
							<div class="mb-2 mr-2">
                                <a href="" class="btn btn-success btn-xl" style="float: right;" title="Import Data" ng-click="show_modal()">
                                	<i class="fas fa-file-excel"></i> Import
                                </a>
                            </div> 
                            <div class="mb-2 mr-2">
                                <a href="" class="btn btn-light btn-xl" style="float: right;" title="Tambah Data" ng-click="tambah()">
                                	<i class="fas fa-plus"></i> Tambah
                                </a>
                            </div> 
						</form>
						<?php } ?>
					</div>
				</div>
				<div class="card card-statistic-2">
					<div class="card-stats p-3">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="table-responsive">
									<table class="table table-striped table-md" style="font-size: 12px;">
										<thead>
											<tr>
												<th>No</th>
												<th class="text-center">Kode Item</th>
												<th class="text-center">Nama Kelompok</th>
												<th class="text-center">Nama Item</th>
												<th class="text-center">Spesifikasi Item</th>
												<th class="text-center">Satuan</th>
												<th class="text-center">Tahun</th>
												<th class="text-center">Harga</th>
												<?php if ($users['role_access']['spesifikasi_harga']['accessedit_spesifikasi_harga'] == 'on' || $users['role_access']['spesifikasi_harga']['accessdelete_spesifikasi_harga'] == 'on') { ?>
													<th class="text-center"></th>
												<?php } ?>
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
												<td ng-bind="value.UraianKelompok"></td>
												<td ng-bind="value.NamaJenis"></td>
												<td ng-bind="value.UraianSpesifikasi"></td>
												<td ng-bind="value.satuan"></td>
												<td class="text-center" ng-bind="value.TahunHarga"></td>
												<td class="text-right" ng-bind="value.harga"></td>
												<?php if ($users['role_access']['spesifikasi_harga']['accessedit_spesifikasi_harga'] == 'on' || $users['role_access']['spesifikasi_harga']['accessdelete_spesifikasi_harga'] == 'on') { ?>
													<td class="text-center" style="white-space: nowrap;">
														<?php if ($users['role_access']['spesifikasi_harga']['accessedit_spesifikasi_harga'] == 'on') { ?>
															<a href="" class="btn btn-success btn-sm p-1" title="Edit: {{value.kodeKelompok}}" ng-click="edit(value)">
																<i class="fas fa-edit"></i>&nbsp;
															</a>
														<?php }?>
														<?php if ($users['role_access']['spesifikasi_harga']['accessdelete_spesifikasi_harga'] == 'on') { ?>
															<a href="" class="btn btn-danger btn-sm p-1" title="Delete: {{value.kodeKelompok}}" ng-click="delete(value)">
																<i class="fas fa-trash"></i>&nbsp;
															</a>
														<?php }?>
													</td>
												<?php }?>
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
	<div class="modal fade" id="modal_import" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mdlInviteLabel">Import Data SSH & SBU</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close_modal_fleet()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body mt-2">
					<form ng-submit="import()" class="mt-4">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">
								<div class="row p-0">
									<div class="col-md-6 col-lg-12">
                                        <form>
                                            <div class="form-group row">
                                                <label for="idSpesifikasi" class="col-sm-4 col-form-label text-sm">Template</label>
                                                <div class="col-sm-8">
													<div class="input-group-append">
														<button class="btn btn-danger" style="cursor: pointer;" type="button" ng-click="download_template()">Download Template Data Isian SSH</button>
													</div>
												</div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="harga" class="col-sm-4 col-form-label text-sm">Upload File</label>
                                                <div class="input-group col-sm-8">
													<input type="file" class="form-control" name="template" id="template" rows="8" ng-model="template" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
													<div><span>Max Row 1000 data (jika data lebih dari 1000 row silahkan dibagi menjadi beberapa bagian)</span></div>								
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
									<button class="btn btn-dark" data-dismiss="modal" type="button"> Kembali <i class="fas fa-undo-alt"></i>
									</button>&nbsp;&nbsp;
								</div>
							</div>
						</div>
					</form>
				</div>
				<!-- <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
				</div> -->
			</div>
		</div>
	</div>
</div>