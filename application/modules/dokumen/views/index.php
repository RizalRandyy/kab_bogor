<style>
	.table tr td {
		vertical-align: middle !important;
	}

	.loader-img {
		width: 25px !important;
	}

	@media (min-width: 992px) {

		.modal-lg,
		.modal-xl {
			max-width: 1200px;
		}
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
									<h3 class="text-center">DAFTAR DOKUMEN</h3>
								</div>
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
												<th></th>
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
														ng-href="<?= base_url('dokumen/download/') ?>{{value.dokumen}}">
														<i class="fas fa-download"></i> Download
													</a>
													<span ng-if="!value.dokumen">-</span>
												</td>

												<td class="text-center" style="white-space: nowrap;">
													<button type="button"
														class="btn btn-info btn-sm p-3"
														title="Show: {{value.id}}"
														ng-click="lihat(value)">
														<i class="fas fa-eye"></i>&nbsp; Lihat
													</button>
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
</div>