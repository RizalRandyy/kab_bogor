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
<div class="main-content" ng-controller="profile" id="profile">
	<section class="section">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="card card-statistic-2">
					<div class="card-stats p-3">
						<div class="row mt-2">
							<div class="col-lg-6 col-md-6 col-sm-6 ">
								<h6><i class="fas fa-user-tag"></i> Profile</h6>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-lg-12 col-md-12 col-sm-12" ng-init="edit_user('<?= $id_user?>')">
				<div class="card card-statistic-2">
					<div class="card-stats p-3">
						<div class="row mt-2">
							<div class="col-lg-12 col-md-12 col-sm-12 ">
								
								<form>
									<div class="form-group row">
										<!-- <label for="photo" class="col-3">Image</label> -->
										<div class="col-5"></div>
									</div>
									<div class="form-group row">
										<div class="col-md-2">
											<label for="full_name" class="col-form-label">Nama Lengkap <span class="text-danger">*</span> </label>
										</div>
										<div class="col-md-4">
											<input type="text" id="full_name" class="form-control form-control-sm text-sm" aria-describedby="full_name" ng-model="full_name">
										</div>
										<div class="col-md-2">
											<label for="nick_name" class="col-form-label">Nama Panggilan<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-4">
											<input type="text" id="nick_name" class="form-control form-control-sm text-sm" aria-describedby="nick_name" ng-model="nick_name">
										</div>
									</div>
									<div class="form-group row">
										<div class="col-md-2">
											<label for="email" class="col-form-label">Email<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-4">
											<input type="email" id="email" class="form-control form-control-sm text-sm" aria-describedby="email" ng-model="email">
										</div>
										<div class="col-md-2">
											<label for="phone" class="col-form-label">No Telepon<span class="text-danger">*</span></label>
										</div>
										<div class="col-md-4">
											<!-- <input type="number" id="phone" class="form-control form-control-sm text-sm" aria-describedby="phone" > -->
											<div class="input-group  input-group-sm">
												<div class="input-group-append">
													<button class="btn btn-danger" style="cursor: text;" type="button">+62</button>
												</div>
												<input class="form-control form-control-sm" id="phone" ng-model="phone" name="phone" placeholder="Example: 81234432112">
											</div>
											<small class="text-warning">Format: 81234432112 (tanpa diawali 62 atau 0)</small>
										</div>
									</div>
								</form>
							</div>
							<div class="col-12 col-md-12 col-lg-12 text-right mt-5">
								<div class="form-group">
									<a class="btn btn-dark m-1" type="button" href="<?= base_url('dashboard') ?>">
										Cancel
									</a>
									<a class="btn btn-primary btn-submit m-1" type="button" ng-click="save_user()" style="color: white;">
										Submit
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>