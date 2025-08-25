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
						<span>Dokumen > Daftar Dokumen</span>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
						<?php if ($users['role_access']['daftar_dokumen']['accessadd_daftar_dokumen'] == 'on') { ?>
							<form class="form-inline float-right">
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
					<div class="card-stats p-4">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12">

								<div class="table-responsive">
									<table class="table table-striped table-md" style="font-size: 12px;">
										<thead>
											<tr>
												<th>No</th>
												<th class="text-center">Nama Dokumen</th>
												<th class="text-center">Jenis</th>
												<th class="text-center">Tahun</th>
												<th class="text-center">Deskripsi</th>
												<th class="text-center">Download File</th>
												<?php if ($users['role_access']['daftar_dokumen']['accessedit_daftar_dokumen'] == 'on' || $users['role_access']['daftar_dokumen']['accessdelete_daftar_dokumen'] == 'on') { ?>
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
												<td colspan="7" class="text-center" ng-bind="message"></td>
											</tr>
											<tr>
												<td colspan="7" ng-show="loading">
													<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
													Loading...
												</td>
											</tr>
											<tr ng-hide="loading" dir-paginate="(key, value) in data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="curPage" pagination-id="paginateID">
												<td ng-bind="key+no"></td>
												<td ng-bind="value.nama_dokumen"></td>
												<td ng-bind="value.nama_jenis_dokumen"></td>
												<td ng-bind="value.tahun"></td>
												<td ng-bind="value.deskripsi"
													style="max-width:250px; 
           											white-space:nowrap; 
           											overflow:hidden; 
           											text-overflow:ellipsis;">
												</td>
												<td class="text-center">
													<a ng-if="value.dokumen"
														class="btn btn-primary btn-sm"
														ng-href="<?= base_url('resources/uploads/dokumen/') ?>{{value.dokumen}}">
														<i class="fas fa-download"></i> Download
													</a>
													<span ng-if="!value.dokumen">-</span>
												</td>

												<?php if ($users['role_access']['daftar_dokumen']['accessedit_daftar_dokumen'] == 'on' || $users['role_access']['daftar_dokumen']['accessdelete_daftar_dokumen'] == 'on') { ?>
													<td class="text-center" style="white-space: nowrap;">
														<?php if ($users['role_access']['daftar_dokumen']['accessedit_daftar_dokumen'] == 'on') { ?>
															<a href="" class="btn btn-success btn-sm p-1" title="Edit: {{value.idOpd}}" ng-click="edit(value)">
																<i class="fas fa-edit"></i>&nbsp;
															</a>
														<?php } ?>
														<?php if ($users['role_access']['daftar_dokumen']['accessdelete_daftar_dokumen'] == 'on') { ?>
															<a href="" class="btn btn-danger btn-sm p-1" title="Delete: {{value.idOpd}}" ng-click="delete(value)">
																<i class="fas fa-trash"></i>&nbsp;
															</a>
														<?php } ?>
													</td>
												<?php } ?>
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
</div>