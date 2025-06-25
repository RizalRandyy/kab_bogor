<style>
	.table tr td {
		vertical-align: middle !important;
	}
	.form-select{
		display: block;
		width: 100%;
		/*height: calc(1.5em + 0.75rem + 2px);*/
		padding: 0.375rem 0.75rem;
		font-size: 1rem;
		font-weight: 400;
		line-height: 1.5;
		color: #495057;
		background-color: #fff;
		background-clip: padding-box;
		border: 1px solid #ced4da;
		border-radius: 0.25rem;
		transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
	}

	.inputFile {
		width: 0.1px;
		height: 0.1px;
		opacity: 0;
		overflow: hidden;
		position: absolute;
		z-index: -1;
	}

	.input-file-banner {
		border: 1px #ced4da dashed;
		text-align: center;
		padding: 6.5rem 1rem !important;
		height: 150px;
		position: relative;
		overflow: hidden;
		border-radius: 10px;
	}

	.input-file-sm {
		padding: 2rem 1rem;
	}
	.inputFile+label {
		color: #5E72E4;
		cursor: pointer;
		font-style: normal;
		font-size: 1.5em;
		display: inline-block;
	}

	.inputFile-banner+label :hover{
		color: black;
		cursor: pointer;
	}

	.preview-photo {
		position: absolute;
		top: 0;
		left: 0;
	}
	.preview-photo-fill {
		width: 100%;
		height: 100%;
	}
	.img-fluid {
		max-width: 100%;
		/*height: auto;*/
	}

</style>
<div class="main-content" ng-controller="user_manage" id="user_manage">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card-bg"></div>
				<div class="row mt-2">
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:25px; margin-bottom:25px;">
						<h3><?= $title ?></h3>
						<span>Manajement User > User</span>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6" style="color:white; margin-top:37px;">
						<?php if ($users['role_access']['user_manage']['accessadd_user_manage'] == 'on') { ?>
						<form class="form-inline float-right">
							<div class="mb-2 mr-2">
                                <a href="" class="btn btn-light btn-xl" style="float: right;" title="Tambah Data" ng-click="show_modal('add')">
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
												<th>#</th>
												<th class="text-center">Nama Lengkap</th>
												<th class="text-center">Email</th>
												<th class="text-center">Login Terkahir</th>
												<th class="text-center">Role</th>
												<th class="text-center">Status</th>
												<?php if ($users['role_access']['user_manage']['accessedit_user_manage'] == 'on' || $users['role_access']['user_manage']['accessdelete_user_manage'] == 'on') { ?>
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
													<input ng-if="b != 'none' && b != 'reset'" type="text" class="form-control no-margin form-filter " ng-model="search_Method.val[b]" ng-change="searchMethod(b, search_Method.val[b])" ng-model-options="{debounce: 2000}">
												</td>
												<td class="no-padding px-1"></td>
												<td class="no-padding px-1"></td>
											</tr>   
											<tr ng-show="message != null">
												<td colspan="4" class="text-center" ng-bind-html="message"></td>
											</tr>
											<tr>
												<td class="text-center" colspan="6" ng-show="loading">
													<img class="loader-img" src="<?= base_url('assets/img/loadertsel.gif') ?>" alt="loader">
													Loading...
												</td>
											</tr>
											<tr ng-hide="loading" dir-paginate="(key, value) in data|itemsPerPage:itemsPerPage" total-items="total_count" current-page="curPage" pagination-id="paginateID">
												<td class="text-center" ng-bind="key+pageno"></td>
												<td class="text-center" ng-bind="value.full_name"></td>
												<td class="text-center" ng-bind="value.email"></td>
												<td class="text-center" ng-bind="value.last_login"></td>
												<td class="text-center" data-toggle="modal" data-type="family-form" ng-bind="value.role_name"></td>
												<td class="text-center">
													<span class="badge badge-success" ng-show="value.status == '1'">Active</span>
													<span class="badge badge-danger" ng-show="value.status == '0'">Non Active</span>
												</td>
												<?php if ($users['role_access']['user_manage']['accessedit_user_manage'] == 'on' || $users['role_access']['user_manage']['accessdelete_user_manage'] == 'on') { ?>
													<td class="text-center p-0">
														<?php if ($users['role_access']['user_manage']['accessedit_user_manage'] == 'on') { ?>
															<button class="btn btn-primary btn-sm p-1" ng-click="show_pwd(value)"><i class="fas fa-lock fa-sm"></i></button>
															<button class="btn btn-warning btn-sm p-1" ng-click="show_modal('edit',value)"><i class="fas fa-pencil-alt fa-sm"></i></button>
														<?php }?>
														<?php if ($users['role_access']['user_manage']['accessdelete_user_manage'] == 'on') { ?>
														<button class="btn btn-danger btn-sm p-1" ng-click="delete_user(value.id)"><i class="fas fa-trash fa-sm"></i></button>
														<?php }?>
													</td>
												<?php }?>
											</tr>
										</tbody>
									</table>
								</div>

								<dir-pagination-controls direction-links="true" pagination-id="paginateID" boundary-links="true" on-page-change="getUsers(newPageNumber)">
								</dir-pagination-controls>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php if ($users['role_access']['user_manage']['accessedit_user_manage'] == 'on') { ?>

	<div class="modal fade" id="modal_users" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="mdlInviteLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="mdlInviteLabel">{{modal_title}}</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close" ng-click="close_modal_fleet()">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body mt-2">
					<form>
						<div class="form-group row">
							<div class="col-4"></div>
						</div>
						<div class="form-group row">
							<div class="col-md-2">
								<label for="full_name" class="col-form-label">Nama Lengkap <span class="text-danger">*</span> </label>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control form-control-sm text-sm" aria-describedby="full_name" ng-model="full_name">
							</div>
							<div class="col-md-2">
								<label for="nick_name" class="col-form-label">Nama Panggilan<span class="text-danger">*</span></label>
							</div>
							<div class="col-md-4">
								<input type="text" class="form-control form-control-sm text-sm" aria-describedby="nick_name" ng-model="nick_name">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-2">
								<label for="email" class="col-form-label">Email<span class="text-danger">*</span></label>
							</div>
							<div class="col-md-4">
								<input type="email" class="form-control form-control-sm text-sm" aria-describedby="email" ng-model="email">
							</div>
							<div class="col-md-2">
								<label for="phone" class="col-form-label">No Telepon<span class="text-danger">*</span></label>
							</div>
							<div class="col-md-4">
								<input class="form-control form-control-sm" id="phone" ng-model="phone" name="phone" ng-model="phone" placeholder="No HP (Contoh: 62812344321)">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-2">
								<label for="role_user" class="col-form-label">Role User<span class="text-danger">*</span></label>
							</div>
							<div class="col-md-4">
								<select ng-model="role_user" class="form-select form-control-sm text-sm">
									<option value="">- Select role -</option>
									<option ng-repeat="(key, value) in temp_role_user" value="{{value.id}}">{{value.name}}
									</option>
								</select>
							</div>
							<div class="col-md-2">
								<label for="status" class="col-form-label">status<span class="text-danger">*</span></label>
							</div>
							<div class="col-md-4">
								<select ng-model="status" class="form-select form-control-sm text-sm">
									<option value="">- Select status -</option>
									<option value="1">Active</option>
									<option value="0">Non Active </option>
								</select>
							</div>
						</div>
						<div class="form-group row" ng-show="modal_title == 'Tambah User'">
							<div class="col-md-2">
								<label for="password" class="col-form-label">Password</label>
							</div>
							<div class="col-md-10">
								<input type="password" id="password" class="form-control form-control-sm text-sm" aria-describedby="password" ng-model="password">
							</div>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal" ng-click="close_modal_user()">Kembali</button>
					<button type="button" class="btn btn-primary" ng-click="save_user()">Simpan</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="change_pwd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Ganti Password {{full_name_chg}}?</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="row mb-2">
						<input class="form-control password" type="password" name="password" placeholder="Password Baru" required style="border: 1px solid #007BFF;" id="chg_password_manage">
					</div>
					<div class="row">
						<input class="form-control confirm-password" type="password" name="confirm-password" id="confirm_password_manage" placeholder="Konfirmasi Password" required style="border: 1px solid #007BFF;">
						<div class="warning mb-2">
							<small class="text-danger"> password do not match</small>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Kemabali</button>
					<button type="button" class="btn btn-primary" ng-click="update_password()">Simpan</button>
				</div>
			</div>
		</div>
	</div>
	<?php }?>