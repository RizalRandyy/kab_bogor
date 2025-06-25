<style type="text/css">

	.wrap-1 {
		margin-top: -16px;
		padding-right: 6px;
		padding-left: 6px;
	}

	.card {
		border-radius: 20px;
	}

	.section {
		padding-top: 0px;
	}

	.badge {
		padding: 5px 8px;
		border-radius: 7px;
	}

	.btn-light {
		padding: 10px;
		font-weight: bold;
		font-size: 11px;
		color: #0F3659;
		background-color: #FFFFFF;
		border-radius: 8px;
		border: 1px solid;
		border-color: #C3D3E2;
	}

	#legendInvoice {
		max-height: 150px;
		overflow: auto;
		margin-right: 0px;
		float: right !important;
	}

	/* Scrollbar */
	.articleList {

		&::-webkit-scrollbar {
			width: 3px;
		}

		/* Track */
		&::-webkit-scrollbar-track {
			background: transparent;
			margin-bottom: 15px;
			margin-top: 70px;
		}

		/* Handle */
		&::-webkit-scrollbar-thumb {
			background: #DCDCDC;
		}
	}

	.list-group-item {
	    position: relative;
	    display: block;
	    padding: 0.75rem 1.25rem;
	    margin-bottom: -1px;
	    background-color: #fff;
	    border: 0px solid rgba(0,0,0,.125);
	    border-bottom: 1px solid rgba(0,0,0,.125);
	}

	@media (min-width: 992px) {
	.modal-lg, .modal-xl {
	    max-width: 1200px;
	}

	.sticky-top {
		z-index: 10;
	}

	.badge.badge-info {
	    border: 2px solid;
	    background-color: rgba(100, 153, 233, 0.8);
	    border-color: rgba(100, 153, 233, 1);
	}
	.badge.badge-warning {
	    border: 2px solid;
	    background-color: rgba(252, 84, 4, 0.8);
	    border-color: rgba(252, 84, 4, 1);
	}
	.badge.badge-success {
	    border: 2px solid;
	    background-color: rgba(0, 223, 162, 0.8);
	    border-color: rgba(0, 223, 162, 1);
	}
	.badge.badge-secondary {
	    border: 2px solid;
	    background-color: rgba(101, 40, 247, 0.8);
	    border-color: rgba(101, 40, 247, 1);
	}
	.badge.badge-dark {
	    border: 2px solid;
	    background-color: rgba(25, 29, 33, 0.8);
	    border-color: rgba(25, 29, 33, 1);
	}
	.badge.badge-primary {
	    border: 2px solid;
	    background-color: rgba(252, 84, 64, 0.8);
	    border-color: rgba(252, 84, 64, 1);
	}
</style>
<div ng-controller="<?= $page ?>" id="<?= $page ?>">
	<div id="page">
		<div class="colorlib-services">
			<div class="container">
				<div class="row">
					<div class="col-12 wrap-1">
						<div class="card animate-box">
							<div class="card-body">
								<b style="font-weight: 600; font-size: 24px;">Grafik Fluktuasi Harga</b>
							</div>
						</div>
					</div>
					<div class="col-12 wrap-1">
						<div class="card animate-box">
							<div class="card-header">
							</div>
							<div class="card-body">
								<div class="chartdiv" style="height: 450px"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="colorlib-services colorlib-bg-white" style="padding-top: 1px; padding-left: 1px;">
			<div class="container">
				<div class="row animate-box">
					<div class="col-lg-12 col-md-12 col-sm-12">
						<div class="col-lg-12 col-md-12 col-sm-12" style="margin-top:25px; margin-bottom:25px;">
							<h2 class="text-center animate-box">Daftar Lokasi Survey</h2>
						</div>
						<div class="table-responsive">
							<table class="table table-striped table-md animate-box" style="font-size: 12px;">
								<thead>
									<tr style="font-size:18px">
										<th style="width: 10%;">No</th>
										<th class="text-center" style="width: 80%;">Nama Toko</th>
										<th class="text-center" style="width: 10%;"></th>
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
										<td colspan="3" class="text-center" ng-bind="message"></td>
									</tr>
									<tr>
										<td colspan="3" ng-show="loading">
											<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
											Loading...
										</td>
									</tr>
									<tr ng-hide="loading" dir-paginate="(key, value) in datalokasi|itemsPerPage:itemsPerPage" total-items="total_count" current-page="curPage" pagination-id="paginateID" style="font-size:14px">
										<td ng-bind="key+no"></td>
										<td ng-bind="value.nama_toko"></td>
										<td class="text-center" title="Lokasi">
											<a href="{{value.tautan}}" class="btn btn-danger btn-sm p-1" target="_blank">
												<i class="fas fa-map-marker-alt"></i>&nbsp;
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