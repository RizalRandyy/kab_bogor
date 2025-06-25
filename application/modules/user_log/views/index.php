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
						<span>Management > User Log</span>
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
                                                <th class="text-center">Full Name</th>
                                                <th class="text-center">IP</th>
                                                <th class="text-center">Browser</th>
                                                <th class="text-center">Page</th>
                                                <th class="text-center">Access Time</th>
											</tr>
										</thead>
										<tbody>
											<tr style="background-color: rgba(0, 0, 0, 0.02);">
												<td ng-repeat="(a,b) in table_header" class="no-padding px-1">
													<button type="button" class="btn btn-success btn-sm text-center" ng-if="b == 'reset'" title="Reset Search" ng-click="reset('master')">
                                                        <i class="fas fa-redo"></i>
                                                    </button>
                                                    <input ng-if="b != 'reset' && b != 'date'" type="text" class="form-control no-margin form-filter " ng-model="search_Method.val[b]" ng-change="searchMethod(b, search_Method.val[b])" ng-model-options="{debounce: 2000}">
                                                    <input ng-if="b == 'date'" type="date" class="form-control no-margin form-filter " id="date" name="date" ng-model="search_Method.val[b]" ng-value="dates" ng-change="searchMethod(b, search_Method.val[b])" ng-model-options="{debounce: 2000}">
												</td>
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
												 <td ng-bind="key+no" style="font-size: 10px;" class="text-center"></td>
                                                <td ng-bind="value.name"></td>
                                                <td ng-bind="value.ip"></td>
                                                <td ng-bind="value.browser"></td>
                                                <td ng-bind="value.controller_name"></td>
                                                <td ng-bind="value.access_time"></td>
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