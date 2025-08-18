<style>
	.table tr td {
		vertical-align: middle !important;
	}

	.loader-img {
		width: 25px !important;
	}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.3.0/exceljs.min.js"></script>
<div class="main-content" ng-controller="<?= $page ?>" id="<?= $page ?>">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Isian HSPK > Tahun Kegiatan</span>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
						<?php if ($users['role_access']['kegiatan_asb_detail']['accessadd_kegiatan_asb_detail'] == 'on') { ?>
							<form class="form-inline float-right">
								<div class="mb-2 mr-2">
									<a href="" class="btn btn-light btn-xl" style="float: right;" title="Tambah Data" ng-click="tambah()">
										<i class="fas fa-plus"></i> Tambah
									</a>
								</div>
							</form>

							<form class="form-inline float-right">
								<div class=" mb-2 mr-2">
									<a href="" class="btn btn-success btn-xl" style="float: right;" title="Import Data" ng-click="show_modal()">
										<i class="fas fa-file-excel"></i> Import
									</a>
								</div>
							</form>
						<?php } ?>
					</div>
				</div>
				<div class="card card-statistic-2">
					<div class="card-stats p-4">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">

								<div class="table-responsive">
									<table class="table table-striped table-md" style="font-size: 12px;">
										<thead>
											<tr>
												<th>No</th>
												<th class="text-center">Kode Item</th>
												<th class="text-center">Bidang</th>
												<th class="text-center">Uraian Kegiatan</th>
												<th class="text-center">Satuan</th>
												<th class="text-center">Tahun Kegiatan</th>
												<th class="text-center">Harga Kegiatan</th>
												<th class="text-center"></th>
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
												<td ng-bind="value.namaOpd"></td>
												<td ng-bind="value.UraianKegiatan"></td>
												<td ng-bind="value.satuan"></td>
												<td ng-bind="value.tahunASB"></td>
												<td class="text-right" ng-bind="value.harga"></td>
												<td class="text-center" style="white-space: nowrap;">
													<?php if ($users['role_access']['kegiatan_asb_detail']['accessedit_kegiatan_asb_detail'] == 'on') { ?>
														<a href="" class="btn btn-success btn-sm p-1" title="Edit: {{value.kodeKelompok}}" ng-click="edit(value)">
															<i class="fas fa-edit"></i>&nbsp;
														</a>
													<?php } ?>
													<?php if ($users['role_access']['kegiatan_asb_detail']['accessdelete_kegiatan_asb_detail'] == 'on') { ?>
														<a href="" class="btn btn-danger btn-sm p-1" title="Delete: {{value.kodeKelompok}}" ng-click="delete(value)">
															<i class="fas fa-trash"></i>&nbsp;
														</a>
													<?php } ?>
													<?php if ($users['role_access']['kegiatan_hspk_detail']['accessdelete_kegiatan_hspk_detail'] == 'on') { ?>
														<button ng-click="exportExcelById(value.id)" class="btn btn-success btn-sm">
															<i class="fa fa-file-excel"></i> Export Excel
														</button>
													<?php } ?>
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
	<div class="modal fade" id="modal_import" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mdlInviteLabel">Import Data ASB</h5>
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
														<button class="btn btn-danger" style="cursor: pointer;" type="button" ng-click="download_template()">Download Template Data Isian ASB Detail</button>
													</div>
												</div>
											</div>
											<div class="form-group row">
												<label for="harga" class="col-sm-4 col-form-label text-sm">Upload File</label>
												<div class="input-group col-sm-8">
													<input type="file" id="template" name="template" class="form-control" accept=".xls,.xlsx">
													<small class="form-text text-muted">
														Hanya file Excel (.xls, .xlsx) sesuai format export.
													</small>
													<div><span>Max Row 1000 data (jika data lebih dari 1000 row silahkan dibagi menjadi beberapa bagian)</span></div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-12 col-lg-12 text-right">
								<div class="form-group">
									<button type="button" class="btn btn-info" ng-click="import()"> Simpan <i class="fas fa-save"></i></button>&nbsp;&nbsp;
									<!-- <button class="btn btn-info" type="submit"> Simpan <i class="fas fa-save"></i></button>&nbsp;&nbsp; -->
									<button class="btn btn-dark" data-dismiss="modal" type="button"> Kembali <i class="fas fa-undo-alt"></i></button>&nbsp;&nbsp;
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