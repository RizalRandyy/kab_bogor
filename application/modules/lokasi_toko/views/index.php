<style>
	.table tr td {
		vertical-align: middle !important;
	}

	.loader-img {
		width: 25px !important;
	}

	#map {
		height: 200px;
		width: 100%;
		margin-bottom: 20px;
		border-radius: 10px;
		border: 1px solid #ddd;
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
						<span>Lokasi Survey</span>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
						<?php if ($users['role_access']['lokasi_toko']['accessadd_lokasi_toko'] == 'on') { ?>
							<form class="form-inline float-right">
								<div class="mb-2 mr-2">
									<a href="" class="btn btn-light btn-xl" style="float: right;" title="Tambah Data" ng-click="tambah()">
										<i class="fas fa-plus"></i> Tambah
									</a>
								</div>
							</form>
						<?php } ?>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12	">
						<div id="map"></div>
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
												<th class="text-center">Nama Toko</th>
												<th class="text-center">Tautan</th>
												<?php if ($users['role_access']['lokasi_toko']['accessedit_lokasi_toko'] == 'on' || $users['role_access']['lokasi_toko']['accessdelete_lokasi_toko'] == 'on') { ?>
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
												<td ng-bind="value.nama_toko"></td>
												<td><a href="{{value.tautan}}" target="_blank" ng-bind="'Link Tautan'"></a></td>
												<?php if ($users['role_access']['lokasi_toko']['accessedit_lokasi_toko'] == 'on' || $users['role_access']['lokasi_toko']['accessdelete_lokasi_toko'] == 'on') { ?>
													<td class="text-center" style="white-space: nowrap;">
														<?php if ($users['role_access']['lokasi_toko']['accessedit_lokasi_toko'] == 'on') { ?>
															<a href="" class="btn btn-success btn-sm p-1" title="Edit: {{value.nama_toko}}" ng-click="edit(value)">
																<i class="fas fa-edit"></i>&nbsp;
															</a>
														<?php } ?>
														<?php if ($users['role_access']['lokasi_toko']['accessdelete_lokasi_toko'] == 'on') { ?>
															<a href="" class="btn btn-danger btn-sm p-1" title="Delete: {{value.nama_toko}}" ng-click="delete(value)">
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


<!-- <script>
	document.addEventListener("DOMContentLoaded", function() {
		var map = L.map('map').setView([-6.9297534, 107.6135855], 15.6);

		L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'

		}).addTo(map);

		L.marker([-6.9311947, 107.6130318]).addTo(map)
			.bindPopup("Universitas Langlanbuana")
			.openPopup();

		L.marker([-6.9289822, 107.6078851]).addTo(map)
			.bindPopup("Universitas Pasundan Lengkong")
			.openPopup();
	});
</script> -->